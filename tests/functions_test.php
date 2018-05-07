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
    $_SERVER["SERVER_NAME"] = 'localhost';
  }
  /**@test*/
  public function test_getURL(){
    $regex = "((https?|ftp):\/\/)?"; // SCHEME
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-z0-9\-\.]*)\.(([a-z]{2,4})|([0-9]{1,3}\.([0-9]{1,3})\.([0-9]{1,3})))"; // Host or IP
    $regex .= "(:[0-9]{2,5})?"; // Port
    $regex .= "(\/([a-z0-9+\$_%-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-z_.-][a-z0-9+$%_.-]*)?"; // Anchor
    $this->assertRegExp("/$regex/", getURL('?page=test')['url'], "provided link does not conforms to URL pattern.");
  }
  /**@test*/
  public function test_getCurrentMeal(){
    $mt = new myTime();
 	  $t =  $mt->getMyTime();
    $d = $mt->d;
 	  $curDay = $mt->getCurDay();
 	  $t1 = $mt->curHur();
    $out = getCurrentMeal($t, $d, $curDay, $t1);
    $this->assertNotEmpty($out['meal'], "navitation function did not return any navigation...");
  }
  /**@test*/
  public function test_footerLocation(){
    $out = footerLocation('looged', 'home');
    $this->assertNotEmpty($out['location'], "navitation function did not return any navigation...");
  }
  /**@test*/
  public function test_Navigation(){
    $out = navigation(4);
    $this->assertNotEmpty($out, "navitation function did not return any navigation...");
  }
  /**@test*/
  public function test_getLocation(){
    $msg = "cookie catering does not exists<br/>";
    $out = getLocation();
    $this->assertSame($msg, $out['msg'], "cookie should not be availabe during unit tests...");
  }
  /**@test*/
  public function test_sanitise(){
    // $msg = "cookie 9(){}|ble ][\';.,/\]<br/>";
    $msg = " hello world !";
    $out = sanitise($msg);
    $this->assertTrue($out, "only allwed characters are: alpha numeric and ...".print_r( array('@', ' ', '.', ',', '?', '!',':', '-', '&', '_'),true));
  }
  /**@test*/
  public function test_sanitiseInput(){
    // $msg = "cookie 9(){}|ble ][\';.,/\]<br/>";
    $msg = " hello world !";
    $pr = 'b_'; // database prefix
    $id = $pr.'id';
    $_POST[$id] = $msg;
    $out = sanitiseInput($_POST, array('', $id));
    $this->assertFalse($out[0], "only allwed characters are: alpha numeric and ...".print_r( array('@', ' ', '.', ',', '?', '!',':', '-', '&', '_'),true));
  }
  /**@test*/
  public function test_formFetch(){
    $db = new myDB();
    $pr = 'b_'; // database prefix
    $_POST['fetch'] = 'fetch';
    $_POST['b_id'] = 1;
    $_POST[$pr.'name'] = 'bed test';
    $_POST[$pr.'id_ward'] = 1;
    $_POST[$pr.'id_hospital'] = 1;
    $_POST[$pr.'phone'] = 9999;
    $_POST[$pr.'occupied'] = 0;
    $msg = "<p>";// app normal output
    $pr = 'b_'; // database prefix
    $id = $pr.'id'; // database id field with prefix
    $dbwhere = 'beds'; // database target table
    $out = array('','','',); // for output from form generating function TODO remove is occupied, not needed
    $formFields = array('id','name','id_ward','id_hospital','phone', 'occupied'); // form fields array
    $to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
    $formButtons = array('edit','add','delete','fetch','reset'); // form buttons
    $sql = ""; // for constructing SQL query
    $required = array(0,1,1,1,0,0); // marks required fields of the form
    $txtField = array(0,0,0,0,0,0); // 0-input text, 1-textArea, 2-date // TODO: select
    // $form = makeForm(array($pr, $dbwhere, $formFields, $formButtons, $txtField)); // // TODO: form, not yet translated

    // Check with what button the form has been submited
    if(isset($_POST['fetch'])){

    	$out = formFetch($to_clean, $required, $dbwhere, $id, $txtField);
    	// if($out[2] != '')$msg = $db->myQuery($out[2]);
    }
    $this->assertNotEmpty($out[2], 'somthing went wrong with the formFetch()...');
  }
  /**@test*/
  public function test_formDelete(){
    $db = new myDB();
    $pr = 'b_'; // database prefix
    $_POST['delete'] = 'delete';
    $_POST['b_id'] = 1;
    $_POST[$pr.'name'] = 'bed test';
    $_POST[$pr.'id_ward'] = 1;
    $_POST[$pr.'id_hospital'] = 1;
    $_POST[$pr.'phone'] = 9999;
    $_POST[$pr.'occupied'] = 0;
    $msg = "<p>";// app normal output
    $pr = 'b_'; // database prefix
    $id = $pr.'id'; // database id field with prefix
    $dbwhere = 'beds'; // database target table
    $out = array('','','',); // for output from form generating function TODO remove is occupied, not needed
    $formFields = array('id','name','id_ward','id_hospital','phone', 'occupied'); // form fields array
    $to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
    $formButtons = array('edit','add','delete','fetch','reset'); // form buttons
    $sql = ""; // for constructing SQL query
    $required = array(0,1,1,1,0,0); // marks required fields of the form
    $txtField = array(0,0,0,0,0,0); // 0-input text, 1-textArea, 2-date // TODO: select
    // $form = makeForm(array($pr, $dbwhere, $formFields, $formButtons, $txtField)); // // TODO: form, not yet translated

    // Check with what button the form has been submited
    if(isset($_POST['delete'])){

    	$out = formDelete($to_clean, $required, $dbwhere, $id);
    	// if($out[2] != '')$msg = $db->myQuery($out[2]);
    }
    $this->assertNotEmpty($out[2], 'somthing went wrong with the formDelete()...');
    // echo $out[0];
  }
  /**@test*/
  public function test_formAdd(){
    $db = new myDB();
    $pr = 'b_'; // database prefix
    $_POST['add'] = 'add';
    $_POST['b_id'] = 1;
    $_POST[$pr.'name'] = 'bed test';
    $_POST[$pr.'id_ward'] = 1;
    $_POST[$pr.'id_hospital'] = 1;
    $_POST[$pr.'phone'] = 9999;
    $_POST[$pr.'occupied'] = 0;
    $msg = "<p>";// app normal output
    $pr = 'b_'; // database prefix
    $id = $pr.'id'; // database id field with prefix
    $dbwhere = 'beds'; // database target table
    $out = array('','','',); // for output from form generating function TODO remove is occupied, not needed
    $formFields = array('id','name','id_ward','id_hospital','phone', 'occupied'); // form fields array
    $to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
    $formButtons = array('edit','add','delete','fetch','reset'); // form buttons
    $sql = ""; // for constructing SQL query
    $required = array(0,1,1,1,0,0); // marks required fields of the form
    $txtField = array(0,0,0,0,0,0); // 0-input text, 1-textArea, 2-date // TODO: select
    // $form = makeForm(array($pr, $dbwhere, $formFields, $formButtons, $txtField)); // // TODO: form, not yet translated

    // Check with what button the form has been submited
    if(isset($_POST['add'])){

    	$out = formDelete($to_clean, $required, $dbwhere, $id);
    	// if($out[2] != '')$msg = $db->myQuery($out[2]);
    }
    $this->assertNotEmpty($out[2], 'somthing went wrong with the formDelete()...');
    // echo $out[0];
  }
  /**@test*/
  public function test_formEdit(){
    $db = new myDB();
    $pr = 'b_'; // database prefix
    $_POST['edit'] = 'edit';
    $_POST['b_id'] = 1;
    $_POST[$pr.'name'] = 'bed test';
    $_POST[$pr.'id_ward'] = 1;
    $_POST[$pr.'id_hospital'] = 1;
    $_POST[$pr.'phone'] = 9999;
    $_POST[$pr.'occupied'] = 0;
    $msg = "<p>";// app normal output
    $pr = 'b_'; // database prefix
    $id = $pr.'id'; // database id field with prefix
    $dbwhere = 'beds'; // database target table
    $out = array('','','',); // for output from form generating function TODO remove is occupied, not needed
    $formFields = array('id','name','id_ward','id_hospital','phone', 'occupied'); // form fields array
    $to_clean = array_merge(array(''), addPrefix($formFields, $pr)); // prepering for sanitazation of data
    $formButtons = array('edit','add','delete','fetch','reset'); // form buttons
    $sql = ""; // for constructing SQL query
    $required = array(0,1,1,1,0,0); // marks required fields of the form
    $txtField = array(0,0,0,0,0,0); // 0-input text, 1-textArea, 2-date // TODO: select
    // $form = makeForm(array($pr, $dbwhere, $formFields, $formButtons, $txtField)); // // TODO: form, not yet translated

    // Check with what button the form has been submited
    if(isset($_POST['edit'])){

    	$out = formDelete($to_clean, $required, $dbwhere, $id);
    	// if($out[2] != '')$msg = $db->myQuery($out[2]);
    }
    $this->assertNotEmpty($out[2], 'somthing went wrong with the formDelete()...');
    // echo $out[0];
  }
}
?>
