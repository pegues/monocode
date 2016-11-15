<?php

require_once 'EcommerceController.php';

class Membership extends EcommerceController {

    public function __construct() {
        $this->__need_authentication = true;
        $this->__unauthorized_actions = array('index', 'notified');
        parent::__construct();
        $this->__load_model('PlanModel');
        $this->__set_module_name(MODULE_NAME_MEMBERSHIP);
        if ($this->isLoggedIn() && $this->isAccountSuspended() && $this->__account->inactive_reason == SUSPENDED_REASON_PAYMENT_DUE) {
            $this->addWarningMessage('Your account is in suspended status now.');
        }
    }

    public function index() {
        $list = $this->____load_model('FeatureModel')->search();
        $features = array();
        foreach ($list as $feature) {
            $features[$feature->feature_id] = $feature;
        }

        $plans = $this->__model->search();
        foreach ($plans as $plan) {
            if (isset($features[$plan->feature_id])) {
                $feature = $features[$plan->feature_id];
                $plan->feature = $feature;
                $plan->feature_details = $this->getFeatureDetail($feature);
            }
        }

        $this->setEntities($plans);

        if ($this->__account) {
            if ($plan = $this->__model->entity($this->__account->user_type)) {
                $this->setEntity($plan);
            }
        }

        $this->view($this->__module_name . '/index');
    }

    public function start() {
        $planId = $this->uri->segment(3);
        if (!$planId || $this->__account->user_type == $planId) {
            $this->redirect($this->__get_base_url());
        }

        $myPlan = $this->__model->entity($this->__account->user_type);
        $newPlan = $this->__model->entity($planId);
        if (!$myPlan || $myPlan->display_order < $newPlan->display_order) {
            $this->redirect($this->__module_name . '/upgrade/' . $planId);
        } else {
            $this->redirect($this->__module_name . '/downgrade/' . $planId);
        }
    }

    public function upgrade($planId) {
        if (!$planId || $this->__account->user_type == $planId) {
            $this->redirect($this->__get_base_url());
        }

        $plan = $this->__model->entity($planId);
        $discount = $plan->discount_id ? $this->____load_model('DiscountModel')->entity($plan->discount_id) : null;
        if ($discount) {
            $this->addToResponseData('discount', $discount);
        }
        $yearlyDiscount = $plan->yearly_discount_id ? $this->____load_model('DiscountModel')->entity($plan->yearly_discount_id) : null;
        if ($yearlyDiscount) {
            $this->addToResponseData('yearlyDiscount', $yearlyDiscount);
        }

        //Process for trial
        if ($this->input->post('trial')) { //going for trial, impossible situation because the trial button will not be shown in this case
            if (!$this->__settings->trial_duration) { //check settings for trial enable/disable
                $this->addErrorMessage("Trial is disabled.");
                $this->redirect(current_url());
            }
            $userModel = $this->____load_model('UserModel');
            $user = $userModel->entity($this->__account->id);
            if ($user->bt_used) { //impossible situation because the trial button will not be shown in this case
                $this->addErrorMessage("You can't apply for the trial because you've already subscribed before.");
                $this->redirect(current_url());
            }
            $this->__save_account(array(
                'user_type' => $planId,
                'bt_used' => BT_USED,
                'trial_taken' => TRIAL_BEING_TAKEN,
                'trial_start_date' => gmdate('Y-m-d'),
                'trial_end_date' => gmdate('Y-m-d', strtotime('+' . $this->__settings->trial_duration . ' ' . $this->__settings->trial_duration_unit . ($this->__settings->trial_duration_unit > 1 ? 's' : ''), gmmktime()))
            ));
            $this->addSuccessMessage('You have applied trial for ' . $plan->user_type_name . ' successfully.');
            $this->sendMailUsingTemplate($user, 'trial-membership-has-started');
            $this->notify($user, 'Your trial for ' . $plan->user_type_name . ' has been started.', 'account/paymentStatus');
            $this->redirect($this->__get_base_url());
        }


        $connected = $plan->plan_id != null && $plan->plan_id != '';
        $this->addToResponseData('connected', $connected);

        if (!$connected) {
            $this->addErrorMessage('You cannot upgrade to this plan because this plan is not connected to braintree yet.');
        } else {
            $this->loadBrainTree();
            $userModel = $this->____load_model('UserModel');
            $user = $userModel->entity($this->__account->id);
            $bt_customer_id = $user->bt_customer_id;

            $isCheckout = $this->uri->segment(4) == 'checkout'; //check if in checkout page
            if ($this->isPost()) {
                if (!$isCheckout) { //once clicked on upgrade, we goto checkout page.
                    $this->redirect(current_url() . '/checkout');
                }

                $billingAddress = new stdClass(); //initialize billingAddress object
                //Getting parameters
                $this->__fillObject($billingAddress, $_POST, array('firstName', 'lastName', 'streetAddress', 'locality', 'region', 'countryCodeAlpha2'));

                $isSavingPaymentMethod = $this->input->post('savePaymentMethod'); //Option to save payment method
                $isRecurring = $this->input->post('paymenttype');
                $paymentMethodNonce = $this->input->post('payment_method_nonce');
                $isPaypal = false;
                if (!$paymentMethodNonce) {
                    $paymentMethodNonce = $this->input->post('payment_method_nonce_paypal');
                    if ($paymentMethodNonce) { // we don't need billing address for paypal
                        $billingAddress = null;
                        $isPaypal = true;
                    }
                }
                $ccToken = $this->input->post('ccToken');
                if (!$ccToken) { //ccToken = null means getting a new payment method
                    if ($isSavingPaymentMethod || $isRecurring) { //and saving option is checked or recurring plan
                        //We are creating customer on bt
                        if (!$bt_customer_id) {   //if no customer saved before
                            //create a new customer on bt if no one exists
                            $result = Braintree_Customer::create(array(
                                        'firstName' => $user->first_name,
                                        'lastName' => $user->last_name,
                                        'email' => $user->email,
                            ));
                            if ($result->success) {
                                $bt_customer_id = $result->customer->id;
                                $user->bt_customer_id = $result->customer->id;
                                $this->__save_account(array(
                                    'bt_customer_id' => $bt_customer_id
                                ));
                            } else {
                                $this->flushBraintreeErrors($result);
                            }
                        }
                        //Adds new payment method
                        try {
                            $creditCard = array(
                                'customerId' => $bt_customer_id,
                                'paymentMethodNonce' => $paymentMethodNonce,
                                'billingAddress' => (array) $billingAddress,
                                'options' => array(
                                    'makeDefault' => true,
                                    'verifyCard' => true,
                                    //'failOnDuplicatePaymentMethod' => true
                                )
                            );
                            $result = Braintree_PaymentMethod::create($creditCard);
                            if ($result->success) {
                                $ccToken = $result->paymentMethod->token;
                                $this->addSuccessMessage('You have successfully added a new payment method.');
                                $this->notify($user, 'You have successfully added a new payment method.', 'account/paymentMethod');
                            } else {
                                $this->flushBraintreeErrors($result);
                            }
                        } catch (Exception $ex) {
                            $this->processBraintreeException($ex);
                        }
                    } else {
                        
                    }
                } else { //set selected payment method as default 
                    try {
                        Braintree_PaymentMethod::update($ccToken, array('options' => array('makeDefault' => true)));
                    } catch (Exception $ex) {
                        $this->processBraintreeException($ex);
                    }
                }

                $billingCycle = $this->input->post('planlength');
                $amount = $plan->amount * $billingCycle;
                $sale_amount = $amount;
                $yearly_discount_amount = 0;
                if ($billingCycle > 1 && $yearlyDiscount) {
                    $yearly_discount_amount = $yearlyDiscount->percent / 100 * $amount;
                    $sale_amount -= $yearly_discount_amount;
                }
                $discount_amount = 0;
                if ($discount) {
                    $discount_amount = $discount->percent / 100 * $sale_amount;
                    $sale_amount -= $discount_amount;
                }

                $promocode = null;
                $promotion_amount = 0;
                if (!$user->promocode_used && $code = $this->input->post('promocode')) {
                    $this->addToResponseData('promocode', $code);
                    $promocode = $this->getPromocode($code);
                    if ($promocode) {
                        $promotion_amount = $promocode->amount;
                        if ($promocode->is_percent) {
                            $promotion_amount = $promotion_amount / 100 * $sale_amount;
                        }
                        $sale_amount -= $promotion_amount;
                    }
                }

                $upgraded = false;
                $newOrderId = $this->getNewOrderId();
                $subscription = $this->getSubscriptionModel()->entity($user->subscription_id);
                $bt_subscription_id = $subscription ? $subscription->bt_subscription_id : null;
                $transactions = null;
                if (!$isRecurring) { //for one-time payment                    
                    if ($bt_subscription_id) { //cancels previous recurring subscription
                        try {
                            Braintree_Subscription::cancel($bt_subscription_id);
                        } catch (Braintree_Exception_NotFound $ex) {
                            $this->processBraintreeException($ex);
                        }
                        $bt_subscription_id = null;
                    }
                    $data = array(
                        'amount' => $sale_amount,
                        'orderId' => $newOrderId,
                        'options' => array(
                            'submitForSettlement' => true
                        )
                    );
                    if ($ccToken) {    //we use customer id of bt if it exists
                        $data['paymentMethodToken'] = $ccToken;
                    } else {    //or we just send payment method nonce and billing address
                        $data['paymentMethodNonce'] = $paymentMethodNonce;
                        if ($billingAddress) {
                            $data['billing'] = (array) $billingAddress;
                        }
                    }

                    try {
                        $result = Braintree_Transaction::sale($data);
                        if ($result->success) {
                            $transactions = $result->transaction;
                            $upgraded = true;
                        } else {
                            $this->flushBraintreeErrors($result);
                        }
                    } catch (Braintree_Exception $ex) {
                        $this->processBraintreeException($ex);
                    }
                } else {    //for recurring payment
                    $result = null;

                    //[2015-03-20 D.A. Zhen] Basic information for subscription
                    $data = array(
                        'planId' => $plan->plan_id . ($billingCycle == 12 ? '-yearly' : ''),
                        'price' => $amount,
                        'paymentMethodToken' => $ccToken
                            //'orderId' => $newOrderId //It will be better if we can pass order id into subscription, it seems impossible
                    );

                    //[2015-04-09] //Cancel previous subscription if exists
                    if ($bt_subscription_id) {
                        try {
                            Braintree_Subscription::cancel($bt_subscription_id);
                        } catch (Braintree_Exception_NotFound $ex) {
                            $this->processBraintreeException($ex);
                        }
                        $bt_subscription_id = null;
                    }

                    //[2015-03-20 D.A. Zhen] Adds discount for monthly, yearly and promotional
                    $discounts = array();
                    if ($discount_amount > 0) {
                        $discounts[] = array('inheritedFromId' => 'default-discount', 'amount' => $discount_amount, 'numberOfBillingCycles' => $discount->billing_cycles);
                    }
                    if ($yearly_discount_amount > 0) {
                        $discounts[] = array('inheritedFromId' => 'yearly-discount', 'amount' => $yearly_discount_amount, 'neverExpires' => true); //yearly discount will be applied for every billing cycle
                    }
                    if ($promotion_amount > 0) {
                        $discounts[] = array('inheritedFromId' => 'promotion', 'amount' => $promotion_amount, 'numberOfBillingCycles' => 1); //discount for promotion will be applied only for first billing cycle
                    }
                    $data['discounts'] = array('add' => $discounts);

                    /* if ($bt_subscription_id) {   //updates brantree subscription if it already exists
                      $data['options'] = array('prorateCharges' => false, 'replaceAllAddOnsAndDiscounts' => true);
                      try {
                      $result = Braintree_Subscription::update($bt_subscription_id, $data);
                      } catch (Braintree_Exception_NotFound $ex) {
                      $this->processBraintreeException($ex);
                      }
                      } else { */
                    //Creates new subscription
                    try {
                        $result = Braintree_Subscription::create($data);
                    } catch (Braintree_Exception_NotFound $ex) {
                        $this->processBraintreeException($ex);
                    }
                    //}
                    if ($result && $result->success) {
                        $bt_subscription_id = $result->subscription->id;
                        $transactions = $result->subscription->transactions;
                        $this->sendMailUsingTemplate($user, 'recurring-payment-created');
                        $upgraded = true;
                    } else {
                        $this->flushBraintreeErrors($result);
                    }
                }

                if ($upgraded) {
                    if ($user->trial_taken == TRIAL_BEING_TAKEN) {
                        $this->cancelTrial();
                    }
                    if ($subscription) {
                        $this->cancelSubscription($subscription);
                    }
                    $more = array();
                    if ($discount_amount > 0) {
                        $more['discount_amount'] = $discount_amount;
                        $more['discount_billing_cycles_for'] = $discount->billing_cycles;
                    }
                    if ($yearly_discount_amount > 0) {
                        $more['yearly_discount_amount'] = $yearly_discount_amount;
                    }
                    if ($promotion_amount > 0) {
                        $more['promotion_code'] = $promocode->code;
                        $more['promotion_amount'] = $promotion_amount;
                    }

                    $newSubscriptionId = $this->addSubscription($user->user_id, $planId, $amount, $newOrderId, $billingCycle, $bt_subscription_id, $more);
                    $this->__save_account(array(
                        'user_type' => $planId,
                        'subscription_id' => $newSubscriptionId,
                        'bt_used' => BT_USED,
                        'promocode_used' => $user->promocode_used || $promocode != null
                    ));

                    $invoiceNumber = $this->createInvoice($this->getSubscriptionModel()->entity($newSubscriptionId), $sale_amount);
                    $this->sendMailWithInvoiceUsingTemplate($user, $invoiceNumber, 'new-invoice-created');

                    $this->addSuccessMessage('You have upgraded your membership to ' . $plan->user_type_name . ' successfully.');
                    $this->notify($user, 'You have upgraded your membership to ' . $plan->user_type_name . ' successfully.', 'account/paymentStatus');
                    $this->saveTransactions($transactions);

                    if ($promocode != null) {
                        $this->addSuccessMessage('You have used promocode successfully.');
                        $this->sendMailUsingTemplate($user, 'payment-success-with-promo-code');
                        $this->notify($user, 'You have used promocode successfully.');
                        $this->____load_model('PromocodeModel')->save(array(
                            'id' => $promocode->id,
                            'used' => true
                        ));
                    }

                    $this->redirect($this->__get_base_url());
                }
            }
        }

        //[2015-03-30] Fetches feature details of plan to upgrade
        if ($plan->feature_id) {
            if ($feature = $this->____load_model('FeatureModel')->entity($plan->feature_id)) {
                $plan->feature = $feature;
                $plan->feature_details = $this->getFeatureDetail($feature);
            }
        }
        $this->setEntity($plan);

        //[2015-03-30] Fetches current plan
        $this->addToResponseData('current_plan', $this->__model->entity($this->__account->user_type));

        if ($connected && $isCheckout) {
            //[2015-03-30] Payment page can be shown only if the plan is connected with braintree and url contains checkout
            if ($bt_customer_id) { //loads list of payment method
                try {
                    $customer = Braintree_Customer::find($bt_customer_id); //loads the customer information saved on bt
                    $paymentMethodList = $customer->paymentMethods();
                    $this->addToResponseData('paymentMethodList', $paymentMethodList);
                } catch (Braintree_Exception_NotFound $ex) {
                    $this->processBraintreeException($ex);
                }
            }
            $creditCard = new stdClass();
            $creditCard->cardholderName = $user->first_name . ' ' . $user->last_name;
            $billingAddress = new stdClass();
            $billingAddress->firstName = $user->first_name;
            $billingAddress->lastName = $user->last_name;
            if (DEBUG) { //sample data for debugging
                $creditCard->number = '4111111111111111';
                $creditCard->cvv = '100';
                $creditCard->expirationYear = '2016';
                $creditCard->expirationMonth = '12';
                $billingAddress->streetAddress = 'Street Address ABC';
                $billingAddress->locality = 'New York';
                $billingAddress->region = 'CA';
                $billingAddress->countryCodeAlpha2 = 'US';
                $billingAddress->postalCode = '34344';
            }
            $this->addToResponseData('creditCard', $creditCard);
            $this->addToResponseData('billingAddress', $billingAddress);

            if ($bt_customer_id) {
                $clientToken = Braintree_ClientToken::generate(array('customerId' => $bt_customer_id));
            } else {
                $clientToken = Braintree_ClientToken::generate();
            }
            $this->addToResponseData('bt_client_token', $clientToken);

            $this->addToResponseData('years', $this->getYears());
            $this->addToResponseData('months', $this->getMonths());

            $this->__set_page_title('Checkout ...');
            $this->addScripts('https://js.braintreegateway.com/v2/braintree.js', true);
            $this->addScripts('themes/frontend/monocode/js/checkout.js');
            $this->view($this->__module_name . '/payment');
        } else {
            $this->__set_page_title('Update to ' . $plan->user_type_name);
            $this->view($this->__module_name . '/upgrade');
        }
    }

    public function downgrade() {
        $planId = $this->uri->segment(3);
        if (!$planId || $this->__account->user_type == $planId) {
            $this->redirect($this->__get_base_url());
        }

        $plan = $this->__model->entity($planId);
        $discount = $plan->discount_id ? $this->____load_model('DiscountModel')->entity($plan->discount_id) : null;
        if ($discount) {
            $this->addToResponseData('discount', $discount);
        }
        $yearlyDiscount = $plan->yearly_discount_id ? $this->____load_model('DiscountModel')->entity($plan->yearly_discount_id) : null;
        if ($yearlyDiscount) {
            $this->addToResponseData('yearlyDiscount', $yearlyDiscount);
        }

        $connected = $plan->plan_id != null && $plan->plan_id != '';
        $this->addToResponseData('connected', $connected);

        if (!$connected && $planId > FREE_PLAN_ID) {
            $this->addErrorMessage('You cannot downgrade to this plan because this plan is not connected to braintree yet.');
        } else {
            $this->loadBrainTree();
            $userModel = $this->____load_model('UserModel');
            $user = $userModel->entity($this->__account->id);
            $bt_customer_id = $user->bt_customer_id;

            $isCheckout = $this->uri->segment(4) == 'checkout';
            $downgraded = false;
            $amount = 0;
            $sale_amount = 0;
            $billingCycle = 0;
            $discount_amount = 0;
            $yearly_discount_amount = 0;
            $promotion_amount = 0;
            $newOrderId = null;
            $subscription = $this->getSubscriptionModel()->entity($user->subscription_id);
            $bt_subscription_id = $subscription ? $subscription->bt_subscription_id : null;
            $transactions = null;
            if ($planId == FREE_PLAN_ID) { //Downgrade to free plan
                if ($this->isPost()) {
                    //Just cancel the subscription if there is any.
                    if ($bt_subscription_id) {
                        try {
                            Braintree_Subscription::cancel($bt_subscription_id);
                        } catch (Braintree_Exception_NotFound $ex) {
                            $this->processBraintreeException($ex);
                        }
                    }
                    $bt_subscription_id = null;
                    $user->user_type = $planId;
                    $downgraded = true;
                }
            } else if ($this->isPost()) {
                if (!$isCheckout) { //once clicked on downgrade, we goto checkout page.
                    $this->redirect(current_url() . '/checkout');
                }

                $billingAddress = new stdClass(); //initialize billingAddress object
                //Getting parameters
                $this->__fillObject($billingAddress, $_POST, array('firstName', 'lastName', 'streetAddress', 'locality', 'region', 'countryCodeAlpha2'));
                $isSavingPaymentMethod = $this->input->post('savePaymentMethod'); //Option to save payment method
                $isRecurring = $this->input->post('paymenttype');
                $paymentMethodNonce = $this->input->post('payment_method_nonce');
                if (!$paymentMethodNonce) {
                    $paymentMethodNonce = $this->input->post('payment_method_nonce_paypal');
                    if ($paymentMethodNonce) { // we don't need billing address for paypal
                        $billingAddress = null;
                    }
                }
                $ccToken = $this->input->post('ccToken');
                if (!$ccToken) { //ccToken = null means getting a new payment method
                    if ($isSavingPaymentMethod || $isRecurring) { //and saving option is checked or recurring plan
                        //We are creating customer on bt
                        if (!$bt_customer_id) {   //if no customer saved before
                            //create a new customer on bt if no one exists
                            $result = Braintree_Customer::create(array(
                                        'firstName' => $user->first_name,
                                        'lastName' => $user->last_name,
                                        'email' => $user->email,
                            ));
                            if ($result->success) {
                                $bt_customer_id = $result->customer->id;
                                $user->bt_customer_id = $result->customer->id;
                                $this->__save_account(array(
                                    'bt_customer_id' => $bt_customer_id
                                ));
                            } else {
                                $this->flushBraintreeErrors($result);
                            }
                        }
                        //Adds new payment method
                        try {
                            $creditCard = array(
                                'customerId' => $bt_customer_id,
                                'paymentMethodNonce' => $paymentMethodNonce,
                                'billingAddress' => (array) $billingAddress,
                                'options' => array(
                                    'makeDefault' => true,
                                    'verifyCard' => true,
                                    //'failOnDuplicatePaymentMethod' => true
                                )
                            );
                            $result = Braintree_PaymentMethod::create($creditCard);
                            if ($result->success) {
                                $ccToken = $result->paymentMethod->token;
                                $this->addSuccessMessage('You have successfully added a new payment method.');
                                $this->notify($user, 'You have successfully added a new payment method.', 'account/paymentMethod');
                            } else {
                                $this->flushBraintreeErrors($result);
                            }
                        } catch (Exception $ex) {
                            $this->processBraintreeException($ex);
                        }
                    }
                } else { //set selected payment method as default 
                    try {
                        Braintree_PaymentMethod::update($ccToken, array('options' => array('makeDefault' => true)));
                    } catch (Exception $ex) {
                        $this->processBraintreeException($ex);
                    }
                }

                $billingCycle = $this->input->post('planlength');
                $amount = $plan->amount * $billingCycle;
                $sale_amount = $amount;
                if ($billingCycle > 1 && $yearlyDiscount) {
                    $yearly_discount_amount = $yearlyDiscount->percent / 100 * $amount;
                    $sale_amount -= $yearly_discount_amount;
                }
                if ($discount) {
                    $discount_amount = $discount->percent / 100 * $sale_amount;
                    $sale_amount -= $discount_amount;
                }

                $promocode = null;
                if (!$user->promocode_used && $code = $this->input->post('promocode')) {
                    $this->addToResponseData('promocode', $code);
                    $promocode = $this->getPromocode($code);
                    if ($promocode) {
                        $promotion_amount = $promocode->amount;
                        if ($promocode->is_percent) {
                            $promotion_amount = $promotion_amount / 100 * $sale_amount;
                        }
                        $sale_amount -= $promotion_amount;
                    }
                }

                $newOrderId = $this->getNewOrderId();
                if (!$isRecurring) { //for one-time payment
                    if ($bt_subscription_id) { //cancels previous recurring subscription
                        try {
                            Braintree_Subscription::cancel($bt_subscription_id);
                        } catch (Braintree_Exception_NotFound $ex) {
                            $this->processBraintreeException($ex);
                        }
                        $bt_subscription_id = null;
                    }
                    $data = array(
                        'amount' => $sale_amount,
                        'orderId' => $newOrderId,
                        'options' => array(
                            'submitForSettlement' => true
                        )
                    );
                    if ($ccToken) {    //we use customer id of bt if it exists
                        $data['paymentMethodToken'] = $ccToken;
                    } else {    //or we just send payment method nonce and billing address
                        $data['paymentMethodNonce'] = $paymentMethodNonce;
                        if ($billingAddress) {
                            $data['billing'] = (array) $billingAddress;
                        }
                    }

                    try {
                        $result = Braintree_Transaction::sale($data);
                        if ($result->success) {
                            $transactions = $result->transaction;
                            $downgraded = true;
                        } else {
                            $this->flushBraintreeErrors($result);
                        }
                    } catch (Braintree_Exception $ex) {
                        $this->processBraintreeException($ex);
                    }
                } else {
                    $result = null;

                    //[2015-03-20 D.A. Zhen] Basic information for subscription
                    $data = array(
                        'planId' => $plan->plan_id . ($billingCycle == 12 ? '-yearly' : ''),
                        'price' => $amount,
                        'paymentMethodToken' => $ccToken
                            //'orderId' => $newOrderId //It will be better if we can pass order id into subscription, it seems impossible
                    );

                    //[2015-04-09] //cancel previous subscription if exists
                    if ($bt_subscription_id) {
                        try {
                            Braintree_Subscription::cancel($bt_subscription_id);
                        } catch (Braintree_Exception_NotFound $ex) {
                            $this->processBraintreeException($ex);
                        }
                        $bt_subscription_id = null;
                    }

                    //[2015-03-20 D.A. Zhen] Adds discount for monthly, yearly and promotional
                    $discounts = array();
                    if ($discount_amount > 0) {
                        $discounts[] = array('inheritedFromId' => 'default-discount', 'amount' => $discount_amount, 'numberOfBillingCycles' => $discount->billing_cycles);
                    }
                    if ($yearly_discount_amount > 0) {
                        $discounts[] = array('inheritedFromId' => 'yearly-discount', 'amount' => $yearly_discount_amount, 'neverExpires' => true); //yearly discount will be applied for every billing cycle
                    }
                    if ($promotion_amount > 0) {
                        $discounts[] = array('inheritedFromId' => 'promotion', 'amount' => $promotion_amount, 'numberOfBillingCycles' => 1); //discount for promotion will be applied only for first billing cycle
                    }
                    $data['discounts'] = array('add' => $discounts);

                    //Creates new subscription
                    try {
                        $result = Braintree_Subscription::create($data);
                    } catch (Braintree_Exception_NotFound $ex) {
                        $this->processBraintreeException($ex);
                    }

                    if ($result && $result->success) {
                        $bt_subscription_id = $result->subscription->id;
                        $transactions = $result->subscription->transactions;
                        $this->sendMailUsingTemplate($user, 'recurring-payment-created');
                        $downgraded = true;
                    } else {
                        $this->flushBraintreeErrors($result);
                    }
                }
            }
            if ($downgraded) {
                if ($subscription) {
                    $this->cancelSubscription($subscription);
                }

                if ($user->trial_taken == TRIAL_BEING_TAKEN) {
                    $this->cancelTrial();
                }

                $more = array();
                if ($discount_amount > 0) {
                    $more['discount_amount'] = $discount_amount;
                    $more['discount_billing_cycles_for'] = $discount->billing_cycles;
                }
                if ($yearly_discount_amount > 0) {
                    $more['yearly_discount_amount'] = $yearly_discount_amount;
                }
                if ($promotion_amount > 0) {
                    $more['promotion_code'] = $promocode->code;
                    $more['promotion_amount'] = $promotion_amount;
                }

                $newSubscriptionId = $this->addSubscription($user->user_id, $planId, $amount, $newOrderId, $billingCycle, $bt_subscription_id, $more);
                $this->__save_account(array(
                    'user_type' => $planId,
                    'subscription_id' => $newSubscriptionId,
                    'promocode_used' => $user->promocode_used || $promocode != null
                ));

                if ($sale_amount > 0) {
                    $invoiceNumber = $this->createInvoice($this->getSubscriptionModel()->entity($newSubscriptionId), $sale_amount);
                    $this->sendMailWithInvoiceUsingTemplate($user, $invoiceNumber, 'new-invoice-created');
                }
                $this->addSuccessMessage('You have downgraded your membership to ' . $plan->user_type_name . ' successfully.');
                $this->notify($user, 'You have downgraded your membership to ' . $plan->user_type_name . ' successfully.', 'account/paymentStatus');
                $this->saveTransactions($transactions);

                if ($promocode != null) {
                    $this->sendMailUsingTemplate($user, 'payment-success-with-promo-code');
                    $this->addSuccessMessage('You have used promocode successfully.');
                    $this->notify($user, 'You have used promocode successfully.');
                    $this->____load_model('PromocodeModel')->save(array(
                        'id' => $promocode->id,
                        'used' => true
                    ));
                }

                $newWorkspaceCount = isset($plan->feature) && $plan->feature ? $plan->feature->work_space : 0;
                $retainingWSs = $this->input->post('workspaces');
                if (!$retainingWSs) {
                    $retainingWSs = array();
                } else if (count($retainingWSs) > $newWorkspaceCount) {
                    $retainingWSs = array_slice($retainingWSs, 0, $newWorkspaceCount);
                }

                $workspaces = $this->__get_workspaces();
                $deletingWSs = array();
                foreach ($workspaces as $ws => $obj) {
                    if (!in_array($ws, $retainingWSs)) {
                        $deletingWSs[] = $ws;
                    }
                }
                if (count($deletingWSs) > 0) {
                    $count = $this->__remove_workspaces($deletingWSs);
                    $this->addInfoMessage($this->__tweakForPlural($count, 'workspace') . ' removed.');
                    $this->notify($user, $this->__tweakForPlural($count, 'workspace') . ' removed.');
                }

                if (isset($this->__options['db_user'])) {
                    $this->__load_db_user();
                    $newDatabaseCount = isset($plan->feature) && $plan->feature ? $plan->feature->database : 0;
                    $retainingDBs = $this->input->post('databases');
                    if (!$retainingDBs) {
                        $retainingDBs = array();
                    } else if (count($retainingDBs) > $newDatabaseCount) {
                        $retainingDBs = array_slice($retainingDBs, 0, $newDatabaseCount);
                    }

                    $databases = $this->__get_databases();
                    $deletingDBs = array();
                    foreach ($databases as $db) {
                        if (!in_array($db, $retainingDBs)) {
                            $deletingDBs[] = $db;
                        }
                    }
                    if (count($deletingDBs) > 0) {
                        $count = $this->__remove_databases($deletingDBs);
                        $this->addInfoMessage($this->__tweakForPlural($count, 'database') . ' removed.');
                        $this->notify($user, $this->__tweakForPlural($count, 'databaes') . ' removed.');
                    }
                }

                $this->redirect($this->__get_base_url());
            }
        }

        if ($plan->feature_id) {
            if ($feature = $this->____load_model('FeatureModel')->entity($plan->feature_id)) {
                $plan->feature = $feature;
                $plan->feature_details = $this->getFeatureDetail($feature);
                $this->setEntity($plan);
            }
        }

        $current_plan = $this->__model->entity($this->__account->user_type);
        if ($current_plan->feature_id) {
            if ($feature = $this->____load_model('FeatureModel')->entity($current_plan->feature_id)) {
                $current_plan->feature = $feature;
                $current_plan->feature_details = $this->getFeatureDetail($feature);
            }
        }
        $this->addToResponseData('current_plan', $current_plan);

        $this->addToResponseData('workspaces', $this->__get_workspaces());

        if (isset($this->__options['db_user'])) {
            $this->__load_db_user();
            $this->addToResponseData('databases', $this->__get_databases());
        }

        if ($connected && $isCheckout) {
            //[2015-03-30] Payment page can be shown only if the plan is connected with braintree and url contains checkout
            if ($bt_customer_id) { //loads list of payment method
                try {
                    $customer = Braintree_Customer::find($bt_customer_id); //loads the customer information saved on bt
                    $paymentMethodList = $customer->paymentMethods();
                    $this->addToResponseData('paymentMethodList', $paymentMethodList);
                } catch (Braintree_Exception_NotFound $ex) {
                    $this->processBraintreeException($ex);
                }
            }
            $creditCard = new stdClass();
            $creditCard->cardholderName = $user->first_name . ' ' . $user->last_name;
            $billingAddress = new stdClass();
            $billingAddress->firstName = $user->first_name;
            $billingAddress->lastName = $user->last_name;
            if (DEBUG) { //sample data for debugging
                $creditCard->number = '4111111111111111';
                $creditCard->cvv = '100';
                $creditCard->expirationYear = '2016';
                $creditCard->expirationMonth = '12';
                $billingAddress->streetAddress = 'Street Address ABC';
                $billingAddress->locality = 'New York';
                $billingAddress->region = 'CA';
                $billingAddress->countryCodeAlpha2 = 'US';
                $billingAddress->postalCode = '34344';
                $this->addToResponseData('creditCard', $creditCard);
                $this->addToResponseData('billingAddress', $billingAddress);
            }
            if ($bt_customer_id) {
                $clientToken = Braintree_ClientToken::generate(array('customerId' => $bt_customer_id));
            } else {
                $clientToken = Braintree_ClientToken::generate();
            }
            $this->addToResponseData('bt_client_token', $clientToken);

            $this->addToResponseData('years', $this->getYears());
            $this->addToResponseData('months', $this->getMonths());

            $this->__set_page_title('Checkout ...');
            $this->addScripts('https://js.braintreegateway.com/v2/braintree.js', true);
            $this->addScripts('themes/frontend/monocode/js/checkout.js');
            $this->view($this->__module_name . '/payment');
        } else {
            $this->__set_page_title('Downgrade to ' . $plan->user_type_name);
            $this->view($this->__module_name . '/downgrade');
        }
    }

    public function details() {
        $planId = $this->uri->segment(3);
        if (!$planId) {
            $this->redirect($this->__get_base_url());
        }

        $plan = $this->__model->entity($planId);
        if ($plan->feature_id) {
            if ($feature = $this->____load_model('FeatureModel')->entity($plan->feature_id)) {
                $plan->feature = $feature;
                $plan->feature_details = $this->getFeatureDetail($feature);
                $this->setEntity($plan);
            }
        }

        $this->__set_page_title($plan->user_type_name);
        $this->view($this->__module_name . '/details');
    }

    private function getFeatureDetail($feature) {
        $details = array();

        if ($feature->allow_ftp == 'yes') {
            $details[] = 'FTP Included';
        }
        if ($feature->allow_svn == 'yes') {
            $details[] = 'SVN Included';
        }
        if ($feature->work_space > 0) {
            $details[] = $feature->work_space . " workspace" . ($feature->work_space > 1 ? 's' : '');
        }
        if ($feature->database > 0) {
            $details[] = $feature->work_space . " database" . ($feature->work_space > 1 ? 's' : '');
        }

        return $details;
    }

    public function notified() {
        $this->loadBrainTree();
        if (isset($_GET['bt_challenge'])) {
            echo Braintree_WebhookNotification::verify($_GET['bt_challenge']);
        }
        if (isset($_POST["bt_signature"]) && isset($_POST["bt_payload"])) {
            $webhookNotification = Braintree_WebhookNotification::parse($_POST["bt_signature"], $_POST["bt_payload"]);

            /*
              $message = "[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
              . "Kind: " . $webhookNotification->kind . " | "
              . "Subscription: " . $webhookNotification->subscription->id . "\n";

              file_put_contents("/tmp/webhook.log", $message, FILE_APPEND);
             * 
             */

            $this->saveTransactions($webhookNotification->subscription->transactions);
            //file_put_contents("/tmp/webhook.log", $ret, FILE_APPEND);
        }
    }

}

?>