<?php

require_once ("dbClass.php");

class transClass {

	public $subtotal;
	public $totalCost;
	public $total;
	public $totalQty;
	public $customerName;
	public $customerId;
	public $cashDisc;
	public $paymentType;
	public $salesMan;
	public $cashMan;
	public $locId;
	public $itemId;
	public $sizeId;
	public $qty;
	public $rtp;
	public $cost;
	public $invoNo    = 0;
	public $transNo   = 0;
	public $transNoTo = 0;
	public $docNo     = 0;
	public $branchFrom;
	public $branchTo;
	public $stockKeeper;
	public $currentDate;
	public $comments;
	public $refInvoNo;
	public $split;
	public $itemTransType;
	public $voucherId;

	public function payNow()
	{
		global $db;

		$getInvoNo  = $db->query("SELECT MAX(invo_no)
								  AS invo_no 
								  FROM invoice_header
								  WHERE loc_id = '".$this->locId."'");
		$getTransNo = $db->query("SELECT MAX(trans_no)
								  AS trans_no
								  FROM inventory_header
								  WHERE wrhs_id = '".$this->locId."'");

		$rowInvoNo  = $db->fetch_array($getInvoNo);
		$rowTransNo = $db->fetch_array($getTransNo);

		if ($rowInvoNo['invo_no'] != NULL)
		{
			$this->invoNo = $rowInvoNo['invo_no'] + 1;
		} else {
			$this->invoNo += 1;
		}

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		$branchShortName = userClass::getBranchShortName($this->locId);

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

	    // A set of queries; if one fails, an exception should be thrown
	    $db->query("INSERT INTO invoice_header (`invo_type`, `invo_no`, `invo_code`, `date`, `time`, `cust_id`,
	    `loc_id`, `cash_man_id`, `sales_man_id`, `total_amount`, `tax`,`discount_amount`,
	    `net_value`, `split`, `qty`, `payment_type_id`, `comments`) VALUES ('1', '".$this->invoNo."',
	    '".$branchShortName."-".$this->invoNo."', '".$this->currentDate."', NOW(), '".$this->customerId."', '".$this->locId."',
	    '".$this->cashMan."', '".$this->salesMan."', '".$this->subtotal."', '0', '".$this->cashDisc."',
	    '".$this->total."', '".$this->split."', '".$this->totalQty."', '".$this->paymentType."',
	    '".$this->comments."')");

	    $db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `invo_no`, `date`,
		`time`, `stock_keeper_id`, `total_cost`) VALUES ('".$this->locId."', '1',
		'".$this->transNo."', '".$this->invoNo."', '".$this->currentDate."', NOW(), '".$this->stockKeeper."',
		'".$this->totalCost."')");

	    for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

	    	$db->query("INSERT INTO invoice_detail (`invo_no`, `loc_id`, `serial`, `item_id`,
	    	`size_id`, `qty`, `rtp`, `type`) VALUES ('".$this->invoNo."',
	    	'".$this->locId."','".$serial."','".$this->itemId[$i]."', '".$this->sizeId[$i]."',
	    	'".$this->qty[$i]."', '".$this->rtp[$i]."', '1')");

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->cost[$i]."', '1')");

		    $getItem = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
		    AND wrhs_id = '".$this->locId."'");
		    
		    if (mysql_num_rows($getItem) > 0)
		    {
		    	$this->subItemWH($i, $this->locId);
		    	
		    } else {
		    	$this->newItemWH($i, $this->locId);
				$this->subItemWH($i, $this->locId);
		    }
	    }
	    if (!empty($this->voucherId)) {
	    	// Update voucher_log info.
			$db->query("INSERT INTO voucher_log SET user_id = '".$this->cashMan."',
						`loc_id` = '".$this->locId."', `invo_no` = '".$this->invoNo."',
						`value` = '".$this->subtotal."', `voucher_id` = ".$this->voucherId."");

			// Update voucher info.
			$db->query("UPDATE vouchers SET used = 1, remain = remain - ".$this->subtotal." WHERE voucher_id = ".$this->voucherId."");
	    }
	    echo json_encode($this->invoNo);
	}

	public function returnNow()
	{
		global $db;

		$getInvoNo  = $db->query("SELECT MAX(invo_no)
								  AS invo_no 
								  FROM invoice_header
								  WHERE loc_id = '".$this->locId."'");
		$getTransNo = $db->query("SELECT MAX(trans_no)
								  AS trans_no
								  FROM inventory_header
								  WHERE wrhs_id = '".$this->locId."'");

		$rowInvoNo  = $db->fetch_array($getInvoNo);
		$rowTransNo = $db->fetch_array($getTransNo);

		if ($rowInvoNo['invo_no'] != NULL)
		{
			$this->invoNo = $rowInvoNo['invo_no'] + 1;
		} else {
			$this->invoNo += 1;
		}

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		$branchShortName = userClass::getBranchShortName($this->locId);

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

	    // A set of queries; if one fails, an exception should be thrown
	    $db->query("INSERT INTO invoice_header (`invo_type`, `invo_no`, `invo_code`, `date`, `time`, `cust_id`,
	    `loc_id`, `cash_man_id`, `sales_man_id`, `ref_invo_no`, `total_amount`, `tax`,`discount_amount`,
	    `net_value`, `qty`, `payment_type_id`, `comments`) VALUES ('2', '".$this->invoNo."',
	    '".$branchShortName."-".$this->invoNo."', '".$this->currentDate."', NOW(),
	    '".$this->customerId."', '".$this->locId."', '".$this->cashMan."', '".$this->salesMan."', 
	    '".$this->refInvoNo."', '".$this->subtotal."', '0', '".$this->cashDisc."', '".$this->total."',
	    '".$this->totalQty."', '".$this->paymentType."', '".$this->comments."')");

	    $db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `invo_no`, `date`,
		`time`, `stock_keeper_id`, `total_cost`) VALUES ('".$this->locId."', '2',
		'".$this->transNo."', '".$this->invoNo."', '".$this->currentDate."', NOW(), '".$this->stockKeeper."',
		'".$this->totalCost."')");

	    for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

	    	$db->query("INSERT INTO invoice_detail (`invo_no`, `loc_id`, `serial`, `item_id`,
	    	`size_id`, `qty`, `rtp`, `type`) VALUES ('".$this->invoNo."',
	    	'".$this->locId."','".$serial."','".$this->itemId[$i]."', '".$this->sizeId[$i]."',
	    	'".$this->qty[$i]."', '".$this->rtp[$i]."', '2')");

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->cost[$i]."', '2')");

		    $getItem = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
		    AND wrhs_id = '".$this->locId."'");
		    
		    if (mysql_num_rows($getItem) > 0)
		    {
		    	$this->addItemWH($i, $this->locId);
		    	
		    } else {
		    	$this->newItemWH($i, $this->locId);
		    	$this->addItemWH($i, $this->locId);
		    }
	    }
	    echo json_encode($this->invoNo);
	}

	public function exchangeNow()
	{
		global $db;

		$getInvoNo  = $db->query("SELECT MAX(invo_no)
								  AS invo_no 
								  FROM invoice_header
								  WHERE loc_id = '".$this->locId."'");
		$getTransNo = $db->query("SELECT MAX(trans_no)
								  AS trans_no
								  FROM inventory_header
								  WHERE wrhs_id = '".$this->locId."'");

		$rowInvoNo  = $db->fetch_array($getInvoNo);
		$rowTransNo = $db->fetch_array($getTransNo);

		if ($rowInvoNo['invo_no'] != NULL)
		{
			$this->invoNo = $rowInvoNo['invo_no'] + 1;
		} else {
			$this->invoNo += 1;
		}

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		$branchShortName = userClass::getBranchShortName($this->locId);

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

	    // A set of queries; if one fails, an exception should be thrown
	    $db->query("INSERT INTO invoice_header (`invo_type`, `invo_no`, `invo_code`, `date`, `time`, `cust_id`,
	    `loc_id`, `cash_man_id`, `sales_man_id`, `ref_invo_no`, `total_amount`, `tax`,`discount_amount`,
	    `net_value`, `qty`, `payment_type_id`, `comments`) VALUES ('3', '".$this->invoNo."',
	    '".$branchShortName."-".$this->invoNo."', '".$this->currentDate."', NOW(),
	    '".$this->customerId."', '".$this->locId."', '".$this->cashMan."', '".$this->salesMan."', 
	    '".$this->refInvoNo."', '".$this->subtotal."', '0', '".$this->cashDisc."', '".$this->total."',
	    '".$this->totalQty."', '".$this->paymentType."', '".$this->comments."')");

	    $db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `invo_no`, `date`,
		`time`, `stock_keeper_id`, `total_cost`) VALUES ('".$this->locId."', '3',
		'".$this->transNo."', '".$this->invoNo."', '".$this->currentDate."', NOW(), '".$this->stockKeeper."',
		'".$this->totalCost."')");

	    for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

	    	$db->query("INSERT INTO invoice_detail (`invo_no`, `loc_id`, `serial`, `item_id`,
	    	`size_id`, `qty`, `rtp`, `type`) VALUES ('".$this->invoNo."',
	    	'".$this->locId."','".$serial."','".$this->itemId[$i]."', '".$this->sizeId[$i]."',
	    	'".$this->qty[$i]."', '".$this->rtp[$i]."', '".$this->itemTransType[$i]."')");

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->cost[$i]."',
		    '".$this->itemTransType[$i]."')");

		    $getItem = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
		    AND wrhs_id = '".$this->locId."'");
		    
		    if (mysql_num_rows($getItem) > 0)
		    {
		    	if ($this->itemTransType[$i] == 1)
		    	{
		    		$this->subItemWH($i, $this->locId);
		    	} else {
		    		$this->addItemWH($i, $this->locId);
		    	}
		    	
		    } else {
		    	if ($this->itemTransType[$i] == 1)
		    	{
		    		$this->newItemWH($i, $this->locId);
		    		$this->subItemWH($i, $this->locId);
		    	} else {
		    		$this->newItemWH($i, $this->locId);
		    		$this->addItemWH($i, $this->locId);
		    	}
		    }
	    }
	    echo json_encode($this->invoNo);
	}

	public function transfer()
	{
		global $db;

		$getTransNo   = $db->query("SELECT MAX(trans_no)
									AS trans_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$getTransNoTo = $db->query("SELECT MAX(trans_no)
									AS trans_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->branchTo."'");

		$getDocNo	  = $db->query("SELECT MAX(doc_no)
									AS doc_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$rowTransNo   = $db->fetch_array($getTransNo);
		$rowTransNoTo = $db->fetch_array($getTransNoTo);
		$rowDocNo     = $db->fetch_array($getDocNo);

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		if ($rowTransNoTo['trans_no'] != NULL)
		{
			$this->transNoTo = $rowTransNoTo['trans_no'] + 1;
		} else {
			$this->transNoTo += 1;
		}

		if ($rowDocNo['doc_no'] != NULL)
		{
			$this->docNo = $rowDocNo['doc_no'] + 1;
		} else {
			$this->docNo += 1;
		}

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		// A set of queries; if one fails, an exception should be thrown
		$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
		`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`, `comments`, `status`) VALUES
		('".$this->locId."', '4', '".$this->transNo."', '".$this->docNo."', '".$this->currentDate."', NOW(),
		'".$this->locId."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
		'".$this->totalCost."', '".$this->totalQty."', '".$this->comments."', '1')");

		for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->rtp[$i]."',
		    '".$this->cost[$i]."', '4')");

		    $getItem = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
		    AND wrhs_id = '".$this->locId."'");
		    
		    if (mysql_num_rows($getItem) > 0)
		    {
		    	$this->subItemWH($i, $this->locId);		    	
		    } else {
		    	$this->newItemWH($i, $this->locId);
		    	$this->subItemWH($i, $this->locId);
		    }

		    $getItem2 = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
		    AND wrhs_id = '".$this->branchTo."'");
		    
		    if (mysql_num_rows($getItem2) > 0)
		    {
		    	$this->addItemWH($i, $this->branchTo);		    	
		    } else {
		    	$this->newItemWH($i, $this->branchTo);
		    	$this->addItemWH($i, $this->branchTo);
		    }
	    }

		$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
		`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`) VALUES
		('".$this->branchTo."', '5', '".$this->transNoTo."', '".$this->docNo."', '".$this->currentDate."', NOW(),
		'".$this->locId."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
		'".$this->totalCost."', '".$this->totalQty."')");
		//Notification
		$db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
						VALUES (3, '".$this->transNo."', '".$this->locId."', '".$this->branchTo."', NOW(), NOW())");
	}

	public function transfer2()
	{
		global $db;

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		// A set of queries; if one fails, an exception should be thrown
		$db->query("UPDATE inventory_header SET `to_wrhs_id` = '".$this->branchTo."',
		`total_rtp` = '".$this->subtotal."', `total_cost` = '".$this->totalCost."', `qty` = '".$this->totalQty."',
		`comments` = '".$this->comments."', `status` = '1'
		WHERE `trans_no` = '".$this->transNo."' AND `wrhs_id` = '".$this->locId."'");

		// delete detail data
		$db->query("DELETE FROM inventory_detail
					WHERE `trans_no` = '".$this->transNo."'
					AND   `wrhs_id`  = '".$this->locId."'");

		for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->rtp[$i]."',
		    '".$this->cost[$i]."', '4')");

		    $getItem = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
		    AND wrhs_id = '".$this->locId."'");
		    
		    if (mysql_num_rows($getItem) > 0)
		    {
		    	$this->subItemWH($i, $this->locId);		    	
		    } else {
		    	$this->newItemWH($i, $this->locId);
		    	$this->subItemWH($i, $this->locId);
		    }

		    $getItem2 = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
		    AND wrhs_id = '".$this->branchTo."'");
		    
		    if (mysql_num_rows($getItem2) > 0)
		    {
		    	$this->addItemWH($i, $this->branchTo);		    	
		    } else {
		    	$this->newItemWH($i, $this->branchTo);
		    	$this->addItemWH($i, $this->branchTo);
		    }
	    }

		$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
		`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`, `status`) VALUES
		('".$this->branchTo."', '4', '".$this->transNoTo."', '".$this->docNo."', '".$this->currentDate."', NOW(),
		'".$this->locId."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
		'".$this->totalCost."', '".$this->totalQty."', '1')");
	}

	public function holdTrans()
	{
		global $db;

		$getTransNo   = $db->query("SELECT MAX(trans_no)
									AS trans_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$getDocNo	  = $db->query("SELECT MAX(doc_no)
									AS doc_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$rowTransNo   = $db->fetch_array($getTransNo);
		$rowDocNo     = $db->fetch_array($getDocNo);

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		if ($rowDocNo['doc_no'] != NULL)
		{
			$this->docNo = $rowDocNo['doc_no'] + 1;
		} else {
			$this->docNo += 1;
		}

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		// A set of queries; if one fails, an exception should be thrown
		$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
		`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`, `comments`, `status`) VALUES
		('".$this->locId."', '4', '".$this->transNo."', '".$this->docNo."', '".$this->currentDate."', NOW(),
		'".$this->locId."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
		'".$this->totalCost."', '".$this->totalQty."', '".$this->comments."', '2')");

		for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->rtp[$i]."',
		    '".$this->cost[$i]."', '4')");
	    }
	    //Notification
	    $db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
					VALUES (4, '".$this->transNo."', '".$this->locId."', '".$this->branchTo."', NOW(), NOW())");
	}

	public function doTransferHQ()
	{
		global $db;

		$getTransNo   = $db->query("SELECT MAX(trans_no)
									AS trans_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$getDocNo	  = $db->query("SELECT MAX(doc_no)
									AS doc_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$rowTransNo   = $db->fetch_array($getTransNo);
		$rowDocNo     = $db->fetch_array($getDocNo);

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		if ($rowDocNo['doc_no'] != NULL)
		{
			$this->docNo = $rowDocNo['doc_no'] + 1;
		} else {
			$this->docNo += 1;
		}

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		// A set of queries; if one fails, an exception should be thrown
		$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
		`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`, `comments`, `status`) VALUES
		('".$this->locId."', '4', '".$this->transNo."', '".$this->docNo."', NOW(), NOW(),
		'".$this->branchFrom."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
		'".$this->totalCost."', '".$this->totalQty."', '', '2')");

	    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
	    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '1',
	    '".$this->itemId."', '".$this->sizeId."', '".$this->qty."', '".$this->rtp."',
	    '".$this->cost."', '4')");
	    //Notification
	    $db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
					VALUES (3, '".$this->transNo."', '".$this->branchFrom."', '".$this->branchTo."', NOW(), NOW())");
	}

	public function activateSlip()
	{
		global $db;

		$getTransNo   = $db->query("SELECT MAX(trans_no)
									AS trans_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$getDocNo	  = $db->query("SELECT MAX(doc_no)
									AS doc_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$rowTransNo   = $db->fetch_array($getTransNo);
		$rowDocNo     = $db->fetch_array($getDocNo);

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		if ($rowDocNo['doc_no'] != NULL)
		{
			$this->docNo = $rowDocNo['doc_no'] + 1;
		} else {
			$this->docNo += 1;
		}

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		// A set of queries; if one fails, an exception should be thrown
		$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
		`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`, `comments`, `status`) VALUES
		('".$this->locId."', '4', '".$this->transNo."', '".$this->docNo."', NOW(), NOW(),
		'".$this->branchFrom."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
		'".$this->totalCost."', '".$this->totalQty."', '', '2')");

		for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->rtp[$i]."',
		    '".$this->cost[$i]."', '4')");
	    }
	    //Notification
	    $db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
					VALUES (3, '".$this->transNo."', '".$this->branchFrom."', '".$this->branchTo."', NOW(), NOW())");
	}

	public function saveTransfer()
	{
		global $db;

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		//Check existing item
		$chkExist = $db->query("SELECT * FROM `saved_transfers`
								WHERE `from_wrhs_id` = '".$this->branchFrom."'
								AND   `to_wrhs_id`   = '".$this->branchTo."'
								AND   `trans_no`     = '".$this->transNo."'
								AND   `item_id`      = '".$this->itemId."'
								AND   `size_id`      = '".$this->sizeId."'");
		if (mysql_num_rows($chkExist) > 0) {
			$db->query("UPDATE `saved_transfers` SET `qty` = `qty`+".$this->qty."
						WHERE `from_wrhs_id` = '".$this->branchFrom."'
						AND   `to_wrhs_id`   = '".$this->branchTo."'
						AND   `trans_no`     = '".$this->transNo."'
						AND   `item_id`      = '".$this->itemId."'
						AND   `size_id`      = '".$this->sizeId."'");
		} else {
			$itemSizeQty = userClass::getItemSizeStock($this->itemId, $this->sizeId, $this->branchFrom);
			// A set of queries; if one fails, an exception should be thrown
	    	$db->query("INSERT INTO `saved_transfers` (`from_wrhs_id`, `to_wrhs_id`, `trans_no`, `item_id`,
	    	`size_id`, `qty`, `item_size_qty`) VALUES ('".$this->branchFrom."', '".$this->branchTo."',
	    	'".$this->transNo."', '".$this->itemId."', '".$this->sizeId."', '".$this->qty."',
	    	'".$itemSizeQty."')");
		}
	}

	public function holdTrans2()
	{
		global $db;

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		// A set of queries; if one fails, an exception should be thrown
		$db->query("UPDATE inventory_header SET `to_wrhs_id` = '".$this->branchTo."',
		`total_rtp` = '".$this->subtotal."', `total_cost` = '".$this->totalCost."',
		`qty` = '".$this->totalQty."', `comments` = '".$this->comments."'
		WHERE `trans_no` = '".$this->transNo."' AND `wrhs_id` = '".$this->locId."'");

		// delete detail data
		$db->query("DELETE FROM inventory_detail
					WHERE `trans_no` = '".$this->transNo."'
					AND   `wrhs_id`  = '".$this->locId."'");

		for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->rtp[$i]."',
		    '".$this->cost[$i]."', '4')");
	    }
	    //Notification
	    $db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
					VALUES (1, '".$this->transNo."', '".$this->locId."', '".$this->branchTo."', NOW(), NOW())");
	}

	public function specialTransfer()
	{
		global $db;

		$getTransNo   = $db->query("SELECT MAX(trans_no)
									AS trans_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$getDocNo	  = $db->query("SELECT MAX(doc_no)
									AS doc_no
									FROM inventory_header
									WHERE wrhs_id = '".$this->locId."'");

		$rowTransNo   = $db->fetch_array($getTransNo);
		$rowDocNo     = $db->fetch_array($getDocNo);

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		if ($rowDocNo['doc_no'] != NULL)
		{
			$this->docNo = $rowDocNo['doc_no'] + 1;
		} else {
			$this->docNo += 1;
		}

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		// A set of queries; if one fails, an exception should be thrown
		$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
		`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`, `comments`, `status`) VALUES
		('".$this->locId."', '4', '".$this->transNo."', '".$this->docNo."', '".$this->currentDate."', NOW(),
		'".$this->branchFrom."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
		'".$this->totalCost."', '".$this->totalQty."', '".$this->comments."', '2')");

		for ($i = 0; $i < count($this->itemId); $i++)
	    {
	    	$serial = $i + 1;

		    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
		    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->locId."', '".$this->transNo."', '".$serial."',
		    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->rtp[$i]."',
		    '".$this->cost[$i]."', '4')");
	    }
	    //Notification
	    $db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
					VALUES (3, '".$this->transNo."', '".$this->branchFrom."', '".$this->branchTo."', NOW(), NOW())");
	}

	public function cancelTrans()
	{
		global $db;

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		$getInvnHeader    = $db->query("SELECT `to_wrhs_id` FROM inventory_header
										WHERE  `trans_no` = '".$this->transNo."'
										AND    `wrhs_id`  = '".$this->locId."'");
		$rowInvnHeader    = $db->fetch_array($getInvnHeader);

		// update header status to be canceled
		$db->query("UPDATE inventory_header
					SET    `status`   = '3'
					WHERE  `trans_no` = '".$this->transNo."'
					AND    `wrhs_id`  = '".$this->locId."'");

		//Notification
		$db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
					VALUES (5, '".$this->transNo."', '".$this->locId."', '".$rowInvnHeader['to_wrhs_id']."', NOW(), NOW())");
	}

	public function proceedTrans()
	{
		global $db;

		// First of all, let's begin a transaction
	    $db->query("SET AUTOCOMMIT=0");
		$db->query("START TRANSACTION");

		$getData = $db->query("SELECT * FROM inventory_detail
							   WHERE trans_no = '".$this->transNo."'
							   AND   wrhs_id  = '".$this->locId."'");

		// update header status to be done
		$db->query("UPDATE inventory_header
					SET    `status` = '1'
					WHERE  `trans_no` = '".$this->transNo."'
					AND    `wrhs_id` = '".$this->locId."'");

		// get header data
		$rowInvnHeader = $this->getInventoryHeader($this->transNo, $this->locId);

		$transNote = $this->transNo;

		$this->transNo = 0;

		$getTransNo   = $db->query("SELECT MAX(trans_no)
									AS trans_no
									FROM inventory_header
									WHERE wrhs_id = '".$rowInvnHeader['to_wrhs_id']."'");

		$rowTransNo = $db->fetch_array($getTransNo);

		if ($rowTransNo['trans_no'] != NULL)
		{
			$this->transNo = $rowTransNo['trans_no'] + 1;
		} else {
			$this->transNo += 1;
		}

		$chkData = $db->query("SELECT * FROM inventory_header
							   WHERE wrhs_id = '".$rowInvnHeader['to_wrhs_id']."'
							   AND   trans_type_id   = 5
							   AND   doc_no          = '".$rowInvnHeader['doc_no']."'
							   AND   date            = CURDATE()
							   AND   from_wrhs_id    = '".$this->locId."'
							   AND   to_wrhs_id      = '".$rowInvnHeader['to_wrhs_id']."'
							   AND   stock_keeper_id = '".$this->stockKeeper."'
							   AND   total_rtp       = '".$rowInvnHeader['total_rtp']."'
							   AND   total_cost      = '".$rowInvnHeader['total_cost']."'
							   AND   qty             = '".$rowInvnHeader['qty']."'");

		if (mysql_num_rows($chkData) > 0) {
			die();
		}

		$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
					`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`) VALUES
					('".$rowInvnHeader['to_wrhs_id']."', '5', '".$this->transNo."', '".$rowInvnHeader['doc_no']."',
					NOW(), NOW(), '".$this->locId."', '".$rowInvnHeader['to_wrhs_id']."',
					'".$this->stockKeeper."', '".$rowInvnHeader['total_rtp']."',
					'".$rowInvnHeader['total_cost']."',	'".$rowInvnHeader['qty']."')");

		while ($rowGetData = mysql_fetch_array($getData))
		{
			$getItem = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$rowGetData['item_id']."' AND size_id = '".$rowGetData['size_id']."'
		    AND wrhs_id = '".$rowInvnHeader['from_wrhs_id']."'");
		    
		    if (mysql_num_rows($getItem) > 0)
		    {
		    	$db->query("UPDATE warehouses SET qty = qty - ".$rowGetData['qty']."
		    	WHERE item_id = '".$rowGetData['item_id']."' AND size_id = '".$rowGetData['size_id']."'
		    	AND wrhs_id = '".$rowInvnHeader['from_wrhs_id']."'");
		    } else {
		    	$db->query("INSERT INTO warehouses (`wrhs_id`, `item_id`, `size_id`, `qty`)
	    		VALUES ('".$rowInvnHeader['from_wrhs_id']."', '".$rowGetData['item_id']."', '".$rowGetData['size_id']."', '0')");

		    	$db->query("UPDATE warehouses SET qty = qty - ".$rowGetData['qty']."
		    	WHERE item_id = '".$rowGetData['item_id']."' AND size_id = '".$rowGetData['size_id']."'
		    	AND wrhs_id = '".$rowInvnHeader['from_wrhs_id']."'");
		    }

		    $getItem2 = $db->query("SELECT item_id, size_id FROM warehouses
		    WHERE item_id = '".$rowGetData['item_id']."' AND size_id = '".$rowGetData['size_id']."'
		    AND wrhs_id = '".$rowInvnHeader['to_wrhs_id']."'");
		    
		    if (mysql_num_rows($getItem2) > 0)
		    {
		    	$db->query("UPDATE warehouses SET qty = qty + ".$rowGetData['qty']."
		    	WHERE item_id = '".$rowGetData['item_id']."' AND size_id = '".$rowGetData['size_id']."'
		    	AND wrhs_id = '".$rowInvnHeader['to_wrhs_id']."'");		    	
		    } else {
		    	$db->query("INSERT INTO warehouses (`wrhs_id`, `item_id`, `size_id`, `qty`)
	    		VALUES ('".$rowInvnHeader['to_wrhs_id']."', '".$rowGetData['item_id']."',
	    		'".$rowGetData['size_id']."', '0')");

		    	$db->query("UPDATE warehouses SET qty = qty + ".$rowGetData['qty']."
		    	WHERE item_id = '".$rowGetData['item_id']."' AND size_id = '".$rowGetData['size_id']."'
		    	AND wrhs_id = '".$rowInvnHeader['to_wrhs_id']."'");
		    }
		}
		//Notification
		$db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
					VALUES (6, '".$transNote."', '".$this->locId."', '".$rowInvnHeader['to_wrhs_id']."', NOW(), NOW())");
	}

	public function subItemWH ($i, $wrhs_id)
	{
		global $db;

		$db->query("UPDATE warehouses SET qty = qty - ".$this->qty[$i]."
    	WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
    	AND wrhs_id = '{$wrhs_id}'");
	}

	public function addItemWH ($i, $wrhs_id)
	{
		global $db;

		$db->query("UPDATE warehouses SET qty = qty + ".$this->qty[$i]."
    	WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
    	AND wrhs_id = '{$wrhs_id}'");
	}

	public function newItemWH ($i, $wrhs_id)
	{
		global $db;

		$db->query("INSERT INTO warehouses (`wrhs_id`, `item_id`, `size_id`, `qty`)
    	VALUES ('{$wrhs_id}', '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '0')");
	}

	public static function getPaymentType($paymentTypeId = '')
	{
		global $db;

		$getData      = $db->query("SELECT * FROM payment_types
							   WHERE payment_type_id = '{$paymentTypeId}'");
		$rowGetData   = $db->fetch_array($getData);
		$paymentType  = $rowGetData['desc'];
		return $paymentType;
	}

	public static function getTransType($transTypeId = '')
	{
		global $db;

		$getData    = $db->query("SELECT * FROM trans_types
							   WHERE trans_type_id = '{$transTypeId}'");
		$rowGetData = $db->fetch_array($getData);
		$transType  = $rowGetData['desc'];
		return $transType;
	}

	public function getStatusType($statusId = '')
	{
		global $db;

		$getData = $db->query("SELECT * FROM status
							   WHERE status_id = '{$statusId}'");
		$rowGetData  = $db->fetch_array($getData);
		$statusType  = $rowGetData['desc'];
		return $statusType;
	}

	public function getInvoiceHeader($invoNo = '', $locId = '')
	{
		global $db;

		$getData = $db->query("SELECT * FROM invoice_header
							   WHERE invo_no = {$invoNo}
							   AND loc_id = {$locId}
							   LIMIT 1");
		$InvoHeader = $db->fetch_array($getData);
		return $InvoHeader;
	}

	public function getInventoryHeader($transNo = '', $locId = '')
	{
		global $db;

		$getData = $db->query("SELECT * FROM inventory_header
							   WHERE trans_no = {$transNo}
							   AND wrhs_id = {$locId}
							   LIMIT 1");
		$InvnHeader = $db->fetch_array($getData);
		return $InvnHeader;
	}

	public function getSize($sizeId = '')
	{
		global $db;

		$getData = $db->query("SELECT `desc` FROM items_size
							   WHERE size_id = {$sizeId}
							   LIMIT 1");

		$sizeDesc = $db->fetch_array($getData);
		return $sizeDesc['desc'];
	}
}

$trans = new transClass();