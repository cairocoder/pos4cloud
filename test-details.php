<?php

require_once ("_inc/dbClass.php");
require_once ("_inc/userClass.php");

// $locId       = $_SESSION['loc_id'];
$itemId      = '12515';
// $selBranch   = $_POST['selBranch'];

// $query       = "SELECT * FROM items
// 		    	WHERE item_id = {$itemId}";
// $result      = $db->query($query);
// $row         = $db->fetch_array($result);

// $dataTable   = '<img class="itemImg" src="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg">';
// $dataTable  .= '<div style="min-height:300px;overflow:auto;">';
// $dataTable  .= '<table class="itemSalesStock">
// 					<tr><td></td><th>SALES</th><th>STOCK</th></tr>';

// $query2      = "SELECT DISTINCT wrhs_id
// 				FROM `warehouses`
// 				WHERE item_id = {$itemId}";
// $result2     = $db->query($query2);
// while ($row2 = $db->fetch_array($result2))
// {
// 	$salesReport = userClass::getItemTotalSales($itemId, $row2['wrhs_id']);
// 	$dataTable  .= '<tr><th>'.userClass::getBranchShortName($row2['wrhs_id']).'</th><td>'.$salesReport['netSales'].'</td><td>'.userClass::getItemTotalStock($itemId, $row2['wrhs_id']).'</td></tr>';
// }
// $dataTable   .= '</table>';

$query   = "SELECT SUM(invoice_detail.qty) AS qty, MONTH(invoice_header.date) AS month,
			YEAR(invoice_header.date) AS year
			FROM invoice_detail
			JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
    		AND  invoice_detail.loc_id = invoice_header.loc_id
    		WHERE invoice_detail.item_id = {$itemId}";

if ($selBranch == 1) {
	$query .= " AND invoice_detail.loc_id = {$locId}";
}
    		
$query  .= " GROUP BY YEAR(invoice_header.date), MONTH(invoice_header.date)
    		 ORDER BY YEAR(invoice_header.date), MONTH(invoice_header.date)";

$getTSMonth   = $db->query($query);
while ($row = $db->fetch_array($getTSMonth)) {
	$qty[]    = $row['qty'];
	$years[]  = $row['year'];
	$months[] = $row['month'];
}

$uYears = array_unique($years);

if ($db->num_rows($getTSMonth) > 0) {
	
	foreach ($uYears as $oneYear)
	{
		$dataTable  .= '<table class="itemSalesStock">
						<tr><td></td><th>SALES/'.$oneYear.'</th></tr>';
		for ($i = 0; $i < count($years); $i++) { 
			if ($years[$i] == $oneYear)
			{
				$dataTable  .= '<tr><th>M'.$months[$i].'</th><td>'.$qty[$i].'</td></tr>';
			}
		}
		if ($selBranch == 1)
		{
			$salesReport = userClass::getItemTotalSales($itemId, $locId, NULL, NULL);
			$dataTable  .= '<tr><th style="background:#0972ba;">T.SLS</th><td>'.$salesReport['totalSales'].'</td></tr>';
			$dataTable  .= '<tr><th style="background:#0972ba;">T.RTN</th><td>'.$salesReport['totalReturn'].'</td></tr>';
			$dataTable  .= '<tr><th style="background:#0972ba;">N.SLS</th><td>'.$salesReport['netSales'].'</td></tr>';
			$dataTable  .= '<tr><th style="background:#0972ba;">T.STK</th><td>'.userClass::getItemTotalStock($itemId, $locId, NULL, NULL).'</td></tr>';
		} else {
			$salesReport = userClass::getItemTotalSales($itemId);
			$dataTable  .= '<tr><th style="background:#0972ba;">T.SLS</th><td>'.$salesReport['totalSales'].'</td></tr>';
			$dataTable  .= '<tr><th style="background:#0972ba;">T.RTN</th><td>'.$salesReport['totalReturn'].'</td></tr>';
			$dataTable  .= '<tr><th style="background:#0972ba;">N.SLS</th><td>'.$salesReport['netSales'].'</td></tr>';
			$dataTable  .= '<tr><th style="background:#0972ba;">T.STK</th><td>'.userClass::getItemTotalStock($itemId).'</td></tr>';
		}
		$dataTable  .= '</table>';
		$dataTable  .= '</div>';	
	}
}

// $dataTable  .= '<br>';
// $dataTable  .= '<div style="display:block;position:absolute;bottom:0;width:100%">';
// $dataTable  .= '<table class="itemData">
// 					<tr><th>Item#</th><td>'.$itemId.'</td></tr>
// 					<tr><th>Description</th><td>'.userClass::getItemDesc($itemId).'</td></tr>
// 					<tr><th>Attribute</th><td>'.userClass::getItemAttr($itemId).'</td></tr>
// 					<tr><th>Vendor</th><td>'.userClass::getItemVend($itemId).'</td></tr>
// 					<tr><th>Intl Code</th><td>'.userClass::getItemIntlCode($itemId).'</td></tr>
// 					<tr><th>Department</th><td>'.userClass::getItemDept($itemId).'</td></tr>
// 				</table>';
// $itemPrices = userClass::getItemPrices($itemId);
// $dataTable  .= '<table class="itemPrice">
// 					<tr><th>MSRP</th><td>'.$itemPrices['msrp'].'</td></tr>
// 					<tr><th>RTP</th><td>'.$itemPrices['rtp'].'</td></tr>';
// if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "analyst") {
// 	$dataTable  .= '<tr><th>COST</th><td>'.$itemPrices['cost'].'</td></tr>';
// }
// $dataTable  .= '<div>';
// $dataTable  .= '</table>';

echo $dataTable;