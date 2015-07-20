<?php

require_once ("_inc/header.php");

?>

<link href="_css/print.css" rel="stylesheet" type="text/css" media="print" />

<h1>End of Day Report Window</h1>

<?php

$today = date('Y-m-d');

$locId = $_SESSION['loc_id'];

$getDate = $db->query("SELECT DISTINCT(`date`) FROM invoice_header
					   WHERE loc_id = '{$locId}'");

while ($rowDate = $db->fetch_array($getDate))
{
	$dateResult[] = $rowDate['date'];
}

if (!empty($dateResult))
{
	$dateRange = '"' . implode('","', $dateResult) . '"';
} else {
	$dateRange = '';
}

if(isset($_GET['btnSubmit']) && !empty($_GET['dateFrom']) && !empty($_GET['dateTo']))
{
	$dateFrom = $_GET['dateFrom'];
	$dateTo   = $_GET['dateTo'];
	$date     = date('Y-m-d');
	$chkDate  = date('Y-m-d', strtotime($Date. ' - 2 days'));

	if ($_SESSION['user_type'] != 'sadmin' && $_SESSION['user_type'] != 'analyst' && $_SESSION['loc_id'] != 50 && $_SESSION['loc_id'] != 51 && $_SESSION['loc_id'] != 65) {
		if ($dateFrom < $chkDate || $dateTo < $chkDate) {
			echo '<p style="font-weight:bold;color:red;">Error!</p>';
			die();
		}
	}

	$result   = $db->query("SELECT * FROM invoice_header
						    WHERE `date` BETWEEN CAST('{$dateFrom}' AS DATE)
						    AND CAST('{$dateTo}' AS DATE)
						    AND loc_id = '{$locId}'");

	if (mysql_num_rows($result) == 0)
	{
		echo '<script>window.location = "eodReport.php"</script>';
		die();
	}

	while ($row = $db->fetch_array($result))
	{
		$invoType[]    = $row['invo_type'];
		$invoNo[]      = $row['invo_no'];
		$paymentType[] = $row['payment_type_id'];
		$netValue[]    = $row['net_value'];
		$split[]       = $row['split'];
		$discAmount[]  = $row['discount_amount'];
	}
	
	$totalSales  = 0;
	$totalReturn = 0;
	$totalCash   = 0;
	$totalVisa   = 0;

	for ($i = 0; $i < count($netValue); $i++)
	{
		if ($invoType[$i] == 1) {
			$totalSales  += $netValue[$i];
		} elseif ($invoType[$i] == 2) {
			$totalReturn += $netValue[$i];
		} else {
			$getInvoDetails = $db->query("SELECT * FROM invoice_detail
										  WHERE invo_no = '{$invoNo[$i]}'
										  AND   loc_id  = '{$locId}'");

			while ($rowInvoDetails = $db->fetch_array($getInvoDetails))
			{
				if ($rowInvoDetails['type'] == 1)
				{
					$totalSales  += ($rowInvoDetails['rtp'] * $rowInvoDetails['qty']);
				} else {
					$totalReturn += ($rowInvoDetails['rtp'] * $rowInvoDetails['qty']);
				}
			}
		}
	}

	for ($i = 0; $i < count($paymentType); $i++)
	{
		if ($paymentType[$i]  == 1)
		{
			$totalCash += $netValue[$i];
		}

		if ($paymentType[$i]  == 2) {
			$totalVisa += $netValue[$i];
		}
		
		if ($paymentType[$i]  == 3) {
			$totalCash  += $split[$i];
			$totalVisa  += $netValue[$i] - $split[$i];
		}
	}

	$dataTable  = '<div id="reportWrapper">';
	$dataTable .= '<a onclick="doPrint()" style="cursor: pointer; display: block; padding: 10px; color: white; background: red; text-decoration: none; font-weight: bold;">Print</a>';
	$dataTable .= '<br><p>'.userClass::getBranchName($locId).'</p><br>';
	$dataTable .= '<p>From: '.$dateFrom.'<br>To: '.$dateTo.'</p><br>';
	$dataTable .= '<p>End of Day Report</p><br>';
	$dataTable .= '<table id="tblSalesReport">';
	$dataTable .= '<tr><th colspan="2">Payments</th></tr>';
	$dataTable .= '<tr><td class="noBorder" colspan="2">&nbsp;</td></tr>';
	$dataTable .= '<tr><th>Sales #</th><td>'.count(array_keys($invoType, 1)).'</td></tr>';
	$dataTable .= '<tr><th>Return #</th><td>'.count(array_keys($invoType, 2)).'</td></tr>';
	$dataTable .= '<tr><th>Exchange #</th><td>'.count(array_keys($invoType, 3)).'</td></tr>';
	$dataTable .= '<tr><td class="noBorder" colspan="2">&nbsp;</td></tr>';
	$dataTable .= '<tr><th>Cash $</th><td>'.$totalCash.'</td></tr>';
	$dataTable .= '<tr><th>Visa $</th><td>'.$totalVisa.'</td></tr>';
	$dataTable .= '<tr><td class="noBorder" colspan="2">&nbsp;</td></tr>';
	$dataTable .= '<tr><th>Total Sales $</th><td>'.$totalSales.'</td></tr>';
	$dataTable .= '<tr><th>Total Return $</th><td>'.$totalReturn.'</td></tr>';
	$dataTable .= '<tr><th>Total Disc. $</th><td>'.array_sum($discAmount).'</td></tr>';
	$dataTable .= '<tr><td class="noBorder" colspan="2">&nbsp;</td></tr>';
	$dataTable .= '<tr><th>Net Sales $</th><td>'.($totalSales + $totalReturn). '</td></tr>';
	$dataTable .= '</table>';
	$dataTable .= '<br><br><p style="text-align: right; direction: rtl;">توقيع المدير المسئول:</p><br><br>';
	$dataTable .= '</div>';
}

?>

<form name="frmSalesReport" id="frmSalesReport" method="GET" action="#"  enctype="multipart/form-data">

	<label>FROM: <input type="text" id="dateFrom" name="dateFrom"></label>

	<label>TO: <input type="text" id="dateTo" name="dateTo"></label>

	<input type="submit" name="btnSubmit" value="Submit">
</form>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>

<script>

var availableDates = [<?php echo $dateRange ?>];

function available(date) {
    dmy = date.getFullYear() + "-" + ('0' + (date.getMonth() + 1)).slice(-2) + "-" + ('0' + (date.getDate())).slice(-2);
    if ($.inArray(dmy, availableDates) == -1) {
        return [false, ""];
    } else {
        return [true, "", "Available"];
    }
}

$(function() {
    $("#dateFrom").datepicker({
        dateFormat: 'yy-mm-dd',
        beforeShowDay: available
    });
    $("#dateTo").datepicker({
        dateFormat: 'yy-mm-dd',
        beforeShowDay: available
    });
});

</script>