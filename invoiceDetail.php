<?php

require_once ("_inc/header.php");
require_once ("_inc/transClass.php");

?>

<link href="_css/print.css" rel="stylesheet" type="text/css" media="print" />

<h1>Invoice Details Window</h1>

<?php
if (!empty($_GET['invoNo'])) {
	$locId  = $_SESSION['loc_id'];
	$invoNo = $_GET['invoNo'];

	#get invoice header
	$getInvoiceHeader = $db->query("SELECT * FROM `invoice_header`
									WHERE loc_id   = '{$locId}'
									AND   invo_no  = '{$invoNo}'");

	#get invoice detail
	$getInvoiceDetail = $db->query("SELECT * FROM invoice_detail
									WHERE loc_id  = '{$locId}'
									AND   invo_no = '{$invoNo}'");

	$rowInvoiceHeader = $db->fetch_array($getInvoiceHeader);

	#get location name
	$locName = userClass::getBranchName($locId);

	#get customer name/mobile
	$getCustomerName  = $db->query("SELECT `cust_name`, `cust_tel` FROM customers
									WHERE cust_id = '".$rowInvoiceHeader['cust_id']."'");

	$rowCustomerName  = $db->fetch_array($getCustomerName);
	$totalQty         = 0;

	$dataTable  = '<div id="reportWrapper">';
	$dataTable .= '<a onclick="doPrint()" style="cursor: pointer; display: block; padding: 10px; color: white; background: red; text-decoration: none; font-weight: bold;">Print</a>';
	$dataTable .= '<br><p><img src="_img/logo-black.png"></p>';
	$dataTable .= '<br><p style="font-weight: bold;">'.$locName.'</p>';
	$dataTable .= '<br><p>Invoice# '.$rowInvoiceHeader['invo_no'].'</p>';
	$dataTable .= '<p>Invoice Type: '.$trans->getTransType($rowInvoiceHeader['invo_type']).'</p>';
	$dataTable .= '<br><p>Mobile# '.$rowCustomerName['cust_tel'].'</p>';
	$dataTable .= '<p>Name: '.$rowCustomerName['cust_name'].'</p>';
	$dataTable .= '<p>'.$rowInvoiceHeader['date'].' / '.date('h:i:s', strtotime($rowInvoiceHeader['time'])).'</p><br>';
	$dataTable .= '<table id="tblInvoiceDetail"><tr><th></th><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th></th></tr>';
	while ($rowInvoiceDetail = $db->fetch_array($getInvoiceDetail))
	{
		$dataTable .= '<tr><td>'.$rowInvoiceDetail['serial'].'</td>
					   <td>'.$rowInvoiceDetail['item_id'].'</td>
					   <td>'.$rowInvoiceDetail['rtp'].'</td>
					   <td>'.$rowInvoiceDetail['qty'].'</td>
					   <td>'.number_format($rowInvoiceDetail['qty'] * $rowInvoiceDetail['rtp'], 2, '.', '').'</td>';
		if ($rowInvoiceDetail['type'] == 1) { $dataTable .= "<td>S</td></tr>"; } else { $dataTable .= "<td>R</td></tr>"; }
		$totalQty  += $rowInvoiceDetail['qty'];
	}
	$dataTable .= '<tr><td class="noBorder" colspan="4">&nbsp</td></tr><tr><td class="noBorder" colspan="4">&nbsp</tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="5">Total Qty</th><td style="text-align: center;">'.$totalQty.'</td></tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="5">Total Price</th><td style="text-align: center;">'.$rowInvoiceHeader['total_amount'].'</td></tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="5">Discount</th><td style="text-align: center;">'.$rowInvoiceHeader['discount_amount'].'</td></tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="5">Net Value</th><td style="text-align: center;">'.$rowInvoiceHeader['net_value'].'</td></tr>';
	if ($rowInvoiceHeader['payment_type_id'] == 3)
	{
		$dataTable .= '<tr style="text-align: left;"><th colspan="5">Cash</th><td style="text-align: center;">'.$rowInvoiceHeader['split'].'</td></tr>';
		$dataTable .= '<tr style="text-align: left;"><th colspan="5">Visa</th><td style="text-align: center;">'.number_format($rowInvoiceHeader['net_value'] - $rowInvoiceHeader['split'], 2, '.', '').'</td></tr>';
	}
	$dataTable .= '<tr style="text-align: left;"><th colspan="5">Payment Type</th><td style="text-align: center;">'.transClass::getPaymentType($rowInvoiceHeader['payment_type_id']).'</td></tr>';
	$dataTable .= '</table>';
	$dataTable .= '<br><p>Thanks for shopping at RAViN Jeanswear</p>';
	$dataTable .= '<br><p>For any inquiry or complaints please contact customer service
				   at: 01140003274<br>From: 10:00am To: 06:00pm except Friday.</p>';
	$dataTable .= '<br><br>';
	$dataTable .= '</div>';
}
?>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>