<?php
use PHPUnit\Framework\TestCase;
class FunctionsTests extends TestCase {//extends PHPunit_Framework_Testcase

  public static function setUpBeforeClass(){
    // innitial set up
    require_once '..\includes/config.php';
    require '..\lang/'.LANGUAGE.'.php';
    require_once '..\includes/functions.php';
    require_once '..\includes/pagination.php';
    displayErroros();
    include '../classes/'.'myTime'.'.php';
    include '../classes/'.'myLogin'.'.php';
    include '../classes/'.'myDB'.'.php';
  }
  /**@test*/
  public function test_getMyTime(){
    $mt = new myTime();
    $t = $mt->getMyTime();
    $this->assertSame($t, time(), "time was not the same...");
  }
  /**@test*/
  public function test_curHur(){
    $h=60*60;
    $d=$h*24;
    $mt = new myTime();
    $t1 = $mt->curHur();
    $t = $mt->getMyTime();
    $k = (int)(($t%$d)/$h)+1;
    $this->assertSame($t1, $k, "curHur was not the same...");
  }
  /**@test*/
  public function test_getCurDay(){
    $h=60*60;
    $d=$h*24;
    $mt = new myTime();
    $t1 = $mt->getCurDay();

    $t = $mt->getMyTime();
    $k = $t%$d;  $k = $t - $k;
    $this->assertSame($t1, $k, "getCurDay was not the same...");
  }
}
?>
