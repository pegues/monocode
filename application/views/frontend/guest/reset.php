<?php /* Reset Password: Start */ ?>
<div class="formcol">
    
	<?php /* Enter Email Address: Start */ ?>
	<div class="formgroup">
        <label for="username">Enter your email address to reset your password.</label>
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
	<?php /* Enter Email Address: End */ ?>
	
    <div class="formgroup buttonrow">
        <a href="<?php echo $base_url; ?>login" class="button login textonly">
            <span>Remember your password? Login Now.</span>
        </a>
        <button class="button pwdreset">
            <span>Password Reset</span>
        </button>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>
<?php /* Reset Password: End */ ?>

<script type="text/javascript">
    $(document).ready(function (e) {
        $("#frm1").validate({
            rules: {
                email: {
                    required: true,
                    email	: true
                }
            },
            messages: {
                email: {
                    required: "Email address is required",
                    email	: "Valid email address is required"
                },
            }
        });
    });
</script>
