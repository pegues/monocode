<form id="frm1" method="post">
    <div class="myaccountform userform profile">

        <h2 class="scformsectitle">Personal Information</h2>

        <?php /* Personal Information: Start */ ?>
        <div class="scformsection">

            <?php /* First Name: Start */ ?>
            <div class="scformrow myaccountformrow userformrow">
                <label for="fname">First Name <span class="required">*</span></label>
                <div class="scfield myaccountformfield userformfield">
                    <input type="text" 
						id="fname" 
						name="first_name" 
						class="text" 
						placeholder="Enter your first name" 
						value="<?php echo $entity->first_name; ?>" 
						required="" />

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* First Name: End */ ?>

            <?php /* Last Name: Start */ ?>
            <div class="scformrow myaccountformrow userformrow">
                <label for="lname">Last Name <span class="required">*</span></label>
                <div class="scfield myaccountformfield userformfield">
                    <input type="text" 
						id="lname" 
						name="last_name" 
						class="text" 
						placeholder="Enter your last name" 
						value="<?php echo $entity->last_name; ?>" 
						required="" />

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Last Name: End */ ?>

            <?php /* Username: Start */ ?>
            <div class="scformrow myaccountformrow userformrow">
                <label for="uname">Username <span class="required">*</span></label>
                <div class="scfield myaccountformfield userformfield fldicon">
                    <input type="text" 
						id="uname" 
						class="text" 
						value="<?php echo $entity->user_name; ?>" 
						placeholder="Enter your username" readonly="" />

                    <i class="fa fa-user"></i>

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Username: End */ ?>

            <?php /* Email Address: Start */ ?>
            <div class="scformrow myaccountformrow userformrow">
                <label for="email">Email Address</label>
                <div class="scfield myaccountformfield userformfield fldicon">
                    <input type="text" 
						id="email" 
						name="email" 
						required="" 
						class="text" 
						value="<?php echo $entity->email; ?>" 
						placeholder="Enter your email address" />

                    <i class="fa fa-envelope"></i>

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Email Address: End */ ?>

            <div class="clear"></div>
        </div>
        <?php /* Personal Information: End */ ?>

        <h2 class="scformsectitle">Address Information</h2>

        <?php /* Address Information: Start */ ?>
        <div class="scformsection">

            <?php /* Country: Start */ ?>
            <div class="scformrow myaccountformrow userformrow">
                <label for="country">Country<span class="required">*</span></label>
                <div class="scfield myaccountformfield userformfield">
                    <select class="select" name="country_code" required="">
                        <option value="">Please Select</option>
                        <?php echo getCountryOptions($entity->country_code); ?>
                    </select>

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Country: End */ ?>

            <?php /* Address: Start */ ?>
            <div class="scformrow myaccountformrow userformrow">
                <label for="address">Address<span class="required">*</span></label>
                <div class="scfield myaccountformfield userformfield">
                    <input type="text" 
						id="address" 
						name="address" 
						class="text" 
						placeholder="Enter your address information" 
						value="<?php echo $entity->address; ?>" 
						required="" />

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Address: End */ ?>

            <?php /* City, State, Zip: Start */ ?>
            <div class="scformrow myaccountformrow userformrow triplecol">

                <?php /* City: Start */ ?>
                <div class="scformcol colleft">
                    <div class="scformcol_inside">
                        <label for="city">City</label>
                        <div class="scfield myaccountformfield userformfield">
                            <input type="text" 
								id="city" 
								name="city" 
								class="text" 
								placeholder="Enter your city" 
								value="<?php echo $entity->city; ?>" 
								required="" />

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>   
                <?php /* City: End */ ?>

                <?php /* State: Start */ ?>
                <div class="scformcol colmiddle">
                    <div class="scformcol_inside">
                        <label for="state">State/Province</label>
                        <div class="scfield myaccountformfield userformfield">
                            <select name="state_code" id="state_code" class="select">
                                <option value="">Please Select</option>
                                <?php echo getStateOptions($entity->state_code); ?>
                            </select>
                            <input style="display: none" type="text" class="text" name="state_code" value="<?php echo getStateByCode($entity->state_code) ? '' : $entity->state_code; ?>" disabled="" />
                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* State: End */ ?>

                <?php /* Zip: Start */ ?>
                <div class="scformcol colright">
                    <div class="scformcol_inside">
                        <label for="zip">Zip/Postal Code</label>
                        <div class="scfield myaccountformfield userformfield">
                            <input type="text" 
								id="zip" 
								name="zip" 
								class="text" 
								placeholder="Enter your zip" 
								value="<?php echo $entity->zip; ?>"  
								required="" />

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Zip: End */ ?>

                <div class="clear"></div>
            </div>
            <?php /* City, State, Zip: End */ ?>

            <div class="clear"></div>
        </div>
        <?php /* Address Information: End */ ?>

        <h2 class="scformsectitle">Billing Information</h2>

        <?php /* Address Information: Start */ ?>
        <div class="scformsection">

            <?php /* Billing Same as Personal: Start */ ?>
            <div class="scformbillingsamecheck">
                <div class="checkboxbttnholder scformbillingcheckbox active">
                    <input type="checkbox" 
						name="use_billing_info" 
						value="1" 
						class="ccheckbox checkbox" 
						<?php echo $entity->use_billing_info ? 'checked="checked"' : ''; ?> />
                    Billing address same as personal address?
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Billing Same as Personal: End */ ?>

            <?php /* Billing Not Same as Personal: Start */ ?>
            <div class="scformbillingnotsame">

                <?php /* First Name: Start */ ?>
                <div class="scformrow myaccountformrow userformrow">
                    <label for="fname">First Name <span class="required">*</span></label>
                    <div class="scfield myaccountformfield userformfield">
                        <input type="text" 
							id="billing_first_name" 
							name="billing_first_name" 
							class="text" 
							placeholder="Enter your first name" 
							value="<?php echo $entity->billing_first_name; ?>"  
							required="" />

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* First Name: End */ ?>

                <?php /* Last Name: Start */ ?>
                <div class="scformrow myaccountformrow userformrow">
                    <label for="billinglname">Last Name <span class="required">*</span></label>
                    <div class="scfield myaccountformfield userformfield">
                        <input type="text" 
							id="billing_last_name" 
							name="billing_last_name" 
							class="text" 
							placeholder="Enter your last name" 
							value="<?php echo $entity->billing_last_name; ?>"  
							required="" />

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Last Name: End */ ?>

                <?php /* Country: Start */ ?>
                <div class="scformrow myaccountformrow userformrow">
                    <label for="country">Country<span class="required">*</span></label>
                    <div class="scfield myaccountformfield userformfield">
                        <select class="select" required="" id="billing_country_code" name="billing_country_code">
                            <option value="">Please select</option>
                            <?php echo getCountryOptions($entity->billing_country_code); ?>
                        </select>
                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Country: End */ ?>

                <?php /* Address: Start */ ?>
                <div class="scformrow myaccountformrow userformrow">
                    <label for="billingaddress">Address<span class="required">*</span></label>
                    <div class="scfield myaccountformfield userformfield">
                        <input type="text" 
							id="billing_address" 
							name="billing_address" 
							class="text" 
							placeholder="Enter your billing address information" 
							value="<?php echo $entity->billing_address; ?>"  
							required="" />

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Address: End */ ?>

                <?php /* City, State, Zip: Start */ ?>
                <div class="scformrow myaccountformrow userformrow triplecol">

                    <?php /* City: Start */ ?>
                    <div class="scformcol colleft">
                        <div class="scformcol_inside">
                            <label for="billingcity">City</label>
                            <div class="scfield myaccountformfield userformfield">
                                <input type="text" 
									id="billing_city" 
									name="billing_city" 
									class="text" 
									placeholder="Enter your billing city" 
									value="<?php echo $entity->billing_city; ?>" 
									required="" />

                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>   
                    <?php /* City: End */ ?>

                    <?php /* State: Start */ ?>
                    <div class="scformcol">
                        <div class="scformcol_inside">
                            <label for="billingstate">State/Province</label>
                            <div class="scfield myaccountformfield userformfield">
                                <select name="billing_state_code" id="billing_state_code" class="select">
                                    <option value="">Please Select</option>
                                    <?php echo getStateOptions($entity->billing_state_code); ?>
                                    <input style="display: none" type="text" class="text" name="billing_state_code" value="<?php echo getStateByCode($entity->billing_state_code) ? '' : $entity->billing_state_code; ?>" disabled="" />
                                </select>
                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <?php /* State: End */ ?>

                    <?php /* Zip: Start */ ?>
                    <div class="scformcol colright">
                        <div class="scformcol_inside">
                            <label for="billingzip">Zip/Postal Code</label>
                            <div class="scfield myaccountformfield userformfield">
                                <input type="text" 
									id="billing_zip" 
									name="billing_zip" 
									class="text" 
									placeholder="Enter your billing zip" 
									value="<?php echo $entity->billing_zip; ?>" 
									required="" />

                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <?php /* Zip: End */ ?>

                    <div class="clear"></div>
                </div>
                <?php /* City, State, Zip: End */ ?>

                <div class="clear"></div>
            </div>
            <?php /* Billing Not Same as Personal: End */ ?>

            <div class="clear"></div>
        </div>
        <?php /* Billing Information: End */ ?>

        <?php if (!$entity->social_id) { ?>
            <h2 class="scformsectitle" style="padding-bottom: 5px;">Password</h2>
			
			<p style="padding-top: 5px; padding-bottom: 15px;">Keep the password fields blank if you do not wish to update your password. Passwords must be a minimum of 8 characters, consist of at least 1 special character, 1 uppercase letter, 1 lowercase letter, and 1 number.</p>
			
            <?php /* Password: Start */ ?>
            <div class="scformsection">

                <?php /* Change Password: Start */ ?>
                <div class="scformrow myaccountformrow userformrow dblcol">
                    <div class="scformcol">
						<label for="pwd">Change Password</label>
						<div class="scfield myaccountformfield userformfield fldicon">
							<input type="password" 
								id="password" 
								class="text" 
								value="" 
								name="password" 
								placeholder="Enter your password" />

							<i class="fa fa-lock"></i>

							<div class="clear"></div>
						</div>

						<div class="clear"></div>
					</div>

                    <div class="clear"></div>
                </div>
                <?php /* Change Password: End */ ?>

                <?php /* Confirm Password: Start */ ?>
                <div class="scformrow myaccountformrow userformrow dblcol">
                    <div class="scformcol">
						<label for="cpwd">Confirm Password</label>
						<div class="scfield myaccountformfield userformfield fldicon">
							<input type="password" 
								id="cpwd" 
								class="text" 
								value="" 
								name="passwordconfirm" 
								placeholder="Confirm your password" />

							<i class="fa fa-lock"></i>

							<div class="clear"></div>
						</div>

						<div class="clear"></div>
					</div>

                    <div class="clear"></div>
                </div>
                <?php /* Confirm Password: End */ ?>

                <div class="clear"></div>
            </div>
            <?php /* Password: End */ ?>
			
        <?php } ?>
		
        <?php /* Button: Start */ ?>
        <div class="scformrow buttoncontainer submitbtn">
            <div class="scfield">
                <button class="button green submit" style="width: 33.33%;">
                    <span>Save Changes</span>
                </button>

                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
        <?php /* Button: End */ ?>

        <div class="clear"></div>
    </div>
</form>
<script type="text/javascript">

    $(document).ready(function (e) {

        // Show or Hide Billing Address Form on Page Load
        if ($('.scformbillingsamecheck').find('input[type="checkbox"]').is(':checked')) {
            $('.scformbillingnotsame').css({display: 'none'});
        } else {
            $('.scformbillingnotsame').css({display: 'block'});
        }

        // Click Event to Show or Hide Billing Address Form
        $('.scformbillingsamecheck .customcheckbox').on('click', function () {
            if ($('.scformbillingsamecheck').find('input[type="checkbox"]').is(':checked')) {
                $('.scformbillingnotsame').slideUp();
                $('.scformbillingnotsame').find('.scformrow .scfield input, .scformrow .scfield select').attr('disabled', true);
            } else {
                $('.scformbillingnotsame').slideDown();
                $('.scformbillingnotsame').find('.scformrow .scfield input, .scformrow .scfield select').attr('disabled', false);
            }
        });

        $('select[name="country_code"]').change(function () {
            var country = $(this).val();
            if (country == 'US'/* || country == 'CA' || country == 'AU'*/) {
                $('input[name="city"]').closest('.scformcol').find('label').append('<span class="required">*</span>');
                $('select[name="state_code"]').show().attr('disabled', false)
                        .next().hide().attr('disabled', true)
                        .closest('.scformcol').find('label').html('State/Province<span class="required">*</span>');
                $('input[name="zip"]').closest('.scformcol').find('label').append('<span class="required">*</span>');
            } else {
                $('input[name="city"]').closest('.scformcol').find('label').find('span').remove();
                $('select[name="state_code"]').hide().attr('disabled', true)
                        .next().show().attr('disabled', false)
                        .closest('.scformcol').find('label').html('State/Province/Region');
                $('input[name="zip"]').closest('.scformcol').find('label').find('span').remove();
            }
        }).change();

        $('select[name="billing_country_code"]').change(function () {
            var country = $(this).val();
            if (country == 'US'/* || country == 'CA' || country == 'AU'*/) {
                $('input[name="billing_city"]').closest('.scformcol').find('label').append('<span class="required">*</span>');
                $('select[name="billing_state_code"]').show().attr('disabled', false)
                        .next().hide().attr('disabled', true)
                        .closest('.scformcol').find('label').html('State/Province<span class="required">*</span>');
                $('input[name="billing_zip"]').closest('.scformcol').find('label').append('<span class="required">*</span>');
            } else {
                $('input[name="billing_city"]').closest('.scformcol').find('label').find('span').remove();
                $('select[name="billing_state_code"]').hide().attr('disabled', true)
                        .next().show().attr('disabled', false)
                        .closest('.scformcol').find('label').html('State/Province/Region');
                $('input[name="billing_zip"]').closest('.scformcol').find('label').find('span').remove();
            }
        }).change();

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

</script>
