<?php require_once ("_inc/header2.php"); ?>

<style type="text/css">
	.component {
		width: 100%;
	}
	.red {
		color: red;
		font-weight: bold;
	}
	.totalSales, .totalStock, .totalSalesx, .totalStockx, .totalSalesxx, .totalStockxx {
		color: red;
		font-weight: bold;
	}
	table {
		font-size: 12px;
	}
	th {
	    background-color: #0072bc;
	    color: #fff;
	    white-space: nowrap;
	    vertical-align: middle;
	    padding: 0 5px;
	}
	th:nth-child(1),th:nth-child(2),th:nth-child(3){
		min-width: 70px;
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
		padding: 0;
		text-align: center;;
	}
	#tblFilterMerch td {
		padding: 5px
	}
	#tblFilterMerch th {
		padding: 5px
	}
</style>

<h1>Merchandise Window</h1>

<?php

$locId       = $_SESSION['loc_id'];
$allDepts    = userClass::getAllDepts();
$allGenders  = userClass::getAllGenders();
$allDesc     = userClass::getAllDesc();
$allSeasons  = userClass::getAllSeasons();
$allYears    = userClass::getAllYears();
$allBranches = userClass::getAllBranchesShort();
$allSubDepts = userClass::getAllSubDepts();
$getDate     = null;

if (isset($_GET['btnSubmit']))
{
	switch ($_GET['selDisplayType']) {
		case 1:
			$query1  = "SELECT items.item_id AS itemId, items_intl_code.desc AS intlCode
						FROM items
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id      = invoice_detail.item_id";

			$query2  = "SELECT items.item_id AS itemId, items_intl_code.desc AS intlCode
						FROM items
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id      = invoice_detail.item_id";
			break;

		case 2:
			$query1  = "SELECT items_dept.desc AS data, items_dept.dept_id AS typeId
						FROM items_dept
						LEFT JOIN items ON items.dept_id = items_dept.dept_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";

			$query2  = "SELECT items_dept.desc AS data, items_dept.dept_id AS typeId
						FROM items_dept
						LEFT JOIN items ON items.dept_id = items_dept.dept_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";		
			break;

		case 3:
			$query1  = "SELECT items_sub_dept.desc AS data, items_sub_dept.sub_dept_id AS typeId
						FROM items_sub_dept
						LEFT JOIN items ON items.sub_dept_id = items_sub_dept.sub_dept_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";

			$query2  = "SELECT items_sub_dept.desc AS data, items_sub_dept.sub_dept_id AS typeId
						FROM items_sub_dept
						LEFT JOIN items ON items.sub_dept_id = items_sub_dept.sub_dept_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";
			break;

		case 4:
			$query1  = "SELECT items_gender.desc AS data, items_gender.gender_id AS typeId
						FROM items_gender
						LEFT JOIN items ON items.gender_id = items_gender.gender_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";

			$query2  = "SELECT items_gender.desc AS data, items_gender.gender_id AS typeId
						FROM items_gender
						LEFT JOIN items ON items.gender_id = items_gender.gender_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";
			break;

		case 5:
			$query1  = "SELECT items_season.desc AS data, items_season.season_id AS typeId
						FROM items_season
						LEFT JOIN items ON items.season_id = items_season.season_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";

			$query2  = "SELECT items_season.desc AS data, items_season.season_id AS typeId
						FROM items_season
						LEFT JOIN items ON items.season_id = items_season.season_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";
			break;

		case 6:
			$query1  = "SELECT items_attr.desc AS data, items_attr.attr_id AS typeId
						FROM items_attr
						LEFT JOIN items ON items.attr_id = items_attr.attr_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";

			$query2  = "SELECT items_attr.desc AS data, items_attr.attr_id AS typeId
						FROM items_attr
						LEFT JOIN items ON items.attr_id = items_attr.attr_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";
			break;

		case 7:
			$query1  = "SELECT items_vendor.desc AS data, items_vendor.vend_id AS typeId
						FROM items_vendor
						LEFT JOIN items ON items.vend_id = items_vendor.vend_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";

			$query2  = "SELECT items_vendor.desc AS data, items_vendor.vend_id AS typeId
						FROM items_vendor
						LEFT JOIN items ON items.vend_id = items_vendor.vend_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";
			break;

		case 8:
			$query1  = "SELECT items_desc.desc AS data, items_desc.desc_id AS typeId
						FROM items_desc
						LEFT JOIN items ON items.desc_id = items_desc.desc_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";

			$query2  = "SELECT items_desc.desc AS data, items_desc.desc_id AS typeId
						FROM items_desc
						LEFT JOIN items ON items.desc_id = items_desc.desc_id
						LEFT JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
						LEFT JOIN invoice_detail  ON items.item_id = invoice_detail.item_id";
			break;
	}

	$condition = "WHERE";

	if (array_search("All", $_GET['selYear']) === false)
	{
		$getAllYears = implode(",", $_GET['selYear']);
		$query1 .= " JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
					 AND YEAR(invoice_header.date) in ({$getAllYears})";
		$query2 .= " JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
					 AND YEAR(invoice_header.date) in ({$getAllYears})";
		$condition = "AND";
	} else {
		$getAllYears = NULL;
	}

	if (array_search("All", $_GET['selMonth']) === false)
	{
		foreach ($_GET['selMonth'] as $value) {
			$months[] = $value;
		}
		$getDate = implode(",", $_GET['selMonth']);
		if ($condition == "WHERE") {
			$query1 .= " JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no";
			$query2 .= " JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no";
		}
		$query1 .= " ".$condition." MONTH(invoice_header.date) in ({$getDate})";
		$query2 .= " ".$condition." MONTH(invoice_header.date) in ({$getDate})";
		$condition = "AND";
	} else {
		$months = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
	}

	$monthsx = implode(",", $months);

	if (array_search("All", $_GET['selDept']) === false)
	{
		$getAllDepts = implode(",", $_GET['selDept']);
		$query1   .= " ".$condition." items.dept_id in ({$getAllDepts})";
		$query2   .= " ".$condition." items.dept_id in ({$getAllDepts})";
		$condition = "AND";
	} else {
		$getAllDepts = NULL;
	}

	if (array_search("All", $_GET['selSeason']) === false)
	{
		$getAllSeasons = implode(",", $_GET['selSeason']);
		$query1   .= " ".$condition." items.season_id in ({$getAllSeasons})";
		$query2   .= " ".$condition." items.season_id in ({$getAllSeasons})";
		$condition = "AND";
	} else {
		$getAllSeasons = NULL;
	}

	if (array_search("All", $_GET['selGender']) === false)
	{
		$getAllGenders = implode(",", $_GET['selGender']);
		$query1   .= " ".$condition." items.gender_id in ({$getAllGenders})";
		$query2   .= " ".$condition." items.gender_id in ({$getAllGenders})";
		$condition = "AND";
	} else {
		$getAllGenders = NULL;
	}

	if (array_search("All", $_GET['selDesc']) === false)
	{
		$getAllDescs = implode(",", $_GET['selDesc']);
		$query1   .= " ".$condition." items.desc_id in ({$getAllDescs})";
		$query2   .= " ".$condition." items.desc_id in ({$getAllDescs})";
		$condition = "AND";
	} else {
		$getAllDescs = NULL;
	}

	if (!empty($_GET['itemId']))
	{
		$query1    .= " ".$condition." items.item_id = '".$_GET['itemId']."'";
		$query2    .= " ".$condition." items.item_id = '".$_GET['itemId']."'";
		$condition = "AND";
	}

	if (!empty($_GET['intlCode']))
	{
		$query1    .= " ".$condition." items_intl_code.desc = '".$_GET['intlCode']."'";
		$query2    .= " ".$condition." items_intl_code.desc = '".$_GET['intlCode']."'";
		$condition = "AND";
	}

	if (array_search("All", $_GET['selSubDept']) === false)
	{
		$getAllSubDepts = implode(",", $_GET['selSubDept']);
		$query1   .= " ".$condition." items.sub_dept_id in ({$getAllSubDepts})";
		$query2   .= " ".$condition." items.sub_dept_id in ({$getAllSubDepts})";
		$condition = "AND";
	} else {
		$getAllSubDepts = NULL;
	}

	if (array_search("All", $_GET['selBranch']) === false) {
		foreach ($_GET['selBranch'] as $value) {
			$branches[]  = userClass::getBranchShortName($value);
			$branchesK[] = $value;
		}
		$branches2 = userClass::getAllBranchesShort();
		foreach ($branches2 as $key => $value) {
			if (!in_array($key, $_GET['selBranch'])) {
				unset($branches2[$key]);
			}
		}
		$_SESSION['slipBranches'] = $branches2;
		$getAllBranches = implode(",", $_GET['selBranch']);
		$query1   .= " ".$condition." invoice_detail.loc_id in ({$getAllBranches})";
		$query2   .= " ".$condition." invoice_detail.loc_id in ({$getAllBranches})";
		$condition = "AND";
	} else {
		$getAllBranches = NULL;
		unset($_SESSION['slipBranches']);
		$branches = userClass::getAllBranchesShort();
		foreach ($branches as $key => $value) {
			$branchesK[] = $key;
		}
	}
	if ($_GET['selDisplayType'] == 1) {
		$query1  .= " GROUP BY items.item_id ORDER BY items_intl_code.desc, items.item_id";
		$query2  .= " GROUP BY items.item_id ORDER BY items_intl_code.desc, items.item_id";
	} else {
		$query1  .= " GROUP BY data";
		$query2  .= " GROUP BY data";
	}
	
	if (isset($_GET['page']))
	{
	   $page = $_GET['page'];
	} else {
	   $page = 1;
	}
	
	$getNumRows  = $db->query($query1);
	$numRows     = mysql_num_rows($getNumRows);
	while ($rowAllIRows = $db->fetch_array($getNumRows)) {
		$allItems[] = $rowAllIRows['itemId'];
	}
	$rowsPerPage = 80;
	$lastPage    = ceil($numRows/$rowsPerPage);

	if ($page > $lastPage) {
	   $page = $lastPage;
	}
	if ($page < 1) {
	   $page  	  = 1;
	   $lastPage  = 1;
	}

	$limit   = ' LIMIT ' .($page - 1) * $rowsPerPage .',' .$rowsPerPage;
	$query2 .= $limit;
	// echo $query1 . "<br>";
	// echo $query2;
	$getAllItems   = $db->query($query2);
	switch ($_GET['selDisplayType']) {
		case 1:
			$dataTable      = '<thead>';
			$dataTable     .= '<tr>';
			$dataTable     .= '<th>Intl#</th><th>Item#</th><th>Type</th>';
			break;

		case 2:
			$dataTable      = '<thead>';
			$dataTable     .= '<tr>';
			$dataTable     .= '<th></th><th>DEPT</th><th>Type</th>';		
			break;

		case 3:
			$dataTable      = '<thead>';
			$dataTable     .= '<tr>';
			$dataTable     .= '<th></th><th>SUBDEPT</th><th>Type</th>';
			break;

		case 4:
			$dataTable      = '<thead>';
			$dataTable     .= '<tr>';
			$dataTable     .= '<th></th><th>GENDER</th><th>Type</th>';
			break;

		case 5:
			$dataTable      = '<thead>';
			$dataTable     .= '<tr>';
			$dataTable     .= '<th></th><th>SEASON</th><th>Type</th>';
			break;

		case 6:
			$dataTable      = '<thead>';
			$dataTable     .= '<tr>';
			$dataTable     .= '<th></th><th>ATTR</th><th>Type</th>';
			break;

		case 7:
			$dataTable      = '<thead>';
			$dataTable     .= '<tr>';
			$dataTable     .= '<th></th><th>VEND</th><th>Type</th>';
			break;

		case 8:
			$dataTable      = '<thead>';
			$dataTable     .= '<tr>';
			$dataTable     .= '<th></th><th>DESC</th><th>Type</th>';
			break;
	}

	if ($_GET['selReportType'] == 1) {
		if ($_GET['selViewType'] == 1)
		{
			foreach ($branches as $branch)
			{
				$dataTable .= '<th>'.$branch.'</th>';
			}
			$dataTable .= '<th>Total</th>';
			$dataTable .= '</tr>';
			$dataTable .= '</thead>';
			$dataTable .= '<tbody>';

			while ($rowAllItems = $db->fetch_array($getAllItems))
			{
				switch ($_GET['selDisplayType']) {
					case 1:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
						$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
						$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';
						break;

					case 2:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';

						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 3:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';

						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 4:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';

						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 5:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';

						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 6:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';

						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 7:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';

						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 8:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';

						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], $key, $monthsx, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						foreach ($branchesK as $key)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $key, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;
				}
			}
			$dataTable .= '<tr>';
			$dataTable .= '<th rowspan="2"></th>';
			$dataTable .= '<th rowspan="2">Total</th>';
			$dataTable .= '<th>Sales</th>';
			foreach ($branchesK as $key)
			{
				$dataTable  .= '<td class="totalSalesx"></td>';
			}
			$dataTable .= '<td class="totalSalesxx"></td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<th>Stock</th>';
			foreach ($branchesK as $key)
			{
				$dataTable  .= '<td class="totalStockx"></td>';
			}
			$dataTable .= '<td class="totalStockxx"></td>';
			$dataTable .= '</tr>';
			$dataTable .= '</tbody>';
		} else {
			foreach ($months as $month)
			{
				$dataTable .= '<th>M'.$month.'</th>';
			}
			$dataTable .= '<th>Total</th>';
			$dataTable .= '</tr>';
			$dataTable .= '</thead>';
			$dataTable .= '<tbody>';

			while ($rowAllItems = $db->fetch_array($getAllItems))
			{
				switch ($_GET['selDisplayType']) {
					case 1:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
						$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
						$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

						foreach ($months as $month)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
							
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
						$flagTotal  = 0;
						foreach ($months as $month)
						{
							if ($flagTotal == 0) {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							} else {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$flagTotal = 1;
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';
						break;

					case 2:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';					

						foreach ($months as $month)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						$flagTotal  = 0;
						foreach ($months as $month)
						{
							if ($flagTotal == 0) {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							} else {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$flagTotal = 1;
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 3:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';					

						foreach ($months as $month)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						$flagTotal  = 0;
						foreach ($months as $month)
						{
							if ($flagTotal == 0) {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							} else {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$flagTotal = 1;
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 4:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';					

						foreach ($months as $month)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						$flagTotal  = 0;
						foreach ($months as $month)
						{
							if ($flagTotal == 0) {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							} else {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$flagTotal = 1;
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';		
						break;

					case 5:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';					

						foreach ($months as $month)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						$flagTotal  = 0;
						foreach ($months as $month)
						{
							if ($flagTotal == 0) {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							} else {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$flagTotal = 1;
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 6:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';					

						foreach ($months as $month)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						$flagTotal  = 0;
						foreach ($months as $month)
						{
							if ($flagTotal == 0) {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							} else {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$flagTotal = 1;
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;

					case 7:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';					

						foreach ($months as $month)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						$flagTotal  = 0;
						foreach ($months as $month)
						{
							if ($flagTotal == 0) {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							} else {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$flagTotal = 1;
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';
						break;

					case 8:
						$dataTable .= '<tr>';
						$dataTable .= '<th rowspan="2"></th>';
						$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
						$dataTable .= '<th>Sales</th>';					

						foreach ($months as $month)
						{
							if ($_GET['selDataType'] == 1) {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							} else {
								$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], NULL, $month, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
							}
						}
						$dataTable .= '<td class="totalSales"></td>';
						$dataTable .= '</tr>';
						$dataTable .= '<tr>';
						$dataTable .= '<th>Stock</th>';
						$flagTotal  = 0;
						foreach ($months as $month)
						{
							if ($flagTotal == 0) {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							} else {
								if ($_GET['selDataType'] == 1) {
									$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$flagTotal = 1;
						}
						$dataTable .= '<td class="totalStock"></td>';
						$dataTable .= '</tr>';	
						break;
				}
			}
			$dataTable .= '<tr>';
			$dataTable .= '<th rowspan="2"></th>';
			$dataTable .= '<th rowspan="2">Total</th>';
			$dataTable .= '<th>Sales</th>';
			foreach ($months as $month)
			{
				$dataTable  .= '<td class="totalSalesx"></td>';
			}
			$dataTable .= '<td class="totalSalesxx"></td>';
			$dataTable .= '</tr>';
			$dataTable .= '<tr>';
			$dataTable .= '<th>Stock</th>';
			$flagTotal  = 0;
			foreach ($months as $month)
			{
				if ($flagTotal == 0) {
					$dataTable  .= '<td class="totalStockx"></td>';
				} else {
					$dataTable  .= '<td></td>';
				}
				$flagTotal  = 1;
			}
			$dataTable .= '<td class="totalStockxx"></td>';
			$dataTable .= '</tr>';
			$dataTable .= '</tbody>';
		}
	} else {
		//if ($_GET['selCompareBy'] == 0) die();
		switch ($_GET['selCompareBy']) {
			//shops
			case 1:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.userClass::getBranchShortName($value).'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//years
			case 2:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.$value.'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock2("dept_id", $rowAllItems['typeId'], $value, $monthsx, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock2("dept_id", $rowAllItems['typeId'], $value, $monthsx, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], NULL, $monthsx, $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//months
			case 3:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.$value.'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], NULL, $value, NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//depts
			case 4:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.userClass::getDeptName($value).'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';	
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $value, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//subdepts
			case 5:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.userClass::getSubDeptName($value).'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $value, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//gender
			case 6:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.userClass::getGenderName($value).'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $value, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//season
			case 7:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.userClass::getSeasonName($value).'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $value, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//attr
			case 8:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.userClass::getAttrName($value).'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//vend
			case 9:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.userClass::getVendName($value).'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], $value, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], $value, $getAllDepts, $getAllSeasons, $getAllGenders, $getAllDescs, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
			//desc
			case 10:
				foreach ($_GET['selViewCompare'] as $value) {
					$dataTable .= '<th>'.userClass::getDescName($value).'</th>';
				}
				$dataTable .= '<th>Total</th>';
				$dataTable .= '</tr>';
				$dataTable .= '</thead>';
				$dataTable .= '<tbody>';

				while ($rowAllItems = $db->fetch_array($getAllItems))
				{
					switch ($_GET['selDisplayType']) {
						case 1:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['intlCode'].'</th>';
							$dataTable .= '<th rowspan="2"><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowAllItems['itemId'].'.jpg">'.$rowAllItems['itemId'].'</a></th>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Sales</a></th>';

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSales3($rowAllItems['itemId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getItemTotalSalesValue2($rowAllItems['itemId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								}
								
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th><a target="_blank" href="itemSizeStock.php?itemId='.$rowAllItems['itemId'].'">Stock</a></th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getItemTotalStock($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getItemTotalStockValue($rowAllItems['itemId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 2:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 3:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("sub_dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("sub_dept_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("sub_dept_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 4:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("gender_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("gender_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("gender_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';		
							break;

						case 5:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("season_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("season_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("season_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 6:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("attr_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("attr_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("attr_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;

						case 7:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("vend_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("vend_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("vend_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';
							break;

						case 8:
							$dataTable .= '<tr>';
							$dataTable .= '<th rowspan="2"></th>';
							$dataTable .= '<th rowspan="2">'.$rowAllItems['data'].'</th>';
							$dataTable .= '<th>Sales</th>';					

							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($_GET['selDataType'] == 1) {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSales("desc_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								} else {
									$dataTable  .= '<td class="tdSales">'.userClass::getTypeTotalSalesValue("desc_id", $rowAllItems['typeId'], NULL, NULL, $getAllYears, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
								}
							}
							$dataTable .= '<td class="totalSales"></td>';
							$dataTable .= '</tr>';
							$dataTable .= '<tr>';
							$dataTable .= '<th>Stock</th>';
							$flagTotal  = 0;
							foreach ($_GET['selViewCompare'] as $value)
							{
								if ($flagTotal == 0) {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td class="tdStock">'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								} else {
									if ($_GET['selDataType'] == 1) {
										$dataTable .= '<td>'.userClass::getTypeTotalStock("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									} else {
										$dataTable .= '<td>'.userClass::getTypeTotalStockValue("desc_id", $rowAllItems['typeId'], NULL, $getAllDepts, $getAllSeasons, $getAllGenders, $value, $getAllSubDepts, $getAllBranches).'</td>';
									}
								}
								$flagTotal = 1;
							}
							$dataTable .= '<td class="totalStock"></td>';
							$dataTable .= '</tr>';	
							break;
					}
				}
			break;
		}
		$dataTable .= '<tr>';
		$dataTable .= '<th rowspan="2"></th>';
		$dataTable .= '<th rowspan="2">Total</th>';
		$dataTable .= '<th>Sales</th>';
		foreach ($_GET['selViewCompare'] as $value)
		{
			$dataTable  .= '<td class="totalSalesx"></td>';
		}
		$dataTable .= '<td class="totalSalesxx"></td>';
		$dataTable .= '</tr>';
		$dataTable .= '<tr>';
		$dataTable .= '<th>Stock</th>';
		$flagTotal  = 0;
		foreach ($_GET['selViewCompare'] as $value)
		{
			if ($flagTotal == 0) {
				$dataTable  .= '<td class="totalStockx"></td>';
			} else {
				$dataTable  .= '<td></td>';
			}
			$flagTotal  = 1;
		}
		$dataTable .= '<td class="totalStockxx"></td>';
		$dataTable .= '</tr>';
		$dataTable .= '</tbody>';
	}
	
	$pagingData = '<div style="text-align:center;clear:both;padding:15px;">';
	if ($page == 1) {
		$pagingData .= " FIRST PREV ";
	} else {
		$prevPage   = $page - 1;
		$params     = $_GET;
		if (!empty($params['page'])) {
			$params['page'] = 1;
			$paramString1 = http_build_query($params);
			$pagingData .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString1."'>FIRST</a> ";
			$params['page'] = $prevPage;
			$paramString2 = http_build_query($params);
			$pagingData .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString2."'>PREV</a> ";
		} else {
			$pagingData .= " <a href='".$_SERVER['REQUEST_URI']."?page=$prevPage'>PREV</a> ";
		}		
	}

	$pagingData .= " ( Page $page of $lastPage ) ";

	if ($page == $lastPage) {
   		$pagingData .= " NEXT LAST ";
	} else {
		$nextPage = $page + 1;
		$params = $_GET;
		if (!empty($params['page'])) {
			$params['page'] = $nextPage;
			$paramString1 = http_build_query($params);
			$pagingData .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString1."'>NEXT</a> ";
			$params['page'] = $lastPage;
			$paramString2 = http_build_query($params);
			$pagingData .= " <a href='".$_SERVER["PHP_SELF"]."?".$paramString2."'>LAST</a> ";
		} else {
			$pagingData .= " <a href='".$_SERVER['REQUEST_URI']."&page=$nextPage'>NEXT</a> ";
			$pagingData .= " <a href='".$_SERVER['REQUEST_URI']."&page=$lastPage'>LAST</a> ";
		}
	}
	$pagingData .= '</div>';
}

?>

<form name="frmTransferReport" id="frmTransferReport" method="GET" action="#"  enctype="multipart/form-data">

	<table id="tblFilterMerch">
		<tr>
			<th>Branch</th>
			<th>Year</th>
			<th>Month</th>
			<th>Department</th>
			<th>Sub Department</th>
			<th>Season</th>
			<th>Gender</th>
			<th>Description</th>
		</tr>
		<tr>
			<td>
				<select name="selBranch[]" id="selBranch" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allBranches)): ?>
					<?php foreach ($allBranches as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
				<select name="selYear[]" id="selYear2" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allYears)): ?>
					<?php foreach ($allYears as $value): ?>
					<option value="<?php echo $value ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
				<select name="selMonth[]" id="selMonth2" multiple="multiple">
					<option value="All" selected>All</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
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
				<select name="selGender[]" id="selGender" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allGenders)): ?>
					<?php foreach ($allGenders as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td>
				<select name="selDesc[]" id="selDesc" multiple="multiple">
					<option value="All" selected>All</option>
					<?php if(!empty($allDesc)): ?>
					<?php foreach ($allDesc as $key => $value): ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
		</tr>
		<?php
			$viewType1 = "checked";
			$viewType2 = "";
			if (!empty($_GET['selViewType'])) {
				if ($_GET['selViewType'] == 1) {
					$viewType1 = "checked";
					$viewType2 = "";
				} else {
					$viewType1 = "";
					$viewType2 = "checked";
				}
			}
			$dataType1 = "checked";
			$dataType2 = "";
			if (!empty($_GET['selDataType'])) {
				if ($_GET['selDataType'] == 1) {
					$dataType1 = "checked";
					$dataType2 = "";
				} else {
					$dataType1 = "";
					$dataType2 = "checked";
				}
			}
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
		<tr>
			<th colspan="4">Report Type: </th>
			<td colspan="2"><input type="radio" name="selReportType" class="selReportType" Value="1" <?php echo $viewType1 ?>>Normal</td>
			<td colspan="2"><input type="radio" name="selReportType" class="selReportType" Value="2" <?php echo $viewType2 ?>>Comparative</td>
		</tr>
		<tr id="trCompareBy">
			<th colspan="4">Compare By: </th>
			<td colspan="2">
				<select name="selCompareBy" id="selCompareBy">
					<option></option>
					<option value="1">Shop</option>
					<option value="2">Year</option>
					<!-- <option value="3">Month</option> -->
					<option value="4">Deptartment</option>
					<option value="5">Sub Department</option>
					<option value="6">Gender</option>
					<option value="7">Season</option>
					<!-- <option value="8">Attribute</option> -->
					<!-- <option value="9">Vendor</option> -->
					<option value="10">Description</option>
				</select>
			</td>
			<td colspan="2" id="selViewTypeResponse"></td>
		</tr>
		<tr id="trViewType">
			<th colspan="4">View Type: </th>
			<td colspan="2"><input type="radio" name="selViewType" Value="1" <?php echo $viewType1 ?>>Shop</td>
			<td colspan="2"><input type="radio" name="selViewType" Value="2" <?php echo $viewType2 ?>>Month</td>
		</tr>
		<tr>
			<th colspan="4">Data Type: </th>
			<td colspan="2"><input type="radio" name="selDataType" Value="1" <?php echo $dataType1 ?>>Qty</td>
			<td colspan="2"><input type="radio" name="selDataType" Value="2" <?php echo $dataType2 ?>>Value</td>
		</tr>
		<tr>
			<th colspan="4">Display Type: </th>
			<td colspan="4">
				<select name="selDisplayType" id="selDisplayType">
					<option value="1" selected>Item</option>
					<option value="2">Deptartment</option>
					<option value="3">Sub Department</option>
					<option value="4">Gender</option>
					<option value="5">Season</option>
					<!-- <option value="6">Attribute</option> -->
					<!-- <option value="7">Vendor</option> -->
					<option value="8">Description</option>
				</select>
			</td>
		</tr>
	</table>

	<br><br>
	<?php if (!empty($dataTable)): ?>
	<!-- <p><a query="<?php echo $query1; ?>" id="exportMerchandise" href="#">Export to Excel</a></p> -->
	<?php endif; ?>
	<br><br>
	<label>Item#: <input type="text" name="itemId"></label>
	<br><br>
	<label>Intl Code#: <input type="text" name="intlCode"></label>
	<br><br>
	<input type="submit" name="btnSubmit" value="Submit">
</form>
Select Color: <input class="color" value="66ff00">
<div class="component">
	<table class="overflow-y" id="tblTransferFull">
		<?php if (isset($dataTable)) echo $dataTable ?>
	</table>
</div>

<?php

if (isset($pagingData)) echo $pagingData;
require_once ("_inc/footer.php");
?>

<script src="_js/numeral.min.js"></script>
<?php
	if (isset($dataTable)) {
		echo '<script>
			var totalSales   = 0;
			var totalStock   = 0;
			var totalSalesx  = 0;
			var totalStockx  = 0;
			var totalSalesxx = 0;
			var totalStockxx = 0;
			var curIndex     = 0;
			$(".totalSales").each(function(){
				$(this).parent().find(".tdSales").each(function(){
					totalSales += Number($(this).text().replace(/,/g, ""));
				});
				$(this).text(totalSales);
				totalSales = 0;
			});
			$(".totalStock").each(function(){
				$(this).parent().find(".tdStock").each(function(){
					totalStock += Number($(this).text().replace(/,/g, ""));
				});
				$(this).text(totalStock);
				totalStock = 0;
			});
			$(".totalSalesx").each(function(){
		        var that = $(this)
				curIndex = $(that).index() - 3;
				$("tr").find("td:eq("+curIndex+")[class|=\'tdSales\']").each(function(){
		          totalSalesx += Number($(this).text().replace(/,/g, ""));
				});
				$(that).text(totalSalesx);
				totalSalesx = 0;
			});
			$(".totalStockx").each(function(){
		        var that = $(this)
				curIndex = $(that).index() - 1;
				$("tr").find("td:eq("+curIndex+")[class|=\'tdStock\']").each(function(){
		          totalStockx += Number($(this).text().replace(/,/g, ""));
				});
				$(that).text(totalStockx);
				totalStockx = 0;
			});
			$(".totalSalesxx").each(function(){
				$(this).parent().find(".totalSalesx").each(function(){
					totalSalesxx += Number($(this).text().replace(/,/g, ""));
				});
				$(this).text(totalSalesxx);
				totalSalesxx = 0;
			});
			$(".totalStockxx").each(function(){
				$(this).parent().find(".totalStockx").each(function(){
					totalStockxx += Number($(this).text().replace(/,/g, ""));
				});
				$(this).text(totalStockxx);
				totalStockxx = 0;
			});
		</script>';
	}
?>

<script src="_js/jquery.ba-throttle-debounce.min.js"></script>
<script src="_js/jquery.stickyheader.js"></script>
<script src="_js/jscolor.js"></script>
<script>
	$('#tblTransferFull td').click(function(){
		$(this).css("background-color", "#"+$('.color').val());
	});
</script>