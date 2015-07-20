<?php require_once ("_inc/header2.php"); 

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

?>

<h1>Manage Target</h1>

<?php

$allBranches = userClass::getAllBranchesShort();

if (isset($_GET['btnSubmit']))
{
	$month = $_GET['selMonth'];
	$year  = $_GET['selYear'];

	$dataTable  = '<tr>';
	$dataTable .= '<th colspan="2">Set Target</th>';
	$dataTable .= '</tr>';

	foreach ($allBranches as $key => $value) {
		$dataTable .= '<tr>';
		$dataTable .= '<th>'.$value.'</th>';

		//Check if Target is already SET
		$chkTarget  = $db->query("SELECT * FROM `target`
								  WHERE `loc_id` = '{$key}'
								  AND   `year`   = '{$year}'
								  AND   `month`   = '{$month}'
								  LIMIT 1");

		if (mysql_num_rows($chkTarget) > 0) {
			$rowTraget  = $db->fetch_array($chkTarget);
			$dataTable .= '<td><input style="text-align:center;" type="text" name='.$key.' value="'.$rowTraget['desc'].'"></td>';	
		} else{
			$dataTable .= '<td><input style="text-align:center;" type="text" name='.$key.' value="0.00"></td>';
		}

		$dataTable .= '</tr>';
	}

	$dataTable .= '<tr>';
	$dataTable .= '<td colspan="2" style="text-align:center; padding:10px;">';
	$dataTable .= '<input type="submit" name="btnSetTarget" id="" value="Set Target">';
	$dataTable .= '</td>';
	$dataTable .= '</tr>';
}

if (isset($_POST['btnSetTarget']))
{
	$month     = $_GET['selMonth'];
	$year      = $_GET['selYear'];
	array_pop($_POST);
	$dataArray = $_POST;
	foreach ($dataArray as $key => $value) {

		//Check if Target is already SET
		$chkTarget  = $db->query("SELECT * FROM `target`
								  WHERE `loc_id` = '{$key}'
								  AND   `year`   = '{$year}'
								  AND   `month`  = '{$month}'
								  LIMIT 1");

		if (mysql_num_rows($chkTarget) > 0) {
			$db->query("UPDATE `target` SET `desc` = '{$value}'
						WHERE `loc_id` = '{$key}'
						AND   `year`   = '{$year}'
						AND   `month`  = '{$month}'");
		} else {
			$db->query("INSERT INTO `target` (`desc`, `year`, `month`, `loc_id`)
						VALUES ('{$value}', '{$year}', '{$month}', '{$key}')");
		}
		//header('Location: '.$_SERVER['REQUEST_URI']);
	}
}

?>

<form name="frmStoresSalesReport" id="frmStoresSalesReport" method="GET" action="#"  enctype="multipart/form-data">
	<table>
		<tr>
			<th>Year</th>
			<th>Month</th>
			<th></th>	
		</tr>
		<tr>
			<td>
				<select name="selYear">
					<option value="2013">2013</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
					<option value="2016">2016</option>
					<option value="2017">2017</option>
					<option value="2018">2018</option>
					<option value="2019">2019</option>
					<option value="2020">2020</option>
				</select>
			</td>
			<td>
				<select name="selMonth">
					<option value="1" selected>1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
				</select>
			</td>
			<td>
				<input type="submit" name="btnSubmit" value="Submit">
			</td>
		</tr>
	</table>	
</form>

<form name="frmSetTarget" id="frmSetTarget" method="POST" action="#"  enctype="multipart/form-data">
	<table style="margin: 0 auto;">
		<?php if (isset($dataTable)) echo $dataTable ?>
	</table>
</form>

<?php require_once ("_inc/footer.php"); ?>