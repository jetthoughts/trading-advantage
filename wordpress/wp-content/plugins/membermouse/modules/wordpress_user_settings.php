<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_hide_admin_bar"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_HIDE_ADMIN_BAR, $_POST["mm_hide_admin_bar"]);
}

$hideAdminBar = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_HIDE_ADMIN_BAR);
$hideAdminBarDesc = "When this is checked MemberMouse will configure new mebers so that the WordPress admin bar is not displayed on the front page. When this is unchecked, whether or not the admin bar will be shown for new members will be based on settings in WordPress or another plugin.";
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 

<script>
function updateAdminBarForm()
{	
	if(jQuery("#mm_hide_admin_bar_cb").is(":checked")) 
	{
		jQuery("#mm_hide_admin_bar").val("1");
	} 
	else 
	{
		jQuery("#mm_hide_admin_bar").val("0");
	}
}
</script>

<div class="mm-wrap">
    <p class="mm-header-text">WordPress User Options <span style="font-size:12px;"><a href="http://support.membermouse.com/customer/portal/articles/1258078-hide-the-wordpress-admin-bar-from-new-members" target="_blank">Learn more</a></span></p>
    <div style="clear:both; height: 10px;"></div>
	<div style="margin-top:10px;">
		<input id="mm_hide_admin_bar_cb" type="checkbox" <?php echo (($hideAdminBar=="1")?"checked":""); ?> onchange="updateAdminBarForm();" />
		Hide the admin bar for new members
		<img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" title="<?php echo $hideAdminBarDesc; ?>" />
		<input id="mm_hide_admin_bar" name="mm_hide_admin_bar" type="hidden" value="<?php echo $hideAdminBar; ?>" />
	</div>
</div>