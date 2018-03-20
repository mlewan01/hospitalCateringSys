<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mt = new myTime();
$b = '<br/>';
$content = '';
$sql = '';
$curDay = $mt->getCurDay();
$date_from = $curDay;
$date_to = $mt->getMyTime();

$db = new myDB();

if(isset($_POST['get_logs'])){
	echo ' get_logs '.$b;
	$date_from = $_POST['l_date_from'];
	$date_to = $_POST['l_date_to'];
	echo "from: $date_from - to: $date_to";
	$date_from = $mt->getMyTime(3, $date_from);
	$date_to = $mt->getMyTime(3, $date_to);
	
}else {
	echo ' not get_logs '.$b;
}

$content .= '<form enctype="multipart/form-data" action="index.php?page=logs" method="post">';
$content .= '<fieldset>';
$content .= '<legend>Logs time range</legend>';
$content .= '<label for="l_date_from">Date from</label>';
$content .= '<input type="text" id="l_date_from" name="l_date_from" value="'.$mt->getMyTime(2, $date_from).'" />';
$content .= '<br />';
$content .= '<label for="l_date_from">Date to</label>';
$content .= '<input type="text" id="l_date_to" name="l_date_to" value="'.$mt->getMyTime(2, $date_to).'" />';
$content .= '<br />';
$content .= '<input type="submit" value="Get Logs" name="get_logs">';			
$content .= '</fieldset></form>';


$sql = "select * from logs where l_date > $date_from and l_date < $date_to ORDER BY l_id DESC";
$result = $db->myQuery($sql);
$content .= '<ul>';
while($row = $result->fetch_assoc()){
	$content .= "<li> $row[l_id] - $row[l_msg] </li>";
}
$content .= '</ul>';
$content .= 'current day: '.$curDay;

?>