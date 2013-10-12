<?php

if(isset($_REQUEST[MM_Session::$PARAM_USER_ID])) 
{	
$user = new MM_User($_REQUEST[MM_Session::$PARAM_USER_ID]);

if($user->isValid()) 
{
	include_once MM_MODULES."/details.header.php";

	$message = "";

	// perform update?
	if(isset($_POST["mm-billing-form"]))
	{
		$user->setBillingAddress($_POST["mm-billing-address"]);
		$user->setBillingCity($_POST["mm-billing-city"]);
		$user->setBillingState($_POST["mm-billing-state"]);
		$user->setBillingZipCode($_POST["mm-billing-zip-code"]);
		$user->setBillingCountry($_POST["mm-billing-country"]);
		
		$response = $user->commitData();
		
		if(MM_Response::isError($response))
		{
			$message = "Error updating billing address. ".$response->message;
		}
		else
		{
			$message = "Billing address updated successfully";
		}
	}
?>
<div id="mm-form-container">
<form method='post'>
<table cellspacing="8">
	<tr>
		<td width='75'>Address</td>
		<td><input name="mm-billing-address" type="text" class="medium-text"  value="<?php echo $user->getBillingAddress(); ?>"/></td>
	</tr>
	<tr>
		<td>City</td>
		<td><input name="mm-billing-city" type="text" class="medium-text"  value="<?php echo $user->getBillingCity(); ?>"/></td>
	</tr>
	<tr>
		<td>State</td>
		<td><input name="mm-billing-state" type="text" class="medium-text"  value="<?php echo $user->getBillingState(); ?>"/></td>
	</tr>
	<tr>
		<td>Zip Code</td>
		<td><input name="mm-billing-zip-code" type="text" class="medium-text"  value="<?php echo $user->getBillingZipCode(); ?>"/></td>
	</tr>
	<tr>
		<td>Country</td>
		<td><select name="mm-billing-country"><?php echo MM_HtmlUtils::getCountryList($user->getBillingCountry()); ?></select></td>
	</tr>
	<tr>
		<td colspan='2'>
			<input type='submit' name="mm-billing-form" class="mm-button blue small" value='Update Billing Address' />
		</td>
	</tr>
</table>
</form>
</div>
<?php if(!empty($message)) { ?>
<script>
alert("<?php echo $message; ?>");
</script>
<?php } ?>
<?php 
}
else 
{
	echo "<div style=\"margin-top:10px;\"><em>Invalid Member ID</em></div>";
}
}
?>