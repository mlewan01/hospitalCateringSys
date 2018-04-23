<?php
// connecting to required/included files
$ds = DIRECTORY_SEPARATOR;
require_once 'includes/config.php';
require 'lang/'.LANGUAGE.'.php';
require_once 'includes/functions.php';
require_once 'includes/pagination.php';
displayErroros();
autoloader();
$content = '';
$b = '</b>';
$navMain = '';
$l = new myLogin();
$logged = $l->checkLogin();
$loginCheck = '';
$authLevel = 0;
$dev = '';

if (!isset($_GET['page'])) { // TODO sanitise the input finally !!
	$pageid = 'home'; // display home page
} else {
	if(sanitise($_GET['page'])){
		if($logged[0] == 'not') {
			if($_GET['page'] == 'passrec'){
				$pageid = 'passrec';
			}else {
				// $content .= 'you are not logged in';
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
// for ajax
if(isset($_GET['is'])){
	echo $content;
}else { // if not ajax call then curry on as normal
$heading1 = "h_".$pageid;

$logged = $l->checkLogin();
if($logged[0] == 'logged' || $loginCheck == 'loggedIn'){
	if($logged[0] == 'logged'){
		$navMain = navigation($logged[2]);
	}else $navMain = navigation($authLevel);
}
//-----------------------------------------------------------------------------------------------
// preparing the redirection link
$domen = strstr($_SERVER['REQUEST_URI'],"index", TRUE);   $dev .= "domen: ".$domen;
$domen = $domen == '' ? $_SERVER['REQUEST_URI'] : $domen;   $dev .= " domen: ".$domen. ' request uri: '.$_SERVER['REQUEST_URI'];
$s = ((!empty($_SERVER['HTTPS'])) ? "s" : "");
$l = $pageid == 'login' ? '' : '?page=login';
$link = "http".$s."://".$_SERVER['SERVER_NAME'].$domen."index.php".$l;
// footer location and navigation
$lo = footerLocation($logged[0], $pageid);
// footer navigations
if(isset($_POST['h_foot'])) {
	footerLink();
	header("Location: http".$s."://".$_SERVER['SERVER_NAME'].$domen.'index.php');
}
if($pageid != 'service'){
// prepearing data for use with usage with site template
$arr = array(
	'[+title+]' => $lang[$pageid],
	'[+heading1+]' => $lang[$heading1],
	'[+content+]' => $content,
	'[+nav_main+]' => $navMain,
	'[+logoImage+]' => $link,
	// '[+f_message+]' => $lang['f_message'],
	'[+f_message+]' => $lo[0],
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
if(!isset($_GET['is'])){
	echo $out;
}

	if(DEV) echo '<div class="devout"><h4>Dev out:</h4>'.$dev.'</div>';
}
} // ajax if
?>
