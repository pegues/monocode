<?php

require_once 'EcommerceController.php';

class Account extends EcommerceController {

    public function __construct() {
        $this->__unauthorized_actions = array('cancelled');
        parent::__construct();
        $this->__load_model('UserModel');
        $this->__set_module_name(MODULE_NAME_ACCOUNT);
        $this->__layout = 'account';
    }

    public function index() {
        $this->__set_page_title('My Account');
        $this->view($this->__module_name . '/index');
        $this->addToResponseData('sub_module', 'index');
    }

    public function checkUsernameAvailable() {
        $this->addValidationRule('user_name', 'User Name', 'trim|required|callback_username_check');
        $this->validateForm();
        $this->ajaxResponse();
    }

    public function profile() {
        $this->setEntity($this->__model->entity($this->__account->id));
        if ($this->isPost()) {
            $data = $_POST;
            if (isset($_POST['user_name'])) {
                unset($_POST['user_name']);
            }
            //$this->addValidationRule('first_name', 'First Name', 'trim|required');
            //$this->addValidationRule('last_name', 'last Name', 'trim|required');
            $this->addValidationRule('email', 'E-mail Address', 'trim|required|callback_email_check');
//            $this->addValidationRule('user_name', 'User Name', 'trim|required|callback_username_check');
            $this->addValidationRule('passwordconfirm', 'Password Confirmation', 'callback_password_confirm[' . $data['password'] . ']');
            if ($this->validateForm()) {
                unset($data['passwordconfirm']);

                $verification_code = $this->createVerificationCode($this->__account->user_name, $this->__account->email);
                if ($data['email'] != $this->__account->email) {
                    $this->__model->save(array('user_id' => $this->__account->user_id, 'verification_code' => $verification_code));
                    $link = $this->__get_base_url() . "profile/email?c=" . urlencode($verification_code) . "&v=" . urlencode($data['email']);
                    $this->sendMailUsingTemplate($this->__account, 'email-changed-my-account', array('email_change_url' => $link));
                    $this->addSuccessMessage('An email with a link to change your email has been sent to your email address. Please check your email.');
                    //$this->addDebugMessage("Click <a href='$link'>here</a> to change your email.");
                }
                unset($data['email']);
//                if ($data['user_name'] != $this->__account->user_name) {
//                    $this->__model->save(array('user_id' => $this->__account->user_id, 'verification_code' => $verification_code));
//                    $link = $this->__get_base_url() . "profile/username?c=" . urlencode($verification_code) . "&v=" . urlencode($data['user_name']);
//                    $this->sendMailUsingTemplate($this->__account, 'username-changed-my-account', array('username_change_url' => $link));
//                    $this->addSuccessMessage('An email with a link to change your username has been sent to your email address. Please check your email.');
//                    //$this->addDebugMessage("Click <a href='$link'>here</a> to change your username.");
//                }
//                unset($data['user_name']);
                if (strlen($data['password']) > 0) {
                    $password = md5($data['password']);
                    $this->__model->save(array('user_id' => $this->__account->user_id, 'verification_code' => $verification_code));
                    $link = $this->__get_base_url() . "profile/password?c=" . urlencode($verification_code) . "&v=" . urlencode($password);
                    $this->sendMailUsingTemplate($this->__account, 'password-changed-my-account', array('password_change_url' => $link));
                    $this->addSuccessMessage('An email with a link to change your password has been sent to your email address. Please check your email.');
                    //$this->addDebugMessage("Click <a href='$link'>here</a> to change your password.");
                }
                unset($data['password']);

                if (!(isset($data['use_billing_info']))) {
                    $data['use_billing_info'] = '';
                }
                $data['user_id'] = $this->__entity->user_id;
                if ($this->__model->save($data)) {
                    $email = $this->input->post('email');
                    $this->sendMail($email, 'Profile Update', 'You have updated your profile');
                    $this->addSuccessMessage('You have updated your profile successfully.');
                    $this->notify($this->__entity, 'You have updated your profile successfully.', 'account/profile');
                    $user = $this->__model->entity($this->__entity->user_id);
                    $this->__set_account($user);
                    $this->redirect($this->__module_name . '/profile');
                } else {
                    $this->addErrorMessage('Unknown error while updating your profile.');
                }
            }

            $this->fillEntity($_POST);
        } else {
            $type = $this->uri->segment(3);
            $types = array('email', 'username', 'password');
            if (in_array($type, $types) && $code = $this->input->get('c')) {
                if ($user = $this->verify($code)) {
                    $val = $this->input->get('v');
                    $this->__set_account($user);
                    if ($type == 'email') {
                        $this->__save_account(array('email' => $val));
                        $this->addSuccessMessage('You have updated your email address successfully.');
//                    } else if ($type == 'username') {
//                        $this->__save_account(array('user_name' => $val));
//                        $workspaces = $this->__get_workspaces();
//                        if ($workspaces && sizeof($workspaces) > 0) {
//                            foreach ($workspaces as $id => $workspace) {
//                                if (isset($workspace['ws_domain']) && $workspace['ws_domain']) {
//                                    if ($domain = $this->getSandboxServer()->rename($workspace['ws_domain'], $val, $workspace['ws_name'])) {
//                                        $workspace['ws_domain'] = $domain;
//                                        $workspaces[$id] = $workspace;
//                                    } else {
//                                        $this->addErrorMessage('Failed to rename the sandbox domain for the workspace.');
//                                        $this->processSandboxServerError();
//                                    }
//                                }
//                            }
//                            $this->__save_option('ws', json_encode($workspaces, true));
//                        }
//                        $this->addSuccessMessage('You have updated your username successfully.');
                    } else if ($type == 'password') {
                        $this->__save_account(array('password' => $val));
                        $this->addSuccessMessage('You have updated your password successfully.');
                    }
                    $this->setEntity($this->__model->entity($this->__account->id));
                } else {
                    $this->addErrorMessage('Invalid link.');
                    $this->redirect(base_url());
                }
            } else if (true/* first time for social signup */) {
                //$this->AddMessage("Please update your profile information. Since this is the first time you've signed in using your social account, you need to update your profile information. Your account will not be fully registered until you complete the required items below.", null, 'update');
            }
        }

        $this->__set_page_title('Edit My Profile');
        $this->addToResponseData('sub_module', 'profile');
        $this->view($this->__module_name . '/profile');
    }

    public function email_check($str) {
        if ($user = $this->__model->get_by_email($str)) {
            if ($user->user_id != $this->__account->id) {
                $this->addValidationError('email_check', 'Email address is already in use. If you have already signed up, please log in, or sign up with another email address.');
                return FALSE;
            }
        }

        return TRUE;
    }

    public function username_check($str) {
        $invalid_names = explode(',', $this->__options['editor_disallow_username']);
        if (in_array($str, $invalid_names)) {
            $this->addValidationError('username_check', 'Username is not allowed.');
            return FALSE;
        }
        if ($user = $this->__model->get_by_name($str)) {
            if ($user->user_id != $this->__account->id) {
                $this->addValidationError('username_check', 'Username is already in use. If you\'ve already signed up, please log in, or register with another username.');
                return FALSE;
            }
        }

        return TRUE;
    }

    public function password_confirm($confirm, $password) {
        if ($confirm != $password) {
            $this->addValidationError('password_confirm', 'Password is not confirmed. Please try again.');
            return FALSE;
        }

        return TRUE;
    }

    public function membership() {
        $this->__set_page_title('Membership Details');
        $this->addToResponseData('plan', $this->____load_model('PlanModel')->entity($this->__account->user_type));
        $this->addToResponseData('sub_module', 'membership');
        $this->view($this->__module_name . '/membership');
    }

    public function paymentmethod() {
        $this->loadBrainTree();

        $action = $this->uri->segment(3);
        $token = $this->uri->segment(4);
        if ($token) {    //processes additional actions
            if ($action == 'delete') {  //deletes a payment method
                $this->delete_paymentMethod($token);
            } else if ($action == 'makeDefault') {  //make a payment method default
                $this->makeDefault_paymentMethod($token);
            }
        }

        $bt_customer_id = $this->__account->bt_customer_id;

        if ($this->isPost()) {  //adding new payment method
            $billingAddress = new stdClass(); //initialize billingAddress object
            //Getting parameters
            $this->__fillObject($billingAddress, $_POST, array('firstName', 'lastName', 'streetAddress', 'locality', 'region', 'countryCodeAlpha2'));

            $paymentMethodNonce = $this->input->post('payment_method_nonce');
            $isPaypal = false;
            if (!$paymentMethodNonce) {
                $paymentMethodNonce = $this->input->post('payment_method_nonce_paypal');
                if ($paymentMethodNonce) { // we don't need billing address for paypal
                    $billingAddress = null;
                    $isPaypal = true;
                }
            }

            //Adds new payment method
            if ($action == 'update') {
                try {
                    $billingAddress->options = array('updateExisting' => true);
                    $creditCard = array(
                        'paymentMethodNonce' => $paymentMethodNonce,
                        'billingAddress' => (array) $billingAddress,
                        'options' => array(
                            'makeDefault' => $this->input->post('defaultPaymentMethod') == 1,
                            'verifyCard' => true,
                        )
                    );
                    $result = Braintree_PaymentMethod::update($token, $creditCard);
                    if ($result->success) {
                        $this->addSuccessMessage('You have successfully updated your payment method.');
                        $this->notify($this->__account, 'You have successfully updated your payment method.', 'account/paymentmethod');
                        if ($returnUrl = $this->input->get('returnUrl')) {
                            $this->redirect($returnUrl);
                        } else {
                            $this->redirect($this->__get_base_url() . 'paymentmethod');
                        }
                    } else {
                        $this->flushBraintreeErrors($result);
                    }
                } catch (Exception $ex) {
                    $this->processBraintreeException($ex);
                }
            } else {
                if (!$bt_customer_id) { //create a new customer on bt if no one exists
                    $result = Braintree_Customer::create(array(
                                'firstName' => $this->__account->first_name,
                                'lastName' => $this->__account->last_name,
                                'email' => $this->__account->email,
                    ));
                    if ($result->success) {
                        $this->__save_account(array(
                            'bt_customer_id' => $bt_customer_id
                        ));
                    } else {
                        $this->flushBraintreeErrors($result);
                    }
                }
                try {
                    $creditCard = array(
                        'customerId' => $bt_customer_id,
                        'paymentMethodNonce' => $paymentMethodNonce,
                        'billingAddress' => (array) $billingAddress,
                        'options' => array(
                            'makeDefault' => $this->input->post('defaultPaymentMethod') == 1,
                            'verifyCard' => true,
                            'failOnDuplicatePaymentMethod' => true
                        )
                    );
                    $result = Braintree_PaymentMethod::create($creditCard);
                    if ($result->success) {
                        $this->addSuccessMessage('You have successfully added your payment method.');
                        $this->notify($this->__account, 'You have successfully added your payment method.', 'account/paymentmethod');
                        $this->redirect($this->__module_name . '/paymentmethod');
                    } else {
                        $this->flushBraintreeErrors($result);
                    }
                } catch (Exception $ex) {
                    $this->processBraintreeException($ex);
                }
            }
        }

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
        $billingAddress = new stdClass();
        if ($token) {
            try {
                if ($paymentMethod = Braintree_PaymentMethod::find($token)) {
                    //var_dump($paymentMethod);exit;
                    $this->__fillObject($creditCard, (array) $paymentMethod->_attributes, array('cardholderName', 'maskedNumber', 'expirationYear', 'expirationMonth'));
                    $creditCard->number = $creditCard->maskedNumber;
                    if ($paymentMethod->billingAddress) {
                        $this->__fillObject($billingAddress, (array) $paymentMethod->billingAddress->_attributes, array('firstName', 'lastName', 'streetAddress', 'locality', 'region', 'countryCodeAlpha2', 'postalCode'));
                    }
                }
            } catch (Exception $ex) {
                $this->processBraintreeException($ex);
            }
            $this->addToResponseData('token', $token);
        } else {
            $user = $this->getUserModel()->entity($this->__account->user_id);
            $creditCard->cardholderName = $user->first_name . ' ' . $user->last_name;
            if ($user->use_billing_info) { //use same address personal address
                $billingAddress->firstName = $user->first_name;
                $billingAddress->lastName = $user->last_name;
                $billingAddress->streetAddress = $user->address;
                $billingAddress->locality = $user->city;
                $billingAddress->region = $user->state_code;
                $billingAddress->countryCodeAlpha2 = $user->country_code;
                $billingAddress->postalCode = $user->zip;
            } else {
                $billingAddress->firstName = $user->billing_first_name;
                $billingAddress->lastName = $user->billing_last_name;
                $billingAddress->streetAddress = $user->billing_address;
                $billingAddress->locality = $user->billing_city;
                $billingAddress->region = $user->billing_state_code;
                $billingAddress->countryCodeAlpha2 = $user->billing_country_code;
                $billingAddress->postalCode = $user->billing_zip;
            }
            if (DEBUG) { //sample data for debugging
                $creditCard->number = '4111111111111111';
                $creditCard->cvv = '100';
                $creditCard->expirationYear = '2016';
                $creditCard->expirationMonth = '12';
//                $billingAddress->streetAddress = 'Street Address ABC';
//                $billingAddress->locality = 'New York';
//                $billingAddress->region = 'CA';
//                $billingAddress->countryCodeAlpha2 = 'US';
//                $billingAddress->postalCode = '34344';
            }
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

        $this->__set_page_title('Payment Method');
        $this->addScripts('https://js.braintreegateway.com/v2/braintree.js', true);
        $this->addScripts('themes/frontend/monocode/js/paymentmethod.js');
        $this->addToResponseData('sub_module', 'paymentmethod');
        $this->view($this->__module_name . '/payment/method');
    }

    //[2015-06-12 D.A. Zhen] deletes a payment method
    private function delete_paymentMethod($token) {
        try {
            Braintree_PaymentMethod::delete($token);
            $this->addSuccessMessage('You have deleted a payment method successfully.');
            $this->notify($this->__account, 'You have deleted a payment method successfully.', 'account/paymentmethod');
        } catch (Exception $ex) {
            $this->processBraintreeException($ex);
        }
        if ($returnUrl = $this->input->get('returnUrl')) {
            $this->redirect($returnUrl);
        } else {
            $this->redirect($this->__get_base_url() . 'paymentmethod');
        }
    }

    //[2015-06-12 D.A. Zhen] makes a payment method as default
    private function makeDefault_paymentMethod($token) {
        try {
            Braintree_PaymentMethod::update($token, array('options' => array('makeDefault' => true)));
            $this->addSuccessMessage('You have set a default payment method successfully.');
            $this->notify($this->__account, 'You have set a default payment method successfully.', 'account/paymentmethod');
        } catch (Exception $ex) {
            $this->processBraintreeException($ex);
        }
        if ($returnUrl = $this->input->get('returnUrl')) {
            $this->redirect($returnUrl);
        } else {
            $this->redirect($this->__get_base_url() . 'paymentmethod');
        }
    }

    public function paymentstatus() {
        $this->addToResponseData('sub_module', 'paymentstatus');
        $this->__set_page_title('Payment Status');
        $userModel = $this->____load_model('UserModel');
        $user = $userModel->entity($this->__account->id);
        $plan = $this->____load_model('PlanModel')->entity($user->user_type);
        $this->addToResponseData('plan', $plan);

        if ($user->user_type == FREE_PLAN_ID || $user->trial_taken == TRIAL_BEING_TAKEN) { //just shows simple message for free plan and trial
            return $this->view($this->__module_name . '/payment/status');
        }

        $subscription = $this->getSubscriptionModel()->entity($user->subscription_id);
        if (!$subscription && $user->user_type > FREE_PLAN_ID) { //impossible, but problem if there's no subscription object
            $this->addErrorMessage('There are some problems with your subscription. Please contact the administrator.');
            return $this->view($this->__module_name . '/payment/status');
        }
        $this->addToResponseData('subscription', $subscription);

        $expiredDays = (strtotime(gmdate('Y-m-d')) - strtotime($subscription->next_billing_date)) / (3600 * 24); //calculating expired days by today - next billing date
        $this->addToResponseData('expiredDays', $expiredDays);
        if ($expiredDays < 0 || $subscription->bt_subscription_id) { //just shows status page if not expired or recurring plan (recurring plan will be paid on bt automatically)
            return $this->view($this->__module_name . '/payment/status', array('subscription' => $subscription));
        }

        $this->loadBrainTree();
        $bt_customer_id = $user->bt_customer_id;
        if ($this->isPost()) {
            $billingAddress = new stdClass(); //initialize billingAddress object
            //Getting parameters
            $this->__fillObject($billingAddress, $_POST, array('firstName', 'lastName', 'streetAddress', 'locality', 'region', 'countryCodeAlpha2'));
            $isSavingPaymentMethod = $this->input->post('savePaymentMethod'); //Option to save payment method
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
                if ($isSavingPaymentMethod) { //and saving option is checked or recurring plan
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
                        if (!DEBUG) {
                            $creditCard['options']['failOnDuplicatePaymentMethod'] = true;
                        }
                        $result = Braintree_PaymentMethod::create($creditCard);
                        if ($result->success) {
                            $ccToken = $result->paymentMethod->token;
                            $this->addSuccessMessage('You have successfully added a new payment method.');
                            $this->notify($user, 'You have successfully added a new payment method.', 'account/paymentmethod');
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

            $data = array(
                'amount' => $this->calculateSubscriptionFee($subscription),
                'orderId' => $subscription->order_id,
                'options' => array(
                    'submitForSettlement' => true
                )
            );
            if ($ccToken) {    //we use customer id of bt if it exists
                $data['paymentMethodToken'] = $ccToken;
            } else {    //or we just send payment method nonce and billing address
                $data['paymentMethodNonce'] = $paymentMethodNonce;
                if ($billingAddress) {
                    $data['billingAddress'] = (array) $billingAddress;
                }
            }

            $result = Braintree_Transaction::sale($data);
            if ($result->success) {
                $this->__set_account($user);
                $this->addSuccessMessage('Payment has been posted successfully.');
                $this->notify($user, 'Payment has been posted successfully.', 'account/paymentstatus');
                if ($this->isAccountSuspended() && $this->__account->inactive_reason == SUSPENDED_REASON_PAYMENT_DUE) {
                    $this->activateUser($this->__account);
                    $this->notify($this->__account, 'Your account has been activated now.');
                    $this->addSuccessMessage('Your account has been activated now.');
                }
                $this->saveTransactions($result->transaction);
                $this->redirect($this->__module_name . '/paymentstatus');
            } else {
                $this->sendMailWithInvoiceUsingTemplate($user, $subscription->last_invoice_number, 'payment-unsuccess');
                $this->flushBraintreeErrors($result);
            }
        }

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
        if (DEBUG) { //sample data for debugging
            $creditCard = new stdClass();
            $creditCard->cardholderName = $user->first_name . ' ' . $user->last_name;
            $creditCard->number = '4111111111111111';
            $creditCard->cvv = '100';
            $creditCard->expirationYear = '2016';
            $creditCard->expirationMonth = '12';
            $billingAddress = new stdClass();
            $billingAddress->firstName = $user->first_name;
            $billingAddress->lastName = $user->last_name;
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


        if ($this->isAccountSuspended() && $this->__account->inactive_reason == SUSPENDED_REASON_PAYMENT_DUE) {
            $this->addWarningMessage('Your account is in suspended status now.');
        }
        $this->__set_page_title('Payment Checkout');
        $this->view($this->__module_name . '/payment/checkout');
    }

    public function editorsettings() {
        $this->__set_page_title('Editor Settings');
        $this->view($this->__module_name . '/editorsettings');
    }

    public function invoices() {
        $invoiceModel = $this->____load_model('InvoiceModel');
        $params = array();
        $status = $this->input->get('status');
        if ($status) {
            $params['status'] = $status > 0 ? $status : 0;
        }
        $icpp = $this->input->get('icpp');
        if (!$icpp) {
            $icpp = PER_PAGE;
        }
        $this->paginate($this->__get_base_url() . 'invoices/', $invoiceModel->__total_count($params), $icpp, 'status=' . $status . '&icpp=' . $icpp);
        $this->setEntities($invoiceModel->search($params, $icpp, $this->uri->segment(3)));
        $this->__set_page_title('View Invoices');
        $this->addToResponseData('sub_module', 'invoices');
        $this->view($this->__module_name . '/invoices', array('status' => $status, 'icpp' => $icpp));
    }

    public function invoice($invoiceNumber = null, $action = null) {
        if (!$invoiceNumber || !($invoice = $this->____load_model('InvoiceModel')->get_by_number($invoiceNumber))) {
            die('Invaid Invoice.');
        }

        $this->__layout = null;
        if ($action == 'download') {
            $file = $this->createInvoiceFile($invoiceNumber);
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=Invoice#" . $invoice->invoice_number . '.pdf');
            header("Content-Type: application/pdf");
            header("Content-Transfer-Encoding: binary");
            readfile($file);
            unlink($file);
            exit;
        }

        $this->view($this->__module_name . '/invoice', array('invoice' => $invoice, 'user' => $this->getUserModel()->entity($invoice->user_id), 'plan' => $this->____load_model('PlanModel')->entity($invoice->plan_id)));
    }

    public function cancel() {
        $type = $this->uri->segment(3);
        if ($type == 'trial') {
            $this->cancelTrial();
            return $this->redirect($this->__module_name . '/paymentstatus');
        } else if ($type == 'account') {
            if ($this->uri->segment(4) != 'confirm') {
                $this->sendMailUsingTemplate($this->__account, 'close-account-requested-my-account', array('confirm_url' => $this->__get_base_url() . 'cancel/account/confirm'));
                $this->addDebugMessage('Please click <a href="' . $this->__get_base_url() . 'cancel/account/confirm">here</a> to cancel your account.');
                $this->addSuccessMessage('An email has been sent to you to confirm cancellation of your account. Please check your email.');
            } else {
                $archiveURL = null;
                $workspaces = array_keys($this->__get_workspaces());
                if (count($workspaces) > 0) {
                    $paths = array();
                    foreach ($workspaces as $workspace) {
                        $paths[] = $this->getWorkshop() . $workspace . '/';
                    }
                    //[2015-04-10] Archives workshop
                    $name = gmdate('Y-m-d') . '-' . $this->__account->workshop . '.zip'; //contains date and user workshop id
                    $archiveFile = $this->getArchiveDir() . $name;
                    $this->load->library('Zipper', null, 'zipper');
                    if ($this->isStorageLocal()) {
                        $this->zipper->create($archiveFile, $paths);
                    } else {
                        $fileList = array();
                        foreach ($paths as $key => $path) {
                            $f = $this->getAWSFileModel()->getByPath($path);
                            $fileList[$key] = ($f->type == 'dir') ? $this->getAWSFileList($path) : null; //if the file is dir, loads the list of files in the directory
                        }
                        $this->zipper->createFromAWS($archiveFile, $paths, $fileList, $this->getAWSServer(), $this->getTempDir());
                    }
                    $archiveURL = base_url() . ARCHIVE_DIR . '/' . $name;

                    //[2015-04-10] Removes workspaces and workshop
                    $count = $this->__remove_workspaces($workspaces);
                    $this->__remove_workshop();
                    $this->addInfoMessage('All of your ' . $this->__tweakForPlural($count, 'workspace') . ' deleted.');
                }
                if (isset($this->__options['db_user'])) {
                    $this->__load_db_user();
                    $databases = $this->__get_databases();
                    if (count($databases) > 0) {
                        $count = $this->__remove_databases($databases);
                        $this->addInfoMessage('All of your ' . $this->__tweakForPlural($count, 'database') . ' deleted.');
                    }
                }

                $user = $this->__model->entity($this->__account->id);
                if ($user->bt_customer_id) {
                    if ($user->subscription_id) {
                        $this->loadBrainTree();
                        $subscription = $this->getSubscriptionModel()->entity($user->subscription_id);
                        if ($subscription->bt_subscription_id) {
                            Braintree_Subscription::cancel($subscription->bt_subscription_id);
                        }
                        $this->addInfoMessage('Your subscription has been cancelled.');
                        $this->cancelSubscription($subscription);
                    }
                    Braintree_Customer::delete($user->bt_customer_id);
                    $this->addInfoMessage('You have been unregistered from Braintree.');
                }
                if (($count = $this->____load_model('OptionModel')->deleteByUser($this->__account->id)) > 0) {
                    $this->addInfoMessage('All of your ' . $this->__tweakForPlural($count, 'option') . ' deleted.');
                }
                $this->__save_account(array('status' => 'cancelled', bt_customer_id => null, 'subscription_id' => null));
                $this->addSuccessMessage('You have been cancelled successfully.');
                $msg = 'You have successfully cancelled your account at monocode.io.';
                if ($archiveURL) {
                    $this->addDebugMessage('<a href="' . $archiveURL . '">Here</a> is your archived data.');
                    $msg .= '<br />' . '<a href="' . $archiveURL . '">Here</a> is your archived data.';
                }
                $this->sendMailUsingTemplate($this->__account, 'account-termination-success', array('archive_url' => $archiveURL));
                $this->session->unset_userdata('account');

                //[DEPRECATED]
                $this->session->unset_userdata("loggedin");

                $this->redirect($this->__module_name . '/cancelled');
            }
        }

        $this->__set_page_title('Cancel Account');
        $this->addToResponseData('sub_module', 'cancel');
        $this->view($this->__module_name . '/cancel');
    }

    public function cancelled() {
        $this->__layout = 'default';
        $this->__set_page_title('Account Cancellation');
        $this->view($this->__module_name . '/cancelled');
    }

    public function notifications() {
        $icpp = $this->input->get('icpp');
        if (!$icpp) {
            $icpp = PER_PAGE;
        }

        $notificationModel = $this->____load_model('NotificationModel');
        $this->paginate($this->__get_base_url() . 'notifications/', $notificationModel->__total_count(), $icpp, 'icpp=' . $icpp);
        $this->setEntities($notificationModel->search(null, $icpp, $this->uri->segment(3)));
        $this->__set_page_title('Notifications');
        $this->addToResponseData('sub_module', 'notifications');
        $this->view($this->__module_name . '/notifications', array('icpp' => $icpp));
    }

    public function files() {
        if ($this->isAccountSuspended()) {
            if ($this->uri->segment(3) == 'validate') {
                if ($this->validateFilesAndSpaces()) {
                    if ($this->__account->inactive_reason == SUSPENDED_REASON_TOO_MANY_FILES || $this->__account->inactive_reason == SUSPENDED_REASON_TOO_MUCH_SPACES) {
                        $this->activateUser($this->__account);
                        $this->notify($this->__account, 'Your account has been activated now.');
                        $this->addSuccessMessage('Your account has been activated now.');
                    }
                } else {
                    $this->addErrorMessage('You still need to clear more files.');
                }
                $this->redirect($this->__get_base_url() . 'files');
            } else {
                $this->addWarningMessage('Your account is in suspended status now.', null, true);
            }
        }
        $workspaces = $this->__get_workspaces();
        if ($ws = $this->input->get('ws')) {
            $workspace = $this->__get_workspace($ws);
        } else {
            $workspace = $this->__get_active_workspace();
        }

        $this->setEntities($workspaces);
        $this->setEntity($workspace);

        if ($this->isAccountSuspended() && ($this->__account->inactive_reason == SUSPENDED_REASON_TOO_MANY_FILES || $this->__account->inactive_reason == SUSPENDED_REASON_TOO_MUCH_SPACES)) {
            $this->addInfoMessage("Please click <a href='" . $this->__get_base_url() . "files/validate'>here</a> to activate your account once you clear your files.", null, true);
        }
        $this->__set_page_title('File Explorer');
        $this->view($this->__module_name . '/files', null, 'default');
    }

}

?>