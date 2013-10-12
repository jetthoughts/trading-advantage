<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$membership = new MM_MembershipLevel($p->accessTypeId);

function generatePurchaseSection($productId)
{
?>
	<div id="mm-purchaselinks-<? echo $productId; ?>" style="display:none;">
	<p>Purchase Link SmartTag <img src="<?php echo MM_Utils::getImageUrl("information") ?>" style="vertical-align: middle" title="You can use this Purchase Link SmartTag in any post or page on your site. When using this SmartTag MemberMouse will automatically generate a link customers can click on to purchase this membership level." /></p>
	
	<?php $smartTag = "<a href=\"[MM_Purchase_Link productId='{$productId}']\">Buy Now</a>" ?>
	<input id="mm-smart-tag-<? echo $productId; ?>" type="text" readonly value="<?php echo htmlentities($smartTag); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-smart-tag-<? echo $productId; ?>').focus(); jQuery('#mm-smart-tag-<? echo $productId; ?>').select();" />
	
	<p>Static Link <img src="<?php echo MM_Utils::getImageUrl("information") ?>" style="vertical-align: middle" title="You can use this link anywhere -- in a PPC or banner ad, email, on your site, on a 3rd party site, etc. Customers can click on this link to purchase this membership level." /></p>
	
	<input id="mm-static-link-<? echo $productId; ?>" type="text" readonly value="<?php echo htmlentities(MM_CorePageEngine::getCheckoutPageStaticLink($productId)); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-static-link-<? echo $productId; ?>').focus(); jQuery('#mm-static-link-<? echo $productId; ?>').select();" />
	</div>
<?php 
}
	
?>

<div id="mm-form-container" style="width:460px;">
	<div style="font-size:11px;">
	<p><span class="mm-section-header">Purchase Links for '<?php echo $p->accessTypeName; ?>'</span></p>
	
	<?php 
		if(!$membership->isFree()) { 
	?>
		<p>MemberMouse offers two methods for creating purchase links. First, select the product you want to create
		a purchase link for and then use one of the links below to allow customers to purchase access to the 
		'<?php echo $p->accessTypeName; ?>' membership.</p>
		
		<input id="mm-last-selected-product-id" type="hidden" value="0" /> 
		<select id="mm-product-selector" onchange="mmjs.productChangeHandler();">
		<option value='0'>Select a product</option>
		<?php
			foreach($p->productIds as $id) 
			{
				$product = new MM_Product($id);
				
				if($product->isValid())
				{
					echo "<option value='{$product->getId()}'>{$product->getName()}</option>";
				}
			}
		?>
		</select>
		
	<?php
			foreach($p->productIds as $id) 
			{
				generatePurchaseSection($id);
			}
		} 
		else 
		{ 
	?>
		<p>MemberMouse offers two methods for creating purchase links. This is a free membership level so
		the customer doesn't need to purchase anything. Use one of the links below 
	 	to allow customers to sign up for the '<?php echo $p->accessTypeName; ?>' membership.</p>
		
		<p>Purchase Link SmartTag <img src="<?php echo MM_Utils::getImageUrl("information") ?>" style="vertical-align: middle" title="You can use this Purchase Link SmartTag in any post or page on your site. When using this SmartTag MemberMouse will automatically generate a link customers can click on to sign up for this free membership level." /></p>
	
		<?php $smartTag = "<a href=\"[MM_Purchase_Link membershipId='{$p->accessTypeId}']\">Sign Up Now</a>"; ?>
		<input id="mm-smart-tag" type="text" readonly value="<?php echo htmlentities($smartTag); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-smart-tag').focus(); jQuery('#mm-smart-tag').select();" />	
		
		<p>Static Link <img src="<?php echo MM_Utils::getImageUrl("information") ?>" style="vertical-align: middle" title="You can use this link anywhere -- in a PPC or banner ad, email, on your site, on a 3rd party site, etc. Customers can click on this link to sign up for this free membership level." /></p>
		
		<input id="mm-static-link" type="text" readonly value="<?php echo htmlentities(MM_CorePageEngine::getCheckoutPageStaticLink($p->accessTypeId, true)); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-static-link').focus(); jQuery('#mm-static-link').select();" />
	<?php } ?>
	</div>
</div>