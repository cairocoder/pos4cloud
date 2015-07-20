<?php require_once ("_inc/header2.php"); ?>

<?php
if (!empty($_SESSION['slipNo'])){
	$slipNo = $_SESSION['slipNo'];
} else {
	$slipNo = "";
}
//echo userClass::getItemSizeSlipStock(14721, 285, 25);
?>

<style type="text/css">
	.component {
		width: 100%;
	}
	.red {
		color: red;
		font-weight: bold;
	}
	.black {
		color: black;
		font-weight: bold;
	}
	table {
		width: 100%;
	}
	th {
	    background-color: #0072bc;
	    color: #fff;
	    white-space: nowrap;
	    vertical-align: middle;
	}
	th:nth-child(1),th:nth-child(2){
		min-width: 60px;
	}
	th a {
		color: #fff;
		text-decoration: none;
	}
	tbody th {
		background-color: #0072bc;
	}
	tbody tr:nth-child(2n) {
	    background-color: #8db4e2;
	    transition: all .125s ease-in-out;
	}
	tbody tr:hover {
	    background-color: rgba(129,208,177,.3);
	}
	td {
		text-align: center;
	}
</style>

<h1>Item Size/Stock Window</h1>

<?php
	if (!empty($_GET['itemId']))
	{
		$itemId     = $_GET['itemId'];
		$itemPrices = userClass::getItemPrices($itemId);

		$result   = $db->query("SELECT dept_id FROM items
							    WHERE item_id = '{$itemId}'");
		$row      = $db->fetch_array($result);
		$deptId   = $row['dept_id'];

		$getSizes = $db->query("SELECT `size_id`, `desc` FROM items_size
								WHERE dept_id = '{$deptId}'
								ORDER BY `order`");

		while ($row = $db->fetch_array($getSizes))
		{
			$sizes[] = $row;
		}
		// array_unshift($sizes, array(0 => 1, 'size_id' => 1, 1 => 'One Size', 'desc' => 'One Size'));
		// array_unshift($sizes, array(0 => 0, 'size_id' => 0, 1 => 'No Size', 'desc' => 'No Size'));
		if (!empty($_SESSION['slipBranches'])) {
			$allBranches = $_SESSION['slipBranches'];
		} else {
			$allBranches   = userClass::getAllBranchesShort();
		}
	}
?>
<div class="component">
	<table>
		<tbody>
			<tr>
				<th>ITEM# </th><td><?php echo $itemId ?></td><th>ATTR</th><td><?php echo userClass::getItemAttr($itemId) ?></td><td rowspan="5"><img style="max-width:200px;max-height:200px" src="https://dl.dropboxusercontent.com/u/64785253/Collections/<?php echo $itemId ?>.jpg"></td>
			</tr>
			<tr>
				<th>MSRP </th><td><?php echo $itemPrices['msrp'] ?></td><th>VEND</th><td><?php echo userClass::getItemVend($itemId) ?></td>
			</tr>
			<tr>
				<th>RTP </th><td><?php echo $itemPrices['rtp'] ?></td><th>L.PRC DATE</th><td></td>
			</tr>
			<tr>
				<th>COST </th><td><?php echo $itemPrices['cost'] ?></td><td colspan="2"></td>
			</tr>
			<tr>
				<th>DESC </th><td colspan="3"><?php echo userClass::getItemDesc($itemId) ?></td>
			</tr>
		</tbody>
	</table>
	<br>
	<p>Slip No# 
		<span id="slipNo"><?php echo $slipNo ?></span>
		<input type="button" id="viewSlip" slipNo="<?php echo $slipNo ?>" value="View Details">
		<input type="button" id="changeSlip" value="Change Slip">
		<input type="button" id="exportSlip" slipNo="<?php echo $slipNo ?>" value="Export">
		<input class="color" value="66ff00">
		<input type="button" id="activateSlip" slipNo="<?php echo $slipNo ?>" value="Activate">
	</p>
	<br>
	<table>
		<thead>
			<th>Branch</th>
			<th>Type</th>
			<?php foreach ($sizes as $key => $value): ?>
				<th><?php echo $value['desc'] ?></th>
			<?php endforeach; ?>
				<th>Total</th>
		</thead>
		<tbody>
			<?php foreach ($allBranches as $key => $branch): ?>
			<tr>
				<th rowspan="2"><?php echo $branch ?></th>
				<th>Sales</th>
				<?php $totalSales = 0; ?>
				<?php foreach ($sizes as $key1 => $value1): ?>
					<?php $sales = userClass::getItemSizeSales($itemId, $value1['size_id'], $key); ?>
					<?php $totalSales += $sales; ?>
					<?php if ($sales > 0): ?>
						<td><?php echo $sales; ?></td>
					<?php else: ?>
						<td></td>
					<?php endif ?>
				<?php endforeach; ?>
					<td class="red"><?php echo  $totalSales; ?></td>
			</tr>
			<tr>
				<th>Stock</th>
				<?php $totalStock = 0; ?>
				<?php foreach ($sizes as $key2 => $value2): ?>
					<?php $stock = userClass::getItemSizeStock($itemId, $value2['size_id'], $key); ?>
					<?php $totalStock += $stock; ?>
					<?php
						$itemSizeSlipStock = userClass::getItemSizeSlipStock($itemId, $value2['size_id'], $key);
					?>
					<?php if ($stock > 0): ?>
						<?php if ($itemSizeSlipStock != 0 && $itemSizeSlipStock > $stock): ?>
							<td style="background-color:red"><a href="#" class="itemSizeStockDetail" itemId="<?php echo $itemId ?>" sizeId="<?php echo $value2['size_id'] ?>" locId="<?php echo $key ?>"><?php echo $stock; ?></a></td>
						<?php else: ?>
							<td><a href="#" class="itemSizeStockDetail" itemId="<?php echo $itemId ?>" sizeId="<?php echo $value2['size_id'] ?>" locId="<?php echo $key ?>"><?php echo $stock; ?></a></td>
						<?php endif; ?>
					<?php else: ?>
						<?php if ($itemSizeSlipStock != 0 && $itemSizeSlipStock > $stock): ?>
							<td style="background-color:red"></td>
						<?php else: ?>
							<td></td>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
					<td class="black"><?php echo  $totalStock; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div style="display:none">
	<div id="itemSizeTransfer"></div>
	<div id="selectSlip" style="text-align:center">
		<?php
			$query   = "SELECT DISTINCT(`trans_no`) AS `trans_no` FROM `saved_transfers` WHERE published = 1";
			$result  = $db->query($query);
			$slips   = array();
			if (mysql_num_rows($result) > 0) {
				while ($row = $db->fetch_array($result)) {
					$slips[] = $row['trans_no'];
				}
			}
			$query2  = "SELECT MAX(`trans_no`) AS `trans_no` FROM `saved_transfers` WHERE published = 1";
			$result2 = $db->query($query2);
			$row2    = $db->fetch_array($result2);
			$transNo = $row2['trans_no'] + 1;
		?>
		<p>Select Slip:</p>
		<br>
		<select name="selSlip[]" id="selSlip">
			<option value="<?php echo $transNo ?>" selected>New</option>
			<?php if(!empty($slips)): ?>
			<?php foreach ($slips as $value): ?>
			<option value="<?php echo $value ?>"><?php echo $value ?></option>
			<?php endforeach; ?>
			<?php endif; ?>
		</select>&nbsp<input type="button" id="getSlip" value="Select">
	</div>
	<div id="slipDetails" style="text-align:center"></div>
</div>

<?php

if (isset($pagingData)) echo $pagingData;
require_once ("_inc/footer.php");

?>

<script src="_js/jquery.ba-throttle-debounce.min.js"></script>
<script src="_js/jquery.stickyheader2.js"></script>
<script src="_js/jscolor.js"></script>
<script src="_js/jquery.floatThead.min.js"></script>
<?php if (empty($_SESSION['slipNo'])): ?>
<script>
	$.colorbox({
		href:"#selectSlip", inline:true, overlayClose:false, escKey:false, fixed:true, width:"250px",
		onComplete:function(){ $("#selSlip").focus(); },
		onLoad:function(){ $("#cboxClose").remove() }
	});
</script>
<?php endif; ?>
<script>
	$('td').click(function(){
		$(this).css("background-color", "#"+$('.color').val());
	});
</script>