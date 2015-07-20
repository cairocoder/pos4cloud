<?php

require_once ("_inc/header2.php");
?>

	<?php
		if (!empty($_GET['err'])) {
			if($_GET['err'] == 1) {
				$errVal = ' Please upload csv file';
			}elseif($_GET['err'] == 2){
				$errVal = $_GET['errVal'] . ' The file must be csv';
			}elseif($_GET['err'] == 3){
				$errVal = $_GET['errVal'] . ' Please follow the template (check the count of the column)';
			}elseif($_GET['err'] == 4){
				$errVal = $_GET['errVal'] . ' Please follow  the template (check columns names)';
			}elseif($_GET['err'] == 5){
				$errVal = $_GET['errVal'] . ' ' . $_GET['lmt'] . ' Can not have repeated values';
			}elseif($_GET['err'] == 6){
				$errVal = $_GET['errVal'] . ' ' . $_GET['lmt'] . ' Can not have empty cells';
			}elseif($_GET['err'] == 7){
				$errVal = $_GET['errVal'] . ' ' . $_GET['lmt'] . ' Already exist in the database';
			}elseif($_GET['err'] == 8){
				$errVal = $_GET['errVal'] . ' ' . $_GET['lmt'] . ' Not exist please add it first';
			}elseif($_GET['err'] == 9){
				$errVal = $_GET['errVal'] . ' Failed to add please try again later';
			}elseif($_GET['err'] == 10){
				$errVal = $_GET['errVal'] . ' Added successfuly';
			}
			$errVal .= '<br>';
		}
	?>
<h1>Add Item Window</h1>
<div id="reportWrapper2">
	<form method="post" action="import.php" enctype="multipart/form-data">
		<label>Upload your .csv file:-</label>
		<input type="file" name="items" id="items" /><br><br>
		<input type="submit" name="upload" id="upload" value="upload" />
	</form>
	<br>
	<a href="items.csv">Download template?</a>
	<br><br>
	<?php
		if (!empty($errVal)) {
			echo "<p style='text-align:center;font-weight:bold;color:red;'>".$errVal."</p>";
		}
	?>
</div>