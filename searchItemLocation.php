<?php require_once ("_inc/header2.php"); ?>

<h1>Search For Item Location Window</h1>

<?php

$allZones = userClass::getAllZones();
$locId    = $_SESSION['loc_id'];

if (isset($_GET['btnSubmit']))
{
	$query  = "SELECT wh_item_location.id, wh_item_location.item_id, wh_item_location.loc_id, wh_locations.desc
			   FROM `wh_item_location`
			   INNER JOIN `wh_locations`
			   ON wh_item_location.loc_id = wh_locations.loc_id
			   AND main_loc_id = {$locId}";

	if (!empty($_GET['itemId']))
	{
		$itemId      = $_GET['itemId'];
		$query      .= " AND wh_item_location.item_id = '{$itemId}'";
	}

	if (array_search("All", $_GET['selLoc']) === false)
	{
		$getAllLoc   = implode(",", $_GET['selLoc']);
		$query      .= " AND wh_item_location.loc_id in ({$getAllLoc})";
	}

	if (array_search("All", $_GET['selLoc']) === false)
	{
		$getAllLoc   = implode(",", $_GET['selLoc']);
		$query      .= " AND wh_item_location.loc_id in ({$getAllLoc})";
	}

	$result = $db->query($query);

	if (mysql_num_rows($result) > 0)
	{
		$dataTable  = '<div id="reportWrapper2">';
		$dataTable .= '<table id="tblTransReport">';
		$dataTable .= '<tr><th>Item#</th><th>Current Location</th><th>Qty</th><th>Last Edit</th><th>Edited By</th></th><th>All Locations</th><th></th><th></th></tr>';
		while ($row = $db->fetch_array($result))
		{
			$itemId = $row['item_id'];

			$chkItem    = $db->query("SELECT wh_item_location.loc_id FROM wh_item_location
									  INNER JOIN `wh_locations`
									  ON wh_item_location.loc_id = wh_locations.loc_id
									  AND wh_item_location.item_id = '{$itemId}'
									  AND main_loc_id = {$locId}");
			if (mysql_num_rows($chkItem) > 1)
			{
				$dataTable .= '<tr style="background: lightpink;">';
			} else {
				$dataTable .= '<tr>';
			}
			$dataTable .= '<td>'.$row['item_id'].'</td>';
			$dataTable .= '<td>'.$row['desc'].'</td>';
			$getItemQty = $db->query("SELECT SUM(qty) AS qty FROM warehouses
									  WHERE  item_id = '{$itemId}'
									  AND    wrhs_id = '{$locId}'
									  HAVING qty > 0");
			if (mysql_num_rows($getItemQty) > 0)
			{
				$rowItemQty = $db->fetch_array($getItemQty);
				$dataTable .= '<td>'.$rowItemQty['qty'].'</td>';
			} else {
				$dataTable .= '<td>0</td>';
			}
			$getLog     = $db->query("SELECT * FROM log
									  WHERE item_id = {$row['item_id']}
									  AND main_loc_id = {$locId}
									  ORDER BY log_id DESC
									  LIMIT 1");
			if (mysql_num_rows($getLog) > 0)
			{
				$rowGetLog  = $db->fetch_array($getLog);
				$dataTable .= '<td>'.$rowGetLog['date'].' '.date('h:i:s',strtotime($rowGetLog['time'])).'</td>';
				$dataTable .= '<td>'.userClass::getUserName($rowGetLog['user_id']).'</td>';
			} else {
				$dataTable .= '<td></td>';
				$dataTable .= '<td></td>';
			}
			$dataTable .= '<td><a class="viewAllLoc" itemId="'.$itemId.'" href="#">View</a></td>';
			$dataTable .= '<td><a class="editItemLocation" href="editItemLocationAdmin.php?id='.$row['id'].'">Edit</a></td>';
			$dataTable .= '<td><a class="deleteItemLocationAdmin" id="'.$row['id'].'" href="#">Delete</a></td>';
			$dataTable .= '</tr>';
		}
	}
}

?>

<form name="frmStockReport" id="frmStockReport" method="GET" action="#"  enctype="multipart/form-data">

	<br><br>

	<table>
		<tr>
			<th>Zone</th>
			<th>Location</th>
		</tr>
		<tr>
			<td>
				<select name="selZone[]" id="selZone" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allZones)): ?>
					<?php foreach ($allZones as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
				<select name="selLoc[]" id="selLoc" multiple="multiple">
					<option value="All" selected>All</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<label>Item# <input type="text" name="itemId"></label>
				<input type="submit" name="btnSubmit" value="Submit">
			</td>
		</tr>
	</table>
	
</form>

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
	<div id='viewAllLocations' style='padding:10px; background:#fff;'></div>
</div>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>