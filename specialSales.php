<?php
	require_once ("_inc/header2.php");

	$username   = $_SESSION['username'];
	$locId      = $_SESSION['loc_id'];

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

	$salesMen   = userClass::getSalesMen($locId);
	$branchName = userClass::getBranchName($locId);

	if (isset($_SESSION['date']))
	{
		$currentDate = $_SESSION['date'];
	} else {
		$currentDate = date('Y-m-d');
	}

	$totalQty   = "0.00";
	$totalRtp   = "0.00";
	$totalCost  = "0.00";

	if (isset($_POST['startUpload']))
	{
		unset($errors);
		unset($success);
		define('CSV_PATH', "_uploads/");
		$mimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'text/comma-separated-values');
		$tableItems = "";
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
				if ($numCol == 4)
				{
			        while ($curRow  = fgetcsv($file))
					{
			            $itemId    = $curRow[0];				
						$sizeDesc  = $curRow[1];
						$txtQty    = $curRow[2];
						$txtPrice  = $curRow[3];

			            $result  = $db->query("SELECT item_id FROM items WHERE item_id = '{$itemId}'");

			            if (mysql_num_rows($result) > 0)
			            {
			            	$result  = $db->query("SELECT item_id, item_name, dept_id, msrp, rtp, item_cost FROM items
			            						   WHERE item_id = '".$itemId."'");
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
								$tableItems .= '<td><span class="rtp">'.$txtPrice.'</span></td>';
								$tableItems .= '<td><span class="cost">'.$row['item_cost'].'</span></td>';
								$tableItems .= '<td><span class="qty">'.$txtQty.'</span></td>';
								$totalQty   += $txtQty;
								$tableItems .= '<td><span class="totalItem">'.$txtQty * $txtPrice.'</span></td>';
								$totalRtp   += $txtPrice * $txtQty;
								$totalCost  += $row['item_cost'] * $txtQty;
								$tableItems .= '<td style="display:none;"><span class="itemTransType">1</span></td>';
								$tableItems .= '<td><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg"><img src="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$row['item_id'].'.jpg" width="80"></a></td>';
								$tableItems .= '<td><a id="removeItem" href="#"><img src="_img/remove.png"></a></td>';
								$tableItems .= '</tr>';
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

<h1>Special Sales Window</h1>
<div id="container">
	<input type="hidden" id="processType" value="sales">
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
		<a id="downloadTemplate" href="_uploads/special-sales.csv">Download Template</a>
		<br class="clear">
	</div>
	<div id="selProd">
		<h2>Select Product:</h2>
		<input name="txtItems" type="text" id="txtItems" />
		<input type="button" name="btnSubmitItem" id="btnSubmitItem" value="Submit" />
		<input type="button" name="btnReset" id="btnReset" value="Reset" />
		<br class="clear">
	</div>
	<div id="selSize">
		<h2>Select Size:</h2>
		<div id="sizeValues"></div>
		<br class="clear">
	</div>
	<div id="selQty">
		<h2>Select Qty:</h2>
		<div id="qtyValue"></div>
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
				<th>Total</th>
				<th>Image</th>
				<th></th>
			</tr>
			<?php if (!empty($tableItems)) echo $tableItems; ?>
		</table>
	</div>
	<div id="invFooter">
		<div id="getCustomer">
			<p style="padding: 0 0 10px 0;">Select Customer:</p>
			<input type="text" name="txtCustMob" id="txtCustMob">
			<a class="addCust" name="addCust" href="#inline_content">Add +</a>
			<span class="customerName"></span>
			<span class="customerId none"></span>
		</div>
		<div id="priceCalc">
			<table id="priceTable">
				<tr>
					<th>Total Qty.</th><td></td><td><span class="totalQty"><?php echo $totalQty ?></span></td>
				</tr>
				<tr>
					<th>Subtotal</th><td></td><td><span class="subtotal"><?php echo $totalRtp ?></span></td>
				</tr>
				<tr>
					<th>Total Cost</th><td></td><td><span class="totalCost"><?php echo $totalCost ?></span></td>
				</tr>
				<?php if($_SESSION['user_type'] == "admin" || $_SESSION['user_type'] == "sadmin"): ?>
				<tr>
					<th>Discount</th><td>% <input type="text" id="percDisc" name="percDisc" value="0.00"></td><td>$ <input type="text" id="cashDisc" name="cashDisc" value="0.00"></td>
				</tr>
				<?php else: ?>
				<tr style="display:none;">
					<th>Discount</th><td>% <input type="text" id="percDisc" name="percDisc" value="0.00"></td><td>$ <input type="text" id="cashDisc" name="cashDisc" value="0.00"></td>
				</tr>
				<?php endif; ?>
				<tr>
					<th>Total</th><td></td><td><span class="total"><?php echo $totalRtp ?></span></td>
				</tr>
				<tr>
					<th>Comments</th><td colspan="2"><textarea id="comments" cols="35" rows="3"></textarea></td>
				</tr>
				<tr>
					<td colspan="3"><input type="button" name="btnProceed" id="btnProceed" value="Proceed (F3)"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div id="sidebar">
	<h2>Branch</h2>
	<br class="clear">
	<p><?php echo $branchName ?></p>
	<h2>Date</h2>
	<br class="clear">
	<p class="currentDate"><?php echo $currentDate ?></p>
	<h2>Cashier</h2>
	<br class="clear">
	<p><?php echo $username ?></p>
	<h2>Sales Man</h2>
	<br class="clear">
	<p><select id="salesMen">
		<option value="0">Select ..</option>
		<?php if (!empty($salesMen)): ?>
		<?php foreach ($salesMen as $key => $value): ?>
			<option value="<?php echo $key ?>"><?php echo $value ?></option>
		<?php endforeach; ?>
		<?php endif; ?>
	</select></p>
</div>
<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
	<div id='inline_content' style='padding:10px; background:#fff;'>
		<table>
			<tbody>
				<tr>
					<td>Name:</td>
					<td></td>
					<td><input type="text" name="txtName" id="txtName" placeholder="Ahmed Ali"></td>
				</tr>
				<tr>
					<td>Mobile:</td>
					<td><input style="width:25px;" type="text" value="+20" disabled></td>
					<td><input type="text" name="txtMob" id="txtMob" placeholder="1004878765"></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td></td>
					<td><input type="text" name="txtEmail" id="txtEmail" placeholder="ahmedali@yahoo.com"></td>
				</tr>
				<tr>
					<td colspan="3"><input type="button" id="btnAddCust" name="btnAddCust" value="Add Customer"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="inline_content2">
		<label>Select Customer: <input type="text" name="txtCustMob2" id="txtCustMob2"></label>
		<a href="#inline_content" name="addCust" class="addCust cboxElement">Add +</a>
	</div>
	<div id="inline_content3">
		<div id="payWindow">
			<select id="paymentType">
				<option value="0">Choose One</option>
				<option value="1">Cash</option>
				<option value="2">Visa</option>
				<option value="3">Split</option>
			</select>
			<br><br>
			<div id="splitOptions">
				<label>Cash: <input type="text" name="txtCash" id="txtCash" value="0.00"></label>
				<label>Visa: <input type="text" name="txtCard" id="txtVisa" value="0.00"></label>
			</div>
			<br>
			<input type="button" name="btnPay" id="btnPay" value="Pay (F4)">
		</div>
	</div>
</div>

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
		(message);return false;
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

<script type="text/javascript">
	function reCalc() {
		var subtotal      = 0;
		var totalCost     = 0;
		var totalQty      = 0;
		var rtp           = new Array();
		var qty           = new Array();
		var cost          = new Array();
		var itemTransType = new Array();

		$(".rtp").each(function() {
			rtp.push ($(this).text());
		});

		$(".cost").each(function() {
			cost.push ($(this).text());
		});

		$(".qty").each(function() {
			qty.push ($(this).text());
		});

		$(".itemTransType").each(function() {
			itemTransType.push ($(this).text());
		});

		for(var i = 0; i < rtp.length; i++)
		{
			//console.log(rtp[i] + qty[i]);
			subtotal  += (rtp[i] * qty[i]);
			totalCost += (cost[i] * qty[i]);
			totalQty  += parseInt(qty[i]);
		}

		$(".totalQty").text(totalQty);
		$(".subtotal").text(subtotal.toFixed(2));
		$(".totalCost").text(totalCost.toFixed(2));
		var subtotal  = $(".subtotal").text();
		var cashDisc  = $("#cashDisc").val();

		if (subtotal != 0)
		{
			$(".total").text((subtotal - cashDisc).toFixed(2));
		} else {
			$("#cashDisc").val('0.00');
			$("#percDisc").val('0.00');
			$(".total").text('0.00');
		}
	}
	$("#itemsWrapper").on('dblclick', 'span.rtp', function(){
		var currentPrice = $(this).text();
		$(this).parent().html('<input type="text" name="rtp" class="rtp" style="width:45px;text-align:center;" value="'+currentPrice+'">');
		$('.rtp').focus();
		$('.rtp').select();
	});
	$("#itemsWrapper").on('dblclick', 'input.rtp', function(){
		var currentPrice = $(this).val();
		var currentPrice = $(this).val();
		var currentQty   = $(this).parent().parent().find('.qty').text();
		$(this).parent().parent().find('.totalItem').text(currentQty * currentPrice);
		$(this).parent().html('<span class="rtp">'+currentPrice+'</span>');
		reCalc();
	}).on('keydown', 'input.rtp', function(e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			var currentPrice = $(this).val();
			var currentQty   = $(this).parent().parent().find('.qty').text();
			$(this).parent().parent().find('.totalItem').text(currentQty * currentPrice);
			$(this).parent().html('<span class="rtp">'+currentPrice+'</span>');
			reCalc();
		}
	});
</script>