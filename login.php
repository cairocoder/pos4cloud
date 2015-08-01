<?php

require_once("_inc/sessionClass.php");
require_once("_inc/dbClass.php");
require_once("_inc/userClass.php");
require_once("_inc/class.phpmailer.php");
require_once("_inc/class.smtp.php");

$eMail = new PHPMailer();

if($session->is_logged_in()) {
  header("location: index.php");
}

if(isset($_GET['action']))
{
	$session->logout();
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (isset($_POST['submitLogin']))
{ // Form has been submitted.

	$email    = trim($_POST['txtEmail']);
	$password = trim($_POST['txtPass']);
	$password = md5($password);

	// Check database to see if email/password exist.
	$found_user = userClass::authenticate($email, $password);

	if ($found_user)
	{
		$session->login($found_user);
		header("location: index.php");
	} else {
		// email/password combo was not found in the database
		$message = "Username/password combination incorrect.";
	}
  
} elseif (isset($_POST['submitPassword'])) {
	$email = test_input($_POST["txtEmail"]);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$message = "Invalid email format!";
	} else {
		$chkMail  = $db->query("SELECT `email`, `user_id` FROM `users`
								WHERE `email` = '{$email}'
								LIMIT 1");
		if ($db->num_rows($chkMail) > 0) {
			$getData = $db->fetch_array($chkMail);
			//send mail to activate the account
		    //$message = "Your new password is:\r\n" . $newPassOrign;
		    $link = 'http://www.pos4cloud.com/pos4cloud/login.php?userId=' . $getData['user_id'];
		    $message = "Please click the link below to confirm changing your password:\r\n" . $link;
			$eMail->IsSMTP();                     // set mailer to use SMTP
			$eMail->Host     = "ira.iravin.com";  // specify main and backup server
			$eMail->SMTPSecure = 'ssl';
			$eMail->SMTPDebug = 1;
			$eMail->Port     = 465;
			$eMail->SMTPAuth = true;     // turn on SMTP authentication
			$eMail->Username = "info@iravin.com";  // SMTP username
			$eMail->Password = "passme123"; // SMTP password
			$eMail->From     = "info@iravin.com";
			$eMail->AddAddress($email);
			$eMail->WordWrap = 50; 
			$eMail->Subject  = "Confirm Changing Password!";
		 	$eMail->Body     = $message;
		 	$eMail->Send();
			$message = "Confirmation mail sent, please check back your email.";
		} else {
			$message = "Email not exist!";
		}
	}
} elseif (isset($_GET['userId'])) {
	//email validation
	$userId   = $_GET['userId'];
	$chkMail  = $db->query("SELECT `email` FROM `users`
							WHERE `user_id` = '{$userId}'
							LIMIT 1");
	if ($db->num_rows($chkMail) > 0) {
		$getData      = $db->fetch_array($chkMail);
		$email        = $getData['email'];
		$newPassOrign = generateRandomString();
		$newPassMD5   = md5($newPassOrign);
		$db->query("UPDATE `users` SET `password` = '{$newPassMD5}'
					WHERE `user_id` = '{$userId}'");
	    //send mail to activate the account
	    $message = "Your new password is:\r\n" . $newPassOrign;
		$eMail->IsSMTP();                     // set mailer to use SMTP
		$eMail->Host     = "ira.iravin.com";  // specify main and backup server
		$eMail->SMTPSecure = 'ssl';
		$eMail->SMTPDebug = 1;
		$eMail->Port     = 465;
		$eMail->SMTPAuth = true;     // turn on SMTP authentication
		$eMail->Username = "info@iravin.com";  // SMTP username
		$eMail->Password = "passme123"; // SMTP password
		$eMail->From     = "info@iravin.com";
		$eMail->AddAddress($email);
		$eMail->WordWrap = 50; 
		$eMail->Subject  = "Password Changed!";
	 	$eMail->Body     = $message;
	 	$eMail->Send();
		$message = "Password changed, please check back your email.";
	} else {
		$message = "Email not exist!";
	}
} else { // Form has not been submitted.
	$email = "";
	$password = "";
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>RAViN POS - SYSTEM</title>
	<link href="_css/content.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<form name="frmLogin" id="frmLogin" method="post" action=""  enctype="multipart/form-data">
		<img title="RAViN Logo" src="_img/logo.png" id="rvnLogo">
		<p class="clear message"><?php if(isset($message)) echo $message; ?></p><br>
		<table id="tblLogin">
			<tbody>
				<tr>
					<td><label for="txtEmail">Email: </label></td>
					<td><input type="email" name="txtEmail" id="txtEmail" value="<?php echo htmlentities($email); ?>"></td>
				</tr>
				<tr>
					<td><label for="txtPass">Password: </label></td>
					<td><input type="password" name="txtPass" id="txtPass" value="<?php echo htmlentities($password); ?>"></td>
				</tr>
				<tr>
					<td><input type="submit" name="submitLogin" id="submitLogin" value="Login"></td>
					<td><input type="submit" name="submitPassword" id="submitPassword" value="Get new password"></td>
				</tr>
			</tbody>
		</table>
	</form>
	<script type="text/javascript">
		document.getElementById("txtEmail").focus();
	</script>
</body>
</html>