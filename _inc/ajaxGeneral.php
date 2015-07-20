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

if (!empty($_POST['action']) && $_POST['action'] == "updateTracking")
{
	$comment  = $_POST['comment'];
	$transNo  = $_POST['transNo'];
	$wrhsId   = $_POST['wrhsId'];
	$tracking = $_POST['tracking'];
	$userId   = $_SESSION['user_id'];
	$userType = $_SESSION['user_type'];

	$getCurTracking = $db->query("SELECT tracking_id FROM trans_tracking
								  WHERE trans_no = ".$transNo."
								  AND wrhs_id = ".$wrhsId."");

	if ($db->num_rows($getCurTracking) > 0) {
		$rowCurTracking = $db->fetch_array($getCurTracking);
		$trackingId     = $rowCurTracking['tracking_id'];

		if ($trackingId < $tracking || $userType == "sadmin") {
			$db->query("UPDATE trans_tracking SET tracking_id = ".$tracking."
						WHERE trans_no = ".$transNo."
						AND wrhs_id = ".$wrhsId."");
			$getTransTrackingId   = $db->query("SELECT trans_tracking_id FROM trans_tracking
												WHERE  trans_no = ".$transNo."
												AND wrhs_id = ".$wrhsId."");
			$rowTransTrackingId = $db->fetch_array($getTransTrackingId);
			$transTrackingId = $rowTransTrackingId['trans_tracking_id'];
			$db->query("INSERT INTO tracking_comments (comment, trans_tracking_id, user_id)
						VALUES ('".$comment."', '".$transTrackingId."', '".$userId."')");
			echo "Done";
		} else {
			echo "Sorry, you can't roll back the tracking!";
		}
	} else {
		$db->query("INSERT INTO trans_tracking SET
					tracking_id = ".$tracking.",
					trans_no = ".$transNo.",
					wrhs_id = ".$wrhsId."");
		$getTransTrackingId   = $db->query("SELECT trans_tracking_id FROM trans_tracking
											WHERE  trans_no = ".$transNo."
											AND wrhs_id = ".$wrhsId."");
		$rowTransTrackingId = $db->fetch_array($getTransTrackingId);
		$transTrackingId = $rowTransTrackingId['trans_tracking_id'];
		$db->query("INSERT INTO tracking_comments (comment, trans_tracking_id, user_id)
					VALUES ('".$comment."', '".$transTrackingId."', '".$userId."')");
		echo "Done";
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "selCompareBy")
{
	$selCompareBy = $_POST['selCompareBy'];

	switch ($selCompareBy) {
		case 1:
			$data  = userClass::getAllBranchesShort();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;

		case 2:
			$data  = userClass::getAllYears();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $value) {
				$html .= '<option value="'.$value.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;

		case 3:
			$data  = userClass::getAllYears();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			$html .= '<option value="1">1</option>
					  <option value="2">2</option>
					  <option value="3">3</option>
					  <option value="4">4</option>
					  <option value="5">5</option>
					  <option value="6">6</option>
					  <option value="7">7</option>
					  <option value="8">8</option>
					  <option value="9">9</option>
					  <option value="10">10</option>
					  <option value="11">11</option>
					  <option value="12">12</option>';
			$html .= '</select>';
			echo $html;
		break;

		case 4:
			$data  = userClass::getAllDepts();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;

		case 5:
			$data  = userClass::getAllSubDepts();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;

		case 6:
			$data  = userClass::getAllGenders();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;

		case 7:
			$data  = userClass::getAllSeasons();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;

		case 8:
			$data  = userClass::getAllAttrs();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;

		case 9:
			$data  = userClass::getAllVends();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;

		case 10:
			$data  = userClass::getAllDesc();
			$html  = '<select name="selViewCompare[]" id="selViewCompare" multiple="multiple">';				
			foreach ($data as $key => $value) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$html .= '</select>';
			echo $html;
		break;
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "updateBulkItems")
{
	$allItems   = implode($_POST['allItems'], "','");
	$updateData = $_POST['updateData'];
	$tableRow   = $_POST['tableRow'];
	$db->query("UPDATE items SET ".$tableRow."=".$updateData." WHERE item_id IN ('".$allItems."')");
}

if (!empty($_POST['action']) && $_POST['action'] == "chkVoucher")
{
	$voucherCode = $_POST['voucherCode'];
	$curDate     = date('Y-m-d');
	$userId      = $_SESSION['user_id'];
	$locId       = $_SESSION['loc_id'];
	$totalRtp    = $_POST['totalRtp'];
	$totalMsrp   = $_POST['totalMsrp'];

	$result   = $db->query("SELECT * FROM vouchers WHERE voucher_code = '{$voucherCode}'
							LIMIT 1");
	
	if ($db->num_rows($result)) {

		$chkVoucher = $db->fetch_array($result);
		$voucherId  = $chkVoucher['voucher_id'];
		if ($chkVoucher['sale'] == 1) {
			$curValue = $totalRtp;
		} else {
			$curValue = $totalMsrp;
		}

		$dataArray = array();

		if ($chkVoucher['start_date'] > $curDate) {
			$dataArray['err'] = 1;
			echo json_encode($dataArray);
		} elseif ($chkVoucher['end_date'] < $curDate ) {
			$dataArray['err'] = 2;
			echo json_encode($dataArray);
		} elseif ($chkVoucher['used'] == 1 && $chkVoucher['remain'] == 0) {
			$dataArray['err'] = 3;
			echo json_encode($dataArray);
		} elseif ($chkVoucher['remain'] < $curValue) {
			$dataArray['err'] = 4;
			echo json_encode($dataArray);
		} else {
			$dataArray['sale'] = $chkVoucher['sale'];
			// // Get invoice number
			// $getInvoNo    = $db->query("SELECT MAX(invo_no)
			// 							AS invo_no 
			// 							FROM invoice_header
			// 							WHERE loc_id = '".$_SESSION['loc_id']."'");

			// $rowInvoNo  = $db->fetch_array($getInvoNo);

			// if ($rowInvoNo['invo_no'] != NULL)
			// {
			// 	$invoNo = $rowInvoNo['invo_no'] + 1;
			// } else {
			// 	$invoNo += 1;
			// }

			// // Update voucher_log info.
			// $db->query("INSERT INTO voucher_log SET user_id = '{$userId}',
			// 			`loc_id` = '{$locId}', `invo_no` = '{$invoNo}', `value` = '{$curValue}',
			// 			`voucher_id` = '{$voucherId}'");

			// // Update voucher info.
			// $db->query("UPDATE vouchers SET used = 1, remain = remain - {$curValue}");
			$dataArray['prc'] = $chkVoucher['percentage'];
			$dataArray['loc'] = $locId;
			$dataArray['voucherId'] = $chkVoucher['voucher_id'];

			echo json_encode($dataArray);
		}
	} else {
		$dataArray['err'] = 0;
		echo json_encode($dataArray);
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "getCustomer")
{
	$custId = $_POST['custId'];

	$result   = $db->query("SELECT * FROM customers WHERE cust_id = '{$custId}' LIMIT 1");
	
	if ($db->num_rows($result)) {
		$getCustomer = $db->fetch_array($result);
		echo json_encode($getCustomer);
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "editCustomer")
{
	$txtId     = $_POST['txtId'];
	$txtName   = $_POST['txtName'];
	$txtMob    = "20" . $_POST['txtMob'];
	$txtEmail  = $_POST['txtEmail'];

	$db->query("UPDATE customers SET cust_name = '{$txtName}', cust_tel = '{$txtMob}',
				cust_email = '{$txtEmail}'
				WHERE cust_id = '{$txtId}'");
}

if (!empty($_POST['action']) && $_POST['action'] == "getVoucherLog")
{
	$voucherId = $_POST['voucherId'];

	$result   = $db->query("SELECT DISTINCT voucher_log.*, customers.cust_name, invoice_header.loc_id, invoice_header.total_amount,
							invoice_header.net_value, invoice_header.date, invoice_header.discount_amount
                			FROM voucher_log
                			LEFT JOIN invoice_header ON invoice_header.invo_no = voucher_log.invo_no
                			AND invoice_header.loc_id = voucher_log.loc_id
                			LEFT JOIN customers ON invoice_header.cust_id = customers.cust_id
                			WHERE voucher_log.voucher_id = {$voucherId}");
	if ($db->num_rows($result) > 0) {
		$data  = '<table id="tblAddNew">';
		$data .= '<thead>';
	    $data .= '<tr>';
	    $data .= '<th>Location</th><th>Invoice#</th><th>Date</th><th>Customer</th><th>Total</th><th>Discount</th><th>Net</th>';
	    $data .= '</tr>';
	    $data .= '</thead>';
	    $data .= '<tbody>';
	    while ($row = $db->fetch_array($result)) {
	    	$data .= '<tr>';
	    	$data .= '<td>'.userClass::getBranchShortName($row['loc_id']).'</td>';
		    $data .= '<td>'.$row['invo_no'].'</td>';
		    $data .= '<td>'.$row['date'].'</td>';
		    $data .= '<td>'.$row['cust_name'].'</td>';
		    $data .= '<td>'.$row['total_amount'].'</td>';
		    $data .= '<td>'.$row['discount_amount'].'</td>';
		    $data .= '<td>'.$row['net_value'].'</td>';
		    $data .= '</tr>';
	    }
	    $data .= '</tbody>';
	} else {
		$data  = '<p>No history available for this voucher!</p>';
	}
	echo $data;
}

