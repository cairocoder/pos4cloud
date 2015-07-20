<?php
	require_once ("_inc/header.php");

	$username   = $_SESSION['username'];
	$locId      = $_SESSION['loc_id'];
	$salesMen   = userClass::getSalesMen($locId);

	$total 		  = "";
	$invoNo       = 0;
	$totalQty     = "";
	$invoDate     = $currentDate = date('Y-m-d');
	$subtotal     = "";
	$discount     = "";
	$comments     = "";
	$salesManId   = "";
	$customerId   = "";
	$customerName = "";
	$salesManName = "";

	$branchName = userClass::getBranchName($locId);

	if (isset($_SESSION['date']))
	{
		$currentDate = $_SESSION['date'];
	} else {
		$currentDate = date('Y-m-d');
	}
?>

<h1>Return Window</h1>
<div id="container">
	<input type="hidden" id="processType" value="return2">
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
				<?php if($_SESSION['user_type'] == "sadmin"): ?>
				<th>Cost</th>
				<?php endif; ?>
				<th>Qty.</th>
				<th>Total</th>
				<th>Image</th>
				<th></th>
			</tr>
			<?php if(!empty($getInvoDetails)): ?>
			<?php while ($rowInvoDetails = $db->fetch_array($getInvoDetails)): ?>
			<tr style="background: lightpink;" class="dropItems">
				<td><span class="itemId"><?php echo $rowInvoDetails['item_id'] ?></span></td>
				<td><?php echo userClass::getItemName($rowInvoDetails['item_id']) ?></td>
				<td><?php echo userClass::getItemDesc($rowInvoDetails['item_id']) ?></td>
				<td><?php echo userClass::getSizeDesc($rowInvoDetails['size_id']) ?></td>
				<td class="sizeId"><?php echo $rowInvoDetails['size_id'] ?></td>
				<td><span class="rtp"><?php echo $rowInvoDetails['rtp'] * (-1) ?></span></td>
				<td><span class="qty"><?php echo $rowInvoDetails['qty'] ?></span></td>
				<td><span class="totalItem"><?php echo $rowInvoDetails['rtp'] * $rowInvoDetails['qty'] * (-1) ?></span></td>
				<td><a href="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $rowInvoDetails['item_id'] ?>.jpg" class="group1 cboxElement"><img width="80" src="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $rowInvoDetails['item_id'] ?>.jpg"></a></td>
				<td><a href="#" id="removeItem"><img src="_img/remove.png"></a></td>
			</tr>
			<?php endwhile; ?>
			<?php endif; ?>
		</table>
	</div>
	<div id="invFooter">
		<?php if(!empty($getInvoDetails)): ?>
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
					<th>Subtotal</th><td></td><td><span class="subtotal"><?php echo $subtotal * (-1) ?></span></td>
				</tr>
				<?php if($_SESSION['user_type'] == "sadmin"): ?>
				<tr>
					<th>Total Cost</th><td></td><td><span class="totalCost">0.00</span></td>
				</tr>
				<?php else: ?>
				<tr style="display:none">
					<th>Total Cost</th><td></td><td><span class="totalCost">0.00</span></td>
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
					<td colspan="3"><input type="button" name="btnProceed" id="btnProceed" value="Proceed (F3)"></td>
				</tr>
			</table>
		</div>
		<?php else: ?>
		<div id="getCustomer">
			<p style="padding: 0 0 10px 0;">Select Customer:</p>
			<input type="text" name="txtCustMob" id="txtCustMob">
			<a class="addCust" name="addCust" href="#inline_content">Add +</a>
			<span class="customerName">General Customer</span>
			<span class="customerId none">1</span>
		</div>
		<div id="priceCalc">
			<table id="priceTable">
				<tr>
					<th>Total Qty.</th><td></td><td><span class="totalQty">0</span></td>
				</tr>
				<tr>
					<th>Subtotal</th><td></td><td><span class="subtotal">0.00</span></td>
				</tr>
				<?php if($_SESSION['user_type'] == "sadmin"): ?>
				<tr>
					<th>Total Cost</th><td></td><td><span class="totalCost">0.00</span></td>
				</tr>
				<?php else: ?>
				<tr style="display:none">
					<th>Total Cost</th><td></td><td><span class="totalCost">0.00</span></td>
				</tr>
				<?php endif; ?>
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
					<th>Total</th><td></td><td><span class="total">0.00</span></td>
				</tr>
				<tr>
					<th>Comments</th><td colspan="2"><textarea id="comments" cols="35" rows="3"></textarea></td>
				</tr>
				<tr>
					<td colspan="3"><input type="button" name="btnProceed" id="btnProceed" value="Proceed (F3)"></td>
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
		<label>Select Invoice: <input type="text" name="txtInvoiceNo2" id="txtInvoiceNo2"></label>
	</div>
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
		<div id="returnWindow">
			<select id="paymentType">
				<option value="0">Choose One</option>
				<option value="1">Cash</option>
				<option value="2">Visa</option>
			</select><br><br>
			<input type="button" name="btnReturn" id="btnReturn" value="Return (F4)">
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

<script type="text/javascript">$("#txtItems").focus()</script>