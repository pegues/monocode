<?php
/*
Plugin Name : Transaction Details
*/
add_method('transaction_menu');
function transaction_menu(){
	add_menu_page('Transaction Details', 'Transaction Details', 'transaction/transaction.php', 'transaction', 7,'entypo-user','This module is used to see Transaction Details');
}
?>