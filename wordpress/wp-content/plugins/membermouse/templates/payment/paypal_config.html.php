<script>
function paypalTestModeChangeHandler()
{
	if(jQuery("#paypal_use_sandbox").is(":checked"))
	{
		jQuery("#paypal-test-account-info").show();
		jQuery("#paypal-email-label").html("PayPal Sandbox Email");
	}
	else
	{
		jQuery("#paypal-test-account-info").hide();
		jQuery("#paypal-email-label").html("PayPal Email");
	}
}

jQuery(function() {
	paypalTestModeChangeHandler();
	
});
</script>

<div style="padding:10px;">
<img src='<?php echo MM_Utils::getImageUrl("paypal-logo"); ?>' />

<div style="margin-top:5px; margin-bottom:10px;">
<a href='http://support.membermouse.com/customer/portal/articles/973029-configuring-paypal' target='_blank'>Need help configuring PayPal?</a>
</div>

<div style="margin-bottom:10px;">
	<input type='checkbox' value='true' <?php echo (($p->useSandbox()==true)?"checked":""); ?> id='paypal_use_sandbox' name='payment_service[paypal][use_sandbox]' onclick="paypalTestModeChangeHandler()" />
	Enable Test Mode
</div>

<div id="paypal-test-account-info" style="margin-bottom:10px; margin-left:10px; <?php echo (($p->useSandbox()==true)?"":"display:none;"); ?>">
	<div style="margin-bottom:5px;">
		<img src="<?php echo MM_Utils::getImageUrl("world_go")?>" style="vertical-align:middle;" /> 
		<a href="https://developer.paypal.com/" target="_blank">Create Test Buyer/Seller Accounts</a>
	</div>
	<div style="margin-bottom:5px;">
		<img src="<?php echo MM_Utils::getImageUrl("world_go")?>" style="vertical-align:middle;" /> 
		<a href="https://www.sandbox.paypal.com/home" target="_blank">Log Into Test Buyer/Seller Accounts (PayPal Sandbox)</a>
	</div>
	<div>
		<img src="<?php echo MM_Utils::getImageUrl("wrench_orange")?>" style="vertical-align:middle;" title="Setup Test Data" /> 
		<a href="<?php echo MM_ModuleUtils::getUrl(MM_ModuleUtils::getPage(), MM_MODULE_TEST_DATA); ?>" target="_blank">Configure Test Data</a>
	</div>
</div>

<div style="margin-bottom:10px;">
	<span id="paypal-email-label">PayPal Email</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getEmail(); ?>' id='paypal_email' name='payment_service[paypal][email]' style='width: 275px;' />
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" />
	In order to take advantage of the full intergration with PayPal you'll want to use a <a href="https://www.paypal.com/webapps/mpp/product-selection" target="_blank">Business PayPal</a> account. 
	Most features will work with a Personal PayPal account, but more advanced functionality like issuing refunds and automatically 
	cancelling subscriptions isn't supported.</p>
</div>

<div style="margin-bottom:10px;">
	Notification URL
	
	<p style="margin-left:10px;">
		<?php $ipnUrl = WP_PLUGIN_URL.'/'.MM_PLUGIN_NAME."/x.php?service=paypal"; ?>
		 
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			 <input id="mm-ipn-url" type="text" value="<?php echo $ipnUrl; ?>" style="width:550px" onclick="jQuery('#mm-ipn-url').focus(); jQuery('#mm-ipn-url').select();" />
		</span>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" />
	PayPal uses <abbr title="Instant Payment Notifications">IPNs</abbr> to inform 3rd party systems when events happen within PayPal
	such as successful payments, subscription cancellations, etc. MemberMouse keeps member accounts in sync with PayPal by listening 
	for these notifications. In order for this to work, you must register the notification URL above with PayPal.</p>
</div>

<div style="margin-bottom:10px;">
	Auto-Return Configuration
	
	<p style="margin-left:10px;">
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			 <input id="mm-site-url" type="text" value="<?php echo site_url(); ?>" style="width:550px" onclick="jQuery('#mm-site-url').focus(); jQuery('#mm-site-url').select();" />
		</span>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" />
	In PayPal you have the option of having it automatically redirect to a MemberMouse confirmation page following a successful payment. To 
	activate this behavior you need to configure auto-return in PayPal with the site URL above.</p>
</div>

<div style="margin-bottom:10px;">
	<span id="paypal-email-label">PDT Identity Token</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getPDTIdentityToken(); ?>' id='paypal_pdt_identity_token' name='payment_service[paypal][pdt_identity_token]' style='width: 550px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	<span id="paypal-express-checkout-creds-label">API Credentials</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<label for="paypal_api_username" style="display:block; float:left; width:10em;">API Username</label>    <input type='text' value='<?php echo $p->getAPIUsername(); ?>' id='paypal_api_username' name='payment_service[paypal][api_username]' style='width: 380px;' /><br/>
		<label for="paypal_api_password" style="display:block; float:left; width:10em;">API Password</label>    <input type='text' value='<?php echo $p->getAPIPassword(); ?>' id='paypal_api_password' name='payment_service[paypal][api_password]' style='width: 380px;' /><br/>
		<label for="paypal_api_signature" style="display:block; float:left; width:10em;">API Signature</label>  <input type='text' value='<?php echo $p->getAPISignature(); ?>' id='paypal_api_signature' name='payment_service[paypal][api_signature]' style='width: 380px;' /><br/>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" />
	PayPal API credentials allow you to perform certain subscription management operations from MemberMouse without
	having to log into your PayPal account.</p>
</div>
</div>

<!--
<table>
<tr>
	<td>
		API Username
	</td>
	<td>
		<input type='text' value='<?php echo $p->getAPIUsername(); ?>' id='paypal_api_username' name='payment_service[paypal][api_username]' style='width: 275px;' />
	</td>
</tr>
<tr>
	<td>
		API Password
	</td>
	<td>
		<input type='text' value='<?php echo $p->getAPIPassword(); ?>' id='paypal_api_password' name='payment_service[paypal][api_password]' style='width: 275px;' />
	</td>
</tr>
<tr>
	<td>
		API Signature
	</td>
	<td>
		<input type='text' value='<?php echo $p->getAPISignature(); ?>' id='paypal_api_signature' name='payment_service[paypal][api_signature]' style='width: 275px;' />
	</td>
</tr>
</table>
-->