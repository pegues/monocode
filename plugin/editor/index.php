<?php
/*
Plugin Name : Editor

*/
add_method('editorAction');
function editorAction(){
	add_menu_page('Editor', 'Editor Modules', 'editor/editor.php', 'editor', 2,'entypo-layout','This Editor modules helps to manage editor of front-end');
	//$page_title, $menu_text, $page_url, $page_slug, $display_order, $icon_url
	
	//add_submenu_page('editor', 'Existing Modules', 'Existing Modules', 'editor/editor.php', 'existioneditor', 1,'entypo-list' );
	//add_submenu_page('editor', 'Editor Config', 'Editor Config', 'editor/editor-config.php', 'editorconfig', 2,'entypo-globe');
	//$parent_page_slug, $page_title, $menu_text, $page_url, $page_slug, $display_order
}

?>