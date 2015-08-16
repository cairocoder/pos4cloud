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
				  items_size.desc AS size, items.msrp, items.rtp
				  FROM warehouses
				  JOIN items ON warehouses.item_id = items.item_id
				  JOIN items_dept ON items.dept_id = items_dept.dept_id
				  JOIN locations  ON warehouses.wrhs_id = locations.loc_id
				  LEFT JOIN items_size ON warehouses.size_id = items_size.size_id
				  WHERE wrhs_id = {$locId}
				  GROUP BY warehouses.item_id, warehouses.size_id, warehouses.wrhs_id
				  ORDER BY warehouses.item_id, items_size.order";
	} else {
		$query = "SELECT warehouses.item_id, SUM(warehouses.qty) AS qty, items_dept.desc AS dept, locations.short_desc AS loc,
				  items_size.desc AS size, items.msrp, items.rtp
				  FROM warehouses
				  JOIN items ON warehouses.item_id = items.item_id
				  JOIN items_dept ON items.dept_id = items_dept.dept_id
				  JOIN locations  ON warehouses.wrhs_id = locations.loc_id
				  LEFT JOIN items_size ON warehouses.size_id = items_size.size_id
				  GROUP BY warehouses.item_id, warehouses.size_id, warehouses.wrhs_id
				  ORDER BY warehouses.item_id, items_size.order";
	}

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "LOC,ITEM,SIZE,DEPT,MSRP,RTP,QTY" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['loc']  . ',';
		$csv .= $row['item_id']  . ',';
		$csv .= $row['size']  . ',';
		$csv .= $row['dept']  . ',';
		$csv .= $row['msrp']  . ',';
		$csv .= $row['rtp']  . ',';
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
	$dateFrom    = $_POST['dateFrom'];
	$dateTo      = $_POST['dateTo'];

	$fileName = "invoices-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	if ($branchValue == 1)
	{
		$query = "SELECT invoice_detail.invo_no, invoice_detail.item_id,
				  invoice_detail.qty, invoice_detail.rtp, trans_types.desc AS invoType,
				  invoice_header.date, invoice_header.time, payment_types.desc AS payment,
				  locations.short_desc AS loc, customers.cust_name, customers.cust_tel,
				  employees.emp_name, items_size.desc AS size, items_dept.desc AS dept
				  FROM invoice_detail
				  JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
				  AND invoice_detail.loc_id = invoice_header.loc_id
				  JOIN trans_types ON invoice_detail.type = trans_types.trans_type_id
				  JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
				  JOIN locations       ON invoice_detail.loc_id = locations.loc_id
				  LEFT JOIN customers  ON invoice_header.cust_id = customers.cust_id
				  LEFT JOIN employees  ON invoice_header.sales_man_id = employees.emp_id
				  LEFT JOIN items_size ON items_size.size_id = invoice_detail.size_id
				  LEFT JOIN items      ON items.item_id = invoice_detail.item_id
				  LEFT JOIN items_dept ON items.dept_id = items_dept.dept_id
				  WHERE invoice_detail.loc_id = '{$locId}'
				  AND invoice_header.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
				  ORDER BY invoice_detail.invo_no ASC";
	} else {
		$query = "SELECT invoice_detail.invo_no, invoice_detail.item_id,
				  invoice_detail.qty, invoice_detail.rtp, trans_types.desc AS invoType,
				  invoice_header.date, invoice_header.time, payment_types.desc AS payment,
				  locations.short_desc AS loc, customers.cust_name, customers.cust_tel,
				  employees.emp_name, items_size.desc AS size, items_dept.desc AS dept
				  FROM invoice_detail
				  JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
				  AND invoice_detail.loc_id = invoice_header.loc_id
				  JOIN trans_types ON invoice_detail.type = trans_types.trans_type_id
				  JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
				  JOIN locations       ON invoice_detail.loc_id = locations.loc_id
				  LEFT JOIN customers  ON invoice_header.cust_id = customers.cust_id
				  LEFT JOIN employees  ON invoice_header.sales_man_id = employees.emp_id
				  LEFT JOIN items_size ON items_size.size_id = invoice_detail.size_id
				  LEFT JOIN items      ON items.item_id = invoice_detail.item_id
				  LEFT JOIN items_dept ON items.dept_id = items_dept.dept_id
				  WHERE invoice_header.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
				  ORDER BY invoice_detail.invo_no ASC";
	}

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "LOC, INVOICE, TYPE, ITEM, SIZE, DEPT, QTY, RTP, TOTAL, DATE, TIME, C.NAME, C.TEL, S.MAN, PAYMENT" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['loc'] . ',';
		$csv .= $row['invo_no'] . ',';
		$csv .= $row['invoType'] . ',';
		$csv .= $row['item_id'] . ',';
		$csv .= $row['size'] . ',';
		$csv .= $row['dept'] . ',';
		if ($row['invoType'] == "Return") {
			$csv .= $row['qty'] * (-1) . ',';
		} else {
			$csv .= $row['qty'] . ',';
		}
		$csv .= $row['rtp'] . ',';
		$csv .= $row['rtp'] * $row['qty'] . ',';
		$csv .= $row['date'] . ',';
		$csv .= $row['time'] . ',';
		$csv .= '"' . $row['cust_name'] . '"' . ',';
		$csv .= $row['cust_tel'] . ',';
		$csv .= $row['emp_name'] . ',';
		$csv .= $row['payment'] . "\n";
	}

	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}

if (!empty($_POST['action']) && $_POST['action'] == "exportBranchInvoices2")
{
	$locId       = $_SESSION['loc_id'];
	$branchValue = $_POST['branchValue'];
	$dateFrom    = $_POST['dateFrom'];
	$dateTo      = $_POST['dateTo'];

	$fileName = "invoices-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	if ($branchValue == 1)
	{
		$query = "SELECT invoice_header.invo_no, invoice_header.cash_man_id, invoice_header.qty, invoice_header.total_amount,
				  invoice_header.discount_amount, invoice_header.net_value, trans_types.desc AS invoType,
				  invoice_header.date, invoice_header.time, payment_types.desc AS payment,
				  locations.short_desc AS loc, customers.cust_name, customers.cust_tel,
				  employees.emp_name
				  FROM invoice_header
				  JOIN trans_types ON invoice_header.invo_type = trans_types.trans_type_id
				  JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
				  JOIN locations  ON invoice_header.loc_id = locations.loc_id
				  LEFT JOIN customers ON invoice_header.cust_id = customers.cust_id
				  LEFT JOIN employees ON invoice_header.sales_man_id = employees.emp_id
				  WHERE invoice_header.loc_id = '{$locId}'
				  AND invoice_header.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
				  ORDER BY invoice_header.invo_no ASC";
	} else {
		$query = "SELECT invoice_header.invo_no, invoice_header.cash_man_id, invoice_header.qty, invoice_header.total_amount,
				  invoice_header.discount_amount, invoice_header.net_value, trans_types.desc AS invoType,
				  invoice_header.date, invoice_header.time, payment_types.desc AS payment,
				  locations.short_desc AS loc, customers.cust_name, customers.cust_tel,
				  employees.emp_name
				  FROM invoice_header
				  JOIN trans_types ON invoice_header.invo_type = trans_types.trans_type_id
				  JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
				  JOIN locations  ON invoice_header.loc_id = locations.loc_id
				  LEFT JOIN customers ON invoice_header.cust_id = customers.cust_id
				  LEFT JOIN employees ON invoice_header.sales_man_id = employees.emp_id
				  WHERE invoice_header.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
				  ORDER BY invoice_header.invo_no ASC";
	}

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "LOC,INVOICE,TYPE,QTY,TOTAL,DISC,NET,DATE,TIME,C.NAME,C.TEL,S.MAN,BY,PAYMENT" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['loc'] . ',';
		$csv .= $row['invo_no'] . ',';
		$csv .= $row['invoType'] . ',';
		$csv .= $row['qty'] . ',';
		$csv .= $row['total_amount'] . ',';
		$csv .= $row['discount_amount'] . ',';
		$csv .= $row['net_value'] . ',';
		$csv .= $row['date'] . ',';
		$csv .= $row['time'] . ',';
		$csv .= '"' . $row['cust_name'] . '"' . ',';
		$csv .= $row['cust_tel'] . ',';
		$csv .= $row['emp_name'] . ',';
		$csv .= userClass::getUserName($row['cash_man_id']) . ',';
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
	$dateFrom    = $_POST['dateFrom'];
	$dateTo      = $_POST['dateTo'];

	$fileName = "inventory-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	if ($branchValue == 1)
	{
		$query = "SELECT inventory_detail.trans_no, inventory_detail.item_id,
				  inventory_detail.qty, inventory_detail.rtp, trans_types.desc AS transType,
				  inventory_header.date, inventory_header.time, loc_from.short_desc AS `loc_from`,
				  loc_to.short_desc AS `loc_to`, loc_send.short_desc AS `loc_send`,
				  status.desc AS status, users.username
				  FROM inventory_detail
				  JOIN inventory_header ON inventory_detail.trans_no = inventory_header.trans_no
				  AND inventory_detail.wrhs_id = inventory_header.wrhs_id
				  JOIN trans_types ON inventory_detail.type = trans_types.trans_type_id
				  JOIN locations AS loc_from ON inventory_header.from_wrhs_id = loc_from.loc_id
				  JOIN locations AS loc_to ON inventory_header.to_wrhs_id = loc_to.loc_id
				  JOIN locations AS loc_send ON inventory_header.wrhs_id = loc_send.loc_id
				  LEFT JOIN status ON inventory_header.status = status.status_id
				  JOIN users ON inventory_header.stock_keeper_id = users.user_id
				  WHERE (inventory_header.wrhs_id = {$locId} OR inventory_header.from_wrhs_id = {$locId}
				  OR inventory_header.to_wrhs_id = {$locId})
				  AND inventory_header.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
				  ORDER BY inventory_detail.trans_no ASC";
	} else {
		$query = "SELECT inventory_detail.trans_no, inventory_detail.item_id,
				  inventory_detail.qty, inventory_detail.rtp, trans_types.desc AS transType,
				  inventory_header.date, inventory_header.time, loc_from.short_desc AS `loc_from`,
				  loc_to.short_desc AS `loc_to`, loc_send.short_desc AS `loc_send`,
				  status.desc AS status, users.username
				  FROM inventory_detail
				  JOIN inventory_header ON inventory_detail.trans_no = inventory_header.trans_no
				  AND inventory_detail.wrhs_id = inventory_header.wrhs_id
				  JOIN trans_types ON inventory_detail.type = trans_types.trans_type_id
				  JOIN locations AS loc_from ON inventory_header.from_wrhs_id = loc_from.loc_id
				  JOIN locations AS loc_to ON inventory_header.to_wrhs_id = loc_to.loc_id
				  JOIN locations AS loc_send ON inventory_header.wrhs_id = loc_send.loc_id
				  LEFT JOIN status ON inventory_header.status = status.status_id
				  JOIN users ON inventory_header.stock_keeper_id = users.user_id
				  WHERE inventory_header.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
				  ORDER BY inventory_detail.trans_no ASC";
	}

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "TRANSACTION, BY, USER, ITEM, QTY, RTP, TYPE, DATE, TIME, FROM, TO, STATUS" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['trans_no']  . ',';
		$csv .= $row['loc_send']  . ',';
		$csv .= $row['username']  . ',';
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
	$dateFrom     = $_POST['dateFrom'];
	$dateTo       = $_POST['dateTo'];
	$chkInvoices  = $_POST['chkInvoices'];
	$chkInventory = $_POST['chkInventory'];
	$chkStock     = $_POST['chkStock'];

	$fileName = "fullReport-" . date('m-d-Y-H-i-s') . ".csv";

	$queryInvoices   = "SELECT invoice_detail.invo_no, invoice_detail.item_id,
						invoice_detail.qty, invoice_detail.rtp, trans_types.desc AS invoType,
						invoice_header.date, invoice_header.time, payment_types.desc AS payment,
						locations.short_desc AS loc, items.msrp AS msrp, inventory_detail.cost AS cost,
						items_size.desc AS size, items_dept.desc AS dept, items_desc.desc AS desc2,
						items_gender.desc AS gender, items_sub_dept.desc AS subDept, items_attr.desc AS attr,
						items_vendor.long_desc AS vend, items_season.desc As season, items.item_name AS desc1
						FROM invoice_detail
						JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
						AND invoice_detail.loc_id = invoice_header.loc_id
						LEFT JOIN trans_types ON invoice_detail.type = trans_types.trans_type_id
						LEFT JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
						LEFT JOIN locations  ON invoice_detail.loc_id = locations.loc_id
						LEFT JOIN items_size ON invoice_detail.size_id = items_size.size_id
						LEFT JOIN  inventory_header ON inventory_header.invo_no  = invoice_detail.invo_no
						AND   inventory_header.wrhs_id = invoice_detail.loc_id
						JOIN items ON invoice_detail.item_id = items.item_id
						LEFT JOIN  inventory_detail ON inventory_header.trans_no = inventory_detail.trans_no
						AND   inventory_header.wrhs_id = inventory_detail.wrhs_id
						AND   inventory_detail.item_id = invoice_detail.item_id
					  	AND   inventory_detail.serial  = invoice_detail.serial
						LEFT JOIN items_dept ON items.dept_id = items_dept.dept_id
						LEFT JOIN items_desc ON items.desc_id = items_desc.desc_id
						LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
						LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
						LEFT JOIN items_attr ON items.attr_id = items_attr.attr_id
						LEFT JOIN items_vendor ON items.vend_id = items_vendor.vend_id
						LEFT JOIN items_season ON items.season_id = items_season.season_id
						WHERE invoice_header.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
						ORDER BY invoice_detail.invo_no ASC";

	$queryInventory  = "SELECT inventory_detail.trans_no, inventory_detail.item_id,
						inventory_detail.qty, inventory_detail.rtp, trans_types.desc AS transType,
						inventory_header.date, inventory_header.time, loc_from.short_desc AS `loc_from`,
						items.msrp AS msrp, inventory_detail.cost AS cost, loc_to.short_desc AS `loc_to`,
						status.desc AS status, items_size.desc AS size, items_dept.desc AS dept,
						items_desc.desc AS desc2, items_gender.desc AS gender, items_sub_dept.desc AS subDept,
						items_attr.desc AS attr, items_vendor.long_desc AS vend, items_season.desc As season,
						items.item_name AS desc1
						FROM inventory_detail
						JOIN inventory_header ON inventory_detail.trans_no = inventory_header.trans_no
						AND inventory_detail.wrhs_id = inventory_header.wrhs_id
						JOIN trans_types ON inventory_detail.type = trans_types.trans_type_id
						JOIN locations AS loc_from ON inventory_header.from_wrhs_id = loc_from.loc_id
						JOIN locations AS loc_to ON inventory_header.to_wrhs_id = loc_to.loc_id
						LEFT JOIN items_size ON inventory_detail.size_id = items_size.size_id
						JOIN items ON inventory_detail.item_id = items.item_id
						LEFT JOIN items_dept ON items.dept_id = items_dept.dept_id
						LEFT JOIN items_desc ON items.desc_id = items_desc.desc_id
						LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
						LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
						LEFT JOIN items_attr ON items.attr_id = items_attr.attr_id
						LEFT JOIN items_vendor ON items.vend_id = items_vendor.vend_id
						LEFT JOIN items_season ON items.season_id = items_season.season_id
						LEFT JOIN status ON inventory_header.status = status.status_id
						WHERE inventory_header.date BETWEEN '{$dateFrom}' AND '{$dateTo}'
						ORDER BY inventory_detail.trans_no ASC";

	$queryStock      = "SELECT warehouses.item_id, SUM(warehouses.qty) AS qty, items_dept.desc AS dept,
						items.msrp AS msrp, items.rtp AS rtp, items.item_cost AS cost,
						locations.short_desc AS loc, items_size.desc AS size, items_dept.desc AS dept,
						items_desc.desc AS desc2, items_gender.desc AS gender, items_sub_dept.desc AS subDept,
						items_attr.desc AS attr, items_vendor.long_desc AS vend, items_season.desc As season,
						items.item_name AS desc1
						FROM warehouses
						JOIN items ON warehouses.item_id = items.item_id
						LEFT JOIN items_dept ON items.dept_id = items_dept.dept_id
						LEFT JOIN items_desc ON items.desc_id = items_desc.desc_id
						LEFT JOIN locations  ON warehouses.wrhs_id = locations.loc_id
						LEFT JOIN items_size ON warehouses.size_id = items_size.size_id
						LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
						LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
						LEFT JOIN items_attr ON items.attr_id = items_attr.attr_id
						LEFT JOIN items_vendor ON items.vend_id = items_vendor.vend_id
						LEFT JOIN items_season ON items.season_id = items_season.season_id
						GROUP BY warehouses.item_id, warehouses.size_id, warehouses.wrhs_id";

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "DATA,STORE,FROM,TO,DATE,YEAR,MONTH,ITEM,SIZE,DEPT,SUBDEPT,DESC1,DESC2,GENDER,ATTR,VEND,SEASON,INVO#,TRANS#,QTY,MSRP,RTP,COST,TOTAL,PAYMENT,STATUS" . "\n";

	if ($chkInvoices != 0) {
		$resultInvoices  = $db->query($queryInvoices);
		while ($rowInvoices = $db->fetch_array($resultInvoices))
		{
			$csv .= $rowInvoices['invoType'] . ',';
			$csv .= $rowInvoices['loc'] . ',';
			$csv .= ',,';
			$csv .= $rowInvoices['date'] . ',';
			$csv .= date('Y', strtotime($rowInvoices['date'])) . ',';
			$csv .= userClass::getMonth(strtotime($rowInvoices['date'])) . ',';
			$csv .= $rowInvoices['item_id'] . ',';
			$csv .= $rowInvoices['size'] . ',';
			$csv .= $rowInvoices['dept'] . ',';
			$csv .= $rowInvoices['subDept'] . ',';
			$csv .= $rowInvoices['desc1'] . ',';
			$csv .= $rowInvoices['desc2'] . ',';
			$csv .= $rowInvoices['gender'] . ',';
			$csv .= $rowInvoices['attr'] . ',';
			$csv .= $rowInvoices['vend'] . ',';
			$csv .= $rowInvoices['season'] . ',';
			$csv .= $rowInvoices['invo_no'] . ',';
			$csv .= ',';
			if ($rowInvoices['invoType'] == "Return") {
				$csv .= $rowInvoices['qty'] * (-1) . ',';
			} else {
				$csv .= $rowInvoices['qty'] . ',';
			}
			$csv .= $rowInvoices['msrp'] . ',';
			$csv .= $rowInvoices['rtp'] . ',';
			$csv .= $rowInvoices['cost'] . ',';
			$csv .= $rowInvoices['qty'] * $rowInvoices['rtp'] . ',';
			$csv .= $rowInvoices['payment'] . ',';
			$csv .= '' . "\n";
		}
	}
	
	if ($chkInventory != 0) {
		$resultInventory = $db->query($queryInventory);
		while ($rowInventory = $db->fetch_array($resultInventory))
		{
			$csv .= $rowInventory['transType'] . ',';
			$csv .= $rowInventory['loc_from'] . ',';
			$csv .= $rowInventory['loc_from'] . ',';
			$csv .= $rowInventory['loc_to'] . ',';
			$csv .= $rowInventory['date'] . ',';
			$csv .= date('Y', strtotime($rowInventory['date'])) . ',';
			$csv .= userClass::getMonth(strtotime($rowInventory['date'])) . ',';
			$csv .= $rowInventory['item_id'] . ',';
			$csv .= $rowInventory['size'] . ',';
			$csv .= $rowInventory['dept'] . ',';
			$csv .= $rowInventory['subDept'] . ',';
			$csv .= $rowInventory['desc1'] . ',';
			$csv .= $rowInventory['desc2'] . ',';
			$csv .= $rowInventory['gender'] . ',';
			$csv .= $rowInventory['attr'] . ',';
			$csv .= $rowInventory['vend'] . ',';
			$csv .= $rowInventory['season'] . ',';
			$csv .= ',';
			$csv .= $rowInventory['trans_no'] . ',';
			$csv .= $rowInventory['qty'] . ',';
			$csv .= $rowInventory['msrp'] . ',';
			$csv .= $rowInventory['rtp'] . ',';
			$csv .= $rowInventory['cost'] . ',';
			$csv .= $rowInventory['qty'] * $rowInventory['rtp'] . ',';
			$csv .= ',';
			$csv .= $rowInventory['status'] . "\n";
		}
	}

	if ($chkStock != 0) {
		$resultStock     = $db->query($queryStock);
		while ($rowStock = $db->fetch_array($resultStock))
		{
			$csv .= 'STOCK,';
			$csv .= $rowStock['loc'] . ',';
			$csv .= ',';
			$csv .= ',';
			$csv .= ',';
			$csv .= ',';
			$csv .= ',';
			$csv .= $rowStock['item_id'] . ',';
			$csv .= $rowStock['size'] . ',';
			$csv .= $rowStock['dept'] . ',';
			$csv .= $rowStock['subDept'] . ',';
			$csv .= $rowStock['desc1'] . ',';
			$csv .= $rowStock['desc2'] . ',';
			$csv .= $rowStock['gender'] . ',';
			$csv .= $rowStock['attr'] . ',';
			$csv .= $rowStock['vend'] . ',';
			$csv .= $rowStock['season'] . ',';
			$csv .= ',';
			$csv .= ',';
			$csv .= $rowStock['qty'] . ',';
			$csv .= $rowStock['msrp'] . ',';
			$csv .= $rowStock['rtp'] . ',';
			$csv .= $rowStock['cost'] . ',';
			$csv .= ',';
			$csv .= ',';
			$csv .= '' . "\n";
		}
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

if (!empty($_POST['action']) && $_POST['action'] == "getInvnDetails2")
{
	$transNo = $_POST['transNo'];
	$wrhsId  = $_POST['wrhsId'];
	$locId   = $_SESSION['loc_id'];

	#get invoice header
	$getInvnHeader = $db->query("SELECT * FROM `inventory_header`
								 WHERE wrhs_id       = '{$wrhsId}'
								 AND   trans_no      = '{$transNo}'
								 AND   trans_type_id IN (4, 7, 8)");

	$rowInvnHeader = $db->fetch_array($getInvnHeader);

	if ($rowInvnHeader['trans_type_id'] == 5)
	{
		#get invoice header
		$getInvnHeader = $db->query("SELECT * FROM `inventory_header`
									 WHERE from_wrhs_id  = '".$rowInvnHeader['from_wrhs_id']."'
									 AND   to_wrhs_id    = '".$rowInvnHeader['to_wrhs_id']."'
									 AND   doc_no        = '".$rowInvnHeader['doc_no']."'
									 AND   trans_type_id = '4'");

		$rowInvnHeader = $db->fetch_array($getInvnHeader);

		#get invoice detail
		$getInvnDetail = $db->query("SELECT * FROM inventory_detail
									 WHERE wrhs_id  = '".$rowInvnHeader['from_wrhs_id']."'
									 AND   trans_no = '".$rowInvnHeader['trans_no']."'");
	} else {
		#get invoice detail
		$getInvnDetail = $db->query("SELECT SUM(`qty`) as qty, `serial`, `item_id`, `rtp` FROM inventory_detail
									 WHERE wrhs_id  = '".$rowInvnHeader['wrhs_id']."'
									 AND   trans_no = '{$transNo}'
									 GROUP BY `item_id`");
	}
	
	#get location name
	$locName  = userClass::getBranchName($locId);

	$totalQty = 0;

	$dataTable  = '<div id="reportWrapper">';
	$dataTable .= '<a onclick="doPrint()" style="cursor: pointer; display: block; padding: 10px; color: white; background: red; text-decoration: none; font-weight: bold;">Print</a>';
	$dataTable .= '<br><p><img src="_img/logo-black.png"></p>';
	$dataTable .= '<br><p style="font-weight: bold;">'.$locName.'</p>';
	$dataTable .= '<br><p>Transaction# '.$transNo.'</p>';
	$dataTable .= '<br><p>From: '.userClass::getBranchName($rowInvnHeader['from_wrhs_id']).'</p>';
	$dataTable .= '<p>To: '.userClass::getBranchName($rowInvnHeader['to_wrhs_id']).'</p>';
	$dataTable .= '<br><p>'.$rowInvnHeader['date'].' / '.date('h:i:s', strtotime($rowInvnHeader['time'])).'</p><br>';
	$dataTable .= '<table id="tblInvoiceDetail"><tr><th></th><th>Item#</th><th>Price</th><th>Qty.</th><th>Total</th>';
	if ($locId == 55 || $locId == 44) {
		$dataTable .= '<th>LOC</th>';
	}
	$dataTable .= '</tr>';
	while ($rowInvnDetail = $db->fetch_array($getInvnDetail))
	{
		$dataTable .= '<tr><td>'.$rowInvnDetail['serial'].'</td><td>'.$rowInvnDetail['item_id'].'</td><td>'.$rowInvnDetail['rtp'].'</td><td>'.$rowInvnDetail['qty'].'</td><td>'.number_format($rowInvnDetail['qty'] * $rowInvnDetail['rtp'], 2, '.', '').'</td>';
		if ($locId == 55) {
			$dataTable .= '<td>'.userClass::getItemLocation($rowInvnDetail['item_id'], 55).'</td>';
		} elseif ($locId == 44) {
			$dataTable .= '<td>'.userClass::getItemLocation($rowInvnDetail['item_id'], 44).'</td>';
		}
		$dataTable .= '</tr>';
		$totalQty  += $rowInvnDetail['qty'];
	}
	$dataTable .= '<tr><td class="noBorder" colspan="4">&nbsp</td></tr><tr><td class="noBorder" colspan="6">&nbsp</tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="4">Total Qty</th><td style="text-align: center;">'.$totalQty.'</td></tr>';
	$dataTable .= '<tr style="text-align: left;"><th colspan="4">Total Price</th><td style="text-align: center;">'.$rowInvnHeader['total_rtp'].'</td></tr>';
	$dataTable .= '</table>';
	$dataTable .= '</div>';

	echo $dataTable;
}

if (!empty($_POST['action']) && $_POST['action'] == "exportInvnExcel")
{
	$transNo  = $_POST['transNo'];
	$wrhsId   = $_POST['wrhsId'];
	$locId    = $_SESSION['loc_id'];
	$fileName = "Trans-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	#get invoice header
	$getInvnHeader = $db->query("SELECT * FROM `inventory_header`
								 WHERE wrhs_id       = '{$wrhsId}'
								 AND   trans_no      = '{$transNo}'
								 AND   trans_type_id IN (4, 7, 8)");

	$rowInvnHeader = $db->fetch_array($getInvnHeader);

	if ($rowInvnHeader['trans_type_id'] == 5)
	{
		#get invoice header
		$getInvnHeader = $db->query("SELECT * FROM `inventory_header`
									 WHERE from_wrhs_id  = '".$rowInvnHeader['from_wrhs_id']."'
									 AND   to_wrhs_id    = '".$rowInvnHeader['to_wrhs_id']."'
									 AND   doc_no        = '".$rowInvnHeader['doc_no']."'
									 AND   trans_type_id = '4'");

		$rowInvnHeader = $db->fetch_array($getInvnHeader);

		#get invoice detail
		$getInvnDetail = $db->query("SELECT * FROM inventory_detail
									 WHERE wrhs_id  = '".$rowInvnHeader['from_wrhs_id']."'
									 AND   trans_no = '".$rowInvnHeader['trans_no']."'");
	} else {
		#get invoice detail
		$getInvnDetail = $db->query("SELECT SUM(`qty`) as qty, `serial`, `item_id`, `size_id` FROM inventory_detail
									 WHERE wrhs_id  = '".$rowInvnHeader['wrhs_id']."'
									 AND   trans_no = '{$transNo}'
									 GROUP BY `item_id`, `size_id`
									 ORDER BY `item_id` ASC");
	}
	
	#get location name
	$locName  = userClass::getBranchName($locId);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file
	if ($locId == 55 || $locId == 44) {
		$csv = "ITEM,SIZE,LOC,QTY" . "\n";
	} else {
		$csv = "ITEM,SIZE,QTY" . "\n";
	}
	while ($rowInvnDetail = $db->fetch_array($getInvnDetail))
	{
		// $data[]  = $rowInvnDetail;
		// $items[0][] = $rowInvnDetail['item_id'];
		$csv .= $rowInvnDetail['item_id'] . ',';
		$csv .= userClass::getSizeDesc($rowInvnDetail['size_id']) . ',';
		if ($locId == 55) {
			$csv .= userClass::getItemLocation($rowInvnDetail['item_id'], 55) . ',';
		} elseif ($locId == 44) {
			$csv .= userClass::getItemLocation($rowInvnDetail['item_id'], 44) . ',';
		}
		$csv .= $rowInvnDetail['qty'] . "\n";
	}
	// $items = array_unique($items);
	// foreach ($data as $value) {
	// 	foreach ($value as $value) {
	// 		if (array_search($value['item_id'], $items) === false)
	// 	}
	// }
	// die();
	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}

if (!empty($_POST['action']) && $_POST['action'] == "getMonthRanges")
{
	$selectedYears = $_POST['selectedYears'];

	echo '<option value="All" selected>All</option>';
	
	foreach ($selectedYears as $oneYear)
	{
		$result   = $db->query("SELECT DISTINCT MONTH(`date`) AS `date` FROM invoice_header
								WHERE YEAR(`date`) = '{$oneYear}' ORDER BY `date` ASC");
		while ($row = $db->fetch_array($result))
		{
			echo '<option value="'.$row['date'].'">'.$row['date'].'</option>';
		}
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "getDayRanges")
{
	$selectedYears  = $_POST['selectedYears'];
	$selectedMonths = $_POST['selectedMonths'];

	echo '<option value="All" selected>All</option>';
	
	foreach ($selectedMonths as $oneMonth)
	{
		foreach ($selectedYears as $oneYear)
		{
			$result   = $db->query("SELECT DISTINCT `date` AS `date` FROM invoice_header
									WHERE MONTH(`date`) = '{$oneMonth}' AND YEAR(`date`) = '{$oneYear}'
									ORDER BY `date` ASC");
			while ($row = $db->fetch_array($result))
			{
				echo '<option value="'.$row['date'].'">'.$row['date'].'</option>';
			}
		}
	}
}

if (!empty($_POST['action']) && $_POST['action'] == "exportSlip")
{
	if (!$session->is_logged_in()) {
	  echo "error";
	  die();
	}

	$slipNo  = $_POST['slipNo'];
	$locId   = $_SESSION['loc_id'];

	$fileName = "slip-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	$query   = "SELECT saved_transfers.id, saved_transfers.trans_no, saved_transfers.item_id,
				saved_transfers.qty, saved_transfers.published, lFrom.short_desc AS lFrom,
				lTo.short_desc AS lTo, items_size.desc AS size
				FROM saved_transfers
				JOIN locations AS lFrom ON saved_transfers.from_wrhs_id = lFrom.loc_id
				JOIN locations AS lTo   ON saved_transfers.to_wrhs_id   = lTo.loc_id
				JOIN items_size         ON saved_transfers.size_id      = items_size.size_id
				WHERE saved_transfers.trans_no = '".$slipNo."'";
	
	$result   = $db->query($query);
	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "SLIP,FROM,TO,ITEM,SIZE,QTY,STATUS" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= $row['trans_no']  . ',';
		$csv .= $row['lFrom']  . ',';
		$csv .= $row['lTo']  . ',';
		$csv .= $row['item_id']  . ',';
		$csv .= $row['size']  . ',';
		$csv .= $row['qty'] . ',';
		if ($row['published'] == 0) {
			$csv .= "REMOVED\n";
		} else {
			$csv .= "ACTIVE\n";
		}
	}

	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}

if (!empty($_POST['action']) && $_POST['action'] == "getTrackingComments")
{
	$transTrackingId      = $_POST['transTrackingId'];
	$getTrackingComments  = $db->query("SELECT tracking_comments.comment, users.username FROM tracking_comments
										JOIN users ON users.user_id = tracking_comments.user_id
										WHERE tracking_comments.trans_tracking_id = '".$transTrackingId."'");

	if ($db->num_rows($getTrackingComments) > 0) {
		$data = '';
		while ($rowTrackingComments = $db->fetch_array($getTrackingComments)) {
			$data .= $rowTrackingComments['username'] . ': ' . $rowTrackingComments['comment'] . '<br>';
		}
	} else {
		$data = 'There\'s No Comments';
	}
	echo $data;
}

flush();