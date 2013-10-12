<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_CommissionProfilesView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$data = $view->getData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "commission profile";

$rows = array();

foreach($data as $key=>$item)
{	
    $profile = new MM_CommissionProfile($item->id);
	
	// Default Flag
	$defaultDescription = "Any commission profile can be marked as the default commission profile. The default commission profile is used when a customer purchases any product in MemberMouse. The default profile can be overridden by editing a product, going to the Commissions section and selecting another commission profile from the drop down.";
    
	if($profile->isDefault()) 
	{
		$defaultFlag = "<a title='Default Commission Profile\n\n".$defaultDescription."' style='margin-right:5px;'><img src='".MM_Utils::getImageUrl("default_flag")."' /></a>";
	}
	else
	{
		$defaultFlag = "<a title='Set as Default Commission Profile\n\n".$defaultDescription."' onclick='mmjs.setDefault(\"".$item->id."\")' style='cursor:pointer'><img src='".MM_Utils::getImageUrl("set_default")."' /></a>";
	} 
	
	if($profile->initialCommissionEnabled())
	{
		$initialCommission = "<img src='".MM_Utils::getImageUrl("tick")."' style='vertical-align:middle;' />";
	}
	else
	{
		$initialCommission = "<img src='".MM_Utils::getImageUrl("cross")."' style='vertical-align:middle;' />";
	}
	
	if($profile->rebillCommissionsEnabled())
	{
		$rebillCommissions = "<img src='".MM_Utils::getImageUrl("tick")."' style='vertical-align:middle;' /> ";
		$rebillCommissions .= "<span style='font-family:courier;'>{$profile->getRebillConfigDescription()}</span>";
	}
	else
	{
		$rebillCommissions = "<img src='".MM_Utils::getImageUrl("cross")."' style='vertical-align:middle;' />";
	}
	
	if($profile->doReverseCommissions())
	{
		$doReverseCommissions = "<img src='".MM_Utils::getImageUrl("tick")."' style='vertical-align:middle;' />";
	}
	else
	{
		$doReverseCommissions = "<img src='".MM_Utils::getImageUrl("cross")."' style='vertical-align:middle;' />";
	}
    
    // Actions
    $actions = '<a title="Edit Commission Profile" onclick="mmjs.edit(\'mm-commission-profiles-dialog\', \''.$item->id.'\')" style="margin-left: 5px; cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';
   	
    if(!$profile->hasAssociations())
    {
    	$actions .= '<a title="Delete Commission Profile" onclick="mmjs.remove(\''.$item->id.'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
    }
    else 
    {
    	$actions .= '<a title="This commission profile is currently being used and cannot be deleted" style="margin-left: 5px;"><img src="'.MM_Utils::getImageUrl("delete-not-allowed").'" /></a>';
    }
    	
    $rows[] = array
    (
    	array('content' => $defaultFlag." <span title='ID [".$item->id."]'>".$profile->getName()."</span>"),
    	array('content' => $initialCommission),
    	array('content' => $rebillCommissions),
    	array('content' => $doReverseCommissions),
    	array('content' => $actions)
    );
}

$headers = array
(	    
   	'name'							=> array('content' => '<a onclick="mmjs.sort(\'name\');" href="#">Name</a>'),
   	'initial_commission_enabled'	=> array('content' => '<a onclick="mmjs.sort(\'initial_commission_enabled\');" href="#">Initial Commission</a>'),
   	'rebill_commissions_enabled'	=> array('content' => '<a onclick="mmjs.sort(\'rebill_commissions_enabled\');" href="#">Rebill Commissions</a>'),
   	'do_reverse_commissions'		=> array('content' => '<a onclick="mmjs.sort(\'do_reverse_commissions\');" href="#">Cancel Commissions</a>'),
   	'actions'						=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);
$dataGrid->width = "85%";

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No commission profiles.</i></p>";
}
?>
<div class="mm-wrap">
	
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-commission-profiles-dialog')" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create Commission Profile</a>
	</div>
	
	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
</div>

<?php if(isset($_REQUEST["autoload"])) { ?>
<script type='text/javascript'>
jQuery(document).ready(function() {
	<?php
	if($_REQUEST["autoload"] == "new")
	{
		 echo 'mmjs.create(\'mm-commission-profiles-dialog\')';
	}
	else
	{
		echo 'mmjs.edit(\'mm-commission-profiles-dialog\', \''.$_REQUEST["autoload"].'\');';
	}
	?>
});
</script>
<?php } ?>