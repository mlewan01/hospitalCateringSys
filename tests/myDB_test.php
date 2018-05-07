<?php
use PHPUnit\Framework\TestCase;
class FunctionsTests extends TestCase {//extends PHPunit_Framework_Testcase

  public static function setUpBeforeClass(){
    // innitial set up
    $_SERVER["SERVER_NAME"] = 'localhost';

    $ds = DIRECTORY_SEPARATOR;
    require_once '..\includes/config.php';
    require '..\lang/'.LANGUAGE.'.php';
    require_once '..\includes/functions.php';
    require_once '..\includes/pagination.php';
    displayErroros();
    include '../classes/'.'myTime'.'.php';
    include '../classes/'.'myLogin'.'.php';
    include '../classes/'.'myDB'.'.php';

    $_SERVER["REQUEST_URI"] = 'test1/';
    $_SERVER["SERVER_NAME"] = 'localhost/';
    $_SERVER['HTTP_HOST'] = 'localhost/';
    $_SERVER['HTTP_REFERER'] = 'http://localhost/test1/';
  }
  /**@test*/
  public function test_logDB(){
    $db = new myDB();
    $out = $db->logDB('message', 10, 1, 'sql querry for testing', time());
    $msg = 'logged';
    $this->assertSame($msg, $out, 'log has not been entered to DB...');
  }
  /**@test*/
  public function test_myLog(){
    $db = new myDB();
    $out = $db->myLog('message', 10, 1, 'sql querry for testing', time());

    $msg = 'message'; //'invalid'; // output when invalid password
    $this->assertSame($msg, $out['l_msg'], "log has not been prepared...");
  }
  /**@test*/
  public function test_hash_password(){
    $password = 'securepassword';
    $db = new myDB();
    $out = $db->hash_password($password, NONCE_SALT);
    $ou2 = hash_hmac('sha512', $password . NONCE_SALT, SITE_KEY);

    $this->assertSame($out, $ou2, "problem with password hashing...");
  }
}
?>
