<form method="post">
    <div class="scpayprocessing">
        <div class="scpayrow downgrade">
            <h1>Upgrade Plan</h1>
			
            <p>You are upgrading your plan <strong><?php echo $current_plan->user_type_name; ?></strong> to <strong><?php echo $entity->user_type_name; ?></strong>.</p>

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
                <li class="odd last"><span>And more...</span></li>
            </ul>

            <div class="buttonrow">
                <button class="button submit" type="submit" <?php echo $connected ? '' : 'disabled="disabled"' ?>>
					<span>Upgrade Now</span>
				</button>
                <?php if ($settings->trial_period && !$account->bt_used) { ?>
                    <button class="button submit red" name="trial" value="1" type="submit">
						<span>Start Trial</span>
					</button>
                <?php } ?>
            </div>

            <div class="clear"></div>
        </div>
		
		<div class="clear"></div>
    </div>
</form>
