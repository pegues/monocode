<?php
/*
Plugin Name : Feature

*/
add_method('feature_menu');
function feature_menu(){
	add_menu_page('Feature', 'Manage Packages', 'feature/feature.php', 'feature', 7,'entypo-book','This is a module to manage features');
}

?>