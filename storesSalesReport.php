<?php require_once ("_inc/header2.php"); 

	if ($_SESSION['user_type'] != "sadmin" && $_SESSION['user_type'] != "analyst") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

?>
<style type="text/css">
	.component {
		width: 100%;
	}
	.red {
		color: red;
		font-weight: bold;
	}
	table {
		font-size: 14px;
	}
	th {
	    background-color: #cccccc;
	    color: #000;
	    font-weight: bold;
	    white-space: nowrap;
	    vertical-align: middle;
	    padding: 5px;
	    border: 1px solid #000;
	}
	th:nth-child(1),th:nth-child(2),th:nth-child(3){
		min-width: 70px;
	}
	th a {
		color: #fff;
		text-decoration: none;
	}
	tbody th {
		background-color: #cccccc;
		border: 1px solid #000;
	}
	tbody tr:nth-child(2n) {
	    transition: all .125s ease-in-out;
	}
	tbody tr:hover {
	    background-color: rgba(129,208,177,.3);
	}
	td {
		padding: 5px;
		text-align: center;
		border: 1px solid #000;
	}
</style>
<h1>Stores Sales Report</h1>
<?php
$locId       = $_SESSION['loc_id'];
$allYears    = userClass::getAllYears();
$allBranches = userClass::getAllBranchesShort();
if (isset($_GET['btnSubmit']) && !empty($_GET['selYear']))
{
	if ($_GET['selViewType'] == 1) {
		$query   = "SELECT SUM(`net_value`) AS `net_value`, `date`, `loc_id`
					FROM invoice_header";
	} else {
		$query   = "SELECT SUM(`net_value`) AS `net_value`, CONCAT(YEAR(`date`), '-', MONTH(`date`)) AS `date`, `loc_id`
					FROM invoice_header";
	}
	$query2  = "SELECT SUM(`net_value`) AS `net_value`, `loc_id`
				FROM invoice_header";
	$getYear  = implode("','", $_GET['selYear']);
	$getYear2 = $getYear - 1;
	$query   .= " WHERE YEAR(`date`) IN ('{$getYear}')";
	$query2  .= " WHERE YEAR(`date`) IN ('{$getYear2}')";
	$numDays  = 0;
	$getMonth = null;
	if (array_search("All", $_GET['selMonth']) === false)
	{
		$getMonth = implode("','", $_GET['selMonth']);
		$query   .= " AND MONTH(`date`) IN ('{$getMonth}')";
		$query2  .= " AND MONTH(`date`) IN ('{$getMonth}')";
		foreach ($_GET['selMonth'] as $oneMonth) {
			$numDays += cal_days_in_month(CAL_GREGORIAN, $oneMonth, $getYear);
		}
		echo '<span style="display:none" id="numDays">'.$numDays.'</span>';
	}
	if (array_search("All", $_GET['selDay']) === false) {
		$getDay  = implode("','", $_GET['selDay']);
		$query  .= " AND DAY(`date`) IN ('{$getDay}')";
		$query2 .= " AND DAY(`date`) IN ('{$getDay}')";
	}
	if (array_search("All", $_GET['selBranch']) === false)
	{
		foreach ($_GET['selBranch'] as $keyxx => $valuexx) {
			$branches[] = userClass::getBranchShortName($valuexx);
		}
		$getAllBranches = implode(",", $_GET['selBranch']);
		$query  .= " AND `loc_id` IN ({$getAllBranches})";
		$query2 .= " AND `loc_id` IN ({$getAllBranches})";
	} else {
		$branches = userClass::getAllBranchesShort();
	}
	if ($_GET['selViewType'] == 1) {
		$query  .= " GROUP BY `loc_id`, `date`";
	} else {
		$query  .= " GROUP BY `loc_id`, YEAR(`date`), MONTH(`date`)";
	}
	//echo $query;
	$query2 .= " GROUP BY `loc_id`";
	$getAllItems   = $db->query($query);
	$getAllItems2  = $db->query($query2);
	while ($rowAllItems = $db->fetch_array($getAllItems))
	{
		$allValues[]  = $rowAllItems;
		$locations[]  = $rowAllItems['loc_id'];
		$allDate[]    = $rowAllItems['date'];
	}
	if (mysql_num_rows($getAllItems2) > 0) {
		while ($rowAllItems2 = $db->fetch_array($getAllItems2))
		{
			$allValues2[]  = $rowAllItems2;
		}
	} else {
		$allValues2 = array();
	}
	$uLocations     = array_unique($locations);
	$uAllDate       = array_unique($allDate);
	$dataTable      = '<thead>';
	$dataTable     .= '<tr>';
	$dataTable     .= '<th>Date</th>';
	foreach ($uLocations as $valuex) {
		$dataTable .= '<th>'.userClass::getBranchShortName($valuex).'</th>';
		$totalCol[$valuex]  = 0;
	}
	$dataTable     .= '<th>Total</th>';
	if ($_GET['selViewType'] == 1) {
		$dataTable .= '<th>Growth</th>';
	} else {
		$dataTable .= '<th>Growth</th>';
	}
	$dataTable     .= '</tr>';
	$dataTable     .= '</thead>';
	$dataTable     .= '<tbody>';
	foreach ($uAllDate as $oneDate) {
		$dataTable .= '<tr>';
		$dataTable .= '<th>'.$oneDate.'</th>';
		$totalRow   = 0;
		foreach ($uLocations as $oneLocation) {
			$flag   = 0;
			foreach ($allValues as $oneArray) {
				if ($oneDate == $oneArray['date'] && $oneLocation == $oneArray['loc_id']) {
					$dataTable .= '<td>'.number_format($oneArray['net_value']).'</td>';
					$totalRow  += $oneArray['net_value'];
					$totalCol[$oneLocation] += $oneArray['net_value'];
					$flag = 1;
				}
			}
			if ($flag == 0) {
				$dataTable .= '<td></td>';
			}
		}
		$dataTable .= '<td class="red"><span class="totalRow">'.number_format($totalRow).'</span></td>';
		if ($_GET['selViewType'] == 1) {
			$dataTable .= '<td class="red"><span class="dayPerc"></span></td>';
		} else {
			$dataTable .= '<td class="red"><span class="growthRow"></span></td>';
		}
		$dataTable .= '</tr>';
	}
	//Get Total
	$dataTable .= '<tr style="background-color: #FFFF00;">';
	$dataTable .= '<th style="background-color: #FFFF00;color:#000;">Total</th>';
	foreach ($totalCol as $oneCol) {
		$dataTable .= '<td class="red"><span class="total">'.number_format($oneCol).'</span></td>';
	}
	$dataTable .= '<td class="red"><span id="totalFinal">'.number_format(array_sum($totalCol)).'</span></td>';
	if ($_GET['selViewType'] == 1) {
		$dataTable .= '<td class="red"><span id="totalDayPerc"></span></td>';
	} else {
		$dataTable .= '<td class="red"><span id="totalGrowthRow"></span></td>';
	}
	$dataTable .= '</tr>';
	if ($_GET['selViewType'] == 1) {
		//Get Target
		$dataTable .= '<tr style="background-color: #ff99cc;">';
		$dataTable .= '<th style="background-color: #ff99cc; color:#000;">Target</th>';
		foreach ($uLocations as $value) {
			//Check if Target is already SET
			$chkQuery    = "SELECT SUM(`desc`) AS `desc` FROM `target`
							WHERE `loc_id` = '{$value}'
							AND   `year` IN ('{$getYear}')";
			if ($getMonth)	$chkQuery  .= " AND `month` IN ('{$getMonth}')";
			$chkTarget   = $db->query($chkQuery);
			if (mysql_num_rows($chkTarget) > 0) {
				$rowTraget  = $db->fetch_array($chkTarget);
				$dataTable .= '<td><span class="target">'.number_format($rowTraget['desc']).'</span></td>';
			} else {
				$dataTable .= '<td><span class="target">0</span></td>';
			}
		}
		$dataTable .= '<td><span class="totalTarget" style="font-weight:bold"></span></td>';
		$dataTable .= '<td style="background-color: #000;"></td>';
		$dataTable .= '</tr>';
		//Get Remain
		$dataTable .= '<tr style="background-color: #ffffff;">';
		$dataTable .= '<th style="background-color: #ffffff; color:#000;">Remain</th>';
		foreach ($uLocations as $value) {
			$dataTable .= '<td><span class="remain"></span></td>';
		}
		$dataTable .= '<td><span class="totalRemain" style="font-weight:bold"></span></td>';
		$dataTable .= '<td style="background-color: #000;"></td>';
		$dataTable .= '</tr>';
		//Get % Store/Sales
		$dataTable .= '<tr style="background-color: #ff99cc;">';
		$dataTable .= '<th style="background-color: #ff99cc; color:#000;">% Store/Sales</th>';
		foreach ($uLocations as $value) {
			$dataTable .= '<td><span class="storeSalesPerc"></span></td>';
		}
		$dataTable .= '<td><span class="totalStoreSalesPerc" style="font-weight:bold"></span></td>';
		$dataTable .= '<td style="background-color: #000;"></td>';
		$dataTable .= '</tr>';
		//Get % Target/Day
		$dataTable .= '<tr style="background-color: #FFFF00;">';
		$dataTable .= '<th style="background-color: #FFFF00; color:#000;">% Target/Day</th>';
		foreach ($uLocations as $value) {
			$dataTable .= '<td><span class="targetDayPerc"></span></td>';
		}
		$dataTable .= '<td><span class="totalTargetDayPerc" style="font-weight:bold"></span></td>';
		$dataTable .= '<td style="background-color: #000;"></td>';
		$dataTable .= '</tr>';
		//Get Last Year Sales
		$dataTable .= '<tr style="background-color: #00ff00;">';
		$dataTable .= '<th style="background-color: #00ff00; color:#000;">Last Year Sales</th>';
		foreach ($uLocations as $oneLocation) {
			$flag   = 0;
			foreach ($allValues2 as $oneArray2) {
				if (in_array($oneLocation, $oneArray2)) {
					$dataTable .= '<td><span class="lastYearSales">'.number_format($oneArray2['net_value']).'</span></td>';
					$flag = 1;
				}
			}
			if ($flag == 0) {
				$dataTable .= '<td><span class="lastYearSales">0</span></td>';
			}
		}
		$dataTable .= '<td><span class="totalLastYearSales" style="font-weight:bold"></span></td>';
		$dataTable .= '<td style="background-color: #000;"></td>';
		$dataTable .= '</tr>';
		//Get % Tagrget/Month
		$dataTable .= '<tr style="background-color: #95b3d7;">';
		$dataTable .= '<th style="background-color: #95b3d7; color:#000;">% Tagrget/Month</th>';
		foreach ($uLocations as $value) {
			$dataTable .= '<td><span class="targetMonthPerc"></span></td>';
		}
		$dataTable .= '<td><span class="totalTargetMonthPerc" style="font-weight:bold"></span></td>';
		$dataTable .= '<td style="background-color: #000;"></td>';
		$dataTable .= '</tr>';
		//Get Growth
		$dataTable .= '<tr style="background-color: #00ff00;">';
		$dataTable .= '<th style="background-color: #00ff00; color:#000;">Growth</th>';
		foreach ($uLocations as $value) {
			$dataTable .= '<td><span class="growth"></span></td>';
		}
		$dataTable .= '<td><span class="totalGrowth" style="font-weight:bold"></span></td>';
		$dataTable .= '<td style="background-color: #000;"></td>';
		$dataTable .= '</tr>';
		//Get Project EOM
		$dataTable .= '<tr style="background-color: #ff9900;">';
		$dataTable .= '<th style="background-color: #ff9900; color:#000;">Project EOM</th>';
		foreach ($uLocations as $value) {
			$dataTable .= '<td><span class="projectEOM"></span></td>';
		}
		$dataTable .= '<td><span class="totalProjectEOM" style="font-weight:bold"></span></td>';
		$dataTable .= '<td style="background-color: #000;"></td>';
		$dataTable .= '</tr>';
	}
	$dataTable .= '</tbody>';
} else if (isset($_GET['btnSubmit']) && empty($_GET['selYear'])) {
	$dataTable = '<p style="text-align:center;color:red;">Please select a date first !</p>';
}
?>
<form name="frmStoresSalesReport" id="frmStoresSalesReport" method="GET" action="#"  enctype="multipart/form-data">
	<table>
		<tr>
			<th>Year</th>
			<th>Month</th>
			<th>Day</th>
			<th>Branch</th>
		</tr>
		<tr>
			<td>
				<select name="selYear[]" id="selYear2" multiple="multiple">
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
				<select name="selDay[]" id="selDay2" multiple="multiple">
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
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>
					<option value="22">22</option>
					<option value="23">23</option>
					<option value="24">24</option>
					<option value="25">25</option>
					<option value="26">26</option>
					<option value="27">27</option>
					<option value="28">28</option>
					<option value="29">29</option>
					<option value="30">30</option>
					<option value="31">31</option>
				</select>
			</td>
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
		</tr>
		<tr>
			<th colspan="2">View Type: </th>
			<td><input type="radio" name="selViewType" Value="1" checked>Daily</td>
			<td><input type="radio" name="selViewType" Value="2">Monthly</td>
		</tr>
	</table>
	<br><br>
	<input type="submit" name="btnSubmit" value="Submit">
</form>
<div class="component">
	<table id="tblTransferFull">
		<?php if (isset($dataTable)) echo $dataTable ?>
	</table>
</div>
<?php
require_once ("_inc/footer.php");
?>
<script src="_js/numeral.min.js"></script>
<?php
	if (isset($dataTable)) {
		echo '<script>
			var rowCount     = $("#tblTransferFull tr").length - 10;
			var numDays      = $("#numDays").text();
			var target       = new Array();
			var total        = new Array();
			var lySales      = new Array();
			var sumAll       = 0;
			var totalTarget  = 0;
			var totalRemain  = 0;
			var totalSSPerc  = 0;
			var totalTDPerc  = 0;
			var totalLYSales = 0;
			var totalTMPerc  = 0;
			var totalGrowth  = 0;
			var totalPEOM    = 0;
			totalGrowthRow   = 0;
			$(".dayPerc").each(function(){
			    var totalRow = parseInt($(this).parent().parent().find(".totalRow").text());
				var totalFinal = parseInt($("#totalFinal").text());
				var dayPerc = parseInt((totalRow * 100) / totalFinal);
				sumAll += dayPerc;
				$(this).text(numeral(dayPerc).format("0,0"));
			});
			$("#totalDayPerc").text(numeral(sumAll).format("0,0"));
			$(".target").each(function(){
			    target.push(parseInt($(this).text().replace(/,/g, "")));
			    totalTarget += parseInt($(this).text().replace(/,/g, ""));
			});
			$(".totalTarget").text(numeral(totalTarget).format("0,0"));
			$(".total").each(function(){
			    total.push(parseInt($(this).text().replace(/,/g, "")));
			});
			$(".lastYearSales").each(function(){
			    lySales.push(parseInt($(this).text().replace(/,/g, "")));
			    totalLYSales += parseInt($(this).text().replace(/,/g, ""));
			});
			$(".totalLastYearSales").text(numeral(totalLYSales).format("0,0"));
			$(".remain").parent().each(function(){
			    var currRemain = $(this).index() - 1;
			    var remain     = target[currRemain] - total[currRemain];
			    totalRemain   += remain;
			    remain         = numeral(remain).format("0,0");
			    $(this).find(".remain").text(remain);
			});
			$(".totalRemain").text(numeral(totalRemain).format("0,0"));
			$(".storeSalesPerc").parent().each(function(){
			    var currSSPerc = $(this).index() - 1;
			    var totalFinal = parseInt($("#totalFinal").text().replace(/,/g, ""));
			    var sSPerc     = total[currSSPerc] / totalFinal;
			    totalSSPerc   += sSPerc;
			    sSPerc         = numeral(sSPerc).format("0%");
			    $(this).find(".storeSalesPerc").text(sSPerc);
			});
			$(".totalStoreSalesPerc").text(numeral(totalSSPerc).format(",0%"));
			$(".targetDayPerc").parent().each(function(){
			    var currTDPerc = $(this).index() - 1;
			    var tDPerc     = ((total[currTDPerc] / rowCount) * numDays) / target[currTDPerc];
			    totalTDPerc   += tDPerc;
			    tDPerc         = numeral(tDPerc).format("0%");
			    $(this).text(tDPerc);
			});
			$(".totalTargetDayPerc").text(numeral(totalTDPerc).format(",0%"));
			$(".targetMonthPerc").parent().each(function(){
			    var currTMPerc = $(this).index() - 1;
			    var tMPerc     = total[currTMPerc] / target[currTMPerc];
			    totalTMPerc   += tMPerc;
			    tMPerc         = numeral(tMPerc).format("0%");
			    $(this).text(tMPerc);
			});
			$(".totalTargetMonthPerc").text(numeral(totalTMPerc).format(",0%"));
			$(".growth").parent().each(function(){
			    var currGrowth = $(this).index() - 1;
			    var growthPerc = (((total[currGrowth] / rowCount) * numDays) / lySales[currGrowth]);
			    totalGrowth   += growthPerc;
			    growthPerc     = numeral(growthPerc).format("0%");
			    $(this).text(growthPerc);
			});
			$(".totalGrowth").text(numeral(totalGrowth).format(",0%"));
			$(".projectEOM").parent().each(function(){
			    var currPEOM   = $(this).index() - 1;
			    var pEOMperc   = (total[currPEOM] / rowCount) * numDays;
			    totalPEOM     += pEOMperc;
			    pEOMperc       = numeral(pEOMperc).format("0,0");
			    $(this).text(pEOMperc);
			});
			$(".totalProjectEOM").text(numeral(totalPEOM).format("0,0"));
			$(".growthRow").parent().each(function(){
			    var totalRow      = parseInt($(this).parent().find(".totalRow").text().replace(/,/g, ""));
			    var totalFinal    = parseInt($("#totalFinal").text().replace(/,/g, ""));
			    var growthRowPerc = (totalRow * 100) / totalFinal;
			    totalGrowthRow   += growthRowPerc;
			    growthRowPerc     = numeral(growthRowPerc).format("0.00");
			    $(this).find(".growthRow").text(growthRowPerc);
			});
			$("#totalGrowthRow").text(totalGrowthRow);
		</script>';
	}
?>
<script src="_js/jquery.ba-throttle-debounce.min.js"></script>
<script src="_js/jquery.stickyheader3.js"></script>
