<?php require_once ("_inc/header2.php") ?>

<?php
	if (isset($_GET['btnSubmit']) && !empty($_GET['itemId']))
	{
		$itemId  = $_GET['itemId'];
		$userId  = $_SESSION['user_id'];
		$locId   = $_SESSION['loc_id'];

		$result  = $db->query("SELECT item_id FROM items
							   WHERE item_id = '{$itemId}'");
		if (mysql_num_rows($result) > 0)
		{
			$dataTable  = '<tr><th>Item# </th><td><span id="txtItemId">'.$itemId.'</span></td></tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<th>Zone </th>';
			$dataTable .= '<td>';
			$result2    = $db->query("SELECT zone_id from wh_zones");
			$dataTable .= '<select name="selZone" id="selZone">';
			if (mysql_num_rows($result2) > 0)
			{
				while ($row2 = $db->fetch_array($result2))
				{
					$dataTable .= '<option value="'.$row2['zone_id'].'">'.userClass::getZoneName($row2['zone_id']).'</option>';
				}
			}
			$dataTable .= '</select>';
			$dataTable .= '</td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<th>Location </th>';
			$dataTable .= '<td>';
			$dataTable .= '<select name="selLocation" id="selLocation">';
			$result3    = $db->query("SELECT `loc_id`, `desc` FROM wh_locations
									  WHERE zone_id = 1");		
			while ($row3 = $db->fetch_array($result3))
			{
				$dataTable .= '<option value="'.$row3['loc_id'].'">'.$row3['desc'].'</option>';
			}
			$dataTable .= '</select>';
			$dataTable .= '</td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<td style="padding: 10px;" colspan="2"><input type="button" id="btnAddItemLocationAdmin" value="Add"></td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<td colspan="2" style="border:none;">&nbsp;</td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<td colspan="2" style="border:none;">&nbsp;</td>';
			$dataTable .= '</tr>';
			$chkItem    = $db->query("SELECT loc_id FROM wh_item_location
									  WHERE item_id = '{$itemId}'
									  AND main_loc_id = {$locId}");
			if (mysql_num_rows($chkItem) > 0)
			{
				$getLog     = $db->query("SELECT * FROM log
										  WHERE item_id = '{$itemId}'
										  AND type = 1
										  AND main_loc_id = {$locId}
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

<h1>Add Item Location Window</h1>

<form name="frmAddItemLocation" id="frmAddItemLocation" method="GET" action="#"  enctype="multipart/form-data">
	<label>Item# <input type="text" name="itemId"></label>
	<input type="submit" name="btnSubmit" value="Submit">
</form>

<div id="reportWrapper">
	<table id="tblTransReport">
		<?php if (isset($dataTable)) echo $dataTable; ?>
	</table>
</div>

<?php require_once ("_inc/footer.php") ?>