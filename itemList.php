<?php

	require_once("_inc/sessionClass.php");
	require_once("_inc/userClass.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

	if (!$session->is_logged_in()) {
	  header("location: login.php");
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>RAViN POS - SYSTEM</title>
	<link href="_css/content.css" rel="stylesheet" type="text/css"/>
</head>
<body class="bgSelWinLogin">
	<div id="loadingWrapper"><img src="_img/ajax-loader.gif" id="loading"></div>
	<div id="topMenu">
		<a href="index.php" class="topLink"><img src="_img/btn-back2.png"><span>Back</span></a>
	</div>
	<img title="RAViN Logo" src="_img/logo.png" style="display: block; margin: 30px auto;">
	<ul id="selectWindow">
		<li><a href="#" id="getItemList"><img src="_img/btn-add-item.png"><br><br>Get Item List</a></li>
		<li><a href="addItem.php" target="_blank"><img src="_img/btn-add-item.png"><br><br>Add Item</a></li>
		<li><a href="itemsUpload.php" target="_blank"><img src="_img/btn-add-item.png"><br><br>Upload Item List</a></li>
		<li><a href="addItemFull.php" target="_blank"><img src="_img/btn-add-item.png"><br><br>Add Item Full</a></li>
		<li><a href="editItem.php" target="_blank"><img src="_img/btn-edit-item.png"><br><br>Edit Item</a></li>
		<li><a href="bulkItemEdit.php" target="_blank"><img src="_img/btn-edit-item.png"><br><br>Bulk Item Edit</a></li>
		<li><a href="barcode.php" target="_blank"><img src="_img/btn-edit-item.png"><br><br>Barcode Printing</a></li>
		<li><a href="addDept.php" target="_blank"><img src="_img/btn-add-new.png"><br><br>Add Department</a></li>
		<li><a href="addVend.php" target="_blank"><img src="_img/btn-add-new.png"><br><br>Add Vendor</a></li>
		<li><a href="addSubDept.php" target="_blank"><img src="_img/btn-add-new.png"><br><br>Add Sub Department</a></li>
		<li><a href="addAttr.php" target="_blank"><img src="_img/btn-add-new.png"><br><br>Add Attribute</a></li>
		<li><a href="addSeason.php" target="_blank"><img src="_img/btn-add-new.png"><br><br>Add Season</a></li>
		<li><a href="addDesc.php" target="_blank"><img src="_img/btn-add-new.png"><br><br>Add Description</a></li>
		<li><a href="addColor.php" target="_blank"><img src="_img/btn-add-new.png"><br><br>Add Color</a></li>
		<li><a href="addIntlCode.php" target="_blank"><img src="_img/btn-add-new.png"><br><br>Add Intl Code</a></li>
	</ul>
	<script type="text/javascript" src="_js/jquery.min.js"></script>
	<script type="text/javascript">
		$('#getItemList').click(function(e) {
			$.ajax({
				url: "_inc/ajaxGetItemList.php",
				type: "post",
				data: {"action":"getItemList"}
			}).done(function(data) {
				document.location.href = data;
			});
			e.preventDefault();
		});
	</script>
</body>
</html>