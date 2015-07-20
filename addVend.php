<?php
    require_once ("_inc/header2.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

    $getVend     = $db->query("SELECT * FROM items_vendor");

    $data  = '<div id="addNewWrapper">';
    $data .= '<input type="button" id="addNewRec" value="Add New"><br><br>';
    $data .= '<table id="tblAddNew">';

    $data .= '<tr>';
    $data .= '<th>Id</th><th>Desc</th><th>Long Desc</th>';
    $data .= '</tr>';
    while ($rowGetVend = $db->fetch_array($getVend)) {
        $data .= '<tr>';
        $data .= '<td>'.$rowGetVend['vend_id'].'</td><td><span class="modify" uTbl="items_vendor" tblCol="vend_id" uTblCol="desc" rowId="'.$rowGetVend['vend_id'].'">'.$rowGetVend['desc'].'</span></td><td><span class="modify" uTbl="items_vendor" tblCol="vend_id" uTblCol="long_desc" rowId="'.$rowGetVend['vend_id'].'">'.$rowGetVend['long_desc'].'</span></td>';
        $data .= '</tr>';
    }
    $data .= '</table></div>';

?>

<h1>Add Vendor Window</h1>
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
                <td><input type="text" class="addNewVal" tblCol="desc" tbl="items_vendor"></td>
            </tr>
            <tr>
                <td><label>Long Desc:</label></td>
                <td><input type="text" class="addNewVal" tblCol="long_desc" tbl="items_vendor"></td>
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