<?php

	require_once("_inc/sessionClass.php");
	require_once("_inc/userClass.php");

	if (!$session->is_logged_in()) {
		header("location: login.php");
	}
	
	if ($_SESSION['user_type'] !== "sadmin" && $_SESSION['user_type'] !== "stocksadmin" && $_SESSION['user_type'] !== "analyst") {
		header("location: index.php");
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>RAViN POS - SYSTEM</title>
	<link href="_css/content.css" rel="stylesheet" type="text/css"/>
<body class="bgSelWinLogin">
	<div id="topMenu">
		<a href="index.php" class="topLink"><img src="_img/btn-back2.png"><span>Back</span></a>
	</div>
	<img title="RAViN Logo" src="_img/logo.png" style="display: block; margin: 30px auto;">
	<ul id="selectWindow">
		<img title="RAViN Logo" id="rvnLogo" src="_img/logo.png">
		<?php if ($_SESSION['user_type'] == "sadmin"): ?>
		<li><a href="receivingBox.php"><img src="_img/btn-return.png"><br><br>Receiving</a></li>
		<li><a href="specialSales.php"><img src="_img/btn-sales.png"><br><br>Special Sales</a></li>
		<!-- <li><a href="returnBoxFull.php"><img src="_img/btn-return.png"><br><br>Return Full</a></li> -->
		<li><a href="specialReturn.php"><img src="_img/btn-return.png"><br><br>Special Return</a></li>
		<!-- <li><a href="setDate.php"><img src="_img/btn-calendar.png"><br><br>Change Date</a></li> -->
		<li><a href="itemList.php"><img src="_img/btn-item-list.png"><br><br>Item List</a></li>
		<li><a href="merchandise.php"><img src="_img/btn-merchandise.png"><br><br>Merchandise</a></li>
		<li><a href="specialTransfer.php"><img src="_img/btn-transfer.png"><br><br>Special Transfer</a></li>
		<li><a href="transferLogReport.php"><img src="_img/btn-transfer-log-report.png"><br><br>Transfer Log</a></li>
		<li><a href="manageVouchers.php"><img src="_img/btn-transfer-log-report.png"><br><br>Manage Vouchers</a></li>
		<li><a href="costReport.php"><img src="_img/btn-transfer-log-report.png"><br><br>Cost Report</a></li>
		<li><a href="manageLocations.php"><img src="_img/btn-manage-locations.png"><br><br>Manage Locations</a></li>
		<li><a href="manageTarget.php"><img src="_img/btn-manage-target.png"><br><br>Manage Target</a></li>
		<?php endif; ?>
		<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "analyst"): ?>
		<li><a href="selectBranch.php"><img src="_img/btn-change-location.png"><br><br>Change Location</a></li>
		<li><a href="storesSalesReport.php"><img src="_img/btn-stores-sales-report.png"><br><br>Stores Sales Report</a></li>
		<?php endif; ?>
		<?php if ($_SESSION['user_type'] == "stockadmin" || $_SESSION['user_type'] == "stocksadmin"): ?>
		<!-- <li><a href="userManager.php"><img src="_img/btn-user-manager.png"><br><br>User<br>Manager</a></li> -->
		<!-- <li><a href="qtySwap.php" target="_blank"><img src="_img/btn-qty-swap.png"><br><br><br>Qty Swap</a></li> -->
		<li><a href="addItemLocationAdmin.php" target="_blank"><img src="_img/btn-change-item-location.png"><br><br>Add Item<br>Location</a></li>
		<li><a href="searchItemLocation.php" target="_blank"><img src="_img/btn-search.png"><br><br>Search for<br>Item Location</a></li>
		<li><a href="duplicatesReport.php" target="_blank"><img src="_img/btn-duplicates-report.png"><br><br>Duplicates<br>Report</a></li>
		<?php // elseif ($_SESSION['user_type'] == "stocksadmin"): ?>
		<!-- <li><a href="selectBranch.php" target="_blank"><img src="_img/btn-change-location.png"><br><br>Change<br>Location (F8)</a></li> -->
		<?php endif; ?>
	</ul>
	<script src="_js/jquery.min.js"></script>
</body>
</html>