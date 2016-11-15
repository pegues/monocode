<form method="post">
    <div class="scpayprocessing">
        <div class="scpayrow downgrade">
            <h1>Downgrade Plan</h1>
			
            <p>You are downgrading your plan <strong><?php echo $current_plan->user_type_name; ?></strong> to <strong><?php echo $entity->user_type_name; ?></strong>.</p>
			
			<h3>Workspaces</h3>
			
            <p>In your new plan, you will have only <strong><?php echo isset($entity->feature) && $entity->feature ? $entity->feature->work_space : 0; ?></strong> workspaces, so please choose the workspace(s) you'd like to keep from your existing <?php echo isset($current_plan->feature) && $current_plan->feature ? $current_plan->feature->work_space : 0; ?> workspace(s).</p>
			
            <div class="project_files">
                <?php
                if (isset($workspaces) && count($workspaces) > 0) {
                    $ws_limit = isset($entity->feature) && $entity->feature ? $entity->feature->work_space : 0;
                    $i = 0;
                    foreach ($workspaces as $key => $ws) {
                        $i++;
                        $sel = ($i <= $ws_limit) ? 'checked="checked"' : 'disabled="disabled"';
                        ?>
						<div class="">
							<label for="ws<?php echo $key; ?>">
								<input type="checkbox" 
									name="workspaces[]" 
									class="workspace" 
									<?php echo $sel; ?> 
									id="ws<?php echo $key; ?>" 
									value="<?php echo $key; ?>" />
								<?php echo $ws['ws_name']; ?>
							</label>
							
							<div class="clear"></div>
						</div>
                        <?php
                    }
                }
                ?>
				
                <div class="clear"></div>
            </div>
			
			<h3>Databases</h3>
			
			<?php if(isset($databases) && count($databases) > 0){ ?>
				<p>In your new plan, you will have only <strong><?php echo isset($entity->feature) && $entity->feature ? $entity->feature->database : 0; ?></strong> databases, so please choose the database(s) you'd like to keep from your existing <?php echo isset($current_plan->feature) && $current_plan->feature ? $current_plan->feature->database : 0; ?> database(s).</p>
				
				<div class="project_files">
					<?php
					if (isset($databases) && count($databases) > 0) {
						$db_limit = isset($entity->feature) && $entity->feature ? $entity->feature->database : 0;
						$i = 0;
						foreach ($databases as $db) {
							$i++;
							$sel = ($i <= $db_limit) ? 'checked="checked"' : 'disabled="disabled"';
							?>
							<div class="">
								<label for="db<?php echo $db; ?>">
									<input type="checkbox" 
										name="databases[]" 
										class="database" 
										<?php echo $sel; ?> 
										id="db<?php echo $db; ?>" 
										value="<?php echo $db; ?>" />
									<?php echo $db; ?>
								</label>
								
								<div class="clear"></div>
							</div>
							<?php
						}
					}
					?>
					
					<div class="clear"></div>
				</div>
			<?php } else { ?>
				<p>The plan you're downgrading to doesn't allow any databases. Make sure you have all your databases backed up. Once you complete your downgrade, all databases will be deleted.</p>
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
			
            <div class="buttonrow">
                <button class="button submit danger" type="submit" <?php echo $connected ? '' : 'disabled="disabled"' ?>>
					<span>Downgrade Now</span>
				</button>
            </div>
			
            <div class="clear"></div>
        </div>
		
		<div class="clear"></div>
    </div>
</form>

<script>
    $(function () {
        $(".workspace").change(function () {
            var availableCount = <?php echo isset($entity->feature) && $entity->feature ? $entity->feature->work_space : 0; ?>;
            var checkedCount = $(".workspace:checked").length;
            if (checkedCount >= availableCount) {
                $(".workspace:not(:checked)").attr("disabled", true);
            } else {
                $(".workspace").attr("disabled", false);
            }
        });
		
        $(".database").change(function () {
            var availableCount = <?php echo isset($entity->feature) && $entity->feature ? $entity->feature->work_space : 0; ?>;
            var checkedCount = $(".database:checked").length;
            if (checkedCount >= availableCount) {
                $(".database:not(:checked)").attr("disabled", true);
            } else {
                $(".database").attr("disabled", false);
            }
        });
    });
</script>