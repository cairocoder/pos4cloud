<?php
	require_once ("_inc/header.php");

	$username   = $_SESSION['username'];
	$locId      = $_SESSION['loc_id'];

	$branchName = userClass::getBranchName($locId);
	$allBrances = userClass::getAllBranches();

	if (isset($_SESSION['date']))
	{
		$currentDate = $_SESSION['date'];
	} else {
		$currentDate = date('Y-m-d');
	}

	if (!empty($_GET['transNo']) && !empty($_GET['wrhsId']))
	{
		$transNo = $_GET['transNo'];
		$wrhsId  = $_GET['wrhsId'];

		$branchfrom = userClass::getBranchName($wrhsId);

		$getInvnHeader  = $db->query("SELECT * FROM inventory_header
									  WHERE trans_no = '{$transNo}'
									  AND wrhs_id    = '{$wrhsId}'
									  AND status     = 2");
		if (mysql_num_rows($getInvnHeader) > 0)
		{
			$rowInvnHeader  = $db->fetch_array($getInvnHeader);
			$invnDate       = $rowInvnHeader['date'];
			$comments       = $rowInvnHeader['comments'];
			$stkKeeperId    = $rowInvnHeader['stock_keeper_id'];
			$stkKeeperName  = userClass::getSalesMan($stkKeeperId);
			$totalQty       = $rowInvnHeader['qty'];
			$totalRtp       = $rowInvnHeader['total_rtp'];
			$totalCost 		= $rowInvnHeader['total_cost'];
			$toWrhsId       = $rowInvnHeader['to_wrhs_id'];
			$toWrhsName     = userClass::getBranchName($toWrhsId);

			$getInvnDetails = $db->query("SELECT * FROM inventory_detail
										  WHERE trans_no = '{$transNo}'
										  AND wrhs_id    = '{$wrhsId}'");
			$proceedBtn = "btnProceedT2";
			$holdBtn    = "btnHoldT2";
		} else {
			$rowInvnHeader  = "";
			$invnDate       = "";
			$comments       = "";
			$stkKeeperId    = "";
			$stkKeeperName  = "";
			$totalQty       = "";
			$totalRtp       = "";
			$totalCost 		= "";
			$toWrhsId       = "";
			$toWrhsName     = "";
			$proceedBtn = "btnProceedT";
			$holdBtn    = "btnHoldT";
		}
	} else {
		$rowInvnHeader  = "";
		$invnDate       = "";
		$comments       = "";
		$stkKeeperId    = "";
		$stkKeeperName  = "";
		$totalQty       = "";
		$totalRtp       = "";
		$totalCost 		= "";
		$toWrhsId       = "";
		$toWrhsName     = "";
		$proceedBtn = "btnProceedT";
		$holdBtn    = "btnHoldT";
	}

	if (isset($_SESSION['date']))
	{
		$currentDate = $_SESSION['date'];
	} else {
		$currentDate = date('Y-m-d');
	}

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
						$sizeDesc  = $curRow[1];
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
								if ($row['item_cost'] != 0) {
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
									$tableItems .= '<td><a id="removeItem2" href="#"><img src="_img/remove.png"></a></td>';
									$tableItems .= '</tr>';
								} else {
									$errors[] = "Error, Cost of #".$itemId." is = 0";
								}
								
							} else {
								$errors[] = "Error, Size: ".$sizeDesc." is not registered with #".$itemId;
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

<h1>Transfer Window</h1>
<div id="container">
	<input type="hidden" id="processType" value="transfer">
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
				<?php if ($_SESSION['user_type'] == "sadmin"): ?>
				<th>Cost</th>
				<?php else: ?>
				<th style="display:none;">Cost</th>
				<?php endif; ?>
				<th>Qty.</th>
				<th>Image</th>
				<th></th>
			</tr>
			<?php if (!empty($getInvnDetails)): ?>
			<?php while ($rowInvnDetails = $db->fetch_array($getInvnDetails)): ?>
			<tr>
				<td><span class="itemId"><?php echo $rowInvnDetails['item_id'] ?></span></td>
				<td><?php echo userClass::getItemName($rowInvnDetails['item_id']) ?></td>
				<td><?php echo userClass::getItemDesc($rowInvnDetails['item_id']) ?></td>
				<td><?php echo userClass::getSizeDesc($rowInvnDetails['size_id']) ?></td>
				<td class="sizeId"><?php echo $rowInvnDetails['size_id'] ?></td>
				<td><span class="rtp"><?php echo $rowInvnDetails['rtp'] ?></span></td>
				<?php if ($_SESSION['user_type'] == "sadmin"): ?>
				<td><span class="cost"><?php echo $rowInvnDetails['cost'] ?></span></td>
				<?php else: ?>
				<td style="display:none;"><span class="cost"><?php echo $rowInvnDetails['cost'] ?></span></td>
				<?php endif; ?>
				<td><span class="qty"><?php echo $rowInvnDetails['qty'] ?></span></td>
				<td><a href="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $rowInvnDetails['item_id'] ?>.jpg" class="group1 cboxElement"><img width="80" src="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $rowInvnDetails['item_id'] ?>.jpg"></a></td>
				<td><a href="#" id="removeItem2"><img src="_img/remove.png"></a></td>
			</tr>
			<?php endwhile; ?>
			<?php endif; ?>
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
					<th>Comments</th><td colspan="2"><textarea id="comments" cols="35" rows="3"><?php echo $comments ?></textarea></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center">
						<?php if ($proceedBtn != "btnProceedT2"): ?>
						<input type="button" name="<?php echo $proceedBtn ?>" id="<?php echo $proceedBtn ?>" value="Proceed (F3)">
						<?php endif; ?>
						<input type="button" name="<?php echo $holdBtn ?>" id="<?php echo $holdBtn ?>" value="Hold (F4)">
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div id="sidebar">
	<h2>Branch From:</h2>
	<br class="clear">
	<p><?php if(!empty($branchfrom)) echo $branchfrom; else echo $branchName; ?></p>
	<h2>Branch To:</h2>
	<br class="clear">
	<?php $state = ""; ?>
	<?php if (!empty($_GET['wrhsId'])) {
		if ($_GET['wrhsId'] != $locId) {
			$state = "disabled";
		}
	} ?>
	<p><select id="allBrances" <?php echo $state ?>>
		<option value="0">Select ..</option>
		<?php foreach ($allBrances as $key => $value): ?>
			<?php if ($key == $toWrhsId): ?>
			<option value="<?php echo $key ?>" selected><?php echo $value ?></option>
			<?php else: ?>
			<option value="<?php echo $key ?>"><?php echo $value ?></option>
			<?php endif; ?>
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
		    echo '<span class="error">' . $value . '</span><br><br>';
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

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
	<div id="selectReason">
		<h2>Select Reason:</h2>
		<p><input type="radio" name="rdReason" class="rdReason" value="1">Defect</p>
		<p><input type="radio" name="rdReason" class="rdReason" value="2">Out of stock</p>
		<p><input type="radio" name="rdReason" class="rdReason" value="3">Size not available</p>
		<span style="display:none" id="curItemId"></span>
		<span style="display:none" id="curSizeId"></span>
		<p><input type="button" name="btnItemReason" id="btnItemReason" value="Send"></p>
	</div>
</div>

<script type="text/javascript">
	document.getElementById("txtItems2").focus();
</script>
<script language="JavaScript">
//////////F12 disable code////////////////////////
document.onkeypress = function (event) {
	event = (event || window.event);
	if (event.keyCode == 123) {
		//alert('No F-12');
		return false;
	}
}
document.onmousedown = function (event) {
	event = (event || window.event);
	if (event.keyCode == 123) {
		//alert('No F-keys');
		return false;
	}
}
document.onkeydown = function (event) {
	event = (event || window.event);
	if (event.keyCode == 123) {
		//alert('No F-keys');
		return false;
	}
}
/////////////////////end///////////////////////
</script>
<script type="text/javascript">
<!-- //Disable right click script
//visit http://www.rainbow.arch.scriptmania.com/scripts/
var message="Sorry, right-click has been disabled";
///////////////////////////////////
function clickIE() {
	if (document.all) {
		(message);
		return false;
	}
}
function clickNS(e) {
	if (document.layers||(document.getElementById&&!document.all)) {
		if (e.which==2||e.which==3) {
			(message);
			return false;
		}
	}
}
if (document.layers) {
	document.captureEvents(Event.MOUSEDOWN);
	document.onmousedown=clickNS;
} else {
	document.onmouseup=clickNS;
	document.oncontextmenu=clickIE;
}
document.oncontextmenu = new Function("return false")
// -->
</script> 

<?php require_once ("_inc/footer.php"); ?>