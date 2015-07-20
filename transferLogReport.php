<?php

require_once ("_inc/header2.php");
require_once ("_inc/transClass.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

?>

<link href="_css/print.css" rel="stylesheet" type="text/css" media="print" />

<h1>Transfer Log Report Window</h1>

<?php

$today = date('Y-m-d');

$locId = $_SESSION['loc_id'];

$getDate = $db->query("SELECT DISTINCT(`date`) FROM trans_log");

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

	$result   = $db->query("SELECT * FROM trans_log
						    WHERE `date` BETWEEN CAST('{$dateFrom}' AS DATE)
						    AND   CAST('{$dateTo}' AS DATE)");

	if(mysql_num_rows($result) == 0)
	{
		echo '<script>window.location = "inventoryReport.php"</script>';
	}

	$dataTable  = '<div id="reportWrapper2">';
	$dataTable .= '<p>From: '.$dateFrom.'<br>To: '.$dateTo.'</p><br>';
	$dataTable .= '<p>Transfer Log Report</p><br>';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>Trans#</th><th>Type</th><th>Date</th><th>Time</th><th>From</th><th>To</th><th>Item</th><th>Size</th><th>Reason</th><th>By</th><th>Comments</th><th></th><th>Status</th></tr>';
	while ($row = mysql_fetch_array($result))
	{
		$rowInvnHeader = $trans->getInventoryHeader($row['trans_no'], $row['wrhs_id']);
		if (!$rowInvnHeader) continue;
		$dataTable .= '<tr>';
		$dataTable .= '<td>'.$row['trans_no'].'</td>';
		$dataTable .= '<td>'.$trans->getTransType($rowInvnHeader['trans_type_id']).'</td>';
		$dataTable .= '<td>'.$row['date'].'</td>';
		$dataTable .= '<td>'.date('h:i:s A', strtotime($row['time'])).'</td>';
		$dataTable .= '<td>'.userClass::getBranchName($rowInvnHeader['from_wrhs_id']).'</td>';
		$dataTable .= '<td>'.userClass::getBranchName($rowInvnHeader['to_wrhs_id']).'</td>';
		$dataTable .= '<td>'.$row['item_id'].'</td>';
		$dataTable .= '<td>'.userClass::getSizeDesc($row['size_id']).'</td>';
		$dataTable .= '<td>'.userClass::getReason($row['reason_id']).'</td>';
		$dataTable .= '<td>'.userClass::getUserName($row['user_id']).'</td>';
		$getInvnComments= $db->query("SELECT `comments` FROM inventory_header
									  WHERE trans_no = ".$row['trans_no']."
									  AND wrhs_id = ".$rowInvnHeader['wrhs_id']."");

		$rowInvnComments = $db->fetch_array($getInvnComments);

		if(!empty($rowInvnComments['comments']))
		{
			$dataTable .= '<td><a
			   id="viewInvnComments"
			   href="#"
			   transNo="'.$row['trans_no'].'"
			   >Show</a></td>';
		} else {
			$dataTable .= '<td></td>';	
		}
		
		$dataTable .= '<td><a
					   id="viewInvnDetails"
					   href="#"
					   transNo="'.$row['trans_no'].'"
					   wrhsId="'.$row['wrhs_id'].'"
					   >Details</a></td>';

		if ($row['trans_type_id'] == 4 && $row['status'] == 2 && $row['wrhs_id'] == $locId) {
			$dataTable .= '<td>'.$trans->getStatusType($row['status']).'<br>';
			$dataTable .= '<button
					   class="proceedTrans"
					   transNo="'.$row['trans_no'].'"
					   >Proceed</button>';
			$dataTable .= '<button
					   class="cancelTrans"
					   transNo="'.$row['trans_no'].'"
					   >Cancel</button>';
			$dataTable .= '<a href="transferBox.php?transNo='.$row['trans_no'].'"
					   class="editTrans"
					   transNo="'.$row['trans_no'].'"
					   >Edit</a></td>';
		} else {
			$dataTable .= '<td>'.$trans->getStatusType($rowInvnHeader['status']).'</td>';
		}

		$dataTable .= '</tr>';
	}
	$dataTable .= '</table>';
	$dataTable .= '</div>';
}

?>

<form name="frmStockReport" id="frmStockReport" method="GET" action="#"  enctype="multipart/form-data">

	<label>FROM: <input type="text" id="dateFrom" name="dateFrom"></label>
	<label>TO: <input type="text" id="dateTo" name="dateTo"></label>
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