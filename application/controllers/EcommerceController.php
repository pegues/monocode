<?php

require_once 'FrontendController.php';

class EcommerceController extends FrontendController {

    private $live = false;

    public function __construct() {
        parent::__construct();
    }

    public function isLive() {
        return $this->live;
    }

    public function loadBrainTree() {
        require_once APPPATH . '../Braintree/Braintree.php';

        $isLive = $this->__options['paypal_mode'] != 'test';
        $this->live = $isLive;

        if ($isLive) {
            Braintree_Configuration::environment('production');
            Braintree_Configuration::merchantId($this->__options['braintree_merchant_id']);
            Braintree_Configuration::publicKey($this->__options['braintree_public_key']);
            Braintree_Configuration::privateKey($this->__options['braintree_private_key']);
        } else {
            Braintree_Configuration::environment('sandbox');
            Braintree_Configuration::merchantId($this->__options['test_braintree_merchant_id']);
            Braintree_Configuration::publicKey($this->__options['test_braintree_public_key']);
            Braintree_Configuration::privateKey($this->__options['test_braintree_private_key']);
            require_once APPPATH . '../Braintree/Braintree_TestHelper.php';
        }
    }

    public function flushBraintreeErrors($result) {
        if ($result) {
            foreach ($result->errors->deepAll() AS $error) {
                $this->addBraintreeErrorMessage($error);
            }
        }
    }

    public function addBraintreeErrorMessage($error) {
        $this->addErrorMessage('Braintree Error(Code: ' . $error->code . "): " . $error->message);
    }

    public function processBraintreeException($ex) {
        $this->addErrorMessage('Braintree Exception(Code: ' . $ex->getCode() . "): " . $ex->getMessage());
    }

    public function getSubscriptionModel() {
        return $this->____load_model('SubscriptionModel');
    }

    //[2015-04-02 D.A. Zhen] Function that adds free membership on signup
    public function addFreeSubscription($userId) {
        return $this->addSubscription($userId, FREE_PLAN_ID);
    }

    //[2015-04-02 D.A. Zhen] Function that adds subscription
    public function addSubscription($userId, $planId, $amount = 0, $orderId = null, $billingCycle = 1, $bt_subscription_id = null, $more = null) {
        $subscription = array(
            'user_id' => $userId,
            'user_type_id' => $planId,
            'amount' => $amount,
            'bt_subscription_id' => $bt_subscription_id,
            'billing_cycle' => $billingCycle,
            'order_id' => $orderId,
            'discount_amount' => 0,
            'discount_billing_cycles_for' => 1,
            'promotion_code' => null,
            'promotion_amount' => 0,
            'yearly_discount_amount' => 0,
            'start_date' => date('Y-m-d')
        );

        if ($more) {
            foreach ($more as $key => $value) {
                if (isset($subscription[$key])) {
                    $subscription[$key] = $value;
                }
            }
        }

        $subscription['next_billing_date'] = $subscription['start_date'];

        $ip = $_SERVER['REMOTE_ADDR'];
        if ($ip != 'localhost' && $ip != '127.0.0.1' && $ip != '::1') {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
            $subscription['ip'] = $details->ip;
            $subscription['hostname'] = $details->hostname;
            if (isset($details->city)) {
                $subscription['city'] = $details->city;
                $subscription['region'] = $details->region;
                $subscription['country'] = $details->country;
                $subscription['loc'] = $details->loc;
                $subscription['org'] = $details->org;
            }
        }

        return $this->getSubscriptionModel()->save($subscription);
    }

    public function getNewOrderId() {
        $last_order_id = $this->__get_option_model()->get('last_order_id');
        $last_order_id += 1;
        $this->__get_option_model()->update('last_order_id', $last_order_id);
        return 'MO' . str_pad($last_order_id, 10, '0', STR_PAD_LEFT);
    }

    //[2015-04-02 D.A. Zhen] Function that cancels subscription
    public function cancelSubscription($subscription) {
        //[2015-04-20 D.A. Zhen] Refunding ....
        if ($subscription->last_transaction_id && $subscription->last_paid_date) { //check if there is any previous payment
            $today = gmdate('Y-m-d');
            $last_paid_date = date_create($subscription->last_paid_date);
            $daysForABillingCycle = date_diff(date_create($subscription->next_billing_date), $last_paid_date);
            $daysForABillingCycle = ($daysForABillingCycle->format('%a'));
            $daysSinceLastPaid = date_diff(date_create($today), $last_paid_date);
            $daysSinceLastPaid = ($daysSinceLastPaid->format('%a'));
            $daysSinceLastPaid ++;
            if ($daysSinceLastPaid < $daysForABillingCycle) {   //if days are still remained to next billing date
                $transaction = $this->____load_model('TransactionModel')->entity($subscription->last_transaction_id);
                if (($refund_amount = $transaction->amount - $subscription->amount / $daysForABillingCycle * ($daysSinceLastPaid)) > 0) {
                    //$transaction->amount: actually paid amount
                    //$subscription->amount / $daysForABillingCycle * ($daysSinceLastPaid): used amount
                    // and subscription has amount remained, refund it
                    $refund_amount = number_format(round($refund_amount, 2), 2);
                    try {
                        if (!$this->isLive()) {
                            Braintree_TestHelper::settle($transaction->bt_transaction_id);
                        }
                        $result = Braintree_Transaction::refund($transaction->bt_transaction_id, $refund_amount);
                        if ($result->success) {
                            $this->addSuccessMessage('$' . $refund_amount . ' has been refunded sucessfully.');
                            $this->notify($this->__account, "You've received a refund in the amount of $$refund_amount.");
                            $this->saveTransaction($result->transaction, 1);
                        } else {
                            $this->flushBraintreeErrors($result);
                            $this->addDebugMessage('Refund Amount: ' . $refund_amount);
                        }
                    } catch (Braintree_Exception $ex) {
                        $this->processBraintreeException($ex);
                    }
                }
            }
        }
        return $this->getSubscriptionModel()->save(array('id' => $subscription->id, 'end_date' => gmdate('Y-m-d')));
    }

    //[2015-04-02 D.A. Zhen] Function that saves transactions
    public function saveTransactions($transactions) {
        if (!$transactions) {
            return;
        }

        if (!is_array($transactions)) {
            $transactions = array($transactions);
        }

        foreach ($transactions as $transaction) {
            $this->saveTransaction($transaction);
        }
    }

    //[2015-04-02 D.A. Zhen] Function that saves transaction details
    private function saveTransaction($transaction, $isRefund = false) {
        $orderId = $transaction->orderId;
        if ($orderId) {
            $subscription = $this->getSubscriptionModel()->get_by_order($orderId);
        } else {
            $subscription = $this->getSubscriptionModel()->get_by_bt_subscription($transaction->subscriptionId);
            $orderId = $subscription->order_id;
        }
        $date = $transaction->updatedAt ? $transaction->updatedAt : $transaction->createdAt;
        $date = $date->format('Y-m-d H:i:s');
        $paymentStatus = 'S'; //will be changed later by analyzing $transaction->status
        $transactionId = $this->____load_model('TransactionModel')->save(array(
            'user_id' => $subscription->user_id,
            'user_type_id' => $subscription->user_type_id,
            'amount' => number_format($transaction->amount, 2),
            'date' => $date,
            'payment_status' => $paymentStatus,
            'order_id' => $orderId,
            'bt_transaction_id' => $transaction->id,
            'bt_transaction_status' => $transaction->status,
            'is_refund' => $isRefund
        ));

        if ($paymentStatus == 'S') {
            $nextBillingDate = date('Y-m-d', strtotime($subscription->billing_cycle . ' month' . ($subscription->billing_cycle > 1 ? 's' : ''), strtotime($subscription->next_billing_date)));
            if ($subscription->discount_billing_cycles_taken < $subscription->discount_billing_cycles_for) {
                $subscription->discount_billing_cycles_taken ++;
            }
            $this->getSubscriptionModel()->save(array(
                'id' => $subscription->id,
                'last_paid_date' => $date,
                'next_billing_date' => $nextBillingDate,
                'discount_billing_cycles_taken' => $subscription->discount_billing_cycles_taken,
                'last_transaction_id' => $transactionId
            ));

            if ($invoiceNumber = $this->updateInvoice($subscription, $transaction, $isRefund)) {
                $this->sendMailWithInvoiceUsingTemplate($this->getUserModel()->entity($subscription->user_id), $invoiceNumber, $subscription->bt_subscription_id ? 'recurring-payment-process-success' : 'payment-success');
            }
        } else {
            $this->sendMailWithInvoiceUsingTemplate($this->getUserModel()->entity($subscription->user_id), $subscription->last_invoice_number, $subscription->bt_subscription_id ? 'recurring-payment-process-unsuccess' : 'payment-unsuccess');
        }
    }

    //[2015-04-21 D.A. Zhen] Updates unpaid invoice, (creates invoice if not exist)
    public function updateInvoice($subscription, $transaction, $isRefund = false) {
        $invoiceNumber = $subscription->last_invoice_number;    //get last created invoice number
        if (!$invoiceNumber) { //if no unpaid invoice
            $invoiceNumber = $this->createInvoice($subscription, $transaction->amount); //create a unpaid invoice immediately
        }

        if (!($invoice = (array) $this->____load_model('InvoiceModel')->get_by_number($invoiceNumber))) {
            //impossible, invoice should always exist
            $this->addErrorMessage('Invalid invoice number.');
        } else {
            
        }

        //Updates unpaid invoice into paid
        $invoice['paid_date'] = gmdate('Y-m-d H:i:s');
        $invoice['transaction_id'] = $transaction->id;
        $invoice['is_paid'] = true;
        $invoice['is_refund'] = $isRefund;

        //Removes unpaid invoice number from subscription
        $this->getSubscriptionModel()->save(array(
            'id' => $subscription->id,
            'last_invoice_number' => null));

        if ($this->____load_model('InvoiceModel')->save($invoice)) {
            return $invoiceNumber;
        }

        return null;
    }

    public function getNewInvoiceNumber() {
        $last_invoice_number = $this->__get_option_model()->get('last_invoice_number');
        $last_invoice_number += 1;
        $this->__get_option_model()->update('last_invoice_number', $last_invoice_number);
        return /*'MI' . */str_pad($last_invoice_number, 10, '0', STR_PAD_LEFT);
    }

    public function createInvoice($subscription, $amount) {
        $invoiceNumber = $this->getNewInvoiceNumber();
        $invoice = array(
            'user_id' => $subscription->user_id,
            'plan_id' => $subscription->user_type_id,
            'amount' => number_format($amount, 2),
            'invoice_number' => $invoiceNumber,
            'order_id' => $subscription->order_id,
            'created_date' => gmdate('Y-m-d H:i:s')
        );

        if (($invoiceId = $this->____load_model('InvoiceModel')->save($invoice)) > 0) {
            //Set unpaid invoice number into subscription
            $this->getSubscriptionModel()->save(array(
                'id' => $subscription->id,
                'last_invoice_number' => $invoiceNumber));
            return $invoiceNumber;
        } else {
            return null;
        }
    }

    public function createInvoiceFile($invoiceNumber, $user = null) {
        if (!($invoice = $this->____load_model('InvoiceModel')->get_by_number($invoiceNumber))) {
            return null;
        }

        if (!$user) {
            $user = $this->getUserModel()->entity($invoice->user_id);
        }

        $html = $this->load->view('frontend/account/invoice', array('invoice' => $invoice, 'user' => $user, 'plan' => $this->____load_model('PlanModel')->entity($invoice->plan_id), 'settings' => $this->__settings), true);

        $file = $this->getTempFileName('pdf');

        $this->load->helper('pdf');        
        $dompdf = dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        file_put_contents($file, $dompdf->output());
        
        return $file;
    }

    function sendMailWithInvoiceUsingTemplate($user, $invoiceNumber, $template_slug, $params = null) {
        if (!$params) {
            $params = array();
        }
        $plan = $this->____load_model('PlanModel')->entity($user->user_type);
        $params['plan'] = $plan->user_type_name;
        $params['user'] = $user;
        $message = $this->generateEmailMessage($template_slug, $params);
        $file = $this->createInvoiceFile($invoiceNumber, $user);
        $this->sendMail($user->email, $message['subject'], $message['content'], $file);
        if ($file) {
            unlink($file);
        }
    }

    public function calculateSubscriptionFee($subscription) {
        return number_format($subscription->amount - $subscription->yearly_discount_amount - ($subscription->discount_billing_cycles_taken < $subscription->discount_billing_cycles_for ? $subscription->discount_amount : 0), 2);
    }

    public function cancelTrial() {
        $this->__save_account(array(
            'user_type' => FREE_PLAN_ID, //downgrade plan to free
            'trial_taken' => TRIAL_TAKEN, //change the flag to taken
            'trial_end_date' => gmdate('Y-m-d') //end date of trial
        ));
        $this->addSuccessMessage('Your trial has been cancelled successfully.');
        $this->notify($this->__account, 'Your trial has been ended successfully.', 'account/paymentStatus');
    }

    protected function getYears() {
        $years = array();
        for ($i = date("Y"); $i < date("Y", strtotime(date("Y") . " +10 years")); $i++) {
            $years[] = $i;
        }
        return $years;
    }

    protected function getMonths() {
        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        return $months;
    }

    public function checkPromocodeAvailable() {
        if ($code = $this->input->post('code')) {
            if ($pc = $this->getPromocode($code)) {
                $this->addToResponseData('promocode', $pc);
            }
            $this->ajaxResponse();
        }
    }

    private function getPromocode($code) {
        if ($pc = $this->____load_model('PromocodeModel')->get_by_code($code)) {
            $today = gmdate('Y-m-d');
            if ($pc->used) {
                $this->addErrorMessage('The promo code has already been used.');
            } else if ($pc->disabled) {
                $this->addErrorMessage('The promo code has been disabled.');
            } else if ($pc->start_date > $today) {
                $this->addErrorMessage('The promo code is still in pending.');
            } else if ($pc->end_date < $today) {
                $this->addErrorMessage('The promo code has been expired.');
            } else {
                return $pc;
            }
        } else {
            $this->addErrorMessage('Invalid promo code.');
        }

        return null;
    }

}

?>