<?php
require_once ("dbClass.php");
require_once ("sessionClass.php");

$locId = $_SESSION['loc_id'];

if (!empty($_REQUEST['term']))
{
	$term =  mysql_real_escape_string($_REQUEST['term']);
	$result = $db->query("SELECT invo_no AS value FROM invoice_header
						  WHERE invo_no LIKE '%".$term."%'
						  AND loc_id = '{$locId}'");

	if (mysql_num_rows($result) > 0)
	{
		while ($row = $db->fetch_array($result))
		{
			$getInvoices[] = $row;
		}
		echo json_encode($getInvoices);
	}

}