<?php

if(isset($_REQUEST[MM_Session::$PARAM_USER_ID])) 
{	
$user = new MM_User($_REQUEST[MM_Session::$PARAM_USER_ID]);

if($user->isValid()) 
{
	include_once MM_MODULES."/details.header.php";

	$message = "";

	// perform update?
	if(isset($_POST["mm-shipping-form"]))
	{
		$user->setShippingAddress($_POST["mm-shipping-address"]);
		$user->setShippingCity($_POST["mm-shipping-city"]);
		$user->setShippingState($_POST["mm-shipping-state"]);
		$user->setShippingZipCode($_POST["mm-shipping-zip-code"]);
		$user->setShippingCountry($_POST["mm-shipping-country"]);
		
		$response = $user->commitData();
		
		if(MM_Response::isError($response))
		{
			$message = "Error updating shipping address. ".$response->message;
		}
		else
		{
			$message = "Shipping address updated successfully";
		}
	}
?>
<div id="mm-form-container">
<form method='post'>
<table cellspacing="8">
	<tr>
		<td width='75'>Address</td>
		<td><input name="mm-shipping-address" type="text" class="medium-text"  value="<?php echo $user->getShippingAddress(); ?>"/></td>
	</tr>
	<tr>
		<td>City</td>
		<td><input name="mm-shipping-city" type="text" class="medium-text"  value="<?php echo $user->getShippingCity(); ?>"/></td>
	</tr>
	<tr>
		<td>State</td>
		<td><input name="mm-shipping-state" type="text" class="medium-text"  value="<?php echo $user->getShippingState(); ?>"/></td>
	</tr>
	<tr>
		<td>Zip Code</td>
		<td><input name="mm-shipping-zip-code" type="text" class="medium-text"  value="<?php echo $user->getShippingZipCode(); ?>"/></td>
	</tr>
	<tr>
		<td>Country</td>
		<td><select name="mm-shipping-country"><?php echo MM_HtmlUtils::getCountryList($user->getShippingCountry()); ?></select></td>
	</tr>
	<tr>
		<td colspan='2'>
			<input type='submit' name="mm-shipping-form" class="mm-button blue small" value='Update Shipping Address' />
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