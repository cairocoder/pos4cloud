<?php
    require_once ("_inc/header2.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

    $getSubDept     = $db->query("SELECT * FROM items_sub_dept");

    $data  = '<div id="addNewWrapper">';
    $data .= '<input type="button" id="addNewRec" value="Add New"><br><br>';
    $data .= '<table id="tblAddNew">';

    $data .= '<tr>';
    $data .= '<th>Id</th><th>Desc</th>';
    $data .= '</tr>';
    while ($rowGetSubDept = $db->fetch_array($getSubDept)) {
        $data .= '<tr>';
        $data .= '<td>'.$rowGetSubDept['sub_dept_id'].'</td><td><span class="modify" uTbl="items_sub_dept" tblCol="sub_dept_id" uTblCol="desc" rowId="'.$rowGetSubDept['sub_dept_id'].'">'.$rowGetSubDept['desc'].'</span></td>';
        $data .= '</tr>';
    }
    $data .= '</table></div>';

?>

<h1>Add Sub Department Window</h1>
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
                <td><input type="text" class="addNewVal" tblCol="desc" tbl="items_sub_dept"></td>
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