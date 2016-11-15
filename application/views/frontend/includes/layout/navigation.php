<?php /* Site Navigation: Start */ ?>
<div class="sitenav">
    <?php if (isset($account) && $account) { ?>
	<ul id="authstatuslist" class="authstatuslist loggedin">
		<?php /*
		<li class="parent first odd loggedin">
			<span>Logged in as <?php echo $this->session->userdata('user_name'); ?></span>
		</li>
		<li class="parent even openeditor">
			<a href="<?php base_url(); ?>editor"><i class="fa fa-code"></i><span>Open Editor</span></a>
		</li>
		*/ ?>
		<li class="parent first last odd myaccount">
			<a href="<?php base_url(); ?>account">
				<i class="fa fa-gear"></i>
				<span>My Account</span>
			</a>
			
			<ul class="myaccountsubnav">
				<li class="userinfo">
					<div class="userinfo_inside">
						<h3>
							<strong>Current Plan</strong>: 
							<?php
							$plan = get_user_plan();
							echo $plan[0]->user_type_name;
							
							if ($account->trial_taken == TRIAL_BEING_TAKEN) {
								echo ' Trial';
							}
							?>
						</h3>
						
						<?php if ($account->trial_taken == TRIAL_BEING_TAKEN) { ?>
						<p class="trialinfo">Your trial ends on <?php echo $account->trial_end_date; ?></p>
						<?php } ?>
						
						<p>To view the list of features available to you with your current plan, <a href="<?php base_url(); ?>membership">click here</a>.</p>
						
						<p>To upgrade or downgrade your plan, <a href="<?php base_url(); ?>membership">click here</a>.</p>
						
						<div class="clear"></div>
					</div>
				</li>
				
				<li class="sep"><span></span></li>
				
				<li class="first odd link myaccount">
					<a href="<?php base_url(); ?>account/profile" class="navlink">
						<i class="fa fa-user"></i>
						<span>Profile</span>
					</a>
				</li>
				<li class="even link notifications">
					<a href="<?php base_url(); ?>account/notifications" class="navlink">
						<i class="fa fa-tag"></i>
						<span>Notifications</span>
					</a>
				</li>
				<li class="odd link viewmembership">
					<a href="<?php base_url(); ?>account/membership" class="navlink">
						<i class="fa fa-tasks"></i>
						<span>View Membership Details</span>
					</a>
				</li>
				<li class="even link settings">
					<a href="<?php base_url(); ?>account/editorSettings" class="navlink">
						<i class="fa fa-gears"></i>
						<span>Editor Settings</span>
					</a>
				</li>
				<li class="odd link upgradeaccount">
					<a href="<?php base_url(); ?>membership" class="navlink">
						<i class="fa fa-plug"></i>
						<span>Upgrade Account</span>
					</a>
				</li>
				<li class="even link cancelaccount">
					<a href="<?php base_url(); ?>account/cancel" class="navlink">
						<i class="fa fa-remove"></i>
						<span>Cancel Account</span>
					</a>
				</li>
				
				<li class="sep"><span></span></li>
				
				<li class="odd link fileexplorer">
					<a href="<?php base_url(); ?>account/files" class="navlink">
						<i class="fa fa-hdd-o"></i>
						<span>File Explorer</span>
					</a>
				</li>
				<li class="even link openeditor">
					<a href="<?php base_url(); ?>editor" class="navlink">
						<i class="fa fa-code"></i>
						<span>Open Editor</span>
					</a>
				</li>
				
				<li class="sep"><span></span></li>
				
				<li class="last odd link logout">
					<a href="<?php base_url(); ?>guest/logout" class="navlink">
						<i class="fa fa-power-off"></i>
						<span>Logout</span>
					</a>
				</li>
			</ul>
		</li>
	</ul>
    <?php } else { ?>
	<ul id="authstatuslist" class="authstatuslist loggedout">
		<li class="parent first myaccount access">
			<a href="<?php base_url(); ?>guest/login">
				<i class="fa fa-gear"></i>
				<span>Access</span>
			</a>
			
			<ul class="myaccountsubnav">
				<li class="userinfo">
					<div class="userinfo_inside">
						<h3>Create a Free Account</h3>
						
						<p>Creating a free account takes just a few moments. Register your account and get started. If you're interested in a particular plan, <a href="<?php base_url(); ?>membership">click here</a>.</p>
						
						<div class="clear"></div>
					</div>
				</li>
				<li class="odd first link login">
					<a href="<?php base_url(); ?>guest/login" class="link"><i class="fa fa-sign-in"></i><span>Login</span></a>
				</li>
				<li class="even last link register">
					<a href="<?php base_url(); ?>guest/register" class="link"><i class="fa fa-random"></i><span>Register</span></a>
				</li>
			</ul>
		</li>
	</ul>
    <?php } ?>
	
    <?php
    function get_child_pages($pages, $link = null) {
        $child_pages = array();
        foreach ($pages as $page) {
            if ($page->parent_link != $link) {
                continue;
            }
            $child_pages[] = $page;
        }
		
        return $child_pages;
    }
	
    function draw_sub_navigation($pages, $child_pages, $current_page) {
        foreach ($child_pages as $page) {
            echo '<li' . ($current_page && $page->link == $current_page->link ? ' class="active"' : '') . '><a href="' . $page->link . '">' . $page->title . '</a>';
            $list = get_child_pages($pages, $page->link);
			
            if (count($list) > 0) {
                echo '<ul>';
                draw_sub_navigation($pages, $list, $current_page);
                echo '</ul>';
            }
			
            echo '</li>';
        }
    }
    ?>
	
	<?php /* Non-responsive Navigation: Start */ ?>
    <ul class="mainmenu" id="main-menu">
        <?php draw_sub_navigation($pages, get_child_pages($pages), isset($current_page) ? $current_page : null); ?>
    </ul>
	<?php /* Non-responsive Navigation: End */ ?>
	
    <div class="clear"></div>
</div>
<?php /* Site Navigation: End */ ?>

<?php /* Responsive Nav: Start */ ?>
<div class="responsivenav">
	<div class="responsivenav_handle">
		<div class="responsivenavhandle_inner">
			<span><i class="fa fa-bars"></i></span>
		</div>
	</div>

	<ul class="mainmenu_responsive" id="main-menu_responsive">
		<?php draw_sub_navigation($pages, get_child_pages($pages), isset($current_page) ? $current_page : null); ?>
	</ul>

	<div class="clear"></div>
</div>
<?php /* Responsive Nav: End */ ?>
