<?php

require_once ("_inc/header.php");
require_once ("_inc/transClass.php");

$locId      = $_SESSION['loc_id'];
$salesMen   = userClass::getSalesMen($locId);

?>

<?php
	if ($_SESSION['user_type'] != "sadmin" && $_SESSION['user_type'] != "analyst" && $_SESSION['loc_id'] != 50 && $_SESSION['loc_id'] != 51 && $_SESSION['loc_id'] != 65 && $_SESSION['loc_id'] != 44){
		echo "<script>window.location.href = 'index.php'</script>";
	}
?>

<link href="_css/print.css" rel="stylesheet" type="text/css" media="print" />

<h1>Invoices Report Window</h1>

<?php

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

if (isset($_GET['btnSubmit']) && !empty($_GET['dateFrom']) && !empty($_GET['dateTo']))
{

	$dateFrom   = $_GET['dateFrom'];
	$dateTo     = $_GET['dateTo'];

	$query      = "SELECT * FROM invoice_header
			       WHERE `date` BETWEEN CAST('{$dateFrom}' AS DATE)
			       AND CAST('{$dateTo}' AS DATE)";

	$query2     = "SELECT SUM(`net_value`) AS net_value FROM invoice_header
			       WHERE `date` BETWEEN CAST('{$dateFrom}' AS DATE)
			       AND CAST('{$dateTo}' AS DATE)";

	if ($_GET['selBranch'] == 1)
	{
		$query  .= " AND loc_id = '{$locId}'";
		$query2 .= " AND loc_id = '{$locId}'";
	}

	if ($_GET['selSalesMan'] != 0)
	{
		$query  .= " AND sales_man_id ='".$_GET['selSalesMan']."'";
		$query2 .= " AND sales_man_id ='".$_GET['selSalesMan']."'";
	}

	if (!empty($_GET['custTel']))
	{
		$query  .= " AND cust_id ='".userClass::getCustomerId($_GET['custTel'])."'";
		$query2 .= " AND cust_id ='".userClass::getCustomerId($_GET['custTel'])."'";
	}

	$result1 = $db->query($query);

	if (mysql_num_rows($result1) == 0)
	{
		echo '<script>window.location = "invoicesReport.php"</script>';
	}

	if (isset($_GET['page']))
	{
	   $page = $_GET['page'];
	} else {
	   $page = 1;
	}

	$numRows     = mysql_num_rows($result1);
	$rowsPerPage = 50;
	$lastPage    = ceil($numRows/$rowsPerPage);

	$page = (int)$page;

	if ($page > $lastPage) {
	   $page = $lastPage;
	}
	if ($page < 1) {
	   $page  	  = 1;
	   $lastPage  = 1;
	}

	$limit   = ' LIMIT ' .($page - 1) * $rowsPerPage .',' .$rowsPerPage;
	$query  .= $limit;

	$result  = $db->query($query);
	$result2 = $db->query($query2);
	$row2    = $db->fetch_array($result2);

	$dataTable  = '<div id="reportWrapper2">';
	$dataTable .= '<br><p>'.userClass::getBranchName($locId).'</p><br>';
	$dataTable .= '<p>From: '.$dateFrom.'<br>To: '.$dateTo.'</p><br>';
	$dataTable .= '<p>Tranactions Report</p><br>';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>Invoice#</th><th>Type</th><th>Date</th><th>Time</th><th>Net</th><th>Qty.</th><th>Payment</th><th>Sales Man</th><th>C.Phone</th><th>C.Name</th><th>Comments</th><th></th></tr>';
	while ($row = mysql_fetch_array($result))
	{
		$invoHeader = $trans->getInvoiceHeader($row['invo_no'], $locId);
		$dataTable .= '<tr>';
		$dataTable .= '<td>'.$invoHeader['invo_no'].'</td>';
		$dataTable .= '<td>';
		$dataTable .= $trans->getTransType($row['invo_type']);
		$dataTable .= '</td>';
		$dataTable .= '<td>'.$row['date'].'</td>';
		$dataTable .= '<td>'.date('h:i:s A', strtotime($row['time'])).'</td>';
		$dataTable .= '<td>'.$invoHeader['net_value'].'</td>';
		$dataTable .= '<td>'.$invoHeader['qty'].'</td>';
		$dataTable .= '<td>'.transClass::getPaymentType($invoHeader['payment_type_id']).'</td>';
		$dataTable .= '<td>'.$invoHeader['sales_man_id'].'-'.userClass::getSalesMan($invoHeader['sales_man_id']).'</td>';
		$dataTable .= '<td>'.userClass::getCustomerTel($invoHeader['cust_id']).'</td>';
		$dataTable .= '<td>'.userClass::getCustomerName($invoHeader['cust_id']).'</td>';

		$getInvnComments = $db->query("SELECT `comments` FROM invoice_header
									   WHERE invo_no = ".$row['invo_no']."
									   AND loc_id = '{$locId}'");

		$rowInvoComments = $db->fetch_array($getInvnComments);

		if(!empty($rowInvoComments['comments']))
		{
			$dataTable .= '<td><a
			   id="viewInvoComments"
			   href="#"
			   invoNo="'.$row['invo_no'].'"
			   >Show</a></td>';
		} else {
			$dataTable .= '<td></td>';	
		}
		
		$dataTable .= '<td><a
					   id="viewInvoDetails"
					   href="#"
					   invoNo="'.$row['invo_no'].'"
					   >Details</a></td>';
		$dataTable .= '</tr>';
	}
	$dataTable .= '<th colspan="6">Total</th>';
	$dataTable .= '<th colspan="6">'.$row2['net_value'].'</th>';
	$dataTable .= '</table>';
	$dataTable .= '</div>';

	$dataTable .= '<div style="text-align:center;clear:both;padding:15px;">';

	if ($page == 1) {
		$dataTable .= " FIRST PREV ";
	} else {
		$prevPage   = $page - 1;
		$params     = $_GET;
		if (!empty($params['page'])) {
			$params['page'] = 1;
			$paramString1 = http_build_query($params);
			$dataTable .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString1."'>FIRST</a> ";
			$params['page'] = $prevPage;
			$paramString2 = http_build_query($params);
			$dataTable .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString2."'>PREV</a> ";
		} else {
			$dataTable .= " <a href='".$_SERVER['REQUEST_URI']."?page=$prevPage'>PREV</a> ";
		}		
	}

	$dataTable .= " ( Page $page of $lastPage ) ";

	if ($page == $lastPage) {
   		$dataTable .= " NEXT LAST ";
	} else {
		$nextPage = $page + 1;
		$params = $_GET;
		if (!empty($params['page'])) {
			$params['page'] = $nextPage;
			$paramString1 = http_build_query($params);
			$dataTable .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString1."'>NEXT</a> ";
			$params['page'] = $lastPage;
			$paramString2 = http_build_query($params);
			$dataTable .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString2."'>LAST</a> ";
		} else {
			$dataTable .= " <a href='".$_SERVER['REQUEST_URI']."&page=$nextPage'>NEXT</a> ";
			$dataTable .= " <a href='".$_SERVER['REQUEST_URI']."&page=$lastPage'>LAST</a> ";
		}
	}
	$dataTable .= '</div>';
}

?>

<form name="frmSalesReport" id="frmSalesReport" method="GET" action="#"  enctype="multipart/form-data">
	
	<p><a id="exportBranchInvoices" href="#">Export Invoices to Excel</a></p><br>
	<p><a id="exportBranchInvoices2" href="#">Export Invoice Headers to Excel</a></p><br>
	<label><input type="radio" name="selBranch" value="1" checked> Current Branch</label>
	<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "analyst"): ?>
	<label><input type="radio" name="selBranch" value="2"> All Branches</label>
	<?php endif; ?>
	<br><br>

	<label>FROM: <input type="text" id="dateFrom" name="dateFrom"></label>

	<label>TO: <input type="text" id="dateTo" name="dateTo"></label>

	<br><br>

	<label>Sales Man: <select id="selSalesMan" name="selSalesMan">
		<option value="0">Select ..</option>
		<?php if (!empty($salesMen)): ?>
		<?php foreach ($salesMen as $key => $value): ?>
			<option value="<?php echo $key ?>"><?php echo $key."-".$value ?></option>
		<?php endforeach; ?>
		<?php endif; ?>
	</select></label>

	<br><br>

	<label>Customer Tel#: <input type="text" name="custTel"></label>

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