<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Terminal Access</title>
	
	<!-- Include gateone.js somewhere on your page -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>core/css/gateone.css" type="text/css" media="screen" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/gateone.js"></script>
	
	<!-- Call GateOne.init() at some point after the page is done loading -->
	<script>
		window.onload = function () {
			// Initialize Gate One:
			//GateOne.prefs.showToolbar = false;
			GateOne.prefs.autoConnectURL = 'ssh://root@<?php echo $ip; ?>:22/?identities=id_rsa.txt';
			GateOne.init({url: 'https://sandbox01.monocode.io/', auth: <?php echo $auth; ?>});
			GateOne.Utils.removeElement('#go_controlsContainer');
		}
	</script>
</head>
<body>
	
	<div id="gateone_container" style="position: relative; width: 100%; height: 100%;">
		<div id="gateone"></div>
	</div>
	
</body>
</html>