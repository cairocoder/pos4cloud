<?php

require_once ("dbClass.php");
require_once ("transClass.php");
require_once ("userClass.php");
require_once ("sessionClass.php");

if (!empty($_POST['action']) && $_POST['action'] == "setSlip")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$transNo = $_POST['transNo'];
	$_SESSION['slipNo'] = $transNo;
}

if (!empty($_POST['action']) && $_POST['action'] == "activateSlip")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$slipNo  = $_POST['slipNo'];

	$getSlipDetails   = $db->query("SELECT saved_transfers.from_wrhs_id, saved_transfers.to_wrhs_id,
									saved_transfers.item_id, saved_transfers.size_id, saved_transfers.qty,
									items.rtp, items.item_cost
									FROM saved_transfers
									JOIN items ON saved_transfers.item_id = items.item_id
									WHERE trans_no = ".$slipNo."
									AND published  = 1");

	if (mysql_num_rows($getSlipDetails) > 0) {

		while ($rowSlipDetails = $db->fetch_array($getSlipDetails)) {
			$fromWrhsIds[] = $rowSlipDetails['from_wrhs_id'];
			$toWrhsIds[]   = $rowSlipDetails['to_wrhs_id'];
			$allData[]     = $rowSlipDetails;
		}
		$db->query("UPDATE saved_transfers SET published = 0 WHERE trans_no = ".$slipNo."");
		$unFromWrhsIds = array_unique($fromWrhsIds);
		$unToWrhsIds   = array_unique($toWrhsIds);
		foreach ($unFromWrhsIds as $fromWrhsId) {
			foreach ($unToWrhsIds as $toWrhsId) {
				$itemIds       = array();
				$sizeIds       = array();
				$itemQtys      = array();
				$itemRtps      = array();
				$itemCosts     = array();
				$totalQty      = 0;
				$subtotal      = 0;
				$totalCost     = 0;
				foreach ($allData as $rowData) {
					if ($rowData['from_wrhs_id'] == $fromWrhsId && $rowData['to_wrhs_id'] == $toWrhsId) {
						$itemIds[]     = $rowData['item_id'];
						$sizeIds[]     = $rowData['size_id'];
						$itemQtys[]    = $rowData['qty'];
						$itemRtps[]    = $rowData['rtp'];
						$itemCosts[]   = $rowData['item_cost'];
						$totalQty     += $rowData['qty'];
						$subtotal     += $rowData['qty'] * $rowData['rtp'];
						$totalCost    += $rowData['qty'] * $rowData['item_cost'];
					}
				}
				if ($totalQty != 0) {
					$trans->itemId      = $itemIds;
					$trans->branchFrom  = $fromWrhsId;
					$trans->branchTo    = $toWrhsId;
					$trans->stockKeeper = $_SESSION['user_id'];
					$trans->locId       = $fromWrhsId;
					$trans->sizeId      = $sizeIds;
					$trans->qty         = $itemQtys;
					$trans->totalQty    = $totalQty;
					$trans->subtotal    = $subtotal;
					$trans->totalCost   = $totalCost;
					$trans->rtp         = $itemRtps;
					$trans->cost    	= $itemCosts;

					try {
						$trans->activateSlip();
					    // If we arrive here, it means that no exception was thrown
					    // i.e. no query has failed, and we can commit the transaction
					    $db->query("COMMIT");
					} catch (Exception $e) {
					    // An exception has been thrown
					    // We must rollback the transaction
					    $db->query("ROLLBACK");
					}
				}
			}
		}
	} else {
		echo "bad";
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "getItemStockSizeDetails")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$itemId      = $_POST['itemId'];
	$sizeId      = $_POST['sizeId'];
	$locId       = $_POST['locId'];
	if (!empty($_SESSION['slipBranches'])) {
		$allBranches = $_SESSION['slipBranches'];
	} else {
		$allBranches   = userClass::getAllBranchesShort();
	}
	$allSizes   = userClass::getItemSizes($itemId);
	$colspan    = count($allSizes);
	$dataTable  = '<div class="wrapper" style="overflow:auto;height:500px;">';
	$dataTable .= '<table class="scroll">';
	$dataTable .= '<thead style="background-color:white">';
	$dataTable .= '<tr>';
	$dataTable .= '<th>Item#</th><td colspan="'.($colspan+2).'">'.$itemId.'</td>';
	$dataTable .= '</tr>';
	$dataTable .= '<tr>';
	$dataTable .= '<th>LOC</th><td colspan="'.($colspan+2).'">'.userClass::getBranchShortName($locId).'<span style="display:none" id="fromLocId">'.$locId.'<span></td>';
	$dataTable .= '</tr>';
	$dataTable .= '<tr>';
	$dataTable .= '<th>Size/Qty</th><td colspan="2"></td>';
	foreach ($allSizes as $oneSize) {
		$dataTable .= '<td><span class="fromItemSizeQty2" sizeId="'.$oneSize['size_id'].'" style="display:none">'.userClass::getItemSizeStock($itemId, $oneSize['size_id'], $locId).'</span><span>'.$oneSize['desc'].' / </span>
		<input style="width: 25px;text-align:center" type="text" class="fromItemSizeQty" sizeId="'.$oneSize['size_id'].'" value="'.userClass::getItemSizeStock($itemId, $oneSize['size_id'], $locId).'" disabled></td>';
	}
	$dataTable .= '</tr>';
	$dataTable .= '</thead>';
	$dataTable .= '<tbody>';
	foreach ($allBranches as $key => $value) {
		if ($key == $locId) {
			continue;
		}
		$dataTable .= '<tr>';
		$dataTable .= '<th rowspan="2">'.$value.'</th>';
		$dataTable .= '<th>Sales</th>';
		$dataTable .= '<td>'.userClass::getItemTotalSales2($itemId, $key).'</td>';
		foreach ($allSizes as $oneSize) {
			$dataTable .= '<td rowspan="2">
			<input style="width: 25px;text-align:center" type="text" class="itemSizeQty" itemId="'.$itemId.'" locId="'.$key.'" sizeId="'.$oneSize['size_id'].'" value="">
			</td>';
		}
		$dataTable .= '</tr>';
		$dataTable .= '<tr>';
		$dataTable .= '<th>Stock</th>';
		$dataTable .= '<td>'.userClass::getItemSizeStock($itemId, $sizeId, $key).'</td>';
		$dataTable .= '</tr>';
	}
	$dataTable .= '</tbody>';
	$dataTable .= '</table>';
	$dataTable .= '</div>';
	$dataTable .= '<br>';
	$dataTable .= '<input style="padding: 10px;width:100%" type="button" id="saveTransfer" value="Save Transfer">';
		
	echo $dataTable;
}

if (!empty($_POST['action']) && $_POST['action'] == "getSlipDetails")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$slipNo  = $_POST['slipNo'];
	$query   = "SELECT saved_transfers.id, saved_transfers.item_id, saved_transfers.qty, lFrom.short_desc AS lFrom,
				lTo.short_desc AS lTo, items_size.desc AS size
				FROM saved_transfers
				JOIN locations AS lFrom ON saved_transfers.from_wrhs_id = lFrom.loc_id
				JOIN locations AS lTo   ON saved_transfers.to_wrhs_id   = lTo.loc_id
				JOIN items_size         ON saved_transfers.size_id      = items_size.size_id
				WHERE saved_transfers.trans_no  = '".$slipNo."'
				AND   saved_transfers.published = 1";
	$result  = $db->query($query);
	$dataTable  = '<input type="button" id="removeAllSlips" slipNo="'.$slipNo.'" value="Remove All"><br><br>';
	$dataTable .= '<table id="tblSlipDetails">';
	$dataTable .= '<thead>';
	$dataTable .= '<tr>';
	$dataTable .= '<th>From</th><th>To</th><th>Item#</th><th>Size</th><th>Qty</th><th></th>';
	$dataTable .= '</tr>';
	$dataTable .= '</thead>';
	$dataTable .= '<tbody>';
	while ($row = $db->fetch_array($result)) {
		$dataTable .= '<tr>';
		$dataTable .= '<td>'.$row['lFrom'].'</td><td>'.$row['lTo'].'</td><td>'.$row['item_id'].'</td><td>'.$row['size'].'</td><td>'.$row['qty'].'</td><td><a href="#" class="removeSlip" slipId="'.$row['id'].'">Remove</a></td>';
		$dataTable .= '</tr>';
	}
	$dataTable .= '</tbody>';
	$dataTable .= '</table>';

	echo $dataTable;
}

if (!empty($_POST['action']) && $_POST['action'] == "removeSlip")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$slipId      = $_POST['slipId'];
	$query   = "UPDATE saved_transfers SET published = 0
				WHERE id = ".$slipId."";
	$result  = $db->query($query);
}

if (!empty($_POST['action']) && $_POST['action'] == "removeAllSlips")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$slipNo  = $_POST['slipNo'];
	$query   = "UPDATE saved_transfers SET published = 0
				WHERE trans_no = ".$slipNo."";
	$result  = $db->query($query);
}

if (!empty($_POST['action']) && $_POST['action'] == "saveTransfer")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$itemIds   = $_POST['itemIds'];
	$sizeIds   = $_POST['sizeIds'];
	$itemQtys  = $_POST['itemQtys'];
	$locIds    = $_POST['locIds'];
	$fromLocId = $_POST['fromLocId'];

	for ($i=0; $i < count($locIds); $i++) {

		$trans->itemId      = $itemIds[$i];
		$trans->branchFrom  = $fromLocId;
		$trans->branchTo    = $locIds[$i];
		$trans->sizeId      = $sizeIds[$i];
		$trans->qty         = $itemQtys[$i];
		$trans->totalQty    = $itemQtys[$i];
		$trans->transNo     = $_SESSION['slipNo'];

		try {
			$trans->saveTransfer();
		    // If we arrive here, it means that no exception was thrown
		    // i.e. no query has failed, and we can commit the transaction
		    $db->query("COMMIT");
		} catch (Exception $e) {
		    // An exception has been thrown
		    // We must rollback the transaction
		    $db->query("ROLLBACK");
		}
	}
}