<?php /* Login Column: Start */ ?>
<div class="formcol">
    
	<?php /* Username/Email: Start */ ?>
	<div class="formgroup">
        <label for="username">Username/Email</label>
        <div class="formfld">
            <input type="text" 
				id="username" 
				name="username" 
				class="text" 
				placeholder="Username/Email" 
				autocomplete="off" 
				value="<?php echo isset($username) ? $username : ''; ?>" />

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>
	<?php /* Username/Email: End */ ?>
	
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
	
    <?php /*
	<div class="formgroup">
		<label for="select">Interface Language</label>
		<div class="formfld">
			<select name="select" id="select" class="select">
				<option selected="selected">Default</option>
			</select>

			<div class="clear"></div>
		</div>

		<div class="clear"></div>
	</div>
	*/ ?>

    <?php if (isset($failed) && $failed >= 2) { ?>
    <div class="formgroup">
        <div class="g-recaptcha" data-sitekey="<?php echo $settings->recaptcha_sitekey; ?>"></div>
    </div>
    <?php } ?>
    <div class="formgroup buttonrow">
        <a href="<?php echo $base_url; ?>reset" class="button pwdreset textonly">
            <span>Forgot your password?</span>
        </a>
        <span class="bttntextsep">|</span>
        <a href="<?php echo $base_url; ?>register/<?php echo isset($_GET['back']) ? '?back=' . $_GET['back'] : ''; ?>" class="button register textonly">
            <span>Create a new account</span>
        </a>

        <button class="button login">
            <span>Login Now</span>
        </button>

        <input type="submit" name="button" id="button" class="button submit" value="Log In" style="display: none;" />

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
    $(document).ready(function (e) {
        $("#frm1").validate({
            rules: {
                password: "required",
                username: "required"
            },
            messages: {
                password: "Please enter your password",
                username: "Please enter your username"
            }
        });
        
        $("input[name='username']").select().focus();
    });
</script>
