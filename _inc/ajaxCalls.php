<?php

require_once ("dbClass.php");
require_once ("transClass.php");
require_once ("userClass.php");
require_once ("sessionClass.php");

if (!empty($_POST['action']) && $_POST['action'] == "selectItem") {
	$itemId  = $_POST['txtItems'];
	$chkItem = $db->query("SELECT item_id, item_cost, msrp, rtp FROM items WHERE item_id = {$itemId}");
	if (mysql_num_rows($chkItem) == 0)
	{
		die();
	}
	$rowChkPrices = $db->fetch_array($chkItem);
	if ($rowChkPrices['item_id'] != 26684 && $rowChkPrices['item_id'] != 9600 && $rowChkPrices['item_id'] != 27004 && ($rowChkPrices['item_cost'] == 0 || $rowChkPrices['msrp'] == 0 || $rowChkPrices['rtp'] == 0)) {
		die();
	}
	$result     = $db->query("SELECT dept_id FROM items
					   WHERE item_id = '{$itemId}'");
	$row        = $db->fetch_array($result);
	$deptId     = $row['dept_id'];

	$getSizes     = $db->query("SELECT `size_id`, `desc` FROM items_size
								WHERE dept_id = '{$deptId}'
								ORDER BY `order`");
	if (mysql_num_rows($getSizes) > 0) {
		while ($row = $db->fetch_array($getSizes))
		{
			$sizes[] = $row;
		}
		$tableSizes  = '<table id="tableSizes">';
		$tableSizes .= '<tr>';
		foreach ($sizes as $key => $value)
		{
			$tableSizes .= '<th>' . $value['desc'] . '</th>';
		}
		$tableSizes .= '<th></th>';
		$tableSizes .= '</tr>';
		$tableSizes .= '<tr>';
		foreach ($sizes as $key => $value)
		{
			$tableSizes .= '<td><input type="radio" name="sizeId" value="'. $value['size_id'] .'"></td>';
		}
		$tableSizes .= '<td><input type="button" name="btnSubmitSize" id="btnSubmitSize" value="Submit"></td>';
		$tableSizes .= '</tr>';
		$tableSizes .= '</table>';
	} else {
		$tableSizes  = '';
	}

	echo $tableSizes;
}

if (!empty($_POST['action']) && $_POST['action'] == "selectItem2") {
	$itemId  = $_POST['txtItems'];
	$chkItem = $db->query("SELECT item_id, item_cost, msrp, rtp FROM items WHERE item_id = {$itemId}");
	if (mysql_num_rows($chkItem) == 0)
	{
		die();
	}
	$rowChkPrices = $db->fetch_array($chkItem);
	if ($rowChkPrices['item_id'] != 26684 && $rowChkPrices['item_id'] != 9600 && $rowChkPrices['item_id'] != 27004 && ($rowChkPrices['item_cost'] == 0 || $rowChkPrices['msrp'] == 0 || $rowChkPrices['rtp'] == 0)) {
		die();
	}
	$result     = $db->query("SELECT dept_id FROM items
					   WHERE item_id = '{$itemId}'");
	$row        = $db->fetch_array($result);
	$deptId     = $row['dept_id'];

	$getSizes     = $db->query("SELECT `size_id`, `desc` FROM items_size
								WHERE dept_id = '{$deptId}'");
	if (mysql_num_rows($getSizes) > 0) {
		while ($row = $db->fetch_array($getSizes))
		{
			$sizes[] = $row;
		}
		$tableSizes  = '<table id="tableSizes">';
		$tableSizes .= '<tr>';
		foreach ($sizes as $key => $value)
		{
			$tableSizes .= '<th>' . $value['desc'] . '</th>';
		}
		$tableSizes .= '<th></th>';
		$tableSizes .= '</tr>';
		$tableSizes .= '<tr>';
		foreach ($sizes as $key => $value)
		{
			$tableSizes .= '<td><input type="text" id="'. $value['size_id'] .'" class="sizeId2" name="sizeId2" value="0"></td>';
		}
		$tableSizes .= '<td><input type="button" name="btnSubmitSize2" id="btnSubmitSize2" value="Submit"></td>';
		$tableSizes .= '</tr>';
		$tableSizes .= '</table>';
	} else {
		$tableSizes  = '';
	}

	echo $tableSizes;
}

if (!empty($_POST['action']) && $_POST['action'] == "selectSize")
{
	echo '<input type="text" name="txtQty" id="txtQty" value="1">
		  <input type="button" name="btnSubmitQty" id="btnSubmitQty" value="Submit">';
}

if (!empty($_POST['action']) && $_POST['action'] == "selectSize2")
{
	$itemId      = $_POST['txtItems'];
	$sizeId      = $_POST['sizeId'];
	$sizeVal     = $_POST['sizeVal'];
	$processType = $_POST['processType'];
	$tableItems  = "";

	for ($i = 0; $i < count($sizeId); $i++)
	{

		if ($sizeVal[$i] > 0)
		{
			$result  = $db->query("SELECT item_id, item_name, dept_id, msrp, rtp, item_cost FROM items WHERE item_id = '".$itemId."'");
			$row     = $db->fetch_array($result);

			$getDept = $db->query("SELECT long_desc FROM items_dept WHERE dept_id='".$row['dept_id']."'");
			$row2    = $db->fetch_array($getDept);

			$getSize = $db->query("SELECT `desc`, `size_id` FROM items_size WHERE size_id='".$sizeId[$i]."'");
			$row3    = $db->fetch_array($getSize);

			$tableItems .= '<tr class="dropItems" style="background: lightgreen;">';
			$tableItems .= '<td><span class="itemId">'.$itemId.'</span></td>';
			$tableItems .= '<td>'.$row['item_name'].'</td>';
			$tableItems .= '<td>'.$row2['long_desc'].'</td>';
			$tableItems .= '<td>'.$row3['desc'].'</td>';
			$tableItems .= '<td class="sizeId">'.$row3['size_id'].'</td>';
			$tableItems .= '<td><span class="msrp">'.$row['msrp'].'</span></td>';
			$tableItems .= '<td><span class="rtp">'.$row['rtp'].'</span></td>';
			if ($processType == "transfer" || $processType == "receiving")
			{
				if ($_SESSION['user_type'] == "sadmin")
				{
					$tableItems .= '<td><span class="cost">'.$row['item_cost'].'</span></td>';
				} else {
					$tableItems .= '<td style="display:none;"><span class="cost">'.$row['item_cost'].'</span></td>';
				}
			}
			$tableItems .= '<td><span class="qty">'.$sizeVal[$i].'</span></td>';
			if ($processType !== "transfer" && $processType !== "receiving") {
				$tableItems .= '<td><span class="totalItem">'.$sizeVal[$i] * $row['rtp'].'</span></td>';
			}
			$tableItems .= '<td style="display:none;"><span class="itemTransType">1</span></td>';
			$tableItems .= '<td><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg"><img src="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg" width="80"></a></td>';
			$tableItems .= '<td><a id="removeItem" href="#"><img src="_img/remove.png"></a></td>';
			$tableItems .= '</tr>';
		} else {
			continue;
		}	
	}

	echo $tableItems;
}

if (!empty($_POST['action']) && $_POST['action'] == "selectQty")
{
	$itemId      = $_POST['txtItems2'];
	$txtQty      = $_POST['txtQty'];
	$sizeId      = $_POST['sizeId2'];
	$processType = $_POST['processType'];

	$result  = $db->query("SELECT item_id, item_name, dept_id, msrp, rtp, item_cost FROM items WHERE item_id = '".$itemId."'");
	$row     = $db->fetch_array($result);

	$getDept = $db->query("SELECT long_desc FROM items_dept WHERE dept_id='".$row['dept_id']."'");
	$row2    = $db->fetch_array($getDept);

	$getSize = $db->query("SELECT `desc`, `size_id` FROM items_size WHERE size_id='".$sizeId."'");
	$row3    = $db->fetch_array($getSize);

	if ($processType == "returnFull") {
		$tableItems  = '<tr class="dropItems" style="background: lightpink;">';
	} else {
		$tableItems  = '<tr class="dropItems" style="background: lightred;">';
	}
	$tableItems .= '<td><span class="itemId">'.$itemId.'</span></td>';
	$tableItems .= '<td>'.$row['item_name'].'</td>';
	$tableItems .= '<td>'.$row2['long_desc'].'</td>';
	$tableItems .= '<td>'.$row3['desc'].'</td>';
	$tableItems .= '<td class="sizeId">'.$row3['size_id'].'</td>';
	$tableItems .= '<td><span class="msrp">'.$row['msrp'].'</span></td>';
	if ($processType == "returnFull" || $processType == "return2") {
		$tableItems .= '<td><span class="rtp">'.$row['rtp'] * (-1).'</span></td>';
	} else {
		$tableItems .= '<td><span class="rtp">'.$row['rtp'].'</span></td>';
	}
	if ($_SESSION['user_type'] == "sadmin")
	{
		if ($processType == "returnFull" || $processType == "return2") {
			$tableItems .= '<td><span class="cost">'.$row['item_cost'] * (-1).'</span></td>';
		} else {
			$tableItems .= '<td><span class="cost">'.$row['item_cost'].'</span></td>';
		}
	} else {
		if ($processType == "returnFull" || $processType == "return2") {
			$tableItems .= '<td style="display:none;"><span class="cost">'.$row['item_cost'] * (-1).'</span></td>';
		} else {
			$tableItems .= '<td style="display:none;"><span class="cost">'.$row['item_cost'].'</span></td>';
		}
	}
	$tableItems .= '<td><span class="qty">'.$txtQty.'</span></td>';
	if ($processType !== "transfer" && $processType !== "receiving") {
		if ($processType == "returnFull" || $processType == "return2") {
			$tableItems .= '<td><span class="totalItem">'.$txtQty * $row['rtp'] * (-1).'</span></td>';
		} else {
			$tableItems .= '<td><span class="totalItem">'.$txtQty * $row['rtp'].'</span></td>';
		}
	}
	$tableItems .= '<td style="display:none;"><span class="itemTransType">1</span></td>';
	$tableItems .= '<td><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg"><img src="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg" width="80"></a></td>';
	$tableItems .= '<td><a id="removeItem" href="#"><img src="_img/remove.png"></a></td>';
	$tableItems .= '</tr>';

	echo $tableItems;
}

if (isset($_POST['txtCustMob'])) {
	$txtCustMob = $_POST['txtCustMob'];
	$result     = $db->query("SELECT cust_id, cust_name FROM customers WHERE cust_tel = '2".$txtCustMob."'");
	if (mysql_num_rows($result) > 0)
	{
		$row        = $db->fetch_array($result);
		$data       = array('id' => $row['cust_id'], 'name' => $row['cust_name']);
		echo json_encode($data);
	} else {
		$data = "false";
		echo json_encode($data);
	}	
}

if (!empty($_POST['action']) && $_POST['action'] == "addCustomer")
{
	$txtName   = $_POST['txtName'];
	$txtMob    = "20" . $_POST['txtMob'];
	$txtEmail  = $_POST['txtEmail'];

	$db->query("INSERT INTO customers (cust_name, cust_tel, cust_email) VALUES ('".$txtName."', '".$txtMob."', '".$txtEmail."')");
	$getCustId = $db->query("SELECT MAX(cust_id) FROM customers");
	$rowCustId = $db->fetch_array($getCustId);
	echo $rowCustId[0];
}

if (!empty($_POST['action']) && $_POST['action'] == "pay")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$processType         = $_POST['processType'];
	$trans->subtotal     = $_POST['subtotal'];
	$trans->total        = $_POST['total'];
	$trans->totalCost    = $_POST['totalCost'];
	$trans->totalQty     = $_POST['totalQty'];
	$trans->customerName = $_POST['customerName'];
	$trans->customerId   = $_POST['customerId'];
	$trans->cashDisc     = $_POST['cashDisc'];
	$trans->paymentType  = $_POST['paymentType'];
	$trans->salesMan     = $_POST['salesMan'];
	$trans->cashMan      = $_SESSION['user_id'];
	$trans->stockKeeper  = $_SESSION['user_id'];
	$trans->locId        = $_SESSION['loc_id'];
	$trans->itemId       = $_POST['itemId'];
	$trans->sizeId       = $_POST['sizeId'];
	$trans->qty          = $_POST['qty'];
	$trans->rtp          = $_POST['rtp'];
	$trans->cost         = $_POST['cost'];
	$trans->comments     = $_POST['comments'];
	$trans->currentDate  = $_POST['currentDate'];
	$trans->split        = $_POST['split'];
	$trans->voucherId    = $_POST['voucherId'];

	try {
		$trans->payNow();
		
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "return")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$processType         = $_POST['processType'];
	$trans->subtotal     = $_POST['subtotal'];
	$trans->total        = $_POST['total'];
	$trans->totalCost    = $_POST['totalCost'];
	$trans->totalQty     = $_POST['totalQty'];
	$trans->customerName = $_POST['customerName'];
	$trans->customerId   = $_POST['customerId'];
	$trans->cashDisc     = $_POST['cashDisc'];
	$trans->paymentType  = $_POST['paymentType'];
	$trans->salesMan     = $_POST['salesMan'];
	$trans->refInvoNo    = $_POST['refInvoNo'];
	$trans->cashMan      = $_SESSION['user_id'];
	$trans->stockKeeper  = $_SESSION['user_id'];
	$trans->locId        = $_SESSION['loc_id'];
	$trans->itemId       = $_POST['itemId'];
	$trans->sizeId       = $_POST['sizeId'];
	$trans->qty          = $_POST['qty'];
	$trans->rtp          = $_POST['rtp'];
	$trans->cost         = $_POST['cost'];
	$trans->comments     = $_POST['comments'];
	$trans->currentDate  = $_POST['currentDate'];

	try {
		$trans->returnNow();
		
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "exchange")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$processType          = $_POST['processType'];
	$trans->subtotal      = $_POST['subtotal'];
	$trans->total         = $_POST['total'];
	$trans->totalCost    = $_POST['totalCost'];
	$trans->totalQty      = $_POST['totalQty'];
	$trans->customerName  = $_POST['customerName'];
	$trans->customerId    = $_POST['customerId'];
	$trans->cashDisc      = $_POST['cashDisc'];
	$trans->paymentType   = $_POST['paymentType'];
	$trans->salesMan      = $_POST['salesMan'];
	$trans->refInvoNo     = $_POST['refInvoNo'];
	$trans->cashMan       = $_SESSION['user_id'];
	$trans->stockKeeper   = $_SESSION['user_id'];
	$trans->locId         = $_SESSION['loc_id'];
	$trans->itemId        = $_POST['itemId'];
	$trans->sizeId        = $_POST['sizeId'];
	$trans->qty           = $_POST['qty'];
	$trans->rtp           = $_POST['rtp'];
	$trans->cost         = $_POST['cost'];
	$trans->comments      = $_POST['comments'];
	$trans->currentDate   = $_POST['currentDate'];
	$trans->itemTransType = $_POST['itemTransType'];

	try {
		$trans->exchangeNow();
		
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "holdTrans")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$trans->itemId      = $_POST['itemId'];
	$trans->branchTo    = $_POST['branchTo'];
	$trans->stockKeeper = $_SESSION['user_id'];
	$trans->locId       = $_SESSION['loc_id'];
	$trans->sizeId      = $_POST['sizeId'];
	$trans->qty         = $_POST['qty'];
	$trans->totalQty    = $_POST['totalQty'];
	$trans->subtotal    = $_POST['subtotal'];
	$trans->totalCost   = $_POST['totalCost'];
	$trans->rtp         = $_POST['rtp'];
	$trans->cost    	= $_POST['cost'];
	$trans->comments    = $_POST['comments'];
	$trans->currentDate = $_POST['currentDate'];

	try {
		$trans->holdTrans();
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "doTransferHQ")
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
		$trans->stockKeeper = $_SESSION['user_id'];
		$trans->locId       = $_SESSION['loc_id'];
		$trans->sizeId      = $sizeIds[$i];
		$trans->qty         = $itemQtys[$i];
		$trans->totalQty    = $itemQtys[$i];
		$itemPrices         = userClass::getItemPrices($itemIds[$i]);
		$trans->subtotal    = $itemPrices['rtp'] * $itemQtys[$i];
		$trans->totalCost   = $itemPrices['cost'] * $itemQtys[$i];
		$trans->rtp         = $itemPrices['rtp'];
		$trans->cost    	= $itemPrices['cost'];

		try {
			$trans->doTransferHQ();
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

if (!empty($_POST['action']) && $_POST['action'] == "specialTransfer")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$itemId      = $_POST['itemId'];
	$branchFrom  = $_POST['branchFrom'];
	$branchTo    = $_POST['branchTo'];
	$stockKeeper = $_SESSION['user_id'];
	$locId       = $_POST['branchFrom'];
	$sizeId      = $_POST['sizeId'];
	$qty         = $_POST['qty'];
	$comments    = $_POST['comments'];
	$currentDate = $_POST['currentDate'];
	$unBranches  = array_unique($branchTo);

	foreach ($unBranches as $key => $value) {
		$trans->itemId      = array();
		$trans->branchTo    = array();
		$trans->sizeId      = array();
		$trans->qty         = array();
		$trans->totalQty    = 0;
		$trans->subtotal    = 0;
		$trans->totalCost   = 0;
		$trans->rtp         = array();
		$trans->cost    	= array();

		for ($i=0; $i < count($itemId); $i++)
		{
			if ($branchTo[$i] == $value) {
				$trans->itemId[]    = $itemId[$i];
				$trans->sizeId[]    = $sizeId[$i];
				$trans->qty[]       = $qty[$i];
				$trans->totalQty   += $qty[$i];
				$itemPrices         = userClass::getItemPrices($itemId[$i]);
				$trans->subtotal   += $itemPrices['rtp']  * $qty[$i];
				$trans->totalCost  += $itemPrices['cost'] * $qty[$i];
				$trans->rtp[]       = $itemPrices['rtp'];
				$trans->cost[]    	= $itemPrices['cost'];
			} else {
				continue;
			}
		}
		$trans->branchTo    = $value;
		$trans->branchFrom  = $branchFrom;
		$trans->stockKeeper = $stockKeeper;
		$trans->locId       = $locId;
		$trans->currentDate = $currentDate;

		try {
			$trans->specialTransfer();
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

if (!empty($_POST['action']) && $_POST['action'] == "holdTrans2")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$trans->itemId      = $_POST['itemId'];
	$trans->branchTo    = $_POST['branchTo'];
	$trans->stockKeeper = $_SESSION['user_id'];
	$trans->locId       = $_POST['wrhsId'];
	$trans->sizeId      = $_POST['sizeId'];
	$trans->qty         = $_POST['qty'];
	$trans->totalQty    = $_POST['totalQty'];
	$trans->subtotal    = $_POST['subtotal'];
	$trans->totalCost   = $_POST['totalCost'];
	$trans->rtp         = $_POST['rtp'];
	$trans->cost    	= $_POST['cost'];
	$trans->comments    = $_POST['comments'];
	$trans->currentDate = $_POST['currentDate'];
	$trans->transNo     = $_POST['transNo'];

	try {
		$trans->holdTrans2();
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "cancelTrans")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$trans->transNo = $_POST['transNo'];
	$trans->locId   = $_POST['wrhsId'];

	try {
		$trans->cancelTrans();
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "proceedTrans")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$trans->transNo     = $_POST['transNo'];
	$trans->locId       = $_POST['wrhsId'];
	$trans->stockKeeper = $_SESSION['user_id'];

	try {
		$trans->proceedTrans();
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "transfer")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$trans->itemId      = $_POST['itemId'];
	$trans->branchTo    = $_POST['branchTo'];
	$trans->stockKeeper = $_SESSION['user_id'];
	$trans->locId       = $_SESSION['loc_id'];
	$trans->sizeId      = $_POST['sizeId'];
	$trans->qty         = $_POST['qty'];
	$trans->totalQty    = $_POST['totalQty'];
	$trans->subtotal    = $_POST['subtotal'];
	$trans->totalCost   = $_POST['totalCost'];
	$trans->rtp         = $_POST['rtp'];
	$trans->cost    	= $_POST['cost'];
	$trans->comments    = $_POST['comments'];
	$trans->currentDate = $_POST['currentDate'];

	try {
		$trans->transfer();
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "transfer2")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$trans->itemId      = $_POST['itemId'];
	$trans->branchTo    = $_POST['branchTo'];
	$trans->stockKeeper = $_SESSION['user_id'];
	$trans->locId       = $_SESSION['loc_id'];
	$trans->sizeId      = $_POST['sizeId'];
	$trans->qty         = $_POST['qty'];
	$trans->totalQty    = $_POST['totalQty'];
	$trans->subtotal    = $_POST['subtotal'];
	$trans->totalCost   = $_POST['totalCost'];
	$trans->rtp         = $_POST['rtp'];
	$trans->cost    	= $_POST['cost'];
	$trans->comments    = $_POST['comments'];
	$trans->currentDate = $_POST['currentDate'];
	$trans->transNo     = $_POST['transNo'];

	try {
		$trans->transfer2();
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "addItem")
{
	$data   = $_POST['data'];
	$itemId = $_POST['itemId'];
	$error  = "";

	$chkItem = $db->query("SELECT item_id FROM items WHERE item_id = '{$itemId}'");

	if (mysql_num_rows($chkItem) > 0) {
		$error = "error";
	} else {
		$error = "done";
		foreach ($data as $key => $value)
		{
			$names[]  = $value['name'];
			$values[] = $value['value'];
		}
		$names  = implode($names, ",");
		$values = implode($values, "','");
		$db->query("INSERT INTO items (".$names.") VALUES ('".$values."')");
	}

	echo $error;
}

if (!empty($_POST['action']) && $_POST['action'] == "updateItem")
{
	$data[] = $_POST['data'];
	$itemId = $_POST['itemId'];

	foreach ($data as $key => $value)
	{
		foreach ($value as $key2 => $value2)
		{
			$db->query("UPDATE items SET ".$value2['name']." = '".$value2['value']."'
	 			WHERE item_id = '{$itemId}'");
		}
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "getInvnDetails")
{
	$transNo = $_POST['transNo'];
	$wrhsId  = $_POST['wrhsId'];
	$locId   = $_SESSION['loc_id'];

	#get invoice header
	$getInvnHeader = $db->query("SELECT * FROM `inventory_header`
								 WHERE wrhs_id       = '{$wrhsId}'
								 AND   trans_no      = '{$transNo}'
								 AND   trans_type_id IN (4, 7, 8)");

	$rowInvnHeader = $db->fetch_array($getInvnHeader);

	if ($rowInvnHeader['trans_type_id'] == 5)
	{
		#get invoice header
		$getInvnHeader = $db->query("SELECT * FROM `inventory_header`
									 WHERE from_wrhs_id  = '".$rowInvnHeader['from_wrhs_id']."'
									 AND   to_wrhs_id    = '".$rowInvnHeader['to_wrhs_id']."'
									 AND   doc_no        = '".$rowInvnHeader['doc_no']."'
									 AND   trans_type_id = '4'");

		$rowInvnHeader = $db->fetch_array($getInvnHeader);

		#get invoice detail
		$getInvnDetail = $db->query("SELECT * FROM inventory_detail
									 WHERE wrhs_id  = '".$rowInvnHeader['from_wrhs_id']."'
									 AND   trans_no = '".$rowInvnHeader['trans_no']."'");
	} else {
		#get invoice detail
		$getInvnDetail = $db->query("SELECT * FROM inventory_detail
									 WHERE wrhs_id  = '".$rowInvnHeader['wrhs_id']."'
									 AND   trans_no = '{$transNo}'");
	}
	
	#get location name
	$locName  = userClass::getBranchName($locId);

	$totalQty = 0;

	$dataTable  = '<div id="reportWrapper">';
	$dataTable .= '<a onclick="doPrint()" style="cursor: pointer; display: block; padding: 10px; color: white; background: red; text-decoration: none; font-weight: bold;">Print</a>';
	$dataTable .= '<br><p><img src="_img/logo-black.png"></p>';
	$dataTable .= '<br><p style="font-weight: bold;">'.$locName.'</p>';
	$dataTable .= '<br><p>Transaction# '.$transNo.'</p>';
	$dataTable .= '<br><p>From: '.userClass::getBranchName($rowInvnHeader['from_wrhs_id']).'</p>';
	$dataTable .= '<p>To: '.userClass::getBranchName($rowInvnHeader['to_wrhs_id']).'</p>';
	$dataTable .= '<br><p>'.$rowInvnHeader['date'].' / '.date('h:i:s', strtotime($rowInvnHeader['time'])).'</p><br>';
	$dataTable .= '<table id="tblInvoiceDetail"><tr><th></th><th>Item#</th><th>Size</th><th>Price</th><th>Qty.</th><th>Total</th>';
	if ($rowInvnHeader['from_wrhs_id'] == 55) {
		$dataTable .= '<th>LOC</th>';
	}
	$dataTable .= '</tr>';
	while ($rowInvnDetail = $db->fetch_array($getInvnDetail))
	{
		$dataTable .= '<tr><td>'.$rowInvnDetail['serial'].'</td><td>'.$rowInvnDetail['item_id'].'</td><td>'.userClass::getSizeDesc($rowInvnDetail['size_id']).'</td><td>'.$rowInvnDetail['rtp'].'</td><td>'.$rowInvnDetail['qty'].'</td><td>'.number_format($rowInvnDetail['qty'] * $rowInvnDetail['rtp'], 2, '.', '').'</td>';
		if ($rowInvnHeader['from_wrhs_id'] == 55) {
			$dataTable .= '<td>'.userClass::getItemLocation($rowInvnDetail['item_id'], 55).'</td>';
		}
		$dataTable .= '</tr>';
		$totalQty  += $rowInvnDetail['qty'];
	}
	$dataTable .= '<tr><td class="noBorder" colspan="5">&nbsp</td></tr><tr><td class="noBorder" colspan="6">&nbsp</tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="5">Total Qty</th><td style="text-align: center;">'.$totalQty.'</td></tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="5">Total Price</th><td style="text-align: center;">'.$rowInvnHeader['total_rtp'].'</td></tr>';
	$dataTable .= '</table>';
	$dataTable .= '</div>';

	echo $dataTable;
}

if (!empty($_POST['action']) && $_POST['action'] == "getInvnComments")
{
	$transNo = $_POST['transNo'];
	$wrhsId  = $_POST['wrhsId'];
	$locId   = $_SESSION['loc_id'];

	$getInvnComments = $db->query("SELECT `comments` FROM inventory_header
								   WHERE  trans_no = {$transNo}
								   AND    wrhs_id = {$wrhsId}");

	$rowInvnComments = $db->fetch_array($getInvnComments);

	$invnComments  = '<div id="reportWrapper">';
	$invnComments .= $rowInvnComments['comments'];
	$invnComments .= '</div>';
	echo $invnComments;
}

if (!empty($_POST['action']) && $_POST['action'] == "getInvoDetails")
{
	$invoNo     = $_POST['invoNo'];
	$locId      = $_SESSION['loc_id'];

	#get invoice header
	$getInvoHeader = $db->query("SELECT * FROM `invoice_header`
								 WHERE loc_id = '{$locId}'
								 AND invo_no  = '{$invoNo}'");

	#get invoice detail
	$getInvoDetail = $db->query("SELECT * FROM invoice_detail
								 WHERE loc_id = '{$locId}'
								 AND invo_no  = '{$invoNo}'");

	$rowInvoHeader = $db->fetch_array($getInvoHeader);

	#get location name
	$locName = userClass::getBranchName($locId);

	#get customer name/mobile
	$getCustomerName  = $db->query("SELECT `cust_name`, `cust_tel` FROM customers
									WHERE cust_id = '".$rowInvoHeader['cust_id']."'");

	$rowCustomerName  = $db->fetch_array($getCustomerName);

	$totalQty = 0;

	$dataTable  = '<div id="reportWrapper">';
	$dataTable .= '<a onclick="doPrint()" style="cursor: pointer; display: block; padding: 10px; color: white; background: red; text-decoration: none; font-weight: bold;">Print</a>';
	$dataTable .= '<br><p><img src="_img/logo-black.png"></p>';
	$dataTable .= '<br><p style="font-weight: bold;">'.$locName.'</p>';
	$dataTable .= '<br><p>Invoice# '.$rowInvoHeader['invo_no'].'</p>';
	$dataTable .= '<p>Mobile# '.$rowCustomerName['cust_tel'].'</p>';
	$dataTable .= '<p>'.$rowInvoHeader['date'].' / '.date('h:i:s', strtotime($rowInvoHeader['time'])).'</p><br>';
	$dataTable .= '<table id="tblInvoiceDetail"><tr><th></th><th>Item#</th><th>Price</th><th>Qty.</th><th>Total</th></tr>';
	while ($rowInvoDetail = $db->fetch_array($getInvoDetail))
	{
		$dataTable .= '<tr><td>'.$rowInvoDetail['serial'].'</td><td>'.$rowInvoDetail['item_id'].'</td><td>'.$rowInvoDetail['rtp'].'</td><td>'.$rowInvoDetail['qty'].'</td><td>'.number_format($rowInvoDetail['qty'] * $rowInvoDetail['rtp'], 2, '.', '').'</td></tr>';
		$totalQty  += $rowInvoDetail['qty'];
	}
	$dataTable .= '<tr><td class="noBorder" colspan="5">&nbsp</td></tr><tr><td class="noBorder" colspan="5">&nbsp</tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="4">Total Qty</th><td style="text-align: center;">'.$totalQty.'</td></tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="4">Total Price</th><td style="text-align: center;">'.$rowInvoHeader['total_amount'].'</td></tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="4">Discount</th><td style="text-align: center;">'.$rowInvoHeader['discount_amount'].'</td></tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="4">Net Value</th><td style="text-align: center;">'.$rowInvoHeader['net_value'].'</td></tr>';
	if ($rowInvoHeader['payment_type_id'] == "3")
	{
		$dataTable .= '<tr style="text-align: left;"><th colspan="4">Cash</th><td style="text-align: center;">'.$rowInvoHeader['split'].'</td></tr>';
		$dataTable .= '<tr style="text-align: left;"><th colspan="4">Visa</th><td style="text-align: center;">'.number_format($rowInvoHeader['net_value'] - $rowInvoHeader['split'], 2, '.', '').'</td></tr>';
	}
	$dataTable .= '<tr style="text-align: left;"><th colspan="4">Payment Type</th><td style="text-align: center;">'.transClass::getPaymentType($rowInvoHeader['payment_type_id']).'</td></tr>';
	$dataTable .= '</table>';
	$dataTable .= '</div>';

	echo $dataTable;
}

if (!empty($_POST['action']) && $_POST['action'] == "getInvoComments")
{
	$invoNo     = $_POST['invoNo'];
	$locId      = $_SESSION['loc_id'];

	$getInvoComments = $db->query("SELECT `comments` FROM invoice_header
								   WHERE invo_no = {$invoNo}
								   AND   loc_id  = {$locId}");

	$rowInvoComments = $db->fetch_array($getInvoComments);

	$invoComments    = '<div id="reportWrapper">';
	$invoComments   .= $rowInvoComments['comments'];
	$invoComments   .= '</div>';
	echo $invoComments;
}

if (!empty($_POST['action']) && $_POST['action'] == "getInvoiceDetails")
{
	$invoNo 		  = $_POST['txtInvoiceNo'];
	$locId            = $_SESSION['loc_id'];

	$chkInvoice = $db->query("SELECT invo_no FROM invoice_header
							  WHERE  invo_no    = {$invoNo}
							  AND    loc_id     = {$locId}
							  AND    invo_type  = 1");

	if (mysql_num_rows($chkInvoice) > 0)
	{
		$chkInvoice2 = $db->query("SELECT ref_invo_no FROM invoice_header
								   WHERE ref_invo_no = {$invoNo}
								   AND   loc_id      = {$locId}
								   AND   invo_type   = 2");
		if (mysql_num_rows($chkInvoice2) > 0)
		{
			echo "0";
		} else {
			echo "1";
		}
	} else {
		echo "2";
	}
	
}

if (!empty($_POST['action']) && $_POST['action'] == "getInvoiceDetails2")
{
	$invoNo 		  = $_POST['txtInvoiceNo'];
	$locId            = $_SESSION['loc_id'];

	$chkInvoice = $db->query("SELECT ref_invo_no FROM invoice_header
							   WHERE ref_invo_no = {$invoNo}
							   AND   loc_id      = {$locId}
							   AND   invo_type   = 2");

	if (mysql_num_rows($chkInvoice) > 0)
	{
		echo "0";
	} else {
		echo "1";
	}
	
}

if (!empty($_POST['action']) && $_POST['action'] == "getInvoiceDetails3")
{
	$invoNo 		  = $_POST['txtInvoiceNo'];
	$locId            = $_SESSION['loc_id'];

	$chkInvoice = $db->query("SELECT invo_no FROM invoice_header
							  WHERE  invo_no    = {$invoNo}
							  AND    loc_id     = {$locId}
							  AND    invo_type  = 1");

	if (mysql_num_rows($chkInvoice) > 0)
	{
		$chkInvoice2 = $db->query("SELECT ref_invo_no FROM invoice_header
								   WHERE ref_invo_no = {$invoNo}
								   AND   loc_id      = {$locId}
								   AND   (invo_type  = 2
								   OR    invo_type   = 3)");
		if (mysql_num_rows($chkInvoice2) > 0)
		{
			echo "0";
		} else {
			echo "1";
		}
	} else {
		echo "2";
	}
	
}

if (!empty($_POST['action']) && $_POST['action'] == "getInvoValues")
{
	$locId = $_SESSION['loc_id'];

	$query = "SELECT DISTINCT(`date`) FROM invoice_header";

	if ($_POST['selBranch'] == 1)
	{
		$query .= " WHERE loc_id = '{$locId}'";
	}

	$getDate = $db->query($query);

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

	if ($_POST['selBranch'] == 1)
	{
		$salesMen   = userClass::getSalesMen($locId);
		$getDate = $db->query("SELECT DISTINCT(`date`) FROM invoice_header
							   WHERE loc_id = '{$locId}'");
		$data = '<p><a id="exportBranchInvoices" href="#">Export Invoices to Excel</a></p><br>
				 <p><a id="exportBranchInvoices2" href="#">Export Invoice Headers to Excel</a></p><br>
				 <label>Current Branch: <input type="radio" name="selBranch" value="1" checked>
				 </select></label>
				 <label>All Branches: <input type="radio" name="selBranch" value="2">
				 </select></label><br><br>';
	} else {
		$salesMen   = userClass::getSalesMen();
		$getDate = $db->query("SELECT DISTINCT(`date`) FROM invoice_header");
		$data = '<p><a id="exportBranchInvoices" href="#">Export Invoices to Excel</a></p><br>
				 <p><a id="exportBranchInvoices2" href="#">Export Invoice Headers to Excel</a></p><br>
				 <label>Current Branch: <input type="radio" name="selBranch" value="1">
				 </select></label>
				 <label>All Branches: <input type="radio" name="selBranch" value="2" checked>
				 </select></label><br><br>';
	}

	$data .= '<label>FROM: <input type="text" id="dateFrom" name="dateFrom"></label>
			  <label>TO: <input type="text" id="dateTo" name="dateTo"></label>';

	$data .= '<br><br>
			  <label>Sales Man: <select id="selSalesMan" name="selSalesMan">
			  <option value="0">Select ..</option>';

	foreach ($salesMen as $key => $value)
	{
		$data .= '<option value="'.$key.'">'.$key.'-'.$value.'</option>';
	}
	
	$data .= '</select></label>';
	$data .= '<br><br>
			  <label>Customer Tel#: <input type="text" name="custTel"></label> ';
	$data .= '<input type="submit" name="btnSubmit" value="Submit">';
	$data .= '<script>var availableDates = ['.$dateRange.']</script>';

	echo $data;
}

if (!empty($_POST['action']) && $_POST['action'] == "getItemStockDetails")
{
	$locId       = $_SESSION['loc_id'];
	$itemId      = $_POST['itemId'];
	$selBranch   = $_POST['selBranch'];

	$query       = "SELECT * FROM items
			    	WHERE item_id = {$itemId}";
	$result      = $db->query($query);
	$row         = $db->fetch_array($result);

	$dataTable   = '<img class="itemImg" src="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg">';

	$dataTable  .= '<div style="min-height:300px;overflow:auto;">';

	$query   = "SELECT SUM(IF(invoice_detail.type=1, invoice_detail.qty, -1*invoice_detail.qty)) AS qty, MONTH(invoice_header.date) AS month,
				YEAR(invoice_header.date) AS year
				FROM invoice_detail
				JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
	    		AND  invoice_detail.loc_id = invoice_header.loc_id
	    		WHERE invoice_detail.item_id = {$itemId}";

	if ($selBranch == 1) {
		$query .= " AND invoice_detail.loc_id = {$locId}";
	}
	    		
	$query  .= " GROUP BY YEAR(invoice_header.date), MONTH(invoice_header.date)
	    		 ORDER BY YEAR(invoice_header.date), MONTH(invoice_header.date)";

	$getTSMonth = $db->query($query);

	if ($db->num_rows($getTSMonth) > 0) {
		while ($row = $db->fetch_array($getTSMonth)) {
			$qty[]    = $row['qty'];
			$years[]  = $row['year'];
			$months[] = $row['month'];
		}

		$uYears = array_unique($years);
			
		foreach ($uYears as $oneYear)
		{
			$dataTable  .= '<table class="itemSalesStock">
							<tr><td></td><th>SALES/'.$oneYear.'</th></tr>';
			for ($i = 0; $i < count($years); $i++) { 
				if ($years[$i] == $oneYear)
				{
					$dataTable  .= '<tr><th>M'.$months[$i].'</th><td>'.$qty[$i].'</td></tr>';
				}
			}
			if ($selBranch == 1)
			{
				$salesReport = userClass::getItemTotalSales($itemId, $locId, NULL, NULL);
				$dataTable  .= '<tr><th style="background:#0972ba;">T.SLS</th><td>'.$salesReport['totalSales'].'</td></tr>';
				$dataTable  .= '<tr><th style="background:#0972ba;">T.RTN</th><td>'.$salesReport['totalReturn'].'</td></tr>';
				$dataTable  .= '<tr><th style="background:#0972ba;">N.SLS</th><td>'.$salesReport['netSales'].'</td></tr>';
				$dataTable  .= '<tr><th style="background:#0972ba;">T.STK</th><td>'.userClass::getItemTotalStock($itemId, $locId, NULL, NULL).'</td></tr>';
			} else {
				$salesReport = userClass::getItemTotalSales($itemId);
				$dataTable  .= '<tr><th style="background:#0972ba;">T.SLS</th><td>'.$salesReport['totalSales'].'</td></tr>';
				$dataTable  .= '<tr><th style="background:#0972ba;">T.RTN</th><td>'.$salesReport['totalReturn'].'</td></tr>';
				$dataTable  .= '<tr><th style="background:#0972ba;">N.SLS</th><td>'.$salesReport['netSales'].'</td></tr>';
				$dataTable  .= '<tr><th style="background:#0972ba;">T.STK</th><td>'.userClass::getItemTotalStock($itemId).'</td></tr>';
			}
			$dataTable  .= '</table>';	
		}
	}
	
	$dataTable  .= '<table class="itemSalesStock">
						<tr><td></td><th>SALES</th><th>STOCK</th></tr>';

	$query2      = "SELECT DISTINCT wrhs_id
					FROM `warehouses`
					WHERE item_id = {$itemId}";
	$result2     = $db->query($query2);
	while ($row2 = $db->fetch_array($result2))
	{
		$salesReport = userClass::getItemTotalSales($itemId, $row2['wrhs_id'], NULL, NULL);
		$dataTable  .= '<tr><th>'.userClass::getBranchShortName($row2['wrhs_id']).'</th><td>'.$salesReport['netSales'].'</td><td>'.userClass::getItemTotalStock($itemId, $row2['wrhs_id'], NULL, NULL).'</td></tr>';
	}
	$dataTable   .= '</table>';
	
	$dataTable  .= '</div>';

	$dataTable  .= '<br>';
	$dataTable  .= '<div style="display:block;position:absolute;bottom:0;width:100%">';
	$dataTable  .= '<table class="itemData">
						<tr><th>Item#</th><td>'.$itemId.'</td></tr>
						<tr><th>Description</th><td>'.userClass::getItemDesc($itemId).'</td></tr>
						<tr><th>Attribute</th><td>'.userClass::getItemAttr($itemId).'</td></tr>
						<tr><th>Vendor</th><td>'.userClass::getItemVend($itemId).'</td></tr>
						<tr><th>Intl Code</th><td>'.userClass::getItemIntlCode($itemId).'</td></tr>
						<tr><th>Department</th><td>'.userClass::getItemDept($itemId).'</td></tr>
					</table>';
	$itemPrices = userClass::getItemPrices($itemId);
	$dataTable  .= '<table class="itemPrice">
						<tr><th>MSRP</th><td>'.$itemPrices['msrp'].'</td></tr>
						<tr><th>RTP</th><td>'.$itemPrices['rtp'].'</td></tr>';
	if ($_SESSION['user_type'] == "sadmin" || $_SESSION['user_type'] == "analyst") {
		$dataTable  .= '<tr><th>COST</th><td>'.$itemPrices['cost'].'</td></tr>';
	}
	$dataTable  .= '<div>';
	$dataTable  .= '</table>';

	echo $dataTable;
}

if (!empty($_POST['action']) && $_POST['action'] == "getSizeRanges")
{
	$selectedDepts = $_POST['selectedDepts'];

	echo '<option value="All" selected>All</option>';
	
	foreach ($selectedDepts as $oneDept)
	{
		$result     = $db->query("SELECT `size_id`, `desc` FROM items_size
							  WHERE dept_id = '{$oneDept}'
							  ORDER BY `desc` ASC");
		while ($row = $db->fetch_array($result))
		{
			echo '<option value="'.$row['size_id'].'">'.$row['desc'].'</option>';
		}
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "getDateRanges")
{
	$selectedYears = $_POST['selectedYears'];

	echo '<option value="All" selected>All</option>';
	if (!empty($selectedYears)) {
		foreach ($selectedYears as $oneYear)
		{
			$result   = $db->query("SELECT DISTINCT `date` AS `date` FROM invoice_header
									WHERE YEAR(`date`) = '{$oneYear}' ORDER BY `date` ASC");
			while ($row = $db->fetch_array($result))
			{
				echo '<option value="'.$row['date'].'">'.$row['date'].'</option>';
			}
		}
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "getLocationRanges")
{
	$selectedZones = $_POST['selectedZones'];

	echo '<option value="All" selected>All</option>';
	
	foreach ($selectedZones as $oneZone)
	{
		$result = $db->query("SELECT `loc_id`, `desc` FROM wh_locations
							  WHERE zone_id = {$oneZone}");
		while ($row = $db->fetch_array($result))
		{
			echo '<option value="'.$row['loc_id'].'">'.$row['desc'].'</option>';
		}
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "getZoneLocations")
{
	$selZone = $_POST['selZone'];

	$result = $db->query("SELECT `loc_id`, `desc` FROM wh_locations
						  WHERE zone_id = '{$selZone}'");
	while ($row = $db->fetch_array($result))
	{
		echo '<option value="'.$row['loc_id'].'">'.$row['desc'].'</option>';
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "addItemLocation")
{
	$itemId = $_POST['itemId'];
	$selLoc = $_POST['selLoc'];
	$userId = $_SESSION['user_id'];
	$locId  = $_SESSION['loc_id'];

	$query  = "INSERT INTO wh_item_location (`item_id`, `loc_id`, `main_loc_id`)
			   VALUES ('{$itemId}', '{$selLoc}', '{$locId}')";
	$query2 = "INSERT INTO log (`user_id`, `date`, `time`, `item_id`, `type`, `desc`, `main_loc_id`)
			   VALUES ('{$userId}', NOW(), NOW(), '{$itemId}', 1, 'Add Item Location', '{$locId}')";
	if ($db->query($query) && $db->query($query2))
	{
		echo "Done";
	} else {
		echo "Error";
	}	
}

if (!empty($_POST['action']) && $_POST['action'] == "deleteItemLocation")
{
	$curId  = $_POST['curId'];
	$userId = $_SESSION['user_id'];
	$locId  = $_SESSION['loc_id'];

	$query  = "DELETE FROM wh_item_location
			   WHERE id = {$curId}";
	$query2 = "INSERT INTO log (`user_id`, `date`, `time`, `type`, `desc`, `main_loc_id`)
			   VALUES ('{$userId}', NOW(), NOW(), 3, 'Delete Item Location', '{$locId}')";
	if ($db->query($query) && $db->query($query2))
	{
		echo "Done";
	} else {
		echo "Error";
	}	
}

if (!empty($_POST['action']) && $_POST['action'] == "viewAllLocations")
{
	$itemId = $_POST['itemId'];
	$locId  = $_SESSION['loc_id'];

	$result = $db->query("SELECT loc_id FROM wh_item_location
						  WHERE item_id   = {$itemId}
						  AND main_loc_id = '{$locId}'");

	$dataTable  = '<div id="reportWrapper3"><table id="tblTransReport2">';
	$dataTable .= '<th>Locations</th>';
	while ($row = $db->fetch_array($result))
	{
		$dataTable .= '<tr><td>'.userClass::getLocName($row['loc_id']).'</td></tr>';
	}
	$dataTable .= '</table></div>';
	echo $dataTable;
}

if (!empty($_POST['action']) && $_POST['action'] == "editItemLocation")
{
	$curId  = $_POST['curId'];
	$itemId = $_POST['itemId'];
	$selLoc = $_POST['selLoc'];
	$userId = $_SESSION['user_id'];
	$locId  = $_SESSION['loc_id'];

	$query  = "UPDATE wh_item_location SET `item_id` = '{$itemId}', `loc_id` = '{$selLoc}'
			   WHERE `id` = '{$curId}'";
	$query2 = "INSERT INTO log (`user_id`, `date`, `time`, `item_id`, `type`, `desc`, `main_loc_id`)
			   VALUES ('{$userId}', NOW(), NOW(), '{$itemId}', 2, 'Edit Item Location', '{$locId}')";
	if ($db->query($query) && $db->query($query2))
	{
		echo "Done";
	} else {
		echo "Error";
	}	
}

if (!empty($_POST['action']) && $_POST['action'] == "editItemQty")
{
	$itemId  = $_POST['itemId'];
	$itemQty = $_POST['itemQty'];
	$userId  = $_SESSION['user_id'];
	$locId   = $_SESSION['loc_id'];

	$query   = "DELETE FROM warehouses
				WHERE item_id = '{$itemId}'
				AND wrhs_id = '{$locId}'";
	$query2  = "INSERT INTO warehouses (`wrhs_id`, `item_id`, `size_id`, `qty`)
				VALUES ('".$locId."', '".$itemId."', 1, '".$itemQty."')";
	$query3  = "INSERT INTO log (`user_id`, `date`, `time`, `item_id`, `type`, `desc`, `main_loc_id`)
				VALUES ('{$userId}', NOW(), NOW(), '{$itemId}', 4, 'Edit Item Qty', '{$locId}')";
	if ($db->query($query) && $db->query($query2) && $db->query($query3))
	{
		echo "Done";
	} else {
		echo "Error";
	}
}

flush();