<?php

require_once ("_inc/header.php");

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

<h1>Sales Report Window</h1>

<?php

$today = date('Y-m-d');

$locId = $_SESSION['loc_id'];

$getDate = $db->query("SELECT DISTINCT(`date`) FROM invoice_header
					   WHERE loc_id = '{$locId}'");

while ($rowDate = $db->fetch_array($getDate))
{
	$dateResult[] = $rowDate['date'];
}

if (isset($_POST['btnSubmit']) && !empty($_POST['dateFrom']) && !empty($_POST['dateTo']))
{
	$dateFrom = $_POST['dateFrom'];
	$dateTo   = $_POST['dateTo'];

	$result   = $db->query("SELECT * FROM invoice_header
						    WHERE `date` BETWEEN CAST('{$dateFrom}' AS DATE)
						    AND CAST('{$dateTo}' AS DATE)
						    AND loc_id = '{$locId}'");

	if(mysql_num_rows($result) == 0)
	{
		header("location: salesReport.php");
	}

	while ($row = $db->fetch_array($result))
	{
		$invo_type[]       = $row['invo_type'];
		$payment_type[]    = $row['payment_type'];
		$net_value[]       = $row['net_value'];
		$split[]           = $row['split'];
		$discount_amount[] = $row['discount_amount'];
	}

	$totalSales  = 0;
	$totalReturn = 0;
	$totalCash   = 0;
	$totalVisa   = 0;
	$totalMCard  = 0;

	for ($i=0; $i < count($net_value); $i++) {
		if ($invo_type[$i] == "s") { $totalSales += $net_value[$i]; }
		else { $totalReturn += $net_value[$i]; }
	}

	for ($i=0; $i < count($payment_type); $i++) {
		if ($payment_type[$i] == "Cash") { $totalCash += $net_value[$i]; }
		elseif ($payment_type[$i] == "Split") {
			$totalCash += $split[$i];
			$totalVisa += $net_value[$i] - $split[$i];
		}
		elseif ($payment_type[$i] == "Visa") { $totalVisa += $net_value[$i]; }
		else { $totalMCard += $net_value[$i]; }
	}

	$dataTable  = '<div id="reportWrapper">';
	$dataTable .= '<a onclick="doPrint()" style="cursor: pointer; display: block; padding: 10px; color: white; background: red; text-decoration: none; font-weight: bold;">Print</a>';
	$dataTable .= '<br><p>'.userClass::getBranchName($locId).'</p><br>';
	$dataTable .= '<p>From: '.$dateFrom.'<br>To: '.$dateTo.'</p><br>';
	$dataTable .= '<p>End of Day Report</p><br>';
	$dataTable .= '<table id="tblSalesReport">';
	$dataTable .= '<tr><th colspan="2">Payments</th></tr>';
	$dataTable .= '<tr><td class="noBorder" colspan="2">&nbsp;</td></tr>';
	$dataTable .= '<tr><th>Sales #</th><td>'.count(array_keys($invo_type, "s")).'</td></tr>';
	$dataTable .= '<tr><th>Return #</th><td>'.count(array_keys($invo_type, "r")).'</td></tr>';
	$dataTable .= '<tr><td class="noBorder" colspan="2">&nbsp;</td></tr>';
	$dataTable .= '<tr><th>Cash $</th><td>'.$totalCash.'</td></tr>';
	$dataTable .= '<tr><th>Visa $</th><td>'.$totalVisa.'</td></tr>';
	$dataTable .= '<tr><th>Master Card $</th><td>'.$totalMCard.'</td></tr>';
	$dataTable .= '<tr><td class="noBorder" colspan="2">&nbsp;</td></tr>';
	$dataTable .= '<tr><th>Total Sales $</th><td>'.$totalSales.'</td></tr>';
	$dataTable .= '<tr><th>Total Return $</th><td>'.$totalReturn.'</td></tr>';
	$dataTable .= '<tr><th>Total Disc. $</th><td>'.array_sum($discount_amount).'</td></tr>';
	$dataTable .= '<tr><td class="noBorder" colspan="2">&nbsp;</td></tr>';
	$dataTable .= '<tr><th>Net Sales $</th><td>'.(($totalSales - $totalReturn) + array_sum($discount_amount)).'</td></tr>';
	$dataTable .= '</table>';
	$dataTable .= '<br><br><p style="text-align: right; direction: rtl;">????? ?????? ???????:</p><br><br>';
	$dataTable .= '</div>';
}

?>

<form name="frmSalesReport" id="frmSalesReport" method="post" action="#"  enctype="multipart/form-data">
	<label>FROM: <select id="dateFrom" name="dateFrom">
		<option value="0">Select ..</option>
		<?php foreach ($dateResult as $date): ?>
		<option value="<?php echo $date ?>"><?php echo $date ?></option>
		<?php endforeach; ?>
	</select></label>

	<label>TO: <select id="dateTo" name="dateTo">
		<option value="0">Select ..</option>
		<?php foreach ($dateResult as $date): ?>
		<option value="<?php echo $date ?>" <?php if ($date == $today) echo "Selected"; ?>><?php echo $date ?></option>
		<?php endforeach; ?>
	</select></label>

	<input type="submit" name="btnSubmit" value="Submit">
</form>


<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>