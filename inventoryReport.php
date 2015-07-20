<?php

require_once ("_inc/header.php");
require_once ("_inc/transClass.php");

?>

<link href="_css/print.css" rel="stylesheet" type="text/css" media="print" />

<h1>Inventory Report Window</h1>

<?php

$today = date('Y-m-d');

$locId = $_SESSION['loc_id'];

$getDate = $db->query("SELECT DISTINCT(`date`) FROM inventory_header
					   WHERE wrhs_id        = '{$locId}'
					   OR    from_wrhs_id   = '{$locId}'
					   OR    to_wrhs_id     = '{$locId}'
					   AND   trans_type_id NOT IN (1, 2, 3, 5)");

$trackingTypes   = array();
$getTrackingList = $db->query("SELECT * FROM tracking_types");
while ($rowGetTrackingTypes = $db->fetch_array($getTrackingList)) {
	$trackingTypes['tracking_id'][] = $rowGetTrackingTypes['tracking_id'];
	$trackingTypes['desc'][]        = $rowGetTrackingTypes['desc'];
}
// var_dump($trackingTypes);
// die();

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
	$selTrackingType = $_GET['selTrackingType'];

	$query     = "SELECT * FROM inventory_header";
	$condition = " WHERE ";
	if ($selTrackingType != 0) {
		$query  .= " JOIN trans_tracking ON inventory_header.wrhs_id = trans_tracking.wrhs_id
					 AND inventory_header.trans_no = trans_tracking.trans_no
					 ".$condition." trans_tracking.tracking_id = " . $selTrackingType;
		$condition = " AND ";
	}
	
	$query  .=  $condition . "inventory_header.date BETWEEN CAST('{$dateFrom}' AS DATE)
				AND   CAST('{$dateTo}' AS DATE)
				AND   (inventory_header.wrhs_id = '{$locId}'
				OR    inventory_header.from_wrhs_id   = '{$locId}'
				OR    inventory_header.to_wrhs_id     = '{$locId}')
				AND   inventory_header.trans_type_id NOT IN (1, 2, 3, 5)";
	
	$result = $db->query($query);
	
	if(mysql_num_rows($result) == 0)
	{
		echo '<script>window.location = "inventoryReport.php"</script>';
	}

	$dataTable  = '<div id="reportWrapper2">';
	$dataTable .= '<br><p>'.userClass::getBranchName($locId).'</p><br>';
	$dataTable .= '<p>From: '.$dateFrom.'<br>To: '.$dateTo.'</p><br>';
	$dataTable .= '<p>Tranactions Report</p><br>';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>Trans#</th><th>Type</th><th>Date</th><th>Done/Date</th><th>Done/By</th><th>From</th><th>To</th><th>Total</th><th>Qty.</th><th>Comments</th><th></th><th></th><th></th><th>Status</th><th>Tracking</th></tr>';
	while ($row = mysql_fetch_array($result))
	{
		$dataTable .= '<tr>';
		$dataTable .= '<td>'.$row['trans_no'].'</td>';
		$dataTable .= '<td>'.$trans->getTransType($row['trans_type_id']).'</td>';
		$dataTable .= '<td>'.$row['date'].'</td>';
		//$dataTable .= '<td>'.date('h:i:s A', strtotime($row['time'])).'</td>';
		if ($row['status'] == 1) {
			$getData  = $db->query("SELECT `date`, `stock_keeper_id` FROM inventory_header
									WHERE `doc_no`        = '".$row['doc_no']."'
									AND   `from_wrhs_id`  = '".$row['from_wrhs_id']."'
									AND   `to_wrhs_id`    = '".$row['to_wrhs_id']."'
									AND   `trans_type_id` = '5'");
			if (mysql_num_rows($getData) > 0) {
				$rowGetData = $db->fetch_array($getData);
				$dataTable .= '<td>'.$rowGetData['date'].'</td>';
				$dataTable .= '<td>'.userClass::getUserName($rowGetData['stock_keeper_id']).'</td>';
			} else {
				$dataTable .= '<td></td>';
				$dataTable .= '<td></td>';
			}
		} else {
			$dataTable .= '<td></td>';
			$dataTable .= '<td></td>';
		}
		$dataTable .= '<td>'.userClass::getBranchName($row['from_wrhs_id']).'</td>';
		$dataTable .= '<td>'.userClass::getBranchName($row['to_wrhs_id']).'</td>';
		$dataTable .= '<td>'.$row['total_rtp'].'</td>';
		$dataTable .= '<td>'.$row['qty'].'</td>';
		$getInvnComments= $db->query("SELECT `comments` FROM inventory_header
									  WHERE trans_no = ".$row['trans_no']."
									  AND wrhs_id = ".$row['wrhs_id']."");

		$rowInvnComments = $db->fetch_array($getInvnComments);

		if(!empty($rowInvnComments['comments']))
		{
			$dataTable .= '<td><a
			   id="viewInvnComments"
			   href="#"
			   transNo="'.$row['trans_no'].'"
			   wrhsId="'.$row['wrhs_id'].'"
			   >Show</a></td>';
		} else {
			$dataTable .= '<td></td>';	
		}
		
		$dataTable .= '<td><a
					   id="viewInvnDetails"
					   href="#"
					   transNo="'.$row['trans_no'].'"
					   wrhsId="'.$row['wrhs_id'].'"
					   >Full Details</a></td>';

		$dataTable .= '<td><a
					   id="viewInvnDetails2"
					   href="#"
					   transNo="'.$row['trans_no'].'"
					   wrhsId="'.$row['wrhs_id'].'"
					   >Details</a></td>';

		$dataTable .= '<td><a
					   id="exportInvnExcel"
					   href="#"
					   transNo="'.$row['trans_no'].'"
					   wrhsId="'.$row['wrhs_id'].'"
					   >Export</a></td>';

		if ($row['trans_type_id'] == 4 && $row['status'] == 2 && ($row['wrhs_id'] == $locId || $row['from_wrhs_id'] == $locId)) {
			$dataTable .= '<td>'.$trans->getStatusType($row['status']).'<br>';
			$dataTable .= '<button
					   class="proceedTrans"
					   transNo="'.$row['trans_no'].'"
					   wrhsId="'.$row['wrhs_id'].'"
					   >Proceed</button>';
			if ($_SESSION['user_type'] == "sadmin") {
				$dataTable .= '<button
					   class="cancelTrans"
					   transNo="'.$row['trans_no'].'"
					   wrhsId="'.$row['wrhs_id'].'"
					   >Cancel</button>';
			}
			$dataTable .= '<a href="transferBox.php?transNo='.$row['trans_no'].'&wrhsId='.$row['wrhs_id'].'"
					   class="editTrans"
					   transNo="'.$row['trans_no'].'"
					   wrhsId="'.$row['wrhs_id'].'"
					   >Edit</a></td>';
		} else {
			$dataTable .= '<td>'.$trans->getStatusType($row['status']).'</td>';
		}
		unset($rowGetCurTracking);
		$getCurTracking = $db->query("SELECT tracking_types.desc, trans_tracking.tracking_id, trans_tracking.trans_tracking_id
									  FROM trans_tracking
									  JOIN tracking_types ON trans_tracking.tracking_id = tracking_types.tracking_id
									  WHERE trans_tracking.trans_no = ".$row['trans_no']."
									  AND trans_tracking.wrhs_id = ".$row['wrhs_id']."");
		
		if ($db->num_rows($getCurTracking) > 0) {
			$rowGetCurTracking = $db->fetch_array($getCurTracking);
		}

		if ($row['wrhs_id'] == $locId || $row['from_wrhs_id'] == $locId) {

			$dataTable .= '<td>';
			echo $rowGetCurTracking['tracking_types.desc'];
			$dataTable .= $rowGetCurTracking['desc'];
			$dataTable .= '<select
						   style="width:70px"
						   class="selTracking"
						   transNo="'.$row['trans_no'].'"
					   	   wrhsId="'.$row['wrhs_id'].'">';
			$dataTable .= "<option value='0'>Select</option>";

			if (!empty($rowGetCurTracking) && $rowGetCurTracking['tracking_id'] == $trackingTypes['tracking_id']) {
				$dataTable .= "<option value='".$trackingTypes['tracking_id'][0]."' selected>".$trackingTypes['desc'][0]."</option>";
				$dataTable .= "<option value='".$trackingTypes['tracking_id'][1]."' selected>".$trackingTypes['desc'][1]."</option>";
			} else {
				$dataTable .= "<option value='".$trackingTypes['tracking_id'][0]."'>".$trackingTypes['desc'][0]."</option>";
				$dataTable .= "<option value='".$trackingTypes['tracking_id'][1]."'>".$trackingTypes['desc'][1]."</option>";
			}
			if ($row['wrhs_id'] == 55 || $row['from_wrhs_id'] == 55) {
				if (!empty($rowGetCurTracking) && $rowGetCurTracking['tracking_id'] == $trackingTypes['tracking_id']) {
					$dataTable .= "<option value='".$trackingTypes['tracking_id'][2]."' selected>".$trackingTypes['desc'][2]."</option>";
					$dataTable .= "<option value='".$trackingTypes['tracking_id'][3]."' selected>".$trackingTypes['desc'][3]."</option>";
				} else {
					$dataTable .= "<option value='".$trackingTypes['tracking_id'][2]."'>".$trackingTypes['desc'][2]."</option>";
					$dataTable .= "<option value='".$trackingTypes['tracking_id'][3]."'>".$trackingTypes['desc'][3]."</option>";
				}
			}
			$dataTable .= '</select><br>';
			$dataTable .= '<a href="#" trans_tracking_id="'.$rowGetCurTracking['trans_tracking_id'].'" class="showTrackingComments">Comments</a>';
			$dataTable .= '</td>';
		} elseif ($row['to_wrhs_id'] == $locId) {
			$dataTable .= '<td>';
			$dataTable .= $rowGetCurTracking['desc'];
			$dataTable .= '<select
						   style="width:70px"
						   class="selTracking"
						   transNo="'.$row['trans_no'].'"
					   	   wrhsId="'.$row['wrhs_id'].'">';
			$dataTable .= "<option value='0'>Select</option>";
			if (!empty($rowGetCurTracking) && $rowGetCurTracking['tracking_id'] == $trackingTypes['tracking_id']) {
				$dataTable .= "<option value='".$trackingTypes['tracking_id'][4]."' selected>".$trackingTypes['desc'][4]."</option>";
			} else {
				$dataTable .= "<option value='".$trackingTypes['tracking_id'][4]."'>".$trackingTypes['desc'][4]."</option>";
			}
			if ($row['wrhs_id'] == 55 || $row['from_wrhs_id'] == 55) {
				if (!empty($rowGetCurTracking) && $rowGetCurTracking['tracking_id'] == $trackingTypes['tracking_id']) {
					$dataTable .= "<option value='".$trackingTypes['tracking_id'][2]."' selected>".$trackingTypes['desc'][2]."</option>";
					$dataTable .= "<option value='".$trackingTypes['tracking_id'][3]."' selected>".$trackingTypes['desc'][3]."</option>";
				} else {
					$dataTable .= "<option value='".$trackingTypes['tracking_id'][2]."'>".$trackingTypes['desc'][2]."</option>";
					$dataTable .= "<option value='".$trackingTypes['tracking_id'][3]."'>".$trackingTypes['desc'][3]."</option>";
				}
			}
			$dataTable .= '</select><br>';
			$dataTable .= '<a href="#" trans_tracking_id="'.$rowGetCurTracking['trans_tracking_id'].'" class="showTrackingComments">Comments</a>';
			$dataTable .= '</td>';
		} else {
			if (!empty($rowGetCurTracking)) {
				$dataTable .= '<td>'.$rowGetCurTracking['desc'].'</td>';
			} else {
				$dataTable .= '<td>No Tracking Data</td>';
			}
		}

		$dataTable .= '</tr>';
	}
	$dataTable .= '</table>';
	$dataTable .= '</div>';
}

?>

<form name="frmStockReport" id="frmStockReport" method="GET" action="#"  enctype="multipart/form-data">

	<p><a id="exportBranchInventory" href="#">Export Transactions to Excel</a></p><br>
	<label><input type="radio" name="selBranch" value="1" checked> Current Branch</label>
	<?php if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "analyst"): ?>
	<label><input type="radio" name="selBranch" value="2"> All Branches</label>
	<?php endif; ?>
	<br><br>

	<label>FROM: <input type="text" id="dateFrom" name="dateFrom"></label>
	<label>TO: <input type="text" id="dateTo" name="dateTo"></label>
	<input type="submit" name="btnSubmit" value="Submit">
	<br><br>

	<label>Tracking:
		<select name="selTrackingType" id="selTrackingType">
		<option value='0'>Select</option>
		<?php for ($i=0; $i < count($trackingTypes['tracking_id']); $i++): ?>
			<option value="<?php echo $trackingTypes['tracking_id'][$i]; ?>"><?php echo $trackingTypes['desc'][$i]; ?></option>
		<?php endfor; ?>
		</select>
	</label>

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