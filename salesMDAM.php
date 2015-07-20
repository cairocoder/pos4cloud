<?php require_once ("_inc/header2.php"); ?>

<style type="text/css">
	.component {
		width: 100%;
	}
	.red {
		color: red;
		font-weight: bold;
	}
	.bold {
		font-weight: bold;
	}
	table {
		font-size: 14px;
		width: 100%;
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
		background-color: #f2e1c0;
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

<h1>Sales Management Dashboard</h1>

<?php
error_reporting(E_ERROR);
$month       = Date("n");
//$month       = 9;
$year        = Date("Y");
$day         = Date("d");
//$am          = 69;
//$am          = $_SESSION['user_id'];
$userId      = $_SESSION['user_id'];
$numDays     = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$queryAM = "SELECT SUM(invoice_header.net_value) AS `net_value`, locations.loc_id, locations.short_desc,
			employees.emp_id, employees.emp_name, t.target
			FROM invoice_header
			JOIN locations ON invoice_header.loc_id  = locations.loc_id
			JOIN   (SELECT locations.area_manager, locations.loc_id, SUM(target.desc) `target` FROM `target`
					JOIN locations ON target.loc_id  = locations.loc_id
					WHERE target.month = ".$month."
					AND   target.year  = ".$year."
					AND locations.user_id = ".$userId."
					GROUP BY locations.loc_id) t
			ON t.loc_id = locations.loc_id
			JOIN employees ON locations.area_manager = employees.emp_id
			WHERE MONTH(invoice_header.date) = ".$month."
			AND   YEAR(invoice_header.date)  = ".$year."
			AND locations.user_id      = ".$userId."
			GROUP BY locations.loc_id";

$resultAM = $db->query($queryAM);

$dataTable  = '<thead>';
$dataTable .= '<tr>';
$dataTable .= '<th>&nbsp;</th><th colspan="2">MONTH</th><th colspan="2">MONTH TO DATE</th><th colspan="2">DAY</th><th colspan="2">&nbsp;</th>';
$dataTable .= '</tr>';
$dataTable .= '<tr>';
$dataTable .= '<th>&nbsp;</th><th>TARGET</th><th>FORECAST</th><th>TARGET</th><th>ACHIEVED</th><th>TARGET</th><th>ACHIEVED</th><th>ACHIEVED %</th>';
$dataTable .= '</tr>';
$dataTable .= '</thead>';
$dataTable .= '<tbody>';
while ($rowAM = $db->fetch_array($resultAM)) {
	$allNames[] = $rowAM['short_desc'];
	$allIds[]   = $rowAM['loc_id'];
	$target     = $rowAM['target'];
	$tmtd       = $target/$day;
	$netVal     = $rowAM['net_value'];
	//$amtd     = ($netVal/$tmtd)*100;
	$tDay       = $target/$numDays;
	$amtd       = ($netVal/$target)*100;
	$rRate      = $netVal/$day;
	$dataTable .= '<tr>';
	$dataTable .= '<td class="bold empName">'.$rowAM['short_desc'].'</td>';
	$dataTable .= '<td class="tMonth">'.number_format($target).'</td>';
	$dataTable .= '<td class="foreCast">'.number_format($rRate*$numDays).'</td>';
	$dataTable .= '<td class="tMTD">'.number_format($tmtd).'</td>';
	$dataTable .= '<td class="aMTD">'.number_format($netVal).'</td>';
	$dataTable .= '<td class="tDay">'.number_format($tDay).'</td>';
	$dataTable .= '<td class="rRate">'.number_format($rRate).'</td>';
	$dataTable .= '<td class="aIndex">'.number_format($amtd).'%'.'</td>';
	$dataTable .= '</tr>';
}
$dataTable .= '</tbody>';
$dataTable .= '<tfoot>';
$dataTable .= '<tr>';
$dataTable .= '<th>Total</th>';
$dataTable .= '<th><span id="totalTMonth"></span></th>';
$dataTable .= '<th><span id="totalForeCast"></span></th>';
$dataTable .= '<th><span id="totalTMTD"></span></th>';
$dataTable .= '<th><span id="totalAMTD"></span></th>';
$dataTable .= '<th><span id="totalTDay"></span></th>';
$dataTable .= '<th><span id="totalRRate"></span></th>';
$dataTable .= '<th><span id="totalAIndex"></span></th>';
$dataTable .= '</tr>';
$dataTable .= '</tfoot>';
?>
<div class="component">
	<table id="tblSalesMD">
		<?php if (isset($dataTable)) echo $dataTable ?>
	</table>
</div>
<!--Div that will hold the pie chart-->
<div id="chart_div" style="float:left;border:1px solid;margin:8px;padding:5px;"></div>
<!--Div that will hold the column chart-->
<div id="chart_div2" style="float:left;border:1px solid;margin:8px;padding:5px;"></div>
<br style="clear:both"><br><hr><br>
<!--Div that will hold the column chart-->
<div id="tbl_div3" style="border:1px solid;margin:8px;padding:5px;">
	<table style="width:100%">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<?php foreach ($allNames as $value): ?>
					<th><?php echo $value; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Av. Traffic/shop/day</th>
				<?php foreach ($allNames as $value): ?>
					<td>X</td>
				<?php endforeach; ?>
			</tr>
			<tr>
				<th>$Sales/visit</th>
				<?php foreach ($allNames as $value): ?>
					<td>X</td>
				<?php endforeach; ?>
			</tr>
			<tr>
				<th>Av. pcs/invoice</th>
				<?php foreach ($allIds as $id): ?>
					<?php
						$getSumPcs        = $db->query("SELECT SUM(invoice_detail.qty) AS `qty`
														FROM `invoice_detail`
														JOIN `locations` ON locations.loc_id = invoice_detail.loc_id
														JOIN `invoice_header` ON invoice_detail.loc_id = invoice_header.loc_id
														AND   invoice_detail.invo_no = invoice_header.invo_no
														WHERE locations.loc_id = '".$id."'
														AND   MONTH(invoice_header.date) = ".$month."
														AND   YEAR(invoice_header.date)  = ".$year."");
						$rowSumPcs        = $db->fetch_array($getSumPcs);

						$getCountInvoice  = $db->query("SELECT COUNT(`net_value`) `cnet_value`
														FROM `invoice_header`
														JOIN `locations` ON locations.loc_id = invoice_header.loc_id
														WHERE locations.loc_id = '".$id."'
														AND   MONTH(invoice_header.date) = ".$month."
														AND   YEAR(invoice_header.date)  = ".$year."");
						$rowCountInvoice  = $db->fetch_array($getCountInvoice);
					?>
					<td><?php echo number_format($rowSumPcs['qty']/$rowCountInvoice['cnet_value']); ?></td>
				<?php endforeach; ?>
			</tr>
			<tr>
				<th>Av. $/invoice</th>
				<?php foreach ($allIds as $id): ?>
					<?php
						$getPoundInvoice  = $db->query("SELECT COUNT(`net_value`) `cnet_value`, SUM(`net_value`) `snet_value`
														FROM `invoice_header`
														JOIN `locations` ON locations.loc_id = invoice_header.loc_id
														WHERE locations.loc_id = '".$id."'
														AND   MONTH(invoice_header.date) = ".$month."
														AND   YEAR(invoice_header.date)  = ".$year."");
						$rowPoundInvoice  = $db->fetch_array($getPoundInvoice);
					?>
					<td><?php echo number_format($rowPoundInvoice['snet_value']/$rowPoundInvoice['cnet_value']); ?></td>
				<?php endforeach; ?>
			</tr>
			<tr>
				<th>$ Sales/Salesman</th>
				<?php foreach ($allIds as $id): ?>
					<?php
						$getCountEmp      = $db->query("SELECT COUNT(`emp_id`) `cemp_id`
														FROM `employees`
														JOIN `locations` ON locations.loc_id = employees.loc_id
														WHERE locations.loc_id = '".$id."'");
						$rowCountEmp      = $db->fetch_array($getCountEmp);

						$getPoundInvoice  = $db->query("SELECT SUM(`net_value`) `snet_value`
														FROM `invoice_header`
														JOIN `locations` ON locations.loc_id = invoice_header.loc_id
														WHERE locations.loc_id = '".$id."'
														AND   MONTH(invoice_header.date) = ".$month."
														AND   YEAR(invoice_header.date)  = ".$year."");
						$rowPoundInvoice  = $db->fetch_array($getPoundInvoice);
					?>
					<td><?php echo number_format($rowPoundInvoice['snet_value']/$rowCountEmp['cemp_id']); ?></td>
				<?php endforeach; ?>
			</tr>
			<!-- <tr>
				<th>Av. Returns/T.Sales</th>
				<?php foreach ($allIds as $id): ?>
					<?php
						$getSumInvoice    = $db->query("SELECT COUNT(`net_value`) `srnet_value`
														FROM `invoice_header`
														JOIN `locations` ON locations.loc_id = invoice_header.loc_id
														WHERE locations.area_manager = '".$id."'
														AND invoice_header.invo_type = 2");
						$rowSumInvoice    = $db->fetch_array($getSumInvoice);

						$getTotalInvoice  = $db->query("SELECT SUM(`net_value`) `sanet_value`
														FROM `invoice_header`
														JOIN `locations` ON locations.loc_id = invoice_header.loc_id
														WHERE locations.area_manager = '".$id."'");
						$rowTotalInvoice  = $db->fetch_array($getTotalInvoice);
					?>
					<td><?php echo number_format($rowSumInvoice['srnet_value']/$rowTotalInvoice['sanet_value'], 2); ?></td>
				<?php endforeach; ?>
			</tr> -->
			<!-- <tr>
				<th>Av. Szn sls/customer</th>
				<?php foreach ($allNames as $value): ?>
					<td>&nbsp;</td>
				<?php endforeach; ?>
			</tr> -->
			<tr>
				<th>Av. Manager</th>
				<?php foreach ($allNames as $value): ?>
					<td>X</td>
				<?php endforeach; ?>
			</tr>
			<tr>
				<th>Av. Staff Work Hours</th>
				<?php foreach ($allNames as $value): ?>
					<td>X</td>
				<?php endforeach; ?>
			</tr>
		</tbody>
	</table>
</div>

<?php
require_once ("_inc/footer.php");
?>

<!--Load the Numeral Script-->
<script src="_js/numeral.min.js"></script>
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

	// Load the Visualization API and the piechart package.
	google.load('visualization', '1.0', {'packages':['corechart']});

	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);

	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.

	function drawChart() {
		var allNames    = Array();
		var totalTMonth = Array();
		var totalAMTD   = Array();
		var sumTMonth   = 0;
		var sumAMTD     = 0;

		$(".empName").each(function(){
		    allNames.push($(this).text());
		});
		$(".tMonth").each(function(){
		    totalTMonth.push(parseFloat($(this).text().replace(/,/g, "")));
		});
		$(".aMTD").each(function(){
		    totalAMTD.push(parseFloat($(this).text().replace(/,/g, "")));
		});
	    // Create the data table.
		var dataArray = [['Name', 'Acheived']];
	    for (var i = 0; i < allNames.length; i++) {
			dataArray.push([allNames[i], totalAMTD[i]]);
			sumTMonth += totalTMonth[i];
			sumAMTD   += totalAMTD[i];
		}
		dataArray.push(["Remain", (sumTMonth - sumAMTD)]);
		// Create the data table.
	    var data = new google.visualization.arrayToDataTable(dataArray);
	    // Set chart options
	    var options = {'title':'Pie Chart', 'width':470, 'height':500};
	    // Instantiate and draw our chart, passing in some options.
	    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	    chart.draw(data, options);
	    // var components = [
		// 	{ type: 'html', datasource: data },
		// 	{ type: 'csv', datasource: data }
		// ];
		// var container = document.getElementById('toolbar_div');
		// google.visualization.drawToolbar(container, components);
	}
</script>
<script type="text/javascript">

	// Load the Visualization API and the piechart package.
	google.load('visualization', '1.0', {'packages':['corechart']});

	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart2);

	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart2() {
		var allNames = Array();
		var aIndex   = Array();

		$(".empName").each(function(){
		    allNames.push($(this).text());
		});
		$(".aIndex").each(function(){
		    aIndex.push(parseFloat($(this).text().replace(/,/g, "")));
		});

		var dataArray = [['Name', 'Target%', 'Acheived%']];
	 
		for (var i = 0; i < allNames.length; i++) {
			dataArray.push([allNames[i], 100, aIndex[i]]);
		}
		// Create the data table.
	    var data = new google.visualization.arrayToDataTable(dataArray);
	    // Set chart options
	    var options = {'title':'Bar Chart', 'width':470, 'height':500, logScale: true};
	    // Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.BarChart(document.getElementById('chart_div2'));
		chart.draw(data, options);
	}
</script>

<?php
	if (isset($dataTable)) {
		echo '<script>
			var totalTMonth   = 0;
			var totalTDay     = 0;
			var totalTMTD     = 0;
			var totalAMTD     = 0;
			var totalAIndex   = 0;
			var totalRRate    = 0;
			var totalForeCast = 0;

			$(".tMonth").each(function(){
			    totalTMonth += parseFloat($(this).text().replace(/,/g, ""));
			});
			$("#totalTMonth").text(numeral(totalTMonth).format("0,0"));

			$(".tDay").each(function(){
			    totalTDay += parseFloat($(this).text().replace(/,/g, ""));
			});
			$("#totalTDay").text(numeral(totalTDay).format("0,0"));

			$(".tMTD").each(function(){
			    totalTMTD += parseFloat($(this).text().replace(/,/g, ""));
			});
			$("#totalTMTD").text(numeral(totalTMTD).format("0,0"));

			$(".aMTD").each(function(){
			    totalAMTD += parseFloat($(this).text().replace(/,/g, ""));
			});
			$("#totalAMTD").text(numeral(totalAMTD).format("0,0"));

			$(".aIndex").each(function(){
			    totalAIndex += parseFloat($(this).text().replace(/,/g, ""));
			    console.log(parseFloat($(this).text().replace(/,/g, "")));
			});
			$("#totalAIndex").text(Math.round(totalAIndex * 100) / 100 + "%");

			$(".rRate").each(function(){
			    totalRRate += parseFloat($(this).text().replace(/,/g, ""));
			});
			$("#totalRRate").text(numeral(totalRRate).format("0,0"));

			$(".foreCast").each(function(){
			    totalForeCast += parseFloat($(this).text().replace(/,/g, ""));
			});
			$("#totalForeCast").text(numeral(totalForeCast).format("0,0"));
		</script>';
	}
?>
<script src="_js/jquery.ba-throttle-debounce.min.js"></script>
<script src="_js/jquery.stickyheader3.js"></script>
