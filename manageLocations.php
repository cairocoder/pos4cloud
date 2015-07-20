<?php
    require_once ("_inc/header2.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

    $getLocation = $db->query("SELECT * FROM locations");

    $data  = '<form id="addNewWrapper" method="post">';
    $data .= '<table id="tblAddNew">';

    $data .= '<tr>';
    $data .= '<th>Id</th><th>Desc</th><th>Short Desc</th><th>Publish</th><th>UnPublish</th>';
    $data .= '</tr>';
    while ($rowGetLocation = $db->fetch_array($getLocation)) {
        if ($rowGetLocation['published'] == 1) {
            $checkedp = "checked";
            $checkedu = "";
        } else {
            $checkedp = "";
            $checkedu = "checked";
        }
        $data .= '<tr>';
        $data .= '<td>'.$rowGetLocation['loc_id'].'</td><td>'.$rowGetLocation['desc'].'</td><td>'.$rowGetLocation['short_desc'].'</td>';
        $data .= '<td><input type="radio" name="'.$rowGetLocation['loc_id'].'" '.$checkedp.' value="1"></td>';
        $data .= '<td><input type="radio" name="'.$rowGetLocation['loc_id'].'" '.$checkedu.' value="0"></td>';
        $data .= '</tr>';
    }
    $data .= '<tr>';
    $data .= '<td colspan="5"><input type="submit" name="btnSubmit" value="Update"></td>';
    $data .= '</tr>';
    $data .= '</table></form>';

    if (!empty($_POST['btnSubmit'])) {
        array_pop($_POST);
        foreach ($_POST as $key => $value) {
            $db->query("UPDATE `locations` SET `published` = '{$value}' WHERE `loc_id` = '{$key}'");
        }
        echo '<script>window.location.href = "manageLocations.php";</script>';
    }

?>
<style>
    #tblAddNew {
        text-align: center;
    }
    #tblAddNew td, #tblAddNew th {
        padding: 5px;
    }
</style>
<h1>Manage Location Window</h1>
<br><br>

<?php
    if(!empty($data)) echo $data;
?>

<?php
    require_once ("_inc/footer.php");
?>