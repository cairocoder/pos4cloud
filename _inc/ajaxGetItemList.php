<?php

require_once ("dbClass.php");
require_once ("userClass.php");
require_once ("sessionClass.php");

if (!empty($_POST['action']) && $_POST['action'] == "getItemList")
{
	$locId       = $_SESSION['loc_id'];

	$fileName = "itemList-" . userClass::getBranchShortName($locId) . "-" . date('m-d-Y-H-i-s') . ".csv";

	$query   = "SELECT items.item_id, items.item_name, items.msrp, items.rtp, items.item_cost, items_color.desc as color, items_desc.desc as desc2,
				items_gender.desc as gender, items_intl_code.desc as intl_code, items_dept.desc as dept, items_dept.long_desc as long_dept,
				items_sub_dept.desc as sub_dept, items_vendor.long_desc as vendor, items_attr.desc as attribute, items_season.desc as season
				FROM `items`
				LEFT JOIN items_color     ON items_color.color_id         = items.color1_id
				LEFT JOIN items_desc      ON items_desc.desc_id           = items.desc_id
				LEFT JOIN items_gender    ON items_gender.gender_id       = items.gender_id
				LEFT JOIN items_intl_code ON items_intl_code.intl_code_id = items.intl_code_id
				LEFT JOIN items_dept      ON items_dept.dept_id           = items.dept_id
				LEFT JOIN items_sub_dept  ON items_sub_dept.sub_dept_id   = items.sub_dept_id
				LEFT JOIN items_vendor    ON items_vendor.vend_id         = items.vend_id
				LEFT JOIN items_season    ON items_season.season_id       = items.season_id
				LEFT JOIN items_attr      ON items_attr.attr_id           = items.attr_id";

	$result   = $db->query($query);

	$file_handle = fopen("../_uploads/".$fileName, "w");
	fputs($file_handle, b"\xEF\xBB\xBF"); //write utf-8 BOM to file

	$csv = "item_id,item_name,msrp,rtp,cost,color,desc2,gender,intl_code,dept,long_dept,sub_dept,vendor,attribute,season" . "\n";

	while ($row = $db->fetch_array($result))
	{
		$csv .= '"' . $row['item_id'] . '"' . ',';
		$csv .= '"' . $row['item_name'] . '"' . ',';
		$csv .= '"' . $row['msrp'] . '"' . ',';
		$csv .= '"' . $row['rtp'] . '"' . ',';
		$csv .= '"' . $row['item_cost'] . '"' . ',';
		$csv .= '"' . $row['color'] . '"' . ',';
		$csv .= '"' . $row['desc2'] . '"' . ',';
		$csv .= '"' . $row['gender'] . '"' . ',';
		$csv .= '"' . $row['intl_code'] . '"' . ',';
		$csv .= '"' . $row['dept'] . '"' . ',';
		$csv .= '"' . $row['long_dept'] . '"' . ',';
		$csv .= '"' . $row['sub_dept'] . '"' . ',';
		$csv .= '"' . $row['vendor'] . '"' . ',';
		$csv .= '"' . $row['attribute'] . '"' . ',';
		$csv .= '"' . $row['season'] . '"' . "\n";
	}

	fwrite($file_handle, $csv);
	fclose($file_handle);

	echo "_uploads/" . $fileName;
}