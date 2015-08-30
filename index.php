<?php
	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);
	require_once ("_inc/dbClass.php");
	require_once("_inc/userClass.php");
	require_once("_inc/sessionClass.php");
	require_once("_inc/roleClass.php");

	if (!$session->is_logged_in()) {
	  header("location: login.php");
	}

	$locId  = $_SESSION['loc_id'];
	$roleId = $_SESSION['role_id'];
	$permissions = roleClass::getRolePerms($roleId);
	print_r($permissions);
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
		<?php if ($roleId == "4" || $roleId == "5" || $roleId == "2"): ?>
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
		<?php
			foreach ($permissions as $key => $value) {
				print_r($value);
			}
		?>
	</ul>
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
<?php if ($_SESSION['role_id'] != "stockadmin" || $_SESSION['role_id'] != "5"): ?>
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