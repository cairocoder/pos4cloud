<?php

require_once ("_inc/header.php");
require_once ("_inc/transClass.php");

$locId      = $_SESSION['loc_id'];

?>

<h1>Size Swap Window</h1>

<?php

if (isset($_GET['btnSubmit']) && !empty($_GET['itemId']))
{
	$itemId = $_GET['itemId'];
	$query1 = "SELECT size_id, qty FROM warehouses
			   WHERE  item_id = '{$itemId}'
			   AND    wrhs_id = '{$locId}'";

	$result1   = $db->query($query1);

	if (mysql_num_rows($result1) == 0)
	{
		echo '<script>window.location = "sizeSwap.php"</script>';
		die();
	}

	while ($row1  = $db->fetch_array($result1))
	{
		$sizeId[] = $row1['size_id'];
		$qty[]    = $row1['qty'];
	}
	
	$sumQty     = array_sum($qty);
	$dataTable  = '<div id="reportWrapper">';
	$dataTable .= '<p>Item# '.$itemId.'</p><br>';
	$dataTable .= '<p>'.userClass::getItemDesc($itemId).'</p><br>';
	$dataTable .= '<form name="frmSizeSwap" id="frmSizeSwap" method="POST" action="#"  enctype="multipart/form-data">';
	$dataTable .= '<table id="tblTransReport">';
	$dataTable .= '<tr><th>Size</th><th>Value</th></tr>';
	$result     = $db->query("SELECT dept_id FROM items
					   WHERE item_id = '{$itemId}'");
	$row        = $db->fetch_array($result);
	$deptId     = $row['dept_id'];
	$result     = $db->query("SELECT `size_id`, `desc` FROM items_size
							  WHERE dept_id = '{$deptId}'
							  ORDER BY `order` ASC");
	while ($row = $db->fetch_array($result))
	{
		$dataTable .= '<tr>';
		$dataTable .= '<td>'.$row['desc'].'</td>';
		$arrayKey = array_search($row['size_id'], $sizeId);
		//var_dump($arrayKey);
		if ($arrayKey !== false)
		{
			$dataTable .= '<td><input type="text" style="text-align:center;" name="'.$row['size_id'].'" value="'.$qty[$arrayKey].'"></td>';
		} else {
			$dataTable .= '<td><input type="text" style="text-align:center;" name="'.$row['size_id'].'"></td>';
		}
		$dataTable .= '</tr>';
	}
	$dataTable .= '<tr><th>Total</th><th>'.$sumQty.'</th></tr>';
	$dataTable .= '</table>';
	$dataTable .= '<br><br>';
	$dataTable .= '<input type="submit" name="btnSubmitSize" value="Submit">';
	$dataTable .= '</form>';
	$dataTable .= '</div>';
}

if (isset($_POST['btnSubmitSize']) && !empty($_GET['itemId']))
{
	array_pop($_POST);
	$sumArray = 0;

	foreach ($_POST as $key => $value) {
		$sumArray += $value;
	}

	if ($sumArray == $sumQty)
	{
		$db->query("DELETE FROM warehouses
					WHERE item_id = '{$itemId}'
					AND wrhs_id = '{$locId}'");
		foreach ($_POST as $key => $value) {
			//echo $key . " - " . $value . "<br>";
			$db->query("INSERT INTO warehouses (`wrhs_id`, `item_id`, `size_id`, `qty`)
						VALUES ('".$locId."', '".$itemId."', '".$key."', '".$value."')");
		}
		echo '<script>document.location.href = "sizeSwap.php?itemId='.$itemId.'&btnSubmit=Submit"</script>';
	} else {
		echo '<script>alert("Error!")</script>';
	}
}

?>

<form name="frmSalesReport" id="frmSalesReport" method="GET" action="#"  enctype="multipart/form-data">
	<label>Item#: <input type="text" name="itemId"></label>
	<input type="submit" name="btnSubmit" value="Submit">
</form>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>