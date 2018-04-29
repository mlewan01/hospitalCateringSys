<?php
	displayErroros(); // error output
	// variable inicialisation
	$tmp = array('','');
	$user='';
	$cookie='';
	$checkLoginOut = array();
	$checkLoginOut = $l->checkLogin($db);
	$logged = $checkLoginOut[0];
	$dev .= $checkLoginOut[1];
	// if user logs in
	if(isset($_POST['l_login'])){
		if($_POST['l_username']==''){ // if username not provided
			$content .= 'Username not provided, pls try again !';
			$logged = 'not';
		}else{ // username provided
			$dev .=' trying to Login '.$b;
			$tmp = $l->login($db); // trying to login
			if($tmp[0] == 'loggedin'){ // if loggedin
				$logged = 'logged';
				$loginCheck = 'loggedIn';
				$authLevel = $checkLoginOut[2];
			}else{ // if usernaee or password were incorrect
				$logged = 'not';
				$content .= 'Username or password incorrect';
			}
		}
	}elseif(isset($_POST['l_logout'])){ // user logs out

		$dev .= ' loging out '.$b;
		$l->logout($user_id, $db);
		$logged = 'not';
		$loginCheck = 'loggedOut';

	}elseif(isset($_POST['l_register'])){ // user regiser

		$logged = 'reg';

	}elseif(isset($_POST['l_registered'])){
		$tmp = $l->register($db, $mt);   $dev .= $tmp[2];  // registering and collecting devout
		if($tmp[0] == 'reg'){
			$content .= "username already exists, try different one";
			$logged = 'reg';
		}
		$dev .= ' registered '.$tmp[0].' '.$tmp[1].$b;
	}elseif(isset($_POST['l_passRecovery'])){
		$l->redirect('index.php?page=passrec');
	}
	if($logged == 'not'){ // if not logged in display login form
		$content .= '<form enctype="multipart/form-data" action="index.php?page=login" method="post" role="form">';
		$content .= '<fieldset>';
		$content .= '<legend>Log in</legend>';
		$content .= '<div class="controlgroup"><label for="l_username">Username:</label>';
		$content .= '<input type="text" id="l_username" name="l_username" /></div>';
		$content .= '<div class="controlgroup"><label for="l_password">Password:</label>';
		$content .= '<input type="password" id="l_password" name="l_password" /></div>';
		$content .= '<input type="submit" value="Login" name="l_login">';
		$content .= '<input type="submit" value="Register" name="l_register">';
		$content .= '<input type="submit" value="Password Recovery" name="l_passRecovery">';
		$content .= '</fieldset></form>';

	}elseif($logged == 'logged'){

		//Grab our authorization cookie array
		if(isset($_COOKIE['catering']) ){
			$cookie = $_COOKIE['catering'];

			if(isset($cookie['user'])){
				//Set our user and authID variables
				$dev .= 'cookie not empty'.$b;
				$user = $cookie['user'];
			}
		}
		if($user == ''){
			//Set our user and variable
			$dev .= 'cookie empty'.$b;
			$user = $tmp[2];
		}
		//Query the database for the selected user
		$table = 'users'; //DB_TABLE_USERS;
		$sql = "SELECT * FROM $table WHERE u_username = '" . $user . "'";
		$re = $db->select($sql);

		//Kill the script if the submitted username doesn't exit
		if (!$re) {
			die("$user,  that username does not exist, sorry!");
		}

		//Fetch our results into an associative array
		$re = mysqli_fetch_assoc( $re );

		$content .= '<div class="info">';
		$content .= 'name: '.$re['u_name'].$b;
		$content .= 'user: '.$re['u_username'].$b;
		$content .= 'email: '.$re['u_email'].$b;
		$content .= 'authLevel: '.$re['u_privileges'].$b.'</div>';
		$authLevel = $re['u_privileges'];
		if(isset($_POST['l_chngpass'])){
			$l->changePassword($re['u_id'],$re['u_username'],$_POST['l_newpass'],$re['u_regdate'],'l_newpass', $db);
		}
		// chanege password form
		$content .= '<form enctype="multipart/form-data" action="index.php?page=login" method="post">';
		$content .= '<fieldset>';
		$content .= '<legend>change password</legend>';
		$content .= '<input type="text" id="l_newpass" name="l_newpass" />';
		$content .= '<input type="submit" value="change password" name="l_chngpass">';
		$content .= '</fieldset></form>';
		// logout form
		$content .= '<form enctype="multipart/form-data" action="index.php?page=login" method="post">';
		$content .= '<fieldset>';
		$content .= '<legend>Log out</legend>';
		$content .= '<input type="submit" value="Logout" name="l_logout">';
		$content .= '</fieldset></form>';
	}elseif($logged == 'reg'){
		// getting the registration form
		$out=file_get_contents('./templates/reg_form.html');
		$content .= $out;
	}
?>
