<?php
    require_once ("_inc/header2.php");

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

    $getDesc     = $db->query("SELECT * FROM items_desc");
    $getSeason   = $db->query("SELECT * FROM items_season");
    $getIntlCode = $db->query("SELECT * FROM items_intl_code");
    $getGender   = $db->query("SELECT * FROM items_gender");
    $getColor   = $db->query("SELECT * FROM items_color");
    while ($rowGetColor = $db->fetch_array($getColor)) {
        $colors[] = $rowGetColor;
    }
    $getDept     = $db->query("SELECT * FROM items_dept");
    $getSubDept  = $db->query("SELECT * FROM items_sub_dept");
    $getVendor   = $db->query("SELECT * FROM items_vendor");
    $getAttr     = $db->query("SELECT * FROM items_attr");

    $data  = '<div id="addItemWrapper"><table id="tblAddItem">';

    $data .= '<tr>';
    $data .= '<th>Item#</th><td><input type="text" name="item_id" value=""></td>';
    $data .= '</tr>';
    $data .= '<tr>';
    $data .= '<th>Name</th><td><input type="text" name="item_name" value=""></td>';
    $data .= '</tr>';

    $data .= '<tr><th>Description</th><td><select name="desc_id">';
    while ($rowGetDesc = $db->fetch_array($getDesc)) {
        $selected = "";
        $data .= '<option value="'.$rowGetDesc['desc_id'].'" '.$selected.'>'.$rowGetDesc['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Season</th><td><select name="season_id">';
    while ($rowGetSeason = $db->fetch_array($getSeason)) {
        $selected = "";
        $data .= '<option value="'.$rowGetSeason['season_id'].'" '.$selected.'>'.$rowGetSeason['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Intl Code</th><td><select name="intl_code_id">';
    while ($rowGetIntlCode = $db->fetch_array($getIntlCode)) {
        $selected = "";
        $data .= '<option value="'.$rowGetIntlCode['intl_code_id'].'" '.$selected.'>'.$rowGetIntlCode['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Gender</th><td><select name="gender_id">';
    while ($rowGender = $db->fetch_array($getGender)) {
        $selected = "";
        $data .= '<option value="'.$rowGender['gender_id'].'" '.$selected.'>'.$rowGender['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Color 1</th><td><select name="color1_id">';
    foreach ($colors as $key => $value) {
        $selected = "";
        $data .= '<option value="'.$value['color_id'].'" '.$selected.'>'.$value['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Color 2</th><td><select name="color2_id">';
    foreach ($colors as $key => $value) {
        $selected = "";
        $data .= '<option value="'.$value['color_id'].'" '.$selected.'>'.$value['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Color 3</th><td><select name="color3_id">';
    foreach ($colors as $key => $value) {
        $selected = "";
        $data .= '<option value="'.$value['color_id'].'" '.$selected.'>'.$value['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Color 4</th><td><select name="color4_id">';
    foreach ($colors as $key => $value) {
        $selected = "";
        $data .= '<option value="'.$value['color_id'].'" '.$selected.'>'.$value['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Color 5</th><td><select name="color5_id">';
    foreach ($colors as $key => $value) {
        $selected = "";
        $data .= '<option value="'.$value['color_id'].'" '.$selected.'>'.$value['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Department</th><td><select name="dept_id">';
    while ($rowGetDept = $db->fetch_array($getDept)) {
        $selected = "";
        $data .= '<option value="'.$rowGetDept['dept_id'].'" '.$selected.'>'.$rowGetDept['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Sub Department</th><td><select name="sub_dept_id">';
    while ($rowGetSubDept = $db->fetch_array($getSubDept)) {
        $selected = "";
        $data .= '<option value="'.$rowGetSubDept['sub_dept_id'].'" '.$selected.'>'.$rowGetSubDept['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Vendor</th><td><select name="vend_id">';
    while ($rowGetVendor = $db->fetch_array($getVendor)) {
        $selected = "";
        $data .= '<option value="'.$rowGetVendor['vend_id'].'" '.$selected.'>'.$rowGetVendor['long_desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr><th>Attribute</th><td><select name="attr_id">';
    while ($rowGetAttr= $db->fetch_array($getAttr)) {
        $selected = "";
        $data .= '<option value="'.$rowGetAttr['attr_id'].'" '.$selected.'>'.$rowGetAttr['desc'].'</option>';
    }
    $data .= '</select></td></tr>';

    $data .= '<tr>';
    $data .= '<th>MSRP</th><td><input type="text" name="msrp" value=""></td>';
    $data .= '</tr>';
    $data .= '<tr>';
    $data .= '<th>RTP</th><td><input type="text" name="rtp" value=""></td>';
    $data .= '</tr>';
    $data .= '<tr>';
    $data .= '<th>Cost</th><td><input type="text" name="item_cost" value=""></td>';
    $data .= '</tr>';
    $data .= '<tr>';
    $data .= '<th>UPC</th><td><input type="text" name="upc" value=""></td>';
    $data .= '</tr>';
    $data .= '<tr><td colspan="2"><input type="button" name="btnAdd" id="btnAdd" value="Add"></td></tr>';
    $data .= '</table></div>';

?>

<h1>Add Item Window</h1>
<br><br>
<?php
    if(!empty($data)) echo $data;
    require_once ("_inc/footer.php");
?>

<script type="text/javascript">
    $("#tblAddItem").on("click", "#btnAdd", function(){

        var x        = $("#tblAddItem input").serializeArray();
        var y        = $("#tblAddItem select").serializeArray();
        var itemId   = $("[name=item_id]").val();
        var itemName = $("[name=item_name]").val();
        var msrp     = $("[name=msrp]").val();
        var rtp      = $("[name=rtp]").val();
        var cost     = $("[name=cost]").val();
        var upc      = $("[name=upc]").val();
        var data     = $.merge(x, y);

        if (itemId == "" || itemName == "" || msrp == "" || rtp == "" || cost == "" || upc == "")
        {
            alert("Please complete all fields!");
        } else {
            $.ajax({
                url: "_inc/ajaxCalls.php",
                data: {"action":"addItem", "data": data, "itemId": itemId},
                type: "post"
            }).done(function(html) {
                if (html == "error") {
                    alert("Item " + itemId + " already exist!");
                } else {
                    alert('Done!');
                }
            });
        }
    });
</script>