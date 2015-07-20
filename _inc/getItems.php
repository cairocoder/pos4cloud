<?php
require_once ("dbClass.php");

if (!empty($_REQUEST['term']))
{
	$term =  mysql_real_escape_string($_REQUEST['term']);
	$result = $db->query("SELECT item_id AS value FROM items WHERE item_id LIKE '%".$term."%'");

	if (mysql_num_rows($result) > 0)
	{
		while ($row = $db->fetch_array($result))
		{
			$getItems[] = $row;
		}
		echo json_encode($getItems);
	}
}