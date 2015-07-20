<?php

require_once ("_inc/header.php");

// $branchName      = userClass::getBranchName($locId);
// $branchShortName = userClass::getBranchShortName($locId);

mysql_query("UPDATE `invoice_header` SET `invo_no` = '{userClass::getBranchShortName(`loc_id`)}'`invo_id`");