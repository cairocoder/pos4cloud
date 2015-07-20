<?php
	require_once ("_inc/header2.php");

	$username   = $_SESSION['username'];
	$locId      = $_SESSION['loc_id'];

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

	$salesMen   = userClass::getSalesMen($locId);
	$branchName = userClass::getBranchName($locId);
	$allBrances = userClass::getAllBranches();

	if (isset($_SESSION['date']))
	{
		$currentDate = $_SESSION['date'];
	} else {
		$currentDate = date('Y-m-d');
	}

	$comments       = "";
	$totalQty       = "";
	$totalRtp       = "";
	$totalCost 		= "";

	if (isset($_POST['startUpload']))
	{
		unset($errors);
		unset($success);
		define('CSV_PATH', "_uploads/");
		$mimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'text/comma-separated-values');
		$tableItems = "";
		$totalQty   = 0;
		$totalRtp   = 0;
		$totalCost  = 0;
		
		if (in_array($_FILES['uploadItems']['type'], $mimes))
		{
		    if ($_FILES["uploadItems"]["error"] > 0)
		    {
		      $errors[] = $_FILES["file"]["error"];
		    } else {
				$ext        = explode('.',$_FILES['uploadItems']['name']);
				$extension  = $ext[1];
				$newName    = $ext[0].'_'.time();
				$newPath    = CSV_PATH . $newName . '.' . $extension ;
				move_uploaded_file($_FILES['uploadItems']['tmp_name'], $newPath);
				$file       = fopen($newPath, "r");
				$numCol     = count(fgetcsv($file));
				if ($numCol == 3)
				{
			        while ($curRow  = fgetcsv($file))
					{
			            $itemId    = $curRow[0];				
						$sizeDesc    = $curRow[1];
						$txtQty    = $curRow[2];

			            $result  = $db->query("SELECT item_id FROM items WHERE item_id = '{$itemId}'");

			            if (mysql_num_rows($result) > 0)
			            {
			            	$result  = $db->query("SELECT item_id, item_name, dept_id, msrp, rtp, item_cost FROM items WHERE item_id = '".$itemId."'");
							$row     = $db->fetch_array($result);

							$getDept = $db->query("SELECT long_desc FROM items_dept WHERE dept_id='".$row['dept_id']."'");
							$row2    = $db->fetch_array($getDept);

							$getSize = $db->query("SELECT `desc`, `size_id` FROM items_size WHERE `desc` = '".$sizeDesc."' AND `dept_id` = '".$row['dept_id']."'");

							if (mysql_num_rows($getSize) > 0)
							{
								$row3    = $db->fetch_array($getSize);
								$tableItems .= '<tr class="dropItems" style="background: lightred;">';
								$tableItems .= '<td><span class="itemId">'.$itemId.'</span></td>';
								$tableItems .= '<td>'.$row['item_name'].'</td>';
								$tableItems .= '<td>'.$row2['long_desc'].'</td>';
								$tableItems .= '<td>'.$row3['desc'].'</td>';
								$tableItems .= '<td class="sizeId">'.$row3['size_id'].'</td>';
								$tableItems .= '<td>'.$row['msrp'].'</td>';
								$tableItems .= '<td><span class="rtp">'.$row['rtp'].'</span></td>';
								if ($_SESSION['user_type'] == "sadmin")
								{
									$tableItems .= '<td><span class="cost">'.$row['item_cost'].'</span></td>';
								} else {
									$tableItems .= '<td style="display:none;"><span class="cost">'.$row['item_cost'].'</span></td>';
								}
								$tableItems .= '<td><span class="qty">'.$txtQty.'</span></td>';
								$totalQty   += $txtQty;
								$totalRtp   += $row['rtp'] * $txtQty;
								$totalCost  += $row['item_cost'] * $txtQty;
								$tableItems .= '<td style="display:none;"><span class="itemTransType">1</span></td>';
								$tableItems .= '<td><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg"><img src="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg" width="80"></a></td>';
								$tableItems .= '<td><a id="removeItem" href="#"><img src="_img/remove.png"></a></td>';
								$tableItems .= '</tr>';
							} else {
								$errors[] = "Error, Size is not registered with #".$itemId;
							}
			            } else {
			               $errors[] = "Error, ".$itemId." is not registered!";
			            }
			        }
					fclose($file);
					$success  = "Done, File imported successfully!";
				} else {
				 $errors[] = "Error, Incorrect column number!";
				}
		    }
		} else {
			$errors[] = "Sorry, File type not allowed!";
		}
	}

?>

<h1>Receiving Window</h1>
<div id="container">
	<input type="hidden" id="processType" value="receiving">
	<div id="selProd">
		<h2>Bulk Select:</h2>
		<form method="post" enctype="multipart/form-data">
			<input name="uploadItems" type="file" id="uploadItems" />
			<input type="Submit" name="startUpload" id="startUpload" value="Import">
		</form>
		<br class="clear">
	</div>
	<div id="downTemp">
		<h2>Template:</h2>
		<a id="downloadTemplate" href="_uploads/upload-items.csv">Download Template</a>
		<br class="clear">
	</div>
	<div id="selProd">
		<h2>Select Product:</h2>
		<input name="txtItems2" type="text" id="txtItems2" />
		<input type="button" name="btnSubmitItem2" id="btnSubmitItem2" value="Submit" />
		<input type="button" name="btnReset2" id="btnReset2" value="Reset" />
		<br class="clear">
	</div>
	<div id="selSize">
		<h2>Select Size:</h2>
		<div id="sizeValues"></div>
		<br class="clear">
	</div>
	<div id="itemsWrapper">
		<table id="tableItems">
			<tr>
				<th class="thItemId">Item#</th>
				<th>Item Name</th>
				<th>Description</th>
				<th>Size</th>
				<th>MSRP</th>
				<th>Price</th>
				<th>Cost</th>
				<th>Qty.</th>
				<th>Image</th>
				<th></th>
			</tr>
			<?php if (!empty($tableItems)) echo $tableItems; ?>
		</table>
	</div>
	<div id="invFooter">
		<div id="priceCalc">
			<table id="priceTable">
				<tr>
					<th>Total Qty.</th><td><span class="totalQty"><?php echo $totalQty ?></span></td>
				</tr>
				<tr>
					<th>Total Price</th><td><span class="subtotal"><?php echo $totalRtp ?></span></td>
				</tr>
				<?php if ($_SESSION['user_type'] == "sadmin"): ?>
				<tr>
					<th>Total Cost</th><td><span class="totalCost"><?php echo $totalCost ?></span></td>
				</tr>
				<?php else: ?>
				<tr style="display:none;">
					<th>Total Cost</th><td><span class="totalCost"><?php echo $totalCost ?></span></td>
				</tr>
				<?php endif; ?>
				<tr>
					<th>Comments</th><td colspan="2"><textarea id="comments" cols="35" rows="3"></textarea></td>
				</tr>
					<th>Reverse</th><td colspan="2"><input type="checkbox" name="chkReverse" id="chkReverse"> Reverse</td>
				</tr>
				<tr>
					<td colspan="3"><input type="button" name="btnProceedR" id="btnProceedR" value="Proceed (F3)"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div id="sidebar">
	<h2>Branch From:</h2>
	<br class="clear">
	<p>HQ - RECEIVING</p>
	<h2>Branch To:</h2>
	<br class="clear">
	<p><select id="allBrances">
		<option value="0">Select ..</option>
		<?php foreach ($allBrances as $key => $value): ?>
			<option value="<?php echo $key ?>"><?php echo $value ?></option>
		<?php endforeach; ?>
	</select></p>
	<h2>Date</h2>
	<br class="clear">
	<p class="currentDate"><?php echo $currentDate ?></p>
	<h2>Stock Keeper</h2>
	<br class="clear">
	<p><?php echo $username ?></p>
	<?php
		if (isset($errors))
		{
		  foreach ($errors as $key => $value) {
		    echo '<span class="error">' . $value . '</span><br>';
		  }
		  echo '<br>';
		}
		if (isset($duplicates))
		{
		 echo '<span class="error">Duplicate values:</span><br>';
		  foreach ($duplicates as $key => $value) {
		    echo $value . '<br>';
		  }
		  echo '<br>';
		}
		if (isset($success)) echo '<span class="success">' . $success . '</span><br><br>';
	?>
</div>
<script type="text/javascript">
	document.getElementById("txtItems2").focus();
</script>
<?php require_once ("_inc/footer.php"); ?>