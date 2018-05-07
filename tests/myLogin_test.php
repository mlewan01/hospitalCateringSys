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
  public function test_register(){

    $db = new myDB();
    $mt = new myTime();
    $ml = new myLogin();

    $_POST['name'] = 'maniek2';
    $_POST['username'] = 'maniek2';
    $_POST['password'] = 'maniek2';
    $_POST['email'] = 'maniek2@maniek2.com';
    $_POST['phone'] = '44444';
    $_POST['info']= 'maniek2';
    $_POST['privileges']= '10';
    $_POST['department']= 'catering';
    $_POST['role']= 'doctor';

    $out = $ml->register($db, $mt);

    // $msg = 'Registration successful.'; // self explanatory
    $msg = 'reg'; // user already exists

    $this->assertSame($msg, $out[0], "registration was not successful: $out[0] for $out[1] def out: $out[2]");
  }
  /**@test*/
  public function test_login(){

    $db = new myDB();
    $mt = new myTime();
    $ml = new myLogin();

    $_POST['l_username'] = 'maniek3'; // logging in as not existing user
    $_POST['l_password'] = 'maniek2';

    $out = $ml->login($db);

    $msg = 'invalid'; // output when invalid password
    $this->assertSame($msg, $out[0], "user not logged in..");
  }
  /**@test*/
  public function test_changePassword(){
    $db = new myDB();
    $l = new myLogin();
    $data = $db->myQuery('select * from users where u_username = "maniek2"');
    $data = $data->fetch_assoc();
    // var_dump($data);
    $data = $data['u_regdate'];
    $msg = "cookie catering does not exists<br/>";
    echo $data;
    $out = $l->changePassword(29, 'maniek2', 'password', $data, 'index', $db);

    $this->assertTrue($out[0], "pass has not been changed... Info:  $out[1]");
  }
}
?>
