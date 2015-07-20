<?php
	require_once ("_inc/dbClass.php");
	require_once("_inc/userClass.php");
	require_once("_inc/sessionClass.php");

	if (!$session->is_logged_in()) {
	  header("location: login.php");
	}

	$locId  = $_SESSION['loc_id'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>RAViN POS - SYSTEM</title>
	<link href="_css/content.css" rel="stylesheet" type="text/css"/>
	<link href="_css/colorbox.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="_css/component.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="_css/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body class="bgSelWinLogin">
	<div id="loadingWrapper"><img src="_img/ajax-loader.gif" id="loading"></div>
	<div id="topMenu">
		<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "stocksadmin" || $_SESSION['user_type'] == "analyst"): ?>
		<a href="admin.php" class="topLink"><img src="_img/btn-admin.png"><span>Admin Area</span></a>
		<?php endif; ?>
		<?php switch ($_SESSION['job_id']) {
		case 3: ?>
				<a href="salesMDAM.php" class="topLink"><img src="_img/btn-dashboard.png"><span>Dashboard</span></a>
		<?php break;
		case 45: ?>
				<a href="salesMDRM.php" class="topLink"><img src="_img/btn-dashboard.png"><span>Dashboard</span></a>
		<?php break;
		case 46: ?>
				<a href="salesMDSD.php" class="topLink"><img src="_img/btn-dashboard.png"><span>Dashboard</span></a>
		<?php break;
		default: ?>
				<a href="salesMDBR.php" class="topLink"><img src="_img/btn-dashboard.png"><span>Dashboard</span></a>
		<?php break;
		} ?>
		<div id="notLinkWrapper">
			<?php
				$result   = $db->query("SELECT COUNT(`notify_id`) AS count
										FROM trans_notify
										WHERE trans_notify.to_wrhs_id = {$locId}
										AND trans_notify.date = CURDATE()");
				if (mysql_num_rows($result) > 0) {
					$row   = $db->fetch_array($result);
					$count = $row['count'];
				} else {
					$count = 0;
				}
			?>
			<a href="#" class="topLink"><img src="_img/btn-notify.png"><span>Notifications (<?php echo $count; ?>)</span></a>
			<div id="notBoxWrapper">
				<div id="downArrow"></div>
				<div id="notBox">
					<div id="notHeader">
						<p>Notifications</p>
					</div>	
					<?php
						$result   = $db->query("SELECT notify_types.desc, loc_from.desc AS loc_from,
												loc_to.desc AS loc_to, trans_notify.trans_no, trans_notify.date, trans_notify.time
												FROM trans_notify
												JOIN `notify_types`          ON trans_notify.notify_type_id = notify_types.notify_type_id
												JOIN `locations` AS loc_from ON loc_from.loc_id = trans_notify.from_wrhs_id
												JOIN `locations` AS loc_to   ON loc_to.loc_id = trans_notify.to_wrhs_id
												WHERE trans_notify.to_wrhs_id = {$locId}
												AND trans_notify.date = CURDATE()");
						$search = array("%loc%", "%trans%");
					?>
					<?php if (mysql_num_rows($result) > 0): ?>
						<?php while ($row = $db->fetch_array($result)): ?>
							<div class="oneNot">
								<?php $replace = array($row['loc_from'], $row['trans_no']) ?>
								<p><?php echo str_replace($search, $replace, $row['desc']) ?></p>
								<p><?php echo $row['date'] . " / " . $row['time'] ?></p>
							</div>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<a href="login.php?action=logout" class="topLink"><img src="_img/btn-logout2.png"><span>Logout</span></a>
	</div>
	<img title="RAViN Logo" src="_img/logo.png" style="display: block; margin: 30px auto;">
	<ul id="selectWindow">
		<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "sales"
		|| $_SESSION['user_type'] == "stockadmin" || $_SESSION['user_type'] == "stocksadmin"): ?>
		<li><a href="salesBox.php" target="_blank"><img src="_img/btn-sales.png"><br><br><br>Sales (F2)</a></li>
		<?php if ($_SESSION['loc_id'] == 44): ?>
		<li><a href="returnBox2.php" target="_blank"><img src="_img/btn-return.png"><br><br><br>Return (F3)</a></li>
		<?php else: ?>
		<li><a href="returnBox.php" target="_blank"><img src="_img/btn-return.png"><br><br><br>Return (F3)</a></li>
		<?php endif; ?>
		<li><a href="exchangeBox.php" target="_blank"><img src="_img/btn-exchange.png"><br><br><br>Exchange (F4)</a></li>
		<li><a href="transferBox.php" target="_blank"><img src="_img/btn-transfer.png"><br><br><br>Transfer (F6)</a></li>
		<?php endif; ?>
		<li><a href="eodReport.php" target="_blank"><img src="_img/btn-eod-report.png"><br><br>End of Day<br>Report</a></li>
		<li><a href="stockReport.php" target="_blank"><img src="_img/btn-stock-report.png"><br><br><br>Stock Report</a></li>
		<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "analyst" || $_SESSION['loc_id'] == 50 || $_SESSION['loc_id'] == 51 || $_SESSION['loc_id'] == 65 || $_SESSION['loc_id'] == 44): ?>
		<li><a href="invoicesReport.php" target="_blank"><img src="_img/btn-invoices.png"><br><br>Invoices<br>Report</a></li>
		<?php endif; ?>
		<li><a href="inventoryReport.php" target="_blank"><img src="_img/btn-inventory.png"><br><br>Inventory<br>Report</a></li>
		<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "analyst" || $_SESSION['loc_id'] == 50 || $_SESSION['loc_id'] == 51 || $_SESSION['loc_id'] == 65 || $_SESSION['loc_id'] == 55 || $_SESSION['loc_id'] == 44): ?>
		<li><a href="historyReport.php" target="_blank"><img src="_img/btn-history.png"><br><br>Item History<br>Report</a></li>
		<?php endif; ?>
		<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "sales" || $_SESSION['user_type'] == "stockadmin" || $_SESSION['user_type'] == "stocksadmin"): ?>
		<li><a href="sizeSwap.php" target="_blank"><img src="_img/btn-sizeSwap.png"><br><br><br>Size Swap</a></li>
		<?php endif; ?>
		<li><a href="changePassword.php" target="_blank"><img src="_img/btn-change-password.png"><br><br>Change<br>Password</a></li>
		<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "analyst"): ?>
		<li><a href="fullReport.php"><img src="_img/btn-full-report.png"><br><br><br>Full Report</a></li>
		<?php endif; ?>
		<?php if ($_SESSION['user_type'] == "stockadmin"): ?>
		<li><a href="addItemLocation.php"><img src="_img/btn-change-item-location.png"><br><br>Add Item<br>Location</a></li>
		<?php
			$userId = $_SESSION['user_id'];
			$result = $db->query("SELECT `zone_id` FROM wh_users
								  WHERE user_id = {$userId}");
			if (mysql_num_rows($result) > 0)
			{
				while ($row = $db->fetch_array($result))
				{
					echo '<li><a href="zone.php?zoneId='.$row['zone_id'].'"><img src="_img/btn-zone.png"><br><br><br>Zone '.userClass::getZoneName($row['zone_id']).'</a></li>';
				}
			}
		?>
	</ul>
	<?php endif; ?>
	<!-- This contains the hidden content for inline calls -->
	<div style='display:none'>
		<div id='fullReportType' style='padding:10px; background:#fff;text-align:center;'>
			<p><label><input type="checkbox" name="chkInvoices" id="chkInvoices" value="1">Invoices</label>
			<label><input type="checkbox" name="chkInventory" id="chkInventory" value="2">Inventory</label>
			<label><input type="checkbox" name="chkStock" id="chkStock" value="3">Stock</label></p><br>
			<p><label style="display: inline-block; width:50px;">From: </label><input style="text-align:center;" type="text" name="txtDateFrom" id="txtDateFrom"></p>
			<p><label style="display: inline-block; width:50px;">To: </label><input style="text-align:center;" type="text" name="txtDateTo" id="txtDateTo"></p><br>
			<input type="button" id="exportFullReport" name="exportFullReport" value="Export">
		</div>
	</div>

<?php require_once ("_inc/footer.php"); ?>
<script type="text/javascript">
$("#notLinkWrapper").click(function(e){
	$("#notBoxWrapper").show();
});
$(document).mouseup(function(e){
	var container = $("#notBoxWrapper");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide();
    }
});
</script>
<?php if ($_SESSION['user_type'] != "stockadmin" || $_SESSION['user_type'] != "stocksadmin"): ?>
	<?php if ($_SESSION['loc_id'] == 44): ?>
		<script type="text/javascript">
			$('body').bind('keydown', function(e) {
				if (e.keyCode == 113){
					e.preventDefault();
					window.location.href = "salesBox.php";
				}
				if (e.keyCode == 114){
					e.preventDefault();
					window.location.href = "returnBox2.php";
				}
				if (e.keyCode == 115){
					e.preventDefault();
					window.location.href = "exchangeBox.php";
				}
				if (e.keyCode == 117){
					e.preventDefault();
					window.location.href = "transferBox.php";
				}
			});
		</script>
	<?php else: ?>
		<script type="text/javascript">
			$('body').bind('keydown', function(e) {
				if (e.keyCode == 113){
					e.preventDefault();
					window.location.href = "salesBox.php";
				}
				if (e.keyCode == 114){
					e.preventDefault();
					window.location.href = "returnBox.php";
				}
				if (e.keyCode == 115){
					e.preventDefault();
					window.location.href = "exchangeBox.php";
				}
				if (e.keyCode == 117){
					e.preventDefault();
					window.location.href = "transferBox.php";
				}
			});
		</script>
	<?php endif; ?>
<?php endif; ?>