<?php

	require_once("_inc/sessionClass.php");
	require_once("_inc/userClass.php");

	if(!$session->is_logged_in()) {
		header("location: login.php");
	} elseif ($session->is_logged_in() && $_SESSION['user_type'] !== "stockadmin") {
		header("location: index.php");
	}
	//var_dump($_SESSION);

?>
<!DOCTYPE html>
<html>
<head>
	<title>RAViN POS - SYSTEM</title>
	<link href="_css/content.css" rel="stylesheet" type="text/css"/>
<body class="bgSelWinLogin">
	<a href="index.php" class="topLink"><img src="_img/back.png"><span>Back</span></a>
	<ul id="selectWindowAdmin">
		<img title="RAViN Logo" id="rvnLogo" src="_img/logo.png">
		<li><a href="userManager.php"><img src="_img/btn-user-manager.png"><br><br>User<br>Manager (F2)</a></li>
		<li><a href="qtySwap.php"><img src="_img/btn-qty-swap.png"><br><br><br>Qty Swap (F3)</a></li>
		<li><a href="changeItemLocation.php"><img src="_img/btn-change-item-location.png"><br><br>Change Item<br>Location (F4)</a></li>
		<li><a href="search.php"><img src="_img/btn-search.png"><br><br><br>Search (F6)</a></li>
		<li><a href="duplicatesReport.php"><img src="_img/btn-duplicates-report.png"><br><br>Duplicates<br>Report (F7)</a></li>
		<?php if($_SESSION['user_type'] == "stocksadmin"): ?>
		<li><a href="selectBranch.php"><img src="_img/btn-change-location.png"><br><br>Change<br>Location (F8)</a></li>
		<?php endif; ?>
	</ul>
	<script src="_js/jquery.min.js"></script>
	<script type="text/javascript">
		$('body').bind('keydown', function(e) {
			if(e.keyCode == 113){
				e.preventDefault();
				window.location.href = "userManager.php";
			}
			if(e.keyCode == 114){
				e.preventDefault();
				window.location.href = "qtySwap.php";
			}
			if(e.keyCode == 115){
				e.preventDefault();
				window.location.href = "changeItemLocation.php";
			}
			if(e.keyCode == 117){
				e.preventDefault();
				window.location.href = "search.php";
			}
			if(e.keyCode == 118){
				e.preventDefault();
				window.location.href = "duplicatesReport.php";
			}
		});
	</script>
	<script type="text/javascript">
		$('body').bind('keydown', function(e) {
			if(e.keyCode == 119){
				e.preventDefault();
				window.location.href = "selectBranch.php";
			}
		}
	</script>
</body>
</html>