/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_PaymentUtilsViewJS = MM_Core.extend({
	showPaymentOptions: function(userId, accessType, accessTypeId, lastActionParams)
	{
		var values = {
	        mm_module: "payment_options",
	        userId: userId,
	        accessType: accessType,
	        accessTypeId: accessTypeId,
	        lastActionParams: lastActionParams
	    };

		mm_pymtdialog.showDialog("mm-payment-options-dialog", this.module, 450, 460, "Payment Options", values, "", "mm_pymtdialog");
	},
	
	getPaymentOptionsList: function()
	{	
		productId = jQuery("#mm-product-selector").val();
		userId = jQuery("#mm-user-id").val();
		if(productId != 0 && userId != 0)
		{
			var values = {
	            mm_action: "getPaymentOptionsList",
	            mm_product_id: productId,
	            mm_user_id: userId
	        };

	        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	        ajax.send(values, false, "pymtutils_js", "paymentOptionsListCallback");
		}
		else
		{
			jQuery("#mm-payment-options-list").hide();
		}
	},
	
	paymentOptionsListCallback: function(data)
	{
		if(data == undefined)
		{
			jQuery("#mm-payment-options-list").hide();
			alert("No response received");
		}
		else if(data.type == "error")
		{
			jQuery("#mm-payment-options-list").hide();
			alert(data.message);
		}
		else
		{
			jQuery("#mm-payment-options-list").html(data.message);
			jQuery("#mm-payment-options-list").show();
		}
	},
	
	changeMembershipStatus: function(memberId, membership_id, newStatus, redirectUrl)
	{
		var msg = "";
		
		switch(parseInt(newStatus))
		{		
			case 2:
				msg = "Are you sure you want to cancel your membership?";
				break;
			
			case 4:
				msg = "Are you sure you want to pause your membership?";
				break;
				
			default:
				msg = "Invalid status '" + newStatus + "'";
				break;
		}
		
		this.id = memberId; 
		
		var values = {
			mm_id: this.id,
			mm_membership_id: membership_id,
			mm_new_status: newStatus,
			mm_redirect_url: redirectUrl,
			mm_action: "changeMembershipStatus"
	    };
		
		var doContinue = confirm(msg);
		if(doContinue)
		{
			var ajax = new MM_Ajax(false, this.module, this.action, this.method);
			ajax.send(values, false, 'pymtutils_js', "accessRightsUpdateHandler"); 
		}
	},
	
	changeBundleStatus: function(memberId, bundleId, newStatus, redirectUrl)
	{	
		var msg = "";
		
		switch(parseInt(newStatus))
		{		
			case 2:
				msg = "Are you sure you want to cancel this bundle?";
				break;
				
			case 4:
				msg = "Are you sure you want to pause this bundle?";
				break;
				
			default:
				msg = "Invalid status '" + newStatus + "'";
				break;
		}
		
		this.id = memberId; 
			
		var values = {
			mm_id: this.id,
			mm_bundle_id: bundleId,
			mm_new_status: newStatus,
			mm_redirect_url: redirectUrl,
			mm_action: "changeBundleStatus"
	    };
	    
		var doContinue = confirm(msg);
		if(doContinue)
		{
			var ajax = new MM_Ajax(false, this.module, this.action, this.method);
			ajax.send(values, false, 'pymtutils_js', "accessRightsUpdateHandler"); 
		}
	},
	
	applyFreeBundle: function(memberId, bundleId, redirectUrl)
	{	
		this.id = memberId; 
			
		var values = {
			mm_id: this.id,
			mm_bundle_id: bundleId,
			mm_redirect_url: redirectUrl,
			mm_action: "applyFreeBundle"
	    };
	    
		var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		ajax.send(values, false, 'pymtutils_js', "accessRightsUpdateHandler"); 
	},
	
	showPaymentConfirmation: function(userId, productId, height, width)
	{
		var values = {
	        mm_module: "payment_confirmation",
	        userId: userId,
	        productId: productId
	    };
		
		if(!height)
		{
			height = 200;
		}
		
		if(!width)
		{
			width = 450;
		}

		mm_pymtdialog.showDialog("mm-payment-confirmation-dialog", this.module, width, height, "Order Confirmation", values, "", "mm_pymtdialog");
	},
	
	showAdminPaymentConfirmation: function(userId, productId)
	{
		var values = {
            mm_action: "placeOrderCardOnFile",
            mm_product_id: productId,
            mm_user_id: userId,
            mm_source: "admin"
        };
		
		var msg = "Are you sure you want to charge the member's card on file for this product?";
		var doContinue = confirm(msg);
		if(doContinue)
		{
			var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	        ajax.send(values, false, "pymtutils_js", "placeOrderCardOnFileCallback");
		}
	},
	
	placeOrderCardOnFile: function(userId, productId, sourceId)
	{
		var values =  {};
		
		var doContinue = true;
		if(jQuery("#mm_1clickpurchase_form").length > 0)
		{
			doContinue = oneclickpurchase_js.validate();
			
			// grab form fields
			if(doContinue)
			{
				var form_obj = new MM_Form('mm_1clickpurchase_form');
			    values = form_obj.getFields();
			}
		}
		
		if(doContinue)
		{
			pymtutils_js.closeDialog(mm_pymtdialog);
			
			values.mm_action = "placeOrderCardOnFile",
            values.mm_product_id = productId,
            values.mm_user_id = userId,
            values.mm_source = sourceId
			
	        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	        ajax.send(values, false, "pymtutils_js", "placeOrderCardOnFileCallback");
		}
	},
	
	placeOrderCardOnFileCallback: function(data)
	{
		if(data == undefined)
		{
			alert("There was an error placing your order");
		}
		else if(data.type == "error")
		{
			alert(data.message);
		}
		else
		{
			document.location.href = data.url;
		}
	},
	
	accessRightsUpdateHandler: function(data)
	{	
		if(data.type == "error")
		{
			if(data.message.length > 0)
			{  
				alert(data.message);
				return false;
			}
			return false;
		}
		else 
		{
			if(data.url != undefined && data.url.length > 0)
			{
				document.location.href = data.url;
			}
			else
			{
				if(data.message != undefined && data.message.length > 0)
				{
					alert(data.message);
				}
				
				var url = document.location.href;
				var index = url.indexOf("message");
				// remove any message from the URL before refreshing the page
				if(index != -1)
				{
					url = url.substring(0, index-1);
				}
				document.location.href = url;
			}
		}
	},
	
	checkIfPaymentRequired: function(accessType, accessTypeId, callbackFunction, callbackReference)
	{
		if(accessType == undefined || accessType == "") 
		{
			alert("Missing required parameter 'accessType' in mm-payment_utils.js.checkIfPaymentRequired");
			return false;
		}
		
		if(accessTypeId == undefined || accessTypeId == "") 
		{
			alert("Missing required parameter 'accessTypeId' in mm-payment_utils.js.checkIfPaymentRequired");
			return false;
		}
		
		if(callbackFunction == undefined || callbackFunction == "") 
		{
			alert("Missing required parameter 'callbackFunction' in mm-payment_utils.js.checkIfPaymentRequired");
			return false;
		}
		
		this.callbackReference = callbackReference;
		this.callbackFunction = callbackFunction;
		
		var values = {
            mm_action: "checkIfPaymentRequired",
            accessType: accessType,
            accessTypeId: accessTypeId
        };

        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
        ajax.send(values, false, "pymtutils_js", "dfltCallbackHandler"); 
	},
	
	dfltCallbackHandler: function(data) 
	{
		result = false;
		
		if(data != undefined)
		{
			if(data.type == "error")
			{
				result = data;
			}
			else
			{
				result = data.message;
			}
		}
		else
		{
			result = false;
		}
		
		if(this.callbackReference != undefined && this.callbackReference != "")
		{
			eval(this.callbackReference + "." + this.callbackFunction + "(result)");
		}
		else
		{
			eval(this.callbackFunction + "(result)");	
		}
	}
});
var callbackReference = "";
var callbackFunction = "";
var mm_pymtdialog = new MM_DialogJS();
var pymtutils_js = new MM_PaymentUtilsViewJS("MM_PaymentUtilsView", "");