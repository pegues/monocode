<?php /* Register Fields: Start */ ?>
<div class="formcol">

    <?php /* First Name: Start */ ?>
    <div class="formgroup">
        <label for="username">First Name</label>
        <div class="formfld">
            <input type="text" 
				id="first_name" 
				name="first_name" 
				class="text" 
				placeholder="First Name" 
				data-message-required="First name is required." 
				data-validate="required" 
				value="<?php echo isset($first_name) ? $first_name : ''; ?>" />

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>
    <?php /* First Name: End */ ?>

    <?php /* Last Name: Start */ ?>
    <div class="formgroup">
        <label for="username">Last Name</label>
        <div class="formfld">
            <input type="text" 
				id="last_name" 
				name="last_name" 
				class="text" 
				placeholder="Last Name" 
				autocomplete="off" 
				value="<?php echo isset($last_name) ? $last_name : ''; ?>" />

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>
    <?php /* Last Name: End */ ?>

    <?php /* Username: Start */ ?>
    <div class="formgroup">
        <label for="username">Username</label>
        <div class="formfld">
            <input type="text" 
				id="user_name" 
				name="user_name" 
				class="text" 
				placeholder="Username" 
				autocomplete="off" 
				value="<?php echo isset($user_name) ? $user_name : ''; ?>" />

            <div class="clear"></div>
        </div>

        <div id="usernameavailability" class="usernameavailability"></div>

        <div class="clear"></div>
    </div>
    <?php /* Username: End */ ?>

    <?php /* Password: Start */ ?>
    <div class="formgroup">
        <label for="password">Password</label>
        <div class="formfld">
            <input type="password" 
				id="password" 
				name="password" 
				class="text" 
				placeholder="Password" 
				autocomplete="off" />

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>
    <?php /* Password: End */ ?>

    <?php /* Password Confirmation: Start */ ?>
    <div class="formgroup">
        <label for="password">Password Confirmation</label>
        <div class="formfld">
            <input type="password" 
				id="passwordconfirm" 
				name="passwordconfirm" 
				class="text" 
				placeholder="Password Confirmation" 
				autocomplete="off" />

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>
    <?php /* Password Confirmation: End */ ?>

    <?php /* Email Address: Start */ ?>
    <div class="formgroup">
        <label for="email">Email Address</label>
        <div class="formfld">
            <input type="text" 
				id="email" 
				name="email" 
				class="text" 
				placeholder="Email Address" 
				autocomplete="off" 
				value="<?php echo isset($email) ? $email : ''; ?>" />

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>
    <?php /* Email Address: End */ ?>

    <?php /* Google Captcha: Start */ ?>
    <div class="formgroup">
        <div class="g-recaptcha" data-sitekey="<?php echo $settings->recaptcha_sitekey; ?>"></div>
    </div>
    <?php /* Google Captcha: End */ ?>

    <div class="formgroup buttonrow">
        <a href="<?php echo $base_url; ?>login" class="button login textonly" style="float: left; margin-left: 0;">
            <span>Have an account? Login Now.</span>
        </a>
        <button href="<?php echo $base_url; ?>register" class="button register">
            <span>Register</span>
        </button>
        <input id="reg-address" type="text" name="address" value="" />

        <div class="clear"></div>
    </div>

    <div class="formgroup buttonrow sociallogin">
        <label>Login with your social account...</label>
        <div class="socialbuttons">

            <?/* Social Login - LinkedIn: Start */ ?>
            <?php if ($settings->linkedin_enable == 1) { ?>
                <a href="<?php echo $base_url; ?>login_linkedin" class="button login-social linkedin odd" title="Login with LinkedIn">
                    <span><i class="fa fa-linkedin"></i></span>
                </a>
            <?php } ?>
            <?/* Social Login - LinkedIn: End */ ?>

            <?/* Social Login - Twitter: Start */ ?>
            <?php if ($settings->twitter_enable == 1) { ?>
                <a href="<?php echo $base_url; ?>login_twitter" class="button login-social twitter even" title="Login with Twitter">
                    <span><i class="fa fa-twitter"></i></span>
                </a>
            <?php } ?>
            <?/* Social Login - Twitter: End */ ?>

            <?/* Social Login - Facebook: Start */ ?>
            <?php if ($settings->facebook_enable == 1) { ?>
                <a href="<?php echo $base_url; ?>login_facebook" class="button login-social facebook odd" title="Login with Facebook">
                    <span><i class="fa fa-facebook"></i></span>
                </a>
            <?php } ?>
            <?/* Social Login - Facebook: End */ ?>

            <?/* Social Login - Google: Start */ ?>
            <?php if ($settings->google_enable == 1) { ?>
                <a href="<?php echo $base_url; ?>login_google" class="button login-social google even" title="Login with Google">
                    <span class="gcolors">
                        <span class="gcolor red"></span>
                        <span class="gcolor blue"></span>
                        <span class="gcolor green"></span>
                        <span class="gcolor orange"></span>
                    </span>
                    <span><i class="fa fa-google"></i></span>
                </a>
            <?php } ?>
            <?/* Social Login - Google: End */ ?>

            <?/* Social Login - GitHub: Start */ ?>
            <?php if ($settings->github_enable == 1) { ?>
                <a href="<?php echo $base_url; ?>login_github" class="button login-social github odd" title="Login with GitHub">
                    <span><i class="fa fa-git"></i></span>
                </a>
            <?php } ?>
            <?/* Social Login - GitHub: End */ ?>

            <?/* Social Login - Stackoverflow: Start */ ?>
            <?php if ($settings->stackoverflow_enable == 1) { ?>
                <a href="<?php echo $base_url; ?>login_stackoverflow" class="button login-social stackoverflow even" title="Login with Stackoverflow">
                    <span><i class="fa fa-stack-overflow"></i></span>
                </a>
            <?php } ?>
            <?/* Social Login - Stackoverflow: End */ ?>

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>

<script type="text/javascript">

    // options used to check availability of username
    var typingTimer = null;	// timer identifier
    var doneTypingInterval = 450;  // time in ms, 450ms
    var xhr = null;
    var usernameavailability = -1;

    $(document).ready(function (e) {
        $("#frm1").submit(function () {
            if (usernameavailability == 0) {
                return false;
            }

            return true;
        }).validate({
            rules: {
                first_name: "required",
                last_name: "required",
                password: "required",
                user_name: "required",
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                first_name: "Please enter your first name",
                last_name: "Please enter your last name",
                password: "Please enter your password",
                user_name: "Please enter your username",
                email: {
                    required: "Email address is required",
                    email: "Valid email address is required"
                },
            },
        });

        //attaching events to check availability of username
        //on keyup, start the countdown
        $('#user_name').keyup(function () {
            if (xhr) {
                xhr.abort();
            }
            clearAvailability();

            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        //on keydown, clear the countdown 
        $('#user_name').keydown(function (e) {
            if (e.keyCode != 9 && e.keyCode != 13) {
                clearTimeout(typingTimer);
                clearAvailability();
            }
        });

        //attaching events to check password strength
        $("input[name='first_name']").select().focus();
        $("#password").reviewPassword({
            preventWeakSubmit: !(DEBUG && !<?php echo REQUIRE_STRONG_PASSWORD ? 'true' : 'false'; ?>),
            minValues: {
                size: 8,
                numbers: 1,
                letter: 3,
            }
        });
    });

    //user is "finished typing," do something
    function doneTyping() {
        var user_name = $("#user_name").val().trim();
        if (user_name == '') {
            return;
        }
        xhr = $.ajax({
            'type': 'post',
            'url': '<?php echo $base_url; ?>checkUsernameAvailable',
            'data': 'user_name=' + user_name,
            'dataType': 'json'
        }).done(function (json) {
            if (json.status) {
                $("#usernameavailability").html('<label for="username" class="success">' + user_name + ' is available.</label>');
                usernameavailability = 1;
            } else {
                $("#usernameavailability").html('<label for="username" class="error">' + user_name + ' is not available.</label>');
                usernameavailability = 0;
            }
        }).fail(function () {
            //clearAvailability();
        });
    }

    function clearAvailability() {
        $("#usernameavailability").html('');
        usernameavailability = -1;
    }
</script>
