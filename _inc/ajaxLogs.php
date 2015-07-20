<?php

require_once ("dbClass.php");
require_once ("transClass.php");
require_once ("userClass.php");
require_once ("sessionClass.php");

if (!empty($_POST['action']) && $_POST['action'] == "sendReason")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}
	
	$userId    = $_SESSION['user_id'];
	$locId     = $_SESSION['loc_id'];
	$reasonId  = $_POST['reasonId'];
	$itemId    = $_POST['itemId'];
	$sizeId    = $_POST['sizeId'];
	$transNo   = $_POST['transNo'];
	$wrhsId    = $_POST['wrhsId'];

	$db->query("INSERT INTO trans_log (`mod_wrhs_id`, `user_id`, `wrhs_id`, `trans_no`, `item_id`, `size_id`, `reason_id`, `date`, `time`)
			    VALUES ('{$locId}', '{$userId}', '{$wrhsId}', '{$transNo}', '{$itemId}', '{$sizeId}', '{$reasonId}', NOW(), NOW())");
}