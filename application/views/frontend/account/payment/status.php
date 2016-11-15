<link href="<?php echo base_url(); ?>core/alert/css/alert.min.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>core/alert/js/alert.min.js"></script>
<link href="<?php echo base_url(); ?>core/alert/themes/<?php echo $options['editor_page_theme']; ?>/theme.css" rel="stylesheet" />

<?php if ($account->user_type == FREE_PLAN_ID) { ?>
    <h4>Free Plan</h4>
	
    <p>You're currently using the free plan. Please <a href="<?php echo base_url(); ?>membership">click here</a> to upgrade.</p>
<?php } else { ?>
    <h4>Your plan: <?php echo $plan->user_type_name; ?></h4>
    
	<?php if ($account->trial_taken == TRIAL_BEING_TAKEN) { ?>
		<p><strong>Trial Period</strong>: Yes<br/>
			<strong>Trial Start Date</strong>: <?php echo $account->trial_start_date; ?><br/>
			<strong>Trial End Date</strong>: <?php echo $account->trial_end_date; ?></p>
		
		<div class="buttoncontainer">
			<a onclick="return __confirm(this, 'Are you sure?');" class="button red buttonleft" href="<?php echo $base_url; ?>cancel/trial">Cancel Trial</a>
			
			<div class="clear"></div>
		</div>
		
    <?php } else { ?>
        <p><strong>Last paid date</strong>: <?php echo $subscription->last_paid_date; ?><br/>
			<strong>Next billing date</strong>: <?php echo $subscription->next_billing_date; ?><br/>
			<strong>Days to next billing</strong>: <?php echo abs($expiredDays); ?> day<?php echo abs($expiredDays) > 1 ? 's' : ''; ?> <?php echo ($expiredDays > 0) ? 'past due' : 'left'; ?></p>
		
        <div class="buttoncontainer">
            <a class="button buttonleft" href="<?php echo $base_url; ?>paymentmethod">Payment Method</a>
            <a class="button buttonleft" href="<?php echo $base_url; ?>invoices">View Invoices</a>
			
			<div class="clear"></div>
        </div>
        <?php
    }
}
?>
<script type="text/javascript">
    function __confirm(obj, msg) {
        var url = obj.href;
        $.alert.open({
            type: 'confirm', content: 'Are you sure you want to continue?', cancel: true, callback: function (e) {
                if (e == 'yes') {
                    location.href = url;
                }
            }
        });
        return false;
    }
</script>