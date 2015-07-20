<?php
    require_once ("_inc/header2.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

    $getDesc     = $db->query("SELECT * FROM items_desc");

    $data  = '<div id="addNewWrapper">';
    $data .= '<input type="button" id="addNewRec" value="Add New"><br><br>';
    $data .= '<table id="tblAddNew">';

    $data .= '<tr>';
    $data .= '<th>Id</th><th>Desc</th>';
    $data .= '</tr>';
    while ($rowGetDesc = $db->fetch_array($getDesc)) {
        $data .= '<tr>';
        $data .= '<td>'.$rowGetDesc['desc_id'].'</td><td><span class="modify" uTbl="items_desc" tblCol="desc_id" uTblCol="desc" rowId="'.$rowGetDesc['desc_id'].'">'.$rowGetDesc['desc'].'</span></td>';
        $data .= '</tr>';
    }
    $data .= '</table></div>';

?>

<h1>Add Description Window</h1>
<br><br>

<?php
    if(!empty($data)) echo $data;
?>

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
    <div id='addNew' style='padding:10px; background:#fff;'>
        <table style="text-align:center">
            <tr>
                <td><label>Desc:</label></td>
                <td><input type="text" class="addNewVal" tblCol="desc" tbl="items_desc"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="button" id="btnAddNew" name="btnAddNew" value="Add"></td>
            </tr>
        </table>
    </div>
</div>

<?php
    require_once ("_inc/footer.php");
?>