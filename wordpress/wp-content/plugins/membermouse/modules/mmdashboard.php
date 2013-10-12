<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
global $wpdb;

$startDate = date("Y-m-d");

/* 
 * start calculations
 */

$activeStatus = MM_Status::$ACTIVE;
$canceledStatus = MM_Status::$CANCELED;

$statistics = MM_MemberMouseService::generateStatistics();

$newMembersPaid = 0;
$newMembersFree = 0;

$sql = "SELECT count(1) as members, memberships.is_free FROM ".MM_TABLE_USER_DATA." u, ".MM_TABLE_MEMBERSHIP_LEVELS." memberships ";
$sql .= " WHERE u.membership_level_id=memberships.id AND u.became_active >= '{$startDate}' AND u.status='{$activeStatus}' ";
$sql .= " GROUP BY memberships.is_free";
$memberResults = $wpdb->get_results($sql);

if (($memberResults !=null) && is_array($memberResults) && (count($memberResults)>0))
{
	foreach($memberResults as $k=>$memberCount)
	{
		if ($memberCount->is_free == 1)
		{
			$newMembersFree = $memberCount->members;
		}
		else 
		{
			$newMembersPaid = $memberCount->members;
		}
	}
}

$newMbrCancelPaid = 0;
$newMbrCancelFree = 0;

$sql = "SELECT count(1) as members, memberships.is_free FROM ".MM_TABLE_USER_DATA." u, ".MM_TABLE_MEMBERSHIP_LEVELS." memberships ";
$sql .= " WHERE ((u.membership_level_id=memberships.id) and ((u.status='{$canceledStatus}') OR (u.status='".MM_Status::$PAUSED."'))) ";
$sql .= " GROUP BY memberships.is_free";
$cancelResults = $wpdb->get_results($sql);

if (($cancelResults !=null) && is_array($cancelResults) && (count($cancelResults)>0))
{
	foreach($cancelResults as $k=>$memberCount)
	{
		if ($memberCount->is_free == 1)
		{
			$newMbrCancelFree = $memberCount->members;
		}
		else 
		{
			$newMbrCancelPaid = $memberCount->members;
		}
	}
}

$newBundlesPaid = 0;
$newBundlesFree = 0;

$baseSql = "SELECT count(1) as total_bundles FROM ".MM_TABLE_APPLIED_BUNDLES." appBundles, {$wpdb->users} users, ";
$baseSql .= MM_TABLE_BUNDLES." bundles WHERE appBundles.access_type='".MM_AppliedBundle::$ACCESS_TYPE_USER."' AND appBundles.access_type_id = users.id ";
$baseSql .= "AND appBundles.status='".MM_Status::$ACTIVE."' AND appBundles.bundle_id = bundles.id AND appBundles.apply_date >= '{$startDate}' ";

// PAID BUNDLES
$result = $wpdb->get_row($baseSql." AND bundles.is_free = '0';");

if($result)
{
	$newBundlesPaid = $result->total_bundles;
}
else
{
	$newBundlesPaid = 0;
}

// FREE BUNDLES
$result = $wpdb->get_row($baseSql." AND bundles.is_free = '1';");

if($result)
{
	$newBundlesFree = $result->total_bundles;
}
else
{
	$newBundlesFree = 0;
}                                                

$newBundleCancelPaid = 0;
$newBundleCancelFree = 0;

$baseSql = "SELECT count(1) as total_bundles FROM ".MM_TABLE_APPLIED_BUNDLES." appBundles, {$wpdb->users} users, ";
$baseSql .= MM_TABLE_BUNDLES." bundles WHERE appBundles.access_type='".MM_AppliedBundle::$ACCESS_TYPE_USER."' AND appBundles.access_type_id = users.id ";
$baseSql .= "AND (appBundles.status='".MM_Status::$CANCELED."' OR appBundles.status='".MM_Status::$PAUSED."') ";
$baseSql .= "AND appBundles.bundle_id = bundles.id ";

// PAID BUNDLES
$result = $wpdb->get_row($baseSql." AND bundles.is_free = '0';");

if($result)
{
	$newBundleCancelPaid = $result->total_bundles;
}
else
{
	$newBundleCancelPaid = 0;
}

// FREE BUNDLES
$result = $wpdb->get_row($baseSql." AND bundles.is_free = '1';");

if($result)
{
	$newBundleCancelFree = $result->total_bundles;
}
else
{
	$newBundleCancelFree = 0;
}                                                                                                                                                                                                                                               

/*
 * end calculations
 */

//reformat start date for display
$startDate = date("M j, Y", strtotime($startDate));
?>
<div id="MMwrapper">
	<div id="branding">       
        <div class="logo"><a href="http://membermouse.com/"><img src="<?php echo MM_Utils::getImageUrl("dashboard/membermouse-logo") ?>" alt="MemberMouse" style="width:340px;" /></a></div>        
    </div>
    
    <div id="MainInfo">
   		<div id="dashboard">
   			<div class="left-column">
   				<div class="dashboard-module-header">
   					<img src="<?php echo MM_Utils::getImageUrl("user") ?>" style="vertical-align: middle;" /> 
   					MEMBERSHIP SNAPSHOT
   				</div>
   				<div class="dashboard-module-row">
   					Free Members Today
   					<span><?php echo number_format($newMembersFree); ?></span>
   				</div>
   				<div class="dashboard-module-row alt">
   					Total Free Members
   					<span><?php echo number_format($statistics[MM_MemberMouseService::$USAGE_FREE_MEMBERS]); ?></span>
   				</div>
   				<div class="dashboard-module-row space">
   				</div>
   					<div class="dashboard-module-row">
   					Paid Members Today
   					<span><?php echo number_format($newMembersPaid); ?></span>
   				</div>
   				<div class="dashboard-module-row alt bottom">
   					Total Paid Members
   					<span><?php echo number_format($statistics[MM_MemberMouseService::$USAGE_PAID_MEMBERS]); ?></span>
   				</div>
   				
   				<div class="dashboard-module-header" style="margin-top:20px;">
   					<img src="<?php echo MM_Utils::getImageUrl("cart") ?>" style="vertical-align: middle;" /> 
   					PRODUCT SNAPSHOT
   				</div>
   				<div class="dashboard-module-row">
	   				Products
	   				<span><?php echo number_format($statistics[MM_MemberMouseService::$CONFIG_PRODUCTS]); ?></span>
   				</div>
   				<div class="dashboard-module-row alt">
	   				Membership Levels
	   				<span><?php echo (number_format($statistics[MM_MemberMouseService::$CONFIG_MEMBERSHIPS_FREE]) + number_format($statistics[MM_MemberMouseService::$CONFIG_MEMBERSHIPS_PAID])) ; ?></span>
   				</div>
   				<div class="dashboard-module-row">
	   				Bundles
	   				<span><?php echo (number_format($statistics[MM_MemberMouseService::$CONFIG_BUNDLES_FREE]) + number_format($statistics[MM_MemberMouseService::$CONFIG_BUNDLES_PAID])) ; ?></span>
   				</div>
   				<div class="dashboard-module-row alt bottom">
   					&nbsp;
   				</div>
   			</div>
   			
   			<div class="center-column">
   				<div class="dashboard-module-header">
   					<img src="<?php echo MM_Utils::getImageUrl("package") ?>" style="vertical-align: middle;" /> 
   					BUNDLE SNAPSHOT
   				</div>
   				<div class="dashboard-module-row">
   					Free Bundles Today
   					<span><?php echo number_format($newBundlesFree); ?></span>
   				</div>
   				<div class="dashboard-module-row alt">
   					Total Free Bundles
   					<span><?php echo number_format($statistics[MM_MemberMouseService::$USAGE_FREE_BUNDLES]); ?></span>
   				</div>
   				<div class="dashboard-module-row space">
   				</div>
   					<div class="dashboard-module-row">
   					Paid Bundles Today
   					<span><?php echo number_format($newBundlesPaid); ?></span>
   				</div>
   				<div class="dashboard-module-row alt bottom">
   					Total Paid Bundles
   					<span><?php echo number_format($statistics[MM_MemberMouseService::$USAGE_PAID_BUNDLES]); ?></span>
   				</div>
   				
   				<div class="dashboard-module-header" style="margin-top:20px;">
   					<img src="<?php echo MM_Utils::getImageUrl("stop") ?>" style="vertical-align: middle;" /> 
   					RETENTION SNAPSHOT
   				</div>
   				<div class="dashboard-module-row">
	   				Canceled Free Memberships
	   				<span><?php echo number_format($newMbrCancelFree); ?></span>
   				</div>
   				<div class="dashboard-module-row alt">
	   				Canceled Paid Memberships
	   				<span><?php echo number_format($newMbrCancelPaid); ?></span>
   				</div>
   				<div class="dashboard-module-row">
	   				Canceled Free Bundles
	   				<span><?php echo number_format($newBundleCancelFree); ?></span>
   				</div>
   				<div class="dashboard-module-row alt bottom">
	   				Canceled Paid Bundles
	   				<span><?php echo number_format($newBundleCancelPaid); ?></span>
   				</div>
   			</div>
   		</div>
   		<div class="right-column">
   			<div class="dashboard-module-header">
   				NEWS
   			</div>
   			<div class="dashboard-module-row bottom" style="height:225px; padding-left:0px; padding-right:0px; font-size:14x;">
	   			<div style="height:225px; overflow: auto;">
	   			<?php 
	   			function changeCachePeriod($secs)
	   			{
	   				return 7200;
	   			}
	   			if(function_exists('fetch_feed')) 
	   			{  
        			include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator  
        			add_filter('wp_feed_cache_transient_lifetime' , 'changeCachePeriod');
        			$feed = fetch_feed('http://membermouse.com/?cat=34&feed=rss2'); // specify the rss feed  
        			remove_filter('wp_feed_cache_transient_lifetime' , 'changeCachePeriod');
        			
        			if (!is_wp_error($feed))
        			{
        				$limit = $feed->get_item_quantity(7); // specify number of items  
        				$items = $feed->get_items(0, $limit); // create an array of items  
        			}
        			else
        			{
        				$limit = 0;
        			}
    			}  
    			
    			if ($limit == 0) 
    			{
    				echo "<p class=\"news-body\">No news at the moment.</p>";  
    			}
    			else 
    			{
    				foreach ($items as $item) 
    				{ 
    			?>  
					    <h1 class="news-header"><a href="<?php echo esc_url($item->get_permalink()); ?>" alt="<?php echo esc_html($item->get_title()); ?>"><?php echo esc_html($item->get_title()); ?></a></h1>  
					    <p class="news-date"><?php echo $item->get_date('j F Y @ g:i a'); ?></p>  
					    <p class="news-body"><?php echo substr(esc_html($item->get_description()), 0, 200); ?> ...</p>
    			<?php 
    				}
    			} 
    			?>  
	   			</div>
   			</div>
   			
   			<div class="dashboard-module-header" style="margin-top:20px;">
   				SUPPORT
   				<?php if(MM_SupportUtils::hasEmailSupport() || MM_SupportUtils::hasPhoneSupport()) { ?>
   				<span style="float:right;">
   					PIN: <?php echo MM_SupportUtils::getSupportPin(); ?>
   					<img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" title="Your support pin is required to receive phone/email support" />
   				</span>
   				<?php } ?>
   			</div>
   			<div class="dashboard-module-row alt bottom">
	   			<a href="http://support.membermouse.com" target="_blank">Support Center</a>
	   			<span style="float:right;">
	   				<?php if(MM_SupportUtils::hasEmailSupport()) { ?>
	   				<a href="http://support.membermouse.com/customer/portal/emails/new" target="_blank">Email</a>
	   				<?php } ?>
	   				<?php if(MM_SupportUtils::hasPhoneSupport()) { ?>
	   				| (512) 630-2219
	   				<?php } ?>
	   			</span>
   			</div>
        </div>
   	</div>
   	
   	<div id="AdSpace">
   		<strong>Profit Alert</strong> 
   		- Learn how million dollar membership sites operate in <a href="http://membermouse.com/7-steps-to-seven-figures" target="_blank">7 Steps to Seven Figures</a> 
   		- Earn 20% recurring commission with our <a href="http://membermouse.com/referral-program" target="_blank">Referral Program</a>
   	</div>
   	
   	<?php 
   		$minorVersion = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_MINOR_VERSION);
   		if(empty($minorVersion)) { $minorVersion = MM_MemberMouseService::$DEFAULT_MINOR_VERSION; }
   	?>
	<div class="version" style="margin-top: 10px; padding-top:5px; border-top:1px solid #CFE6EF;">MemberMouse Version <?php echo MM_MemberMouseService::getPluginVersion()."-".$minorVersion; ?>&nbsp;&nbsp;&nbsp;</div>
</div>