<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
	$crntPage = MM_ModuleUtils::getPage();
	$module = MM_ModuleUtils::getModule();
	
	if($user->getFullName() != "") 
	{
		$displayName = $user->getFullName();
	}
	else 
	{
		$displayName = $user->getEmail();
	}
?> 
<div style="width:98%; margin-top:10px; margin-bottom:20px; padding-bottom: 10px; padding-left: 10px; 
	border-radius: 3px 3px 0px 0px;
	box-shadow: 0 4px 2px -2px #DDDDDD; 
	background:#EAF2FA;">
	<div class="mm-wrap" style="padding-bottom:10px;">
   		<h2>
   			<span style="position:relative; top:-2px;">
   				<?php echo MM_Status::getImage($user->getStatus()); ?>
   				<?php if($user->isComplimentary()) { ?>
   				<img src="<?php echo MM_Utils::getImageUrl("award_star_gold") ?>" title="Membership is complimentary" style="vertical-align:middle;" />
   				<?php } ?>
   			</span> 
   			Member Details for <?php echo $displayName; ?></h2>
	</div>
	
	<div style="font-size:16px; text-shadow: 0 1px #F8F8F8;">
		<?php if($user->isImported()) { ?>
			<img src="<?php echo MM_Utils::getImageUrl('user_go'); ?>" title="Membership Level (Member Imported)" style="vertical-align: middle" /> 
		<?php } else { ?>
			<img src="<?php echo MM_Utils::getImageUrl('user'); ?>" title="Membership Level" style="vertical-align: middle" /> 
		<?php } ?>
		<?php echo $user->getMembershipName(); ?>
		
		<?php 
			$appliedBundles = $user->getAppliedBundleNames();
			
			if(!empty($appliedBundles)) { 
		?>
		<span style="margin-left: 5px;">
		<img src="<?php echo MM_Utils::getImageUrl('package'); ?>" title="Bundles" style="vertical-align: middle" /> <?php echo $appliedBundles; ?>
		</span>
		<?php } ?>
	</div>
</div>
