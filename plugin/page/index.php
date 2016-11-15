<?php
/*
Plugin Name : Page
*/
add_method('pages_menu');
function pages_menu(){
	add_menu_page('Pages', 'Manage Pages', 'page/page.php', 'page', 7 ,'entypo-book','This CMS(page) module helps to manage pages for front-end');
    add_submenu_page('page', 'Pages', 'Pages', 'page/page.php', 'pages', 1 );
    add_submenu_page('Page', 'Page Position', 'Page Position', 'page/page_position.php', 'Menu_position', 2 );
}
?>