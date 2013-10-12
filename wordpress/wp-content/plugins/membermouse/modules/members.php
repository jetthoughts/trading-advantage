<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_MembersView();

$showSearch = false;

//only show 'export csv' option if current user is an administrator
global $current_user;

$showCsvExportButton = false;
if (isset($current_user) && isset($current_user->ID))
{
	$employee = MM_Employee::findByUserId($current_user->ID);
	if($employee->isValid() && $employee->getRoleId() == MM_Role::$ROLE_ADMINISTRATOR)
	{
		$showCsvExportButton = true;
	}
	
	echo "<input type='hidden' id='mm-admin-id' value='{$current_user->ID}' />";
	
	// determine if this user's preference is to have the advanced search open
	$showSearchOptionName = MM_OptionUtils::$OPTION_KEY_SHOW_MBRS_SEARCH."-".$current_user->ID;
	$showSearchOptionValue = MM_OptionUtils::getOption($showSearchOptionName);
	
	if($showSearchOptionValue == "1")
	{
		$showSearch = true;
	}
}
?>
<div class="mm-wrap">
    <img src="<?php echo MM_Utils::getImageUrl('lrg_directory'); ?>" class="mm-header-icon" /> 
    <p class="mm-header-text">Manage Members</p>
	
	<?php if(count(MM_MembershipLevel::getMembershipLevelsList()) > 0) { ?>
		<div style="margin-top:20px;" class="mm-button-container">			
			<a id="mm-show-search-btn" onclick="mmjs.showSearch()" class="mm-button blue small" <?php echo ($showSearch) ? "style=\"display:none;\"" : ""; ?>><img src="<?php echo MM_Utils::getImageUrl('magnifier'); ?>" style="vertical-align:middle;" /> Advanced Search</a>
			<a id="mm-hide-search-btn" onclick="mmjs.hideSearch()" class="mm-button small" <?php echo ($showSearch) ? "" : "style=\"display:none;\""; ?>><img src="<?php echo MM_Utils::getImageUrl('magnifier_zoom_out'); ?>" style="vertical-align:middle;" /> Advanced Search</a>
			
			<a onclick="mmjs.create('mm-create-member-dialog', 500, 360)" class="mm-button green small" style="margin-left:15px;"><img src="<?php echo MM_Utils::getImageUrl('user_add'); ?>" style="vertical-align:middle;" /> Create Member</a>
			
			<a href="<?php echo MM_ModuleUtils::getUrl(MM_MODULE_MANAGE_MEMBERS, MM_MODULE_IMPORT_WIZARD); ?>" class="mm-button small" style="margin-left:15px;"><img src="<?php echo MM_Utils::getImageUrl('user_go'); ?>" style="vertical-align:middle;" /> Import Members</a>
		
			<?php 
				if($showCsvExportButton) { 
			?>
			<a class="mm-button small" onclick="mmjs.csvExport(0);" style="margin-left:15px;"><img src="<?php echo MM_Utils::getImageUrl('page_white_go'); ?>" style="vertical-align: middle;" /> Export Members</a>
			<?php } ?>
		</div>
	<?php } ?>
	
	<div style="width: 100%; margin-top: 10px; margin-bottom: 0px;" class="mm-divider"></div> 
	
	<div id="mm-advanced-search" <?php echo ($showSearch) ? "" : "style=\"display:none;\""; ?>>
		<div id="mm-advanced-search-container">
		<?php echo $view->generateSearchForm($_POST); ?>
		</div>
		<div style="width: 100%; margin-top: 0px; margin-bottom: 10px;" class="mm-divider"></div> 
	</div>
	
	<div id='mm_members_csv'></div>
	<div id="mm-grid-container">
		<?php echo $view->generateDataGrid($_POST); ?>
	</div>
</div>