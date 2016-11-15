<?php
function get_sub_editor_menus($parent=0,$class=''){
	$m		= get_editor_menus($parent);
	$list	= '';
	
	if(sizeof($m)>0){
		$list='<ul class="' . $class . '">';
		
		foreach($m as $vals){
			$actions=($vals->data_action != '') ? $vals->data_action : '';
			$dataAction=($vals->data_action == '' || $vals->data_action == '#') ? '' : 'data-action-type="' . $vals->action_type . '" data-action="' . $vals->data_action . '"';
			$resnts='';
			
			if(($vals->action_type == 'callphp' && $actions != '')){
				if(function_exists($actions)){
					$resnts=$actions();
				}
			} else if(($vals->action_type == 'command' && $actions != '')){
				if ($commands = get_option('commands')) {
					$commands = json_decode($commands);
					
					foreach ($commands as $command) {
						if ($command->name == $actions) {
							$vals->shortcut_key = $command->shortcut_key;
							$vals->shortcut_key_mac = $command->shortcut_key_mac;
						}
					}
				}
			}
			
			if($resnts == ''){
				$subclass = str_replace(' ', '', strtolower($vals->name));
				$resnts=get_sub_editor_menus($vals->editor_id,'sitenavdropdown dropdown' . $subclass);
			}
			
			$sub_class=($resnts != '' && $resnts != ' ')?' class="subnav" ' : '';
			$list.='<li class="child first">';
				$list.='<a href="#" ' . $dataAction . ' ' . $sub_class . ' data-title="' . $vals->name . '">';
					$list.='<span class="label_w_key">' . $vals->name . '</span>';
					$list.='<span class="label_key shortcut-key-win">' . $vals->shortcut_key . '</span>';
					$list.='<span class="label_key shortcut-key-mac">' . $vals->shortcut_key_mac . '</span>';
					$list.='<span class="clear"></span>';
				$list.='</a>';
				$list.=$resnts;
			$list.='</li>';
			
			if($vals->nav_sep == 'Y'){
				$list.='<li class="navsep"><span></span></li>';	
			}
		}
		
		$list.='</ul>';
	}
	return $list;
}
?>
<ul class="sitenavigation">
	
	<?php /* File: Start */ ?>
	
	<?php
	$menus=get_editor_menus('0');
	
	//print_r($menus); die();
	
	foreach ($menus as $val){
		$dataAction=($val->data_action == '' || $val->data_action == '#') ? '' : 'data-action-type="' . $val->action_type . '" data-action="' . $val->data_action . '"';
	?>
	<li class="parent first">
		<a href="javascript:void(0);" <?php echo $dataAction; ?> class="parent link" data-title="<?php echo $val->name; ?>">
			<span><?php echo $val->name; ?></span>
		</a>
		<?php echo get_sub_editor_menus($val->editor_id,'sitenavdropdown primary dropdown'.strtolower($val->name)); ?>
	</li>
	<?php if($val->nav_sep=='Y'){ ?>
	<li class="navsep"><span></span></li>
	<?php
		}
	}
	?>
</ul>

<?php /* Username and My Account: Start */ ?>
<ul class="editoruseracclist">
	<li class="parent username"><span class="topitem">Logged in as <?php echo $this->session->userdata('user_name'); ?></span></li>
	<li class="parent myaccount"><a href="javascript:void(0);" class="topitem">My Account<i class="fa fa-bars"></i></a>
		<ul class="editoruseraccdropdown">
			<li class="first">
				<div class="usrmyaccinfo">
					<h3>
    					<strong>Current Plan</strong>: 
    					<?php
    						$plan=get_user_plan();
    						echo $plan[0]->user_type_name;
							if ($account->trial_taken == TRIAL_BEING_TAKEN) {
								echo ' Trial';
							}
    					?>
					</h3>
					
					<?php if ($account->trial_taken == TRIAL_BEING_TAKEN) { ?>
					<p class="trialinfo">Your trial ends on <?php echo $account->trial_end_date; ?></p>
					<?php } ?>
					
					<p>To view the list of features available to you with your current plan, <a href="membership">click here</a>.</p>
					
					<p>To upgrade or downgrade your plan, <a href="membership">click here</a>.</p>
					
					<div class="clear"></div>
				</div>
			</li>
			<li class="navsep"><span></span></li>
			<li class="child link"><a href="<?php echo base_url(); ?>"><i class="fa fa-sign-out"></i>Exit</a></li>
			<li class="navsep"><span></span></li>
			<li class="child link last"><a href="<?php base_url(); ?>guest/logout"><i class="fa fa-power-off"></i>Logout</a></li>
		</ul>
	</li>
</ul>
<?php /* Username and My Account: End */ ?>
