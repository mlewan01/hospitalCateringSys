<?php
  displayErroros();
  if(isset($_POST['l_savepass'])){
    $newpass = $_POST['l2_password'];
    // $newpass =
    $token = $_POST['token'];

    $sql = "SELECT * from users WHERE u_passrec = '".$token."'";
    //$sql1 = 'UPDATE users SET u_passrec = "0" WHERE u_passrec = '.$token;
    //$sql2 = 'UPDATE users SET u_password = "'.$newpass.'" WHERE u_passrec = "'.$token.'"';
    $res = $db->myQuery($sql);
    $re = mysqli_fetch_assoc($res);
    if (!$re) {
      $content .= 'User does not exist. Pleas try password recovery again.'.$b;
      // die("$user,  Sorry, that username does not exist!");
    }else{

      $content .= $l->changePassword($re['u_id'],$re['u_username'],$newpass,$re['u_regdate'],'newpas', $db);
      $content .= 'Password has been changed for: '.$re['u_username'].$b;

      $sql = 'UPDATE users SET u_passrec = "0" WHERE u_id = '.$re['u_id'];
      $result = $db->myQuery($sql);
    }
  }elseif(isset($_GET['token'])){
    $token = $_GET['token'];

    $content .= '<form enctype="multipart/form-data" action="index.php?page=passrec" method="post">';
    $content .= '<fieldset>';
    $content .= '<legend>Set new password</legend>';
    $content .= '<label for="l2_password">New password:</label>';
//     $content .= '<input type="text" id="l2_password" name="l2_password" />';
    $content .= '<input type="password" name="l2_password" id="l2_password" pattern=
"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="capital & normal letter + digit,
min length 8" required aria-required="true" aria-labbelledby="l2_password"/>';

    $content .= '<input type="hidden" value='.$token.' name="token">';
    $content .= '<input type="submit" value="Save" name="l_savepass">';
    $content .= '</fieldset></form>';

  }elseif(isset($_POST['l_recover'])){
    if(isset($_POST['l2_username'])){
      $username = $_POST['l2_username'];
      if($username == ''){
        $content .= "usernme not provided, pls try again".$b;
      }else{
        $content .= "provided username: ".$username.$b;
        $sql = "SELECT * from users WHERE u_username = '".$username."'";
        $result = $db->myQuery($sql);
        $result = mysqli_fetch_assoc($result);
        if (!$result) {
    		  $content .= 'user does not exist pleas try again'.$b;
    			// die("$user,  Sorry, that username does not exist!");
    		}else{
          $content .= 'your email is: '.$result['u_email'].$b;
          $passrec = md5('passrecovery-'.$username.(time() - 111).PASSREC_SALT);

          $sql = 'UPDATE users SET u_passrec = "'.$passrec.'" WHERE u_id = '.$result['u_id'];
          $result = $db->myQuery($sql);

          $domen =  strstr($_SERVER['REQUEST_URI'],"index", TRUE);
      		$s = ((!empty($_SERVER['HTTPS'])) ? "s" : "");
          $page = "index.php?page=passrec&token=".$passrec;
      		$header = "http".$s."://".$_SERVER['SERVER_NAME'].$domen.$page;
          $content .= 'Plese check your email for instructions.<br>';

          $to      = 'le.wy@op.pl'; //$result['u_email'];
          $subject = 'Passwor reset for hospital system';
          $message = 'hello! Please follow the link below in order reset your password: ';
          $message .= $header;
          $headers  = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          $headers = 'From: webmaster@hospital.com' . "\r\n";
          mail($to, $subject, $message, $headers);
        }
      }
    }else{
      $content.= "something went wrong, try agains pleas".$b;
    }
  }else{
    $content .= '<form enctype="multipart/form-data" action="index.php?page=passrec" method="post">';
    $content .= '<fieldset>';
    $content .= '<legend>Request new password</legend>';
    $content .= '<label for="l2_username">Username:</label>';
    $content .= '<input type="text" id="l2_username" name="l2_username" />';
    $content .= '<input type="submit" value="Recover" name="l_recover">';
    $content .= '</fieldset></form>';
  }
?>
