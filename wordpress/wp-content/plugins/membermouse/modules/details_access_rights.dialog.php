<?php 
	$appliedBundle = MM_AppliedBundle::getAppliedBundle($p->memberId, $p->bundleId);
	
	if($appliedBundle->isValid())
	{
		$canChangeDaysCalc = true;
		
		if($appliedBundle->getStatus() != MM_Status::$ACTIVE && $appliedBundle->getStatus() != MM_Status::$EXPIRED)
		{
			$canChangeDaysCalc = false;
		}
		
		$customDateSelected = "";
		$fixedSelected = "";
		$joinDateSelected = "";	
		$customDateValue = "";
		$fixedValue = "";
		switch($appliedBundle->getDaysCalcMethod())
		{
			case MM_DaysCalculationTypes::$CUSTOM_DATE:
				$calcMethod = MM_DaysCalculationTypes::$CUSTOM_DATE;
				$customDateValue  = date("m/d/Y", strtotime($appliedBundle->getDaysCalcValue()));
				$customDateSelected = "checked";
				break;
				
			case MM_DaysCalculationTypes::$FIXED:
				$calcMethod = MM_DaysCalculationTypes::$FIXED;
				$fixedValue = $appliedBundle->getDaysCalcValue();
				$fixedSelected = "checked";
				break;
				
			default:
				$calcMethod = MM_DaysCalculationTypes::$JOIN_DATE;
				$joinDateSelected = "checked";
				break;
		}
		
		if($appliedBundle->doesExpire())
		{
			$expirationDate = date("m/d/Y", strtotime($appliedBundle->getExpirationDate()));
		}
?>
<script type='text/javascript'>
jQuery(document).ready(function() {
	jQuery("#mm-custom-date").datepicker();
});
</script>
<div id='mm-calc-method-div'>
<input type='hidden' id='member_id' value='<?php echo $p->memberId ?>' />
<input type='hidden' id='bundle_id' value='<?php echo $p->bundleId; ?>' />
<?php
	$calcMethodDesc = "This determines how MemberMouse will calculate the number of days a member has had a bundle. This is used primarily in determining where a member is in a drip content schedule and therefore what content they get access to. By default, the calculation is done based on the date the bundle was first applied to the member's account, but you can choose to have the calculation done based on a custom date or fix the number of days to a specific number.";
?>
<p>'Days with Bundle' Calculation Method <img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" title="<?php echo $calcMethodDesc; ?>" /></p>
<?php if(!$canChangeDaysCalc) { ?>
	<div style="margin-bottom:5px;">
		<img src='<?php echo MM_Utils::getImageUrl("exclamation"); ?>' style='vertical-align: middle; '/> You can modify the number of days 
		this bundle is fixed at, but to change the calculation method you must change the bundle's status to Active.
	</div>
<?php } ?>
<div style="margin-bottom:5px;"></div>
<div style="margin-bottom:5px;">
	<input type='radio' <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> onchange="mmjs.changeCalcMethodHandler('<?php echo MM_DaysCalculationTypes::$JOIN_DATE; ?>');" id='mm-calc-method-reg-date' <?php echo $joinDateSelected; ?> name='mm-calc-method' /> By join date
</div>
<div style="margin-bottom:5px;">
	<input type='radio' <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> onchange="mmjs.changeCalcMethodHandler('<?php echo MM_DaysCalculationTypes::$CUSTOM_DATE; ?>');" id='mm-calc-method-custom-date'  <?php echo $customDateSelected; ?> name='mm-calc-method' /> By custom date 

	<img src="<?php echo MM_Utils::getImageUrl("calendar") ?>" style="vertical-align: middle" />
	<input <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> id="mm-custom-date" type="text" style="width: 152px" value="<?php echo $customDateValue; ?>" /> 
</div>
<div style="margin-bottom:5px;">
	<input type='radio' onchange="mmjs.changeCalcMethodHandler('<?php echo MM_DaysCalculationTypes::$FIXED; ?>');" id='mm-calc-method-fixed'  <?php echo $fixedSelected; ?>  name='mm-calc-method' /> Fixed at <input id="mm-fixed" type="text" value="<?php echo $fixedValue; ?>"  style="width: 52px" /> days <br />
</div>
 	
<input type='hidden' id='mm-calc-method' value="<?php echo $calcMethod; ?>" />

<?php if($appliedBundle->doesExpire()) { ?>
<div style="width: 360px; margin-top: 8px; margin-bottom: 8px;" class="mm-divider"></div>
<p>Bundle Expires</p>
<p>
	<img src="<?php echo MM_Utils::getImageUrl("calendar") ?>" style="vertical-align: middle" />
	<input id="mm-expiration-date" type="text" style="width: 152px" value="<?php echo $expirationDate; ?>" /> 
</p>
<?php } ?>

</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.saveBundleConfiguration(<?php echo $p->bundleId; ?>);" class="mm-button blue">Save</a>
<a href="javascript:mmjs.closeDialog();" class="mm-button">Cancel</a>
</div>
</div>
<script type='text/javascript'>
jQuery(document).ready(function()
{	
	jQuery("#mm-expiration-date").datepicker();	
});
</script>
<?php } else { ?>
	Invalid Member ID or bundle ID
<?php } ?>