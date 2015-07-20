<?php
    require_once ("_inc/header2.php");
?>

<style type="text/css">
    #addNewWrapper > table {
        font-size: 14px;
        text-align: center;
    }
    #getBarCode {
        overflow: hidden !important;
    }
</style>

<?php
	if ($_SESSION['user_type'] != "sadmin") {
		echo "<script>window.location.href = 'index.php'</script>";
	}

    function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

    $getCreators  = $db->query("SELECT DISTINCT(vouchers.creator_id), users.username FROM users
                                JOIN vouchers ON vouchers.creator_id = users.user_id");
    $getLocations = $db->query("SELECT DISTINCT(voucher_log.loc_id), locations.short_desc FROM locations
                                JOIN voucher_log ON voucher_log.loc_id = locations.loc_id");

    if (isset($_GET['btnSubmit']))
    {
        $dateFrom     = $_GET['dateFromx'];
        $dateTo       = $_GET['dateTox'];

        $query   = "SELECT DISTINCT(vouchers.voucher_id), vouchers.voucher_code, vouchers.creator_id, vouchers.start_date,
                    vouchers.end_date, vouchers.percentage, vouchers.max_value, vouchers.remain, vouchers.used,
                    vouchers.sale, users.username, customers.cust_name, invoice_header.total_amount,
                    invoice_header.net_value
                    FROM vouchers
                    LEFT JOIN voucher_log ON voucher_log.voucher_id = vouchers.voucher_id
                    LEFT JOIN invoice_header ON invoice_header.invo_no = voucher_log.invo_no
                    AND invoice_header.loc_id = voucher_log.loc_id
                    LEFT JOIN customers ON invoice_header.cust_id = customers.cust_id
                    LEFT JOIN users ON vouchers.creator_id = users.user_id";

        $condition = "WHERE";

        if (array_search("All", $_GET['selCreator']) === false)
        {
            $getAllCreators = implode(",", $_GET['selCreator']);
            $query         .= " ".$condition." vouchers.creator_id in ({$getAllCreators})";
            $condition      = "AND";
        }

        if (array_search("All", $_GET['selLocation']) === false)
        {
            $getAllLocations = implode(",", $_GET['selLocation']);
            $query         .= " ".$condition." vouchers.loc_id in ({$getAllLocations})";
            $condition      = "AND";
        }

        if (array_search("All", $_GET['selAvailability']) === false)
        {
            $getAllAvailabilities = implode(",", $_GET['selAvailability']);
            $query               .= " ".$condition." vouchers.used in ({$getAllAvailabilities})";
            $condition            = "AND";
        }

        if (!empty($dateFrom) && !empty($dateTo)) {
            $query .= " ".$condition." invoice_header.date BETWEEN CAST('{$dateFrom}' AS DATE)
                                    AND CAST('{$dateTo}' AS DATE)";
        }
        
        $getVouchers  = $db->query($query);

    } else {
        $getVouchers  = $db->query("SELECT vouchers.*, users.username
                                    FROM vouchers
                                    LEFT JOIN users ON vouchers.creator_id = users.user_id
                                    LIMIT 20");
    }

    $data  = '<div id="addNewWrapper">';
    $data .= '<input type="button" id="addNewRec" value="Add New"><br><br>';
    $data .= '<table id="tblAddNew">';

    $data .= '<tr>';
    $data .= '<th>Vouceher#</th><th>Creator</th><th>Start Date</th><th>End Date</th><th>Perc.</th><th>Max. Value</th><th>Remain</th><th>Available</th><th>History</th><th>Barcode</th>';
    $data .= '</tr>';

    while ($rowGetVouchers = $db->fetch_array($getVouchers)) {
        $data .= '<tr>';
        $data .= '<td>'.$rowGetVouchers['voucher_code'].'</td>';
        $data .= '<td>'.$rowGetVouchers['username'].'</td>';
        $data .= '<td>'.$rowGetVouchers['start_date'].'</td>';
        $data .= '<td>'.$rowGetVouchers['end_date'].'</td>';
        $data .= '<td>'.$rowGetVouchers['percentage'].'%</td>';
        $data .= '<td>'.$rowGetVouchers['max_value'].'</td>';
        if (!empty($rowGetVouchers['remain'])) {
            $data .= '<td>'.$rowGetVouchers['remain'].'</td>';
        } else {
            $data .= '<td></td>';
        }
        
        if ($rowGetVouchers['used'] == 1) {
        	$data .= '<td>No</td>';
        } else {
        	$data .= '<td>Yes</td>';
        }
        $data .= '<td><a class="getVoucherLog" href="#" data-voucher="'.$rowGetVouchers['voucher_id'].'">View</a></td>';
        $data .= '<td><a class="getBarCode" href="#" data-voucher="'.$rowGetVouchers['voucher_code'].'">Get Barcode</a></td>';
        $data .= '</tr>';
    }
    $data .= '</table></div>';

?>

<h1>Manage Vouchers Window</h1>
<br><br>

<form name="frmTransferReport" id="frmTransferReport" method="GET" action="#"  enctype="multipart/form-data">

    <table id="tblFilterMerch">
        <tr>
            <th>Creator</th>
            <th>Location</th>
            <th>Availability</th>
        </tr>
        <tr>
            <td>
                <select name="selCreator[]" id="selCreator" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if ($db->num_rows($getCreators) > 0): ?>
                    <?php while ($rowGetCreators = $db->fetch_array($getCreators)): ?>
                    <option value="<?php echo $rowGetCreators['creator_id'] ?>">
                        <?php echo $rowGetCreators['username'] ?>
                    </option>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <select name="selLocation[]" id="selLocation" multiple="multiple">
                    <option value="All" selected>All</option>
                    <?php if ($db->num_rows($getLocations) > 0): ?>
                    <?php while ($rowGetLocations = $db->fetch_array($getLocations)): ?>
                    <option value="<?php echo $rowGetLocations['loc_id'] ?>">
                        <?php echo $rowGetLocations['short_desc'] ?>
                    </option>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                <select name="selAvailability[]" id="selAvailability" multiple="multiple">
                    <option value="All" selected>All</option>
                    <option value="0">Yes</option>
                    <option value="1">No</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Invoice Date From: </th>
            <td colspan="6"><input type="text" id="dateFromx" name="dateFromx"></td>
        </tr>
        <tr>
            <th>Invoice Date To: </th>
            <td colspan="6"><input type="text" id="dateTox" name="dateTox"></td>
        </tr>
    </table>
    <br>
    <input type="submit" name="btnSubmit" value="Submit">
</form>

<br><br>

<?php
    if(!empty($data)) echo $data;
?>

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
    <div id='addNew' style='padding:10px; background:#fff;'>
        <table style="text-align:center">
        	<tr style="display:none;">
                <td><label>Creator Id:</label></td>
                <td>
                	<input type="text" class="addNewVal" tblCol="creator_id" tbl="vouchers" value="<?php echo $_SESSION['user_id']; ?>" disabled>
                </td>
            </tr>
        	<tr>
                <td><label>Voucher#:</label></td>
                <td>
                	<input type="text" class="addNewVal" tblCol="voucher_code" tbl="vouchers" value="<?php echo generateRandomString(); ?>" disabled>
                </td>
            </tr>
            <tr>
                <td><label>Start Date:</label></td>
                <td>
                	<input type="text" id="dateFrom" class="addNewVal" tblCol="start_date" tbl="vouchers">
                </td>
            </tr>
            <tr>
                <td><label>End Date:</label></td>
                <td>
                	<input type="text" id="dateTo" class="addNewVal" tblCol="end_date" tbl="vouchers">
                </td>
            </tr>
            <tr>
                <td><label>Percentage:</label></td>
                <td>
                	<input type="text" id="perc" class="addNewVal" tblCol="percentage" tbl="vouchers">
                </td>
            </tr>
            <tr>
                <td><label>Maximum Value:</label></td>
                <td>
                	<input type="text" id="maxVal" class="addNewVal" tblCol="max_value" tbl="vouchers">
                </td>
            </tr>
            <tr>
                <td><label>Apply Discount On:</label></td>
                <td>
                    <input class="chkSale" name="chkSale" type="radio" value="0" checked>MSRP
                    <input class="chkSale" name="chkSale" type="radio" value="1">RTP
                </td>
            </tr>
            <tr style="display:none;">
                <td><label>Apply Discount On:</label></td>
                <td>
                    <input type="text" id="sale" class="addNewVal" tblCol="sale" tbl="vouchers" value="0">
                </td>
            </tr>
            <tr style="display:none;">
                <td><label>Remain:</label></td>
                <td>
                    <input type="text" id="remain" class="addNewVal" tblCol="remain" tbl="vouchers">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                	<input type="button" id="btnAddNew" name="btnAddNew" value="Add">
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
    <div id="getBarCode"></div>
    <div id="getVoucherLog"></div>
</div>

<?php

if (isset($dataTable)) echo $dataTable;

require_once ("_inc/footer.php");

?>

<script type="text/javascript" src="_js/jquery-barcode.min.js"></script>  

<script>

$(function() {
    $("#dateFromx").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#dateTox").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#dateFrom").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#dateTo").datepicker({
        dateFormat: 'yy-mm-dd'
    });
});

$('.getBarCode').click(function(){
    var voucherNo = $(this).attr('data-voucher');
    $("#getBarCode").barcode(""+voucherNo+"", "code128", {barWidth:3, barHeight:150, output:"bmp"});
    $.colorbox({ inline:true, overlayClose:false, width:"550px", height:"230px", href:"#getBarCode" });
});

$("#maxVal").keyup(function(){
    var maxVal = $(this).val();
    $("#remain").val(maxVal);
});

$('.chkSale').change(function(){
   $("#sale").val($(this).val());
});

</script>


