<?php
// connecting to required/included files
$ds = DIRECTORY_SEPARATOR;
require_once 'includes/config.php';
require 'lang/'.LANGUAGE.'.php';
require_once 'includes/functions.php';
displayErroros();
autoloader();
$content = '';
$b = '</b>';
$navMain = '';
$l = new myLogin();
$logged = $l->checkLogin();
$loginCheck = '';
$authLevel = 0;

if (!isset($_GET['page'])) { // TODO sanitise the input finally !!
	$pageid = 'home'; // display home page
} else {
	if(sanitise($_GET['page'])){
		if($logged[0] == 'not') {
			if($_GET['page'] == 'passrec'){
				$pageid = 'passrec';
			}else {
				$content .= 'you are not loged in';
				$pageid = 'login';
			}// $l->redirect("index.php?page=login");
		}else {
			if(strpos(navigation($logged[2]), $_GET['page']) === false){
				$content .= '<b>You have no rights to access '.$_GET['page'].' page.</b>';
				$pageid = 'login';
			}else {
				$pageid = $_GET['page']; // else requested page,
			}
		}
	}else{
		$pageid = '404';
		$content .= '<b>Link contains wrong characters</b>';
	}
}
if (!file_exists("views/$pageid.php")) $pageid = '404';

include "views/$pageid.php";
$heading1 = "h_".$pageid;

$logged = $l->checkLogin();
if($logged[0] == 'logged' || $loginCheck == 'loggedIn'){
	if($logged[0] == 'logged'){
		$navMain = navigation($logged[2]);
	}else $navMain = navigation($authLevel);
}
//-----------------------------------------------------------------------------------------------
// $navMain = navigation(10);
// $domen = '/nhs/'; // strstr($_SERVER['REQUEST_URI'],"index", TRUE);
// echo strstr($_SERVER['REQUEST_URI'],"index", TRUE);
$domen = strstr($_SERVER['REQUEST_URI'],"index", TRUE);
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
