<?php
/*
Plugin Name : User
*/
add_method('satice_menu');
function satice_menu(){
	add_menu_page('User', 'Manage Users', 'user/user.php', 'user', 7,'entypo-user','This module is used to manage users');
	//$page_title, $menu_text, $page_url, $page_slug, $display_order, $icon_url
	add_submenu_page('user', 'Users', 'Users', 'user/user.php', 'existing_user', 7 );
	add_submenu_page('user', 'User Plans', 'User Plans', 'user/user_type.php', 'user_plans', 7 );
	//add_submenu_page('user', 'User Subscriptions Info', 'User Subscriptions Info', 'user/user_subscribers.php', 'user_subscription_info', 7 );
	//$parent_page_slug, $page_title, $menu_text, $page_url, $page_slug, $display_order
}
?>