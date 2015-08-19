//Instantiate Request Flag
var requestSent = false;

//Remove item 
var removeItem = function() {

	var subtotal      = 0;
	var totalCost     = 0;
	var totalQty      = 0;
	var rtp           = new Array();
	var qty           = new Array();
	var cost          = new Array();
	var itemTransType = new Array();

	$(".rtp").each(function() {
		rtp.push ($(this).text());
	});

	$(".cost").each(function() {
		cost.push ($(this).text());
	});

	$(".qty").each(function() {
		qty.push ($(this).text());
	});

	$(".itemTransType").each(function() {
		itemTransType.push ($(this).text());
	});

	for(var i = 0; i < rtp.length; i++)
	{
		//console.log(rtp[i] + qty[i]);
		subtotal  += (rtp[i] * qty[i]);
		totalCost += (cost[i] * qty[i]);
		totalQty  += parseInt(qty[i]);
	}

	$(".totalQty").text(totalQty);
	$(".subtotal").text(subtotal.toFixed(2));
	$(".totalCost").text(totalCost.toFixed(2));
	var subtotal  = $(".subtotal").text();
	var cashDisc  = $("#cashDisc").val();

	if (subtotal != 0)
	{
		$(".total").text((subtotal - cashDisc).toFixed(2));
	} else {
		$("#cashDisc").val('0.00');
		$("#percDisc").val('0.00');
		$(".total").text('0.00');
	}
}

$('#tableItems').on('click', '#removeItem2', function() {
	if (confirm("Are you sure?"))
	{
		var itemId  = $(this).closest('tr').find(".itemId").text();
		var sizeId  = $(this).closest('tr').find(".sizeId").text();

		$(this).closest('tr').remove();
		removeItem();

		$("#curItemId").text(itemId);
		$("#curSizeId").text(sizeId);

		$.colorbox
		({
			href:"#selectReason",
			inline:true,
			overlayClose:false,
			escKey:false,
			onLoad:function() { $('#cboxClose').remove() }
		});
	}
	return false;
});

$("#selectReason").on("click", "#btnItemReason", function() {
	var reasonId = $(".rdReason:checked").val();
	var itemId   = $("#curItemId").text();
	var sizeId   = $("#curSizeId").text();
	var transNo  = $.urlParam("transNo");
	var wrhsId   = $.urlParam("wrhsId");

	if (!reasonId) {
		alert("Please select a reason !");
	} else {
		$.ajax({
			url: "_inc/ajaxLogs.php",
			type: "post",
			data: {"action": "sendReason", "reasonId": reasonId, "itemId": itemId, "sizeId": sizeId,
			"transNo": transNo, "wrhsId": wrhsId}
		}).done(function(html) {
			if (html == "error") {
				alert('Please login again!');
			} else {
				$.colorbox.close();
			}
		});
	}
	return false;
});

var btnProceedR = function() {

	var branchTo 	 = $("#allBrances").val();
	var comments     = $("#comments").val();
	var subtotal  	 = $(".subtotal").text();
	var totalCost	 = $(".totalCost").text();
	var totalQty  	 = $(".totalQty").text();
	var currentDate  = $(".currentDate").text();
	var itemId   	 = new Array();
	var sizeId   	 = new Array();
	var qty      	 = new Array();
	var rtp     	 = new Array();
	var cost   	  	 = new Array();
	var action       = "";

	$(".itemId").each(function() {
		itemId.push ($(this).text());
	});

	$(".sizeId").each(function() {
		sizeId.push ($(this).text());
	});

	$(".qty").each(function() {
		qty.push ($(this).text());
	});

	$(".rtp").each(function() {
		rtp.push ($(this).text());
	});

	$(".cost").each(function() {
		cost.push ($(this).text());
	});

	if ($('#chkReverse').is(':checked')) {
		action = "revReceiving";
	} else {
		action = "receiving";
	};

	if (itemId.length != 0 && branchTo != 0) {
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxRcv.php",
				type: "post",
				data: {"action": action, "itemId": itemId, "sizeId": sizeId, "qty": qty, "rtp": rtp,
					   "cost": cost, "subtotal": subtotal, "totalCost": totalCost, "totalQty": totalQty,
					   "branchTo": branchTo, "comments": comments,"currentDate": currentDate}
			}).done(function(html) {
				if (html == "error") {
					alert('Please login again!');
				} else {
					resetAll();
					requestSent = false;
					alert("Done.");
					$("#allBrances").val($("#allBrances option:first").val());
				}
			});
		}
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

$("#btnProceedR").click(function() {
	btnProceedR();
});

$('body').bind('keydown', function(e) {
	if (e.keyCode == 114) {
		e.preventDefault();
		if ($("#btnProceedR").length > 0) {
			btnProceedR();
		}
	}
});

$("#addNewRec").click(function(){
	$.colorbox({
		href:"#addNew", inline:true
	});
});

$("#btnAddNew").click(function(){
	var records  = Array();
	var tbl      = "";
	var tblCol   = Array();
	var status   = 0;

	$(".addNewVal").each(function(){
		if ($(this).val() == "") {
			alert("Please complete all fields!");
			status = 1;
			return false;
		} else {
			records.push($(this).val());
			tblCol.push($(this).attr("tblCol"));
		}
	});

	tbl    = $(".addNewVal").attr("tbl");
	if (status != 1) {
		$.ajax({
			url : "_inc/ajaxAddEdit.php",
			type: "post",
			data: {"action":"addNewRec", "records":records, "tbl":tbl, "tblCol":tblCol}
		}).done(function(data){
			alert("Done");
			$.colorbox.close();
			location.reload();
		});
	}
});

$("#tblAddNew").on('dblclick', 'span.modify', function(){
    var uTbl    = $(this).attr("uTbl");
    var tblCol  = $(this).attr("tblCol");
    var uTblCol = $(this).attr("uTblCol");
    var rowId   = $(this).attr("rowId");
    var curVal  = $(this).text().toUpperCase();
    $(this).parent().html('<input type="text" class="modify" uTbl="'+uTbl+'" tblCol="'+tblCol+'" uTblCol="'+uTblCol+'" rowId="'+rowId+'" value="'+curVal+'">');
    $('.modify').focus();
    $('.modify').select();
});

$("#tblAddNew").on('dblclick', 'input.modify', function(){
    var uTbl    = $(this).attr("uTbl");
    var tblCol  = $(this).attr("tblCol");
    var uTblCol = $(this).attr("uTblCol");
    var rowId   = $(this).attr("rowId");
    var curVal  = $(this).val().toUpperCase();
    $.ajax({
        url : "_inc/ajaxAddEdit.php",
        type: "post",
        data: {"action":"updateCurRec", "rowId":rowId, "uTbl":uTbl, "tblCol":tblCol, "uTblCol":uTblCol, "curVal":curVal}
    });
    $(this).parent().html('<span class="modify" uTbl="'+uTbl+'" tblCol="'+tblCol+'" uTblCol="'+uTblCol+'" rowId="'+rowId+'">'+curVal+'</span>');
}).on('keydown', 'input.modify', function(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        var uTbl    = $(this).attr("uTbl");
        var tblCol  = $(this).attr("tblCol");
        var uTblCol = $(this).attr("uTblCol");
        var rowId   = $(this).attr("rowId");
        var curVal  = $(this).val().toUpperCase();
        $.ajax({
            url : "_inc/ajaxAddEdit.php",
            type: "post",
            data: {"action":"updateCurRec", "rowId":rowId, "uTbl":uTbl, "tblCol":tblCol, "uTblCol":uTblCol, "curVal":curVal}
        });
        $(this).parent().html('<span class="modify" uTbl="'+uTbl+'" tblCol="'+tblCol+'" uTblCol="'+uTblCol+'" rowId="'+rowId+'">'+curVal+'</span>');
    }
});

$('#tblTransReport').on('click', '#viewInvnDetails2', function(e) {

	var transNo = $(this).attr('transNo');
	var wrhsId  = $(this).attr('wrhsId');

	$.ajax({
		url: "_inc/ajaxReports.php",
		type: "post",
		data: {"action":"getInvnDetails2", "transNo": transNo, "wrhsId": wrhsId}
	}).done(function(data) {
		$("#inline_content").html(data);
		$.colorbox
		({
			href:"#inline_content",
			inline:true
		});
	});
	$.colorbox.resize();
	e.preventDefault();
});

$('#tblTransReport').on('click', '#exportInvnExcel', function(e) {

	var transNo = $(this).attr('transNo');
	var wrhsId  = $(this).attr('wrhsId');

	$.ajax({
		url: "_inc/ajaxReports.php",
		type: "post",
		data: {"action":"exportInvnExcel", "transNo": transNo, "wrhsId": wrhsId}
	}).done(function(data) {
		document.location.href = data;
	});
	
	e.preventDefault();
});

$('#fullReportWindow').click(function(e) {
	e.preventDefault();
	$.colorbox({
		href:"#fullReportType",
		inline:true
	});
});

$("#txtDateFrom").datepicker({
    dateFormat: 'yy-mm-dd'
});

$("#txtDateTo").datepicker({
    dateFormat: 'yy-mm-dd'
});

$('#exportFullReport').click(function() {
	if ($("#chkInvoices").is(':checked')) {
		var chkInvoices  = $("#chkInvoices").val();
	} else {
		var chkInvoices  = 0;
	}
	if ($("#chkInventory").is(':checked')) {
		var chkInventory  = $("#chkInventory").val();
	} else {
		var chkInventory  = 0;
	}
	if ($("#chkStock").is(':checked')) {
		var chkStock  = $("#chkStock").val();
	} else {
		var chkStock  = 0;
	}
	var dateFrom     = $('#txtDateFrom').val();
	var dateTo       = $('#txtDateTo').val();

	if (!$("#chkInvoices").is(':checked') && !$("#chkInventory").is(':checked') && !$("#chkStock").is(':checked')) {
		alert("Please choose the content!");
	} else {
		if ((dateFrom == "" || dateTo == "") &&	($("#chkInvoices").is(':checked') || $("#chkInventory").is(':checked'))) {
			alert("Please choose correct date range!");
		} else {
			$.ajax({
				url: "_inc/ajaxReports.php",
				type: "post",
				data: {"action": "exportFullReport", "dateFrom":dateFrom, "dateTo":dateTo,
				"chkInvoices":chkInvoices, "chkInventory":chkInventory, "chkStock":chkStock}
			}).done(function(data) {
				document.location.href = data;
			});
		}
	};
});

$("#frmStoresSalesReport").on("change", "#selYear", function() {

	var selectedYears = [];

	$('#selYear :selected').each(function(i, selected) {
		selectedYears[i] = $(selected).val(); 
	});

	$.ajax({
		url: "_inc/ajaxReports.php",
		type: "post",
		data: {"action": "getMonthRanges", "selectedYears": selectedYears}
	}).done(function(data) {
		$("#selMonth").html(data);
	});
});

$("#frmStoresSalesReport").on("change", "#selMonth", function() {

	var selectedYears = [];
	var selectedMonths = [];

	$('#selYear :selected').each(function(i, selected) {
		selectedYears[i] = $(selected).val(); 
	});

	$('#selMonth :selected').each(function(i, selected) {
		selectedMonths[i] = $(selected).val(); 
	});

	$.ajax({
		url: "_inc/ajaxReports.php",
		type: "post",
		data: {"action": "getDayRanges", "selectedYears": selectedYears, "selectedMonths": selectedMonths}
	}).done(function(data) {
		$("#selDay").html(data);
	});
});

$(".selTracking").change(function(){
	var comment  = prompt("Please enter the notes below:", "مثال: رقم الشحنه");
	var transNo  = $(this).attr('transNo');
	var wrhsId   = $(this).attr('wrhsId');
	var tracking = $(this).val();
	if (confirm("Are you sure?")) {
		$.ajax({
			url: "_inc/ajaxGeneral.php",
			type: "post",
			data: {"action":"updateTracking", "comment":comment, "transNo":transNo, "wrhsId":wrhsId, "tracking":tracking}
		}).done(function(data){
			alert(data);
		});
	};
});

// console.log(selCompareBy);

$("#selCompareBy").change(function(){
	var selCompareBy = $(this).val();

	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action":"selCompareBy", "selCompareBy":selCompareBy}
	}).done(function(html){
		$("#selViewTypeResponse").html(html);	
	});
});

$("#trCompareBy").hide();

$('.selReportType').click(function() {
	var value = $(this).val();
	if(value == 2) {
		$("#trCompareBy").show();
		$("#trViewType").hide();
	} else {
		$("#trCompareBy").hide();
		$("#trViewType").show();
	}
});

$("#editItemWrapper").on("click", "#btnUpdateBulkItems", function(){
	var allItems   = Array();
	var updateData = $("#updateData").val();
	var tableRow   = $(this).attr("data-row");

	$(".itemId").each(function(){
		allItems.push($(this).text());
	});

	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action":"updateBulkItems", "allItems":allItems, "updateData":updateData, "tableRow": tableRow}
	}).done(function(){
		alert("Done");
		location.reload();
	});
});

$("#editItemWrapper").on("click", "#btnUpdateBulkItem", function(){
	var allItems   = Array();
	var updateData = $(this).parent().parent().find(".updateOneData").val();
	var tableRow   = $(this).attr("data-row");

	allItems.push($(this).parent().parent().find(".itemId").text());
	
	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action":"updateBulkItems", "allItems":allItems, "updateData":updateData, "tableRow": tableRow}
	}).done(function(){
		alert("Done");
	});
});

$('#tblTransReport').on('click', '.showTrackingComments', function(e) {

	var transTrackingId = $(this).attr('trans_tracking_id');

	$.ajax({
		url: "_inc/ajaxReports.php",
		type: "post",
		data: {"action":"getTrackingComments", "transTrackingId":transTrackingId}
	}).done(function(data) {
		$("#inline_content").html(data);
		$.colorbox
		({
			href:"#inline_content",
			inline:true
		});
	});
	$.colorbox.resize();
	e.preventDefault();
});

function reCalculate() {

	var subtotal      = 0;
	var totalCost     = 0;
	var totalQty      = 0;
	var rtp           = new Array();
	var qty           = new Array();
	var cost          = new Array();

	$(".rtp").each(function() {
		rtp.push ($(this).text());
	});

	$(".cost").each(function() {
		cost.push ($(this).text());
	});

	$(".qty").each(function() {
		qty.push ($(this).text());
	});

	for (var i = 0; i < rtp.length; i++)
	{
		subtotal  += (rtp[i] * qty[i]);
		totalCost += (cost[i] * qty[i]);
		totalQty  += parseInt(qty[i]);
	}
	$(".totalQty").text(totalQty);
	$(".subtotal").text(subtotal.toFixed(2));
	$(".total").text(subtotal.toFixed(2));
	$(".totalCost").text(totalCost.toFixed(2));
}

$("#chkVoucher").click(function(){
	var voucherCode = $("#voucherCode").val();
	var itemId      = new Array();
	var rtp         = new Array();
	var rtp2        = new Array();
	var msrp        = new Array();
	var totalRtp    = 0;
	var totalMsrp   = 0;

	$(".rtp").each(function() {
		rtp.push($(this));
		rtp2.push($(this).text());
	});

	$(".msrp").each(function() {
		msrp.push($(this).text());
	});

	$(".itemId").each(function() {
		itemId.push ($(this).text());
	});

	for (var i = 0; i < rtp.length; i++) {
		totalMsrp += parseInt(msrp[i]);
		totalRtp  += parseInt(rtp2[i]);
	};

	if (itemId.length != 0)
	{
		if (voucherCode.length != 0) {
			$.ajax({
				url: "_inc/ajaxGeneral.php",
				type: "post",
				dataType: "json",
				data: {"action":"chkVoucher", "voucherCode":voucherCode, "totalRtp":totalRtp, "totalMsrp":totalMsrp}
			}).done(function(data) {
				switch (data.err) {
					case 0 : alert("Voucher is not valid!");
					break;
					case 1 : alert("Voucher is not active!");
					break;
					case 2 : alert("Voucher is expired!");
					break;
					case 3 : alert("Voucher is already used!");
					break;
					case 4 : alert("Voucher can't be applied!");
					break;
					default:
						if (data.sale == 0) {
							for (var i = 0; i < rtp.length; i++) {
								rtp[i].text(msrp[i]);
							};
							reCalculate();
						};
						
						$("#txtItems").prop("disabled", true);
						$("#btnReset").prop("disabled", true);
						$("#btnSubmitItem").prop("disabled", true);
						//note it's an #ID
						$("#removeItem").prop("disabled", true);
						$("#voucherCode").prop("disabled", true);
						$("#chkVoucher").prop("disabled", true);

						$("#percDisc").val(data.prc);
						//very useful
						$("#percDisc").trigger("keyup");
						$("#voucherCode").attr("data-voucherId", data.voucherId);
					break;
				}
			});
		} else {
			alert("Voucher code is not valid!");
		};

	} else {
		alert("No items added!");
	};
});

$(".getVoucherLog").click(function(){
	var voucherId = $(this).attr("data-voucher");
	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action": "getVoucherLog", "voucherId":voucherId}
	}).done(function(data) {
		$("#getVoucherLog").html(data);
		$.colorbox({ inline:true, overlayClose:false, href:"#getVoucherLog" });
	});
});


//add colorbox event to edit customer button
$(".editCust").colorbox({
	inline:true, overlayClose:false,
	onComplete:function() { $('#txtName').focus(); }, onClosed:function() { $('#txtItems').focus(); },
	onLoad: function() { $('#cboxClose').remove() }
});

$(".editCust").click(function(e){
	var custId = $(".customerId").text();
	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action": "getCustomer", "custId":custId},
		dataType: 'json'
	}).done(function(data) {
		$("#etxtId").val(data.cust_id);
		$("#etxtName").val(data.cust_name);
		$("#etxtMob").val(data.cust_tel.slice(2));
		$("#etxtEmail").val(data.cust_email);
	});
	e.preventDefault();
});

$("#etxtMob").keyup(function(){
	var txtMob   = $(this).val();
	txtMob = txtMob.replace(/^0+/, '');
	$(this).val(txtMob);
});

$("#btnEditCust").click(function() {

	var txtId    = $("#etxtId").val();
	var txtName  = $("#etxtName").val();
	var txtMob   = $("#etxtMob").val();
	var txtEmail = $("#etxtEmail").val();
	var chkEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	if (txtName != '' && txtMob != '') {
		if (/^\d{10}$/.test(txtMob)) {
			if (txtEmail != '' && !chkEmail.test(txtEmail)) {
				alert("Invalid email.")
			} else {
				$.ajax({
					url: "_inc/ajaxGeneral.php",
					type: "post",
					data: {"action":"editCustomer", "txtId":txtId, "txtName":txtName, "txtMob":txtMob, "txtEmail":txtEmail}
				}).done(function(html) {
					alert("Done.");
					$(".customerName").text(txtName);
					$.colorbox.close();
				});
			}
		} else {
		    alert("Invalid number; must be ten digits.");
		    $("#txtMob").focus();
		    return false;
		}
	} else {
		alert("Please complete all fields.");
	    $("#txtMob").focus();
	    return false;
	}
});

$('.updateRole').click(function() {
	var chkRoles = Array();
	var roleId   = $(this).parent().parent().find('.role').attr('data-roleId');
	var roleName = $(this).parent().parent().find('.role').val();

	$(this).parent().parent().find('.selPerm:checked').each(function() {
		chkRoles.push($(this).val());
	});

	//console.log(roleId);
});



