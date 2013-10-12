<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

require_once("../../../../../../../wp-config.php");

$export_type = (isset($_GET['export_type'])) ? $_GET['export_type'] : "standard";

if ($export_type == 'standard')
{
	if (!isset($_GET['membership_id']) || !is_numeric($_GET['membership_id']))
	{
		exit; //must have membership ID
	}
	
	$membership = new MM_MembershipLevel();
	$membership->setId($_GET['membership_id']);
	$membership->getData();
	
	if (!$membership->isValid())
	{
		exit;
	}
	$filename = preg_replace("/([^A-za-z0-9\s])/","",strtolower($membership->getName()));
	$filename = preg_replace("/\s/","_",$filename)."_export.csv";
}
else if ($export_type == 'cancellation')
{
	$filename = "cancelled_members_export.csv";
}
else 
{
	$filename = "member_export.csv";
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

$outstream = fopen("php://output",'w');

$user_fields = array("user_email"=>"Email", "first_name"=>"First Name", "last_name"=>"Last Name");

$sql = "select id, short_name from ".MM_TABLE_BUNDLES;
$bundle_results = $wpdb->get_results($sql, ARRAY_A);
$bundles = array();

foreach ($bundle_results as $rownum=>$access_tag)
{
	$bundles[$access_tag['short_name']] = $access_tag['short_name'];
}

//output header row
$header_row = array_merge($user_fields, $bundles);
fputcsv($outstream, $header_row, ',', '"');

$sql = "SELECT u.id, u.user_email, mmu.status, mmu.first_name, mmu.last_name ";
$sql .= "FROM ".$wpdb->users." u, ".MM_TABLE_USER_DATA." mmu WHERE (mmu.wp_user_id = u.ID) ";

if ($export_type == 'cancellation')
{
	$sql .= " AND ((mm_status = %d) OR (mm_status = %d))";
	$results = $wpdb->get_results($wpdb->prepare($sql,MM_Status::$PAUSED, MM_Status::$CANCELED), ARRAY_A);
}
else 
{
	$sql .= " AND (mmu.membership_level_id = %d) AND ((mmu.status = %d) OR (mmu.status = %d) OR (mmu.status = %d))";
	$results = $wpdb->get_results($wpdb->prepare($sql, $membership->getId(), MM_Status::$ACTIVE, MM_Status::$LOCKED, MM_Status::$OVERDUE), ARRAY_A);
}

foreach ($results as $rownum=>$member_row) 
{
	$current_row_output = array();
	foreach ($user_fields as $field_key=>$v)
	{
		$current_row_output[] = $member_row[$field_key];
	}
	$member = MM_user::findByEmail($member_row['user_email']);
	
	if (!$member->isValid())
	{
		// shouldn't ever happen
		continue;
	}
	
	$appliedBundles = $member->getAppliedBundles();
	$current_member_bundles = array();
	foreach ($appliedBundles as $appliedBundle)
	{
		$bundle = $appliedBundle->getBundle();
		
		if($bundle->isValid())
		{
			$shortName = strtoupper($bundle->getShortName());
			$current_member_bundles[$shortName] = true;
		}
	}
	
	foreach ($bundles as $short_name)
	{
		$current_row_output[]  = (isset($current_member_bundles[$short_name]) && ($current_member_bundles[$short_name] === true))?"TRUE":"FALSE";
	}
	fputcsv($outstream, $current_row_output, ',', '"');
}

fclose($outstream);
?>