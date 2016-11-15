<input type="hidden" name="widgets[]" />
<?php
$e = isset($options['widgets']) ? $options['widgets'] : null;
if ($e != null && $e != '') {
    $selected_ids = json_decode($e);
} else {
    $selected_ids = array();
}
if (sizeof($widgetInfo->types) > 0) {
    foreach ($widgetInfo->types as $type) {
        ?>
        <div class="infopopupsubtitle widget-type">
            <span><?php echo $type->widget_type_name; ?></span>
        </div>

        <ul class="editorwidgetlist">
            <?php
            if (sizeof($widgetInfo->list) > 0) {
                foreach ($widgetInfo->list as $widget) {
                    if ($widget->type_id != $type->widget_type_id) {
                        continue;
                    }
                    ?>
                    <li class="widgetitem <?php echo (!$widgetInfo->allowed || !in_array($widget->widget_id, $widgetInfo->availableList) ? 'disabled' : ''); ?>" data-widget='<?php echo json_encode($widget); ?>' data-widget-id="<?php echo $widget->widget_id; ?>">
                        <div class="widgetitem_inside">
                            <div class="clear"></div>
							
                            <div class="widgetitemimage">
								<img src="<?php echo base_url($widget->thumbnail_url); ?>" alt="" class="" />
							</div>

                            <div class="widgetitem_info">
                                <div class="widgettitle">
                                    <div><?php echo $widget->title . ' ' . $widget->version; ?></div>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="widgetenable">
                                    <div><span>Enable:</span> <input type="checkbox" name="widgets[]" value="<?php echo $widget->widget_id; ?>" <?php echo (in_array($widget->widget_id, $selected_ids) ? 'checked=""' : ''); ?> /></div>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
							
                            <div class="clear"></div>
							
                            <div class="widgetitemdisabled">
                                <i class="fa fa-lock fa-3x"></i>
                            </div>
							
                            <div class="widgetitemdisabled-info">
                                <div>
                                    Please upgrade your plan if you want to use this widget.<br />
                                    <a target="parent" href='<?php echo base_url(); ?>plans'>Upgrade</a>
                                </div>
                            </div>
							
                            <div class="widgetitemdesc_click">Click for Info</div>
							
                            <div class="widgetitemdesc_info">
                                <strong><?php echo $widget->title . ' ' . $widget->version; ?></strong><br/>
                                <?php echo $widget->description; ?>
								
                                <div class="clear"></div>
                            </div>
							
							<div class="clear"></div>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>

        <div class="clear"></div>
        <?php
    }
}
?>

<script>
    $(function() {
        $(".widgetenable input:checkbox").change(function(e) {
            if (this.checked) {
                var widget = $(this).closest('.widgetitem').data('widget');
                parent.sceditor.call('base.widget.enable()', widget);
            } else {
                parent.sceditor.call('base.widget.disable(' + this.value + ')');
            }
        });
    });
</script>
