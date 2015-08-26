<?php

require_once ("_inc/header.php");
require_once ("_inc/transClass.php");

?>

<link href="_css/print.css" rel="stylesheet" type="text/css" media="print" />

<h1>Yesterday Sales Window</h1>

<?php

	$locId     = $_SESSION['loc_id'];
	$yesterday = date('Y-m-d', strtotime("-1 days"));

	//get yesterday sales
	$getYDSales   = $db->query("SELECT   SUM(invoice_detail.qty) AS `qty`, invoice_detail.item_id, invoice_detail.size_id
								FROM     invoice_detail
								JOIN     invoice_header ON invoice_detail.loc_id = invoice_header.loc_id
								AND      invoice_detail.invo_no = invoice_header.invo_no
								WHERE    invoice_header.date    = '{$yesterday}'
								AND      invoice_header.loc_id  = '{$locId}'
								GROUP BY invoice_detail.item_id
								ORDER BY invoice_detail.item_id, invoice_detail.size_id");

	//get location name
	$locName = userClass::getBranchName($locId);

	$dataTable   = '<div id="reportWrapper">';
	$dataTable  .= '<a onclick="doPrint()" style="cursor: pointer; display: block; padding: 10px; color: white; background: red; text-decoration: none; font-weight: bold;">Print</a>';
	$dataTable  .= '<br><p><img src="_img/logo-black.png"></p>';
	$dataTable  .= '<br><p style="font-weight: bold;">'.$locName.'</p><br>';
	$dataTable  .= '<p>'.$yesterday.'</p><br>';
	$dataTable  .= '<table id="tblInvoiceDetail">
					<tr>
						<th>Item</th>
						<th>Size</th>
						<th>Qty</th>
					</tr>';

	while ($rowYDSales = $db->fetch_array($getYDSales))
	{
		$dataTable  .= '<tr>
							<td>'.$rowYDSales['item_id'].'</td>
							<td>'.userClass::getSizeDesc($rowYDSales['size_id']).'</td>
							<td>'.$rowYDSales['qty'].'</td>
						</tr>';
	}
	$dataTable .= '</table>';
	$dataTable .= '<br><br>';
	$dataTable .= '</div>';
	echo $dataTable;

?>

<?php

require_once ("_inc/footer.php");

?>