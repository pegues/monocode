<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//
include("../../system/options.php");
$root = '';
//echo $root . $_POST['dir'];
//echo "E:/web/htdocs/codeeditor/trunk/workspace/";
$_POST['dir']=rawurldecode($_POST['dir']);//rawurldecode($_POST['dir']);

if( file_exists($root.$_POST['dir']) ) {
	$files 		= scandir($root . $_POST['dir']);
	natcasesort($files);
	$dirindex	= 0;
	$fileindex	= 0;
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
		
		// All Directories
		foreach( $files as $file ) {
			$dirindex++;
			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) ) {
				echo "<li class=\"directory collapsed\"><a href=\"#\" data-dir='".$dirindex."' data-file-id='".code(htmlentities($_POST['dir'] . $file))."' rel=\"" . rawurlencode($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
			}
		}
		
		// All Files
		foreach( $files as $file ) {
			$fileindex++;
			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) ) {
				$ext = preg_replace('/^.*\./', '', $file);

				echo "<li class=\"file ext_$ext\"><a href=\"#\" data-url='".$root.$_POST['dir']. $file."' data-title='".htmlentities($file)."' data-file-id='".code(htmlentities($_POST['dir'] . $file)).'-'.(isImage($root.$_POST['dir']. $file)?'static':'editor')."' rel=\"" . rawurlencode($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
			}
		}
		echo "</ul>";
	}
}

?>