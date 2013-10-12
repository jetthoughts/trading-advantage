<script>
function authNetTestModeChangeHandler()
{
	if(jQuery("#authorizenet_use_test_gateway").is(":checked"))
	{
		jQuery("#auth-net-test-account-info").show();
		jQuery("#auth-net-login-label").html("Test API Login ID");
		jQuery("#auth-net-transaction-key-label").html("Test Transaction Key");
	}
	else
	{
		jQuery("#auth-net-test-account-info").hide();
		jQuery("#auth-net-login-label").html("API Login ID");
		jQuery("#auth-net-transaction-key-label").html("Transaction Key");
	}
}

jQuery(function() {
	authNetTestModeChangeHandler();
});

function showAuthNetTestCardNumbers()
{
	var str = "";

	str += "You can use the following test credit card numbers when testing payments.\n";
	str += "The expiration date must be set to the present date or later:\n\n";
	str += "- American Express: 370000000000002\n";
	str += "- Discover: 6011000000000012\n";
	str += "- Visa: 4007000000027\n";
	str += "- Second Visa: 4012888818888\n";
	str += "- JCB: 3088000000000017\n";
	str += "- Diners Club/Carte Blanche: 38000000000006";

	alert(str);
}
</script>

<div style="padding:10px;">
<img src='<?php echo MM_Utils::getImageUrl("authorizenet"); ?>' />

<div style="margin-top:5px; margin-bottom:10px;">
<a href='http://support.membermouse.com/customer/portal/articles/973028-configuring-authorize-net' target='_blank'>Need help configuring Authorize.net?</a>
</div>

<div style="margin-bottom:10px;">
	<input type='checkbox' value='true' <?php echo (($p->useTestGateway()==true)?"checked":""); ?> id='authorizenet_use_test_gateway' name='payment_service[authorizenet][use_test_gateway]' onclick="authNetTestModeChangeHandler()" />
	Enable Test Mode
</div>

<div id="auth-net-test-account-info" style="margin-bottom:10px; margin-left:10px; <?php echo (($p->useTestGateway()==true)?"":"display:none;"); ?>">
	<div style="margin-bottom:5px;">
		<img src="<?php echo MM_Utils::getImageUrl("world_go")?>" style="vertical-align:middle;" /> 
		<a href="https://developer.authorize.net/testaccount/" target="_blank">Sign Up for a Test Gateway Account</a>
	</div>
	<div style="margin-bottom:5px;">
		<img src="<?php echo MM_Utils::getImageUrl("world_go")?>" style="vertical-align:middle;" /> 
		<a href="https://sandbox.authorize.net/" target="_blank">Log Into Test Merchant Interface</a>
	</div>
	<div style="margin-bottom:5px;">
		<img src="<?php echo MM_Utils::getImageUrl("creditcards")?>" style="vertical-align:middle;" title="Test Card Numbers" /> 
		<a href="javascript:showAuthNetTestCardNumbers()">Test Credit Card Numbers</a>
	</div>
	<div>
		<img src="<?php echo MM_Utils::getImageUrl("wrench_orange")?>" style="vertical-align:middle;" title="Setup Test Data" /> 
		<a href="<?php echo MM_ModuleUtils::getUrl(MM_ModuleUtils::getPage(), MM_MODULE_TEST_DATA); ?>" target="_blank">Configure Test Data</a>
	</div>
</div>

<div style="margin-bottom:10px;">
	<span id="auth-net-login-label">API Login ID</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getLogin(); ?>' id='authorizenet_login' name='payment_service[authorizenet][login]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	<span id="auth-net-transaction-key-label">Transaction Key</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getTransactionKey(); ?>' id='authorizenet_transaction_key' name='payment_service[authorizenet][transaction_key]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	Notification URL
	
	<p style="margin-left:10px;">
		<?php $ipnUrl = WP_PLUGIN_URL.'/'.MM_PLUGIN_NAME."/x.php?service=authorizenet"; ?>
		 
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			 <input id="mm-authnet-ipn-url" type="text" value="<?php echo $ipnUrl; ?>" style="width:550px" onclick="jQuery('#mm-authnet-ipn-url').focus(); jQuery('#mm-authnet-ipn-url').select();" />
		</span>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" />
	Authorize.net uses silent posts to inform 3rd party systems when events happen within Authorize.net
	such as successful payments, subscription cancellations, etc. MemberMouse keeps member accounts in sync with Authorize.net by listening 
	for these notifications. In order for this to work, you must 
	<a href='http://support.membermouse.com/customer/portal/articles/973028-configuring-authorize-net' target='_blank'>register the notification URL above with Authorize.net</a>.</p>
</div>
</div>