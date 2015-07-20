<?php

require_once("_inc/sessionClass.php");
require_once("_inc/dbClass.php");
require_once("_inc/userClass.php");

if($session->is_logged_in()) {
  header("location: index.php");
}

if(isset($_GET['action']))
{
	$session->logout();
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
					<td><input type="text" name="txtEmail" id="txtEmail" value="<?php echo htmlentities($email); ?>"></td>
				</tr>
				<tr>
					<td><label for="txtPass">Password: </label></td>
					<td><input type="password" name="txtPass" id="txtPass" value="<?php echo htmlentities($password); ?>"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="submitLogin" id="submitLogin" value="Login"></td>
				</tr>
			</tbody>
		</table>
	</form>
	<script type="text/javascript">
		document.getElementById("txtEmail").focus();
	</script>
</body>
</html>