<?php
	require_once ("_inc/header.php");

	$username   = $_SESSION['username'];
	$locId      = $_SESSION['loc_id'];
	$branchName = userClass::getBranchName($locId);
	$salesMen   = userClass::getSalesMen($locId);

	if (isset($_SESSION['date']))
	{
		$currentDate = $_SESSION['date'];
	} else {
		$currentDate = date('Y-m-d');
	}
	
	$total 		  = "";
	$invoNo       = "";
	$totalQty     = "";
	$invoDate     = $currentDate;
	$subtotal     = "";
	$totalCost    = "";
	$discount     = "";
	$comments     = "";
	$salesManId   = "";
	$customerId   = "";
	$customerName = "";
	$salesManName = "";

	if(!empty($_GET['invoNo']))
	{
		$invoNo = $_GET['invoNo'];

		$getInvoHeader  = $db->query("SELECT * FROM invoice_header
									  WHERE invo_no   = '{$invoNo}'
									  AND   loc_id    = '{$locId}'");

		if (mysql_num_rows($getInvoHeader) > 0)
		{
			$rowInvoHeader  = $db->fetch_array($getInvoHeader);
			$invoDate       = $rowInvoHeader['date'];
			$comments       = $rowInvoHeader['comments'];
			$customerId     = $rowInvoHeader['cust_id'];
			$salesManId     = $rowInvoHeader['sales_man_id'];
			$customerName   = userClass::getCustomerName($customerId);
			$salesManName   = userClass::getSalesMan($salesManId);
			$totalQty       = $rowInvoHeader['qty'];
			$subtotal       = $rowInvoHeader['total_amount'];
			$totalCost      = "";
			$discount       = $rowInvoHeader['discount_amount'];
			$total 			= $rowInvoHeader['net_value'];
			$date           = date('Y-m-d');

			$chkDate  = date('Y-m-d', strtotime($date. ' - 15 days'));

			if ($invoDate < $chkDate) {
				echo '<script>document.location.href = "exchangeBox.php"</script>';
				die();
			}

			$getInvoDetails = $db->query("SELECT invoice_detail.item_id, invoice_detail.size_id, invoice_detail.qty,
										  invoice_detail.rtp, inventory_header.total_cost, inventory_detail.cost
										  FROM  invoice_detail
										  JOIN  inventory_header ON inventory_header.invo_no  = invoice_detail.invo_no
										  AND   inventory_header.wrhs_id = invoice_detail.loc_id
										  JOIN  inventory_detail ON inventory_header.trans_no = inventory_detail.trans_no
										  AND   inventory_header.wrhs_id = inventory_detail.wrhs_id
										  AND   inventory_detail.item_id = invoice_detail.item_id
									  	  AND   inventory_detail.serial  = invoice_detail.serial
										  WHERE invoice_detail.invo_no   = '{$invoNo}'
										  AND   invoice_detail.loc_id    = '{$locId}'");
		}
	}
?>

<h1>Exchange Window</h1>
<div id="container">
	<input type="hidden" id="processType" value="exchange">
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
	<div id="selQtyEx">
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
				<?php if($_SESSION['user_type'] == "sadmin"): ?>
				<th>Cost</th>
				<?php endif; ?>
				<th>Qty.</th>
				<th>Total</th>
				<th>Image</th>
				<th></th>
			</tr>
			<?php if (!empty($getInvoDetails)): ?>
			<?php while ($rowInvoDetails = $db->fetch_array($getInvoDetails)): ?>
			<tr style="background: lightpink;">
				<td><span class="itemId"><?php echo $rowInvoDetails['item_id'] ?></span></td>
				<td><?php echo userClass::getItemName($rowInvoDetails['item_id']) ?></td>
				<td><?php echo userClass::getItemDesc($rowInvoDetails['item_id']) ?></td>
				<td><?php echo userClass::getSizeDesc($rowInvoDetails['size_id']) ?></td>
				<td class="sizeId"><?php echo $rowInvoDetails['size_id'] ?></td>
				<td></td>
				<td><span class="rtp"><?php echo $rowInvoDetails['rtp'] * (-1) ?></span></td>
				<td><span class="cost"><?php echo $rowInvoDetails['cost'] * (-1) ?></span></td>
				<td><span class="qty"><?php echo $rowInvoDetails['qty'] ?></span></td>
				<td><span class="totalItem"><?php echo $rowInvoDetails['rtp'] * $rowInvoDetails['qty'] * (-1) ?></span></td>
				<td style="display:none;"><span class="itemTransType">2</span></td>
				<td><a href="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $rowInvoDetails['item_id'] ?>.jpg" class="group1 cboxElement"><img width="80" src="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $rowInvoDetails['item_id'] ?>.jpg"></a></td>
				<td><a href="#" id="removeItem"><img src="_img/remove.png"></a></td>
			</tr>
			<?php $totalCost = $rowInvoDetails['total_cost']; ?>
			<?php endwhile; ?>
			<?php endif; ?>
		</table>
	</div>
	<div id="invFooter">
		<?php if (!empty($getInvoDetails)): ?>
		<div id="getCustomer">
			<label>Customer:</label>
			<span class="customerName"><?php echo $customerName ?></span>
			<span class="customerId none"><?php echo $customerId ?></span>
		</div>
		<div id="priceCalc">
			<table id="priceTable">
				<tr>
					<th>Total Qty.</th><td></td><td><span class="totalQty"><?php echo $totalQty ?></span></td>
				</tr>
				<tr>
					<th>Subtotal</th><td></td><td><span class="subtotal"><?php echo $total * (-1) ?></span></td>
				</tr>
				<?php if($_SESSION['user_type'] == "sadmin"): ?>
				<tr>
					<th>Total Cost</th><td></td><td><span class="totalCost"><?php echo $totalCost * (-1) ?></span></td>
				</tr>
				<?php else: ?>
				<tr style="display:none">
					<th>Total Cost</th><td></td><td><span class="totalCost"><?php echo $totalCost * (-1) ?></span></td>
				</tr>
				<?php endif; ?>
				<?php if($_SESSION['user_type'] == "admin" || $_SESSION['user_type'] == "sadmin"): ?>
				<tr>
					<th>Discount</th><td>% <input type="text" id="percDisc" name="percDisc" value="<?php echo ($discount / $subtotal) * 100 ?>"></td><td>$ <input type="text" id="cashDisc" name="cashDisc" value="<?php echo $discount ?>"></td>
				</tr>
				<?php else: ?>
				<tr style="display:none;">
					<th>Discount</th><td>% <input type="text" id="percDisc" name="percDisc" value="<?php echo ($discount / $subtotal) * 100 ?>"></td><td>$ <input type="text" id="cashDisc" name="cashDisc" value="<?php echo $discount ?>"></td>
				</tr>
				<?php endif; ?>
				<tr>
					<th>Total</th><td></td><td><span class="total"><?php echo $total * (-1) ?></span></td>
				</tr>
				<tr>
					<th>Comments</th><td colspan="2"><textarea id="comments" cols="35" rows="3"><?php echo $comments ?></textarea></td>
				</tr>
				<!--<tr>
					<th>Pay/Remain</th><td>$ <input type="text" id="payCash" name="payCash" value="0.00"></td><td><span class="remainCash">0.00</span></td>
				</tr>-->
				<tr>
					<td colspan="3"><input type="button" name="btnProceedE" id="btnProceedE" value="Proceed (F3)"></td>
				</tr>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>
<div id="sidebar">
	<h2>Branch</h2>
	<br class="clear">
	<p><?php echo $branchName ?></p>
	<h2>Invoice#</h2>
	<br class="clear">
	<p class="invoNo"><?php echo $invoNo ?></p>
	<h2>Invoice Date</h2>
	<br class="clear">
	<p><?php echo $invoDate ?></p>
	<h2>Current Date</h2>
	<br class="clear">
	<p class="currentDate"><?php echo $currentDate ?></p>
	<h2>Sales Man</h2>
	<br class="clear">
	<p><select id="salesMen">
		<option value="0">Select ..</option>
		<?php if (!empty($salesMen)): ?>
		<?php foreach ($salesMen as $key => $value): ?>
			<?php if($key == $salesManId): ?>
			<option value="<?php echo $key ?>" selected><?php echo $value ?></option>
			<?php else: ?>
			<option value="<?php echo $key ?>"><?php echo $value ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php endif; ?>
	</select><span style="display:none;" class="salesMan"></span></p>
	<h2>Cashier</h2>
	<br class="clear">
	<p><?php echo $username ?></p>
</div>
<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
	<div id="select_invoice">
		<label>Select Invoice: <input type="text" name="txtInvoiceNo3" id="txtInvoiceNo3"></label>
	</div>
	<div id="inline_content3">
		<div id="exchangeWindow">
			<select id="paymentType">
				<option value="0">Choose One</option>
				<option value="1">Cash</option>
				<option value="2">Visa</option>
			</select><br><br>
			<input type="button" name="btnExchange" id="btnExchange" value="Exchange (F4)">
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
<?php
	if(empty($_GET['invoNo']))
	{
		echo '<script>';
		echo '$.colorbox({
			  	href:"#select_invoice", inline:true, overlayClose:false, escKey:false,
			  	width:"520px", onComplete:function(){ $("#txtInvoiceNo3").focus() },
			  	onClosed:function(){ $("#txtItems").focus() },
			  	onLoad:function(){ $("#cboxClose").remove() }
			  });';
		echo "</script>";
	}
?>