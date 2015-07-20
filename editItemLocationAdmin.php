<?php require_once ("_inc/header2.php") ?>

<?php
	if (!empty($_GET['id']))
	{
		$id      = $_GET['id'];
		$userId  = $_SESSION['user_id'];

		$result  = $db->query("SELECT * FROM wh_item_location
							   WHERE id = '{$id}'");

		if (mysql_num_rows($result) > 0)
		{
			$row          = $db->fetch_array($result);
			$itemId       = $row['item_id'];
			$dataTable    = '<tr><th>Item# </th><td><span id="txtItemId">'.$itemId.'</span></td></tr>';
			$dataTable   .= '<tr>';
			$dataTable   .= '<th>Zone </th>';
			$dataTable   .= '<td>';
			$getUserZones = $db->query("SELECT zone_id from wh_zones");		
			$getItemZone  = $db->query("SELECT zone_id from wh_locations
										WHERE loc_id = '{$row['loc_id']}'");
			$rowItemZone  = $db->fetch_array($getItemZone);
			$dataTable .= '<select name="selZone" id="selZone">';
			if (mysql_num_rows($getUserZones) > 0)
			{
				while ($rowUserZones = $db->fetch_array($getUserZones))
				{
					if ($rowUserZones['zone_id'] == $rowItemZone['zone_id'])
					{
						$dataTable .= '<option value="'.$rowUserZones['zone_id'].'" selected>'.userClass::getZoneName($rowUserZones['zone_id']).'</option>';
					} else {
						$dataTable .= '<option value="'.$rowUserZones['zone_id'].'">'.userClass::getZoneName($rowUserZones['zone_id']).'</option>';
					}
				}
			}
			$dataTable .= '</select>';
			$dataTable .= '</td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<th>Location </th>';
			$dataTable .= '<td>';
			$dataTable .= '<select name="selLocation" id="selLocation">';
			$getItemLoc = $db->query("SELECT `loc_id`, `desc` FROM wh_locations
									  WHERE zone_id = {$rowItemZone['zone_id']}");		
			while ($rowItemLoc = $db->fetch_array($getItemLoc))
			{
				if ($rowItemLoc['loc_id'] == $row['loc_id'])
				{
					$dataTable .= '<option value="'.$rowItemLoc['loc_id'].'" selected>'.$rowItemLoc['desc'].'</option>';
				} else {
					$dataTable .= '<option value="'.$rowItemLoc['loc_id'].'">'.$rowItemLoc['desc'].'</option>';
				}
			}
			$dataTable .= '</select>';
			$dataTable .= '</td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<td style="padding: 10px;" colspan="2"><input type="button" id="btnEditItemLocationAdmin" value="Update"></td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<td colspan="2" style="border:none;">&nbsp;</td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<td colspan="2" style="border:none;">&nbsp;</td>';
			$dataTable .= '</tr>';
			$chkItem    = $db->query("SELECT loc_id FROM wh_item_location
									  WHERE item_id = '{$itemId}'");
			if (mysql_num_rows($chkItem) > 0)
			{
				$getLog     = $db->query("SELECT * FROM log
										  WHERE item_id = '{$itemId}'
										  AND type = 2
										  ORDER BY log_id DESC
										  LIMIT 1");
				if (mysql_num_rows($getLog) > 0)
				{
					$rowGetLog  = $db->fetch_array($getLog);
					$dataTable .= '<tr>';
					$dataTable .= '<th>Last Add </th><td>'.$rowGetLog['date'].' '.date('h:i:s',strtotime($rowGetLog['time'])).'</td>';
					$dataTable .= '</tr>';
					$dataTable .= '<tr>';
					$dataTable .= '<th>Added By </th><td>'.userClass::getUserName($rowGetLog['user_id']).'</td>';
					$dataTable .= '</tr>';
					$dataTable .= '<tr>';
					$dataTable .= '<td colspan="2" style="border:none;">&nbsp;</td>';
					$dataTable .= '</tr>';
					while ($rowChkItem = $db->fetch_array($chkItem))
					{
						$dataTable .= '<tr>';
						$dataTable .= '<th>Location </th><td>'.userClass::getLocName($rowChkItem['loc_id']).'</td>';
						$dataTable .= '</tr>';
					}
				}
			}
		}
	}
?>

<h1>Edit Item Location Window</h1>

<div id="reportWrapper">
	<table id="tblTransReport">
		<?php if (isset($dataTable)) echo $dataTable; ?>
	</table>
</div>

<?php require_once ("_inc/footer.php") ?>