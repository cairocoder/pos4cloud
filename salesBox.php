<?php
	require_once ("_inc/header.php");

	$username   = $_SESSION['username'];
	$locId      = $_SESSION['loc_id'];

	$salesMen   = userClass::getSalesMen($locId);
	$branchName = userClass::getBranchName($locId);

	if (isset($_SESSION['date']))
	{
		$currentDate = $_SESSION['date'];
	} else {
		$currentDate = date('Y-m-d');
	}
?>

<?php
	if ($_SESSION['user_type'] != "sadmin" && $_SESSION['user_type'] != "sales" && $_SESSION['user_type'] != "stockadmin" && $_SESSION['user_type'] != "stocksadmin"){
		echo "<script>window.location.href = 'index.php'</script>";
	}
?>

<h1>Sales Window</h1>
<div id="container">
	<input type="hidden" id="processType" value="sales">
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
		</table>
	</div>
	<div id="invFooter">
		<div id="getCustomer">
			<p style="padding: 0 0 10px 0;">Select Customer:</p>
			<input type="text" name="txtCustMob" id="txtCustMob">
			<a class="addCust" name="addCust" href="#inline_content">Add</a> / 
			<a href="#editCustomer" name="editCust" class="editCust">Edit</a>
			<span class="customerName"></span>
			<span class="customerId none"></span>
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
					<th>Voucher</th><td><input type="password" id="voucherCode" name="voucherCode" value="" data-voucherId=""></td><td><input type="button" id="chkVoucher" name="chkVoucher" value="Use Voucher"></td>
				</tr>
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
	<div id='editCustomer' style='padding:10px; background:#fff;'>
		<table>
			<tbody>
				<tr style="display:none;">
					<td>Id:</td>
					<td></td>
					<td><input type="text" name="etxtId" id="etxtId" placeholder="Id"></td>
				</tr>
				<tr>
					<td>Name:</td>
					<td></td>
					<td><input type="text" name="etxtName" id="etxtName" placeholder="Ahmed Ali"></td>
				</tr>
				<tr style="display:none;">
					<td>Mobile:</td>
					<td><input style="width:25px;" type="text" value="+20" disabled></td>
					<td><input type="text" name="etxtMob" id="etxtMob" placeholder="1004878765"></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td></td>
					<td><input type="text" name="etxtEmail" id="etxtEmail" placeholder="ahmedali@yahoo.com"></td>
				</tr>
				<tr>
					<td colspan="3"><input type="button" id="btnEditCust" name="btnEditCust" value="Edit Customer"></td>
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