<link href="<?php echo base_url(); ?>core/alert/css/alert.min.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>core/alert/js/alert.min.js"></script>
<link href="<?php echo base_url(); ?>core/alert/themes/<?php echo $options['editor_page_theme']; ?>/theme.css" rel="stylesheet" />

<p>If you'd like to cancel your account, please click the cancel button below. Before proceeding, please make sure to create a backup of all your files. By proceeding with cancelling your account, your account and all account content will be deleted. Cancelling your account is permanent and cannot be reversed.</p>

<?php /* Button: Start */ ?>
<div class="buttoncontainer">
    <a onclick="return __confirm(this, 'Are you sure?');" class="button red buttonleft" href="<?php echo $base_url; ?>cancel/account">Cancel Account</a>
	
    <div class="clear"></div>
</div>
<?php /* Button: End */ ?>

<script type="text/javascript">
    function __confirm(obj, msg) {
        var url = obj.href;
        $.alert.open({
            type: 'confirm', content: 'Are you sure you to continue?', cancel: true, callback: function (e) {
                if (e == 'yes') {
                    location.href = url;
                }
            }
        });
        return false;
    }
</script>