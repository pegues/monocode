<html>
    <head>
        <title>Invoice #<?php echo $invoice->invoice_number; ?></title>
        <style>
            html, body, div, span, applet, object, iframe,
            h1, h2, h3, h4, h5, h6, p, blockquote, pre,
            a, abbr, acronym, address, big, cite, code,
            del, dfn, em, font, ins, kbd, q, s, samp,
            small, strike, strong, sub, sup, tt, var,
            dl, dt, dd, ol, ul, li,
            fieldset, form, label, legend,
            table, caption, tbody, tfoot, thead, tr, th, td,
            article, aside, details, figcaption, figure,
            footer, header, main, nav, section {
                margin: 0;
                padding: 0;
                font-family: inherit;
                font-size: 100%;
                font-style: inherit;
                font-weight: inherit;
                outline: 0;
                vertical-align: baseline;
                border: 0;
            }
            html, body { width: 100%; height: 100%; }
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, Helvetica, Sans-serif;
                font-size: 14px;
                font-weight: normal;
                line-height: 1;
                background-color: #fff;
            }

            article,aside,details,figcaption,figure,
            footer,header,main,nav,section { display: block; }

            ol, ul { list-style: none; }

            table {
                border-collapse: collapse;
                border-spacing: 0;
            }
            caption, th, td {
                font-weight: normal;
                text-align: left;
            }

            body,
            button,
            input,
            select,
            textarea,
            .whistles {
                color: #646c7f;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 15px;
                line-height: 1.5;

                -webkit-font-smoothing: antialiased;
            }

            hr {
                margin-bottom: 1.5em;
                height: 1px;
                border: 0;
                background-color: #ccc;
            }

            h1, h2, h3, h4, h5, h6 { clear: both; }

            b, strong { font-weight: bold; }

            blockquote, address {
                margin: 1.5em 0;
                padding-left: 1.5em;
                border-left: 2px solid #37353a;
            }

            pre {
                margin-bottom: 1.6em;
                padding: 1.6em;
                max-width: 100%;
                font-family: "Courier 10 Pitch", Courier, monospace;
                font-size: 15px;
                font-size: 1.5rem;
                line-height: 1.6;
                overflow: auto;
                background: #eee;
            }

            code, kbd, tt, var {
                font: 15px Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono", monospace;
            }

            img {
                margin: 0;
                padding: 0;
                display: block;
                max-width: 100%;
                height: auto;
                border: 0 none;
            }

            /* Border-box for All Elements */
            *,
            *::after,
            *::before {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: 	border-box;
                box-sizing: 		border-box;
            }

            .clear {
                clear: both;
                margin: 0;
                padding: 0;
                width: 0;
                height: 0;
                font-size: 0;
                line-height: 0;
                overflow: hidden;
                visibility: hidden;
                *zoom: 1;
            }

            div.invoicecontainer {
                padding: 5px;
            }
            div.invoicecontainerinside {}

            /* Invoice Header */
            div.invoiceheader {
                color: #fff;
                background-color: #1e2229;
            }

            /* Invoice Personal */
            div.invoicepersonel {
                margin-top: 5px;
                text-align: center;
            }
            div.invoicepersonel_col {
                float: left;
                width: 50%;
            }
            div.invoicepersonel_col.left {
                border-top: 5px solid #2a2a2a;
            }
            div.invoicepersonel_col.right {
                border-top: 5px solid #0dc0c0;
            }

            div.invoiceto {}
            div.invoiceto h2 {
                padding: 15px 0 0;
                color: #0dc0c0;
                font-size: 18px;
                letter-spacing: 5px;
                text-transform: uppercase;
            }
            div.invoiceto p {
                padding: 0 0 15px;
                font-weight: bold;
            }

            /* Invoice Details */
            div.invoicedetails {
                padding: 15px 15px 0;
                overflow: hidden;
            }
            ul.invoicedetailslist {}
            li.invoicedetialsitem {
                padding: 0 5px 0 0;
                float: left;
                color: #0dc0c0;
                font-size: 12px;
                line-height: 25px;
            }
            li.invoicedetialsitem:last-child { padding-right: 0; }

            li.invoicedetialsitem.title {}
            li.invoicedetialsitem.data {}

            li.invoicedetialsitem span.invitemlabel {}
            li.invoicedetialsitem.title span.invitemlabel {
                font-size: 25px;
                text-transform: uppercase;
            }
            li.invoicedetialsitem span.invitemdata {}

            /* Invoice Items */
            div.invoiceitems {
                padding: 15px 0 0;
            }
            table.invoiceitemstable {
                width: 100%;
                font-size: 13px;
                border: 0 none;
            }
            table.invoiceitemstable thead {}
            table.invoiceitemstable tbody {}

            table.invoiceitemstable tr {}
            table.invoiceitemstable th,
            table.invoiceitemstable td {
                padding: 8px;
            }

            /* Default Cell Styling */
            table.invoiceitemstable th {
                color: #fff;
                text-transform: uppercase;
                background-color: #2a2a2a;
            }
            table.invoiceitemstable td {
                background-color: #f5f5f5;
            }

            /* Table Header Cell Styles */
            table.invoiceitemstable th.linecounter {
                text-align: center;
                background-color: #0dc0c0;
            }
            table.invoiceitemstable th.description {}
            table.invoiceitemstable th.rate {}
            table.invoiceitemstable th.quantity { text-align: center; }
            table.invoiceitemstable th.price { text-align: right; }

            /* Table Content Cell Styling */
            table.invoiceitemstable td.linecounter {
                color: #fff;
                text-align: center;
                background-color: #2a2a2a;
            }
            table.invoiceitemstable td.description {}
            table.invoiceitemstable td.rate {}
            table.invoiceitemstable td.quantity { text-align: center; }
            table.invoiceitemstable td.price { text-align: right; }

            tr.itemheaderrow {}
            tr.itembodyrow {}

            /* Invoice Totals */
            div.invoicetotals {
                position: relative;
                margin: 15px 0 0;
                padding: 15px 0 0;
                overflow: hidden;
                border-top: 1px solid #e2e2e2;
            }

            div.paymentdetails {
                /*					position: absolute;
                                                        top: 30px;
                                                        left: 15px;*/
            }
            div.paymentdetails h3 {
                font-weight: bold;
            }
            div.paymentdetails p {
                font-size: 13px;
            }

            div.invoicetotaldetails {
                /*					float: right;
                                                        width: 40%;*/
            }
            div.invoicetotaldetails_row {
                overflow: hidden;
            }
            div.invoicetotaldetails_row.subtotal {}
            div.invoicetotaldetails_row.discounts {}
            div.invoicetotaldetails_row.total {}

            span.invoicetotal_label,
            span.invoicetotal_data {
                padding: 2px 5px;
                float: left;
                width: 50%;
                font-size: 12px;
            }
            span.invoicetotal_label {
                text-align: right;
                text-transform: uppercase;
            }
            span.invoicetotal_data {
                padding-right: 10px;
                text-align: right;
            }

            /* Total */
            div.invoicetotaldetails_row.total span.invoicetotal_label,
            div.invoicetotaldetails_row.total span.invoicetotal_data {
                padding-top: 5px;
                padding-bottom: 5px;
                color: #fff;
                font-size: 14px;
                font-weight: bold;
            }
            div.invoicetotaldetails_row.total span.invoicetotal_label {
                background-color: #2a2a2a;
            }
            div.invoicetotaldetails_row.total span.invoicetotal_data {
                background-color: #0dc0c0;
            }


            /* Invoice Footer */
            div.invoicefooter {
                /*				position: fixed;
                                                bottom: 10px;
                                                left: 5px;*/
                padding: 15px 0 0;
                width: 98%;
            }
            div.invoiceftr_col {
                float: left;
                width: 50%;
            }

            /* Terms */
            div.invoiceftr_col.left {
                padding: 15px 10px 0 15px;
                border-top: 5px solid #2a2a2a;
            }
            div.invoiceftr_col.left h3 {
                color: #0dc0c0;
            }
            div.invoiceftr_col.left p {
                font-size: 11px;
            }

            /* Thank you Message */
            div.invoiceftr_col.right {
                padding: 15px 15px 0 10px;
                border-top: 5px solid #0dc0c0;
            }
            div.invoiceftr_col.right p {
                padding-top: 15px;
                color: #0dc0c0;
                font-size: 20px;
                text-align: right;
            }

        </style>
    </head>
    <body>

        <div class="invoicecontainer">
            <div class="invoicecontainerinside">

                <?php /* Header: Start */ ?>
                <div class="invoiceheader">
                    <div class="invheadercol left">
                        <div class="invheaderlogo">
                            Monocode
                        </div>

                        <?php echo $settings->invoice_company_address; ?>

                        <div class="clear"></div>
                    </div>
                    <div class="invheadercol right">

                        <?php echo $settings->invoice_company_phone_number; ?>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Header: End */ ?>

                <?php /* Invoice To Information: Start */ ?>
                <div class="invoicepersonel">

                    <div class="invoicepersonel_col left"></div>
                    <div class="invoicepersonel_col right"></div>

                    <div class="invoiceto">
                        <h2>Invoice To</h2>
                        <?php
                        $name = '';
                        $address = '';
                        if ($user->use_billing_info) {
                            $name = $user->first_name . ' ' . $user->last_name;
                            $state = getStateByCode($user->state_code);
                            if (!$state && $user->state_code) {
                                $state = $user->state_code;
                            }
                            $address = $user->address . ', ' . $user->city . ', ' . ($state ? $state : $user->state_code) . ', ' . $user->zip . ', ' . getCountryByCode($user->country_code);
                        } else {
                            $name = $user->billing_first_name . ' ' . $user->billing_last_name;
                            $state = getStateByCode($user->billing_state_code);
                            if (!$state && $user->billing_state_code) {
                                $state = $user->billing_state_code;
                            }
                            $address = $user->billing_address . ', ' . $user->billing_city . ', ' . ($state ? $state : $user->billing_state_code) . ', ' . $user->billing_zip . ', ' . getCountryByCode($user->billing_country_code);
                        }
                        ?>
                        <p>
                            <em><?php echo $name; ?></em><br/><?php echo $address; ?>
                        </p>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Invoice To Information: End */ ?>

                <?php /* Invoice Details: Start */ ?>
                <div class="invoicedetails">
                    <ul class="invoicedetailslist">
                        <li class="invoicedetialsitem title">
                            <span class="invitemlabel">Invoice</span>
                        </li>
                        <li class="invoicedetialsitem data">
                            <span class="invitemlabel">Invoice No:</span>
                            <span class="invitemdata"><?php echo $invoice->invoice_number; ?></span>
                        </li>
                        <li class="invoicedetialsitem sep">
                            <span class="invitemlabel">|</span>
                        </li>
                        <li class="invoicedetialsitem data">
                            <span class="invitemlabel">Created Date:</span>
                            <span class="invitemdata"><?php echo date('Y-m-d H:i', strtotime($invoice->created_date)); ?></span>
                        </li>
                        <li class="invoicedetialsitem sep">
                            <span class="invitemlabel">|</span>
                        </li>
                        <li class="invoicedetialsitem data">
                            <span class="invitemlabel">Paid Date:</span>
                            <span class="invitemdata"><?php echo date('Y-m-d H:i', strtotime($invoice->paid_date)); ?></span>
                        </li>
                    </ul>

                    <div class="clear"></div>
                </div>
                <?php /* Invoice Details: End */ ?>

                <?php /* Invoice Items: Start */ ?>
                <div class="invoiceitems">
                    <table class="invoiceitemstable">
                        <thead>
                            <tr class="itemheaderrow">
                                <th class="linecounter">No.</th>
                                <th class="description">Item Description</th>
                                <th class="rate">Rate</th>
                                <th class="quantity">Quantity</th>
                                <th class="price">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="itembodyrow">
                                <td class="linecounter">1</td>
                                <td class="description"><?php echo $plan->user_type_name; ?> Membership Plan</td>
                                <td class="rate">$<?php echo number_format($invoice->amount, 2); ?></td>
                                <td class="quantity">1</td>
                                <td class="price">$<?php echo number_format($invoice->amount, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="clear"></div>
                </div>
                <?php /* Invoice Items: End */ ?>

                <?php /* Invoice Totals: Start */ ?>
                <div class="invoicetotals">
                    <div class="paymentdetails">
                        <h3>Payment Methods</h3>

                        <p>Paypal or Credit Card</p>

                        <div class="clear"></div>
                    </div>

                    <div class="invoicetotaldetails">
                        <div class="invoicetotaldetails_row subtotal">
                            <span class="invoicetotal_label">Sub Total</span>
                            <span class="invoicetotal_data">$<?php echo number_format($invoice->amount, 2); ?></span>
                        </div>
                        <div class="invoicetotaldetails_row discounts">
                            <span class="invoicetotal_label">Discount</span>
                            <span class="invoicetotal_data"></span>
                        </div>
                        <div class="invoicetotaldetails_row total">
                            <span class="invoicetotal_label">Total</span>
                            <span class="invoicetotal_data">$<?php echo number_format($invoice->amount, 2); ?></span>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="sign">

                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Invoice Totals: End */ ?>

                <?php /* Invoice Footer: Start */ ?>
                <div class="invoicefooter">
                    <div class="invoiceftr_col left terms">
                        <h3>Terms:</h3>

                        <p>The amount of this invoice is due and must be paid in full for continued service. Submit a support request for any questions.</p>
                    </div>
                    <div class="invoiceftr_col right thanksyou">
                        <p>Thank you for your Business</p>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Invoice Footer: End */ ?>

                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>

    </body>
</html>