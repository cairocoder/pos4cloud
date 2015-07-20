<?php

require_once ("_inc/header2.php");
require_once ("_inc/transClass.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

?>
<style type="text/css">
	.ui-datepicker-calendar, .ui-datepicker-current {
	    display: none;
	}
</style>
<link href="_css/print.css" rel="stylesheet" type="text/css" media="print" />
<h1>Cost Report Window</h1>

<?php

if(isset($_GET['btnSubmit']) && !empty($_GET['pickDate']))
{
	$pickDate = explode('-', $_GET['pickDate']);
	$year     = $pickDate[0];
	$month    = $pickDate[1];

	if ($_GET['reportType'] == 1)
	{
		$result   = $db->query("SELECT SUM(inventory_detail.qty * inventory_detail.cost) AS total, locations.short_desc AS loc
								FROM invoice_detail
								LEFT JOIN locations  ON invoice_detail.loc_id = locations.loc_id
								LEFT JOIN  inventory_header ON inventory_header.invo_no  = invoice_detail.invo_no
								AND   inventory_header.wrhs_id = invoice_detail.loc_id
								LEFT JOIN  inventory_detail ON inventory_header.trans_no = inventory_detail.trans_no
								AND   inventory_header.wrhs_id = inventory_detail.wrhs_id
								AND   inventory_detail.item_id = invoice_detail.item_id
								AND   inventory_detail.serial  = invoice_detail.serial
								WHERE inventory_header.date BETWEEN '".$year."-".$month."-1' AND '".$year."-".$month."-31'
								GROUP BY locations.short_desc");
	} else {
		$result   = $db->query("SELECT SUM(invoice_detail.qty * invoice_detail.rtp) AS total, locations.short_desc AS loc
								FROM invoice_detail
								LEFT JOIN locations  ON invoice_detail.loc_id = locations.loc_id
								LEFT JOIN  inventory_header ON inventory_header.invo_no  = invoice_detail.invo_no
								AND   inventory_header.wrhs_id = invoice_detail.loc_id
								LEFT JOIN  inventory_detail ON inventory_header.trans_no = inventory_detail.trans_no
								AND   inventory_header.wrhs_id = inventory_detail.wrhs_id
								AND   inventory_detail.item_id = invoice_detail.item_id
								AND   inventory_detail.serial  = invoice_detail.serial
								WHERE inventory_header.date BETWEEN '".$year."-".$month."-1' AND '".$year."-".$month."-31'
								GROUP BY locations.short_desc");
	}

	if(mysql_num_rows($result) == 0)
	{
		echo '<script>window.location = "costReport.php"</script>';
	}

	$dataTable  = '<div id="reportWrapper2">';
	$dataTable .= '<p>Year: '.$year.'<br>Month: '.$month.'</p><br>';
	$dataTable .= '<p>Stores Cost Report</p><br>';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>Store</th><th>Total Cost</th>';
	$sumTotal   = 0;
	while ($row = mysql_fetch_array($result))
	{
		$sumTotal  += $row['total'];
		$dataTable .= '<tr>';
		$dataTable .= '<td>'.$row['loc'].'</td>';
		$dataTable .= '<td>'.$row['total'].'</td>';
		$dataTable .= '</tr>';
	}
	$dataTable .= '<tr><th>Total</th><th>'.$sumTotal.'</th></td>';
	$dataTable .= '</table>';
	$dataTable .= '</div>';
}

?>

<form name="frmStockReport" id="frmStockReport" method="GET" action="#"  enctype="multipart/form-data">

	<label>Date: <input type="text" id="pickDate" name="pickDate"></label><br><br>
	<label><input type="radio" id="getCost" name="reportType" value="1" checked>Cost</label>
	<label><input type="radio" id="getSalePrice" name="reportType" value="2">Sale Price</label><br><br>
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

<script>
$(function() {
    $("#pickDate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm',
        showButtonPanel: true,
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
});
</script>