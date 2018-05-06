<?php
// connecting to required/included files
$ds = DIRECTORY_SEPARATOR;
require_once 'includes/config.php';
require 'lang/'.LANGUAGE.'.php';
require_once 'includes/functions.php';
require_once 'includes/pagination.php';
displayErroros();
autoloader();
$mt = new myTime(); // instantiation of myTime class
$db = new myDB(); // connection with database
$content = '';
$b = '</br>';
$navMain = '';
$l = new myLogin(); // instantiation of myLogin class
$logged = $l->checkLogin($db);
$loginCheck = '';
$authLevel = 0;
$dev = '';
$user_id = 0;
// authorization
if (!isset($_GET['page'])) { //
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
			if(navigation(0,$_GET['page'])){
				$user_id = $logged[3];
				if(strpos(navigation($logged[2]), $_GET['page']) === false){ // authorization
					$content .= '<b>You have no rights to access '.$_GET['page'].' page.</b>';
					$pageid = 'login';
				}else {
					$pageid = $_GET['page']; // else requested page,
				}
			}else{
				$pageid = '404';
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

$logged = $l->checkLogin($db); // another check after executing/including the page script
if($logged[0] == 'logged' || $loginCheck == 'loggedIn'){
	if($logged[0] == 'logged'){
		$navMain = navigation($logged[2]);
	}else $navMain = navigation($authLevel);
}
//-----------------------------------------------------------------------------------------------
// preparing the redirection link
$l = $pageid == 'login' ? '' : '?page=login';
$link = getURL($l)['url'];    if(DEV) $dev .= getURL($l)['dev'];
// $domen = strstr($_SERVER['REQUEST_URI'],"index", TRUE);
// $domen = $domen == '' ? $_SERVER['REQUEST_URI'] : $domen;
// footer location and navigation
$lo = footerLocation($logged[0], $pageid);
// footer navigations
if(isset($_POST['h_foot'])) {
	footerLinkUpdate(); // sets the location cookie for new selected bed
	header("Location: ".getURL('')['url']);
	// header("Location: http".$s."://".$_SERVER['SERVER_NAME'].$domen.'index.php'); // HACK: since the new value assigned to cookie is not immidiatly accessible
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
echo $out;
	if(DEV) echo '<div class="devout"><h4>Dev out:</h4>'.$dev.'</div>';
} // end service if
} // ajax if
?>
