<?php require_once ("_inc/header.php"); ?>

<h1>Stock Report Window</h1>

<?php
$locId       = $_SESSION['loc_id'];
$allDepts    = userClass::getAllDepts();
$allAttrs    = userClass::getAllAttrs();
$allVends    = userClass::getAllVends();
$allSeasons  = userClass::getAllSeasons();
$allGenders  = userClass::getAllGenders();
$allSubDepts = userClass::getAllSubDepts();
$allStkLvls  = array('1' => 'Between 1 and 15',
					 '2' => '16 - 60',
					 '3' => '61 - 180',
					 '4' => '< 0',
					 '5' => '> 180',
					 '6' => '0',
					 '7' => 'Between 1 and 40');

if (isset($_GET['btnSubmit']))
{
	$query  = "SELECT warehouses.wrhs_id, warehouses.item_id, warehouses.size_id, warehouses.qty,
			   items.item_id, items.dept_id
			   FROM warehouses
			   INNER JOIN items
			   ON warehouses.item_id = items.item_id";

	$query2 = "SELECT warehouses.wrhs_id, warehouses.item_id, items.item_id, items.dept_id
			   FROM warehouses
			   INNER JOIN items
			   ON warehouses.item_id = items.item_id";

	$query3 = "SELECT warehouses.wrhs_id, warehouses.item_id, warehouses.size_id, SUM(warehouses.qty) AS sumQty,
			   items.item_id, items.dept_id
			   FROM warehouses
			   INNER JOIN items
			   ON warehouses.item_id = items.item_id";

	$condition = "WHERE";

	if (!empty($_GET['intlCode']))
	{
		$intlCode = $_GET['intlCode'];
		$chkIntlCode = $db->query("SELECT `intl_code_id` FROM `items_intl_code` WHERE `desc` = '{$intlCode}'");
		if (mysql_num_rows($chkIntlCode) > 0) {
			$rowChkIntlCode = $db->fetch_array($chkIntlCode);
			$query  .= " ".$condition." items.intl_code_id = '".$rowChkIntlCode['intl_code_id']."'";
			$query2 .= " ".$condition." items.intl_code_id = '".$rowChkIntlCode['intl_code_id']."'";
			$query3 .= " ".$condition." items.intl_code_id = '".$rowChkIntlCode['intl_code_id']."'";	
		} else {
			$query  .= " ".$condition." items.intl_code_id = NULL";
			$query2 .= " ".$condition." items.intl_code_id = NULL";
			$query3 .= " ".$condition." items.intl_code_id = NULL";
		}
		$condition = "AND";
	}

	if (!empty($_GET['itemId']))
	{
		$query  .= " ".$condition." warehouses.item_id = '".$_GET['itemId']."'";
		$query2 .= " ".$condition." warehouses.item_id = '".$_GET['itemId']."'";
		$query3 .= " ".$condition." warehouses.item_id = '".$_GET['itemId']."'";
		$condition = "AND";
	}

	if ($_GET['selBranch'] == 1)
	{
		$query  .= " ".$condition." wrhs_id = '{$locId}'";
		$query2 .= " ".$condition." wrhs_id = '{$locId}'";
		$query3 .= " ".$condition." wrhs_id = '{$locId}'";
		$condition = "AND";
	}

	if (array_search("All", $_GET['selAttr']) === false)
	{
		$getAllAttrs = implode(",", $_GET['selAttr']);
		$query  .= " ".$condition." items.attr_id in ({$getAllAttrs})";
		$query2 .= " ".$condition." items.attr_id in ({$getAllAttrs})";
		$query3 .= " ".$condition." items.attr_id in ({$getAllAttrs})";
		$condition = "AND";
	}

	if (array_search("All", $_GET['selGend']) === false)
	{
		$getAllGenders = implode(",", $_GET['selGend']);
		$query  .= " ".$condition." items.gender_id in ({$getAllGenders})";
		$query2 .= " ".$condition." items.gender_id in ({$getAllGenders})";
		$query3 .= " ".$condition." items.gender_id in ({$getAllGenders})";
		$condition = "AND";
	}

	if (array_search("All", $_GET['selVend']) === false)
	{
		$getAllVends = implode(",", $_GET['selVend']);
		$query  .= " ".$condition." items.vend_id in ({$getAllVends})";
		$query2 .= " ".$condition." items.vend_id in ({$getAllVends})";
		$query3 .= " ".$condition." items.vend_id in ({$getAllVends})";
		$condition = "AND";
	}

	if (array_search("All", $_GET['selSeason']) === false)
	{
		$getAllSeasons = implode(",", $_GET['selSeason']);
		$query  .= " ".$condition." items.season_id in ({$getAllSeasons})";
		$query2 .= " ".$condition." items.season_id in ({$getAllSeasons})";
		$query3 .= " ".$condition." items.season_id in ({$getAllSeasons})";
		$condition = "AND";
	}

	if (array_search("All", $_GET['selDept']) === false)
	{
		$getAllDepts = implode(",", $_GET['selDept']);
		$query  .= " ".$condition." items.dept_id in ({$getAllDepts})";
		$query2 .= " ".$condition." items.dept_id in ({$getAllDepts})";
		$query3 .= " ".$condition." items.dept_id in ({$getAllDepts})";
		$condition = "AND";
	}

	if (array_search("All", $_GET['selSubDept']) === false)
    {
        $getAllSubDepts = implode(",", $_GET['selSubDept']);
		$query  .= " ".$condition." items.sub_dept_id in ({$getAllSubDepts})";
		$query2 .= " ".$condition." items.sub_dept_id in ({$getAllSubDepts})";
		$query3 .= " ".$condition." items.sub_dept_id in ({$getAllSubDepts})";
		$condition = "AND";
    }

	if (array_search("All", $_GET['selSize']) === false)
	{
		$getAllSizes = implode(",", $_GET['selSize']);
		$query  .= " ".$condition." warehouses.size_id in ({$getAllSizes})";
		$query2 .= " ".$condition." warehouses.size_id in ({$getAllSizes})";
		$query3 .= " ".$condition." warehouses.size_id in ({$getAllSizes})";
		$condition = "AND";
	}

	if (array_search("All", $_GET['selStkLvl']) === false)
	{
		foreach ($_GET['selStkLvl'] as $key => $value) {
			switch ($value)
			{
				case 1:
					if ($_GET['selStkLvl'][0] == 1)
					{
						$query  .= " ".$condition." (warehouses.qty BETWEEN 1 AND 15";
						$query2 .= " ".$condition." (warehouses.qty BETWEEN 1 AND 15";
						$query3 .= " ".$condition." (warehouses.qty BETWEEN 1 AND 15";
					} else {
						$query  .= " OR warehouses.qty BETWEEN 1 AND 15";
						$query2 .= " OR warehouses.qty BETWEEN 1 AND 15";
						$query3 .= " OR warehouses.qty BETWEEN 1 AND 15";
					}
					break;

				case 2:				
					if ($_GET['selStkLvl'][0] == 2)
					{
						$query  .= " ".$condition." (warehouses.qty BETWEEN 16 AND 60";
						$query2 .= " ".$condition." (warehouses.qty BETWEEN 16 AND 60";
						$query3 .= " ".$condition." (warehouses.qty BETWEEN 16 AND 60";
					} else {
						$query  .= " OR warehouses.qty BETWEEN 16 AND 60";
						$query2 .= " OR warehouses.qty BETWEEN 16 AND 60";
						$query3 .= " OR warehouses.qty BETWEEN 16 AND 60";
					}
					break;

				case 3:
					if ($_GET['selStkLvl'][0] == 3)
					{
						$query  .= " ".$condition." (warehouses.qty BETWEEN 61 AND 180";
						$query2 .= " ".$condition." (warehouses.qty BETWEEN 61 AND 180";
						$query3 .= " ".$condition." (warehouses.qty BETWEEN 61 AND 180";
					} else {
						$query  .= " OR warehouses.qty BETWEEN 61 AND 180";
						$query2 .= " OR warehouses.qty BETWEEN 61 AND 180";
						$query3 .= " OR warehouses.qty BETWEEN 61 AND 180";
					}
					break;

				case 4:
					if ($_GET['selStkLvl'][0] == 4)
					{
						$query  .= " ".$condition." (warehouses.qty < 0";
						$query2 .= " ".$condition." (warehouses.qty < 0";
						$query3 .= " ".$condition." (warehouses.qty < 0";
					} else {
						$query  .= " OR warehouses.qty < 0";
						$query2 .= " OR warehouses.qty < 0";
						$query3 .= " OR warehouses.qty < 0";
					}
					break;

				case 5:
					if ($_GET['selStkLvl'][0] == 5)
					{
						$query  .= " ".$condition." (warehouses.qty > 180";
						$query2 .= " ".$condition." (warehouses.qty > 180";
						$query3 .= " ".$condition." (warehouses.qty > 180";
					} else {
						$query  .= " OR warehouses.qty > 180";
						$query2 .= " OR warehouses.qty > 180";
						$query3 .= " OR warehouses.qty > 180";
					}
					break;

				case 6:
					if ($_GET['selStkLvl'][0] == 6)
					{
						$query  .= " ".$condition." (warehouses.qty = 0";
						$query2 .= " ".$condition." (warehouses.qty = 0";
						$query3 .= " ".$condition." (warehouses.qty = 0";
					} else {
						$query  .= " OR warehouses.qty = 0";
						$query2 .= " OR warehouses.qty = 0";
						$query3 .= " OR warehouses.qty = 0";
					}
					break;
					
				case 7:
					if ($_GET['selStkLvl'][0] == 7)
					{
						$query  .= " ".$condition." (warehouses.qty BETWEEN 1 AND 40";
						$query2 .= " ".$condition." (warehouses.qty BETWEEN 1 AND 40";
						$query3 .= " ".$condition." (warehouses.qty BETWEEN 1 AND 40";
					} else {
						$query  .= " OR warehouses.qty BETWEEN 1 AND 40";
						$query2 .= " OR warehouses.qty BETWEEN 1 AND 40";
						$query3 .= " OR warehouses.qty BETWEEN 1 AND 40";
					}
					break;
			}
		}
		$query  .= ")";
		$query2 .= ")";
		$query3 .= ")";
		$condition = "AND";
	}

	if (!empty($_GET['priceFrom']) && !empty($_GET['priceTo']))
	{
		$query  .= " ".$condition." items.rtp BETWEEN '".$_GET['priceFrom']."' AND '".$_GET['priceTo']."'";
		$query2 .= " ".$condition." items.rtp BETWEEN '".$_GET['priceFrom']."' AND '".$_GET['priceTo']."'";
		$query3 .= " ".$condition." items.rtp BETWEEN '".$_GET['priceFrom']."' AND '".$_GET['priceTo']."'";
		$condition = "AND";
	}

	$query2     .= ' GROUP BY warehouses.item_id';

	if (isset($_GET['page']))
	{
	   $page = $_GET['page'];
	} else {
	   $page = 1;
	}

	if ($_GET['selReportType'] == 1) {
		$getStockTotal1 = $db->query($query);
		$numRows     = mysql_num_rows($getStockTotal1);
		$rowsPerPage = 50;
		$lastPage    = ceil($numRows/$rowsPerPage);
	} else {
		$getStockTotal2 = $db->query($query2);
		$numRows     = mysql_num_rows($getStockTotal2);
		$rowsPerPage = 50;
		$lastPage    = ceil($numRows/$rowsPerPage);
	}

	$page = (int)$page;

	if ($page > $lastPage) {
	   $page  = $lastPage;
	}
	if ($page < 1) {
	   $page  	  = 1;
	   $lastPage  = 1;
	}
	
	$limit   = ' LIMIT ' .($page - 1) * $rowsPerPage .',' .$rowsPerPage;
	$query  .= $limit;
	$query2 .= $limit;

	if ($_GET['selReportType'] == 1) {
		$getStock   = $db->query($query);
		$getStock3  = $db->query($query3);
		$rowStock3  = $db->fetch_array($getStock3);
		$dataTable  = '<div id="reportWrapper2">';
		$dataTable .= '<table id="tblStockReport">';
		$dataTable .= '<tr><th>Item#</th><th>Description</th><th>Size</th><th>Qty.</th><th>Branch</th></tr>';
		$totalQty   = 0;
		while ($rowStock = $db->fetch_array($getStock))
		{
			$dataTable .= '<tr><td>'.$rowStock['item_id'].'</td>';
			$dataTable .= '<td>'.userClass::getItemDesc($rowStock['item_id']).'</td>';
			$dataTable .= '<td>'.userClass::getSizeDesc($rowStock['size_id']).'</td>';
			$dataTable .= '<td>'.$rowStock['qty'].'</td>';
			$dataTable .= '<td>'.userClass::getBranchName($rowStock['wrhs_id']).'</td>';
			$dataTable .= '</tr>';
			$totalQty  += $rowStock['qty'];
		}
		$dataTable .= '<tr><th colspan="2">Total Qty./Row</th><th colspan="3">'.$totalQty.'</th></tr>';
		$dataTable .= '<tr><th colspan="2">Total Qty.</th><th colspan="3">'.$rowStock3['sumQty'].'</th></tr>';
		$dataTable .= '</table>';
		$dataTable .= '<br><br>';
		$dataTable .= '</div>';

		$dataTable .= '<div style="text-align:center;clear:both;padding:15px;">';

		if ($page == 1) {
			$dataTable .= " FIRST PREV ";
		} else {
			$prevPage   = $page - 1;
			$params     = $_GET;
			if (!empty($params['page'])) {
				$params['page'] = 1;
				$paramString1   = http_build_query($params);
				$dataTable     .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString1."'>FIRST</a> ";
				$params['page'] = $prevPage;
				$paramString2   = http_build_query($params);
				$dataTable     .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString2."'>PREV</a> ";
			} else {
				$dataTable     .= " <a href='".$_SERVER['REQUEST_URI']."?page=$prevPage'>PREV</a> ";
			}		
		}

		$dataTable .= " ( Page $page of $lastPage ) ";
		
		if ($page == $lastPage) {
	   		$dataTable .= " NEXT LAST ";
		} else {
			$nextPage   = $page + 1;
			$params     = $_GET;
			if (!empty($params['page'])) {
				$params['page'] = $nextPage;
				$paramString1   = http_build_query($params);
				$dataTable     .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString1."'>NEXT</a> ";
				$params['page'] = $lastPage;
				$paramString2   = http_build_query($params);
				$dataTable     .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString2."'>LAST</a> ";
			} else {
				$dataTable     .= " <a href='".$_SERVER['REQUEST_URI']."&page=$nextPage'>NEXT</a> ";
				$dataTable     .= " <a href='".$_SERVER['REQUEST_URI']."&page=$lastPage'>LAST</a> ";
			}
		}
		$dataTable .= '</div>';
	} else {
		$getStock   = $db->query($query2);
		$dataTable  = '<div id="reportWrapper2">';
		while ($rowStock = $db->fetch_array($getStock))
		{
			$itemPrices = userClass::getItemPrices($rowStock['item_id']);
			$dataTable .= '<div class="oneImage">
						   <span style="float:left;font-size:12px;font-weight:bold;color:red;">'.$rowStock['item_id'].'</span>';
			if ($_GET['selBranch'] == 1) {
				$totalSales2 = userClass::getItemTotalSales2($rowStock['item_id'], $locId);
				$dataTable  .= '<span style="float:right;font-size:12px;">'.number_format((userClass::getItemTotalSalesValue($rowStock['item_id'], $locId)/$totalSales2)).'/'.number_format($itemPrices['rtp']).'/'.number_format($itemPrices['msrp']).'</span>';
			} else {
				$totalSales2 = userClass::getItemTotalSales2($rowStock['item_id']);
				$dataTable  .= '<span style="float:right;font-size:12px;">'.number_format((userClass::getItemTotalSalesValue($rowStock['item_id'])/$totalSales2)).'/'.number_format($itemPrices['rtp']).'/'.number_format($itemPrices['msrp']).'</span>';
			}
			$dataTable  .= '<br style="clear:both;">
						   <a class="itemStockDetails" href="#" rel="'.$rowStock['item_id'].'">
						   <img src="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowStock['item_id'].'.jpg">
						   </a>
						   <span style="float:left;font-size:12px;">'.userClass::getItemAttr($rowStock['item_id']).'</span>';
			if ($_GET['selBranch'] == 1) {
				$dataTable  .= '<span style="float:right;font-size:12px;">'.$totalSales2.'/'.userClass::getItemTotalStock($rowStock['item_id'], $locId).'</span>';
			} else {
				$dataTable  .= '<span style="float:right;font-size:12px;">'.$totalSales2.'/'.userClass::getItemTotalStock($rowStock['item_id']).'</span>';
			}

			$dataTable .= '</div>';
		}

		$dataTable .= '<div style="text-align:center;clear:both;padding:15px;">';

		if ($page == 1) {
			$dataTable .= " FIRST PREV ";
		} else {
			$prevPage   = $page - 1;
			$params     = $_GET;
			if (!empty($params['page'])) {
				$params['page'] = 1;
				$paramString1   = http_build_query($params);
				$dataTable     .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString1."'>FIRST</a> ";
				$params['page'] = $prevPage;
				$paramString2   = http_build_query($params);
				$dataTable     .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString2."'>PREV</a> ";
			} else {
				$dataTable     .= " <a href='".$_SERVER['REQUEST_URI']."?page=$prevPage'>PREV</a> ";
			}		
		}

		$dataTable .= " ( Page $page of $lastPage ) ";

		if ($page == $lastPage) {
	   		$dataTable .= " NEXT LAST ";
		} else {
			$nextPage = $page + 1;
			$params  = $_GET;
			if (!empty($params['page'])) {
				$params['page'] = $nextPage;
				$paramString1   = http_build_query($params);
				$dataTable     .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString1."'>NEXT</a> ";
				$params['page'] = $lastPage;
				$paramString2   = http_build_query($params);
				$dataTable     .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString2."'>LAST</a> ";
			} else {
				$dataTable     .= " <a href='".$_SERVER['REQUEST_URI']."&page=$nextPage'>NEXT</a> ";
				$dataTable     .= " <a href='".$_SERVER['REQUEST_URI']."&page=$lastPage'>LAST</a> ";
			}
		}
		$dataTable .= '</div>';
	}
}

?>

<form name="frmStockReport" id="frmStockReport" method="GET" action="#"  enctype="multipart/form-data">
	
	<p><a id="exportBranchStock" href="#">Export Stock to Excel</a></p><br>
	<?php
		$branchType1 = "checked";
		$branchType2 = "";
		if (!empty($_GET['selBranch'])) {
			if ($_GET['selBranch'] == 1) {
				$branchType1 = "checked";
				$branchType2 = "";
			} else {
				$branchType1 = "";
				$branchType2 = "checked";
			}
		}
	?>
	<label><input type="radio" name="selBranch" value="1" <?php echo $branchType1 ?>> Current Branch</label>
	<label><input type="radio" name="selBranch" value="2" <?php echo $branchType2 ?>> All Branches</label>

	<br><br>

	<table>
		<tr>
			<th>Attribute</th>
			<th>Vendor</th>
			<th>Season</th>
			<th>Department</th>
			<th>Sub Department</th>
			<th>Size</th>
			<th>Gender</th>
			<th>Stock Level</th>
		</tr>
		<tr>
			<td>
				<select name="selAttr[]" id="selAttr" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allAttrs)): ?>
					<?php foreach ($allAttrs as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
				<select name="selVend[]" id="selVend" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allVends)): ?>
					<?php foreach ($allVends as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
				<select name="selSeason[]" id="selSeason" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allSeasons)): ?>
					<?php foreach ($allSeasons as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
				<select name="selDept[]" id="selDept" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allDepts)): ?>
					<?php foreach ($allDepts as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
                <select name="selSubDept[]" id="selSubDept" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if(!empty($allSubDepts)): ?>
                    <?php foreach ($allSubDepts as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
			<td>
				<select name="selSize[]" id="selSize" multiple="multiple">
					<option value="All" selected>All</option>
				</select>
			</td>
			<td>
				<select name="selGend[]" id="selGend" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allGenders)): ?>
					<?php foreach ($allGenders as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
				<select name="selStkLvl[]" id="selStkLvl" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allStkLvls)): ?>
					<?php foreach ($allStkLvls as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
		</tr>
	</table>

	<br><br>

	<label>Price From: <input class="txtPrice" type="text" name="priceFrom"></label>
	<label>To: <input class="txtPrice" type="text" name="priceTo"></label>

	<br></br>
	<?php
		$reportType1 = "checked";
		$reportType2 = "";
		if (!empty($_GET['selReportType'])) {
			if ($_GET['selReportType'] == 1) {
				$reportType1 = "checked";
				$reportType2 = "";
			} else {
				$reportType1 = "";
				$reportType2 = "checked";
			}
		}
	?>
	<label><input type="radio" name="selReportType" value="1" <?php echo $reportType1 ?>> Stock Report</label>

	<label><input type="radio" name="selReportType" value="2" <?php echo $reportType2 ?>> Visual Report</label>

	<br><br>
	<label>Intl Code#: <input type="text" name="intlCode"></label><br><br>
	<label>Item#: <input type="text" name="itemId"></label><br><br>

	<input type="submit" name="btnSubmit" value="Submit">
</form>

<div style="display:none">
	<div class="itemDetails"></div>
</div>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>