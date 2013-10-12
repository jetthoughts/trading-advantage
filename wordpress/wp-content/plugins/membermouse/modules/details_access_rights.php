<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_REQUEST[MM_Session::$PARAM_USER_ID])) 
{	
	$user = new MM_User($_REQUEST[MM_Session::$PARAM_USER_ID]);
	
	if($user->isValid()) 
	{
		include_once MM_MODULES."/details.header.php";
		$membership = $user->getMembershipLevel();
?>
<div id="mm-form-container">
	<!-- MANAGE MEMBERSHIP -->
	<div style="margin-bottom:15px;"><span class="mm-section-header">Manage Membership</span></div>
	
	<!-- CHANGE MEMBERSHIP LEVEL -->
	<?php if($user->getStatus() == MM_Status::$ACTIVE || $user->getStatus() == MM_Status::$LOCKED) { ?>
	<div>
		<select id="mm-new-membership-selection">
			<?php echo MM_HtmlUtils::getMemberships($user->getMembershipId(), true); ?>
		</select>
		<a onclick="mmjs.changeMembership('<?php echo $user->getId(); ?>', '<?php echo $user->getMembershipId(); ?>')" class="mm-button small"><img src="<?php echo MM_Utils::getImageUrl('user_edit'); ?>" style="vertical-align:middle;" /> Change Membership</a>
	</div>
	<?php } ?>
	
	<!-- CHANGE MEMBERSHIP STATUS -->
	<div style="margin-top:15px;">
	<?php if($user->getStatus() == MM_Status::$ACTIVE || $user->getStatus() == MM_Status::$LOCKED) { ?>

		<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$CANCELED; ?>', false)" class="mm-button red small"><img src="<?php echo MM_Utils::getImageUrl('stop'); ?>" style="vertical-align:middle;" /> Cancel Membership</a>
		<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$PAUSED; ?>', false)" class="mm-button orange small"><img src="<?php echo MM_Utils::getImageUrl('pause'); ?>" style="vertical-align:middle;" /> Pause Membership</a>		

		<?php if($user->getStatus() == MM_Status::$ACTIVE) { ?>
		
			<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$LOCKED; ?>', false)" class="mm-button small"><img src="<?php echo MM_Utils::getImageUrl('lock'); ?>" style="vertical-align:middle;" /> Lock Account</a>
		
		<?php } else { ?>
			
			<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$ACTIVE; ?>', true)" class="mm-button small"><img src="<?php echo MM_Utils::getImageUrl('lock_open'); ?>" style="vertical-align:middle;" /> Unlock Account</a>
			
		<?php } ?>
		
	<?php } else if($user->getStatus() == MM_Status::$CANCELED || $user->getStatus() == MM_Status::$PAUSED || $user->getStatus() == MM_Status::$OVERDUE || $user->getStatus() == MM_Status::$EXPIRED || $user->getStatus() == MM_Status::$PENDING || $user->getStatus() == MM_Status::$ERROR) { ?>
	
		<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$ACTIVE; ?>', false)" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('accept'); ?>" style="vertical-align:middle;" /> Activate Membership</a>
		
		<?php if($user->getStatus() == MM_Status::$OVERDUE) { ?>
		
			<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$CANCELED; ?>', false)" class="mm-button red small"><img src="<?php echo MM_Utils::getImageUrl('stop'); ?>" style="vertical-align:middle;" /> Cancel Membership</a>
		
		<?php } ?>
		
	<?php } ?>
	</div>
	
	<!-- MANAGE BUNDLES -->
	<?php 
		$bundleOptions = MM_HtmlUtils::getBundles(null, true);
		
		if(!empty($bundleOptions))
		{
	?>
	<div style="width: 700px; margin-top: 15px;" class="mm-divider"></div>
	
	<p><span class="mm-section-header">Manage Bundles</span></p>
			
	<div style="margin-bottom:20px;">
		<select id="bundle-selector" name="bundle-seletor">
		<?php echo $bundleOptions; ?>
		</select>
		<a onclick="mmjs.applyBundle('<?php echo $user->getId(); ?>', '<?php echo MM_Status::$ACTIVE; ?>')" class="mm-button small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Apply Bundle</a>
	</div>
	
	<div id="mm-grid-container" style="width:700px;">
		<?php include_once MM_MODULES."/details_access_rights.appliedbundles.php"; ?>
	</div>
	<?php } ?>
</div>
<?php 
	if(isset($_GET["message"]))
	{
?>
<script>
alert("<?php echo $_GET["message"]; ?>");
</script>
<?php
	}
}
else 
{
	echo "<div style=\"margin-top:10px;\"><em>Invalid Member ID</em></div>";
}
}
?>
<div style="clear:both; height: 10px;" ></div>