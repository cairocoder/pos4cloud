<?php
    require_once ("_inc/header2.php");

    if (!empty($_GET['txtItem']))
    {
        $txtItem = $_GET['txtItem'];
        $getItem = $db->query("SELECT * FROM items WHERE item_id='{$txtItem}'");
        if (mysql_num_rows($getItem) > 0)
        {
            $rowItem = $db->fetch_array($getItem);

            $getDesc     = $db->query("SELECT * FROM items_desc");
            $getSeason   = $db->query("SELECT * FROM items_season");
            $getIntlCode = $db->query("SELECT * FROM items_intl_code");
            $getGender   = $db->query("SELECT * FROM items_gender");
            $getColor1   = $db->query("SELECT * FROM items_color");
            $getColor2   = $db->query("SELECT * FROM items_color");
            $getColor3   = $db->query("SELECT * FROM items_color");
            $getColor4   = $db->query("SELECT * FROM items_color");
            $getColor5   = $db->query("SELECT * FROM items_color");
            $getDept     = $db->query("SELECT * FROM items_dept");
            $getSubDept  = $db->query("SELECT * FROM items_sub_dept");
            $getVendor   = $db->query("SELECT * FROM items_vendor");
            $getAttr     = $db->query("SELECT * FROM items_attr");

            $data  = '<div id="editItemWrapper"><table id="tblEditItem">';
            $data .= '<tr>';
            $data .= '<th>Item#</th><td><input type="text" name="item_id" value="'.$rowItem['item_id'].'"></td>';
            $data .= '</tr>';
            $data .= '<tr>';
            $data .= '<th>Name</th><td><input type="text" name="item_name" value="'.$rowItem['item_name'].'"></td>';
            $data .= '</tr>';

            $data .= '<tr><th>Description</th><td><select name="desc_id">';
            while ($rowGetDesc = $db->fetch_array($getDesc)) {
                $selected = "";
                if ($rowItem['desc_id'] == $rowGetDesc['desc_id']) $selected = 'selected="selected"';
                $data .= '<option value="'.$rowGetDesc['desc_id'].'" '.$selected.'>'.$rowGetDesc['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Season</th><td><select name="season_id">';
            while ($rowGetSeason = $db->fetch_array($getSeason)) {
                $selected = "";
                if ($rowItem['season_id'] == $rowGetSeason['season_id']) $selected = 'selected="selected"';
                $data .= '<option value="'.$rowGetSeason['season_id'].'" '.$selected.'>'.$rowGetSeason['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Intl Code</th><td><select name="intl_code_id">';
            while ($rowGetIntlCode = $db->fetch_array($getIntlCode)) {
                $selected = "";
                if ($rowItem['intl_code_id'] == $rowGetIntlCode['intl_code_id']) $selected = 'selected="selected"';
                $data .= '<option value="'.$rowGetIntlCode['intl_code_id'].'" '.$selected.'>'.$rowGetIntlCode['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Gender</th><td><select name="gender_id">';
            while ($rowGender = $db->fetch_array($getGender)) {
                $selected = "";
                if ($rowItem['gender_id'] == $rowGender['gender_id']) $selected = 'selected="selected"';
                $data .= '<option value="'.$rowGender['gender_id'].'" '.$selected.'>'.$rowGender['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Color 1</th><td><select name="color1_id">';
            while ($rowGetColor1 = $db->fetch_array($getColor1)) {
                $selected = "";
                if ($rowItem['color1_id'] == $rowGetColor1['color_id'])
                {
                    $selected = 'selected="selected"';
                }
                $data .= '<option value="'.$rowGetColor1['color_id'].'" '.$selected.'>'.$rowGetColor1['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Color 2</th><td><select name="color2_id">';
            while ($rowGetColor2 = $db->fetch_array($getColor2)) {
                $selected = "";
                if ($rowItem['color2_id'] == $rowGetColor2['color_id'])
                {
                    $selected = 'selected="selected"';
                }
                $data .= '<option value="'.$rowGetColor2['color_id'].'" '.$selected.'>'.$rowGetColor2['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Color 3</th><td><select name="color3_id">';
            while ($rowGetColor3 = $db->fetch_array($getColor3)) {
                $selected = "";
                if ($rowItem['color3_id'] == $rowGetColor3['color_id'])
                {
                    $selected = 'selected="selected"';
                }
                $data .= '<option value="'.$rowGetColor3['color_id'].'" '.$selected.'>'.$rowGetColor3['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Color 4</th><td><select name="color4_id">';
            while ($rowGetColor4 = $db->fetch_array($getColor4)) {
                $selected = "";
                if ($rowItem['color4_id'] == $rowGetColor4['color_id'])
                {
                    $selected = 'selected="selected"';
                }
                $data .= '<option value="'.$rowGetColor4['color_id'].'" '.$selected.'>'.$rowGetColor4['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Color 5</th><td><select name="color5_id">';
            while ($rowGetColor5 = $db->fetch_array($getColor5)) {
                $selected = "";
                if ($rowItem['color5_id'] == $rowGetColor5['color_id'])
                {
                    $selected = 'selected="selected"';
                }
                $data .= '<option value="'.$rowGetColor5['color_id'].'" '.$selected.'>'.$rowGetColor5['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Department</th><td><select name="dept_id">';
            while ($rowGetDept = $db->fetch_array($getDept)) {
                $selected = "";
                if ($rowItem['dept_id'] == $rowGetDept['dept_id']) $selected = 'selected="selected"';
                $data .= '<option value="'.$rowGetDept['dept_id'].'" '.$selected.'>'.$rowGetDept['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Sub Department</th><td><select name="sub_dept_id">';
            while ($rowGetSubDept = $db->fetch_array($getSubDept)) {
                $selected = "";
                if ($rowItem['sub_dept_id'] == $rowGetSubDept['sub_dept_id']) $selected = 'selected="selected"';
                $data .= '<option value="'.$rowGetSubDept['sub_dept_id'].'" '.$selected.'>'.$rowGetSubDept['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Vendor</th><td><select name="vend_id">';
            while ($rowGetVendor = $db->fetch_array($getVendor)) {
                $selected = "";
                if ($rowItem['vend_id'] == $rowGetVendor['vend_id']) $selected = 'selected="selected"';
                $data .= '<option value="'.$rowGetVendor['vend_id'].'" '.$selected.'>'.$rowGetVendor['long_desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr><th>Attribute</th><td><select name="attr_id">';
            while ($rowGetAttr= $db->fetch_array($getAttr)) {
                $selected = "";
                if ($rowItem['attr_id'] == $rowGetAttr['attr_id']) $selected = 'selected="selected"';
                $data .= '<option value="'.$rowGetAttr['attr_id'].'" '.$selected.'>'.$rowGetAttr['desc'].'</option>';
            }
            $data .= '</select></td></tr>';

            $data .= '<tr>';
            $data .= '<th>MSRP</th><td><input type="text" name="msrp" value="'.$rowItem['msrp'].'"></td>';
            $data .= '</tr>';
            $data .= '<tr>';
            $data .= '<th>RTP</th><td><input type="text" name="rtp" value="'.$rowItem['rtp'].'"></td>';
            $data .= '</tr>';
            $data .= '<tr>';
            $data .= '<th>Cost</th><td><input type="text" name="item_cost" value="'.$rowItem['item_cost'].'"></td>';
            $data .= '</tr>';
            $data .= '<tr>';
            $data .= '<th>UPC</th><td><input type="text" name="upc" value="'.$rowItem['upc'].'"></td>';
            $data .= '</tr>';
            $data .= '<tr><td colspan="2"><input type="button" name="btnUpdate" id="btnUpdate" value="Update"></td></tr>';
            $data .= '</table></div>';
        }
    }
?>

<h1>Edit Item Window</h1>

<form id="frmEditItem" name="frmEditItem" action="#" method="get">
    <lable>Item#: <input type="text" name="txtItem"></lable>
    <input type="submit" name="op" value="Search">
</form>

<?php
    if(!empty($data)) echo $data;
    require_once ("_inc/footer.php");
?>

<script type="text/javascript">
    $("#tblEditItem").on("click", "#btnUpdate", function(){

        var x      = $("#tblEditItem input").serializeArray();
        var y      = $("#tblEditItem select").serializeArray();
        var itemId = $("[name=item_id]").val();
        var data   = $.merge(x, y);

        $.ajax({
            url: "_inc/ajaxCalls.php",
            type: "post",
            data: {"action":"updateItem", "data": data, "itemId": itemId}
        }).done(function(html) {
            alert('Done!');
        });

    });
</script>