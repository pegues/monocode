<?php

require_once 'EcommerceController.php';

class Guest extends EcommerceController {

    public function __construct() {
        $this->__need_authentication = false;
        parent::__construct();
        if (strpos(current_url(), 'https://') !== 0) {
            redirect(str_replace('http://', 'https://', current_url()));
        }
        if ($this->isLoggedIn() && $this->uri->rsegments[2] != 'logout') {
            $this->redirect();
        }
        $this->__load_model('UserModel');
        $this->__layout = 'guest';
        $this->__set_module_name(MODULE_NAME_GUEST);
    }

    public function index() {
        $this->redirect($this->__module_name . '/login');
    }

    public function login() {
        if ($this->isPost()) {
            $this->addValidationRule('username', 'User Name', 'trim|required');
            $this->addValidationRule('password', 'Password', 'required|callback_password_check[' . $this->input->post('username') . ']');

            $failed = (int) $this->session->userdata('failed');
            if ($this->validateForm($failed >= 2)) {
                $this->session->unset_userdata('failed');
                $this->doAfterLogin();
            } else {
                $failed ++;
                $this->session->set_userdata('failed', $failed);
                $this->addToResponseData('failed', $failed);

                if ($failed == 5) {
                    $username = $this->input->post('username');
                    if ($user = $this->__model->get_by_email_or_name($username)) {
                        if ($user->status == USER_STATUS_ACTIVE) {
                            $this->suspendUser($user, "$failed failed login attempts.", true);
                            $this->addErrorMessage("Your account '$username' has been blocked because of $failed failed login attempts.");
                            $this->sendMailUsingTemplate($user, 'account-failed-login-suspended-notice');
                        }
                    }
                }
            }
            $this->addToResponseData($_POST);
        } else {
            $this->saveReturnUrl();
        }

        $this->__set_page_title('Account Login');
        $this->view($this->__module_name . '/login');
    }

    function password_check($password, $email) {
        $user = $this->__model->get_by_email_or_name($email);
        if (sizeof($user) <= 0) {
            //$this->my_form_validation->set_message('password_check', 'Invalid e-mail address. Please try again.');
            $this->addValidationError('password_check', 'Username or password is incorrect. Please try again.');
            return FALSE;
        }

        if (strcmp(md5($password), $user->password) != 0) {
            //$this->my_form_validation->set_message('password_check', 'The password is incorrect.');
            $this->addValidationError('password_check', 'Username or password is incorrect. Please try again.');
            return FALSE;
        }

        if ($user->status != USER_STATUS_ACTIVE && $user->status != USER_STATUS_SUSPENDED) {
            if ($user->status == USER_STATUS_PENDING) {
                $this->addValidationError('password_check', 'Your account is pending. Please verify your email.');
                $this->addInfoMessage('Please click <a href="' . $this->__get_base_url() . 'resend">here</a> if you have not received the confirmation email yet.');
            } else {
                $this->addValidationError('password_check', 'Your account is blocked. Please contact the site administrator.');
            }
            return FALSE;
        }

        $this->__set_account($user);

        return TRUE;
    }

    public function saveReturnUrl() {
        $returnUrl = $this->input->get('returnUrl');
        if ($returnUrl && strlen($returnUrl) > 0) {
            $this->session->set_userdata('returnUrl', $returnUrl);
        } else {
            $this->session->unset_userdata('returnUrl');
        }
    }

    public function loadReturnUrl() {
        $returnUrl = $this->session->userdata('returnUrl');
        if ($returnUrl && strlen($returnUrl) > 0) {
            $this->session->unset_userdata('returnUrl');
            return $returnUrl;
        }

        return null;
    }

    public function doAfterLogin($is_social = false, $is_signup = false) {
        $this->setLastLoginTime();
        if (!$this->isLocallyDeveloping()) {
            $this->startSandboxes();
        }
        $this->redirectAfterLogin($is_social, $is_signup);
    }

    public function setLastLoginTime() {
        $time = gmdate('Y-m-d H:i:s');
        $this->____load_model('UserModel')->save(array(
            'user_id' => $this->__account->id,
            'last_login_time' => $time,
            'last_active_time' => $time
        ));
    }

    public function redirectAfterLogin($is_social = false, $is_signup = false) {
        $redirectUrl = null;

        if ($this->isAccountSuspended()) {
            if ($this->__account->inactive_reason == SUSPENDED_REASON_PAYMENT_DUE) {
                $subscription = $this->getSubscriptionModel()->entity($this->__account->subscription_id);
                $amount = $subscription ? $this->calculateSubscriptionFee($subscription) : 0;
                $this->addInfoMessage("You've been directed here because you have a balance that is past due. The amount is $$amount. Please submit payment now to restore full functionality to your account.");
                $this->redirect('account/paymentStatus');
            } else if ($this->__account->inactive_reason == SUSPENDED_REASON_TOO_MANY_FILES) {
                $this->addInfoMessage("You've been directed here because you have too many files. Please delete the necessary number of files to restore your account to full functionality.");
                $this->redirect('account/files');
            } else if ($this->__account->inactive_reason == SUSPENDED_REASON_TOO_MUCH_SPACES) {
                $this->addInfoMessage("You've been directed here because you're using more space than your account allows. Please delete the necessary number of files to restore your account to full functionality.");
                $this->redirect('account/files');
            }
        }

        if ($returnUrl = $this->loadReturnUrl()) {
            $redirectUrl = $returnUrl;
        } else {
            $path = null;
            if (!$is_social) {
                if ($is_signup) {
                    $path = $this->__options['signup_page_redirect'];
                } else {
                    $path = $this->__options['login_page_redirect'];
                }
            } else {
                if ($is_signup) {
                    $path = $this->__options['social_signup_page_redirect'];
                } else {
                    $path = $this->__options['social_login_page_redirect'];
                }
            }
            $redirectUrl = $this->getUrlByPath($path);
        }

        $this->redirect(str_replace('http://', 'https://', $redirectUrl));
    }

    private function getUrlByPath($path) {
        if ($path == 'aboutus') {
            $url = ('aboutus');
        }

        // $url =  user to cancel-account on login
        else if ($path == 'cancelaccount') {
            $url = ('account/cancel');
        }

        // $url =  user to contact-us on login
        else if ($path == 'contactus') {
            $url = ('contactus');
        }

        // $url =  user to editor on login
        else if ($path == 'editor') {
            $url = ('editor');
        }

        // $url =  user to homepage on login
        else if ($path == 'homepage') {
            $url = '';
        }

        // $url =  user to marketing on login
        else if ($path == 'marketing') {
            $url = ('ourservices/marketing');
        }

        // $url =  user to notifications on login
        else if ($path == 'notifications') {
            $url = ('account/notifications');
        }

        // $url =  user to our-services on login
        else if ($path == 'ourservices') {
            $url = ('ourservices');
        }

        // $url =  user to plans on login
        else if ($path == 'plans') {
            $url = ('membership');
        }

        // $url =  user to profile on login
        else if ($path == 'profile') {
            $url = ('account/profile');
        }

        // $url =  user to settings on login
        else if ($path == 'settings') {
            $url = ('account/editorsettings');
        }

        // $url =  user to upgrade-account on login
        else if ($path == 'upgradeaccount') {
            $url = ('membership');
        }

        // $url =  user to view-membership on login
        else if ($path == 'viewmembership') {
            $url = ('account/paymentstatus');
        }

        // $url =  user to web-design on login
        else if ($path == 'webdesign') {
            $url = ('ourservices/webdesign');
        }

        // $url =  user to web-development on login
        else if ($path == 'webdevelopment') {
            $url = ('urservices/webdev');
        }

        // $url =  user to homepage on login if no other conditions met
        else {
            $url = '';
        }

        return $url;
    }

    // Logout Function
    public function logout() {
        $this->stopSandboxes();

        $this->session->unset_userdata('account');

        //[DEPRECATED]
        $this->session->unset_userdata("loggedin");

        $session_name = 'PMASignonSession';
        session_name($session_name);
        session_start();
        session_destroy();

        $this->redirect(str_replace('https://', 'http://', base_url() . $this->getUrlByPath($this->__options['logout_page_redirect'])));
    }

    public function getNewWorkshop() {
        $try = 0;
        while ($try++ < 5) {
            $workshop = 'user-' . UUID::v4();
            $subId = substr($workshop, 0, 18);
            if ($this->__model->exist_sub_id($subId) > 0) {
                continue;
            }

            return $workshop;
        }

        return null;
    }

    public function checkUsernameAvailable() {
        $this->addValidationRule('user_name', 'User Name', 'trim|required|callback_username_check');
        $this->validateForm();
        $this->ajaxResponse();
    }

    // Register Function
    public function register() {
        if ($this->isPost()) {
            $data = $_POST;
            $this->addValidationRule('email', 'E-mail Address', 'trim|required|callback_email_check');
            $this->addValidationRule('user_name', 'User Name', 'trim|required|callback_username_check');
            $this->addValidationRule('passwordconfirm', 'Password Confirmation', 'callback_password_confirm[' . $data['password'] . ']');
            if ($this->input->post('address') == '' && $this->validateForm(true)) {
                unset($data['passwordconfirm']);
                unset($data['g-recaptcha-response']);
                $email = $this->input->post('email');
                if (!($workshop = $this->getNewWorkshop())) {
                    $this->addErrorMessage('Workshop cannot be created. Please contact to the site administrator.');
                } else {
                    $verification_code = $this->createVerificationCode($data['user_name'], $data['email']);
                    $data['password'] = md5($data['password']);
                    $data['verification_code'] = $verification_code;
                    $data['status'] = 'pending';
                    $data['user_type'] = FREE_PLAN_ID;
                    $data['workshop'] = $workshop;
                    $data['registered_date'] = date("Y-m-d H:i:s");

                    if ($this->__model->save($data)) {
                        $display_name = $data['first_name'] . ' ' . $data['last_name'];
                        $activation_url = base_url() . 'guest/activate?c=' . urlencode($verification_code);
                        $this->sendMailUsingTemplate($email, 'registration-email-confirmation', array('activation_url' => $activation_url));
                        $this->sendMailUsingTemplate($this->__settings->email_address, 'new-registration-email-for-admin', array('username' => $display_name));
                        $this->addSuccessMessage('Registration Successful!!!');
                        $this->addDebugMessage("Click <a href='$activation_url'>here</a> to activate.");
                        $this->redirect($this->__module_name . '/regsuccessful');
                    }
                }
            }

            $this->addToResponseData($_POST);
        }
        $this->__set_page_title('User Registration');
        $this->view($this->__module_name . '/register');
    }

    public function email_check($str) {
        if ($this->__model->get_by_email($str)) {
            $this->addValidationError('email_check', 'E-mail address is already in use. If you\'ve already signed up, please log in, or register using another e-mail address.');
            return FALSE;
        }

        return TRUE;
    }

    public function username_check($str) {
        $invalid_names = explode(',', $this->__options['editor_disallow_username']);
        if (in_array($str, $invalid_names)) {
            $this->addValidationError('username_check', 'Username is not allowed.');
            return FALSE;
        }
        if ($this->__model->get_by_name($str)) {
            $this->addValidationError('username_check', 'Username is already in use. If you\'ve already signed up, please log in, or register with another username.');
            return FALSE;
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

    public function regsuccessful() {
        $this->__set_page_title('Congratulation');
        $this->view($this->__module_name . '/congrat');
    }

    public function resend() {
        if ($this->isPost()) {
            $email = $this->input->post('email');
            if (!($user = $this->__model->get_by_email($email))) {
                $this->addErrorMessage('Invalid E-mail address.');
            }
            if (!$this->hasError()) {
                $verification_code = $user->verification_code;
                $activation_url = $this->__get_base_url() . 'activate?c=' . urlencode($verification_code);
                $this->sendMailUsingTemplate($user, 'registration-email-confirmation', array('activation_url' => $activation_url));
                $this->addSuccessMessage('Confirmation email has been sent successfully!');
                $this->addDebugMessage("Click <a href='$activation_url'>here</a> to activate.");
            }
            $this->addToResponseData($_POST);
        }

        $this->__set_page_title('Email Confirmation');
        $this->view($this->__module_name . '/resend');
    }

    public function activate() {
        $code = $this->input->get('c');
        if (!($user = $this->verify($code))) {
            $this->addErrorMessage('Invalid verification code. Please register again or click <a href="' . $this->__get_base_url() . 'resend">here</a> to resend the confirmation email if you have already registered.');
        } else if ($user->status == USER_STATUS_ACTIVE) {
            $this->addErrorMessage('Your email is already verified. You can now log in.');
        } else if ($user->status == USER_STATUS_PENDING) {
            $user->subscription_id = $this->addFreeSubscription($user->user_id);
            $this->__model->save(array('user_id' => $user->user_id, 'status' => 'active', 'verification_code' => '', 'subscription_id' => $user->subscription_id));
            $this->addSuccessMessage('Your email has been verified successfully.');
            $this->sendMailUsingTemplate($user, 'registration-success', array('username' => $user->user_name));
            $this->__set_account($user);
            $this->makeReadyForEditor($user);
            $this->doAfterLogin(false, true);
        } else {
            $this->addErrorMessage('Failed to activate your account. Please contact the site administrator.');
        }

        $this->redirect($this->__get_base_url() . 'resend');
    }

    public function makeReadyForEditor($user) {

        $this->__create_workshop($user->workshop);
        if (!($this->__add_workspace('Workspace - 1'))) {
            $this->addErrorMessage('Failed to create a new workspace.');
        }
    }

    public function reset() {
        if ($this->isPost()) {
            $email = $this->input->post('email');
            if (!($user = $this->__model->get_by_email($email))) {
                $this->addErrorMessage('Invalid E-mail address.');
            }
            if (!$this->hasError()) {
                $verification_code = $this->createVerificationCode($user->user_name, $user->email);
                $this->__model->save(array('user_id' => $user->user_id, 'verification_code' => $verification_code));
                $link = $this->__get_base_url() . "reset?c=" . urlencode($verification_code);
                $this->sendMailUsingTemplate($user, 'forgot-password', array('reset_url' => $link));
                $this->addSuccessMessage('The link to reset your password has been sent to your email.');
                $this->addDebugMessage("Click <a href='$link'>here</a> to reset your password.");
            }
            $this->addToResponseData($_POST);
        } else {
            if ($code = $this->input->get('c')) {
                if ($user = $this->verify($code)) {
                    $this->__set_account($user);
                    $this->addInfoMessage('You have requested to reset your password. Please change your password here.');
                    $this->redirect(base_url() . 'account/profile');
                } else {
                    $this->addErrorMessage('The link to reset password is invalid. Please enter your email address to reset your password again.');
                }
            }
        }

        $this->__set_page_title('Password Reset');
        $this->view($this->__module_name . '/reset');
    }

    public function socialregistration() {
        if ($this->isPost()) {
            $data = $_POST;
            $this->addValidationRule('email', 'E-mail Address', 'trim|required|callback_email_check');
            $this->addValidationRule('user_name', 'User Name', 'trim|required|callback_username_check');
            if ($this->input->post('address') == '' && $this->validateForm()) {
                $email = $this->input->post('email');
                if (!($workshop = $this->getNewWorkshop())) {
                    $this->addErrorMessage('Workshop cannot be created. Please contact to the site administrator.');
                } else {
                    $data['password'] = md5($this->config->item('social_password'));
                    $data['status'] = 'active';
                    $data['user_type'] = FREE_PLAN_ID;
                    $data['workshop'] = $workshop;
                    $data['registered_date'] = date("Y-m-d H:i:s");

                    if ($id = $this->__model->save($data)) {
                        $username = $this->input->post('first_name') . '&nbsp;' . $this->input->post('last_name');
                        $this->sendMailUsingTemplate($email, 'registration-success', array('username' => $username));
                        $this->sendMailUsingTemplate($this->__settings->email_address, 'new-registration-email-for-admin', array('username' => $username));

                        $user = $this->UserModel->entity($id);
                        $user->subscription_id = $this->addFreeSubscription($id);
                        $this->__model->save(array('user_id' => $id, 'subscription_id' => $user->subscription_id));
                        $this->addSuccessMessage('Registration Successful!!!');
                        $this->stopAddingMessage();
                        $this->__set_account($user);
                        $this->makeReadyForEditor($user);
                        $this->doAfterLogin(true, true);
                    } else {
                        $this->addErrorMessage('Unknow error occurred while registering user social user.');
                    }
                }
            }

            $this->addToResponseData($_POST);
        } else {
            if ($social_data = $this->session->userdata('social_data')) {
                $this->addToResponseData($social_data);
                $this->session->unset_userdata('social_data');
            } else {
                $this->redirect('guest/login');
            }
        }

        $this->__set_page_title('Social User Registration');
        $this->view($this->__module_name . '/socialregistration');
    }

    public function login_social($social_type, $social_id, $user_name, $email, $first_name, $last_name) {
        $this->load->model('UserModel');
        $user = $this->UserModel->get_by_social($social_id, $social_type);
        if (!$user) {
            $data = array(
                'social_type' => $social_type,
                'social_id' => $social_id,
                'user_name' => $user_name,
                'email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name
            );
            $this->session->set_userdata('social_data', $data);
            $this->redirect('guest/socialregistration');
        } else {
            $this->__set_account($user);
            $this->doAfterLogin(true, false);
        }


        return $is_signup;
    }

    public function login_google() {
        $gsrv = $this->getGoogleServer();

        if ($code = $this->input->get('code')) {
            if ($token = $gsrv->authenticate($code)) {
                $this->session->set_userdata($this->config->item('google_token_field'), $token);
                $google_account_info = $gsrv->getAccountInfo();
                $social_type = $this->config->item('google_social_type');
                $social_id = $google_account_info['id'] . '_' . $social_type;
                $email = $google_account_info['email'];
                $user_name = substr($email, 0, stripos($email, '@')); // . '_' . $social_type;
                $is_signup = $this->login_social($social_type, $social_id, $user_name, $email, $google_account_info['given_name'], $google_account_info['family_name']);
            }
        } else {
            $this->saveReturnUrl();
            redirect($gsrv->getAuthenticationURL());
        }
    }

    public function getGoogleServer() {
        if (!isset($this->googleServer)) {
            $this->load->view('editor/options');
            $this->load->config('social');
            $client_id = get_settings('google_client_id');
            $client_secret = get_settings('google_client_secret');
            $redirect_uri = base_url() . get_settings('google_redirect_uri');

            if ($client_id == '<YOUR_CLIENT_ID>' || $client_secret == '<YOUR_CLIENT_SECRET>' || $redirect_uri == '<YOUR_REDIRECT_URI>') {
                echo 'Invalid settings for using Google services.';
                exit;
            }

            $config = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => $redirect_uri,
            );
            $this->load->library('GoogleServer', $config, 'googleServer');

            $access_token = $this->session->userdata($this->config->item('google_token_field'));
            if (!empty($access_token)) {
                $this->googleServer->set_access_token($access_token);
            }
        }

        return $this->googleServer;
    }

    public function processGoogleServerError($server) {
        $error = $server->getError();
        if ($error) {
            $this->__error($error->msg . ' (' . $error->code . ')');
        }
    }

    public function login_facebook() {
        $gsrv = $this->getFacebookServer();

        if ($facebook_account_info = $gsrv->getAccountInfo()) {
            $social_type = $this->config->item('facebook_social_type');
            $social_id = $facebook_account_info->getId() . '_' . $social_type;
            $email = '';
            $user_name = ''; //$social_id;
            $name = $facebook_account_info->getName();
            $pos = stripos($name, ' ');
            if ($pos > 0) {
                $first_name = substr($name, 0, $pos);
                $last_name = substr($name, $pos + 1);
            } else {
                $first_name = $name;
                $last_name = '';
            }
            $is_signup = $this->login_social($social_type, $social_id, $user_name, $email, $first_name, $last_name);
        } else {
            $this->saveReturnUrl();
            redirect($gsrv->getAuthenticationURL());
        }
    }

    public function getFacebookServer() {
        if (!isset($this->facebookServer)) {
            $this->load->view('editor/options');
            $this->load->config('social');
            $app_id = get_settings('facebook_app_id');
            $app_secret = get_settings('facebook_app_secret');
            $redirect_uri = base_url() . get_settings('facebook_redirect_uri');

            $config = array(
                'app_id' => $app_id,
                'app_secret' => $app_secret,
                'redirect_uri' => $redirect_uri,
            );
            $this->load->library('FacebookServer', $config, 'facebookServer');

            $access_token = $this->session->userdata($this->config->item('facebook_token_field'));
            if (!empty($access_token)) {
                $this->facebookServer->set_access_token($access_token);
            }
        }

        return $this->facebookServer;
    }

    public function processFacebookServerError($server) {
        $error = $server->getError();
        if ($error) {
            $this->__error($error->msg . ' (' . $error->code . ')');
        }
    }

    public function login_twitter() {
        $gsrv = $this->getTwitterServer();

        if ($gsrv->get_request_token() && $auth_token = $this->input->get('oauth_token')) {
            if ($access_token = $gsrv->authenticate($auth_token, $this->input->get('oauth_verifier'))) {
                $this->session->set_userdata($this->config->item('twitter_token_field'), $access_token);
                $twitter_account_info = $gsrv->getAccountInfo();
                $social_type = $this->config->item('twitter_social_type');
                $social_id = $twitter_account_info->id . '_' . $social_type;
                $email = '';
                $user_name = $twitter_account_info->screen_name; // . '_' . $social_type;
                $name = $twitter_account_info->name;
                $pos = stripos($name, ' ');
                if ($pos > 0) {
                    $first_name = substr($name, 0, $pos);
                    $last_name = substr($name, $pos + 1);
                } else {
                    $first_name = $name;
                    $last_name = '';
                }

                $is_signup = $this->login_social($social_type, $social_id, $user_name, $email, $first_name, $last_name);
            }
            $this->session->unset_userdata('request_token');
        } else {
            $this->saveReturnUrl();
            $request = $gsrv->get_request();
            if ($request) {
                $this->session->set_userdata('request_token', $request['request_token']);
                redirect($request['auth_url']);
            }
        }
    }

    public function getTwitterServer() {
        if (!isset($this->twitterServer)) {
            $this->load->view('editor/options');
            $this->load->config('social');
            $consumer_key = get_settings('twitter_consumer_key');
            $consumer_secret = get_settings('twitter_consumer_secret');
            $redirect_uri = base_url() . get_settings('twitter_redirect_uri');

            if ($consumer_key == '<YOUR_CONSUMER_KEY>' || $consumer_secret == '<YOUR_CONSUMER_SECRET>' || $redirect_uri == '<YOUR_REDIRECT_URI>') {
                echo 'Invalid settings for using Twitter services.';
                exit;
            }

            $config = array(
                'consumer_key' => $consumer_key,
                'consumer_secret' => $consumer_secret,
                'redirect_uri' => $redirect_uri,
            );
            $this->load->library('TwitterServer', $config, 'twitterServer');

            $request_token = $this->session->userdata('request_token');
            if ($request_token) {
                $this->twitterServer->set_request_token($request_token);
            }
            $access_token = $this->session->userdata($this->config->item('twitter_token_field'));
            if (!empty($access_token)) {
                $this->twitterServer->set_access_token($access_token);
            }
        }

        return $this->twitterServer;
    }

    public function processTwitterServerError($server) {
        $error = $server->getError();
        if ($error) {
            $this->__error($error->msg . ' (' . $error->code . ')');
        }
    }

    public function login_linkedin() {
        $gsrv = $this->getLinkedinServer();

        if ($code = $this->input->get('code')) {
            if ($access_token = $gsrv->authenticate($code)) {
                $this->session->set_userdata($this->config->item('linkedin_token_field'), $access_token);
                $linkedin_account_info = $gsrv->getAccountInfo();
                $social_type = $this->config->item('linkedin_social_type');
                $temp = $linkedin_account_info['siteStandardProfileRequest']['url'];
                $temp = explode('?', $temp);
                $temp = explode('&', $temp[1]);
                $temp = explode('=', $temp[0]);
                $social_id = $temp[1] . '_' . $social_type;
                $email = '';
                $user_name = ''; //$social_id;
                $first_name = $linkedin_account_info['firstName'];
                $last_name = $linkedin_account_info['lastName'];

                $is_signup = $this->login_social($social_type, $social_id, $user_name, $email, $first_name, $last_name);
            }
            $this->session->unset_userdata('request_token');
        } else {
            $this->saveReturnUrl();
            redirect($gsrv->getAuthorizationURL());
        }
    }

    public function getLinkedinServer() {
        if (!isset($this->linkedinServer)) {
            $this->load->view('editor/options');
            $this->load->config('social');
            $api_key = get_settings('linkedin_api_key');
            $api_secret = get_settings('linkedin_api_secret');
            $redirect_uri = base_url() . get_settings('linkedin_redirect_uri');

            if ($api_key == '<YOUR_API_KEY>' || $api_secret == '<YOUR_API_SECRET>' || $redirect_uri == '<YOUR_REDIRECT_URI>') {
                echo 'Invalid settings for using LinkedIn services.';
                exit;
            }

            $config = array(
                'api_key' => $api_key,
                'api_secret' => $api_secret,
                'redirect_uri' => $redirect_uri,
            );
            $this->load->library('LinkedinServer', $config, 'linkedinServer');

            $access_token = $this->session->userdata($this->config->item('linkedin_token_field'));
            if (!empty($access_token)) {
                $this->linkedinServer->set_access_token($access_token);
            }
        }

        return $this->linkedinServer;
    }

    public function processLinkedinServerError($server) {
        $error = $server->getError();
        if ($error) {
            $this->__error($error->msg . ' (' . $error->code . ')');
        }
    }

    public function login_stackoverflow() {
        $gsrv = $this->getStackoverflowServer();

        if ($code = $this->input->get('code')) {
            if ($access_token = $gsrv->authenticate($code)) {
                $this->session->set_userdata($this->config->item('stackoverflow_token_field'), $access_token);
                $stackoverflow_account_info = $gsrv->getAccountInfo();
                $social_type = $this->config->item('stackoverflow_social_type');
                $social_id = $stackoverflow_account_info['items'][0]['user_id'] . '_' . $social_type;
                $email = '';
                $user_name = ''; //$social_id;
                $first_name = ''; //$stackoverflow_account_info['firstName'];
                $last_name = ''; //$stackoverflow_account_info['lastName'];

                $is_signup = $this->login_social($social_type, $social_id, $user_name, $email, $first_name, $last_name);
            }
            $this->session->unset_userdata('request_token');
        } else {
            $this->saveReturnUrl();
            redirect($gsrv->getAuthorizationURL());
        }
    }

    public function getStackoverflowServer() {
        if (!isset($this->stackoverflowServer)) {
            $this->load->view('editor/options');
            $this->load->config('social');
            $api_key = get_settings('stackoverflow_api_key');
            $api_secret = get_settings('stackoverflow_api_secret');
            $key = get_settings('stackoverflow_key');
            $redirect_uri = base_url() . get_settings('stackoverflow_redirect_uri');

            if ($api_key == '<YOUR_API_KEY>' || $api_secret == '<YOUR_API_SECRET>' || $redirect_uri == '<YOUR_REDIRECT_URI>') {
                echo 'Invalid settings for using Stackoverflow services.';
                exit;
            }

            $config = array(
                'api_key' => $api_key,
                'api_secret' => $api_secret,
                'key' => $key,
                'redirect_uri' => $redirect_uri,
            );
            $this->load->library('StackoverflowServer', $config, 'stackoverflowServer');

            $access_token = $this->session->userdata($this->config->item('stackoverflow_token_field'));
            if (!empty($access_token)) {
                $this->stackoverflowServer->set_access_token($access_token);
            }
        }

        return $this->stackoverflowServer;
    }

    public function processStackoverflowServerError($server) {
        $error = $server->getError();
        if ($error) {
            $this->__error($error->msg . ' (' . $error->code . ')');
        }
    }

    public function login_github() {
        $gsrv = $this->getGithubServer();

        if ($code = $this->input->get('code')) {
            if ($access_token = $gsrv->authenticate($code)) {
                $this->session->set_userdata($this->config->item('github_token_field'), $access_token);
                $github_account_info = $gsrv->getAccountInfo();

                $social_type = $this->config->item('github_social_type');
                $social_id = $github_account_info['id'] . '_' . $social_type;
                $email = '';
                $user_name = $github_account_info['login']; // . '_' . $social_type;
                $first_name = '';
                $last_name = '';

                $is_signup = $this->login_social($social_type, $social_id, $user_name, $email, $first_name, $last_name);
            }
            $this->session->unset_userdata('request_token');
        } else {
            $this->saveReturnUrl();
            redirect($gsrv->getAuthorizationURL());
        }
    }

    public function getGithubServer() {
        if (!isset($this->githubServer)) {
            $this->load->view('editor/options');
            $this->load->config('social');
            $api_key = get_settings('github_api_key');
            $api_secret = get_settings('github_api_secret');
            $redirect_uri = base_url() . get_settings('github_redirect_uri');

            if ($api_key == '<YOUR_API_KEY>' || $api_secret == '<YOUR_API_SECRET>' || $redirect_uri == '<YOUR_REDIRECT_URI>') {
                echo 'Invalid settings for using GitHub services.';
                exit;
            }

            $config = array(
                'api_key' => $api_key,
                'api_secret' => $api_secret,
                'redirect_uri' => $redirect_uri,
            );
            $this->load->library('GithubServer', $config, 'githubServer');

            $access_token = $this->session->userdata($this->config->item('github_token_field'));
            if (!empty($access_token)) {
                $this->githubServer->set_access_token($access_token);
            }
        }

        return $this->githubServer;
    }

    public function processGithubServerError($server) {
        $error = $server->getError();
        if ($error) {
            $this->__error($error->msg . ' (' . $error->code . ')');
        }
    }

}

?>