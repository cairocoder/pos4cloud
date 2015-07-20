<?php

require_once ("_inc/header.php");
require_once ("_inc/transClass.php");

?>

<?php
	if ($_SESSION['user_type'] != "sadmin" && $_SESSION['user_type'] != "analyst" && $_SESSION['loc_id'] != 50 && $_SESSION['loc_id'] != 51 && $_SESSION['loc_id'] != 65 && $_SESSION['loc_id'] != 55 && $_SESSION['loc_id'] != 44){ 
		echo "<script>window.location.href = 'index.php'</script>";
	}
?>

<link href="_css/print.css" rel="stylesheet" type="text/css" media="print" />

<h1>Item History Report Window</h1>

<?php

$locId = $_SESSION['loc_id'];

if(isset($_GET['btnSubmit']) && !empty($_GET['txtSearchByItem']))
{
	$itemId = $_GET['txtSearchByItem'];
	$db->query("SET SQL_BIG_SELECTS=1");  //Set it before your main query

	$result   = $db->query("SELECT inventory_detail.wrhs_id, inventory_detail.trans_no, inventory_detail.item_id, SUM(inventory_detail.qty) AS qty, inventory_header.trans_no, inventory_header.wrhs_id, inventory_header.trans_type_id, inventory_header.from_wrhs_id, inventory_header.to_wrhs_id FROM `inventory_detail`
							JOIN inventory_header ON (inventory_header.wrhs_id = inventory_detail.wrhs_id AND inventory_header.trans_no = inventory_detail.trans_no)
							WHERE inventory_detail.item_id = {$itemId} AND (inventory_header.wrhs_id = {$locId} OR inventory_header.from_wrhs_id = {$locId} OR inventory_header.to_wrhs_id = {$locId})
							GROUP BY inventory_detail.trans_no
							ORDER BY inventory_header.date, inventory_header.time ASC");

	if (mysql_num_rows($result) == 0)
	{
		echo '<script>window.location = "historyReport.php"</script>';
	}

	$dataTable  = '<div id="reportWrapper2">';
	$dataTable .= '<br><p>'.userClass::getBranchName($locId).'</p>';
	$dataTable .= '<br><p>Item# '.$_GET['txtSearchByItem'].'</p><br>';
	$dataTable .= '<p>Item History Report</p><br>';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>Transaction#</th><th>Type</th><th>Qty</th><th>From</th><th>To</th><th>Date</th><th>Time</th><th>Status</th><th></th></tr>';
	while ($row = mysql_fetch_array($result))
	{
		$invnHeader = $trans->getInventoryHeader($row['trans_no'], $row['wrhs_id']);
		$dataTable .= '<tr>';
		$dataTable .= '<td>'.$invnHeader['trans_no'].'</td>';
		$dataTable .= '<td>';
		$dataTable .= $trans->getTransType($invnHeader['trans_type_id']);
		$dataTable .= '</td>';
		$dataTable .= '<td>'.$row['qty'].'</td>';
		$dataTable .= '<td>'.userClass::getBranchName($row['from_wrhs_id']).'</td>';
		$dataTable .= '<td>'.userClass::getBranchName($row['to_wrhs_id']).'</td>';
		$dataTable .= '<td>'.$invnHeader['date'].'</td>';
		$dataTable .= '<td>'.date('h:i:s A', strtotime($invnHeader['time'])).'</td>';

		if ($invnHeader['trans_type_id'] == 1 || $invnHeader['trans_type_id'] == 2 || $invnHeader['trans_type_id'] == 3)
		{
			$dataTable .= '<td></td>';
			$dataTable .= '<td><a
						   id="viewInvoDetails"
						   href="#"
						   invoNo="'.$invnHeader['invo_no'].'"
						   >Details</a></td>';
			
		} else {
			$dataTable .= '<td>'.$trans->getStatusType($invnHeader['status']).'</td>';
			$dataTable .= '<td><a
						   id="viewInvnDetails"
						   href="#"
						   transNo="'.$invnHeader['trans_no'].'"
						   wrhsId="'.$invnHeader['wrhs_id'].'"
						   >Details</a></td>';
		}

		$dataTable .= '</tr>';
	}
	$dataTable .= '</table>';
	$dataTable .= '</div>';
}

?>

<form name="frmItemHistoryReport" id="frmItemHistoryReport" method="GET" action="#"  enctype="multipart/form-data">
	<label>Item# <input type="text" id="txtSearchByItem" name="txtSearchByItem"></label>
	<input type="submit" name="btnSubmit" value="Submit">
</form>

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
	<div id='inline_content' style='padding:10px; background:#fff;'></div>
</div>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>