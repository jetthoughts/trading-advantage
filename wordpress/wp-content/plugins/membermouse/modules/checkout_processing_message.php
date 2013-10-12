<?php 
if(isset($_POST["mm_checkout_paid_message"]))
{
	$checkoutPaidMessage = $_POST["mm_checkout_paid_message"];
	$checkoutFreeMessage = $_POST["mm_checkout_free_message"];
	$checkoutMessageCSS = $_POST["mm_checkout_message_css"];
	
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_PAID_MESSAGE, trim($checkoutPaidMessage));
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_FREE_MESSAGE, trim($checkoutFreeMessage));
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_MESSAGE_CSS, trim($checkoutMessageCSS));
}

$checkoutPaidMessage = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_PAID_MESSAGE);
$checkoutFreeMessage = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_FREE_MESSAGE);
$checkoutMessageCSS = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_MESSAGE_CSS);
?>
<script>
function updatePreviewButton()
{
	jQuery("#mm-save-changes").show();
}
</script>
<div class="mm-wrap">
    <p class="mm-header-text">Checkout Processing Message <span style="font-size:12px;"><a href="http://support.membermouse.com/customer/portal/articles/1279708-change-the-checkout-page-processing-message" target="_blank">Learn more</a></span></p>
	<table style="margin-bottom:10px;">
		<tr>
			<td width='100' style='vertical-align:top;'>Paid Message <img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" title="This is the message that will be displayed to customers when a payment is being processed." /></td>
			<td>
				<span style="font-family: courier; font-size: 11px;">
				<textarea id='mm_checkout_paid_message' name='mm_checkout_paid_message' style="width: 380px; height: 50px;" onkeydown="updatePreviewButton()"><?php echo $checkoutPaidMessage; ?></textarea>
				</span>
			</td>
		</tr>
		<tr>
			<td width='100' style='vertical-align:top;'>Free Message <img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" title="This is the message that will be displayed to customers when they're signing up for a free account." /></td>
			<td>
				<span style="font-family: courier; font-size: 11px;">
				<textarea id='mm_checkout_free_message' name='mm_checkout_free_message' style="width: 380px; height: 50px;" onkeydown="updatePreviewButton()"><?php echo $checkoutFreeMessage; ?></textarea>
				</span>
			</td>
		</tr>
		<tr>
			<td width='100'>CSS Class</td>
			<td>
				<span style="font-family: courier; font-size: 11px;">
				<input type='text' id='mm_checkout_message_css' name='mm_checkout_message_css' value='<?php echo $checkoutMessageCSS; ?>' size="45" onkeydown="updatePreviewButton()" />
				</span>
			</td>
		</tr>
	</table>
	
	<div>
		<a id="mm-preview-button" target="_blank" href="<?php echo MM_CorePageEngine::getUrl(MM_CorePageType::$CHECKOUT, '')."?mm-checkout-preview=true" ?>" class="mm-button small green">Preview</a> 
		<span id="mm-save-changes" style="display:none; font-size:11px;"><em>*save settings before previewing changes</em></span>
	</div>
</div>