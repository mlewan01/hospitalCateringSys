<?php
session_start();
// connecting to required/included files
$ds = DIRECTORY_SEPARATOR;
require_once 'includes/config.php';
require 'lang/'.LANGUAGE.'.php';
require_once 'includes/functions.php';
autoloader();
$content = '';
$b = '</b>';
$navMain = '';
$l = new myLogin();
$logged = $l->checkLogin();
$loginCheck = '';
$authLevel = 0;

// Code to detect whether index.php has been requested without query string goes here
if (!isset($_GET['page'])) { // TODO sanitise the input finally !!
	$pageid = 'home'; // display home page
} else {
	// $l = new myLogin();
	// $logged = $l->checkLogin();
	if($logged[0] == 'not') {
		// $l->redirect("index.php?page=login");
		$pageid = 'login';
	}else {
		$pageid = $_GET['page']; // else requested page,
		// $navMain = navigation($logged[2]);
	}
}
if (!file_exists("views/$pageid.php")) $pageid = '404';

include "views/$pageid.php";
$heading1 = "h_".$pageid;

$logged = $l->checkLogin();
// echo ' session login status: '.($_SESSION['auth'] ? 'LOGGED IN' : 'LOGGED NOT').$b;
if($logged[0] == 'logged' || $loginCheck == 'loggedIn'){
	if($logged[0] == 'logged'){
		$navMain = navigation($logged[2]);
	}else $navMain = navigation($authLevel);

// 	echo 'loginCheck:_'.$loginCheck.'_value logged[0]:_'.$logged[0].'_value'.$b; //.$navMain;
// 	echo 'login level:_'.$logged[2].'_value'.$b;
// 	echo ' autentication level:_'.$authLevel.'_value'.$b;
}
//-----------------------------------------------------------------------------------------------
// $navMain = navigation(10);
$domen = '/nhs/'; // strstr($_SERVER['REQUEST_URI'],"index", TRUE);
// echo $domen;
$s = ((!empty($_SERVER['HTTPS'])) ? "s" : "");
$link = "http".$s."://".$_SERVER['SERVER_NAME'].$domen."index.php?page=login";
if($pageid != 'service'){
// prepearing data for use with usage with site template
$arr = array(
	'[+title+]' => $lang[$pageid],
	'[+heading1+]' => $lang[$heading1],
	'[+content+]' => $content,
	'[+nav_main+]' => $navMain,
	'[+logoImage+]' => $link,
	'[+f_message+]' => $lang['f_message'],
	'[+logo+]' => $lang['logo_title'],
	'[+m1+]' => $lang['menue'],
	'[+m2+]' => $lang['items'],
	'[+m3+]' => $lang['menus'],
	'[+m4+]' => $lang['orders'],
	'[+m5+]' => $lang['hospitals'],
	'[+m6+]' => $lang['wards'],
	'[+m7+]' => $lang['beds'],
	'[+m8+]' => $lang['patients'],
	'[+m9+]' => $lang['users'],
	'[+m10+]' => $lang['pat_bed'],
	'[+m11+]' => $lang['pat_diet'],
	'[+m13+]' => $lang['menu_items'],
	'[+m14+]' => $lang['menu_sets'],
	'[+m15+]' => $lang['set_location'],
	'[+m16+]' => $lang['bed_pat_diet'],
	'[+m17+]' => $lang['view_order'],
	'[+m18+]' => $lang['logs'],
	'[+m19+]' => $lang['c_login']

);
	$out = tpl(1, './templates/page_tpl.html', $arr);
// outputing all collected data to the browser
	echo $out;
}
?>
