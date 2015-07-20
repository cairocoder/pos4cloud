<?php

	require_once("_inc/sessionClass.php");
	require_once("_inc/userClass.php");

	$allBrances = userClass::getAllBranches();

	if (!$session->is_logged_in())
	{
	  header("location: login.php");
	} elseif ($session->is_logged_in() && $_SESSION['user_type'] !== "sadmin" && $_SESSION['user_type'] !== "analyst") {
		header("location: index.php");
	}
	//var_dump($_SESSION);

	if (!empty($_POST['branchId']))
	{
		$branchId = $_POST['branchId'];
		$_SESSION['loc_id'] = $branchId;
		header("location: admin.php");
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
	<form name="frmSelectBranch" id="frmSelectBranch" method="post" action="">
		<img title="RAViN Logo" src="_img/logo.png" id="rvnLogo">
		<label>Select Branch<br><br><select name="branchId">
			<option value="">Select ..</option>
			<?php foreach ($allBrances as $key => $value): ?>
				<?php
					$selected = "";
					if ($key == $_SESSION['loc_id']) $selected = 'selected="selected"';
				?>
				<option value="<?php echo $key ?>" <?php echo $selected; ?>><?php echo $value ?></option>
			<?php endforeach; ?>
		</select></label>
		<input type="submit" name="submitBranch" id="submitBranch" value="Select">
	</form>
</body>
</html>