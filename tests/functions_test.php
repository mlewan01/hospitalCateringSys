<?php
use PHPUnit\Framework\TestCase;
class FunctionsTests extends TestCase {//extends PHPunit_Framework_Testcase {
  /**@test*/
  public function testSomething(){
    include('C:\Users\me\Google Drive\sites\nhs\includes\functions.php');

    $out = navigation(4);
    echo 'out navigation: <br/>'.$out;
    echo $_DIR_;

  }
}
?>
<!-- Unit testing is usually conducted in order to help identify and fix various bugs.
Us of this technicue helps with refractoring working code as correctly placed UnitTests
 will keep the output of refractored code in constrains immidiately reporting errors
 while conducting tests after changes were made.  -->
