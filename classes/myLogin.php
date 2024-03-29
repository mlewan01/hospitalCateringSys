<?php
class myLogin {
/** registers user
 * @param class myDB database object
 * @param class myTime custom Time object
 * @return array where 0 is message, 1 is username, 2 devout
 */
	function register($db, $mt) {
		$dev = "--------------<b>start of function register</b>------------------<br>";
		$b = '<br/>';
		//Check to make sure the form submission is coming from our script
		//The full URL of our registration page
		$current = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		//The full URL of the page the form was submitted from
		$referrer = $_SERVER['HTTP_REFERER'];
		/*
		 * Check to see if the $_POST array has date (i.e. our form was submitted) and if so,
		 * process the form data.
		 */
		if ( !empty ( $_POST ) ) {
			/*
			 * Here we actually run the check to see if the form was submitted from our
			 * site. Our registration from submits to itself, if
			 * the form submission didn't come from the index.php page on our server,
			 * we don't allow the data through.
			 */
			if ( $referrer == $current ) {
				// checking if the usrename already exists
				$userlogin = $_POST['username'];

				$sql1 = "Select * from users where u_username = '$userlogin'";
				$result1 = $db->myQuery($sql1);
				$result = $result1->fetch_assoc();
				if($userlogin == $result['u_username']){
					$dev .= 'username exists'.$b;
					$dev .= 'submited: '.$userlogin.' existing: '.$result['u_username'].$b."--------------<b>end of function register</b>--------------<br>";
					return array('reg',$userlogin, $dev);
				}
				$result1->free();
				//Set up the variables we'll need to pass to our insert method

				//These are the values from our registration form... cleaned using our clean method
			// $values = $cdb->clean($_POST);   // produces errors, change to msqli due to php 7 upgrade
				$values = $_POST;

				//Now, we're breaking apart our $_POST array, so we can storely our password securely
				$username = $_POST['name'];
				$userlogin = $_POST['username'];
				$userpass = $_POST['password'];
				$useremail = $_POST['email'];
				$userphone = $_POST['phone'];
				$userreg =  $mt->getMyTime();   // $_POST['date'];
				$userinfo = $_POST['info'];
				$userprivileges = $_POST['privileges'];
				$userdepartment = $_POST['department'];
				$userrole = $_POST['role'];

				//We create a NONCE using the action, username, timestamp, and the NONCE SALT
				$nonce = md5('registration-' . $userlogin . $userreg . NONCE_SALT);

				//We hash our password
				$userpass = $db->hash_password($userpass, $nonce);

				//Recompile our $value array to insert into the database
				$values = array(
							'u_name' => $username,
							'u_username' => $userlogin,
							'u_password' => $userpass,
							'u_email' => $useremail,
							'u_phone' => $userphone,
							'u_regdate' => $userreg,
							'u_info' => $userinfo,
							'u_privileges' => 0,
							'u_role' => $userrole,
							'u_department' => $userdepartment,
							'u_active' =>$userprivileges
						);

				//And, we insert our data
				$sql2 = $db->makeInsertSql('users', $values);
				$insert = $db->myQuery($sql2);
				// logging registration event
				$res = ($db->myQuery("SELECT u_id FROM users WHERE u_username = '$userlogin'"))->fetch_assoc();
				$ti = $mt->getMyTime(1);
				$ms = "$userlogin <b>REGISTERED</b> at $ti";

				if(LOG_)$db->logDB($ms, $res['u_id'], 8, $sql2); // logging the registration

				if ( $insert == TRUE ) {
					$dev = $ms."--------------<b>end of function register</b>--------------<br>";
					return array('Registration successful.',$username, $dev);
				}
			} else {
				die('Submission from wrong page. Please contact administrator. </br>current:'.$current." referer:$referrer");
			}
		}
	}
/** Logs in user
 * @param class myDB object
 * @return array, 0 is status, either 'empty','invalid' or 'loggedin'.
 *         1 is a message from that function. 3 is urername or empty
 */
	function login($db) {
		$b = '<br/>';
		$msg = ''; $out = array();

		if(!empty($_POST )){
			// print_r($_POST);
			// The username and password submitted by the user
			$subname = $_POST['l_username'];
			$subpass = $_POST['l_password'];

			$msg .= 'subname: '.$subname.$b;
			$msg .= 'subpass: '.$subpass.$b;

			if($subname == '' || $subpass == ''){   // ---- test
				$msg .= $b.' empty '.$b;
				$out[1] = $msg;
				$out[0] = 'empty';
				return 'empty';
			}
			//The name of the table we want to select data from
			//$table = 'users';

			/*
			 * Run our query to get all data from the users table where the user
			 * login matches the submitted login.
			 */
			$sql = "select * from users where u_username = '" . $subname . "'";
			$results = $db->select($sql);

			//Kill the script if the submitted username doesn't exit
			if (!$results) {
				die('Sorry, that username does not exist!');
			}

			//Fetching results into an associative array
			$results = $results->fetch_assoc();

			//The registration date of the user
			$storeg = $results['u_regdate'];

			//The hashed password of the user
			$stopass = $results['u_password'];

			//Recreate our NONCE used at registration
			$nonce = md5('registration-' . $subname . $storeg . NONCE_SALT);

			//Rehash the submitted password to see if it matches the stored hash
			$subpass = $db->hash_password($subpass, $nonce);

			//Check to see if the submitted password matches the stored password
			$msg .= ' subpass: '.$subpass.$b;
			$msg .=  ' stopass: '.$stopass.$b;
			if($subpass == $stopass){
				// $_SESSION['auth'] = true;
				$msg .=  'you are logged in !! ';
				//If there's a match, we rehash password to store in a cookie
				$authnonce = md5('cookie-' . $subname . $storeg . AUTH_SALT);
				$authID = $db->hash_password($subpass, $authnonce);

				//Set our authorization cookie
				setcookie('catering[user]', $subname, 0, '', '', '', true);
				setcookie('catering[authID]', $authID, 0, '', '', '', true);
				// logging successful login of a user
				$ti = myTime::getMyTime(1);
				$ms = "$subname logged <b>IN</b> on $ti";
				if(LOG_)$db->logDB($ms, $results['u_id'], 6, $sql); // user has logged in
				return array('loggedin', $msg, $subname);
			} else {
				return array('invalid', $msg, '');
			}
		} else {
			return array('empty', $msg, '');
		}
	}
/** logs ures out
 * @param int user_id database primary key
 * @param class myDB database object
 * @return returns either true or false depends on the status of the operation
 */
	function logout($user_id, $db) {
		$username = $_COOKIE['catering']['user'];
		//Expire our auth coookie to log the user out
		$idout = setcookie('catering[authID]', '', -3600, '', '', '', true);
		$userout = setcookie('catering[user]', '', -3600, '', '', '', true);

		if ( $idout == true && $userout == true ) {
			// logging successful logout of a user
			$ti = myTime::getMyTime(1);
			$ms = "$username logged <b>OUT</b> on $ti";
			if(LOG_)$db->logDB($ms, $user_id, 7, '$sql no query'); // loggin the loged  out event
			return true;
		} else {
			return false;
		}
	}
/** Checks if user is logged in
 * @param class myDB database object
 * @return array where 0 is status, 1 is message, 2 is user privilages, 3 user id
 */
	function checkLogin($db) {
		$user='';
		$authID='';
		$msg = '';
		$results = false;
		$out = array('','',0,1);

		//Grab our authorization cookie array
		if(isset($_COOKIE['catering']) ){
			$cookie = $_COOKIE['catering'];
			if(isset($cookie['user']) && isset($cookie['authID'])){
				//Set our user and authID variables
				$user = $cookie['user'];
				$authID = $cookie['authID'];
			}else {
				$msg .= 'user not set in catering cookie<br>';
				$out[0] = 'not';
				$out[1] = $msg;
				return $out;
			}
		}else {
			$msg .= 'no catering cookie detected';
			$out[0] = 'not';
			$out[1] = $msg;
			return $out;
		}
		/*
		 * If the cookie values are empty, we stop carry on login check;
		 * otherwise, we run the login check.
		 */
		if ( !empty ( $cookie ) ) {

			//Query the database for the selected user
			$table = 'users';
			$sql = "SELECT * FROM $table WHERE u_username = '" . $user . "'";
			$results = $db->select($sql);

			//Kill the script if the submitted username doesn't exit
			if (!$results) {
				die('Sorry, that username does not exist!');
			}

			//Fetch our results into an associative array
			$results = mysqli_fetch_assoc( $results );
			// extractin privilages level
			$u_privileges = $results['u_privileges'];
			//The registration date of the stored matching user
			$storeg = $results['u_regdate'];

			//The hashed password of the stored matching user
				$stopass = $results['u_password'];

			//Rehash password to see if it matches the value stored in the cookie
			$authnonce = md5('cookie-' . $user . $storeg . AUTH_SALT);
			$stopass = $db->hash_password($stopass, $authnonce);

			if ( $stopass == $authID ) {
				// $_SESSION['auth'] = true;
				$out[3] = $results["u_id"];
				$out[2] = $u_privileges;
				$out[1] = 'logged';
				$out[0] = 'logged';
			} else {
				// $_SESSION['auth'] = false;
				$results = 'not';
				$out[0] = 'not';
			}
		} else {
			// $_SESSION['auth'] = false;
			$results = 'not';
			$out[0] = 'not';
		}
		$out[1] = $msg;
		return $out;
	}
	/* redirect to a given page
	 */
	function redirect($page){
		$domen =  strstr($_SERVER['REQUEST_URI'],"index", TRUE);
		$s = ((!empty($_SERVER['HTTPS'])) ? "s" : "");
		header("Location: http".$s."://".$_SERVER['SERVER_NAME'].$domen.$page);//"index.php?page=login" );
	}
	/** redirect back to login page if user not logged in
	 * CURRENTLY NOT IN USE !!!
	 */
	function loginEnforce($red){
		$logged = self::checkLogin();
		if($red){
			if($logged[0] == 'not') self::redirect("index.php?page=login");
		}
	}
	/** changes user password
	* @param userId user id
	* @param username user name
	* @param newpass new Password
	* @param userreg user registration date
	* @param index for accociative array used to sanitiseInput
	* @return array where 0 is status and 1 is message
	*/
	function changePassword($userId, $username, $newpass, $userreg,$index, $db){
		$out = array();
		$clean = array();
		$clean[$index] = $newpass;
		$clean = sanitiseInput($clean, array('',$index),array(0,1));
		if($clean[0]==false){
			$nonce = md5('registration-'.$username.$userreg.NONCE_SALT);
			$userpass = $db->hash_password($newpass, $nonce);
			$sql3 = 'UPDATE users SET u_password = "'.$userpass.'" WHERE u_id = '.$userId;
			$results = $db->myQuery($sql3);
			$out[0] = true; $out[1] = 'password change successful';
		 }else {
			$out[1] = false;  $out[1] = 'not clean !!!!<br/>';
			$out[1] .= $clean[0];
			$out[1] .= $lang[$clean[0]];
			$out[1] .= $clean[1];
		}
		return $out;
	}
}
?>
