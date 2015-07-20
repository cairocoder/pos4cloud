//Instantiate Request Flag
var requestSent = false;

//print function
function doPrint() {
	var thisDiv = document.getElementById("reportWrapper").innerHTML;
	document.getElementById("printArea").innerHTML = thisDiv;
	window.print();
}

//check selected payment
$('#paymentType').change(function() {

	var paymentValue = $(this).find('option:selected').val();

	if (paymentValue == 3) {
		$("#splitOptions").show();
		$("#txtCash").focus();
		$("#txtCash").select();
	} else {
		$("#splitOptions").hide();
	}
});

//manipulate payment cash/visa
$('#splitOptions').on("keyup", "#txtCash", function(e) {

	var total = $(".total").text();
	var cash  = $("#txtCash").val();

	$("#txtVisa").val((total - cash).toFixed(2));
	var visa  = $("#txtVisa").val();
	if (visa < 0) {
		$("#txtVisa").val("0.00");
		$("#txtCash").val("0.00");
		$("#txtCash").select();
	}
}).on("keyup", "#txtVisa", function(e) {

	var total = $(".total").text();
	var visa  = $("#txtVisa").val();

	$("#txtCash").val((total - visa).toFixed(2));
	var cash  = $("#txtCash").val();
	if (cash < 0) {
		$("#txtVisa").val("0.00");
		$("#txtCash").val("0.00");
		$("#txtVisa").select();
	}
});

//add colorbox event to add customer button
$(".addCust").colorbox({
	inline:true, overlayClose:false,
	onComplete:function() { $('#txtName').focus(); }, onClosed:function() { $('#txtItems').focus(); },
	onLoad: function() { $('#cboxClose').remove() }
});

$(".group1").colorbox();

if ($("#inline_content2").length > 0) {
	$.colorbox({
		href:"#inline_content2", inline:true, overlayClose:false,
		width:"520px", onComplete:function() { $('#txtCustMob2').focus(); },
		onClosed:function() { $('#txtItems').focus(); }, onLoad:function() { $('#cboxClose').remove() }
	});
}

var btnProceed = function() {

	var total        = $(".total").text();
	var customerName = $(".customerName").text();
	var saleMan      = $("#salesMen").val();
	var itemId       = new Array();

	$(".itemId").each(function() {
		itemId.push ($(this).text());
	});

	if (itemId.length != 0 && customerName != '' && saleMan != 0) {
		$.colorbox({href:"#inline_content3", inline:true, width:"300px", height: "220px",
		onComplete:function() { $('#paymentType').focus(); }, onClosed:function() { $('#txtItems').focus(); },
		onLoad:function() { $('#cboxClose').remove() }});
		$("#paymentType").val($("#paymentType option:first").val());
		$("#splitOptions").hide();
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

var btnProceedE = function() {

	var total         = $(".total").text();
	var customerName  = $(".customerName").text();
	var itemId        = new Array();
	var itemTransType = new Array();


	$(".itemId").each(function() {
		itemId.push ($(this).text());
	});

	$(".itemTransType").each(function() {
		itemTransType.push ($(this).text());
	});

	if (itemId.length != 0 && customerName != '' && ($.inArray("1", itemTransType)) > -1 && ($.inArray("2", itemTransType)) > -1) {
		$.colorbox({href:"#inline_content3", inline:true, width:"300px",
		onComplete:function() { $('#paymentType').focus(); }, onClosed:function() { $('#txtItems').focus(); },
		onLoad:function() { $('#cboxClose').remove() }});
		$("#paymentType").val($("#paymentType option:first").val());
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

var btnProceedT = function() {

	var branchTo     = $("#allBrances").val();
	var comments     = $("#comments").val();
	var subtotal     = $(".subtotal").text();
	var totalCost    = $(".totalCost").text();
	var totalQty     = $(".totalQty").text();
	var currentDate  = $(".currentDate").text();
	var itemId       = new Array();
	var sizeId       = new Array();
	var qty          = new Array();
	var rtp          = new Array();
	var cost      	 = new Array();

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

	if (itemId.length != 0 && branchTo != 0) {
		if (confirm("Are you sure?")) {
			if(!requestSent) {
				requestSent = true;
				$.ajax({
					url: "_inc/ajaxCalls.php",
					type: "post",
					data: {"action": "transfer", "itemId": itemId, "sizeId": sizeId, "qty": qty, "rtp": rtp,
						   "cost": cost, "subtotal": subtotal, "totalCost": totalCost, "totalQty": totalQty,
						   "branchTo": branchTo, "comments": comments, "currentDate": currentDate}
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
		};
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

var btnProceedT2 = function() {

	var branchTo     = $("#allBrances").val();
	var comments     = $("#comments").val();
	var subtotal     = $(".subtotal").text();
	var totalCost    = $(".totalCost").text();
	var totalQty     = $(".totalQty").text();
	var currentDate  = $(".currentDate").text();
	var itemId       = new Array();
	var sizeId       = new Array();
	var qty          = new Array();
	var rtp          = new Array();
	var cost      	 = new Array();
	var transNo      = $.urlParam('transNo');

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

	if (itemId.length != 0 && branchTo != 0) {
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxCalls.php",
				type: "post",
				data: {"action": "transfer2", "itemId": itemId, "sizeId": sizeId, "qty": qty, "rtp": rtp,
					   "cost": cost, "subtotal": subtotal, "totalCost": totalCost, "totalQty": totalQty,
					   "branchTo": branchTo, "comments": comments, "currentDate": currentDate,
					   "transNo": transNo}
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

var btnProceedST = function() {

	var comments     = $("#comments").val();
	var subtotal     = $(".subtotal").text();
	var totalCost    = $(".totalCost").text();
	var totalQty     = $(".totalQty").text();
	var currentDate  = $(".currentDate").text();
	var itemId       = new Array();
	var sizeId       = new Array();
	var qty          = new Array();
	var rtp          = new Array();
	var cost      	 = new Array();
	var branchTo     = new Array();
	var branchFrom   = $("#allBrances").val();

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

	$(".branchTo").each(function() {
		branchTo.push ($(this).text());
	});

	if (itemId.length != 0 && branchFrom != 0) {
		if (confirm("Are you sure?")) {
			if(!requestSent) {
				requestSent = true;
				$.ajax({
					url: "_inc/ajaxCalls.php",
					type: "post",
					data: {"action": "specialTransfer", "itemId": itemId, "sizeId": sizeId, "qty": qty,
						   "branchFrom": branchFrom, "branchTo": branchTo, "comments": comments,
						   "currentDate": currentDate}
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
		}
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

var btnHoldT = function() {

	var branchTo     = $("#allBrances").val();
	var comments     = $("#comments").val();
	var subtotal     = $(".subtotal").text();
	var totalCost    = $(".totalCost").text();
	var totalQty     = $(".totalQty").text();
	var currentDate  = $(".currentDate").text();
	var itemId       = new Array();
	var sizeId       = new Array();
	var qty          = new Array();
	var rtp          = new Array();
	var cost      	 = new Array();

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

	if (itemId.length != 0 && branchTo != 0) {
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxCalls.php",
				type: "post",
				data: {"action": "holdTrans", "itemId": itemId, "sizeId": sizeId, "qty": qty, "rtp": rtp,
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

var btnHoldT2 = function() {

	var branchTo     = $("#allBrances").val();
	var comments     = $("#comments").val();
	var subtotal     = $(".subtotal").text();
	var totalCost    = $(".totalCost").text();
	var totalQty     = $(".totalQty").text();
	var currentDate  = $(".currentDate").text();
	var itemId       = new Array();
	var sizeId       = new Array();
	var qty          = new Array();
	var rtp          = new Array();
	var cost      	 = new Array();
	var transNo      = $.urlParam('transNo');
	var wrhsId       = $.urlParam('wrhsId');

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

	if (itemId.length != 0 && branchTo != 0) {
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxCalls.php",
				type: "post",
				data: {"action": "holdTrans2", "itemId": itemId, "sizeId": sizeId, "qty": qty, "rtp": rtp,
					   "cost": cost, "subtotal": subtotal, "totalCost": totalCost, "totalQty": totalQty,
					   "branchTo": branchTo, "comments": comments,"currentDate": currentDate,
					   "transNo": transNo, "wrhsId": wrhsId}
			}).done(function(html) {
				if (html == "error") {
					alert('Please login again!');
				} else {
					resetAll();
					requestSent = false;
					alert("Done.");
					document.location.href = "transferBox.php";
				}
			});
		}
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

$("#btnProceed").click(function() {
	btnProceed();
});

$("#btnProceedE").click(function() {
	btnProceedE();
});

$("#btnProceedT").click(function() {
	btnProceedT();
});

$("#btnProceedT2").click(function() {
	btnProceedT2();
});

$("#btnProceedST").click(function() {
	btnProceedST();
});

$("#btnHoldT").click(function() {
	btnHoldT();
});

$("#btnHoldT2").click(function() {
	btnHoldT2();
});

$('body').bind('keydown', function(e) {
	if (e.keyCode == 114) {
		e.preventDefault();
		if ($("#btnProceedT").length > 0) {
			btnProceedT();
		} else if ($("#btnProceedT2").length > 0) {
			btnProceedT2();
		} else if ($("#btnProceedST").length > 0) {
			btnProceedST();
		} else if ($("#btnProceedE").length > 0) {
			btnProceedE();
		} else if ($("#btnProceed").length > 0) {
			btnProceed();
		}
	}
});

$('body').bind('keydown', function(e) {
	if (e.keyCode == 115) {
		e.preventDefault();
		if ($("#btnHoldT").length > 0) {
			btnHoldT();
		} else if ($("#btnHoldT2").length > 0) {
			btnHoldT2();
		}
	}
});

//update customer name with it's number from the database
$('#txtCustMob').bind('keydown', function(e) {

	if (e.keyCode == 13) {
		e.preventDefault();
		var txtCustMob = $('#txtCustMob').val();

		if (txtCustMob != "")
		{
			$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			dataType: "json",
			data: {"txtCustMob": txtCustMob}
			}).done(function(html) {
				$('.customerId').text(html.id);
				$('.customerName').text(html.name);
				$('#txtCustMob').val("");
				$.colorbox.close();
			});
		} else {
			$('.customerId').text("1");
			$('.customerName').text("General Customer");
			$('#txtCustMob').val("");
			$.colorbox.close();
		}
	}
});

//update customer name with it's number from the database
$('#txtCustMob2').bind('keydown', function(e) {

	if (e.keyCode == 13) {

		e.preventDefault();
		var txtCustMob2 = $('#txtCustMob2').val();

		if (txtCustMob2 != "")
		{
			$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			dataType: "json",
			data: {"txtCustMob": txtCustMob2}
			}).done(function(html) {
				if (html == "false") {
					$(".addCust").click();
					$("#txtMob").val(txtCustMob2);
				} else {
					$('.customerId').text(html.id);
					$('.customerName').text(html.name);
					$('#txtCustMob2').val("");
					$(".ui-autocomplete").css('display', 'none');
					$.colorbox.close();
				}
			});
		} else {
			$('.customerId').text("1");
			$('.customerName').text("General Customer");
			$('#txtCustMob2').val("");
			$(".ui-autocomplete").css('display', 'none');
			$.colorbox.close();
		}
	}
});

//get invoice details for return window
$('#txtInvoiceNo').bind('keydown', function(e) {

	if (e.keyCode == 13) {

		e.preventDefault();
		var txtInvoiceNo = $('#txtInvoiceNo').val();

		if (txtInvoiceNo != "")
		{
			$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			data: {action: "getInvoiceDetails", "txtInvoiceNo": txtInvoiceNo}
			}).done(function(html) {
				if (html == "1")
				{
					document.location.href = "returnBox.php?invoNo=" + txtInvoiceNo;
				} else if (html == "0") {
					alert("This invoice already had a return");
					$("#txtInvoiceNo").focus();
				}
			});
		}
	}
});

//get invoice details for return window 2
$('#txtInvoiceNo2').bind('keydown', function(e) {

	if (e.keyCode == 13) {

		e.preventDefault();
		var txtInvoiceNo2 = $('#txtInvoiceNo2').val();

		if (txtInvoiceNo2 != "")
		{
			$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			data: {action: "getInvoiceDetails2", "txtInvoiceNo": txtInvoiceNo2}
			}).done(function(html) {
				if (html == "1")
				{
					document.location.href = "returnBoxFull.php?invoNo=" + txtInvoiceNo2;
				} else if (html == "0") {
					alert("This invoice already had a return");
					$("#txtItems").focus();
				}
			});
		}
	}
});

//get invoice details for exchange window
$('#txtInvoiceNo3').bind('keydown', function(e) {

	if (e.keyCode == 13) {

		e.preventDefault();
		var txtInvoiceNo3 = $('#txtInvoiceNo3').val();

		if (txtInvoiceNo3 != "")
		{
			$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			data: {action: "getInvoiceDetails3", "txtInvoiceNo": txtInvoiceNo3}
			}).done(function(html) {
				if (html == "1")
				{
					document.location.href = "exchangeBox.php?invoNo=" + txtInvoiceNo3;
				} else if (html == "0") {
					alert("This invoice already had a exchange");
					$("#txtItems").focus();
				}
			});
		}
	}
});

$("#txtMob").keyup(function(){
	var txtMob   = $(this).val();
	txtMob = txtMob.replace(/^0+/, '');
	$(this).val(txtMob);
});

var addCustomer = function() {

	var txtName  = $("#txtName").val();
	var txtMob   = $("#txtMob").val();
	var txtEmail = $("#txtEmail").val();
	var chkEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	if (txtName != '' && txtMob != '') {
		if (/^\d{10}$/.test(txtMob)) {
			if (txtEmail != '' && !chkEmail.test(txtEmail)) {
				alert("Invalid email.")
			} else {
				$.ajax({
					url: "_inc/ajaxCalls.php",
					type: "post",
					data: {"action":"addCustomer", "txtName": txtName, "txtMob": txtMob, "txtEmail": txtEmail}
				}).done(function(html) {
					alert("Done.");
					$('.customerId').text(html);
					$(".customerName").text(txtName);
					$("#txtName").val("");
					$("#txtMob").val("");
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
}

$('#inline_content').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		addCustomer();
	}
});

//add customer to the database
$("#btnAddCust").click(function() {
	addCustomer();
});

var selItem = function() {

	var txtItems = $('#txtItems').val();
	    txtItems = txtItems.replace(/^0+/, '');
	$("#txtItems").prop('disabled', true);
	if (isNaN(parseInt(txtItems)))
	{
		$('#txtItems').val("");
	} else {
		$('#txtItems').val(parseInt(txtItems));
	}

	if (txtItems != "")
	{
		$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action": "selectItem", "txtItems": txtItems}
		}).done(function(html) {
			if (html != "")
			{
				$('#sizeValues').html(html);
				var firstRadio = $('input:radio[name="sizeId"]:first');
				firstRadio.attr('checked','checked');
				firstRadio.focus();
			} else {
				$("#txtItems").prop('disabled', false);
			}
		});
	}
}

var selItem2 = function() {

	var txtItems = $('#txtItems2').val();
		txtItems = txtItems.replace(/^0+/, '');
	$("#txtItems2").prop('disabled', true);
	if (isNaN(parseInt(txtItems)))
	{
		$('#txtItems2').val("");
	} else {
		$('#txtItems2').val(parseInt(txtItems));
	}

	if (txtItems != "")
	{
		$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action": "selectItem2", "txtItems": txtItems}
		}).done(function(html) {
			if (html != "")
			{
				$('#sizeValues').html(html);
				$('input:text[name="sizeId2"]:first').select();
				$('#tableSizes').on('keyup', '.sizeId2',function () { 
				    this.value = this.value.replace(/[^0-9\.]/g,'');
				    if (this.value == "") {this.value = "0"; this.select();};
				});
			} else {
				$("#txtItems2").prop('disabled', false);
			}
		});
	}
}

$('#btnSubmitItem').click(function() {
	selItem();
});

$('#btnSubmitItem2').click(function() {
	selItem2();
});

$('#txtItems').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		selItem();
	}
});

$('#txtItems2').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		selItem2();
	}
});

var selSize = function() {

	var sizeId = $("input[name='sizeId']:checked").val();

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action": "selectSize", "sizeId": sizeId}
	}).done(function(html) {
		$('#qtyValue').html(html);
		$('#txtQty').focus();
		$('#txtQty').select();
	});
}

var selSize2 = function() {

	var txtItems    = $('#txtItems2').val();
	var processType = $('#processType').val();
	var sizeId      = new Array();
	var sizeVal     = new Array();
	var sumVal      = 0;
	var subtotal    = 0;
	var totalCost   = 0;
	var totalQty    = 0;
	var rtp         = new Array();
	var qty         = new Array();
	var cost        = new Array();

	$(".sizeId2").each(function()
	{
		sizeId.push ($(this).attr('id'));
	});

	$(".sizeId2").each(function()
	{
		sizeVal.push ($(this).val());
		sumVal += $(this).val();
	});

	if (sumVal > 0) {
		$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			data: {"action": "selectSize2", "sizeId": sizeId, "sizeVal": sizeVal,
				   "processType": processType, "txtItems": txtItems}
		}).done(function(html) {
			$('#tableItems').append(html);
			$('#txtItems2').val("").focus();
			$('#sizeValues').html("");
			$(".group1").colorbox({rel:'group1'});
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
				//console.log(rtp[i] + qty[i]);
				subtotal  += (rtp[i] * qty[i]);
				totalCost += (cost[i] * qty[i]);
				totalQty  += parseInt(qty[i]);
			}

			$(".totalQty").text(totalQty);
			$(".subtotal").text(subtotal.toFixed(2));
			$(".total").text(subtotal.toFixed(2));
			$(".totalCost").text(totalCost.toFixed(2));
			$("#txtItems2").prop('disabled', false);
			$("#txtItems2").focus();
		});
	} else {
		alert("Error !");
	}
}

$('#selSize').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		if ($("#btnSubmitSize").length > 0) {
			selSize();
		}
		if ($("#btnSubmitSize2").length > 0) {
			selSize2();
		}
	}
});

$('#selSize').on('click', '#btnSubmitSize', function() {
	selSize();
});

$('#selSize').on('click', '#btnSubmitSize2', function() {
	selSize2();
});

var selQty = function() {

	var subtotal      = 0;
	var totalCost     = 0;
	var totalQty      = 0;
	var rtp           = new Array();
	var qty           = new Array();
	var cost          = new Array();
	var txtItems      = $('#txtItems').val();
	var sizeId        = $("input[name='sizeId']:checked").val();
	var txtQty        = $('#txtQty').val();
	var processType   = $('#processType').val();
	var itemTransType = new Array();

	if (txtItems != "" && txtQty > 0 && txtQty == ~~txtQty)
	{
		$('#txtQty').blur();
		$('#btnSubmitQty').prop("disabled", true);
		$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			data: {"action":"selectQty", "processType": processType, "txtItems2":txtItems,
				   "sizeId2":sizeId, "txtQty":txtQty}

		}).done(function(html) {
			$('#tableItems').append(html);
			$('#txtItems').val("").focus();
			$('#sizeValues').html("");
			$('#qtyValue').html("");
			$(".group1").colorbox({rel:'group1'});

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

			for (var i = 0; i < rtp.length; i++)
			{
				//console.log(rtp[i] + qty[i]);
				subtotal  += (rtp[i] * qty[i]);
				totalCost += (cost[i] * qty[i]);
				totalQty  += parseInt(qty[i]);
			}
			$(".totalQty").text(totalQty);
			$(".subtotal").text(subtotal.toFixed(2));
			$(".total").text(subtotal.toFixed(2));
			$(".totalCost").text(totalCost.toFixed(2));
			$("#txtItems").prop('disabled', false);
			$("#txtItems").focus();

			// The ID of the extension we want to talk to.
			var editorExtensionId = "ldflcgdodifaiblhadipgnhkpjndbmio";
			var message = "Subtotal: " + rtp[rtp.length-1] + "\x0A\x0DTotal: " + subtotal.toFixed(2);
			// Make a simple request:
			try {
				chrome.runtime.sendMessage(editorExtensionId, {subtotal: message}, function(response) {});
			}
			catch(e) {
				console.log(e);
			}
		});
	}
}

$('#selQty').on('click', '#btnSubmitQty', function() {
	selQty();
});

$('#selQtyEx').on('click', '#btnSubmitQty', function() {
	selQty();
});

$('#qtyValue').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		selQty();
	}
});

$('#tableItems').on('click', '#removeItem', function() {

	if (confirm("Are you sure?"))
	{
		$(this).closest('tr').remove();

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

		// The ID of the extension we want to talk to.
		var editorExtensionId = "ldflcgdodifaiblhadipgnhkpjndbmio";
		var message = "Subtotal: " + rtp[rtp.length-1] + "\x0A\x0DTotal: " + subtotal.toFixed(2);
		// Make a simple request:
		try {
			chrome.runtime.sendMessage(editorExtensionId, {subtotal: message}, function(response) {});
		}
		catch(e) {
			console.log(e);
		}
	}
	return false;
});

$("#priceTable").on("keyup", "#cashDisc", function() {

	var subtotal  = $(".subtotal").text();
	var cashDisc  = $("#cashDisc").val();

	if (subtotal != 0)
	{
		$(".total").text((subtotal - cashDisc).toFixed(2));
		var percDisc = (cashDisc/subtotal) * 100;
		$("#percDisc").val(percDisc.toFixed(2));
	} else {
		$("#percDisc").val('0.00');
		$("#cashDisc").val('0.00');
	}

}).on("keyup", "#percDisc", function() {

	var subtotal  = $(".subtotal").text();
	var percDisc  = $("#percDisc").val();
	var cashDisc  = (percDisc * subtotal) / 100;

	if (subtotal != 0)
	{
		$("#cashDisc").val(cashDisc.toFixed(2));
		$(".total").text((subtotal - cashDisc).toFixed(2));
	} else {
		$("#percDisc").val('0.00');
		$("#cashDisc").val('0.00');
	}
});

var resetWindow = function() {
	$('#txtItems').val('');
	$('#sizeValues').html('');
	$('#qtyValue input').remove();
	$("#txtItems").prop('disabled', false);
	$('#txtItems').focus();
}

var resetWindow2 = function() {
	$('#txtItems2').val('');
	$('#sizeValues').html('');
	$("#txtItems2").prop('disabled', false);
	$('#txtItems2').focus();
}

var resetAll = function() {
	$.colorbox.close();
	$('#comments').val('');
	$('.customerName').text('');
	$('.customerId').text('');
	$('.subtotal').text('0.00');
	$('.totalCost').text('0.00');
	$('#percDisc').val('0.00');
	$('#cashDisc').val('0.00');
	$('.total').text('0.00');
	$('.totalQty').text('0');
	$('.subtotal2').text('0.00');
	$('.totalCost2').text('0.00');
	$('#percDisc2').val('0.00');
	$('#cashDisc2').val('0.00');
	$('.total2').text('0.00');
	$('.totalQty2').text('0');
	$('.dropItems').remove();
	$('#txtItems').val('');
	$('#sizeValues').html('');
	$('#qtyValue input').remove();
	$("#txtItems").prop('disabled', false);
	$('#txtItems').focus();
	$("#salesMen").val($("#salesMen option:first").val());
	$("#tableItems").find("tr:gt(0)").remove();
}

var payNow = function() {

	var processType  = $("#processType").val();
	var comments     = $("#comments").val();
	var subtotal     = $(".subtotal").text();
	var total        = $(".total").text();
	var totalCost    = $(".totalCost").text();
	var totalQty     = $(".totalQty").text();
	var customerName = $(".customerName").text();
	var customerId   = $(".customerId").text();
	var cashDisc     = $("#cashDisc").val();
	var paymentType  = $("#paymentType").val();
	var salesMan     = $("#salesMen").val();
	var currentDate  = $(".currentDate").text();
	var split        = $("#txtCash").val();
	var voucherId    = $("#voucherCode").attr("data-voucherId");
	var itemId       = new Array();
	var sizeId       = new Array();
	var qty          = new Array();
	var rtp          = new Array();
	var cost      	 = new Array();

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

	if (itemId.length != 0 && customerName != '' && paymentType != 0) {
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxCalls.php",
				type: "post",
				data: {"action":"pay", "processType": processType, "subtotal": subtotal,
				"total": total, "customerName": customerName, "customerId": customerId,
				"cashDisc": cashDisc, "paymentType": paymentType, "salesMan": salesMan, "itemId": itemId,
				"sizeId": sizeId, "totalQty": totalQty, "qty": qty, "rtp": rtp, "comments": comments,
				"currentDate": currentDate, "split": split, "cost": cost, "totalCost": totalCost, "voucherId": voucherId}
			}).done(function(html) {
				if (html == "error") {
					alert('Please login again!');
				} else {
					resetAll();
					requestSent = false;
					alert('Payed Successfully!');
					document.location.href = "invoiceDetail.php?invoNo=" + html;
				}
			});
			$('#payWindow').unbind('keydown');
			$('#btnPay').unbind('click');
		}
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

function getSalesManId(sel) {
    var key = sel.options[sel.selectedIndex].value;
    $(".salesMan").text(key);
}

var returnNow = function() {

	var processType  = $("#processType").val();
	var comments     = $("#comments").val();
	var subtotal     = $(".subtotal").text();
	var total        = $(".total").text();
	var totalCost    = $(".totalCost").text();
	var totalQty     = $(".totalQty").text();
	var customerName = $(".customerName").text();
	var customerId   = $(".customerId").text();
	if (processType == "return") {
		var salesMan     = $(".salesMan").text();
	}else{
		var salesMan     = $("#salesMen").val();
	}
	var cashDisc     = $("#cashDisc").val();
	var paymentType  = $("#paymentType").val();
	var currentDate  = $(".currentDate").text();
	var refInvoNo    = $(".invoNo").text();
	var itemId       = new Array();
	var sizeId       = new Array();
	var qty          = new Array();
	var rtp          = new Array();
	var cost      	 = new Array();

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

	if (itemId.length != 0 && customerName != '' && paymentType != 0) {
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxCalls.php",
				type: "post",
				data: {"action":"return", "processType": processType, "subtotal": subtotal,
				"total": total, "customerName": customerName, "customerId": customerId,
				"cashDisc": cashDisc, "paymentType": paymentType, "salesMan": salesMan, 
				"refInvoNo": refInvoNo, "itemId": itemId, "sizeId": sizeId, "totalQty": totalQty,
				"qty": qty, "rtp": rtp, "comments": comments,"currentDate": currentDate,
				"cost": cost, "totalCost": totalCost}
			}).done(function(html) {
				if (html == "error") {
					alert('Please login again!')
				} else {
					resetAll();
					requestSent = false;
					alert('Returned Successfully!');
					document.location.href = "invoiceDetail.php?invoNo=" + html;
				}
			});
			$('#returnWindow').unbind('keydown');
			$('#btnReturn').unbind('click');
		}
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

var exchangeNow = function() {

	var processType   = $("#processType").val();
	var comments      = $("#comments").val();
	var subtotal      = $(".subtotal").text();
	var total         = $(".total").text();
	var totalCost     = $(".totalCost").text();
	var totalQty      = $(".totalQty").text();
	var customerName  = $(".customerName").text();
	var customerId    = $(".customerId").text();
	var salesMan      = $("#salesMen").val();
	var cashDisc      = $("#cashDisc").val();
	var paymentType   = $("#paymentType").val();
	var currentDate   = $(".currentDate").text();
	var refInvoNo     = $(".invoNo").text();
	var itemId        = new Array();
	var sizeId        = new Array();
	var qty           = new Array();
	var rtp           = new Array();
	var cost      	  = new Array();
	var itemTransType = new Array();	

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

	$(".itemTransType").each(function() {
		itemTransType.push ($(this).text());
	});

	if (itemId.length != 0 && customerName != '' && paymentType != 0) {
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxCalls.php",
				type: "post",
				data: {"action":"exchange", "processType": processType, "subtotal": subtotal,
				"total": total, "customerName": customerName, "customerId": customerId,
				"cashDisc": cashDisc, "paymentType": paymentType, "salesMan": salesMan, 
				"refInvoNo": refInvoNo, "itemId": itemId, "sizeId": sizeId, "totalQty": totalQty,
				"qty": qty, "rtp": rtp, "itemTransType": itemTransType, "comments": comments,
				"currentDate": currentDate, "cost": cost, "totalCost": totalCost}
			}).done(function(html) {
				if (html == "error") {
					alert('Please login again!')
				} else {
					resetAll();
					requestSent = false;
					alert('Exchanged Successfully!');
					document.location.href = "invoiceDetail.php?invoNo=" + html;
				}
			});
			$('#exchangeWindow').unbind('keydown');
			$('#btnExchange').unbind('click');
		}
	} else {
		alert("Error, Please Complete Empty Fields!");
	}
}

$('#btnReset').click(function() {
	resetWindow();
});

$('#btnReset2').click(function() {
	resetWindow2();
});

$('#paymentType').bind('keydown', function(e) {
	if (e.keyCode == 13) {

		e.preventDefault();

		if ($("#btnPay").length > 0) {
			payNow();
		}

		if ($("btnReturn").length > 0) {
			returnNow();
		}

		if ($("btnReturn").length > 0) {
			returnNow();
		}
	}
});

$('#payWindow').bind('keydown', function(e) {
	if (e.keyCode == 115) {
		e.preventDefault();
		payNow();
	}
});

$('#returnWindow').bind('keydown', function(e) {
	if (e.keyCode == 115) {
		e.preventDefault();
		returnNow();
	}
});

$('#exchangeWindow').bind('keydown', function(e) {
	if (e.keyCode == 115) {
		e.preventDefault();
		exchangeNow();
	}
});

$("#btnPay").click(function() {
	payNow();
});

$("#btnReturn").click(function() {
	returnNow();
});

$("#btnExchange").click(function() {
	exchangeNow();
});

$('#tblTransReport').on('click', '#viewInvnDetails', function(e) {

	var transNo = $(this).attr('transNo');
	var wrhsId  = $(this).attr('wrhsId');

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"getInvnDetails", "transNo": transNo, "wrhsId": wrhsId}
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

$('#tblTransReport').on('click', '#viewInvnComments', function(e) {

	var transNo = $(this).attr('transNo');
	var wrhsId  = $(this).attr('wrhsId');

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"getInvnComments", "transNo": transNo, "wrhsId": wrhsId}
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

$('#tblTransReport').on('click', '#viewInvoDetails', function(e) {

	var invoNo     = $(this).attr('invoNo');

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"getInvoDetails", "invoNo": invoNo}
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

$('#tblTransReport').on('click', '#viewInvoComments', function(e) {

	var invoNo = $(this).attr('invoNo');

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"getInvoComments", "invoNo": invoNo}
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

$('#tblTransReport').on('click', '.cancelTrans', function(e) {
	var thisLink = $(this);
	if (confirm("Are you sure?"))
	{
		var transNo = $(this).attr('transNo');
		var wrhsId  = $(this).attr('wrhsId');
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxCalls.php",
				type: "post",
				data: {"action":"cancelTrans", "transNo": transNo, "wrhsId": wrhsId}
			}).done(function(data) {
				if (data == "error") {
					alert('Please login again!')
				} else {
					thisLink.closest('td').html("Canceled");
					requestSent = false;
				}
			});
		}
	}
});

$('#tblTransReport').on('click', '.proceedTrans', function(e) {
	var thisLink = $(this);
	if (confirm("Are you sure?"))
	{
		var transNo = $(this).attr('transNo');
		var wrhsId  = $(this).attr('wrhsId');
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxCalls.php",
				type: "post",
				data: {"action":"proceedTrans", "transNo": transNo, "wrhsId": wrhsId}
			}).done(function(data) {
				if (data == "error") {
					alert('Please login again!')
				} else {
					thisLink.closest('td').html("Done");
					requestSent = false;
				}
			});
		}
	}
});

$("#frmSalesReport").on("change", "input[name=selBranch]:radio", function() {

	var selBranch = $("input[name=selBranch]:radio:checked").val();

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"getInvoValues", "selBranch": selBranch}
	}).done(function(data) {
		$("#frmSalesReport").html(data);

		$("#dateFrom").datepicker({
	        dateFormat: 'yy-mm-dd',
	        beforeShowDay: available
	    });

	    $("#dateTo").datepicker({
	        dateFormat: 'yy-mm-dd',
	        beforeShowDay: available
	    });
	});
});

$("#reportWrapper2").on("click", ".itemStockDetails", function(e) {

	e.preventDefault();
	var itemId    = $(this).attr('rel');
	var selBranch = $.urlParam('selBranch');

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"getItemStockDetails", "itemId": itemId, "selBranch": selBranch}
	}).done(function(data) {
		$(".itemDetails").html(data);
		$.colorbox
		({
			href:".itemDetails",
			inline:true,
			width:850,
			height:565
		});
	});
});

$("#frmStockReport").on("change", "#selDept", function() {

	var selectedDepts = [];

	$('#selDept :selected').each(function(i, selected) {
		selectedDepts[i] = $(selected).val(); 
	});

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action": "getSizeRanges", "selectedDepts": selectedDepts}
	}).done(function(data) {
		$("#selSize").html(data);
	});
});

$("#frmTransferReport").on("change", "#selYear", function() {

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

$("#frmStockReport").on("change", "#selZone", function() {

	var selectedZones = []; 

	$('#selZone :selected').each(function(i, selected)
	{ 
		selectedZones[i] = $(selected).val(); 
	});

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"getLocationRanges", "selectedZones": selectedZones}
	}).done(function(data) {
		$("#selLoc").html(data);
	});
});

$("#tblTransReport").on("change", "#selZone", function() {

	var selZone = $('#selZone').val(); 	

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"getZoneLocations", "selZone": selZone}
	}).done(function(data) {
		$("#selLocation").html(data);
	});
});

$("#tblTransReport").on("click", "#btnAddItemLocation", function() {

	var itemId = $('#txtItemId').text();
	var selLoc = $('#selLocation').val();	

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"addItemLocation", "itemId": itemId, "selLoc": selLoc}
	}).done(function(data) {
		alert(data);
		document.location.href = "addItemLocation.php";
	});
});

$("#tblTransReport").on("click", "#btnAddItemLocationAdmin", function() {

	var itemId = $('#txtItemId').text();
	var selLoc = $('#selLocation').val();

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"addItemLocation", "itemId": itemId, "selLoc": selLoc}
	}).done(function(data) {
		alert(data);
		document.location.href = "addItemLocationAdmin.php";
	});
});

$("#tblTransReport").on("click", ".deleteItemLocation", function(e) {

	var zoneId = $.urlParam('zoneId');
	var curId  = $(this).attr('id');	

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"deleteItemLocation", "curId": curId}
	}).done(function(data) {
		alert(data);
		document.location.href = "zone.php?zoneId=" + zoneId;
	});
	e.preventDefault();
});

$("#tblTransReport").on("click", ".deleteItemLocationAdmin", function(e) {

	var curId  = $(this).attr('id');	

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"deleteItemLocation", "curId": curId}
	}).done(function(data) {
		alert(data);
		document.location.href = "searchItemLocation.php";
	});
	e.preventDefault();
});

$("#tblTransReport").on("click", ".deleteItemDuplicates", function(e) {
	
	var curId  = $(this).attr('id');

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"deleteItemLocation", "curId": curId}
	}).done(function(data) {
		alert(data);
		document.location.href = "duplicatesReport.php";
	});
	e.preventDefault();
});

$("#tblTransReport").on("click", ".viewAllLoc", function(e) {

	var itemId = $(this).attr('itemId');

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action":"viewAllLocations", "itemId": itemId}
	}).done(function(data) {
		$("#viewAllLocations").html(data);
		$.colorbox
		({
			href:"#viewAllLocations",
			inline:true
		});
	});
	$.colorbox.resize();
	e.preventDefault();
});

$("#tblTransReport").on("click", "#btnEditItemLocation", function() {

	var itemId = $('#txtItemId').text();
	var selLoc = $('#selLocation').val();
	var curId  = $.urlParam('id');	

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action": "editItemLocation", "curId": curId, "itemId": itemId, "selLoc": selLoc}
	}).done(function(data) {
		alert(data);
		document.location.href = "editItemLocation.php?id=" + curId;
	});
});

$("#tblTransReport").on("click", "#btnEditItemLocationAdmin", function() {

	var itemId = $('#txtItemId').text();
	var selLoc = $('#selLocation').val();
	var curId  = $.urlParam('id');

	$.ajax({
		url: "_inc/ajaxCalls.php",
		type: "post",
		data: {"action": "editItemLocation", "curId": curId, "itemId": itemId, "selLoc": selLoc}
	}).done(function(data) {
		alert(data);
		document.location.href = "editItemLocationAdmin.php?id=" + curId;
	});
});

$("#tblTransReport").on("click", "#btnEditItemQty", function() {

	var itemQty = $('#itemQty').val();
	var itemId  = $('#txtItemId').text();

	if (itemQty > 0 && itemQty == ~~itemQty) {
		$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			data: {"action": "editItemQty", "itemQty": itemQty, "itemId": itemId}
		}).done(function(data) {
			alert(data);
			document.location.href = "qtySwap.php?itemId=" + itemId + "&btnSubmit=Submit";
		});
	}
});

$.urlParam = function(name) {
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
}

$('#exportBranchStock').click(function() {

	var branchValue = $("input[name='selBranch']:checked").val();

	$.ajax({
		url: "_inc/ajaxReports.php",
		type: "post",
		data: {"action": "exportBranchStock", "branchValue": branchValue}
	}).done(function(data) {
		document.location.href = data;
	});
});

$('#frmTransferReport').on('click', '#exportMerchandise', function(e) {

	var query  = $(this).attr('query');
	var deptId = $(this).attr('deptId');
	if (deptId == "All") {
		alert("Please select department!");
	} else {
		$.ajax({
			url: "_inc/ajaxCalls.php",
			type: "post",
			data: {"action": "exportMerchandise", "query": query}
		}).done(function(data) {
			document.location.href = data;
		});
	}
	e.preventDefault();
});

$('#frmSalesReport').on('click', '#exportBranchInvoices', function() {

	var branchValue = $("input[name='selBranch']:checked").val();
	var dateFrom    = $("#dateFrom").val();
	var dateTo      = $("#dateTo").val();

	if (dateFrom == "" || dateTo == "") {
		alert("Please select date range !");
	} else {
		$.ajax({
			url: "_inc/ajaxReports.php",
			type: "post",
			data: {"action": "exportBranchInvoices", "branchValue": branchValue,
				   "dateFrom": dateFrom, "dateTo": dateTo}
		}).done(function(data) {
			document.location.href = data;
		});
	}
});

$('#frmSalesReport').on('click', '#exportBranchInvoices2', function() {

	var branchValue = $("input[name='selBranch']:checked").val();
	var dateFrom    = $("#dateFrom").val();
	var dateTo      = $("#dateTo").val();

	if (dateFrom == "" || dateTo == "") {
		alert("Please select date range !");
	} else {
		$.ajax({
			url: "_inc/ajaxReports.php",
			type: "post",
			data: {"action": "exportBranchInvoices2", "branchValue": branchValue,
				   "dateFrom": dateFrom, "dateTo": dateTo}
		}).done(function(data) {
			document.location.href = data;
		});
	}
});

$('#exportBranchInventory').click(function() {

	var branchValue = $("input[name='selBranch']:checked").val();
	var dateFrom    = $("#dateFrom").val();
	var dateTo      = $("#dateTo").val();

	if (dateFrom == "" || dateTo == "") {
		alert("Please select date range !");
	} else {
		$.ajax({
			url: "_inc/ajaxReports.php",
			type: "post",
			data: {"action": "exportBranchInventory", "branchValue": branchValue,
				   "dateFrom": dateFrom, "dateTo": dateTo}
		}).done(function(data) {
			document.location.href = data;
		});
	}
});

$('.itemSizeStockDetail').click(function(e) {

	var itemId = $(this).attr('itemId');
	var sizeId = $(this).attr('sizeId');
	var locId  = $(this).attr('locId');

	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action": "getItemStockSizeDetails", "itemId": itemId, "sizeId": sizeId, "locId": locId}
	}).done(function(data) {
		$("#itemSizeTransfer").html(data);
		$.colorbox
		({
			href:"#itemSizeTransfer",
			inline:true, overlayClose:false,
			onLoad:function() { $('#cboxClose').remove() }
		});
		$(document).bind('cbox_complete', function(){
			var $table = $('table.scroll');
			console.log($table);
			$table.floatThead({
			    scrollContainer: function($table){
					return $table.closest('.wrapper');
				}
			});
			$table.trigger('reflow');
		});
		
	});
	e.preventDefault();
});

$('#itemSizeTransfer').on('keyup', '.itemSizeQty', function(){
	var itemSizeQty = 0;
	var sizeId      = $(this).attr("sizeId");
	$(".itemSizeQty[sizeId='"+sizeId+"']").each(function() {
		itemSizeQty += Number(($(this).val()));
	});
	var fromItemSizeQty = Number($(".fromItemSizeQty2[sizeId='"+sizeId+"']").text());
	$(".fromItemSizeQty[sizeId='"+sizeId+"']").val(fromItemSizeQty - itemSizeQty);
});

$('#itemSizeTransfer').on('click', '#saveTransfer', function(){

	var allSizes  = Array();
	var itemIds   = Array();
	var sizeIds   = Array();
	var itemQtys  = Array();
	var locIds    = Array();
	var fromLocId = $('#fromLocId').text();

	$(".itemSizeQty").each(function() {
		if (Number($(this).val()) != "" && Number($(this).val()) != 0) {
			itemIds.push($(this).attr('itemId'));
			sizeIds.push($(this).attr('sizeId'));
			itemQtys.push($(this).val());
			locIds.push($(this).attr('locId'));
		};
	});
	if (confirm("Are you sure?"))
	{
		if(!requestSent) {
			requestSent = true;
			$.ajax({
				url: "_inc/ajaxGeneral.php",
				type: "post",
				data: {"action": "saveTransfer", "itemIds": itemIds, "locIds": locIds, "itemQtys": itemQtys,
					   "sizeIds": sizeIds, "fromLocId": fromLocId}
			}).done(function(html) {
				if (html == "error") {
					alert('Please login again!');
				} else {
					$.colorbox.close();
					requestSent = false;
					alert("Done.");
				}
			});
		}
	}
});

$('#selectSlip').on('click', '#getSlip', function(){
	var transNo = $("#selSlip").find('option:selected').val();

	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action": "setSlip", "transNo": transNo}
	}).done(function(html) {
		if (html == "error") {
			alert('Please login again!');
		} else {
			$.colorbox.close();
			$("#slipNo").text(transNo);
			$("#viewSlip").attr('slipNo', transNo);
			$("#activateSlip").attr('slipNo', transNo);
			$("#exportSlip").attr('slipNo', transNo);
		}
	});
});

$('#changeSlip').click(function(){
	$.colorbox({
		href:"#selectSlip", inline:true, overlayClose:false, fixed:true, width:"250px",
		onComplete:function(){ $("#selSlip").focus(); },
		onLoad:function(){ $("#cboxClose").remove() }
	});
});

$('#viewSlip').on('click', function(){
	var slipNo = $(this).attr("slipNo");

	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action": "getSlipDetails", "slipNo": slipNo}
	}).done(function(data) {
		if (data == "error") {
			alert('Please login again!');
		} else {
			$("#slipDetails").html(data);
			$.colorbox
			({
				href:"#slipDetails",
				inline:true,
				fixed:true,
				height: "500px",
				width: "600px"
			});
		}
	});
	$.colorbox.resize();
});

$('#activateSlip').on('click', function(){
	var slipNo = $(this).attr("slipNo");

	if (confirm("Are you sure you want to activate?"))
	{
		$.ajax({
			url: "_inc/ajaxGeneral.php",
			type: "post",
			data: {"action": "activateSlip", "slipNo": slipNo}
		}).done(function(data) {
			if (data == "error") {
				alert('Please login again!');
			} else if (data == "bad") {
				alert("There's no data to activate !");
			} else {
				alert("Done.");
				location.reload();
			}
		});
	}
	$.colorbox.resize();
});

$('#exportSlip').on('click', function(){
	var slipNo = $(this).attr("slipNo");
	if (confirm("Are you sure you want to export?"))
	{
		$.ajax({
			url: "_inc/ajaxReports.php",
			type: "post",
			data: {"action": "exportSlip", "slipNo": slipNo}
		}).done(function(data) {
			if (data == "error") {
				alert('Please login again!');
			} else {
				document.location.href = data;
			}
		});
	}
});

$('#slipDetails').on('click', '.removeSlip', function(e){
	var slipId     = $(this).attr('slipId');
	var removeSlip = $(this);

	$.ajax({
		url: "_inc/ajaxGeneral.php",
		type: "post",
		data: {"action": "removeSlip", "slipId": slipId}
	}).done(function(html) {
		if (html == "error") {
			alert('Please login again!');
		} else {
			removeSlip.closest('tr').remove();
		}
	});
	e.preventDefault();
});

$('#slipDetails').on('click', '#removeAllSlips', function(e){
	var slipNo     = $(this).attr('slipNo');
	if (confirm("Are you sure you want to export?"))
	{
		$.ajax({
			url: "_inc/ajaxGeneral.php",
			type: "post",
			data: {"action": "removeAllSlips", "slipNo": slipNo}
		}).done(function(html) {
			if (html == "error") {
				alert('Please login again!');
			} else {
				$('#tblSlipDetails > tbody').empty();
			}
		});
	}
});

$(document).ajaxStart(function() {
	$("#loading").show();
	$("#loadingWrapper").show();
}).ajaxStop(function() {
	$("#loading").hide();
	$("#loadingWrapper").hide();
});

//var x = $("#tableSizes input").serializeArray();
//x[1].value