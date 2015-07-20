<?php

require_once ("dbClass.php");
require_once ("transClass.php");

/**
* Receiving Class
*/
class rcvClass extends transClass
{
	public function receiving()
	{
		global $db;

		$getTransNo = $db->query("SELECT MAX(trans_no)
								  AS trans_no
								  FROM inventory_header
								  WHERE wrhs_id = '".$this->branchTo."'");

		$getDocNo   = $db->query("SELECT MAX(doc_no)
								  AS doc_no
								  FROM inventory_header
								  WHERE wrhs_id = '".$this->branchTo."'");

		$rowTransNo = $db->fetch_array($getTransNo);
		$rowDocNo   = $db->fetch_array($getDocNo);

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

		//Check for duplicate entries
		$chkDup   = $db->query("SELECT * FROM inventory_header
								WHERE `wrhs_id`         = '".$this->branchTo."'
								AND   `trans_type_id`   = 7
								AND   `date`            = '".$this->currentDate."'
								AND   `from_wrhs_id`    = '".$this->locId."'
								AND   `to_wrhs_id`      = '".$this->branchTo."'
								AND   `stock_keeper_id` = '".$this->stockKeeper."'
								AND   `total_rtp`       = '".$this->subtotal."'
								AND   `total_cost`      = '".$this->totalCost."'
								AND   `qty`             = '".$this->totalQty."'
								AND   `comments`        = '".$this->comments."'");
		if (mysql_num_rows($chkDup) > 0) {
			echo "Duplicate";
			return false;
		} else {
			// A set of queries; if one fails, an exception should be thrown
			$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
			`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`, `comments`) VALUES
			('".$this->branchTo."', '7', '".$this->transNo."', '".$this->docNo."', '".$this->currentDate."', NOW(),
			'".$this->locId."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
			'".$this->totalCost."', '".$this->totalQty."', '".$this->comments."')");

			for ($i = 0; $i < count($this->itemId); $i++)
		    {
		    	$serial = $i + 1;

			    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
			    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->branchTo."', '".$this->transNo."', '".$serial."',
			    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->rtp[$i]."',
			    '".$this->cost[$i]."', '7')");

			    $getItem = $db->query("SELECT item_id, size_id FROM warehouses
			    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
			    AND wrhs_id = '".$this->branchTo."'");
			    
			    if (mysql_num_rows($getItem) > 0)
			    {
			    	$this->addItemWH($i, $this->branchTo);		    	
			    } else {
			    	$this->newItemWH($i, $this->branchTo);
			    	$this->addItemWH($i, $this->branchTo);
			    }
		    }
		    $db->query("INSERT INTO `trans_notify` (`notify_type_id`, `trans_no`, `from_wrhs_id`, `to_wrhs_id`, `date`, `time`)
						VALUES (2, '".$this->transNo."', '".$this->locId."', '".$this->branchTo."', NOW(), NOW())");
		}
	}

	public function revReceiving()
	{
		global $db;

		$getTransNo = $db->query("SELECT MAX(trans_no)
								  AS trans_no
								  FROM inventory_header
								  WHERE wrhs_id = '".$this->branchTo."'");

		$getDocNo   = $db->query("SELECT MAX(doc_no)
								  AS doc_no
								  FROM inventory_header
								  WHERE wrhs_id = '".$this->branchTo."'");

		$rowTransNo = $db->fetch_array($getTransNo);
		$rowDocNo   = $db->fetch_array($getDocNo);

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

		//Check for duplicate entries
		$chkDup   = $db->query("SELECT * FROM inventory_header
								WHERE `wrhs_id`         = '".$this->branchTo."'
								AND   `trans_type_id`   = 8
								AND   `date`            = '".$this->currentDate."'
								AND   `from_wrhs_id`    = '".$this->locId."'
								AND   `to_wrhs_id`      = '".$this->branchTo."'
								AND   `stock_keeper_id` = '".$this->stockKeeper."'
								AND   `total_rtp`       = '".$this->subtotal."'
								AND   `total_cost`      = '".$this->totalCost."'
								AND   `qty`             = '".$this->totalQty."'
								AND   `comments`        = '".$this->comments."'");
		if (mysql_num_rows($chkDup) > 0) {
			echo "Duplicate";
			return false;
		} else {
			// A set of queries; if one fails, an exception should be thrown
			$db->query("INSERT INTO inventory_header (`wrhs_id`, `trans_type_id`, `trans_no`, `doc_no`, `date`,
			`time`, `from_wrhs_id`, `to_wrhs_id`, `stock_keeper_id`, `total_rtp`, `total_cost`, `qty`, `comments`) VALUES
			('".$this->branchTo."', '8', '".$this->transNo."', '".$this->docNo."', '".$this->currentDate."', NOW(),
			'".$this->locId."', '".$this->branchTo."', '".$this->stockKeeper."', '".$this->subtotal."',
			'".$this->totalCost."', '".$this->totalQty."', '".$this->comments."')");

			for ($i = 0; $i < count($this->itemId); $i++)
		    {
		    	$serial = $i + 1;

			    $db->query("INSERT INTO inventory_detail (`wrhs_id`, `trans_no`, `serial`, `item_id`,
			    `size_id`, `qty`, `rtp`, `cost`, `type`) VALUES ('".$this->branchTo."', '".$this->transNo."', '".$serial."',
			    '".$this->itemId[$i]."', '".$this->sizeId[$i]."', '".$this->qty[$i]."', '".$this->rtp[$i]."',
			    '".$this->cost[$i]."', '8')");

			    $getItem = $db->query("SELECT item_id, size_id FROM warehouses
			    WHERE item_id = '".$this->itemId[$i]."' AND size_id = '".$this->sizeId[$i]."'
			    AND wrhs_id = '".$this->branchTo."'");
			    
			    if (mysql_num_rows($getItem) > 0)
			    {
			    	$this->subItemWH($i, $this->branchTo);		    	
			    } else {
			    	$this->newItemWH($i, $this->branchTo);
			    	$this->subItemWH($i, $this->branchTo);
			    }
		    }
		}
	}	
}

$rcv = new rcvClass();