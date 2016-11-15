<link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/payprocessing.css" rel="stylesheet" media="all" />

<form id="checkout-form" method="post">
    <div class="scformrow">
		
		<?php /* Plan Selection: Start */ ?>
        <h2 class="scformsectitle">Plan Selection</h2>
		
        <div class="scformsection">
			
            <div class="scformrow dblcol">
				
                <?php /* Payment Length: Start */ ?>
                <div class="scformcol planlengthmain">
                    <label>Plan Length</label>
					
                    <div class="radiobttnholder planlength inactive">
                        <input type="radio" name="planlength" value="12" class="radio inactive" />
                        Yearly Plan: <?php echo $settings->currency . ' ' . ($entity->amount * (100 - (isset($discount) && $discount ? $discount->percent : 0)) / 100) * 12 * (100 - (isset($yearlyDiscount) && $yearlyDiscount ? $yearlyDiscount->percent : 0)) / 100; ?>
						
                        <br />
						
                        <?php
                        if (isset($yearlyDiscount) && $yearlyDiscount) {
                            echo $yearlyDiscount->percent . '% discount will be provided for a yearly plan.';
                        }
                        ?>
                    </div>
					
                    <div class="radiobttnholder planlength active">
                        <input type="radio" name="planlength" value="1" class="radio active" checked="checked" />
                        Monthly Plan: <?php echo $settings->currency . ' ' . ($entity->amount * (100 - (isset($discount) && $discount ? $discount->percent : 0)) / 100); ?>
                    </div>
					
                    <div class="clear"></div>
                </div>
                <?php /* Payment Length: End */ ?>
				
                <?php /* FOLLOWING ITEM NEEDS TO BE PROGRAMMED */ ?>
                <?php /* Payment Type: Start */ ?>
                <div class="scformcol paymenttypemain">
                    <label>Payment Type</label>
                    <div class="radiobttnholder paymenttype active">
                        <input type="radio" name="paymenttype" value="1" class="radio active" checked="checked" />
                        Recurring
                    </div>
                    <div class="radiobttnholder paymenttype inactive">
                        <input type="radio" name="paymenttype" value="0" class="radio inactive" />
                        One Time
                    </div>
					
                    <div class="clear"></div>
                </div>
                <?php /* Payment Type: End */ ?>
                <?php /* PREVIOUS ITEM NEEDS TO BE PROGRAMMED */ ?>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
        <?php /* Plan Selection: End */ ?>
		
		<div class="clear"></div>
    </div>
	
    <div class="clear"></div>
	
    <?php if (!$account->promocode_used) { ?>
        <div class="scformrow">
            <div class="scfield">
                <label for="promocode">Promo Code</label>
                <input name="promocode" 
					id="promocode" 
					type="text" 
					class="text" 
					value="<?php echo isset($promocode) ? $promocode : ''; ?>" />
				
                <div id="pcavailability"></div>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    <?php } ?>
	
    <?php if (isset($paymentMethodList) && count($paymentMethodList) > 0) { ?>
        <ul id="paymentmethodlist">
            <?php foreach ($paymentMethodList as $paymentMethod) { ?>
                <li class="radiobttnholder <?php echo $paymentMethod->isDefault() ? 'active' : ''; ?>">
                    <input type="radio" 
						name="ccToken" 
						value="<?php echo $paymentMethod->token; ?>" 
						class="radio <?php echo $paymentMethod->isDefault() ? 'active' : ''; ?>" 
						<?php echo $paymentMethod->isDefault() ? 'checked="checked"' : ''; ?> />
                    
					<span>
                        <?php
                        echo $paymentMethod->cardholderName . "'s " . $paymentMethod->cardType . ' ';
                        if (isset($paymentMethod->email)) {
                            echo $paymentMethod->email;
                        } else {
                            echo $paymentMethod->maskedNumber . ' expires in ' . $paymentMethod->expirationYear . '/' . $paymentMethod->expirationMonth;
                        }
                        ?>
                    </span>
					
                    <a class="button blue" style="display: inline-block; width: auto;" href="<?php echo base_url() . 'account/paymentMethod/update/' . $paymentMethod->token; ?>?returnUrl=<?php echo current_url(); ?>">Change</a>
					
                    <a onclick="return confirm('Are you sure you wish to continue?');" class="button red" style="display: inline-block; width: auto;" href="<?php echo base_url() . 'account/paymentMethod/delete/' . $paymentMethod->token; ?>?returnUrl=<?php echo current_url(); ?>">Delete</a>
                </li>
            <?php } ?>
			
            <li class="radiobttnholder">
                <input type="radio" name="ccToken" value="" class="radio"/> Use Another Payment Method
            </li>
        </ul>
		
        <div class="clear"></div>
    <?php } ?>
	
    <div id="paymentMethodInfo">
        
		<?php /* Form Column - Left: Start */ ?>
        <div class="scformcol twocol colleft">
            <div class="scformcol_inside">
				
                <?php /* Personal Information: Start */ ?>
                <h2 class="scformsectitle">Personal Information</h2>
				
                <div class="scformsection">
					
                    <?php /* First Name: Start */ ?>
                    <div class="scformrow">
                        <label for="firstName">First Name<span class="req">*</span></label>
                        <div class="scfield">
                            <input name="firstName" 
								id="firstName" 
								type="text" 
								class="text" 
								value="<?php echo isset($billingAddress->firstName) ? $billingAddress->firstName : ''; ?>" />
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* First Name: End */ ?>
					
                    <?php /* Last Name: Start */ ?>
                    <div class="scformrow">
                        <label for="lastName">Last Name<span class="req">*</span></label>
                        <div class="scfield">
                            <input name="lastName" 
								id="lastName" 
								type="text" 
								class="text" 
								value="<?php echo isset($billingAddress->lastName) ? $billingAddress->lastName : ''; ?>" />
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Last Name: End */ ?>
					
                    <?php /* Email: Start */ ?>
                    <!--
					<div class="scformrow">
                        <label for="email">Email<span class="req">*</span></label>
                        <div class="scfield">
                            <input name="email" 
								id="email" 
								type="text" 
								class="text" 
								value="<?php echo isset($billingAddress->email) ? $billingAddress->email : ''; ?>" />
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
					-->
                    <?php /* Email: End */ ?>
					
                    <div class="clear"></div>
                </div>
                <?php /* Personal Information: End */ ?>
				
                <?php /* Billing Information: Start */ ?>
                <h2 class="scformsectitle">Billing Information</h2>
				
                <div class="scformsection">
					
                    <?php /* Address: Start */ ?>
                    <div class="scformrow">
                        <label for="streetAddress">Address<span class="req">*</span></label>
                        <div class="scfield">
                            <input name="streetAddress" 
								id="streetAddress" 
								type="text" 
								class="text" 
								value="<?php echo isset($billingAddress->streetAddress) ? $billingAddress->streetAddress : ''; ?>" />
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Address: End */ ?>
					
                    <?php /* City: Start */ ?>
                    <div class="scformrow">
                        <label for="locality">City<span class="req">*</span></label>
                        <div class="scfield">
                            <input name="locality" 
								id="locality" 
								type="text" 
								class="text" 
								value="<?php echo isset($billingAddress->locality) ? $billingAddress->locality : ''; ?>" />
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* City: End */ ?>
					
                    <?php /* Country: Start */ ?>
                    <div class="scformrow">
                        <label for="countryCodeAlpha2">Country<span class="req">*</span></label>
                        <div class="scfield">
                            <select name="countryCodeAlpha2" id="countryCodeAlpha2" class="select"> 
                                <option value="">Please Select</option> 
                                <option value="US" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "US" ? "selected" : "" ?>>United States</option>
                                <option value="CA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CA" ? "selected" : "" ?>>Canada</option>
                                <option value="UK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "UK" ? "selected" : "" ?>>United Kingdom</option>
                                <option value="AU" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AU" ? "selected" : "" ?>>Australia</option>
                                <option value="AF" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AF" ? "selected" : "" ?>>Afghanistan</option>
                                <option value="AL" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AL" ? "selected" : "" ?>>Albania</option>
                                <option value="DZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "DZ" ? "selected" : "" ?>>Algeria</option>
                                <option value="AS" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AS" ? "selected" : "" ?>>American Samoa</option>
                                <option value="AD" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AD" ? "selected" : "" ?>>Andorra</option>
                                <option value="AO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AO" ? "selected" : "" ?>>Angola</option>
                                <option value="AI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AI" ? "selected" : "" ?>>Anguilla</option>
                                <option value="AQ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AQ" ? "selected" : "" ?>>Antarctica</option>
                                <option value="AG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AG" ? "selected" : "" ?>>Antigua and Barbuda</option>
                                <option value="AR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AR" ? "selected" : "" ?>>Argentina</option>
                                <option value="AM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AM" ? "selected" : "" ?>>Armenia</option>
                                <option value="AW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AW" ? "selected" : "" ?>>Aruba</option>
                                <option value="AT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AT" ? "selected" : "" ?>>Austria</option>
                                <option value="AZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AZ" ? "selected" : "" ?>>Azerbaijan</option>
                                <option value="BS" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BS" ? "selected" : "" ?>>Bahamas</option>
                                <option value="BH" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BH" ? "selected" : "" ?>>Bahrain</option>
                                <option value="BD" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BD" ? "selected" : "" ?>>Bangladesh</option>
                                <option value="BB" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BB" ? "selected" : "" ?>>Barbados</option>
                                <option value="BY" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BY" ? "selected" : "" ?>>Belarus</option>
                                <option value="BE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BE" ? "selected" : "" ?>>Belgium</option>
                                <option value="BZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BZ" ? "selected" : "" ?>>Belize</option>
                                <option value="BJ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BJ" ? "selected" : "" ?>>Benin</option>
                                <option value="BM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BM" ? "selected" : "" ?>>Bermuda</option>
                                <option value="BT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BT" ? "selected" : "" ?>>Bhutan</option>
                                <option value="BO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BO" ? "selected" : "" ?>>Bolivia</option>
                                <option value="BA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BA" ? "selected" : "" ?>>Bosnia and Herzegovina</option>
                                <option value="BW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BW" ? "selected" : "" ?>>Botswana</option>
                                <option value="BR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BR" ? "selected" : "" ?>>Brazil</option>
                                <option value="BN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BN" ? "selected" : "" ?>>Brunei Darussalam</option>
                                <option value="BG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BG" ? "selected" : "" ?>>Bulgaria</option>
                                <option value="BF" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BF" ? "selected" : "" ?>>Burkina Faso</option>
                                <option value="BI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "BI" ? "selected" : "" ?>>Burundi</option>
                                <option value="KH" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KH" ? "selected" : "" ?>>Cambodia</option>
                                <option value="CM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CM" ? "selected" : "" ?>>Cameroon</option>
                                <option value="CV" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CV" ? "selected" : "" ?>>Cape Verde</option>
                                <option value="KY" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KY" ? "selected" : "" ?>>Cayman Islands</option>
                                <option value="CF" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CF" ? "selected" : "" ?>>Central African Republic</option>
                                <option value="TD" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TD" ? "selected" : "" ?>>Chad</option>
                                <option value="CL" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CL" ? "selected" : "" ?>>Chile</option>
                                <option value="CN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CN" ? "selected" : "" ?>>China</option>
                                <option value="CX" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CX" ? "selected" : "" ?>>Christmas Island</option>
                                <option value="CC" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CC" ? "selected" : "" ?>>Cocos (Keeling) Islands</option>
                                <option value="CO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CO" ? "selected" : "" ?>>Colombia</option>
                                <option value="KM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KM" ? "selected" : "" ?>>Comoros</option>
                                <option value="CG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CG" ? "selected" : "" ?>>Congo</option>
                                <option value="CD" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CD" ? "selected" : "" ?>>Congo, The Democratic Republic of the</option>
                                <option value="CK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CK" ? "selected" : "" ?>>Cook Islands</option>
                                <option value="CR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CR" ? "selected" : "" ?>>Costa Rica</option>
                                <option value="CI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CI" ? "selected" : "" ?>>Cote D`Ivoire</option>
                                <option value="HR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "HR" ? "selected" : "" ?>>Croatia</option>
                                <option value="CY" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CY" ? "selected" : "" ?>>Cyprus</option>
                                <option value="CZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CZ" ? "selected" : "" ?>>Czech Republic</option>
                                <option value="DK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "DK" ? "selected" : "" ?>>Denmark</option>
                                <option value="DJ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "DJ" ? "selected" : "" ?>>Djibouti</option>
                                <option value="DM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "DM" ? "selected" : "" ?>>Dominica</option>
                                <option value="DO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "DO" ? "selected" : "" ?>>Dominican Republic</option>
                                <option value="EC" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "EC" ? "selected" : "" ?>>Ecuador</option>
                                <option value="EG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "EG" ? "selected" : "" ?>>Egypt</option>
                                <option value="SV" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SV" ? "selected" : "" ?>>El Salvador</option>
                                <option value="GQ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GQ" ? "selected" : "" ?>>Equatorial Guinea</option>
                                <option value="ER" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "ER" ? "selected" : "" ?>>Eritrea</option>
                                <option value="EE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "EE" ? "selected" : "" ?>>Estonia</option>
                                <option value="ET" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "ET" ? "selected" : "" ?>>Ethiopia</option>
                                <option value="FK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "FK" ? "selected" : "" ?>>Falkland Islands (Malvinas)</option>
                                <option value="FO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "FO" ? "selected" : "" ?>>Faroe Islands</option>
                                <option value="FJ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "FJ" ? "selected" : "" ?>>Fiji</option>
                                <option value="FI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "FI" ? "selected" : "" ?>>Finland</option>
                                <option value="FR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "FR" ? "selected" : "" ?>>France</option>
                                <option value="GF" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GF" ? "selected" : "" ?>>French Guiana</option>
                                <option value="PF" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PF" ? "selected" : "" ?>>French Polynesia</option>
                                <option value="GA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GA" ? "selected" : "" ?>>Gabon</option>
                                <option value="GM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GM" ? "selected" : "" ?>>Gambia</option>
                                <option value="GE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GE" ? "selected" : "" ?>>Georgia</option>
                                <option value="DE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "DE" ? "selected" : "" ?>>Germany</option>
                                <option value="GH" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GH" ? "selected" : "" ?>>Ghana</option>
                                <option value="GI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GI" ? "selected" : "" ?>>Gibraltar</option>
                                <option value="GR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GR" ? "selected" : "" ?>>Greece</option>
                                <option value="GL" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GL" ? "selected" : "" ?>>Greenland</option>
                                <option value="GD" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GD" ? "selected" : "" ?>>Grenada</option>
                                <option value="GP" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GP" ? "selected" : "" ?>>Guadeloupe</option>
                                <option value="GU" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GU" ? "selected" : "" ?>>Guam</option>
                                <option value="GT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GT" ? "selected" : "" ?>>Guatemala</option>
                                <option value="GN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GN" ? "selected" : "" ?>>Guinea</option>
                                <option value="GW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GW" ? "selected" : "" ?>>Guinea-Bissau</option>
                                <option value="GY" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "GY" ? "selected" : "" ?>>Guyana</option>
                                <option value="HT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "HT" ? "selected" : "" ?>>Haiti</option>
                                <option value="HN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "HN" ? "selected" : "" ?>>Honduras</option>
                                <option value="HK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "HK" ? "selected" : "" ?>>Hong Kong</option>
                                <option value="HU" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "HU" ? "selected" : "" ?>>Hungary</option>
                                <option value="IS" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "IS" ? "selected" : "" ?>>Iceland</option>
                                <option value="IN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "IN" ? "selected" : "" ?>>India</option>
                                <option value="ID" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "ID" ? "selected" : "" ?>>Indonesia</option>
                                <option value="IR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "IR" ? "selected" : "" ?>>Iran (Islamic Republic Of)</option>
                                <option value="IQ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "IQ" ? "selected" : "" ?>>Iraq</option>
                                <option value="IE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "IE" ? "selected" : "" ?>>Ireland</option>
                                <option value="IL" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "IL" ? "selected" : "" ?>>Israel</option>
                                <option value="IT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "IT" ? "selected" : "" ?>>Italy</option>
                                <option value="JM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "JM" ? "selected" : "" ?>>Jamaica</option>
                                <option value="JP" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "JP" ? "selected" : "" ?>>Japan</option>
                                <option value="JO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "JO" ? "selected" : "" ?>>Jordan</option>
                                <option value="KZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KZ" ? "selected" : "" ?>>Kazakhstan</option>
                                <option value="KE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KE" ? "selected" : "" ?>>Kenya</option>
                                <option value="KI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KI" ? "selected" : "" ?>>Kiribati</option>
                                <option value="KP" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KP" ? "selected" : "" ?>>Korea North</option>
                                <option value="KR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KR" ? "selected" : "" ?>>Korea South</option>
                                <option value="KW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KW" ? "selected" : "" ?>>Kuwait</option>
                                <option value="KG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KG" ? "selected" : "" ?>>Kyrgyzstan</option>
                                <option value="LA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LA" ? "selected" : "" ?>>Laos</option>
                                <option value="LV" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LV" ? "selected" : "" ?>>Latvia</option>
                                <option value="LB" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LB" ? "selected" : "" ?>>Lebanon</option>
                                <option value="LS" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LS" ? "selected" : "" ?>>Lesotho</option>
                                <option value="LR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LR" ? "selected" : "" ?>>Liberia</option>
                                <option value="LI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LI" ? "selected" : "" ?>>Liechtenstein</option>
                                <option value="LT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LT" ? "selected" : "" ?>>Lithuania</option>
                                <option value="LU" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LU" ? "selected" : "" ?>>Luxembourg</option>
                                <option value="MO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MO" ? "selected" : "" ?>>Macau</option>
                                <option value="MK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MK" ? "selected" : "" ?>>Macedonia</option>
                                <option value="MG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MG" ? "selected" : "" ?>>Madagascar</option>
                                <option value="MW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MW" ? "selected" : "" ?>>Malawi</option>
                                <option value="MY" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MY" ? "selected" : "" ?>>Malaysia</option>
                                <option value="MV" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MV" ? "selected" : "" ?>>Maldives</option>
                                <option value="ML" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "ML" ? "selected" : "" ?>>Mali</option>
                                <option value="MT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MT" ? "selected" : "" ?>>Malta</option>
                                <option value="MH" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MH" ? "selected" : "" ?>>Marshall Islands</option>
                                <option value="MQ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MQ" ? "selected" : "" ?>>Martinique</option>
                                <option value="MR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MR" ? "selected" : "" ?>>Mauritania</option>
                                <option value="MU" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MU" ? "selected" : "" ?>>Mauritius</option>
                                <option value="MX" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MX" ? "selected" : "" ?>>Mexico</option>
                                <option value="FM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "FM" ? "selected" : "" ?>>Micronesia</option>
                                <option value="MD" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MD" ? "selected" : "" ?>>Moldova</option>
                                <option value="MC" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MC" ? "selected" : "" ?>>Monaco</option>
                                <option value="MN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MN" ? "selected" : "" ?>>Mongolia</option>
                                <option value="MS" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MS" ? "selected" : "" ?>>Montserrat</option>
                                <option value="MA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MA" ? "selected" : "" ?>>Morocco</option>
                                <option value="MZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MZ" ? "selected" : "" ?>>Mozambique</option>
                                <option value="NA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NA" ? "selected" : "" ?>>Namibia</option>
                                <option value="NP" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NP" ? "selected" : "" ?>>Nepal</option>
                                <option value="NL" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NL" ? "selected" : "" ?>>Netherlands</option>
                                <option value="AN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AN" ? "selected" : "" ?>>Netherlands Antilles</option>
                                <option value="NC" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NC" ? "selected" : "" ?>>New Caledonia</option>
                                <option value="NZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NZ" ? "selected" : "" ?>>New Zealand</option>
                                <option value="NI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NI" ? "selected" : "" ?>>Nicaragua</option>
                                <option value="NE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NE" ? "selected" : "" ?>>Niger</option>
                                <option value="NG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NG" ? "selected" : "" ?>>Nigeria</option>
                                <option value="NO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "NO" ? "selected" : "" ?>>Norway</option>
                                <option value="OM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "OM" ? "selected" : "" ?>>Oman</option>
                                <option value="PK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PK" ? "selected" : "" ?>>Pakistan</option>
                                <option value="PW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PW" ? "selected" : "" ?>>Palau</option>
                                <option value="PS" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PS" ? "selected" : "" ?>>Palestine Autonomous</option>
                                <option value="PA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PA" ? "selected" : "" ?>>Panama</option>
                                <option value="PG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PG" ? "selected" : "" ?>>Papua New Guinea</option>
                                <option value="PY" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PY" ? "selected" : "" ?>>Paraguay</option>
                                <option value="PE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PE" ? "selected" : "" ?>>Peru</option>
                                <option value="PH" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PH" ? "selected" : "" ?>>Philippines</option>
                                <option value="PL" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PL" ? "selected" : "" ?>>Poland</option>
                                <option value="PT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PT" ? "selected" : "" ?>>Portugal</option>
                                <option value="PR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "PR" ? "selected" : "" ?>>Puerto Rico</option>
                                <option value="QA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "QA" ? "selected" : "" ?>>Qatar</option>
                                <option value="RE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "RE" ? "selected" : "" ?>>Reunion</option>
                                <option value="RO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "RO" ? "selected" : "" ?>>Romania</option>
                                <option value="RU" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "RU" ? "selected" : "" ?>>Russian Federation</option>
                                <option value="RW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "RW" ? "selected" : "" ?>>Rwanda</option>
                                <option value="VC" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "VC" ? "selected" : "" ?>>Saint Vincent and the Grenadines</option>
                                <option value="MP" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "MP" ? "selected" : "" ?>>Saipan</option>
                                <option value="SM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SM" ? "selected" : "" ?>>San Marino</option>
                                <option value="SA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SA" ? "selected" : "" ?>>Saudi Arabia</option>
                                <option value="SN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SN" ? "selected" : "" ?>>Senegal</option>
                                <option value="SC" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SC" ? "selected" : "" ?>>Seychelles</option>
                                <option value="SL" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SL" ? "selected" : "" ?>>Sierra Leone</option>
                                <option value="SG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SG" ? "selected" : "" ?>>Singapore</option>
                                <option value="SK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SK" ? "selected" : "" ?>>Slovak Republic</option>
                                <option value="SI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SI" ? "selected" : "" ?>>Slovenia</option>
                                <option value="SO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SO" ? "selected" : "" ?>>Somalia</option>
                                <option value="ZA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "ZA" ? "selected" : "" ?>>South Africa</option>
                                <option value="ES" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "ES" ? "selected" : "" ?>>Spain</option>
                                <option value="LK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LK" ? "selected" : "" ?>>Sri Lanka</option>
                                <option value="KN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "KN" ? "selected" : "" ?>>St. Kitts/Nevis</option>
                                <option value="LC" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "LC" ? "selected" : "" ?>>St. Lucia</option>
                                <option value="SD" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SD" ? "selected" : "" ?>>Sudan</option>
                                <option value="SR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SR" ? "selected" : "" ?>>Suriname</option>
                                <option value="SZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SZ" ? "selected" : "" ?>>Swaziland</option>
                                <option value="SE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SE" ? "selected" : "" ?>>Sweden</option>
                                <option value="CH" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "CH" ? "selected" : "" ?>>Switzerland</option>
                                <option value="SY" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "SY" ? "selected" : "" ?>>Syria</option>
                                <option value="TW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TW" ? "selected" : "" ?>>Taiwan</option>
                                <option value="TI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TI" ? "selected" : "" ?>>Tajikistan</option>
                                <option value="TZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TZ" ? "selected" : "" ?>>Tanzania</option>
                                <option value="TH" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TH" ? "selected" : "" ?>>Thailand</option>
                                <option value="TG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TG" ? "selected" : "" ?>>Togo</option>
                                <option value="TK" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TK" ? "selected" : "" ?>>Tokelau</option>
                                <option value="TO" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TO" ? "selected" : "" ?>>Tonga</option>
                                <option value="TT" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TT" ? "selected" : "" ?>>Trinidad and Tobago</option>
                                <option value="TN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TN" ? "selected" : "" ?>>Tunisia</option>
                                <option value="TR" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TR" ? "selected" : "" ?>>Turkey</option>
                                <option value="TM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TM" ? "selected" : "" ?>>Turkmenistan</option>
                                <option value="TC" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TC" ? "selected" : "" ?>>Turks and Caicos Islands</option>
                                <option value="TV" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "TV" ? "selected" : "" ?>>Tuvalu</option>
                                <option value="UG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "UG" ? "selected" : "" ?>>Uganda</option>
                                <option value="UA" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "UA" ? "selected" : "" ?>>Ukraine</option>
                                <option value="AE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "AE" ? "selected" : "" ?>>United Arab Emirates</option>
                                <option value="UY" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "UY" ? "selected" : "" ?>>Uruguay</option>
                                <option value="UZ" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "UZ" ? "selected" : "" ?>>Uzbekistan</option>
                                <option value="VU" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "VU" ? "selected" : "" ?>>Vanuatu</option>
                                <option value="VE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "VE" ? "selected" : "" ?>>Venezuela</option>
                                <option value="VN" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "VN" ? "selected" : "" ?>>Viet Nam</option>
                                <option value="VG" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "VG" ? "selected" : "" ?>>Virgin Islands (British)</option>
                                <option value="VI" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "VI" ? "selected" : "" ?>>Virgin Islands (U.S.)</option>
                                <option value="WF" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "WF" ? "selected" : "" ?>>Wallis and Futuna Islands</option>
                                <option value="YE" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "YE" ? "selected" : "" ?>>Yemen</option>
                                <option value="YU" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "YU" ? "selected" : "" ?>>Yugoslavia</option>
                                <option value="ZM" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "ZM" ? "selected" : "" ?>>Zambia</option>
                                <option value="ZW" <?php echo isset($billingAddress->countryCodeAlpha2) && $billingAddress->countryCodeAlpha2 == "ZW" ? "selected" : "" ?>>Zimbabwe</option>
                            </select>
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Country: End */ ?>
					
                    <?php /* State/Province: Start */ ?>
                    <div class="scformrow">
                        <label for="state">State/Province<span class="req">*</span></label>
                        <div class="scfield">
                            <select name="region" id="region" class="select">
                                <option value="">Please Select</option>
                                <optgroup label="Australian Provinces">
                                    <option value="-AU-NSW" <?php echo isset($billingAddress->region) && $billingAddress->region == "-AU-NSW" ? "selected" : "" ?>>New South Wales</option>
                                    <option value="-AU-QLD" <?php echo isset($billingAddress->region) && $billingAddress->region == "-AU-QLD" ? "selected" : "" ?>>Queensland</option>
                                    <option value="-AU-SA" <?php echo isset($billingAddress->region) && $billingAddress->region == "-AU-SA" ? "selected" : "" ?>>South Australia</option>
                                    <option value="-AU-TAS" <?php echo isset($billingAddress->region) && $billingAddress->region == "-AU-TAS" ? "selected" : "" ?>>Tasmania</option>
                                    <option value="-AU-VIC" <?php echo isset($billingAddress->region) && $billingAddress->region == "-AU-VIC" ? "selected" : "" ?>>Victoria</option>
                                    <option value="-AU-WA" <?php echo isset($billingAddress->region) && $billingAddress->region == "-AU-WA" ? "selected" : "" ?>>Western Australia</option>
                                    <option value="-AU-ACT" <?php echo isset($billingAddress->region) && $billingAddress->region == "-AU-ACT" ? "selected" : "" ?>>Australian Capital Territory</option>
                                    <option value="-AU-NT" <?php echo isset($billingAddress->region) && $billingAddress->region == "-AU-NT" ? "selected" : "" ?>>Northern Territory</option>
                                </optgroup>
                                <optgroup label="Canadian Provinces">
                                    <option value="AB" <?php echo isset($billingAddress->region) && $billingAddress->region == "AB" ? "selected" : "" ?>>Alberta</option>
                                    <option value="BC" <?php echo isset($billingAddress->region) && $billingAddress->region == "BC" ? "selected" : "" ?>>British Columbia</option>
                                    <option value="MB" <?php echo isset($billingAddress->region) && $billingAddress->region == "MB" ? "selected" : "" ?>>Manitoba</option>
                                    <option value="NB" <?php echo isset($billingAddress->region) && $billingAddress->region == "NB" ? "selected" : "" ?>>New Brunswick</option>
                                    <option value="NF" <?php echo isset($billingAddress->region) && $billingAddress->region == "NF" ? "selected" : "" ?>>Newfoundland</option>
                                    <option value="NT" <?php echo isset($billingAddress->region) && $billingAddress->region == "NT" ? "selected" : "" ?>>Northwest Territories</option>
                                    <option value="NS" <?php echo isset($billingAddress->region) && $billingAddress->region == "NS" ? "selected" : "" ?>>Nova Scotia</option>
                                    <option value="NVT" <?php echo isset($billingAddress->region) && $billingAddress->region == "NVT" ? "selected" : "" ?>>Nunavut</option>
                                    <option value="ON" <?php echo isset($billingAddress->region) && $billingAddress->region == "ON" ? "selected" : "" ?>>Ontario</option>
                                    <option value="PE" <?php echo isset($billingAddress->region) && $billingAddress->region == "PE" ? "selected" : "" ?>>Prince Edward Island</option>
                                    <option value="QC" <?php echo isset($billingAddress->region) && $billingAddress->region == "QC" ? "selected" : "" ?>>Quebec</option>
                                    <option value="SK" <?php echo isset($billingAddress->region) && $billingAddress->region == "SK" ? "selected" : "" ?>>Saskatchewan</option>
                                    <option value="YK" <?php echo isset($billingAddress->region) && $billingAddress->region == "YK" ? "selected" : "" ?>>Yukon</option>
                                </optgroup>
                                <optgroup label="US regions">
                                    <option value="AL" <?php echo isset($billingAddress->region) && $billingAddress->region == "AL" ? "selected" : "" ?>>Alabama</option>
                                    <option value="AK" <?php echo isset($billingAddress->region) && $billingAddress->region == "AK" ? "selected" : "" ?>>Alaska</option>
                                    <option value="AZ" <?php echo isset($billingAddress->region) && $billingAddress->region == "AZ" ? "selected" : "" ?>>Arizona</option>
                                    <option value="AR" <?php echo isset($billingAddress->region) && $billingAddress->region == "AR" ? "selected" : "" ?>>Arkansas</option>
                                    <option value="BVI" <?php echo isset($billingAddress->region) && $billingAddress->region == "BVI" ? "selected" : "" ?>>British Virgin Islands</option>
                                    <option value="CA" <?php echo isset($billingAddress->region) && $billingAddress->region == "CA" ? "selected" : "" ?>>California</option>
                                    <option value="CO" <?php echo isset($billingAddress->region) && $billingAddress->region == "CO" ? "selected" : "" ?>>Colorado</option>
                                    <option value="CT" <?php echo isset($billingAddress->region) && $billingAddress->region == "CT" ? "selected" : "" ?>>Connecticut</option>
                                    <option value="DE" <?php echo isset($billingAddress->region) && $billingAddress->region == "DE" ? "selected" : "" ?>>Delaware</option>
                                    <option value="FL" <?php echo isset($billingAddress->region) && $billingAddress->region == "FL" ? "selected" : "" ?>>Florida</option>
                                    <option value="GA" <?php echo isset($billingAddress->region) && $billingAddress->region == "GA" ? "selected" : "" ?>>Georgia</option>
                                    <option value="GU" <?php echo isset($billingAddress->region) && $billingAddress->region == "GU" ? "selected" : "" ?>>Guam</option>
                                    <option value="HI" <?php echo isset($billingAddress->region) && $billingAddress->region == "HI" ? "selected" : "" ?>>Hawaii</option>
                                    <option value="ID" <?php echo isset($billingAddress->region) && $billingAddress->region == "ID" ? "selected" : "" ?>>Idaho</option>
                                    <option value="IL" <?php echo isset($billingAddress->region) && $billingAddress->region == "IL" ? "selected" : "" ?>>Illinois</option>
                                    <option value="IN" <?php echo isset($billingAddress->region) && $billingAddress->region == "IN" ? "selected" : "" ?>>Indiana</option>
                                    <option value="IA" <?php echo isset($billingAddress->region) && $billingAddress->region == "IA" ? "selected" : "" ?>>Iowa</option>
                                    <option value="KS" <?php echo isset($billingAddress->region) && $billingAddress->region == "KS" ? "selected" : "" ?>>Kansas</option>
                                    <option value="KY" <?php echo isset($billingAddress->region) && $billingAddress->region == "KY" ? "selected" : "" ?>>Kentucky</option>
                                    <option value="LA" <?php echo isset($billingAddress->region) && $billingAddress->region == "LA" ? "selected" : "" ?>>Louisiana</option>
                                    <option value="ME" <?php echo isset($billingAddress->region) && $billingAddress->region == "ME" ? "selected" : "" ?>>Maine</option>
                                    <option value="MP" <?php echo isset($billingAddress->region) && $billingAddress->region == "MP" ? "selected" : "" ?>>Mariana Islands</option>
                                    <option value="MPI" <?php echo isset($billingAddress->region) && $billingAddress->region == "MPI" ? "selected" : "" ?>>Mariana Islands (Pacific)</option>
                                    <option value="MD" <?php echo isset($billingAddress->region) && $billingAddress->region == "MD" ? "selected" : "" ?>>Maryland</option>
                                    <option value="MA" <?php echo isset($billingAddress->region) && $billingAddress->region == "MA" ? "selected" : "" ?>>Massachusetts</option>
                                    <option value="MI" <?php echo isset($billingAddress->region) && $billingAddress->region == "MI" ? "selected" : "" ?>>Michigan</option>
                                    <option value="MN" <?php echo isset($billingAddress->region) && $billingAddress->region == "MN" ? "selected" : "" ?>>Minnesota</option>
                                    <option value="MS" <?php echo isset($billingAddress->region) && $billingAddress->region == "MS" ? "selected" : "" ?>>Mississippi</option>
                                    <option value="MO" <?php echo isset($billingAddress->region) && $billingAddress->region == "MO" ? "selected" : "" ?>>Missouri</option>
                                    <option value="MT" <?php echo isset($billingAddress->region) && $billingAddress->region == "MT" ? "selected" : "" ?>>Montana</option>
                                    <option value="NE" <?php echo isset($billingAddress->region) && $billingAddress->region == "NE" ? "selected" : "" ?>>Nebraska</option>
                                    <option value="NV" <?php echo isset($billingAddress->region) && $billingAddress->region == "NV" ? "selected" : "" ?>>Nevada</option>
                                    <option value="NH" <?php echo isset($billingAddress->region) && $billingAddress->region == "NH" ? "selected" : "" ?>>New Hampshire</option>
                                    <option value="NJ" <?php echo isset($billingAddress->region) && $billingAddress->region == "NJ" ? "selected" : "" ?>>New Jersey</option>
                                    <option value="NM" <?php echo isset($billingAddress->region) && $billingAddress->region == "NM" ? "selected" : "" ?>>New Mexico</option>
                                    <option value="NY" <?php echo isset($billingAddress->region) && $billingAddress->region == "NY" ? "selected" : "" ?>>New York</option>
                                    <option value="NC" <?php echo isset($billingAddress->region) && $billingAddress->region == "NC" ? "selected" : "" ?>>North Carolina</option>
                                    <option value="ND" <?php echo isset($billingAddress->region) && $billingAddress->region == "ND" ? "selected" : "" ?>>North Dakota</option>
                                    <option value="OH" <?php echo isset($billingAddress->region) && $billingAddress->region == "OH" ? "selected" : "" ?>>Ohio</option>
                                    <option value="OK" <?php echo isset($billingAddress->region) && $billingAddress->region == "OK" ? "selected" : "" ?>>Oklahoma</option>
                                    <option value="OR" <?php echo isset($billingAddress->region) && $billingAddress->region == "OR" ? "selected" : "" ?>>Oregon</option>
                                    <option value="PA" <?php echo isset($billingAddress->region) && $billingAddress->region == "PA" ? "selected" : "" ?>>Pennsylvania</option>
                                    <option value="PR" <?php echo isset($billingAddress->region) && $billingAddress->region == "PR" ? "selected" : "" ?>>Puerto Rico</option>
                                    <option value="RI" <?php echo isset($billingAddress->region) && $billingAddress->region == "RI" ? "selected" : "" ?>>Rhode Island</option>
                                    <option value="SC" <?php echo isset($billingAddress->region) && $billingAddress->region == "SC" ? "selected" : "" ?>>South Carolina</option>
                                    <option value="SD" <?php echo isset($billingAddress->region) && $billingAddress->region == "SD" ? "selected" : "" ?>>South Dakota</option>
                                    <option value="TN" <?php echo isset($billingAddress->region) && $billingAddress->region == "TN" ? "selected" : "" ?>>Tennessee</option>
                                    <option value="TX" <?php echo isset($billingAddress->region) && $billingAddress->region == "TX" ? "selected" : "" ?>>Texas</option>
                                    <option value="UT" <?php echo isset($billingAddress->region) && $billingAddress->region == "UT" ? "selected" : "" ?>>Utah</option>
                                    <option value="VT" <?php echo isset($billingAddress->region) && $billingAddress->region == "VT" ? "selected" : "" ?>>Vermont</option>
                                    <option value="USVI" <?php echo isset($billingAddress->region) && $billingAddress->region == "USVI" ? "selected" : "" ?>>VI U.S. Virgin Islands</option>
                                    <option value="VA" <?php echo isset($billingAddress->region) && $billingAddress->region == "VA" ? "selected" : "" ?>>Virginia</option>
                                    <option value="WA" <?php echo isset($billingAddress->region) && $billingAddress->region == "WA" ? "selected" : "" ?>>Washington</option>
                                    <option value="DC" <?php echo isset($billingAddress->region) && $billingAddress->region == "DC" ? "selected" : "" ?>>Washington, D.C.</option>
                                    <option value="WV" <?php echo isset($billingAddress->region) && $billingAddress->region == "WV" ? "selected" : "" ?>>West Virginia</option>
                                    <option value="WI" <?php echo isset($billingAddress->region) && $billingAddress->region == "WI" ? "selected" : "" ?>>Wisconsin</option>
                                    <option value="WY" <?php echo isset($billingAddress->region) && $billingAddress->region == "WY" ? "selected" : "" ?>>Wyoming</option>
                                </optgroup>
                                <option value="N/A" <?php echo isset($billingAddress->region) && $billingAddress->region == "N/A" ? "selected" : "" ?>>Other</option>
                            </select>
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* State/Province: End */ ?>
					
                    <?php /* Zip/Postal Code: Start */ ?>
                    <div class="scformrow">
                        <label for="postalCode">ZIP/Postal Code<span class="req">*</span></label>
                        <div class="scfield">
                            <input data-braintree-name="postalCode" 
								name="postalCode" 
								id="postalCode" 
								type="text" 
								class="text" 
								value="<?php echo isset($billingAddress->postalCode) ? $billingAddress->postalCode : ''; ?>" />
                            
							<div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Zip/Postal Code: End */ ?>
					
                    <div class="clear"></div>
                </div>
                <?php /* Billing Information: End */ ?>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
        <?php /* Form Column - Left: End */ ?>
		
        <?php /* Form Column - Right: Start */ ?>
        <div class="scformcol twocol colright">
            <div class="scformcol_inside">
				
                <?php /* Payment Method: Start */ ?>
                <h2 class="scformsectitle">Payment Method</h2>
				
                <div class="scformsection">
					
                    <?php /* Payment Method: Start */ ?>
                    <div class="scformrow">
						
                        <ul class="scpaymentoptionslist">
							
                            <?php /* CC Selection: Start */ ?>
                            <li class="first visa">
                                <input name="cctype" type="radio" value="V" class="radio lft-field" />
                                <div title="Visa" class="lft-field cardhide cardicon visa V"></div>
                            </li>
							
                            <li class="mastercard">
                                <input name="cctype" type="radio" value="M" class="radio lft-field" />
                                <div title="Master Card" class="lft-field cardhide cardicon mastercard M"></div>
                            </li>
							
                            <li class="amex">
                                <input name="cctype" type="radio" value="A" class="radio lft-field" />
                                <div title="American Express" class="lft-field cardhide cardicon amex A"></div>
                            </li>
							
                            <li <?php echo (/* $enable_paypal == */true) ? 'class="discover"' : 'class="discover last"' ?>>
                                <input name="cctype" type="radio" value="D" class="radio lft-field" />
                                <div title="Discover Card" class="lft-field cardhide cardicon discover D"></div>
                            </li>
                            <?php /* CC Selection: End */ ?>
							
                            <?php /* If PayPal: Start */ ?>
                            <?php if (true/* $enable_paypal */) { ?>
                                <li class="paypal last">
                                    <input name="cctype" type="radio" value="PP" class="radio lft-field isPayPal" />
                                    <div title="PayPal" class="lft-field paypal cardhide cardicon paypal PP"></div>
                                </li>
                            <?php } ?>
                            <?php /* If PayPal: End */ ?>
							
                        </ul>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Payment Method: End */ ?>
					
                    <?php /* Card Card Info: Start */ ?>
                    <div class="scformrow ccinfo">
						
                        <?php /* CC Name on Card: Start */ ?>
                        <div class="scformrow">
                            <label for="cardholderName">Name on Card<span class="req">*</span></label>
                            <div class="scfield fldicon">
                                <input data-braintree-name="cardholderName" 
									name="cardholderName" 
									id="cardholderName" 
									type="text" 
									class="text" 
									value="<?php echo isset($creditCard->cardholderName) ? $creditCard->cardholderName : ''; ?>" />
								
                                <i class="fa fa-lock"></i>
								
                                <div class="clear"></div>
                            </div>
							
                            <div class="clear"></div>
                        </div>
                        <?php /* CC Name on Card: End */ ?>
						
                        <?php /* CC Card Number: Start */ ?>
                        <div class="scformrow">
                            <label for="number">Card Number<span class="req">*</span></label>
                            <div class="scfield fldicon">
                                <?php if (isset($creditCard->maskedNumber) && $creditCard->maskedNumber) { ?>
                                    <input name="cardnumber" 
										value="<?php echo $creditCard->maskedNumber; ?>" 
										id="number" 
										type="text" 
										class="text" 
										maxlength="16" 
										readonly="" />
                                <?php } else { ?>
                                    <input data-braintree-name="number" 
										name="cardnumber" 
										value="<?php echo isset($creditCard->number) ? $creditCard->number : ''; ?>" 
										id="number" 
										type="text" 
										class="text" 
										maxlength="16" />
                                <?php } ?>
								
                                <i class="fa fa-lock"></i>
								
                                <div class="clear"></div>
                            </div>
							
                            <span class="ccresult"></span>
							
                            <div class="clear"></div>
                        </div>
                        <?php /* CC Card Number: Start */ ?>
						
                        <?php /* CC Expiration Date: Start */ ?>
                        <div class="scformrow">
                            <label for="expirationMonth">Expiration Date<span class="req">*</span></label>
                            <div class="scfield dblfld">
                                <div class="fldleft">
                                    <div class="dblfldinside">
                                        <select data-braintree-name="expirationMonth" name="expirationMonth" id="expirationMonth" class="select">
                                            <?php foreach ($months as $month) { ?>
                                                <option value="<?php echo $month; ?>" <?php echo isset($creditCard->expirationMonth) && $creditCard->expirationMonth == $month ? 'selected="selected"' : ''; ?>><?php echo $month; ?></option>
                                            <?php } ?>
                                        </select>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="fldright">
                                    <div class="dblfldinside">
                                        <select data-braintree-name="expirationYear" name="expirationYear" id="expirationYear" class="select">
                                            <?php foreach ($years as $year) { ?>
                                                <option value="<?php echo $year; ?>" <?php echo isset($creditCard->expirationYear) && $creditCard->expirationYear == $year ? 'selected="selected"' : ''; ?>><?php echo $year; ?></option>
                                            <?php } ?>
                                        </select>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
							
                            <div class="clear"></div>
                        </div>
                        <?php /* CC Expiration Date: End */ ?>
						
                        <?php /* CC CVV Code: Start */ ?>
                        <div class="scformrow">
                            <label for="cvv">CVV<span class="req">*</span></label>
                            <div class="scfield dblfld">
                                <div class="fldleft fldicon">
                                    <div class="dblfldinside">
                                        <?php if (isset($creditCard->maskedNumber) && $creditCard->maskedNumber) { ?>
                                            <input name="cvv" 
												value="" 
												id="cvv" 
												type="text" 
												maxlength="5" 
												class="text" 
												disabled="" />
                                        <?php } else { ?>
                                            <input data-braintree-name="cvv" 
												name="cvv" 
												value="<?php echo isset($creditCard->cvv) ? $creditCard->cvv : ''; ?>" 
												id="cvv" 
												type="text" 
												maxlength="5" 
												class="text" />
                                        <?php } ?>
										
                                        <i class="fa fa-lock"></i>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="fldright">
                                    <div class="dblfldinside">
                                        <a href="user/hint" rel="hint" class="noscriptCase">
                                            <img src="<?php echo base_url(); ?>core/images/payment_processing/ico_question.jpg" alt="Question" />
                                        </a>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
							
                            <noscript>
                            <a href="user/hint" target="_blank">
                                <img src="<?php echo base_url(); ?>core/images/payment_processing/ico_question.jpg" alt="User Hint" />
                            </a>
                            </noscript>
							
                            <div class="clear"></div>
                        </div>
                        <?php /* CC CVV Code: End */ ?>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Credit Card Info: End */ ?>
					
                    <div class="clear"></div>
					
                    <div class="scformrow">
                        <div class="paymentMethodCheckbox">
                            <div class="radiobttnholder paymentMethodCheckbox active">
                                <input type="checkbox" name="savePaymentMethod" value="1" checked="checked" class="radio active" />
                                Save payment method?
                            </div>
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
					
                    <div class="clear"></div>
					
                    <?php /* Card Card Info: Start */ ?>
                    <div id="paypal-info" class="scformrow">
                        <input type="hidden" id="payment_method_nonce_paypal" name="payment_method_nonce_paypal" />
                        <input type="hidden" name="paypal_email" />
                    </div>
                    <?php /* Credit Card Info: End */ ?>
					
					<div class="clear"></div>
                </div>
                <?php /* Payment Method: End */ ?>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
        <?php /* Form Column - Right: End */ ?>
		
		<div class="clear"></div>
    </div>
	
    <div class="clear"></div>
	
    <div class="buttonrow">
        <button class="button submit" type="submit">Checkout</button>
    </div>
	
    <div class="clear"></div>
	
    <div class="scpayprocessing">
        <div class="scpayrow downgrade">
            <?php if (isset($downgrade) && $downgrade) { ?>
                <h1>Downgrade Plan</h1>
				
                <p>You are downgrading your plan <strong><?php echo $current_plan->user_type_name; ?></strong> to <strong><?php echo $entity->user_type_name; ?></strong>.</p>
            <?php } else { ?>
                <h1>Upgrade Plan</h1>
				
                <p>You are upgrading your plan <strong><?php echo $current_plan->user_type_name; ?></strong> to <strong><?php echo $entity->user_type_name; ?></strong>.</p>
            <?php } ?>
            <p>In your new plan <strong><?php echo $entity->user_type_name; ?></strong>, you will have the following features:</p>
			
            <ul class="planfeatureslist">
                <?php
                if (isset($entity->feature_details) && $entity->feature_details && count($entity->feature_details) > 0) {
                    foreach ($entity->feature_details as $detail) {
                        ?>
                        <li class="odd first">
                            <span><?php echo $detail; ?></span>
                        </li>
                        <?php
                    }
                }
                ?>
                <li class="odd first"><span>And more...</span></li>
            </ul>
			
            <div class="clear"></div>
        </div>
    </div>
</form>

<script>
    var BT_CLIENT_TOKEN = "<?php echo $bt_client_token; ?>";
</script>
