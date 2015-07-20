<?php

require_once ("rcvClass.php");
require_once ("userClass.php");
require_once ("sessionClass.php");

if (!empty($_POST['action']) && $_POST['action'] == "receiving")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$rcv->itemId      = $_POST['itemId'];
	$rcv->branchTo    = $_POST['branchTo'];
	$rcv->stockKeeper = $_SESSION['user_id'];
	$rcv->locId       = 2;
	$rcv->sizeId      = $_POST['sizeId'];
	$rcv->qty         = $_POST['qty'];
	$rcv->totalQty    = $_POST['totalQty'];
	$rcv->subtotal    = $_POST['subtotal'];
	$rcv->totalCost   = $_POST['totalCost'];
	$rcv->rtp         = $_POST['rtp'];
	$rcv->cost    	= $_POST['cost'];
	$rcv->comments    = $_POST['comments'];
	$rcv->currentDate = $_POST['currentDate'];

	try {
		$rcv->receiving();
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "revReceiving")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$rcv->itemId      = $_POST['itemId'];
	$rcv->branchTo    = $_POST['branchTo'];
	$rcv->stockKeeper = $_SESSION['user_id'];
	$rcv->locId       = 2;
	$rcv->sizeId      = $_POST['sizeId'];
	$rcv->qty         = $_POST['qty'];
	$rcv->totalQty    = $_POST['totalQty'];
	$rcv->subtotal    = $_POST['subtotal'];
	$rcv->totalCost   = $_POST['totalCost'];
	$rcv->rtp         = $_POST['rtp'];
	$rcv->cost    	= $_POST['cost'];
	$rcv->comments    = $_POST['comments'];
	$rcv->currentDate = $_POST['currentDate'];

	try {
		$rcv->revReceiving();
	    // If we arrive here, it means that no exception was thrown
	    // i.e. no query has failed, and we can commit the transaction
	    $db->query("COMMIT");
	} catch (Exception $e) {
	    // An exception has been thrown
	    // We must rollback the transaction
	    $db->query("ROLLBACK");
	}
}