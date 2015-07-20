<?php

require_once ("dbClass.php");
require_once ("transClass.php");
require_once ("userClass.php");
require_once ("sessionClass.php");

if (!empty($_POST['action']) && $_POST['action'] == "addNewRec") {
	$tbl      = $_POST['tbl'];
	$tblCol   = $_POST['tblCol'];
	$records  = $_POST['records'];
	$tblCol2  = implode($tblCol, "`,`");
	$records2 = implode($records, "','");
	//$records2 = mysql_real_escape_string(strtoupper($records2));
	$records2 = strtoupper($records2);
	$query    = "SELECT `$tblCol2` FROM $tbl WHERE";
	for ($i=0; $i < count($tblCol); $i++) { 
		$query .= " `".$tblCol[$i]."` = '".$records[$i]."'";
		if ($i != (count($tblCol) - 1)) {
			$query .= " AND";
		}
	}
	$chkDup = $db->query($query);
	if (mysql_num_rows($chkDup) > 0) {
		echo "error";
		die();
	} else {
		$db->query("INSERT INTO $tbl (`$tblCol2`) VALUES ('$records2')");
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "updateCurRec") {
	$rowId   = $_POST['rowId'];
	$uTbl    = $_POST['uTbl'];
	$tblCol  = $_POST['tblCol'];
	$uTblCol = $_POST['uTblCol'];
	$curVal  = $_POST['curVal'];
	$curVal  = mysql_real_escape_string(strtoupper($curVal));

	$db->query("UPDATE `$uTbl` SET `$uTblCol` = '$curVal' WHERE `$tblCol` = '$rowId'");
}