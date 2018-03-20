<?php
// connecting to required/included files
$ds = DIRECTORY_SEPARATOR;
require_once 'includes/config.php';
require 'lang/'.LANGUAGE.'.php';
require_once 'includes/functions.php';
autoloader();
$content = '';
// Code to detect whether index.php has been requested without query string goes here
if (!isset($_GET['page'])) { // TODO sanitise the input finally !!
	$pageid = 'home'; // display home page
} else {
	// $l = new myLogin();
	// $logged = $l->checkLogin();
	// if($logged == 'not') $l->redirect("index.php?page=login");
	$pageid = $_GET['page']; // else requested page,
}
if (!file_exists("views/$pageid.php")) $pageid = '404';

include "views/$pageid.php";
$heading1 = "h_".$pageid;

//-----------------------------------------------------------------------------------------------

if($pageid != 'service'){
// prepearing data for use with usage with site template
$arr = array(
	'[+title+]' => $lang[$pageid],
	'[+heading1+]' => $lang[$heading1],
	'[+content+]' => $content,
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
