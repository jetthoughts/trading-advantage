<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>

<div id="mm-form-container" style="width:460px;">
	<p><span class="mm-section-header">Purchase Links for '<?php echo $p->productName; ?>'</span></p>
	
	<div style="font-size:11px;">
		<p>MemberMouse offers two methods for creating purchase links. Use one of the links below 
		to allow customers to purchase the '<?php echo $p->productName; ?>' product.</p>
		
		<p>Purchase Link SmartTag <img src="<?php echo MM_Utils::getImageUrl("information") ?>" style="vertical-align: middle" title="You can use this Purchase Link SmartTag in any post or page on your site. When using this SmartTag MemberMouse will automatically generate a link customers can click on to purchase this product." /></p>

		<input id="mm-smart-tag" type="text" readonly value="<?php echo htmlentities($p->smartTag); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-smart-tag').focus(); jQuery('#mm-smart-tag').select();" />
		
		<p>Static Link <img src="<?php echo MM_Utils::getImageUrl("information") ?>" style="vertical-align: middle" title="You can use this link anywhere -- in a PPC or banner ad, email, on your site, on a 3rd party site, etc. Customers can click on this link to purchase this product" /></p>
		
		<input id="mm-static-link" type="text" readonly value="<?php echo htmlentities($p->staticLink); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-static-link').focus(); jQuery('#mm-static-link').select();" />
	</div>
</div>