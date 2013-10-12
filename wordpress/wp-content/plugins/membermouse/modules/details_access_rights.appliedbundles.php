<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if($user && $user->isValid())
{

$dataGrid = new MM_DataGrid();
$dataGrid->showPagingControls = false;
$dataGrid->recordName = "bundle";

$rows = array();

// applied bundles
$appliedBundles = $user->getAppliedBundles(true);

foreach($appliedBundles as $appliedBundle)
{	
	// Bundle
	$bundle = $appliedBundle->getBundle();
	
	// status
	$status = MM_Status::getImage($appliedBundle->getStatus());
	
	if($appliedBundle->isComplimentary())
	{
		$status .= ' <img src="'.MM_Utils::getImageUrl("award_star_gold").'" style="vertical-align:middle" title="Bundle is complimentary" />';
	}
	
	if($appliedBundle->isImported())
	{
		$status .= ' <img src="'.MM_Utils::getImageUrl("package_go").'" style="vertical-align:middle" title="Bundle applied through import" />';
	}
	
	// actions
	$actions = "";
	$showCancel = false;
	$showPause = false;
	$showActivate = false;
	$showEditCalc = false;
	if($appliedBundle->getStatus() == MM_Status::$ACTIVE || $appliedBundle->getStatus() == MM_Status::$EXPIRED || $appliedBundle->getStatus() == MM_Status::$PAUSED)
	{
		$showCancel = true;
		$showEditCalc = true;
		
		if($appliedBundle->getStatus() == MM_Status::$ACTIVE || $appliedBundle->getStatus() == MM_Status::$EXPIRED) 
		{
			$showPause = true;
		}
		else if($appliedBundle->getStatus() == MM_Status::$PAUSED) 
		{
			$showActivate = true;
		}
	}
	else
	{
		$showActivate = true;
	}
	
	if($showActivate)
	{
		$actions .= "<a style=\"cursor: pointer;\" onclick=\"mmjs.changeBundleStatus('{$user->getId()}', '{$bundle->getId()}', '".MM_Status::$ACTIVE."')\" title=\"Activate {$bundle->getName()}\"><img src=\"".MM_Utils::getImageUrl('accept')."\" /></a> ";
	}
	else
	{
		$actions .= "<img src=\"".MM_Utils::getImageUrl('bullet_white')."\" style=\"vertical-align:middle;\" /> ";
	}
	
	if($showCancel)
	{
		$actions .= "<a style=\"cursor: pointer;\" onclick=\"mmjs.changeBundleStatus('{$user->getId()}', '{$bundle->getId()}', '".MM_Status::$CANCELED."')\" title=\"Cancel {$bundle->getName()}\"><img src=\"".MM_Utils::getImageUrl('stop')."\" /></a> ";
	}
	else
	{
		$actions .= "<img src=\"".MM_Utils::getImageUrl('bullet_white')."\" style=\"vertical-align:middle;\" /> ";
	}
	
	if($showPause)
	{
		$actions .= " <a style=\"cursor: pointer;\" onclick=\"mmjs.changeBundleStatus('{$user->getId()}', '{$bundle->getId()}', '".MM_Status::$PAUSED."')\" title=\"Pause {$bundle->getName()}\"><img src=\"".MM_Utils::getImageUrl('pause')."\" /></a> ";
	}
	else
	{
		$actions .= "<img src=\"".MM_Utils::getImageUrl('bullet_white')."\" style=\"vertical-align:middle;\" /> ";
	}
	
	if($showEditCalc)
	{
		$actions .= ' <a style="cursor: pointer" onclick="mmjs.editBundleConfiguration(\''.$user->getId().'\', \''.$bundle->getId().'\')" title="Edit bundle configuration"><img src="'.MM_Utils::getImageUrl('edit').'" /></a>';
	}
	else
	{
		$actions .= "<img src=\"".MM_Utils::getImageUrl('bullet_white')."\" style=\"vertical-align:middle;\" /> ";
	}
	
    $rows[] = array
    (
    	array('content' => MM_Utils::abbrevString($bundle->getName())),
    	array('content' => $status),
    	array('content' => "<span style='font-family:courier;'>".number_format($user->getDaysWithBundle($bundle->getId()))."</span>"),
    	array('content' => $appliedBundle->getApplyDate(true)),
    	array('content' => $appliedBundle->getStatusUpdatedDate(true)),
    	array('content' => $actions)
    );
}

// membership level bundles
$membershipBundles = $membership->getBundles();

foreach($membershipBundles as $id=>$name)
{
	$status = ' <img src="'.MM_Utils::getImageUrl("user").'" style="vertical-align:middle" title="Bundle applied through membership" />';

	$rows[] = array
	(
			array('content' => $name),
			array('content' => $status),
			array('content' => MM_NO_DATA),
			array('content' => MM_NO_DATA),
			array('content' => MM_NO_DATA),
			array('content' => MM_NO_DATA)
	);
}

$headers = array
(
	'bundle'		=> array('content' => 'Bundle'),
	'status'		=> array('content' => 'Status'),
	'days'			=> array('content' => '<span title=\'Days with Bundle\'>Days...</span>'),
	'date_added'	=> array('content' => 'First Applied'),
	'last_updated'	=> array('content' => 'Last Updated'),
	'actions'		=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "")
{
	$dgHtml = "<p><em>No bundles found.</em></p>";
}
?>
<div id="mm-grid-container">
	<?php echo $dgHtml; ?>
</div>
<div id='mm-edit-bundle-configuration-dialog'></div>
<?php } ?>