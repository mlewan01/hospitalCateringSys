<?php
class myLogin {

	function register() {

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

		 echo ' referer: '.$referrer.$b;
		 echo ' current: '.$current.$b;

		if ( !empty ( $_POST ) ) {

			/*
			 * Here we actually run the check to see if the form was submitted from our
			 * site. Our registration from submits to itself, if
			 * the form submission didn't come from the register.php page on our server,
			 * we don't allow the data through.
			 */
			if ( $referrer == $current ) {

				echo ' same referrer and current page'.$b;
				// checking if the usrename already exists
				$userlogin = $_POST['username'];

				//Require our database class
				$db = new myDB();

				$sql1 = "Select * from users where u_username = '$userlogin'";
				$result1 = $db->myQuery($sql1);
				$result = $result1->fetch_assoc();
				if($userlogin == $result['u_username']){
					echo 'username exists'.$b;
					echo 'submited: '.$userlogin.' existing: '.$result['u_username'].$b;
					return 'reg';
				}
				//Set up the variables we'll need to pass to our insert method

				//These are the fields in that table that we want to insert data into
				$fields = array('name', 'username', 'password', 'email', 'regdate', 'info', 'privileges', 'title');

				//These are the values from our registration form... cleaned using our clean method
			// $values = $cdb->clean($_POST);   // produces errors, change to msqli due to php 7 upgrade
				$values = $_POST;

				//Now, we're breaking apart our $_POST array, so we can storely our password securely
				$username = $_POST['name'];
				$userlogin = $_POST['username'];
				$userpass = $_POST['password'];
				$useremail = $_POST['email'];
				$userreg =( new myTime())-> getMyTime();   // $_POST['date'];
				$userinfo = $_POST['info'];
				$userprivileges = $_POST['privileges'];
				$usertitle = $_POST['title'];

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
							'u_regdate' => $userreg,
							'u_info' => $userinfo,
							'u_privileges' => $userprivileges
						);

				//And, we insert our data
				$sql2 = $db->makeInsertSql('users', $values);
				$insert = $db->myQuery($sql2);

				if ( $insert == TRUE ) {
						return array('Registration successful.',$username);
				}
			} else {
				die('Your form submission did not come from the correct page. Please check with the site administrator.');
			}
		}
	}

	function login() {
		$b = '<br/>';
		$msg = ''; $out = array();
		$db = new myDB();

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

			//Fetch our results into an associative array
			$results = mysqli_fetch_assoc( $results );

			//The registration date of the stored matching user
			$storeg = $results['u_regdate'];

			//The hashed password of the stored matching user
			$stopass = $results['u_password'];

			//Recreate our NONCE used at registration
			$nonce = md5('registration-' . $subname . $storeg . NONCE_SALT);

			//Rehash the submitted password to see if it matches the stored hash
			$subpass = $db->hash_password($subpass, $nonce);

			//Check to see if the submitted password matches the stored password
			$msg .= ' subpass: '.$subpass.$b;
			$msg .=  ' stopass: '.$stopass.$b;
			if($subpass == $stopass){
				$msg .=  'you are logged in !! ';
				//If there's a match, we rehash password to store in a cookie
				$authnonce = md5('cookie-' . $subname . $storeg . AUTH_SALT);
				$authID = $db->hash_password($subpass, $authnonce);

				//Set our authorization cookie
				setcookie('catering[user]', $subname, 0, '', '', '', true);
				setcookie('catering[authID]', $authID, 0, '', '', '', true);

				return array('loggedin', $msg, $subname);
			} else {
				return array('invalid', $msg, '');
			}
		} else {
			return array('empty', $msg, '');
		}
	}

	function logout() {
		//Expire our auth coookie to log the user out
		$idout = setcookie('catering[authID]', '', -3600, '', '', '', true);
		$userout = setcookie('catering[user]', '', -3600, '', '', '', true);

		if ( $idout == true && $userout == true ) {
			return true;
		} else {
			return false;
		}
	}

	function checkLogin() {
		$user='';
		$authID='';
		$db = new myDB();
		$msg = '';
		$results = false;
		$out = array();

		//Grab our authorization cookie array
		if(isset($_COOKIE['catering']) ){
			$cookie = $_COOKIE['catering'];
			if(isset($cookie['user']) && isset($cookie['authID'])){
				//Set our user and authID variables
				$user = $cookie['user'];
				$authID = $cookie['authID'];
			}else {
				$msg .= 'user not set in catering cookie';
				$out[0] = 'not';
				$out[1] = $msg;
				return $out;
			}
		}else {
			$msg .= 'no catering cookie';
			$out[0] = 'not';
			$out[1] = $msg;
			return $out;
		}
		//echo 'autentication.... '.$user.'  '.$authID;

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

			//The registration date of the stored matching user
			$storeg = $results['u_regdate'];

			//The hashed password of the stored matching user
			$stopass = $results['u_password'];

			//Rehash password to see if it matches the value stored in the cookie
			$authnonce = md5('cookie-' . $user . $storeg . AUTH_SALT);
			$stopass = $db->hash_password($stopass, $authnonce);

			if ( $stopass == $authID ) {
				$results = 'logged';
				$out[0] = 'logged';
			} else {
				$results = 'not';
				$out[0] = 'not';
			}
		} else {
			$results = 'not';
			$out[0] = 'not';
		}
		$out[1] = $msg;
		// print_r($out);
		// return $results;
		return $out;
	}
	function redirect($page){
		$domen =  strstr($_SERVER['REQUEST_URI'],"index", TRUE);
		$s = ((!empty($_SERVER['HTTPS'])) ? "s" : "");
		header("Location: http".$s."://".$_SERVER['SERVER_NAME'].$domen.$page);//"index.php?page=login" );
	}
	function loginEnforce($red){
		// $l = new myLogin();
		// $logged = $l->checkLogin();
		$logged = self::checkLogin();
		if($red){
			if($logged[0] == 'not') self::redirect("index.php?page=login");
		}
	}
}
?>
