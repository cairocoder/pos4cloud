<?php
	require_once ("_inc/header.php");

	$username   = $_SESSION['username'];
	$locId      = $_SESSION['loc_id'];

	if(!empty($_GET['invoNo']))
	{
		$invoNo = $_GET['invoNo'];

		$getInvoHeader  = $db->query("SELECT * FROM invoice_header
									  WHERE invo_no = '{$invoNo}'
									  AND loc_id    = '{$locId}'");

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
			echo '<script>document.location.href = "returnBox.php"</script>';
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

	} else {
		$total 		  = "";
		$invoNo       = "";
		$totalQty     = "";
		$invoDate     = "";
		$subtotal     = "";
		$totalCost    = "";
		$discount     = "";
		$comments     = "";
		$salesManId   = "";
		$customerId   = "";
		$customerName = "";
		$salesManName = "";
	}

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
	<input type="hidden" id="processType" value="return">
	<div id="itemsWrapper">
		<table id="tableItems">
			<tr>
				<th class="thItemId">Item#</th>
				<th>Item Name</th>
				<th>Description</th>
				<th>Size</th>
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
				<td><span class="cost"><?php echo $rowInvoDetails['cost'] * (-1) ?></span></td>
				<td><span class="qty"><?php echo $rowInvoDetails['qty'] ?></span></td>
				<td><span class="totalItem"><?php echo $rowInvoDetails['rtp'] * $rowInvoDetails['qty'] * (-1) ?></span></td>
				<td><a href="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $rowInvoDetails['item_id'] ?>.jpg" class="group1 cboxElement"><img width="80" src="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $rowInvoDetails['item_id'] ?>.jpg"></a></td>
				<td><a href="#" id="removeItem"><img src="_img/remove.png"></a></td>
			</tr>
			<?php $totalCost = $rowInvoDetails['total_cost']; ?>
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
	<p><span><?php echo $salesManName ?></span><span style="display:none;" class="salesMan"><?php echo $salesManId ?></span></p>
	<h2>Cashier</h2>
	<br class="clear">
	<p><?php echo $username ?></p>
</div>
<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
	<div id="select_invoice">
		<label>Select Invoice: <input type="text" name="txtInvoiceNo" id="txtInvoiceNo"></label>
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

<?php require_once ("_inc/footer.php"); ?>
<?php
	if(empty($_GET['invoNo']))
	{
		echo '<script>';
		echo '$.colorbox({
			  	href:"#select_invoice", inline:true, overlayClose:false, escKey:false,
			  	width:"520px", onComplete:function(){ $("#txtInvoiceNo").focus(); },
			  	onClosed:function(){ $("#txtItems").focus(); },
			  	onLoad:function(){ $("#cboxClose").remove() }
			  });';
		echo "</script>";
	}
?>