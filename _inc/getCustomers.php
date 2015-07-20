<?php
require_once ("dbClass.php");

if (!empty($_REQUEST['term']))
{
	$term =  mysql_real_escape_string($_REQUEST['term']);
	$result = $db->query("SELECT cust_tel AS value FROM customers WHERE cust_tel LIKE '%".$term."%'");

	if (mysql_num_rows($result) > 0)
	{
		while ($row = $db->fetch_array($result))
		{
			$getCustomers[] = $row;
		}
		echo json_encode($getCustomers);
	}

}