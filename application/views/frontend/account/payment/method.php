<link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/payprocessing.css" rel="stylesheet" media="all" />

<?php /* Available Payment Methods: Start */ ?>
<?php if (isset($paymentMethodList) && count($paymentMethodList) > 0) { ?>
    <div class="availpaymethods">
        <h2 class="scformsectitle">Available Payment Methods</h2>

        <ul class="availpaymethodslist">
            <?php foreach ($paymentMethodList as $paymentMethod) { ?>
                <li class="availpaymethoditem 
					<?php
					// Card Type
					echo strtolower($paymentMethod->cardType);

					// If Default Payment Method
					echo $paymentMethod->default ? ' default' : '';
					?>">

                    <div class="availpaymethoditem_inner">

                        <?php /* Card Holder Name: Start */ ?>
                        <div class="availpaymethod_holdername">
                            <?php echo '<span>Name:</span> ' . $paymentMethod->cardholderName; ?>
                        </div>
                        <?php /* Card Holder Name: End */ ?>

                        <?php /* Card Type and Expiration: Start */ ?>
                        <div class="availpaymethod_cardinfo">
                            <?php
                            if (isset($paymentMethod->email)) {
                                echo '<span>PayPal Email:</span> ' . $paymentMethod->email;
                            } else {
                                echo '<span>Card #</span>' . $paymentMethod->maskedNumber . '<br/>';
                                echo '<span>Expiration:</span> ' . $paymentMethod->expirationMonth . '/' . $paymentMethod->expirationYear;
                            }
                            ?>
                        </div>
                        <?php /* Card Type and Expiration: End */ ?>

                        <div class="buttoncontainer">

                            <?php /* Button - View/Change: Start */ ?>
                            <div class="availbttnitem">
                                <a class="button md blue availpaymentchange" 
									href="<?php echo $base_url . 'paymentMethod/update/' . $paymentMethod->token; ?>">
                                    View/Change
                                </a>
                            </div>
                            <?php /* Button - View/Change: End */ ?>

                            <?php /* Button - Delete: Start */ ?>
                            <div class="availbttnitem">
                                <a class="button md red availpaymentdelete" 
									onclick="return confirm('Are you sure to continue?');" 
									href="<?php echo $base_url . 'paymentmethod/delete/' . $paymentMethod->token; ?>">
                                    Delete
                                </a>
                            </div>
                            <?php /* Button - Delete: End */ ?>

                            <?php /* Button - Make Default: Start */ ?>
                            <?php if (!$paymentMethod->default) { ?>
                                <div class="availbttnitem">
                                    <a class="button md green availpaymentdefault" 
										onclick="return confirm('Are you sure to continue?');" 
										href="<?php echo $base_url . 'paymentmethod/makeDefault/' . $paymentMethod->token; ?>">
										Make Default
                                    </a>
                                </div>
                            <?php } ?>
                            <?php /* Button - Make Default: End */ ?>

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                </li>
            <?php } ?>
        </ul>

        <div class="clear"></div>
    </div>
<?php } ?>
<?php /* Available Payment Methods: End */ ?>

<form id="payment-method-form" method="post">

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
                            <?php echo getCountryOptions(isset($billingAddress->countryCodeAlpha2) ? $billingAddress->countryCodeAlpha2 : null); ?>
                        </select>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Country: End */ ?>

                <?php /* State/Province: Start */ ?>
                <div class="scformrow">
                    <label for="region">State/Province<span class="req">*</span></label>
                    <div class="scfield">
                        <select name="region" id="region" class="select">
                            <option value="">Please Select</option>
                            <?php echo getStateOptions(isset($billingAddress->region) ? $billingAddress->region : null); ?>
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

                        <?php /* If Paypal: Start */ ?>
                        <?php if (true/* $enable_paypal */) { ?>
                            <li class="paypal last">
                                <input name="cctype" type="radio" value="PP" class="radio lft-field isPayPal" />
                                <div title="PayPal" class="lft-field paypal cardhide cardicon paypal PP"></div>
                            </li>
                        <?php } ?>
                        <?php /* If Paypal: End */ ?>

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
                                            <option value="<?php echo $month; ?>" <?php echo isset($creditCard->expirationMonth) && $creditCard->expirationMonth == $month ? 'selected="selected"' : ''; ?>>
                                                <?php echo $month; ?>
                                            </option>
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
                                            <option value="<?php echo $year; ?>" <?php echo isset($creditCard->expirationYear) && $creditCard->expirationYear == $year ? 'selected="selected"' : ''; ?>>
                                                <?php echo $year; ?>
                                            </option>
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
                                    <a href="#" class="paymentcvvhint">
                                        <span>Payment CSS Hint</span>
                                    </a>

                                    <div class="clear"></div>
                                </div>

                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>

                        <noscript>
							<p>This form can only be submitted if JavaScript is enabled.</p>
                        </noscript>

                        <div class="clear"></div>
                    </div>
                    <?php /* CC CVV Code: End */ ?>

                    <div class="clear"></div>
                </div>
                <?php /* Credit Card Info: End */ ?>

                <div class="clear"></div>

                <?php /* Card Card Info: Start */ ?>
                <div id="paypal-info" class="scformrow">
                    <input type="hidden" id="payment_method_nonce_paypal" name="payment_method_nonce_paypal" />
                    <input type="hidden" name="paypal_email" />
                </div>
                <?php /* Credit Card Info: End */ ?>

                <div class="clear"></div>

                <div class="scformrow">
                    <div class="defaultPaymentMethodCheckbox">
                        <div class="radiobttnholder defaultPaymentMethodCheckbox active">
                            <input id="setasdefault" type="checkbox" name="defaultPaymentMethod" value="1" checked="checked" class="radio active" />
                            Set As Default Payment Method?
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>

                <?php /* Submit Button: Start */ ?>
                <div class="scformrow submitbtn">
                    <div class="scfield">
                        <?php if (isset($token) && $token) { ?>
                            <button class="button submit" style="margin-right: 8px; float: left;" type="submit">Save Changes</button>
                            <a class="button cancel red" style="float: left;" href="<?php echo $base_url; ?>paymentmethod">Cancel</a>
                        <?php } else { ?>
                            <button class="button submit" type="submit">Submit</button>
                        <?php } ?>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Submit Button: End */ ?>

                <input type="hidden" name="process" value="yes" />	
            </div>
            <?php /* Payment Method: End */ ?>

            <div class="clear"></div>
        </div>

        <div class="clear"></div>
    </div>
    <?php /* Form Column - Right: End */ ?>

</form>

<script>
    var BT_CLIENT_TOKEN = "<?php echo $bt_client_token; ?>";
</script>
