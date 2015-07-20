<?php
	require_once("_inc/header.php");
	include_once("_inc/xlsxwriter.class.php");
	ini_set('display_errors', 1);
?>

<h1>Full Report Window</h1>

<?php
$locId       = $_SESSION['loc_id'];
$allData     = $db->query('SELECT DISTINCT(`data`)    AS `data` FROM full_report');
$allStores   = $db->query('SELECT DISTINCT(`store`)   AS `data` FROM full_report');
$allYears    = $db->query('SELECT DISTINCT(`year`)    AS `data` FROM full_report');
$allMonths   = $db->query('SELECT DISTINCT(`month`)   AS `data` FROM full_report');
$allDepts    = $db->query('SELECT DISTINCT(`dept`)    AS `data` FROM full_report');
$allSubDepts = $db->query('SELECT DISTINCT(`subdept`) AS `data` FROM full_report');
$allGenders  = $db->query('SELECT DISTINCT(`gend`)    AS `data` FROM full_report');
$allAttrs    = $db->query('SELECT DISTINCT(`attr`)    AS `data` FROM full_report');
$allVends    = $db->query('SELECT DISTINCT(`vend`)    AS `data` FROM full_report');
$allSeasons  = $db->query('SELECT DISTINCT(`season`)  AS `data` FROM full_report');

if (isset($_GET['btnSubmit']))
{
	$query  = "SELECT *
			   FROM full_report";

	$condition = "WHERE";

	if (array_search("All", $_GET['selData']) === false)
	{
		$getAllData     = implode("','", $_GET['selData']);
		$query         .= " ".$condition." `data` IN ('{$getAllData}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selStore']) === false)
	{
		$getAllStores   = implode("','", $_GET['selStore']);
		$query         .= " ".$condition." `store` IN ('{$getAllStores}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selYear']) === false)
	{
		$getAllYears    = implode("','", $_GET['selYear']);
		$query         .= " ".$condition." `year` IN ('{$getAllYears}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selMonth']) === false)
	{
		$getAllMonths   = implode("','", $_GET['selMonth']);
		$query         .= " ".$condition." `month` IN ('{$getAllMonths}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selDept']) === false)
	{
		$getAllDepts    = implode("','", $_GET['selDept']);
		$query         .= " ".$condition." `dept` IN ('{$getAllDepts}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selSubDept']) === false)
	{
		$getAllSubDepts = implode("','", $_GET['selSubDept']);
		$query         .= " ".$condition." `subdept` IN ('{$getAllSubDepts}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selGend']) === false)
	{
		$getAllGends    = implode("','", $_GET['selGend']);
		$query         .= " ".$condition." `gend` IN ('{$getAllGends}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selAttr']) === false)
	{
		$getAllAttrs    = implode("','", $_GET['selAttr']);
		$query         .= " ".$condition." `attr` IN ('{$getAllAttrs}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selVend']) === false)
	{
		$getAllVends    = implode("','", $_GET['selVend']);
		$query         .= " ".$condition." `vend` IN ('{$getAllVends}')";
		$condition      = "AND";
	}
	if (array_search("All", $_GET['selSeason']) === false)
	{
		$getAllSeasons  = implode("','", $_GET['selSeason']);
		$query         .= " ".$condition." `season` IN ('{$getAllSeasons}')";
		$condition      = "AND";
	}

	$header = array(
	    'DATA'=>'string',
	    'STORE'=>'string',
	    'FROM'=>'string',
	    'TO'=>'string',
	    'DATE'=>'date',
	    'YEAR'=>'integer',
	    'MONTH'=>'integer',
	    'ITEM'=>'integer',
	    'SIZE'=>'string',
	    'DEPT'=>'string',
	    'SUBDEPT'=>'string',
	    'DESC1'=>'string',
	    'DESC2'=>'string',
	    'GENDER'=>'string',
	    'ATTR'=>'string',
	    'VEND'=>'string',
	    'SEASON'=>'string',
	    'INVO#'=>'integer',
	    'TRANS#'=>'integer',
	    'QTY'=>'integer',
	    'MSRP'=>'money',
	    'RTP'=>'money',
	    'COST'=>'money',
	    'T.RTP'=>'money',
	    'T.COST'=>'money',
	    'PAYMENT'=>'string',
	    'STATUS'=>'string'
	);

	$result  = $db->query($query);

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		unset($row['id']);
		unset($row[0]);
		$row = array_values($row);
		$data[] = $row;
	}

	$fileName = "fullReport-" . date('m-d-Y-H-i-s') . ".xlsx";
	$path = "_uploads/" . $fileName;

	$writer = new XLSXWriter();
	$writer->setAuthor('Said Abdul Aziem');
	$writer->writeSheet($data,'Sheet1',$header);
	//$writer->writeToStdOut();
	$writer->writeToFile($path);
	echo '<script>window.open("'.$path.'","Download");</script>';
	//echo $writer->writeToString();
	//exit(0);
}

?>

<form name="frmStockReport" id="frmStockReport" method="GET" action="#"  enctype="multipart/form-data">

	<table>
		<tr>
			<th>Data</th>
			<th>Store</th>
			<th>Year</th>
			<th>Month</th>
			<th>Department</th>
			<th>Sub Department</th>
			<th>Gender</th>
			<th>Attribute</th>
			<th>Vendor</th>
			<th>Season</th>
		</tr>
		<tr>
			<td>
				<select name="selData[]" multiple="multiple">
					<option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allData)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>
				<select name="selStore[]" multiple="multiple">
					<option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allStores)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>
				<select name="selYear[]" multiple="multiple">
					<option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allYears)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>
				<select name="selMonth[]" multiple="multiple">
					<option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allMonths)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>
                <select name="selDept[]" multiple="multiple">
                    <option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allDepts)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
                </select>
            </td>
			<td>
                <select name="selSubDept[]" multiple="multiple">
                    <option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allSubDepts)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
                </select>
            </td>
			<td>
				<select name="selGend[]" multiple="multiple">
					<option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allGenders)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>
				<select name="selAttr[]" multiple="multiple">
					<option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allAttrs)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>
				<select name="selVend[]" multiple="multiple">
					<option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allVends)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
				</select>
			</td>
			<td>
				<select name="selSeason[]" multiple="multiple">
					<option value="All" selected>All</option>
					<?php while($row = $db->fetch_array($allSeasons)): ?>
					<option value="<?php echo $row['data'] ?>"><?php echo $row['data'] ?></option>
					<?php endwhile; ?>
				</select>
			</td>
		</tr>
	</table>

	<br></br>

	<input type="submit" name="btnSubmit" value="Export">
</form>


<?php

require_once ("_inc/footer.php");

?>