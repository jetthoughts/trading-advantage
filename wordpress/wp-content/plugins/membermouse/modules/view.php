<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

MM_MemberMouseService::validateLicense(new MM_License());

$crntPage = MM_ModuleUtils::getPage();
$primaryTab = MM_ModuleUtils::getPrimaryTab();
$module = MM_ModuleUtils::getModule();

if(isset($_REQUEST[MM_Session::$PARAM_USER_ID]))
{
	$user = new MM_User($_REQUEST[MM_Session::$PARAM_USER_ID]);
}
else
{
	$user = new MM_User();
}

$resourceUrl = MM_RESOURCES_URL;

if(MM_Utils::isSSL())
{
	$resourceUrl = preg_replace("/(http\:)/", "https:", MM_RESOURCES_URL);
}
?>

<link rel="stylesheet" href="<?php echo $resourceUrl; ?>css/menu/core.css" type="text/css" media="screen">
<link rel="stylesheet" href="<?php echo $resourceUrl; ?>css/menu/sgray.css" type="text/css" media="screen">
<!--[if (gt IE 9)|!(IE)]><!-->
	<link rel="stylesheet" href="<?php echo $resourceUrl; ?>css/menu/fade.css" type="text/css" media="screen">
<!--<![endif]-->

<!-- This piece of code, makes the CSS3 effects available for IE -->
<!--[if lte IE 9]>
	<script src="<?php echo $resourceUrl; ?>js/menu.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		jQuery(function() {
			jQuery("#mm-menu").menu({'effect' : 'fade'});
		});
	</script>
<![endif]-->

<div style="margin-top:20px; <?php echo ($primaryTab == MM_MODULE_MEMBER_DETAILS ? "":"margin-bottom:20px;"); ?> width:99%;">	
	<?php if ($primaryTab == MM_MODULE_MEMBER_DETAILS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_BROWSE_MEMBERS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("previous") ?>" /> 
			</a>
		</li>
		
		<?php if($user->isValid()) { ?>
		<li class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_GENERAL ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_GENERAL); ?>&user_id=<?php echo $user->getId(); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("vcard") ?>" />
				General
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_ACCESS_RIGHTS ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_ACCESS_RIGHTS); ?>&user_id=<?php echo $user->getId(); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("key") ?>" />
				Manage Access Rights
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_TRANSACTION_HISTORY ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_TRANSACTION_HISTORY); ?>&user_id=<?php echo $user->getId(); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("page_white_text") ?>" />
				Transaction History
			</a>
		</li>
		<?php if(MM_CustomField::hasCustomFields()) { ?>
		<li class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_CUSTOM_FIELDS ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_CUSTOM_FIELDS); ?>&user_id=<?php echo $user->getId(); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("textfield_rename") ?>" />
				Custom Fields
			</a>
		</li>
		<?php } ?>
		<li class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_BILLING_INFO ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_BILLING_INFO); ?>&user_id=<?php echo $user->getId(); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("creditcards") ?>" />
				Billing Address
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_SHIPPING_INFO ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_SHIPPING_INFO); ?>&user_id=<?php echo $user->getId(); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("lorry") ?>" />
				Shipping Address
			</a>
		</li>
		<?php } ?>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>
	
	<?php if($crntPage == MM_MODULE_PRODUCT_SETTINGS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_PRODUCTS ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_PRODUCTS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("cart") ?>" /> 
				Products
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_MEMBERSHIP_LEVELS ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBERSHIP_LEVELS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("user") ?>" />
				Membership Levels
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_BUNDLES ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_BUNDLES); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("package") ?>" />
				Bundles
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_COUPONS ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_COUPONS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("coupon") ?>" />
				Coupons
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_DRIP_CONTENT_SCHEDULE ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_DRIP_CONTENT_SCHEDULE); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("calendar") ?>" />
				Drip Content Schedule
			</a>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>
	
	
	<?php if($crntPage == MM_MODULE_CHECKOUT_SETTINGS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_CUSTOM_FIELDS ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_CUSTOM_FIELDS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("textfield_rename") ?>" /> 
				Custom Fields
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_COUNTRIES ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_COUNTRIES); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("us") ?>" />
				Countries
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_SHIPPING ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_SHIPPING); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("lorry") ?>" />
				Shipping Methods
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_CHECKOUT_OTHER_SETTINGS ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_CHECKOUT_OTHER_SETTINGS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("cog") ?>" />
				Other Settings
			</a>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>
	
	
	<?php if($crntPage == MM_MODULE_PAYMENT_SETTINGS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_PAYMENT_METHODS ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_PAYMENT_METHODS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("creditcards") ?>" /> 
				Payment Methods
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_TEST_DATA ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_TEST_DATA); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("wrench_orange") ?>" /> 
				Test Data
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_CANCELLATION_METHOD ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_CANCELLATION_METHOD); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("stop") ?>" />
				Cancellation Method
			</a>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>
	
	
	<?php if($crntPage == MM_MODULE_EMAIL_SETTINGS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_EMAIL_INTEGRATION ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_EMAIL_INTEGRATION); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("email") ?>" /> 
				Email Integration
			</a>
		</li>
		<li class='<?php echo ($module == MM_EMAIL_TEMPLATES ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_EMAIL_TEMPLATES); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("email_open") ?>" />
				Email Templates
			</a>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>
	
	
	<?php if($crntPage == MM_MODULE_AFFILIATE_SETTINGS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_AFFILIATE_INTEGRATION ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_AFFILIATE_INTEGRATION); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("user_suit") ?>" /> 
				Affiliate Integration
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_COMMISSION_PROFILES ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_COMMISSION_PROFILES); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("cash") ?>" />
				Commission Profiles
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_AFFILIATE_TRACKING ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_AFFILIATE_TRACKING); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("mouse") ?>" />
				Tracking Settings
			</a>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>
	
	
	<?php if($crntPage == MM_MODULE_DEVELOPER_TOOLS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_PUSH_NOTIFICATIONS ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_PUSH_NOTIFICATIONS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("transmit") ?>" />
				Push Notifications
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_API ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_API); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("key") ?>" /> 
				API Credentials
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_WORDPRESS_HOOKS ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_WORDPRESS_HOOKS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("wpmini-blue") ?>" />
				WordPress Hooks/Filters
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_PHP_INTERFACE ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_PHP_INTERFACE); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("page_white_php") ?>" />
				PHP Interface
			</a>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>
	
	
	<?php if($crntPage == MM_MODULE_REPORTS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_EVENT_LOG ? "selected":""); ?>'> 
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_EVENT_LOG); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("page_white_text") ?>" />
				Event Log
			</a>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>	
	
	
	<?php if($crntPage == MM_MODULE_WEBFORMS) { ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_FREE_MEMBER_FORM ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_FREE_MEMBER_FORM); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("application_form") ?>" />
				Free Member Webform
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_LOGIN_FORM ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_LOGIN_FORM); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("application_form") ?>" />
				Login Webform
			</a>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>	

	
	<?php  if($crntPage == MM_MODULE_GENERAL_SETTINGS) {  ?>
	<ul class="menu sgray fade" id="mm-menu">
		<li class='<?php echo ($module == MM_MODULE_EMPLOYEES ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_EMPLOYEES); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("group") ?>" />
				Employees
			</a>
		</li>
		<li class='<?php echo ($module == MM_MODULE_OTHER_SETTINGS ? "selected":""); ?>'>
			<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_OTHER_SETTINGS); ?>">
				<img src="<?php echo MM_Utils::getImageUrl("cog") ?>" />
				Other Settings
			</a>
		</li>
		<li>
			<a href="">Manage Install</a>
			<ul>
				<li><a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_VERSION_HISTORY); ?>">Version History</a></li>
				<li><a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_REPAIR_INSTALL); ?>">Repair Install</a></li>
				<?php if (isLocalInstall()) { ?>
				<li><a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_REPAIR_MEMBERMOUSE); ?>">Repair MemberMouse (dev)</a></li>
				<?php } ?>
			</ul>
		</li>
		
		<?php echo MM_SupportUtils::supportMenuItem($module); ?>
	</ul>
	<?php } ?>	
</div>

<div style="clear: both"></div>

<div id="mm-view-container">
	<?php echo MM_TEMPLATE::generate(MM_MODULES."/".$module.".php"); ?>
</div>
	
<?php 
	if(file_exists(MM_MODULES."/".$module.".firstrun.php")) 
	{
		echo MM_TEMPLATE::generate(MM_MODULES."/".$module.".firstrun.php"); 
	}
?>