
<?php /* Footer Promo: Start * / ?>
<?php if($this->session->userdata('loggedin')=='true'){ ?>
<div class="">
	<div class="">
		<p>User logged in</p>
		
		<p><strong>Current Plan</strong>: 
			<?php
				$plan = get_user_plan();
				echo $plan[0]->user_type_name;
			?></p>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
<?php }else{ ?>
<div class="">
	<div class="">
		<p>User logged out</p>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
<?php } ?>
<?php / * Footer Promo: End */ ?>
