<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function (e) {
        var data = {id: "<?php echo $id; ?>", name: "<?php echo $name; ?>", path: "<?php echo $path; ?>"};
        parent.sceditor.call("base.file.openFromLocal()", data);
        setTimeout(function () {
            parent.sceditor.call("base.closePopup()", {}, POPUP_ID);
        }, 0);
    });
</script>