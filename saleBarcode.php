<?php require_once("_inc/header2.php"); 

	// if ($_SESSION['user_type'] != "sadmin") {
	// 	echo "<script>window.location.href = 'index.php'</script>";
	// }

?>

<style>
	#frmSearchCode {
		width: 300px;
		padding: 10px;
		margin: 0 auto;
		text-align: center;
	}
	.item_barcode {
		display: inline-block;
		margin: 10px;
		border: 1px dotted #000;
		width: 3.9cm;
		height: 2.9cm;
		position: relative;
		font-size: 12px;
		font-family: arial;
		font-weight: bold;
		padding-right: 3px;
		text-align: center;
	}
	#items {
		text-align: center;
		padding: 20px;
	}
	#printBarCodeArea {
		display: none;
	}
	.printBarCode {
		text-decoration: none;
	}
	.msrp, .rtp {
		font-weight: bold;
		position: absolute;
	}
	.msrp {
		font-size: 20px;
		top: 25px;
		left: 65px;
		text-decoration: line-through;
	}
	.rtp {
		font-size: 30px;
		top: 60px;
		left: 55px;
	}
	.sale {
		-webkit-transform: rotate(-90deg);
		-moz-transform: rotate(-90deg);
		-ms-transform: rotate(-90deg);
		-o-transform: rotate(-90deg);
		left: -20px;
		margin-top: -5px;
		position: absolute;
		text-align: center;
		font-size: 40px;
		font-family: arial;
		font-weight: bold;
		top: 50%;
		width: 78px;
		z-index: 9999;
	}
</style>

<style type="text/css" media="print">
	#wrapper {display: none;}
	#printBarCodeArea {
		display: block !important;
		display: inline-block;
		width: 3.9cm;
		height: 2.9cm;
		position: relative;
		font-size: 12px;
		font-family: arial;
		font-weight: bold;
		padding-right: 3px;
		text-align: center;
		line-height: 18px;
	}
	.msrp, .rtp {
		font-weight: bold;
		position: absolute;
	}
	.msrp {
		font-size: 20px;
		top: 5px;
		left: 65px;
		text-decoration: line-through;
	}
	.rtp {
		font-size: 30px;
		top: 40px;
		left: 55px;
	}
	.sale {
		-webkit-transform: rotate(-90deg);
		-moz-transform: rotate(-90deg);
		-ms-transform: rotate(-90deg);
		-o-transform: rotate(-90deg);
		left: -35px;
    	margin-top: -8px;
		position: absolute;
		text-align: center;
		font-size: 40px;
		font-family: arial;
		font-weight: bold;
		top: 50%;
		width: 105px;
		z-index: 9999;
	}
</style>

<?php

	if (!empty($_GET['searchCode']) && !empty($_GET['code']))
	{
		$code       = $_GET['code'];
		$searchData = "";

		$query   = "SELECT msrp, rtp
					FROM `items`
					WHERE item_id = '$code'";

		$result  = $db->query($query);

		if ($db->num_rows($result) > 0) {
			$row = $db->fetch_array($result);
			$searchData .= '<a href="#" class="printBarCode">
								<div class="item_barcode">
									<div class="sale">SALE</div>
									<p class="msrp">'.$row['msrp'].'</p>
									<p class="rtp">'.$row['rtp'].'</p>
								</div>
							</a>';	
		}
	}
?>

<h1>Sale Barcode Window</h1>

<div class="reportWrapper2">
	<form id="frmSearchCode" name="frmSearchCode" action="" method="get">
		<h3>Search for Items</h3>
    	<br><br>
	    <label>Enter Code: <input type="text" name="code" ></label>
		<br><br>
		<input type="submit" id="searchCode" name="searchCode" value="Search">
	</form>
	<div id="items">
		<?php if (!empty($searchData)) echo $searchData; ?>
		<br style="clear:both;">
	</div>
</div>

<?php require_once("_inc/footer.php"); ?>
<script src="_js/EAN_UPC.js"></script>
<script src="_js/CODE128.js"></script>
<script src="_js/JsBarcode.js"></script>
<!-- <input class="barcode_print btn btn-primary" type="button"  value="print" name="print"> -->
<script type="text/javascript">
	//print function
	$(".printBarCode").click(function () {
		var barCodeData = $(this).find(".item_barcode").html();
		$("#printBarCodeArea").html(barCodeData);
		window.print();
	});
</script>
<div id="printBarCodeArea"></div>