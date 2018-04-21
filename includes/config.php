<?php
// General Settings
$config['app_name'] = 'Catering system for hospital';
define('APP_NAME', 'Ctering system for hospital');

//  database settings + php error reporting
if($_SERVER['SERVER_NAME'] != 'artemlux.com'){
  define('ERR', true); // true for displaing errors output
  define('DB_USER', 'root'); $config['db_user'] = 'root';
  define('DB_PASS', ''); $config['db_pass'] = '';
  define('DB_HOST', 'localhost'); $config['db_host'] = 'localhost';
  define('DB_NAME', 'catering'); $config['db_name'] = 'catering';
}else{ // if($_SERVER['SERVER_NAME'] == 'artemlux.com'){
  define('ERR', false); // false for hiding all errors output
  define('DB_USER', 'nelhae01_nhs2');
  define('DB_PASS', 'nhsnhs');
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'nelhae01_nhs2');
}
// Language settings, commet out the right settings for you
$config['language'] = 'en';
define('LANGUAGE', 'en');
//$config['language'] = 'pl';

/**
 * Absolute path to application root directory (one level above current dir)
 * Tip: using dynamically generated absolute paths makes the app more portable.
 */
$config['app_dir'] = dirname(dirname(__FILE__));
define('APP_DIR', dirname(dirname(__FILE__)));

// a field used to indicate wheter the site is in development proces or not
// in order to turn on/of development output
$config['in_development'] = false;
define('IN_DEVELOPMENT', false);
//$config['hospital'] = 1;
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
$config['time_breakfast'] = 10;
define('TIME_BREAKFAST', 10);
$config['time_lunch'] = 14;
define('TIME_LUNCH', 14);
$config['time_supper'] = 18;
define('TIME_SUPPER', 18);
// logging, to enable set it of 'true' to disable set to 'false'
$config['log'] = true; // enables logging of important database changes
define('LOG_',true);
define('DEV', true); // enables development aid output
define('RED', true); // for login redirection enforcement
//SALT Information

/* Application Keys
 */
define('SITE_KEY', 'tIVLEabZMrxm!%4ZHJWnXAjxbPt4mYGtyb!@$%&^%VQJsxGjOIdej#OT3EhCpxqC5Bu6KSOJM$$##VJV9jLF5uWiiFXm1G');
$config['site_key'] = 'tIVLEabZMrxm!%4ZHJWnXAjxbPt4mYGtyb!@$%&^%VQJsxGjOIdej#OT3EhCpxqC5Bu6KSOJM$$##VJV9jLF5uWiiFXm1G';
/* NONCE SALT
 */
define('NONCE_SALT', 'fxmAMC5TiY2_)(eh2DfbOOX4*&F73ldggm8KZP35N48t3OVbTaoOpaOlLydef#_+kvusgNgafnuujTPdazfzqpDy');
$config['nonce_salt'] = 'fxmAMC5TiY2_)(eh2DfbOOX4*&F73ldggm8KZP35N48t3OVbTaoOpaOlLydef#_+kvusgNgafnuujTPdazfzqpDy';
/* AUTH SALT
 */
define('AUTH_SALT', 'g)(*)Um9SXCqWWvSDm6&^&k3iwMqPghWzTgqMSiy)(&*&RaAoMdbyLNuRdvH(gwL0fA7Umlmy4ZvH04r2xjp7KH2ahNNc');
$config['auth_salt'] = 'g)(*)Um9SXCqWWvSDm6&^&k3iwMqPghWzTgqMSiy)(&*&RaAoMdbyLNuRdvH(gwL0fA7Umlmy4ZvH04r2xjp7KH2ahNNc';

define('PASSREC_SALT','fxmAMC5TiY2_)(eh2DfbXAjxbPt4mYGtyb!@$%1k9VQJsxGjOIdej#OT3EhCpxqC5Bu6KSOJM$g)(*)Um9SXCqWWvSDm6&')
?>
