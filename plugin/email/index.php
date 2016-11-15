<?php
/*
Plugin Name : Email

*/
add_method('email_menu');
function email_menu(){
	add_menu_page('Email', 'Manage Emails', 'email/email.php', 'email', 7,'entypo-book','This is a module to manage emails');
	//$page_title, $menu_text, $page_url, $page_slug, $display_order, $icon_url
	add_submenu_page('email', 'Existing Emails', 'Existing Emails', 'email/email.php', 'existing_email', 7 );
	add_submenu_page('email', 'Existing Email Groups', 'Existing Email Groups', 'email/email_group.php', 'email_group', 7 );
	add_submenu_page('email', 'Existing Email Templates', 'Existing Email Templates', 'email/email_template.php', 'email_template', 7 );
	
	//$parent_page_slug, $page_title, $menu_text, $page_url, $page_slug, $display_order
}

?>