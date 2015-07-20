<?php
    require_once ("_inc/header2.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

    $getAttr     = $db->query("SELECT * FROM items_attr");

    $data  = '<div id="addNewWrapper">';
    $data .= '<input type="button" id="addNewRec" value="Add New"><br><br>';
    $data .= '<table id="tblAddNew">';

    $data .= '<tr>';
    $data .= '<th>Id</th><th>Desc</th><th>Ship</th>';
    $data .= '</tr>';
    while ($rowGetAttr = $db->fetch_array($getAttr)) {
        $data .= '<tr>';
        $data .= '<td>'.$rowGetAttr['attr_id'].'</td><td><span class="modify" uTbl="items_attr" tblCol="attr_id" uTblCol="desc" rowId="'.$rowGetAttr['attr_id'].'">'.$rowGetAttr['desc'].'</span></td><td><span class="modify" uTbl="items_attr" tblCol="attr_id" uTblCol="ship" rowId="'.$rowGetAttr['attr_id'].'">'.$rowGetAttr['ship'].'</span></td>';
        $data .= '</tr>';
    }
    $data .= '</table></div>';

?>

<h1>Add Attribute Window</h1>
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
                <td><input type="text" class="addNewVal" tblCol="desc" tbl="items_attr"></td>
            </tr>
            <tr>
                <td><label>Ship:</label></td>
                <td><input type="text" class="addNewVal" tblCol="ship" tbl="items_attr"></td>
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