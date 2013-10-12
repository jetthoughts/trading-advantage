<script>
function braintreeTestModeChangeHandler()
{
	if(jQuery("#braintree_use_test_gateway").is(":checked"))
	{
		jQuery("#braintree-test-account-info").show();
		
		jQuery("#braintree-merchant-id-label").html("Test Merchant ID");
		jQuery("#braintree_test_merchant_id").show();
		jQuery("#braintree_live_merchant_id").hide();
		
		jQuery("#braintree-public-key-label").html("Test Public Key");
		jQuery("#braintree_test_public_key").show();
		jQuery("#braintree_live_public_key").hide();
		
		jQuery("#braintree-private-key-label").html("Test Private Key");
		jQuery("#braintree_test_private_key").show();
		jQuery("#braintree_live_private_key").hide();
	}
	else
	{
		jQuery("#braintree-test-account-info").hide();
		
		jQuery("#braintree-merchant-id-label").html("Live Merchant ID");
		jQuery("#braintree_test_merchant_id").hide();
		jQuery("#braintree_live_merchant_id").show();
		
		jQuery("#braintree-public-key-label").html("Live Public Key");
		jQuery("#braintree_test_public_key").hide();
		jQuery("#braintree_live_public_key").show();
		
		jQuery("#braintree-private-key-label").html("Live Private Key");
		jQuery("#braintree_test_private_key").hide();
		jQuery("#braintree_live_private_key").show();
	}
}

function showBraintreeTestCardNumbers()
{
	var str = "";

	str += "You can use the following test credit card numbers when testing payments.\n";
	str += "The expiration date must be set to the present date or later:\n\n";
	str += "- Visa: 4111111111111111\n";
	str += "- MasterCard: 5555555555554444\n";
	str += "- American Express: 378282246310005\n";
	str += "- Discover: 6011111111111117\n";
	str += "- JCB: 3530111333300000";

	alert(str);
}

jQuery(function($){
	braintreeTestModeChangeHandler();
});
</script>

<div style="padding:10px;">
<img src='<?php echo MM_Utils::getImageUrl("braintree"); ?>' />

<div style="margin-top:5px; margin-bottom:10px;">
<a href='http://support.membermouse.com/customer/portal/articles/1285000-configuring-braintree-payments' target='_blank'>Need help configuring Braintree?</a>
</div>

<div style="margin-bottom:10px;">
	<input type='checkbox' value='true' <?php echo (($p->isInTestMode()==true)?"checked":""); ?> id='braintree_use_test_gateway' name='payment_service[braintree][test_mode]' onclick="braintreeTestModeChangeHandler()" />
	Enable Test Mode
</div>

<div id="braintree-test-account-info" style="margin-bottom:10px; margin-left:10px; <?php echo (($p->isInTestMode()==true)?"":"display:none;"); ?>">
	<div style="margin-bottom:5px;">
		<img src="<?php echo MM_Utils::getImageUrl("world_go")?>" style="vertical-align:middle;" /> 
		<a href="https://sandbox.braintreegateway.com/" target="_blank">Log Into Sandbox Account</a>
	</div>
	<div style="margin-bottom:5px;">
		<img src="<?php echo MM_Utils::getImageUrl("creditcards")?>" style="vertical-align:middle;" title="Test Card Numbers" /> 
		<a href="javascript:showBraintreeTestCardNumbers()">Test Credit Card Numbers</a>
	</div>
	<div>
		<img src="<?php echo MM_Utils::getImageUrl("wrench_orange")?>" style="vertical-align:middle;" title="Setup Test Data" /> 
		<a href="<?php echo MM_ModuleUtils::getUrl(MM_ModuleUtils::getPage(), MM_MODULE_TEST_DATA); ?>" target="_blank">Configure Test Data</a>
	</div>
</div>

<div style="margin-bottom:10px;">
	<span id="braintree-merchant-id-label">Merchant ID</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getTestMerchantId(); ?>' id='braintree_test_merchant_id' name='payment_service[braintree][test_merchant_id]' style='width: 275px;' />
	</p>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getLiveMerchantId(); ?>' id='braintree_live_merchant_id' name='payment_service[braintree][live_merchant_id]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	<span id="braintree-public-key-label">Public Key</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getTestPublicKey(); ?>' id='braintree_test_public_key' name='payment_service[braintree][test_public_key]' style='width: 275px;' />
	</p>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getLivePublicKey(); ?>' id='braintree_live_public_key' name='payment_service[braintree][live_public_key]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	<span id="braintree-private-key-label">Private Key</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getTestPrivateKey(); ?>' id='braintree_test_private_key' name='payment_service[braintree][test_private_key]' style='width: 275px;' />
	</p>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getLivePrivateKey(); ?>' id='braintree_live_private_key' name='payment_service[braintree][live_private_key]' style='width: 275px;' />
	</p>
</div>

<!-- div style="margin-bottom:10px;">
	Notification URL
	
	<p style="margin-left:10px;">
		<?php $ipnUrl = WP_PLUGIN_URL.'/'.MM_PLUGIN_NAME."/x.php?service=braintree"; ?>
		 
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			 <input id="mm-braintree-ipn-url" type="text" value="<?php echo $ipnUrl; ?>" style="width:550px" onclick="jQuery('#mm-braintree-ipn-url').focus(); jQuery('#mm-braintree-ipn-url').select();" />
		</span>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" />
	Braintree uses webhooks to inform 3rd party systems when events happen within Braintree
	such as successful payments, subscription cancellations, etc. MemberMouse keeps member accounts in sync with Braintree by listening 
	for these notifications. In order for this to work, you must 
	<a href='#' target='_blank'>register the notification URL above with Braintree</a>.</p>
</div -->
</div>