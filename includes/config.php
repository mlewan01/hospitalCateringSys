<?php
// General Settings
define('APP_NAME', 'Ctering system for hospital');

//  database settings + php error reporting
if($_SERVER['SERVER_NAME'] != 'artemlux.com'){
  define('ERR', true); // true for displaing errors output
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'catering');
}else{ // if($_SERVER['SERVER_NAME'] == 'artemlux.com'){
  define('ERR', false); // false for hiding all errors output
  define('DB_USER', 'nelhae01_nhs2');
  define('DB_PASS', 'nhsnhs');
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'nelhae01_nhs2');
}
// Language settings, commet out the right settings for you
// define('LANGUAGE', 'en');
define('LANGUAGE', 'pl');

/**
 * Absolute path to application root directory (one level above current dir)
 * Tip: using dynamically generated absolute paths makes the app more portable.
 */
define('APP_DIR', dirname(dirname(__FILE__)));

// allowed characters in sanitise functions
$config['sanitise'] = array('@', ' ', '.', ',', '?', '!',':', '-', '&', '_');
// patient type
$config['type'] = array('nhs','private');
// diet
$config['diet'] = array('standard','vegetarian','vegan','halal','kosher');
// nutrition
$config['nutrition'] = array('nil','water','clear fluids','free fluids','soft diet','light diet','eating & drinking');
// allergens
$config['allergens'] = array('celery','crustaceans','eggs','fish','lupin','milk','molluscs','mustard','tree nuts','peanuts','sesame','soybeans','sulphites');
//meals time end
define('TIME_BREAKFAST', 10); // change to required value
define('TIME_LUNCH', 14); // change to required value
define('TIME_SUPPER', 18); // change to required value
// site operation
define('LOG_',true); // enables logging of important database changes, to enable set it of 'true' to disable set to 'false'
define('DEV', false); // enables development aid output
define('PAG', 10); // amount of items displayed in paginated output
//SALT Information

/* Application Keys
 */
define('SITE_KEY', 'tIVLEabZMrxm!%4ZHJWnXAjxbPt4mYGtyb!@$%&^%VQJsxGjOIdej#OT3EhCpxqC5Bu6KSOJM$$##VJV9jLF5uWiiFXm1G');
/* NONCE SALT
 */
define('NONCE_SALT', 'fxmAMC5TiY2_)(eh2DfbOOX4*&F73ldggm8KZP35N48t3OVbTaoOpaOlLydef#_+kvusgNgafnuujTPdazfzqpDy');
/* AUTH SALT
 */
define('AUTH_SALT', 'g)(*)Um9SXCqWWvSDm6&^&k3iwMqPghWzTgqMSiy)(&*&RaAoMdbyLNuRdvH(gwL0fA7Umlmy4ZvH04r2xjp7KH2ahNNc');
/* PASSREC_SALT password recovery salt
 */
define('PASSREC_SALT','fxmAMC5TiY2_)(eh2DfbXAjxbPt4mYGtyb!@$%1k9VQJsxGjOIdej#OT3EhCpxqC5Bu6KSOJM$g)(*)Um9SXCqWWvSDm6&')
?>
