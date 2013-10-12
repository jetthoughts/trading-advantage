<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_selected_currency"]))
{
	//update default currency
	if (in_array($_POST['mm_selected_currency'],array_keys(MM_CurrencyUtil::getSupportedCurrencies())))
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_CURRENCY,$_POST["mm_selected_currency"]);
	}
}

if (isset($_POST["mm_postfix_iso_to_currency"]))
{
	if ($_POST["mm_postfix_iso_to_currency"] == "1")
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_CURRENCY_FORMAT_POSTFIX_ISO,true);
	}
}

$currentCurrency      = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CURRENCY);
$postfixIsoToCurrency = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CURRENCY_FORMAT_POSTFIX_ISO);
$postfixIsoToCurrency = (empty($postfixIsoToCurrency))?false:$postfixIsoToCurrency;

//check the support of the active payment services for the currently selected currency
$activePaymentServices = MM_PaymentServiceFactory::getAvailablePaymentServices();
$unsupportedPaymentServices = array();
foreach ($activePaymentServices as $aps)
{
	if (!$aps->isSupportedCurrency($currentCurrency))
	{
		$unsupportedPaymentServices[] = $aps->getName();
	}
}

$warningMsg = "";
$warningBox = "";
$numUnsupported = count($unsupportedPaymentServices);
if ($numUnsupported > 0)
{
	$lastService = "";
	if ($numUnsupported > 1)
	{
		$lastService = array_pop($unsupportedPaymentServices);
		$lastService = " and {$lastService}";
	}
	$warningMsg = "The currently selected currency is not supported by the ".implode(", ",$unsupportedPaymentServices)."{$lastService} payment services";
}

if (!empty($warningMsg))
{
	$exclamationIcon = MM_Utils::getImageUrl("exclamation");
	$warningBox = "<span style='color:#ff0000;'><img style='vertical-align:middle;' src='{$exclamationIcon}'>{$warningMsg}</div>";
}
?>
<script>
<!-- currency confirmation script -->
</script>
<div class="wrap">
    <p class="mm-header-text">Currency <span style="font-size:12px;"><a href="http://support.membermouse.com/customer/portal/articles/1283489-currency-settings" target="_blank">Learn more</a></span></p>
    <div style="clear:both; height: 10px;"></div>
    <div style="margin-bottom:10px; width:450px;">
    	Setting the currency indicates which currency customers will pay in and also determines how all product and coupon prices are 
    	formatted across the site. If you're changing the currency and you've already defined product and coupon prices, you'll need to go 
    	through each product or coupon and update the prices to the new currency. MemberMouse does NOT perform automatic currency conversion.
    </div>
	
	<div style="margin-top:10px;">
		<select id="mm_currency_selector" name="mm_selected_currency">
		<?php echo MM_HtmlUtils::getSupportedCurrenciesList($currentCurrency); ?>
		</select>
		<?php echo $warningBox; ?>
	</div>
	
	<div style='margin:10px 0px 5px;'>
		<label>
			<input id="mm_postfix_iso_to_currency" name="mm_postfix_iso_to_currency" value='1' type="checkbox" <?php echo ($postfixIsoToCurrency == "1") ? "checked":""; ?> />
			Append the currency code after the amount (ie. $100.00 becomes $100.00 USD)
		</label>
	</div>
</div>