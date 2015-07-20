<?php require_once("_inc/header2.php"); 

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

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
		width: 5cm;
		height: 3cm;
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
</style>

<style type="text/css" media="print">
	#wrapper {display: none;}
	#printBarCodeArea {
		display: block !important;
		display: inline-block;
		width: 5cm;
		height: 3cm;
		font-size: 12px;
		font-family: arial;
		font-weight: bold;
		padding-right: 3px;
		text-align: center;
		line-height: 18px;
	}
</style>

<?php

	if (!empty($_GET['searchCode']) && !empty($_GET['codeType']) && !empty($_GET['code']))
	{
		$type       = $_GET['codeType'];
		$code       = $_GET['code'];
		$searchData = "";

		$query   = "SELECT items.item_id, items.item_name, items.msrp, items_dept.desc AS dept,
				items_gender.desc AS gender, items_vendor.desc AS vendor, items_season.desc AS season,
				items_attr.desc AS attr
				FROM `items`
				JOIN `items_dept`   on items_dept.dept_id     = items.dept_id
				JOIN `items_gender` on items_gender.gender_id = items.gender_id
				JOIN `items_vendor` on items_vendor.vend_id   = items.vend_id
				JOIN `items_season` on items_season.season_id = items.season_id
				JOIN `items_attr`   on items_attr.attr_id     = items.attr_id";
		
		if ($type == 1) {
			$query .= " WHERE items.item_id = '$code'"; 
		} else {
			$query .= " JOIN `items_intl_code` on items_intl_code.intl_code_id = items.intl_code_id
						WHERE items_intl_code.desc = '$code'"; 
		}

		$query     = $db->query($query);

		if ($db->num_rows($query) > 0) {
			while ($data     = $db->fetch_array($query)) {
				$items[]     = $data['item_id'];
				$itemNames[] = $data['item_name'];
				$msrps[]     = $data['msrp'];
				$depts[]     = $data['dept'];
				$genders[]   = $data['gender'];
				$vendors[]   = $data['vendor'];
				$seasons[]   = $data['season'];
				$attrs[]     = $data['attr'];
			}

			for ($i=0; $i < count($items); $i++) {
				$searchData .= '<a href="#" class="printBarCode">
								<div class="item_barcode">
								<span style="margin-right:1cm;float:left;">'.$depts[$i].'</span>
								<span style="margin-right:1cm;float:left;">'.$vendors[$i].'</span>
								<span style="float:left;">'.$genders[$i].'</span>
								<br style="clear:both">
								<span style="float:left;">'.$itemNames[$i].'</span>
								<span style="float:right;">'.$attrs[$i].'</span>
								<br style="clear:both">
								<span>'.$seasons[$i].'</span>
								<br style="clear:both">
									<div style="text-align:center;margin:5px;">
										<img class="barcode2"/>
										<script type="text/javascript">
											document.ready = function() {
												$(".barcode2").JsBarcode("'.$items[$i].'",{width:1,height:35});
											}
										</script>
									</div>
								<span style="float:left;">MSRP : '.$msrps[$i].'L.E</span>
								<span style="float:right;">#'.$items[$i].'</span>
								</div>
								</a>';
				$itemSizes = userClass::getItemSizes($items[$i]);
				foreach ($itemSizes as $value) {
					$searchData .= '<a href="#" class="printBarCode">
									<div class="item_barcode">
									<span style="margin-right:1cm;float:left;">'.$depts[$i].'</span>
									<span style="margin-right:1cm;float:left;">'.$vendors[$i].'</span>
									<span style="float:left;">'.$genders[$i].'</span>
									<br style="clear:both">
									<span style="float:left;">'.$itemNames[$i].'</span>
									<span style="float:right;">'.$attrs[$i].'</span>
									<br style="clear:both">
									<span>'.$seasons[$i].'</span>
									<br style="clear:both">
										<div style="text-align:center;margin:5px;">
											<img class="barcode2"/>
											<script type="text/javascript">
												document.ready = function() {
													$(".barcode2").JsBarcode("'.$items[$i].'-'.$value['desc'].'",{width:1,height:35});
												}
											</script>
										</div>
									<span style="float:left;">MSRP : '.$msrps[$i].'L.E</span>
									<span style="float:right;">#'.$items[$i].'-'.$value['desc'].'</span>
									</div>
									</a>';
				}			
			}
		}
	}
?>

<h1>Item Barcode Window</h1>

<div class="reportWrapper2">
	<form id="frmSearchCode" name="frmSearchCode" action="" method="get">
		<h3>Search for Items</h3>
		<br>
    	<label>Code Type: <select name="codeType">
	      	<option value="1">Item Code</option>
	    	<option value="2">International Code</option>
    	</select></label>
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