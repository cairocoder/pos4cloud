<?php

require_once ("_inc/header2.php");
require_once ("_inc/transClass.php");

?>

<style type="text/css" media="print">
	#frmSalesReport {display: none;}
	h1 {display: none;}
	#back {display: none;}
	#logout {display: none;}
	body {background: #fff;}
	#wrapper {box-shadow: 0 0 0;}
	a {display: none !important;}
</style>

<h1>Transactions Report Window</h1>

<?php

$today = date('Y-m-d');

$locId = $_SESSION['loc_id'];

$getDate = $db->query("SELECT DISTINCT(`date`) FROM inventory_header
					   WHERE wrhs_id = '{$locId}'");

while ($rowDate = $db->fetch_array($getDate))
{
	$dateResult[] = $rowDate['date'];
}

if(isset($_POST['btnSubmit']) && !empty($_POST['dateFrom']) && !empty($_POST['dateTo']))
{
	$dateFrom = $_POST['dateFrom'];
	$dateTo   = $_POST['dateTo'];

	$result   = $db->query("SELECT * FROM inventory_header
						    WHERE `date` BETWEEN CAST('{$dateFrom}' AS DATE)
						    AND CAST('{$dateTo}' AS DATE)
						    AND wrhs_id = '{$locId}'");

	if(mysql_num_rows($result) == 0)
	{
		header("location: transReport.php");
	}

	$dataTable  = '<div id="reportWrapper2">';
	$dataTable .= '<br><p>'.userClass::getBranchName($locId).'</p><br>';
	$dataTable .= '<p>From: '.$dateFrom.'<br>To: '.$dateTo.'</p><br>';
	$dataTable .= '<p>Tranactions Report</p><br>';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>Type</th><th>Date</th><th>Time</th><th>From</th><th>To</th><th>Total Price</th><th>Qty.</th><th></th></tr>';
	while ($row = mysql_fetch_array($result))
	{
		$dataTable .= '<tr>';
		$dataTable .= '<td>';
		$dataTable .= $pr->getTransType($row['trans_type_id']);
		$dataTable .= '</td>';
		$dataTable .= '<td>'.$row['date'].'</td>';
		$dataTable .= '<td>'.date('h:i:s A', strtotime($row['time'])).'</td>';
		if ($row['trans_type_id'] == 1 || $row['trans_type_id'] == 2)
		{
			$invoHeader = $pr->getInvoiceHeader($row['invo_no'], $locId);
			$dataTable .= '<td></td>';
			$dataTable .= '<td></td>';
			$dataTable .= '<td>'.$invoHeader['net_value'].'</td>';
			$dataTable .= '<td>'.$invoHeader['qty'].'</td>';
		} else {
			$dataTable .= '<td>'.userClass::getBranchName($row['from_wrhs_id']).'</td>';
			$dataTable .= '<td>'.userClass::getBranchName($row['to_wrhs_id']).'</td>';
			$dataTable .= '<td>'.$row['total_rtp'].'</td>';
			$dataTable .= '<td>'.$row['qty'].'</td>';
		}
		$dataTable .= '<td><a
					   id="viewDetails"
					   href="#"
					   transNo="'.$row['trans_no'].'"
					   transTypeId="'.$row['trans_type_id'].'"
					   invoNo="'.$row['invo_no'].'"
					   >Details</a></td>';
		$dataTable .= '</tr>';
	}
	$dataTable .= '</table>';
	$dataTable .= '</div>';
}

?>

<form name="frmSalesReport" id="frmSalesReport" method="post" action="#"  enctype="multipart/form-data">
	<label>FROM: <select id="dateFrom" name="dateFrom">
		<option value="0">Select ..</option>
		<?php if(!empty($dateResult)): ?>
		<?php foreach ($dateResult as $date): ?>
		<option value="<?php echo $date ?>"><?php echo $date ?></option>
		<?php endforeach; ?>
		<?php endif; ?>
	</select></label>

	<label>TO: <select id="dateTo" name="dateTo">
		<option value="0">Select ..</option>
		<?php if(!empty($dateResult)): ?>
		<?php foreach ($dateResult as $date): ?>
		<option value="<?php echo $date ?>" <?php if ($date == $today) echo "Selected"; ?>><?php echo $date ?></option>
		<?php endforeach; ?>
		<?php endif; ?>
	</select></label>

	<input type="submit" name="btnSubmit" value="Submit">
</form>
<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
	<div id='inline_content' style='padding:10px; background:#fff;'>
		<label>Name: <input type="text" name="txtName" id="txtName"></label>
		<label>Mobile: <input type="text" name="txtMob" id="txtMob"></label>
		<input type="button" id="btnAddCust" name="btnAddCust" value="+">
	</div>
</div>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>