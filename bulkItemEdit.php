<?php
    require_once ("_inc/header2.php");
?>
    <style type="text/css">
        th {
            font-size: 0.9em;  
        }
    </style>
<?php

	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

    $locId       = $_SESSION['loc_id'];
    $allDepts    = userClass::getAllDepts();
    $allGenders  = userClass::getAllGenders();
    $allDesc     = userClass::getAllDesc();
    $allSeasons  = userClass::getAllSeasons();
    $allBranches = userClass::getAllBranchesShort();
    $allSubDepts = userClass::getAllSubDepts();
    $allAttrs    = userClass::getAllAttrs();
    $allVends    = userClass::getAllVends();

    if (!empty($_GET['btnSubmit']))
    {
        $query   = "SELECT items.item_id, items.item_name, items_dept.desc AS dept, items_sub_dept.desc AS subDept, items_gender.desc AS gender, items_season.desc AS season, items_attr.desc AS attr, items_vendor.desc AS vend, items_desc.desc AS `desc` FROM items
                    JOIN items_intl_code ON items.intl_code_id = items_intl_code.intl_code_id
                    JOIN items_dept      ON items.dept_id      = items_dept.dept_id
                    JOIN items_sub_dept  ON items.sub_dept_id  = items_sub_dept.sub_dept_id
                    JOIN items_gender    ON items.gender_id    = items_gender.gender_id
                    JOIN items_season    ON items.season_id    = items_season.season_id
                    JOIN items_attr      ON items.attr_id      = items_attr.attr_id
                    JOIN items_vendor    ON items.vend_id      = items_vendor.vend_id
                    JOIN items_desc      ON items.desc_id      = items_desc.desc_id";
        
        $condition = "WHERE";

        if (array_search("All", $_GET['selDept']) === false)
        {
            $getAllDepts = implode(",", $_GET['selDept']);
            $query    .= " ".$condition." items.dept_id in ({$getAllDepts})";
            $condition = "AND";
        }

        if (array_search("All", $_GET['selSeason']) === false)
        {
            $getAllSeasons = implode(",", $_GET['selSeason']);
            $query    .= " ".$condition." items.season_id in ({$getAllSeasons})";
            $condition = "AND";
        }

        if (array_search("All", $_GET['selGender']) === false)
        {
            $getAllGenders = implode(",", $_GET['selGender']);
            $query    .= " ".$condition." items.gender_id in ({$getAllGenders})";
            $condition = "AND";
        }

        if (array_search("All", $_GET['selDesc']) === false)
        {
            $getAllDescs = implode(",", $_GET['selDesc']);
            $query    .= " ".$condition." items.desc_id in ({$getAllDescs})";
            $condition = "AND";
        }

        if (!empty($_GET['itemId']))
        {
            $query    .= " ".$condition." items.item_id = '".$_GET['itemId']."'";
            $condition = "AND";
        }

        if (!empty($_GET['intlCode']))
        {
            $query    .= " ".$condition." items_intl_code.desc = '".$_GET['intlCode']."'";
            $condition = "AND";
        }

        if (array_search("All", $_GET['selSubDept']) === false)
        {
            $getAllSubDepts = implode(",", $_GET['selSubDept']);
            $query    .= " ".$condition." items.sub_dept_id in ({$getAllSubDepts})";
            $condition = "AND";
        }

        if (array_search("All", $_GET['selAttr']) === false)
        {
            $getAllAttrs = implode(",", $_GET['selAttr']);
            $query      .= " ".$condition." items.attr_id in ({$getAllAttrs})";
            $condition   = "AND";
        }

        if (array_search("All", $_GET['selVend']) === false)
        {
            $getAllVends = implode(",", $_GET['selVend']);
            $query      .= " ".$condition." items.vend_id in ({$getAllVends})";
            $condition   = "AND";
        }

        $getItems = $db->query($query);

        if (mysql_num_rows($getItems) > 0)
        {
            switch ($_GET['updateType']) {
                case 1:
                    $getDataFill = $db->query("SELECT `dept_id` AS `id`, `desc` FROM `items_dept`");
                    $updateData  = ' data-row="dept_id" ';
                break;
                case 2:
                    $getDataFill = $db->query("SELECT `sub_dept_id` AS `id`, `desc` FROM `items_sub_dept`");
                    $updateData  = ' data-row="sub_dept_id" ';
                break;
                case 3:
                    $getDataFill = $db->query("SELECT `gender_id` AS `id`, `desc` FROM `items_gender`");
                    $updateData  = ' data-row="gender_id" ';
                break;
                case 4:
                    $getDataFill = $db->query("SELECT `season_id` AS `id`, `desc` FROM `items_season`");
                    $updateData  = ' data-row="season_id" ';
                break;
                case 5:
                    $getDataFill = $db->query("SELECT `attr_id` AS `id`, `desc` FROM `items_attr`");
                    $updateData  = ' data-row="attr_id" ';
                break;
                case 6:
                    $getDataFill = $db->query("SELECT `vend_id` AS `id`, `desc` FROM `items_vendor`");
                    $updateData  = ' data-row="vend_id" ';
                break;
                case 7:
                    $getDataFill = $db->query("SELECT `desc_id` AS `id`, `desc` FROM `items_desc`");
                    $updateData  = ' data-row="desc_id" ';
                break;
                case 8:
                    $updateData  = ' data-row="rtp" ';
                break;
            }
            if ($db->num_rows($getDataFill) > 0) {
                while ($rowGetDataFill = $db->fetch_array($getDataFill)) {
                    $dataFill[] = $rowGetDataFill;
                }
                $selData    = '<select name="updateOneData" class="updateOneData">';
                foreach ($dataFill as $key => $value) {
                    $selData .= '<option value="'.$value['id'].'">'.$value['desc'].'</option>';
                }
                $selData   .= '</select>';
            }
            $tableData  = '<div id="editItemWrapper"><table id="tblEditItem" style="text-align:center;">';
            $tableData .= '<tr>';
            $tableData .= '<th></th>';
            $tableData .= '<th>Item#</th>';
            $tableData .= '<th>Name</th>';
            $tableData .= '<th>Department</th>';
            $tableData .= '<th>Sub Department</th>';
            $tableData .= '<th>Gender</th>';
            $tableData .= '<th>Season</th>';
            $tableData .= '<th>Attribute</th>';
            $tableData .= '<th>Vendor</th>';
            $tableData .= '<th>Description</th>';
            $tableData .= '<th></th>';
            $tableData .= '<th></th>';
            $tableData .= '<th></th>';
            $tableData .= '</tr>';
            while ($rowGetItems = $db->fetch_array($getItems)) {
                $tableData .= '<tr>';
                $tableData .= '<td><a class="group1" href="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowGetItems['item_id'].'.jpg"><img src="https://dl.dropboxusercontent.com/u/64785253/Collections/'.$rowGetItems['item_id'].'.jpg" width="50"></a></td>';
                $tableData .= '<td class="itemId">'.$rowGetItems['item_id'].'</td>';
                $tableData .= '<td>'.$rowGetItems['item_name'].'</td>';
                $tableData .= '<td>'.$rowGetItems['dept'].'</td>';
                $tableData .= '<td>'.$rowGetItems['subDept'].'</td>';
                $tableData .= '<td>'.$rowGetItems['gender'].'</td>';
                $tableData .= '<td>'.$rowGetItems['season'].'</td>';
                $tableData .= '<td>'.$rowGetItems['attr'].'</td>';
                $tableData .= '<td>'.$rowGetItems['vend'].'</td>';
                $tableData .= '<td>'.$rowGetItems['desc'].'</td>';
                if ($_GET['updateType'] != 8) {
                    $tableData .= '<td>'.$selData.'</td>';
                } else {
                    $itemPrices = userClass::getItemPrices($rowGetItems['item_id']);
                    $tableData .= '<td><input type="text" name="updateOneData" class="updateOneData" value="'.$itemPrices['rtp'].'"></td>';
                }
                $tableData .= '<td style="padding:10px;"><input type="button" name="btnUpdateBulkItem" id="btnUpdateBulkItem" value="Update" '.$updateData.'></td>';
                $tableData .= '<td><input type="checkbox" name="chkItem[]" class="chkItem" value="'.$rowGetItems['item_id'].'" '.$updateData.'></td>';
                $tableData .= '</tr>';
            }
            $tableData .= '<tr>';
            $tableData .= '<th colspan="2" style="padding:10px;">Bulk Update</th>';
            $tableData .= '<td colspan="11" style="padding:10px;">';
            if ($_GET['updateType'] != 8) {
                $selData    = '<select name="updateData" id="updateData">';
                foreach ($dataFill as $key => $value) {
                    $selData .= '<option value="'.$value['id'].'">'.$value['desc'].'</option>';
                }
                $selData   .= '</select>';
            } else {
                $selData    = '<input type="text" name="updateData" id="updateData">';
            }
            $tableData .= $selData;
            $tableData .= '</td>';
            $tableData .= '</tr>';
            $tableData .= '<tr>';
            $tableData .= '<td colspan="11" style="padding:10px;"><input type="button" name="btnUpdateBulkItems" id="btnUpdateBulkItems" value="Update All" '.$updateData.'></td>';
            $tableData .= '<td colspan="2" style="padding:10px;"><input type="button" name="btnUpdateChkdkItems" id="btnUpdateChkdkItems" value="Update Checked" '.$updateData.'></td>';
            $tableData .= '</tr>';
            $tableData .= '</table></div>';
        }
    }
?>

<h1>Bulk Item Edit Window</h1>

<form name="frmTransferReport" id="frmTransferReport" method="GET" action="#"  enctype="multipart/form-data">

    <table id="tblFilterMerch">
        <tr>
            <th>Department</th>
            <th>Sub Department</th>
            <th>Gender</th>
            <th>Season</th>
            <th>Attribute</th>
            <th>Vendor</th>
            <th>Description</th>
        </tr>
        <tr>
            <td>
                <select name="selDept[]" id="selDept" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if(!empty($allDepts)): ?>
                    <?php foreach ($allDepts as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <select name="selSubDept[]" id="selSubDept" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if(!empty($allSubDepts)): ?>
                    <?php foreach ($allSubDepts as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <select name="selGender[]" id="selGender" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if(!empty($allGenders)): ?>
                    <?php foreach ($allGenders as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <select name="selSeason[]" id="selSeason" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if(!empty($allSeasons)): ?>
                    <?php foreach ($allSeasons as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <select name="selAttr[]" id="selAttr" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if(!empty($allAttrs)): ?>
                    <?php foreach ($allAttrs as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <select name="selVend[]" id="selVend" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if(!empty($allVends)): ?>
                    <?php foreach ($allVends as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <select name="selDesc[]" id="selDesc" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if(!empty($allDesc)): ?>
                    <?php foreach ($allDesc as $key => $value): ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Item#</th>
            <td colspan="6"><input type="text" name="itemId"></label></td>
        </tr>
        <tr>
            <th>Intl#</th>
            <td colspan="6"><input type="text" name="intlCode"></label></td>
        </tr>
        <tr>
            <th>Update Type</th>
            <td colspan="6">
                <input type="radio" name="updateType" value="1" checked="checked">Department
                <input type="radio" name="updateType" value="2">Sub Department
                <input type="radio" name="updateType" value="3">Gender
                <input type="radio" name="updateType" value="4">Season
                <input type="radio" name="updateType" value="5">Attribute
                <input type="radio" name="updateType" value="6">Vendor
                <input type="radio" name="updateType" value="7">Description
                <input type="radio" name="updateType" value="8">RTP
            </td>
        </tr>
    </table>
    <br>
    <input type="submit" name="btnSubmit" value="Submit">
</form>

<?php
    if(!empty($tableData)) echo $tableData;
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
    $("#editItemWrapper").on("click", "#btnUpdateChkdkItems", function(){
        var allItems   = Array();
        var updateData = $("#updateData").val();
        var tableRow   = "";
        
        $('input:checked').each(function() {
            allItems.push($(this).val());
            tableRow = $(this).attr("data-row");
        });

        $.ajax({
            url: "_inc/ajaxGeneral.php",
            type: "post",
            data: {"action":"updateBulkItems", "allItems":allItems, "updateData":updateData, "tableRow": tableRow}
        }).done(function(){
            alert("Done");
        });
    });
</script>