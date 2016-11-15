<?php /* Register Fields: Start */ ?>
<div class="formcol">

    <?php /* Social Login Type: Start */ ?>
    <div class="formgroup sociallogintype">

        <?php /*
          Social Network Icons:
          Linkedin		: fa-linkedin
          Twitter		: fa-twitter
          Facebook		: fa-facebook
          Google Plus	: fa-google-plus
          GitHub		: fa-github
          Stackoverflow	: fa-stack-overflow
         */ ?>

        <label>
            <?php if ($social_type == 'linkedin') { ?>
                <i class="fa fa-linkedin"></i><?php /* Network Icon */ ?>
                <span class="socialnetworktxt">Linkedin</span>	<?php /* Network Name */ ?>
            <?php } else if ($social_type == 'twitter') { ?>
                <i class="fa fa-twitter"></i><?php /* Network Icon */ ?>
                <span class="socialnetworktxt">Twitter</span>	<?php /* Network Name */ ?>
            <?php } else if ($social_type == 'facebook') { ?>
                <i class="fa fa-facebook"></i><?php /* Network Icon */ ?>
                <span class="socialnetworktxt">Facebook</span>	<?php /* Network Name */ ?>
            <?php } else if ($social_type == 'google') { ?>
                <i class="fa fa-google-plus"></i><?php /* Network Icon */ ?>
                <span class="socialnetworktxt">Google</span>	<?php /* Network Name */ ?>
            <?php } else if ($social_type == 'github') { ?>
                <i class="fa fa-github"></i><?php /* Network Icon */ ?>
                <span class="socialnetworktxt">Github</span>	<?php /* Network Name */ ?>
            <?php } else if ($social_type == 'stackoverflow') { ?>
                <i class="fa fa-stack-overflow"></i><?php /* Network Icon */ ?>
                <span class="socialnetworktxt">Stackoverflow</span>	<?php /* Network Name */ ?>
            <?php } ?>
            <span class="sociallogintxt">Registration</span><?php /* Does not change */ ?>
        </label>

        <div class="clear"></div>
    </div>
    <?php /* Social Login Type: End */ ?>

    <?php /* Social Login Directions: Start */ ?>
    <div class="formgroup sociallogindirections">
        <label>Provide the following information to complete your account registration.</label>
    </div>
    <?php /* Social Login Directions: End */ ?>

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

    <?php /* Email Address: Start */ ?>
    <div class="formgroup">
        <label for="email">Email Address</label>
        <div class="">
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

    <div class="formgroup buttonrow">
        <input type="hidden" name="social_id" value="<?php echo $social_id; ?>" />
        <input type="hidden" name="social_type" value="<?php echo $social_type; ?>" />
        <button href="<?php echo $base_url; ?>register" class="button register">
            <span>Complete Registration</span>
        </button>
        <input id="reg-address" type="text" name="address" value="" />

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>

<script type="text/javascript">

    // Options used to check availability of username
    var typingTimer = null;       	// timer identifier
    var doneTypingInterval = 450;  	// time in ms, 450ms
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
                user_name: "required",
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                first_name: "Please enter your first name",
                last_name: "Please enter your last name",
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
        }).keyup();

        //on keydown, clear the countdown 
        $('#user_name').keydown(function (e) {
            if (e.keyCode != 9 && e.keyCode != 13) {
                clearTimeout(typingTimer);
                clearAvailability();
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
                $("#usernameavailability").html('<label class="success" for="username">' + user_name + ' is available.</label>');
                usernameavailability = 1;
            } else {
                $("#usernameavailability").html('<label class="error" for="username">' + user_name + ' is not available.</label>');
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
