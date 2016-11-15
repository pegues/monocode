<?php
global $CI;

if(!defined('prefix')){
	if(isset($CI)){
		$CI =& get_instance();
		$CI->load->database();
		define('prefix',$CI->db->dbprefix);
	}
}

if(!defined('encryption_key')){
	global $config;
	define('encryption_key',$config['encryption_key']);
}

function getObjectValue($e,$f){
	if(isset($e[0]) && isset($e[0]->$f)){
		return $e[0]->$f;
	}
}

function add_option($key='',$val='',$user_id=''){
	$uid=($user_id=='') ? get_user_id() : $user_id;
	
	if($key != '' && $val != ''){
		$res=query("select option_value from ".prefix."options where option_key='".$key."' AND user_id='".$uid."'");
		
		if(sizeof($res)==0){
			$data=query("insert into ".prefix."options (option_key,option_value,user_id) values('".$key."','".$val."','".$uid."')");
		}
	}
}

function get_user_plan(){
	return $data=query("select* from ".prefix."user_type  where user_type_id='".get_user_type_id()."'");
}

function get_active_workspace(){
	$_ws=get_option('ws');
	$_ws=json_decode($_ws,true);
	
	if(is_array($_ws) && sizeof($_ws)>0){
		//print_r($_ws); die();
		$ws='';
		
		foreach($_ws as $key=>$vl){
			if(isset($vl['ws_active']) && $vl['ws_active']=='true'){
			$ws=$vl['ws_directory'];
			break;
			}
		}
		
		if($ws==''){
			$keys=current(array_keys($_ws));
			$_ws[$keys]['ws_active']='true';
			update_option(json_encode($_ws),true);
			//print_r($_ws); // die();
			
			return $keys;
		}else{
			return $ws;
		}
	}
}

function get_user_feature($e,$uid=''){
	$user_id='';
	
	if($uid==''){
		$user_id=get_user_id();
	}else{
		$user_id=$uid;
	}
	
	$data=query("select f.".$e." from 
	".prefix."features f,
	".prefix."user_type ut,
	".prefix."user u
	 where 
	 u.user_id='".$user_id."' AND 
	 ut.feature_id=f.feature_id
	 ");
	$v='';
	
	if(isset($data[0]) && isset($data[0]->$e)){
		return $data[0]->$e;
	}
}

function get_option($key='',$get='true'){
	if($key!=''){
		$data=query("select option_value from ".prefix."options where option_key='".$key."' AND user_id='".get_user_id()."'");
		$v='';
		
		if(isset($data[0]) && isset($data[0]->option_value)){
			$v=$data[0]->option_value;
		}
		
		if($get==true && $v==''){
			$data=query("select option_value from ".prefix."options where option_key='".$key."' AND user_id='0'");
			if(isset($data[0]) && isset($data[0]->option_value)){
				$v=$data[0]->option_value;
			}
		}
		
		return $v;
	}
}

function delete_option($key=''){
	if($key!=''){
		$data=query("delete from ".prefix."options where option_key='".$key."' AND user_id='".get_user_id()."'");
	}
}

function update_option($key='',$val=''){
	if($key!='' && $val!=''){
		$res=query("select option_value from ".prefix."options where option_key='".$key."' and user_id='".get_user_id()."'");
		if(sizeof($res)==0){
			$data=query("insert into ".prefix."options (option_key,option_value,user_id) values('".$key."','".$val."','".get_user_id()."')");
		}else{
			$data=query("update ".prefix."options set option_value='".$val."' where option_key='".$key."' and user_id='".get_user_id()."'");
		}
	}
}

// User ID
function get_user_id(){
	global $CI;
	
	if(isset($CI)){
		$CI =& get_instance();
		
		if($CI->session->userdata("user_id") != ''){
			return $CI->session->userdata("user_id");
		}else{
			return '0';
		}
	}else{
		return '0';
	}
}

// User Type ID
function get_user_type_id(){
	global $CI;
	
	if(isset($CI)){
		$CI =& get_instance();
		
		if($CI->session->userdata("user_type") != ''){
			return $CI->session->userdata("user_type");
		}else{
			return '2';
		}
	}else{
		return '2';
	}
}

function query($sql){
	global $CI;
	
	if(isset($CI)){
		$CI =& get_instance();
        $CI->load->database();
		$query = $CI->db->query($sql);
		
		if((strpos($sql,'select') !== false) || (strpos($sql,'select*') !== false)) {
			return $query->result();
		}
	}else{
		global $model;
		
		if($model){
			return $model->query($sql);
		}
	}
}

// CMS Redirect Login: Redirect User to Specified Page
function get_login_redirect_location_array(){
	return $pageredirectlists = array(
		"aboutus"			=> "About Us",
		"cancelaccount"		=> "Cancel Account",
		"contactus"			=> "Contact Us",
		"editor"			=> "Editor",
		"homepage"			=> "Homepage",
		"marketing"			=> "Marketing",
		"notifications"		=> "Notifications",
		"ourservices"		=> "Our Services",
		"plans"				=> "Plans",
		"profile"			=> "Profile",
		"settings"			=> "Settings",
		"upgradeaccount"	=> "Upgrade Account",
		"viewmembership"	=> "View Membership",
		"webdesign"			=> "Web Design",
		"webdevelopment"	=> "Web Development"
	);
}

function get_login_redirect_location($e) {
	$pageredirectlists = get_login_redirect_location_array();
	echo '<select id="login_page_redirect" data-key="login_page_redirect" data-value="' . $e . '" name="login_page_redirect" class="select form-control">';
		foreach($pageredirectlists as $key=>$pageredirectlist){
			$sel=($key == $e) ? 'selected="selected"' : '';
			echo '<option ' . $sel . ' value="' . $key . '" class="child">' . $pageredirectlist . '</option>';
		}
	echo '</select>';
}

// CMS Redirect Logout: Redirect User to Specified Page
function get_logout_redirect_location_array(){
	return $pageredirectlists = array(
		"aboutus"			=> "About Us",
		"contactus"			=> "Contact Us",
		"homepage"			=> "Homepage",
		"login"				=> "Login",
		"marketing"			=> "Marketing",
		"ourservices"		=> "Our Services",
		"plans"				=> "Plans",
		"register"			=> "Register",
		"webdesign"			=> "Web Design",
		"webdevelopment"	=> "Web Development"
	);
}

function get_logout_redirect_location($e) {
	$pageredirectlists = get_logout_redirect_location_array();
	echo '<select id="logout_page_redirect" data-key="logout_page_redirect" data-value="' . $e . '" name="logout_page_redirect" class="select form-control">';
		foreach($pageredirectlists as $key=>$pageredirectlist){
			$sel=($key == $e) ? 'selected="selected"' : '';
			echo '<option ' . $sel . ' value="' . $key . '" class="child">' . $pageredirectlist . '</option>';
		}
	echo '</select>';
}

// Editor: Editor Theme Array
function get_theme_array(){
	return $themes = array(
		"ambiance" 					=> "Ambiance",
		"chaos"						=> "Chaos",
		"clouds"					=> "Clouds",
		"clouds_midnight"			=> "Clouds Midnight",
		"cobalt"					=> "Cobalt",
		"eclipse"					=> "Eclipse",
		"idle_fingers"				=> "Idle fingers",
		"kr_theme"					=> "krTheme",
		"merbivore"					=> "Merbivore",
		"merbivore_soft"			=> "Merbivore Soft",
		"mono_industrial"			=> "Mono Industrial",
		"monokai"					=> "Monokai",
		"pastel_on_dark"			=> "Pastel on Dark",
		"runnable_dark"				=> "Runnable Dark",
		"runnable_light"			=> "Runnable Light",
		"solarized_dark"			=> "Solarized Dark",
		"terminal"					=> "Terminal",
		"tomorrow_night"			=> "Tomorrow Night",
		"tomorrow_night_blue"		=> "Tomorrow Night Blue",
		"tomorrow_night_bright"		=> "Tomorrow Night Bright",
		"tomorrow_night_eighties" 	=> "Tomorrow Night 80s",
		"twilight"					=> "Twilight",
		"vibrant_ink"				=> "Vibrant Ink",
		"visualstudio"				=> "Visual Studio"
	);
}

// Editor: Syntax Array
function get_syntax_array(){
	return $syntaxlists = array(
		"csharp"		=> "C#",
		"css"			=> "CSS",
		"haml"			=> "HAML",
		"haxe"			=> "haXe",
		"html"			=> "HTML",
		"less"			=> "LESS",
		"liquid"		=> "Liquid",
		"java"			=> "Java",
		"javascript"	=> "JavaScript",
		"json"			=> "JSON",
		"ocaml"			=> "OCaml",
		"perl"			=> "Perl",
		"php"			=> "PHP",
		"python"		=> "Python",
		"ruby"			=> "Ruby",
		"Scala"			=> "Scala",
		"sass"			=> "SASS",
		"scss"			=> "SCSS",
		"svg"			=> "SVG",
		"textile"		=> "Textile",
		"xml"			=> "XML"
	);
}

// Editor: Editor Theme List
function get_editor_theme_list() {
	$themes=get_theme_array();
	$lists='<ul class="sitenavdropdown dropdownthemes">';
		
		foreach($themes as $theme){
			if($theme == 'Ambiance'){
            $lists.='<li class="child first"><a href="#" data-action-type="calljs" data-action="changeTheme' . str_replace(" ","",$theme) . '" ><span class="label_wo_key">' . $theme . '</span></a></li>';
			}elseif($theme == 'Vibrant Ink'){
            $lists.='<li class="child last"><a href="#" data-action-type="calljs" data-action="changeTheme' . str_replace(" ","",$theme) . '"><span class="label_wo_key">' . $theme . '</span></a></li>';
			}else{
            $lists.='<li class="child"><a href="#" data-action-type="calljs" data-action="changeTheme' . str_replace(" ","",$theme) . '"><span class="label_wo_key">' . $theme . '</span></a></li>';
			}
		}
		
	$lists.='</ul>';
	return $lists;
}

// Editor: List of Editor Themes for Configuration Page
function get_editor_theme($e) {
	$themes=get_theme_array();
	echo '<select id="editor_theme" data-key="setTheme" data-value="'.$e.'" name="editor_theme" class="select form-control">';
		
		foreach($themes as $key=>$theme){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'">'. $theme .'</option>';
		}
		
	echo '</select>';
}

// Editor: List of Editor Syntax for Navigation
function get_editor_syntax_list() {
$syntaxlists=get_syntax_array();;
	$list = '<ul class="sitenavdropdown dropdownwrap">';
	$list.= '<li class="child first"><a href="#"><span class="label_wo_key">Auto-Select</span></a></li>';
	$list.= '<li class="child"><a href="#"><span class="label_wo_key">Plain Text</span></a></li>';
	$list.= '<li class="navsep"><span></span></li>';
		
		foreach($syntaxlists as $syntaxlist){
			if($syntaxlist == 'XML'){
            $list.='<li class="child last"><a href="#" data-action-type="calljs" data-action="changeSyntax' . $syntaxlist . '"><span class="label_wo_key">' . $syntaxlist . '</span></a></li>';
			}else{
            $list.='<li class="child"><a href="#" data-action-type="calljs" data-action="changeSyntax' . $syntaxlist . '"><span class="label_wo_key">' . $syntaxlist . '</span></a></li>';
			}
		}
		
	$list.='</ul>';
	
	return $list;
}

// Editor: List of Editor Syntax for Configuration Page
function get_editor_syntax($e) {
	$syntaxlists=get_syntax_array();
	echo '<select id="editor_default_mode" data-key="setMode" data-value="'.$e.'" name="editor_default_mode" class="select form-control">';
		
		foreach($syntaxlists as $key=>$syntaxlist){
			$sel=(strtolower($key)==strtolower($e))?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $syntaxlist .'</option>';
		}
		
	echo '</select>';
}

// Editor: Page Theme Array
function get_page_theme_array(){
	return $pagethemelists = array(
		"dark"			=> "Dark",
		"light"			=> "Light",
		"mystic"		=> "Mystic",
		"smoke"			=> "Smoke"
	);
}

// Editor: Page Theme List
function get_page_theme_list() {
    $lists = '<ul class="sitenavdropdown dropdownthemes">';
    $pagethemelists = get_page_theme_array();

    foreach ($pagethemelists as $key => $pagethemelist) {
        $lists.='<li class="child first"><a href="#" data-action-type="calljs" data-action="pageTheme' . str_replace(" ", "", $pagethemelist) . '"><span class="label_wo_key">' . $pagethemelist . '</span></a></li>';
    }

    $lists.='</ul>';
    return $lists;
}

// Editor: Page Theme
function get_page_theme($e) {
	$pagethemelists = get_page_theme_array();
	echo '<select id="editor_page_theme" data-key="editor_page_theme" data-value="'.$e.'" name="editor_page_theme" class="select form-control">';
		foreach($pagethemelists as $key=>$pagethemelist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $pagethemelist .'</option>';
		}
	echo '</select>';
}

// Editor: Font Size
function get_font_size($e) {
	$fontsizelists = array(
		"8px"		=> "8px",
		"9px"		=> "9px",
		"10px"		=> "10px",
		"11px"		=> "11px",
		"12px"		=> "12px",
		"13px"		=> "13px",
		"14px"		=> "14px",
		"15px"		=> "15px",
		"16px"		=> "16px",
		"17px"		=> "17px",
		"18px"		=> "18px",
		"19px"		=> "19px",
		"20px"		=> "20px",
		"24px"		=> "24px",
		"28px"		=> "28px",
		"32px"		=> "32px"
	);
	echo '<select id="font_size" name="font_size" data-value="'.$e.'" data-key="fontSize" class="select form-control">';
		
		foreach($fontsizelists as $key=>$fontsizelist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $fontsizelist .'</option>';
		}
		
	echo '</select>';
}

// Editor: Page Margin
function get_print_margin($e) {
	$printmarginlists = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="show_print_margin" data-key="setShowPrintMargin" data-value="'.$e.'" name="show_print_margin" class="select form-control">';
		foreach($printmarginlists as $key=>$printmarginlist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $printmarginlist .'</option>';
		}
	echo '</select>';
}

// Editor: Read Only
function get_red_only($e) {
	$readonlylists = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="editor_read_only" data-key="setReadOnly" data-value="'.$e.'" name="editor_read_only" class="select form-control">';
		foreach($readonlylists as $key=>$readonlylist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $readonlylist .'</option>';
		}
	echo '</select>';
}
function get_show_gutter($e) {
	$gutterlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="show_gutter" data-key="editor_show_gutter" data-value="'.$e.'" name="show_gutter" class="select form-control">';
		foreach($gutterlist as $key=>$gutter){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $gutter .'</option>';
		}
	echo '</select>';
}

function get_editor_use_soft_tab($e) {
	$gutterlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="use_soft_tab" data-key="editor_use_soft_tab" data-value="'.$e.'" name="use_soft_tab" class="select form-control">';
		foreach($gutterlist as $key=>$gutter){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $gutter .'</option>';
		}
	echo '</select>';
}

function get_editor_highlight_selected_word($e) {
	$gutterlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="highlight_selected_word" data-key="editor_highlight_selected_word" data-value="'.$e.'" name="highlight_selected_word" class="select form-control">';
		foreach($gutterlist as $key=>$gutter){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $gutter .'</option>';
		}
	echo '</select>';
}

function get_editor_fade_fold_widgets($e) {
        if ($e != "true") {
            $e = "false";
        }
	$gutterlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="fade_fold_widgets" data-key="editor_fade_fold_widgets" data-value="'.$e.'" name="fade_fold_widgets" class="select form-control">';
		foreach($gutterlist as $key=>$gutter){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $gutter .'</option>';
		}
	echo '</select>';
}

function get_editor_scroll_past_end($e) {
	$gutterlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="scroll_past_end" data-key="editor_scroll_past_end" data-value="'.$e.'" name="scroll_past_end" class="select form-control">';
		foreach($gutterlist as $key=>$gutter){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $gutter .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_enable($e) {
    if ($e != "true") {
        $e = "false";
    }
	$js_jshint_enablelist = array(
		"true"		=> "Enable",
		"false"		=> "Disable",
	);
	echo '<select data-key="js_jshint_enable" data-value="'.$e.'" id="js_jshint_enable" name="js_jshint_enable" class="select form-control">';
		foreach($js_jshint_enablelist as $key=>$js_jshint_enable){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_enable .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_esnext($e) {
    if ($e != "true") {
        $e = "false";
    }
	$esnextlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="js_jshint_esnext" name="js_jshint_esnext" class="select form-control">';
		foreach($esnextlist as $key=>$js_jshint_esnext){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_esnext .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_moz($e) {
    if ($e != "true") {
        $e = "false";
    }
	$mozlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="js_jshint_moz" name="js_jshint_moz" class="select form-control">';
		foreach($mozlist as $key=>$js_jshint_moz){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_moz .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_devel($e) {
    if ($e != "true") {
        $e = "false";
    }
	$devellist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="js_jshint_devel" name="js_jshint_devel" class="select form-control">';
		foreach($devellist as $key=>$js_jshint_devel){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_devel .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_browser($e) {
    if ($e != "true") {
        $e = "false";
    }
	$browserlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="js_jshint_browser" name="js_jshint_browser" class="select form-control">';
		foreach($browserlist as $key=>$js_jshint_browser){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_browser .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_node($e) {
    if ($e != "true") {
        $e = "false";
    }
	$nodelist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="js_jshint_node" name="js_jshint_node" class="select form-control">';
		foreach($nodelist as $key=>$js_jshint_node){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_node .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_lastsemic($e) {
    if ($e != "true") {
        $e = "false";
    }
	$lastsemiclist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="js_jshint_lastsemic" name="js_jshint_lastsemic" class="select form-control">';
		foreach($lastsemiclist as $key=>$js_jshint_lastsemic){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_lastsemic .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_maxerr($e) {
    if ($e != "true") {
        $e = "false";
    }
	$maxerrlist = array(
		"10"		=> "10",
		"20"		=> "20",
		"30"		=> "30",
		"40"		=> "40",
		"50"		=> "50",
		"100"		=> "100",
	);
	echo '<select id="js_jshint_maxerr" name="js_jshint_maxerr" class="select form-control">';
		foreach($maxerrlist as $key=>$js_jshint_maxerr){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_maxerr .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_expr($e) {
    if ($e != "true") {
        $e = "false";
    }
	$exprlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="js_jshint_expr" name="js_jshint_expr" class="select form-control">';
		foreach($exprlist as $key=>$js_jshint_expr){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_expr .'</option>';
		}
	echo '</select>';
}

function get_js_jshint_globalstrict($e) {
    if ($e != "true") {
        $e = "false";
    }
	$globalstrictlist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="js_jshint_globalstrict" name="js_jshint_globalstrict" class="select form-control">';
		foreach($globalstrictlist as $key=>$js_jshint_globalstrict){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $js_jshint_globalstrict .'</option>';
		}
	echo '</select>';
}

function get_show_token_info($e) {
    if ($e != "true") {
        $e = "false";
    }
	$token_infolist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select data-key="show_token_info" data-value="'.$e.'" id="show_token_info" name="show_token_info" class="select form-control">';
		foreach($token_infolist as $key=>$token_info){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $token_info .'</option>';
		}
	echo '</select>';
}

function get_editor_selection_style($e) {
	$token_infolist = array(
		"line"		=> "true",
		"text"		=> "false",
	);
	echo '<select data-key="editor_selection_style" data-value="'.$e.'" id="editor_selection_style" name="editor_selection_style" class="select form-control">';
		foreach($token_infolist as $key=>$token_info){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $token_info .'</option>';
		}
	echo '</select>';
}

function get_editor_animated_scroll($e) {
    if ($e != "true") {
        $e = "false";
    }
	$token_infolist = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select data-key="editor_animated_scroll" data-value="'.$e.'" id="editor_animated_scroll" name="editor_animated_scroll" class="select form-control">';
		foreach($token_infolist as $key=>$token_info){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $token_info .'</option>';
		}
	echo '</select>';
}

function get_code_folding($e) {
    if ($e == null || $e == '') {
        $e = "markbegin";
    }
	$token_infolist = array(
		"manual"		=> "Manual",
		"markbegin"		=> "Mark Beginning",
		"markbeginend"		=> "Mark Beginning and End",
	);
	echo '<select data-key="editor_code_folding" id="editor_code_folding" name="editor_code_folding" class="select form-control">';
		foreach($token_infolist as $key=>$token_info){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $token_info .'</option>';
		}
	echo '</select>';
}
function get_editor_key_binding($e) {
    if ($e == null || $e == '') {
        $e = "ace";
    }
	$token_infolist = array(
		"ace"		=> "Ace",
		"vim"		=> "Vim",
		"emacs"		=> "Emacs",
	);
	echo '<select data-key="editor_key_binding" id="editor_key_binding" name="editor_key_binding" class="select form-control">';
		foreach($token_infolist as $key=>$token_info){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $token_info .'</option>';
		}
	echo '</select>';
}
function get_editor_soft_wrap($e) {
    if ($e == null || $e == '') {
        $e = "ace";
    }
	$token_infolist = array(
		"off"		=> "Off",
		"40"		=> "40 Characters",
		"80"		=> "80 Characters",
		"free"		=> "Free",
	);
	echo '<select data-key="editor_soft_wrap" id="editor_soft_wrap" name="editor_soft_wrap" class="select form-control">';
		foreach($token_infolist as $key=>$token_info){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $token_info .'</option>';
		}
	echo '</select>';
}
function get_highlight_line($e) {
	$list = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select data-key="editor_highlight_line" id="editor_highlight_line" name="editor_highlight_line" class="select form-control">';
		foreach($list as $key=>$value){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $value .'</option>';
		}
	echo '</select>';
}
function get_show_indent_guides($e) {
	$list = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select data-key="editor_show_indent_guides" id="editor_show_indent_guides" name="editor_show_indent_guides" class="select form-control">';
		foreach($list as $key=>$value){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $value .'</option>';
		}
	echo '</select>';
}
function get_show_invisibles($e) {
	$list = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select data-key="editor_show_invisibles" id="editor_show_invisibles" name="editor_show_invisibles" class="select form-control">';
		foreach($list as $key=>$value){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $value .'</option>';
		}
	echo '</select>';
}
function get_enable_behaviors($e) {
    if ($e != "true") {
        $e = "false";
    }
	$list = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select data-key="editor_enable_behaviors" id="editor_enable_behaviors" name="editor_enable_behaviors" class="select form-control">';
		foreach($list as $key=>$value){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $value .'</option>';
		}
	echo '</select>';
}
// Editor: Autodetect
function get_auto_detect($e) {
	$readonlylists = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="auto_file_extension_detect_mode" data-key="auto_file_extension_detect_mode" data-value="'.$e.'" name="auto_file_extension_detect_mode" class="select form-control">';
		foreach($readonlylists as $key=>$readonlylist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $readonlylist .'</option>';
		}
	echo '</select>';
}

// Editor: Status Bar
function get_statusbar($e){
	$readonlylists = array(
		"active"		=> "Yes",
		"inactive"		=> "No",
	);
	echo '<select id="statusbar" data-value="'.$e.'" data-key="statusbar" name="statusbar" class="select form-control">';
		foreach($readonlylists as $key=>$readonlylist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $readonlylist .'</option>';
		}
	echo '</select>';
}

function get_tagbar($e){
	$readonlylists = array(
		"active"		=> "Yes",
		"inactive"		=> "No",
	);
	echo '<select id="tagbar" data-value="'.$e.'" data-key="tagbar" name="tagbar" class="select form-control">';
		foreach($readonlylists as $key=>$readonlylist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $readonlylist .'</option>';
		}
	echo '</select>';
}

// Editor: Word Wrap
function get_word_wrap($e) {
	$wordwrappinglists = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="editor_toggle_word_wrapping" data-value="'.$e.'" data-key="setUseWrapMode" name="editor_toggle_word_wrapping" class="select form-control">';
		foreach($wordwrappinglists as $key=>$wordwrappinglist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $wordwrappinglist .'</option>';
		}
	echo '</select>';
}

// Editor: phpMyAdmin Open
function get_phpmyadmin_open($e) {
	$phpmyadmin_openpinglists = array(
		"0"		=> "Code Editor Tab",
		"1"		=> "Browser Window Tab",
	);
	echo '<select id="toggle_phpmyadmin_open" data-value="'.$e.'" name="phpmyadmin_open" class="select form-control">';
		foreach($phpmyadmin_openpinglists as $key=>$phpmyadmin_openpinglist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $phpmyadmin_openpinglist .'</option>';
		}
	echo '</select>';
}

function get_search_collapse($e) {
	$search_collapselists = array(
		"0"		=> "Expand",
		"1"		=> "Collapse",
	);
	echo '<select data-key="search_collapse" id="toggle_search_collapse" data-value="'.$e.'" name="search_collapse" class="select form-control">';
		foreach($search_collapselists as $key=>$search_collapselists){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $search_collapselists .'</option>';
		}
	echo '</select>';
}

function get_toolbar_enable($e) {
	$toolbar_enablepinglists = array(
		"0"		=> "Disable",
		"1"		=> "Enable",
	);
	echo '<select data-key="toolbar_enable" id="toggle_toolbar_enable" data-value="'.$e.'" name="toolbar_enable" class="select form-control">';
		foreach($toolbar_enablepinglists as $key=>$toolbar_enablepinglist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $toolbar_enablepinglist .'</option>';
		}
	echo '</select>';
}

function get_lazyloading_enable($e) {
    if ($e == null || $e == '') {
        $e = 1;
    }
	$lazyloading_enablepinglists = array(
		"1"		=> "Yes",
		"0"		=> "No",
	);
	echo '<select data-key="lazyloading" id="toggle_lazyloading" data-value="'.$e.'" name="lazyloading" class="select form-control">';
		foreach($lazyloading_enablepinglists as $key=>$lazyloading_enablepinglist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $lazyloading_enablepinglist .'</option>';
		}
	echo '</select>';
}

// Editor: Autocomplete
function get_autocomplete($e) {
	$autocomplete = array(
		"true"		=> "true",
		"false"		=> "false",
	);
	echo '<select id="get_autocomplete" data-value="'.$e.'" data-key="get_autocomplete" name="get_autocomplete" class="select form-control">';
		foreach($autocomplete as $key=>$autocompletelist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $autocompletelist .'</option>';
		}
	echo '</select>';
}

function get_elastic_tabstops($e) {
	$autocomplete = array(
		"true"		=> "Enable",
		"false"		=> "Disable",
	);
	echo '<select id="elastic_tabstops" data-value="'.$e.'" data-key="elastic_tabstops" name="elastic_tabstops" class="select form-control">';
		foreach($autocomplete as $key=>$autocompletelist){
			$sel=($key==$e)?'selected="selected"':'';
			echo '<option '.$sel.' value="'.$key.'" class="child">'. $autocompletelist .'</option>';
		}
	echo '</select>';
}

// Editor: Split Editor Windows
function get_split(){}

function get_editor_menus($parents){
	return query(
	"select * from 
	".prefix."editor e, 
	".prefix."user_type ut, 
	".prefix."features_to_editor u 
	where 
	ut.user_type_id='".get_user_type_id()."' AND e.parent='".$parents."' AND
	ut.feature_id=u.feature_id AND 
	u.editor_id=e.editor_id order by e.display_order
	");
}

function get_settings($e='',$default=''){
	$datas=query("select* from ".prefix."website_setting where field_name='".$e."'");
	$res=$default;
	
	if(sizeof($datas)>0 && isset($datas[0]->field_value)){
		$res=$datas[0]->field_value;
	}
	
	return $res;
}

/* Navigation: Get Pages */
function get_pages($arg){
	$menu_id		= '';
	$class			= '';
	$home			= 'true';
	$parent			= '0';
	$add_last		= '';
	$page_position	= 'default';
	
	if(is_array($arg)){
		if(isset($arg['menu_id'])){
			$menu_id		= $arg['menu_id'];
		}
		if(isset($arg['class'])){
			$class			= $arg['class'];
		}
		if(isset($arg['home'])){
			$home			= $arg['home'];
		}
		if(isset($arg['parent'])){
			$parent			= $arg['parent'];
		}
		if(isset($arg['parent'])){
			$add_last		= $arg['add_last'];
		}
		if(isset($arg['page_position'])){
			$page_position	= $arg['page_position'];
		}
	}
	
	$menus='';
	$res=query("select* from ".prefix."cms c,".prefix."page_position p where c.page_position_id=p.page_position_id AND p.page_position_slug='".$page_position."' AND c.cms_parent='0' and c.status='Y' order by c.display_order ASC");
	
	if(sizeof($res) > 0){
		$menus.='<ul class="' . $class . '" id="' . $menu_id . '">';
		
		if($home=='true'){
			$cls=(urls() == '') ? 'class="active"' : '';
			$menus.='<li ' . $cls . '>';
				$menus.='<a href="' . get_full_path() . '">';
					$menus.='<span>Home</span>';
				$menus.='</a>';
			$menus.='</li>';
		}
	
		foreach($res as $vals){
			$sublists=get_sub_pages($vals->cms_id,'',$page_position);
			$has=($sublists != '') ? 'has-sub' : '';
			$cls=($vals->cms_title == urls()) ? 'class="active $has"' : '';
			$menus.='<li '.$cls.'>';
				$menus.='<a href="' . get_link($vals->cms_id,'') . '">';
					$menus.='<span>' . $vals->cms_title . '</span>';
				$menus.='</a>';
				$menus.=$sublists;
			$menus.='</li>';
		}
		$menus.=$add_last."</ul>";
	}
	
	return $menus;
}

// Navigation: Subpages
function get_sub_pages($parent='0',$class='',$page_position=''){
	$sub='';
	$res=query("select*  from ".prefix."cms c,".prefix."page_position p where c.page_position_id=p.page_position_id AND p.page_position_slug='".$page_position."' AND c.cms_parent='$parent' and c.status='Y' order by c.display_order ASC");
	
	if(sizeof($res) > 0){
		$sub.='<ul>';
		
		foreach($res as $vals){
			$sublists=get_sub_pages($vals->cms_id,'');
			$has=($sublists != '') ? 'has-sub' : '';
			$cls=($vals->cms_title == urls()) ? 'class="active $has"' : '';
			$sub.='<li ' . $cls . '>';
				$sub.='<a href="' . get_link($vals->cms_id,'') . '">';
					$sub.='<span>' . $vals->cms_title . '</span>';
				$sub.='</a>';
				$sub.=$sublists;
			$sub.='</li>';
		}
		
		$sub.="</ul>";
	}
	
	return $sub;
}

// Link
function get_link($id,$link){
	$res=query("select* from " . prefix . "cms where cms_id='$id' order by display_order ASC");
	
	if(sizeof($res)>0){
		$link=$res[0]->cms_slug . '/' . $link;
		if($res[0]->cms_parent != '0'){
			return get_link($res[0]->cms_parent,$link);
		}else{
			return $link;
		}
	}else{ return $link; }
}

// Set Content
function set_contents(){
    return null;
	$res=query("select* from ".prefix."cms where cms_slug='".urls()."' order by display_order ASC");
	
	if(sizeof($res)>0){
		return $post['db']=$res;//$res;
	}elseif(urls()!=''){
		
	}else if(sizeof($res)==0 && urls()!=''){
		echo urls().( "Page not found");
	}
}

// URLS
function urls(){
	$sinments 		= explode("#",'http://'.$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI']);
	$sinments1 		= explode("?",$sinments[0]);
	$sinments_prm 	= explode("/",str_replace(get_full_path(),'',$sinments1[0]));
	$sinments_prm=array_filter($sinments_prm);
	
	if(sizeof($sinments_prm)>0){
		//print_r($sinments_prm);
		return urlencode($sinments_prm[sizeof($sinments_prm)-1]);
	}else{
		return '';
	}
}

// Full Path
function get_full_path(){
	global $CI;
	
	if(isset($CI)){
		$CI =& get_instance();
		
		return $CI->config->item('base_url');
	}
}

// Page Title
function get_the_title(){
	$current=set_contents();
	
	return isset($current[0])?$current[0]->cms_title:'';
}

// Breadcrumbs
function get_breadcrumb(){
	$current	= set_contents();
	
	$id 		= $current[0]->cms_id;
	$allpath	= get_b($id,'',array());
	$allpath	= array_reverse($allpath);

	$dates = '
				<section class="sec-pagetitle">
					<div class="wrapper_pagetitle">
						<div class="container pagetitleholder">
							<h1 class="pagetitle">' . $current[0]->cms_title . '</h1>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
				</section>
			';
	$dates .= '
				<section class="sec-breadcrumb">
					<div class="wrapper_breadcrumbs">
						<div class="container breadcrumbs">
							<ul class="breadcrumbs">
			';
		$dates .= '				<li>';
			$dates .= '				<a href="' . get_full_path() . '">
										<i class="fa fa-home"></i>Home
									</a>
									<span class="separator">
										<i class="fa fa-angle-right"></i>
									</span>';
		$dates .= '				</li>';

		foreach($allpath as $pth){
			$sels = (urls() == $pth['current_page']) ? 'class="active"' : '';
			$dates .= '			<li ' . $sels . '>';
			if(urls() == $pth['current_page']){
				$dates .= '			<span class="current">' . $pth['title'] . '</span>';
			}else{
				$dates .= '			<a href="' . $pth['url'] . '">' . $pth['title'] . '</a>
									<span class="separator">
										<i class="fa fa-angle-right"></i>
									</span>';	
			}
			$dates .= '			</li>';
		}
	$dates .= '
							</ul>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
				</section>
			';
	echo $dates;
}

function get_b($id,$link,$data){
	$res=query("select* from " . prefix . "cms where cms_id='$id' order by display_order ASC");
	
	if(sizeof($res)>0){
		$data[]=array(
			'url'			=> $res[0]->cms_slug . '/' . $link,
			'title'			=> $res[0]->cms_title,
			'current_page'	=> $res[0]->cms_slug
		);
		
		if($res[0]->cms_parent!='0'){
			return get_b($res[0]->cms_parent,$link,$data);
		}else{
			return $data;
		}
	}else{ return $data; }
}

// Get Recent
function getRecent(){
	$existingOptions=get_option('recentFile',false);
	$optionsArray=json_decode($existingOptions,true);
	//echo "<pre>";
	//print_r($optionsArray); die();
	$options='<ul class="sitenavdropdown dropdownrecent">';
	
	if(is_array($optionsArray) && (sizeof($optionsArray)>0)){
		$optionsArray=array_reverse($optionsArray);
		$count=0;
		foreach($optionsArray as $key=>$vales){
			
			if(isset($vales['title']) && $vales['title']!=''){
				$count++;
				$options.='<li class="child"><a href="javascript:void(0)" data-action-type="calljs"  data-action="openRecentFile" data-tab-id="'.$key.'" data-tab-data=\''.json_encode($vales).'\'><span>'.$vales['title'].'</span></a></li>';
			if($count>2){
				break;
			}
			}
		}
	}
	
	$options.='</ul>';
	
	return $options;
}

// Get Local FTP
function get_local_ftp($dires){
	$dires_files='';
	
	if( file_exists($dires)){
		$files = scandir($dires);
		natcasesort($files);
		$dirindex=0;
		$fileindex=0;
		
		$ex_=get_option('leftDire');
		$ex_=explode('-',$ex_);
		$arys=array_filter($ex_);
		
		if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		
			$dires_files.="";
			$id=isset($_GET['id'])?$_GET['id']:'';
			
			// All dirs
			foreach( $files as $file ) {
				$dirindex++;
				
				if( file_exists($dires.$file) && $file != '.' && $file != '..' && is_dir($dires . $file) ) {
				
					$dires_files.="
						<tr class='directory' data-location='local' draggable='true' data-ftp-log-id='".$id."' data-url='".$dires.$file."'>
								<td>" . htmlentities($file) . "</td>
								<td>N/A</td>
								<td>".date ("d, M Y", filemtime($dires.$file))."</td>
						</tr>";
				}
			}
			
			// All files
			foreach( $files as $file ) {
				$fileindex++;
				
				if( file_exists($dires. $file) && $file != '.' && $file != '..' && !is_dir($dires.$file) ) {
					$ext = preg_replace('/^.*\./', '', $file);
					
					$dires_files.="
						<tr class=\"file ext_$ext\" data-location='local' draggable='true' data-ftp-log-id='".$id."' data-url='".$dires.$file."'>
								<td>". htmlentities($file) . "</td>
								<td>".formatBytes(filesize($dires.$file))."</td>
								<td>".date ("d, M Y", filemtime($dires.$file))."</td>
						</tr>";
				}
			}
			
			$dires_files.="";
		}
	}
	
	return $dires_files;
}

function formatBytes($bytes) { 
    if ($bytes >= 1073741824){
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	}elseif ($bytes >= 1048576){
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	}elseif ($bytes >= 1024){
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	}elseif ($bytes > 1){
		$bytes = $bytes . ' bytes';
	}elseif ($bytes == 1){
		$bytes = $bytes . ' byte';
	}else{
		$bytes = '0 bytes';
	}

	return $bytes;
} 

// Get Directories
function get_dires($root,$dires){
	$dires_files='';

        if( file_exists($root.$dires)){
		$files = scandir($root.$dires);
		natcasesort($files);
		$dirindex=0;
		$fileindex=0;
		
		$ex_=get_option('leftDire');
		$ex_=explode('-',$ex_);
		$arys=array_filter($ex_);
		
		$dires_files.="<ul class=\"jqueryFileTree parent-uls\">";
		
		if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		
			// All dirs
			foreach( $files as $file ) {
				$dirindex++;
				if( file_exists($root.$dires.$file) && $file != '.' && $file != '..' && is_dir($root . $dires . $file) ) {
					$der_='';
					/*
					if(in_array(code(htmlentities($dires. $file)),$arys)){
						$der_=get_dires($root,$dires.$file.'/');
					}
                    */
					
					$cels=($der_!='')?'expanded':'collapsed';
					$dires_files.="<li class=\"directory $cels\"><a href=\"#\" data-dir='".$dirindex."' data-file-id='".code(htmlentities($dires. $file))."' rel=\"" . rawurlencode($dires. $file) . "/\">" . htmlentities($file) . "</a>";
					$dires_files.=$der_;
					$dires_files.="</li>";
				}
			}
			
			// All files
			foreach( $files as $file ) {
				$fileindex++;
				
				if( file_exists($root.$dires. $file) && $file != '.' && $file != '..' && !is_dir($root.$dires.$file) ) {
					$ext = preg_replace('/^.*\./', '', $file);
					$dires_files.="<li class=\"file ext_$ext\"><a href=\"#\" data-url='".($dires. $file)."'  data-title='".htmlentities($file)."' data-file-id='".code(htmlentities($dires.$file)).'-'.(isImage($root.$dires. $file)?'static':'editor')."' rel=\"" . rawurlencode($dires.$file) . "\">" . htmlentities($file) . " </a></li>";
				}
			}
		}
		
		$dires_files.="</ul>";
	}
	
	return $dires_files;
}

// Allowed Image Extensions (formats)
function imageExtensions(){
	return array('bmp','gif','jpeg','jpg','png');
}

function isImage($e){
	$ext = pathinfo($e, PATHINFO_EXTENSION);
	if(in_array($ext,imageExtensions())){
		return true;
	}else{
		return false;
	}
}

function code($data,$enc=true){
    if ($enc == true) {
        $output = base64_encode (convert_uuencode ($data));
    } else {
        $output = convert_uudecode (base64_decode ($data));
    }
	$result = preg_replace("/[^a-zA-Z0-9]+/", "", $output);
	
	return strtolower($result);
}

function get_discounted_price($price,$discount_persentage){
	return $newprice = $price * ((100-$discount_persentage) / 100);
}

function send($to='',$from='',$from_name='',$subject='',$message=''){
	if(file_exists('class/class.phpmailer.php')){
		require_once('class/class.phpmailer.php');
		require_once('class/class.smtp.php');
	}else{
		require_once('admin/class/class.phpmailer.php');
		require_once('admin/class/class.smtp.php');
	}
	
	if(file_exists('../application/config/email.php')){
		require('../application/config/email.php');

		$mail 				= new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug 	= 1;
		$mail->SMTPAuth 	= true;
		$mail->Host 		= $config['smtp_host'];
		$mail->Port 		= $config['smtp_port'];
		$mail->Timeout		= 360;  
		$mail->Username		= $config['smtp_user'];
		$mail->Password		= $config['smtp_pass'];
		$mail->Subject 		= $subject;
		$mail->From 		= $from;
		$mail->FromName 	= $from_name;
		$mail->AddReplyTo($from, $from_name);
		$mail->AddAddress($to);

		$mail->Body 		= $message;
		$mail->IsHTML(true);
		$returns			= $mail->Send();
		
		return $returns;
	}
}

function search_in_array($e,$values){
	foreach($e as $key=>$val){
		if(is_array($val)){
			if(in_array($values,$val)){
				return $key;
			}
		}else{
			return false;
		}
	}
}
?>