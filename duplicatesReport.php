<?php

require_once ("_inc/header2.php");

$locId  = $_SESSION['loc_id'];

$result = $db->query("SELECT wh_item_location.id, wh_item_location.item_id,
					  COUNT(wh_item_location.item_id) AS totalItems, wh_item_location.loc_id, wh_locations.desc
					  FROM `wh_item_location`
					  INNER JOIN `wh_locations`
					  ON wh_item_location.loc_id = wh_locations.loc_id
					  AND wh_item_location.main_loc_id = {$locId}
					  GROUP BY wh_item_location.item_id");

if (mysql_num_rows($result) > 0)
{
	$dataTable  = '<div id="reportWrapper2">';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>Item#</th><th>Current Location</th><th>Qty</th><th>Last Edit</th><th>Edited By</th><th></th><th></th></tr>';
	while ($row = $db->fetch_array($result))
	{
		if ($row['totalItems'] > 1)
		{
			$itemId  = $row['item_id'];

			$result2 = $db->query("SELECT wh_item_location.id, wh_item_location.item_id,
								  wh_item_location.loc_id, wh_locations.desc
					  			  FROM `wh_item_location`
					  			  INNER JOIN `wh_locations`
					  			  ON wh_item_location.loc_id = wh_locations.loc_id
					  			  AND wh_item_location.item_id = '{$itemId}'
					  			  AND wh_item_location.main_loc_id = '{$locId}'
					  			  GROUP BY wh_item_location.item_id, wh_item_location.loc_id");

			while ($row2 = $db->fetch_array($result2))
			{
				$dataTable .= '<tr style="background: lightpink;">';
				$dataTable .= '<td>'.$row2['item_id'].'</td>';
				$dataTable .= '<td>'.$row2['desc'].'</td>';
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
										  WHERE item_id = {$row2['item_id']}
										  AND   main_loc_id = {$locId}
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
				$dataTable .= '<td><a class="editItemLocation" href="editItemLocationAdmin.php?id='.$row2['id'].'">Edit</a></td>';
				$dataTable .= '<td><a class="deleteItemDuplicates" id="'.$row2['id'].'" href="#">Delete</a></td>';
				$dataTable .= '</tr>';
			}
		}
	}
}

?>

<h1>Duplicates Window</h1>

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
	<div id='viewAllLocations' style='padding:10px; background:#fff;'></div>
</div>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>