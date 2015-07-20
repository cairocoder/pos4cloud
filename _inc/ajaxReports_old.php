<?php

require_once ("dbClass.php");
require_once ("transClass.php");
require_once ("userClass.php");
require_once ("sessionClass.php");

if (!empty($_POST['action']) && $_POST['action'] == "exportBranchStock")
{
	$locId       = $_SESSION['loc_id'];
	$branchValue = $_POST['branchValue'];

	$fileName = "stock-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	if ($branchValue == 1)
	{
		$query = "SELECT warehouses.item_id, SUM(warehouses.qty) AS qty, items_dept.desc AS dept, locations.short_desc AS loc,
				  items_size.desc AS size
				  FROM warehouses
				  JOIN items ON warehouses.item_id = items.item_id
				  JOIN items_dept ON items.dept_id = items_dept.dept_id
				  JOIN locations  ON warehouses.wrhs_id = locations.loc_id
				  JOIN items_size ON warehouses.size_id = items_size.size_id
				  WHERE wrhs_id = {$locId}
				  GROUP BY warehouses.item_id, warehouses.size_id, warehouses.wrhs_id";
	} else {
		$query = "SELECT warehouses.item_id, SUM(warehouses.qty) AS qty, items_dept.desc AS dept, locations.short_desc AS loc,
				  items_size.desc AS size
				  FROM warehouses
				  JOIN items ON warehouses.item_id = items.item_id
				  JOIN items_dept ON items.dept_id = items_dept.dept_id
				  JOIN locations  ON warehouses.wrhs_id = locations.loc_id
				  JOIN items_size ON warehouses.size_id = items_size.size_id
				  GROUP BY warehouses.item_id, warehouses.size_id, warehouses.wrhs_id";
	}

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "LOC, ITEM, SIZE, DEPT, QTY" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['loc']  . ',';
		$csv .= $row['item_id']  . ',';
		$csv .= $row['size']  . ',';
		$csv .= $row['dept']  . ',';
		$csv .= $row['qty'] . "\n";
	}

	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}

if (!empty($_POST['action']) && $_POST['action'] == "exportBranchInvoices")
{
	$locId       = $_SESSION['loc_id'];
	$branchValue = $_POST['branchValue'];

	$fileName = "invoices-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	if ($branchValue == 1)
	{
		$query = "SELECT invoice_detail.invo_no, invoice_detail.item_id,
				  invoice_detail.qty, invoice_detail.rtp, trans_types.desc AS invoType,
				  invoice_header.date, invoice_header.time, payment_types.desc AS payment,
				  locations.short_desc AS loc, customers.cust_name, customers.cust_tel,
				  employees.emp_name
				  FROM invoice_detail
				  JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
				  AND invoice_detail.loc_id = invoice_header.loc_id
				  JOIN trans_types ON invoice_detail.type = trans_types.trans_type_id
				  JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
				  JOIN locations  ON invoice_detail.loc_id = locations.loc_id
				  LEFT JOIN customers ON invoice_header.cust_id = customers.cust_id
				  LEFT JOIN employees ON invoice_header.sales_man_id = employees.emp_id
				  WHERE invoice_detail.loc_id = {$locId}
				  ORDER BY invoice_detail.invo_no ASC";
	} else {
		$query = "SELECT invoice_detail.invo_no, invoice_detail.item_id,
				  invoice_detail.qty, invoice_detail.rtp, trans_types.desc AS invoType,
				  invoice_header.date, invoice_header.time, payment_types.desc AS payment,
				  locations.short_desc AS loc, customers.cust_name, customers.cust_tel,
				  employees.emp_name
				  FROM invoice_detail
				  JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
				  AND invoice_detail.loc_id = invoice_header.loc_id
				  JOIN trans_types ON invoice_detail.type = trans_types.trans_type_id
				  JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
				  JOIN locations  ON invoice_detail.loc_id = locations.loc_id
				  LEFT JOIN customers ON invoice_header.cust_id = customers.cust_id
				  LEFT JOIN employees ON invoice_header.sales_man_id = employees.emp_id
				  ORDER BY invoice_detail.invo_no ASC";
	}

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "LOC, INVOICE, TYPE, ITEM, QTY, RTP, TOTAL, DATE, TIME, C.NAME, C.TEL, S.MAN, PAYMENT" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['loc'] . ',';
		$csv .= $row['invo_no'] . ',';
		$csv .= $row['invoType'] . ',';
		$csv .= $row['item_id'] . ',';
		if ($row['invoType'] == "Return") {
			$csv .= $row['qty'] * (-1) . ',';
		} else {
			$csv .= $row['qty'] . ',';
		}
		$csv .= $row['rtp'] . ',';
		$csv .= $row['rtp'] * $row['qty'] . ',';
		$csv .= $row['date'] . ',';
		$csv .= $row['time'] . ',';
		$csv .= $row['cust_name'] . ',';
		$csv .= $row['cust_tel'] . ',';
		$csv .= $row['emp_name'] . ',';
		$csv .= $row['payment'] . "\n";
	}

	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}

if (!empty($_POST['action']) && $_POST['action'] == "exportBranchInventory")
{
	$locId       = $_SESSION['loc_id'];
	$branchValue = $_POST['branchValue'];

	$fileName = "inventory-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	if ($branchValue == 1)
	{
		$query = "SELECT inventory_detail.trans_no, inventory_detail.item_id,
				  inventory_detail.qty, inventory_detail.rtp, trans_types.desc AS transType,
				  inventory_header.date, inventory_header.time, loc_from.short_desc AS `loc_from`,
				  loc_to.short_desc AS `loc_to`, status.desc AS status
				  FROM inventory_detail
				  JOIN inventory_header ON inventory_detail.trans_no = inventory_header.trans_no
				  AND inventory_detail.wrhs_id = inventory_header.wrhs_id
				  JOIN trans_types ON inventory_detail.type = trans_types.trans_type_id
				  JOIN locations AS loc_from ON inventory_header.from_wrhs_id = loc_from.loc_id
				  JOIN locations AS loc_to ON inventory_header.to_wrhs_id = loc_to.loc_id
				  JOIN status ON inventory_header.status = status.status_id
				  WHERE (inventory_header.wrhs_id = {$locId} OR inventory_header.from_wrhs_id = {$locId}
				  OR inventory_header.to_wrhs_id = {$locId})
				  ORDER BY inventory_detail.trans_no ASC";
	} else {
		$query = "SELECT inventory_detail.trans_no, inventory_detail.item_id,
				  inventory_detail.qty, inventory_detail.rtp, trans_types.desc AS transType,
				  inventory_header.date, inventory_header.time, loc_from.short_desc AS `loc_from`,
				  loc_to.short_desc AS `loc_to`, status.desc AS status
				  FROM inventory_detail
				  JOIN inventory_header ON inventory_detail.trans_no = inventory_header.trans_no
				  AND inventory_detail.wrhs_id = inventory_header.wrhs_id
				  JOIN trans_types ON inventory_detail.type = trans_types.trans_type_id
				  JOIN locations AS loc_from ON inventory_header.from_wrhs_id = loc_from.loc_id
				  JOIN locations AS loc_to ON inventory_header.to_wrhs_id = loc_to.loc_id
				  JOIN status ON inventory_header.status = status.status_id
				  ORDER BY inventory_detail.trans_no ASC";
	}

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "TRANSACTION, ITEM, QTY, RTP, TYPE, DATE, TIME, FROM, TO, STATUS" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['trans_no']  . ',';
		$csv .= $row['item_id']  . ',';
		$csv .= $row['qty']  . ',';
		$csv .= $row['rtp']  . ',';
		$csv .= $row['transType']  . ',';
		$csv .= $row['date']  . ',';
		$csv .= $row['time']  . ',';
		$csv .= $row['loc_from']  . ',';
		$csv .= $row['loc_to']  . ',';
		$csv .= $row['status'] . "\n";
	}

	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}

if (!empty($_POST['action']) && $_POST['action'] == "exportFullReport")
{
	$fileName = "fullReport-" . date('m-d-Y-H-i-s') . ".csv";

	$queryInvoices   = "SELECT invoice_detail.invo_no, invoice_detail.item_id,
						invoice_detail.qty, invoice_detail.rtp, trans_types.desc AS invoType,
						invoice_header.date, invoice_header.time, payment_types.desc AS payment,
						locations.short_desc AS loc, items.msrp AS msrp, items.item_cost AS cost,
						items_size.desc AS size, items_dept.desc AS dept, items_desc.desc AS desc2,
						items_gender.desc AS gender, items_sub_dept.desc AS subDept
						FROM invoice_detail
						JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
						AND invoice_detail.loc_id = invoice_header.loc_id
						JOIN trans_types ON invoice_detail.type = trans_types.trans_type_id
						JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
						JOIN locations  ON invoice_detail.loc_id = locations.loc_id
						JOIN items_size ON invoice_detail.size_id = items_size.size_id
						JOIN items ON invoice_detail.item_id = items.item_id
						JOIN items_dept ON items.dept_id = items_dept.dept_id
						JOIN items_desc ON items.desc_id = items_desc.desc_id
						LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
						LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
						ORDER BY invoice_detail.invo_no ASC";

	$queryInventory  = "SELECT inventory_detail.trans_no, inventory_detail.item_id,
						inventory_detail.qty, inventory_detail.rtp, trans_types.desc AS transType,
						inventory_header.date, inventory_header.time, loc_from.short_desc AS `loc_from`,
						items.msrp AS msrp, items.item_cost AS cost, loc_to.short_desc AS `loc_to`,
						status.desc AS status, items_size.desc AS size, items_dept.desc AS dept,
						items_desc.desc AS desc2, items_gender.desc AS gender,
						items_sub_dept.desc AS subDept
						FROM inventory_detail
						JOIN inventory_header ON inventory_detail.trans_no = inventory_header.trans_no
						AND inventory_detail.wrhs_id = inventory_header.wrhs_id
						JOIN trans_types ON inventory_detail.type = trans_types.trans_type_id
						JOIN locations AS loc_from ON inventory_header.from_wrhs_id = loc_from.loc_id
						JOIN locations AS loc_to ON inventory_header.to_wrhs_id = loc_to.loc_id
						JOIN items_size ON inventory_detail.size_id = items_size.size_id
						JOIN items ON inventory_detail.item_id = items.item_id
						JOIN items_dept ON items.dept_id = items_dept.dept_id
						JOIN items_desc ON items.desc_id = items_desc.desc_id
						LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
						LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
						JOIN status ON inventory_header.status = status.status_id
						ORDER BY inventory_detail.trans_no ASC";

	$queryStock      = "SELECT warehouses.item_id, SUM(warehouses.qty) AS qty, items_dept.desc AS dept,
						items.msrp AS msrp, items.rtp AS rtp, items.item_cost AS cost,
						locations.short_desc AS loc, items_size.desc AS size, items_dept.desc AS dept,
						items_desc.desc AS desc2, items_gender.desc AS gender,
						items_sub_dept.desc AS subDept
						FROM warehouses
						JOIN items ON warehouses.item_id = items.item_id
						JOIN items_dept ON items.dept_id = items_dept.dept_id
						JOIN items_desc ON items.desc_id = items_desc.desc_id
						JOIN locations  ON warehouses.wrhs_id = locations.loc_id
						JOIN items_size ON warehouses.size_id = items_size.size_id
						LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
						LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
						GROUP BY warehouses.item_id, warehouses.size_id, warehouses.wrhs_id";

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "DATA,STORE,FROM,TO,YEAR,MONTH,ITEM,SIZE,DEPT,SUB DEPT,DESC2,GENDER,INVOICE#,TRANS#,QTY,MSRP,RTP,COST,TOTAL,PAYMENT,STATUS" . "\n";

	$resultInvoices  = $db->query($queryInvoices);
	$resultInventory = $db->query($queryInventory);
	$resultStock     = $db->query($queryStock);

	while ($rowInvoices = $db->fetch_array($resultInvoices))
	{
		$csv .= $rowInvoices['invoType']  . ',';
		$csv .= $rowInvoices['loc'] . ',';
		$csv .= '-,';
		$csv .= '-,';
		$csv .= date('Y', strtotime($rowInvoices['date'])) . ',';
		$csv .= userClass::getMonth(strtotime($rowInvoices['date'])) . ',';
		$csv .= $rowInvoices['item_id'] . ',';
		$csv .= $rowInvoices['size'] . ',';
		$csv .= $rowInvoices['dept'] . ',';
		$csv .= $rowInvoices['subDept'] . ',';
		$csv .= $rowInvoices['desc2'] . ',';
		$csv .= $rowInvoices['gender'] . ',';
		$csv .= $rowInvoices['invo_no'] . ',';
		$csv .= '-,';
		if ($rowInvoices['invoType'] == "Return") {
			$csv .= $rowInvoices['qty'] * (-1) . ',';
		} else {
			$csv .= $rowInvoices['qty'] . ',';
		}
		$csv .= $rowInvoices['msrp'] . ',';
		$csv .= $rowInvoices['rtp'] . ',';
		$csv .= $rowInvoices['cost'] . ',';
		$csv .= $rowInvoices['qty'] * $rowInvoices['rtp'] . ',';
		$csv .= $rowInvoices['payment'] . ",";
		$csv .= '-' . "\n";
	}

	while ($rowInventory = $db->fetch_array($resultInventory))
	{
		$csv .= $rowInventory['transType'] . ',';
		$csv .= $rowInventory['loc_from'] . ',';
		$csv .= $rowInventory['loc_from'] . ',';
		$csv .= $rowInventory['loc_to'] . ',';
		$csv .= date('Y', strtotime($rowInventory['date'])) . ',';
		$csv .= userClass::getMonth(strtotime($rowInventory['date'])) . ',';
		$csv .= $rowInventory['item_id'] . ',';
		$csv .= $rowInventory['size'] . ',';
		$csv .= $rowInventory['dept'] . ',';
		$csv .= $rowInventory['subDept'] . ',';
		$csv .= $rowInventory['desc2'] . ',';
		$csv .= $rowInventory['gender'] . ',';
		$csv .= '-,';
		$csv .= $rowInventory['trans_no'] . ',';
		$csv .= $rowInventory['qty'] . ',';
		$csv .= $rowInventory['msrp'] . ',';
		$csv .= $rowInventory['rtp'] . ',';
		$csv .= $rowInventory['cost'] . ',';
		$csv .= $rowInventory['qty'] * $rowInventory['rtp'] . ',';
		$csv .= '-,';
		$csv .= $rowInventory['status'] . "\n";
	}

	while ($rowStock = $db->fetch_array($resultStock))
	{
		$csv .= 'STOCK,';
		$csv .= $rowStock['loc'] . ',';
		$csv .= '-,';
		$csv .= '-,';
		$csv .= '-,';
		$csv .= '-,';
		$csv .= $rowStock['item_id'] . ',';
		$csv .= $rowStock['size'] . ',';
		$csv .= $rowStock['dept'] . ',';
		$csv .= $rowStock['subDept'] . ',';
		$csv .= $rowStock['desc2'] . ',';
		$csv .= $rowStock['gender'] . ',';
		$csv .= '-,';
		$csv .= '-,';
		$csv .= $rowStock['qty'] . ',';
		$csv .= $rowStock['msrp'] . ',';
		$csv .= $rowStock['rtp'] . ',';
		$csv .= $rowStock['cost'] . ',';
		$csv .= '-,';
		$csv .= '-,';
		$csv .= '-' . "\n";
	}

	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}

if (!empty($_POST['action']) && $_POST['action'] == "exportMerchandise")
{
	$query       = $_POST['query'];

	$fileName = "merchandise-" . date('m-d-Y-H-i-s') . ".csv";

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$allBranches = userClass::getAllBranchesShort();


	$csv = "INTL#, ITEM#, TYPE," . implode(",", $allBranches) . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['intlCode'] . ',';
		$csv .= $row['itemId'] . ',';
		$csv .= 'Sales,';
		foreach ($allBranches as $key => $branch)
		{
			$csv  .= userClass::getItemTotalSales2($row['itemId'], $key) . ',';
		}
		$csv .= "\n";
		$csv .= $row['intlCode'] . ',';
		$csv .= $row['itemId'] . ',';
		$csv .= 'Stock,';
		foreach ($allBranches as $key => $branch)
		{
			$csv  .= userClass::getItemTotalStock($row['itemId'], $key) . ',';
		}
		$csv .= "\n";
	}

	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}

if (!empty($_POST['action']) && $_POST['action'] == "getItemStockSizeDetails")
{
	$itemId      = $_POST['itemId'];
	$sizeId      = $_POST['sizeId'];
	$locId       = $_POST['locId'];
	$allBranches = userClass::getAllBranchesShort();

	$dataTable  = '<table>';
	$dataTable .= '<tbody>';
	$dataTable .= '<tr>';
	$dataTable .= '<th>Item#</th><td></td><td colspan="2">'.$itemId.'</td>';
	$dataTable .= '</tr>';
	$dataTable .= '<tr>';
	$dataTable .= '<th>Size</th><td></td><td colspan="2">'.userClass::getSizeDesc($sizeId).'</td>';
	$dataTable .= '</tr>';
	$dataTable .= '<tr>';
	$dataTable .= '<th>LOC</th><td></td><td colspan="2">'.userClass::getBranchShortName($locId).'<span style="display:none" id="fromLocId">'.$locId.'<span></td>';
	$dataTable .= '</tr>';
	$dataTable .= '<tr>';
	$dataTable .= '<th>QTY</th><td></td><td><span id="fromItemSizeQty">'.userClass::getItemSizeStock($itemId, $sizeId, $locId).'</sapn></td><td><input style="width: 25px;text-align:center" type="text" id="fromItemSizeQty2" value="'.userClass::getItemSizeStock($itemId, $sizeId, $locId).'" disabled></td>';
	$dataTable .= '</tr>';
	$dataTable .= '<tr>';
	$dataTable .= '<td colspan="3">&nbsp;</td>';
	$dataTable .= '</tr>';
	foreach ($allBranches as $key => $value) {
		if ($key == $locId) {
			continue;
		}
		$dataTable .= '<tr>';
		$dataTable .= '<th rowspan="2">'.$value.'</th>';
		$dataTable .= '<th>Sales</th>';
		$dataTable .= '<td>'.userClass::getItemSizeSales($itemId, $sizeId, $key).'</td>';
		$dataTable .= '<td rowspan="2"><input style="width: 25px;text-align:center" type="text" class="itemSizeQty" itemId="'.$itemId.'" locId="'.$key.'" sizeId="'.$sizeId.'" value=""></td>';
		$dataTable .= '</tr>';
		$dataTable .= '<tr>';
		$dataTable .= '<th>Stock</th>';
		$dataTable .= '<td>'.userClass::getItemSizeStock($itemId, $sizeId, $key).'</td>';
		$dataTable .= '</tr>';
	}
	$dataTable .= '</tbody>';
	$dataTable .= '</table>';
	$dataTable .= '<br>';
	$dataTable .= '<input style="padding: 10px;width:100%" type="button" id="saveTransfer" value="Save Transfer">';

	echo $dataTable;
}

flush();