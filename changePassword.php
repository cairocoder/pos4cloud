<?php

	require_once("_inc/sessionClass.php");
	require_once("_inc/userClass.php");

	$allBrances = userClass::getAllBranches();

	if (!$session->is_logged_in())
	{
		header("location: login.php");
	}
	//var_dump($_SESSION);

	if (!empty($_POST['txtPassword']))
	{
		$userId      = $_SESSION['user_id'];
		$txtPassword = $_POST['txtPassword'];
		$txtPassword = md5($txtPassword);
		$db->query("UPDATE users SET `password` = '{$txtPassword}'
					WHERE user_id = '{$userId}'");
		header("location: index.php");
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>RAViN POS - SYSTEM</title>
	<link href="_css/content.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div id="topMenu">
		<a href="index.php" class="topLink"><img src="_img/btn-back2.png"><span>Back</span></a>
	</div>
	<form name="frmChngPass" id="frmChngPass" method="post" action="">
		<img title="RAViN Logo" src="_img/logo.png" id="rvnLogo">
		<label>Enter the New Password:
			<input type="password" name="txtPassword" "txtPassword"></label>
		<input type="submit" name="submitPassword" id="submitPassword" value="Update">
	</form>
</body>
</html>