<?php

require_once 'EcommerceController.php';

class Cron extends EcommerceController {

    public function __construct() {
        $this->__need_authentication = false;
        $this->__record_active_time = false;
        parent::__construct();

        if (!DEBUG && !$this->isLocalRequest()) {
            exit;
        }
    }

    public function clearSandboxes() {
        $users = $this->____load_model('UserModel')->search();
        $expire = gmdate('Y-m-d H:i:s', time() - $this->__sessionLifeTime);
        foreach ($users as $user) {
            $this->logDebug('\n\rChecking [' . $user->user_name . '] to stop sandboxes ...');
            if ($this->__get_feature('allow_sandbox_live', $user->user_id)) {
                continue;
            }
            if ($user->last_active_time && $user->last_active_time > $expire) {
                continue;
            }

            $workspaces = $this->__get_option_model()->get('ws', $user->user_id);
            if ($workspaces && strlen($workspaces)) {
                $workspaces = json_decode($workspaces, true);
                if ($workspaces && sizeof($workspaces)) {
                    $this->logDebug(sizeof($workspaces) . ' workspace(s) found.');
                    foreach ($workspaces as $workspace) {
                        if (isset($workspace['ws_domain']) && $workspace['ws_domain']) {
                            $this->getSandboxServer()->stop($workspace['ws_domain']);
                            $this->logInfo($workspace['ws_domain'] . ' has been stopped.');
                        }
                    }
                }
            }
        }
    }

    //[2015-04-06] Cron function that checks expiration of payment
    public function checkout() {
        $users = $this->____load_model('UserModel')->search(array('status' => 'active', 'NON-FREE-PLAN' => true));
        $subscriptionModel = $this->____load_model('SubscriptionModel');
        $today = strtotime(gmdate('Y-m-d'));
        foreach ($users as $user) {
            $this->logDebug('Checking ' . $user->user_name . ' for trial & payment expiration ...');
            $subscription = $subscriptionModel->entity($user->subscription_id);
            if (!$subscription) { //impossible, but problem if there's no subscription object
                $this->logDebug($user->user_name . ' has invalid subscription.');
                //Should report to the adminstrator or suspend the account
                continue;
            }

            if ($user->trial_taken == TRIAL_BEING_TAKEN) {  //if the user under trial
                $expiringDays = (strtotime($user->trial_end_date) - $today) / (3600 * 24);
                $this->logDebug($user->user_name . " has $expiringDays days to expire.");
                if ($expiringDays <= 0) { //if trial period is over
                    $user->user_type = FREE_PLAN_ID;
                    $user->trial_taken = TRIAL_TAKEN;
                    $this->getUserModel->save(array(
                        'user_id' => $user->user_id,
                        'user_type' => $user->user_type, //downgrade plan to free
                        'trial_taken' => $user->trial_taken    //change the flag to taken
                    ));
                    $this->notify($user, "Your trial has been expired.", 'account/paymentStatus');
                    $this->sendMailUsingTemplate($user, 'trial-membership-expired-general-email');
                    $this->sendMailUsingTemplate($user, 'trial-membership-expired-account-automatically-downgraded');
                    $this->logDebug($user->user_name . ": Your trial has been expired.");
                } else {
                    $halfDays = ((strtotime($user->trial_end_date) - strtotime($user->trial_start_date)) / (3600 * 24)) / 2;
                    //$this->logDebug($user->user_name . " Half: $halfDays days.");
                    if (abs($halfDays - $expiringDays) <= 1 && $subscription->due <= 0) {
                        $this->sendMailUsingTemplate($user, 'trial-membership-half-way-over');
                        $this->logDebug($user->user_name . ": Your trial has been been half way over.");
                        $subscriptionModel->save(array('id' => $subscription->id, 'due' => $subscription->due + 1));
                    } else if ($expiringDays <= 1 && $subscription->due <= 1) {
                        $this->sendMailUsingTemplate($user, 'trial-membership-expires-in-24-hours');
                        $this->logDebug($user->user_name . ": Your trial expires in 24 hours.");
                        $this->sendMailUsingTemplate($user, 'trial-membership-expires-in-24-hours-upgrade-now');
                        $this->logDebug($user->user_name . ": Your trial expires in 24 hours. Please upgrade your membership.");
                        $subscriptionModel->save(array('id' => $subscription->id, 'due' => $subscription->due + 1));
                    }
                }
                continue;
            }

            $expiredDays = ($today - strtotime($subscription->next_billing_date)) / (3600 * 24);
            $this->logDebug($user->user_name . " has $expiredDays days of expiration.");

            if ($expiredDays == 0) {
                //[2015-04-21 D.A. Zhen] Make unpaid invoice
                if (!$subscription->last_invoice_number) {
                    $invoiceNumber = $this->createInvoice($subscription, $this->calculateSubscriptionFee($subscription));
                    $this->sendMailWithInvoiceUsingTemplate($user, $invoiceNumber, 'new-invoice-created');
                    $this->sendMailWithInvoiceUsingTemplate($user, $invoiceNumber, 'current-invoice-due');
                }
                if (!$subscription->bt_subscription_id) { //recurring subscription will be paid automatically on bt, so no need to notify here
                    $this->notify($user, 'Today is your date to pay subscription fee.', 'account/paymentStatus');
                    $this->logDebug($user->user_name . ': Today is your date to pay subscription fee.');
                }
            } else if ($expiredDays > 0) {
                if ($expiredDays > PAYMENT_DUE_LIMIT) { //if it has been limited the expiration limit, suspend the account 
                    $this->suspendUser($user, SUSPENDED_REASON_PAYMENT_DUE);
                    $this->sendMailWithInvoiceUsingTemplate($user, $subscription->last_invoice_number, "invoice-past-due-account-suspended");
                    $this->notify($user, 'Your account has been suspended due to expiration of payment. The amount is ' . $this->calculateSubscriptionFee($subscription) . '. Please submit payment now to restore full functionality to your account.', 'account/paymentStatus');
                    $this->logDebug($user->user_name . ': Your account has been suspended due to expiration of payment.');
                } else { //or, we will notify the user and wait for the payment
                    $due = ++$subscription->due;
                    if ($due % EXPIRATION_CHECK_CYCLE == 0) {
                        $hrs = $expiredDays * 24;
                        $this->sendMailWithInvoiceUsingTemplate($user, $subscription->last_invoice_number, "invoice-$hrs-hours-past-due");
                        $this->notify($user, "You have $expiredDays day(s) due payment.", 'account/paymentStatus');
                        $this->logDebug($user->user_name . ": You have $expiredDays day(s) due payment.");
                    }
                    $subscriptionModel->save(array('id' => $subscription->id, 'due' => $due));
                }
            }
        }
    }

    //[2015-04-06] Cron function that checks if users use too many files or too much spaces
    public function checkFilesAndSpaces() {
        $users = $this->____load_model('UserModel')->search(array());
        foreach ($users as $user) {
            $this->logDebug('Checking ' . $user->user_name . '(' . $user->workshop . ') for using too many files or too much spaces ...');
            $this->__clear_feature();
            $this->__set_account($user, false);
            $this->validateFilesAndSpaces(true);
        }
    }

    //[2015-06-09] Cron function that deletes old backup files
    public function checkBackupFiles() {
        $users = $this->____load_model('UserModel')->search(array());
        foreach ($users as $user) {
            $this->logDebug('Checking ' . $user->user_name . ' to delete backup files ...');
            $this->__set_account($user, false);
            if (isset($this->__options['ws_backups'])) {
                $backups = json_decode($this->__options['ws_backups'], true);
                if (count($backups) > 0) {
                    $changed = false;
                    foreach ($backups as $key => $backup) {
                        $file = $backup['file'];
                        if (!is_file($file)) {
                            unset($backups[$key]);
                            $changed = true;
                        } else {
                            $created = (time() - filemtime($file));
                            if ($created >= 3600 * 6) {
                                $this->sendMailUsingTemplate($user, 'workspace-backup-deleted', array('workspace' => $backup['name']));
                                unlink($file);
                                unset($backups[$key]);
                                $changed = true;
                            }
                        }
                    }
                    if ($changed) {
                        $this->__save_option('ws_backup', json_encode($backups));
                    }
                }
            }
        }
    }

}
