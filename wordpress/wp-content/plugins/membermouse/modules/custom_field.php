<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_CustomFieldView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "custom field";

$rows = array();

foreach($data as $key=>$item)
{		
	$customField = new MM_CustomField($item->id);
	
    // Actions
	$actions = '<a title="Edit Custom Field" onclick="mmjs.edit(\'mm-custom-fields-dialog\', \''.$customField->getId().'\', 475, 335)" style="margin-left: 5px; cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';
	
	if(!MM_CustomField::isBeingUsed($customField->getId()))
	{
		$actions .= '<a title="Delete Custom Field" onclick="mmjs.remove(\''.$customField->getId().'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
	}
	else
	{
		$actions .= '<a title="This custom field is currently being used and cannot be deleted" style="margin-left: 5px;"><img src="'.MM_Utils::getImageUrl("delete-not-allowed").'" /></a>';
	}
	
	if($item->show_on_my_account)
	{
		$myAcctPage = '<img src="'.MM_Utils::getImageUrl("tick").'" style="vetical-align:middle;" title="Show on My Account Page" />';
	}
	else
	{
		$myAcctPage = '<img src="'.MM_Utils::getImageUrl("cross").'" style="vetical-align:middle;" title="Hide on My Account Page" />';
	}
	
	$smartTags = '<a title="Show Checkout Form SmartTag" onclick="mmjs.showCheckoutFormSmartTags('.$customField->getId().',\''.addslashes($customField->getDisplayName()).'\')" class="mm-button small"><img src="'.MM_Utils::getImageUrl('smarttag').'" style="vertical-align:middle;" /></a>';
	
    $rows[] = array
    (
    	array('content' => "<span title='ID [".$customField->getId()."]'>".$customField->getDisplayName()."</span>"),	
    	array('content' => MM_CustomField::getFieldTypeName($item->type)),
    	array('content' => $myAcctPage),
    	array('content' => $smartTags),
    	array('content' => $actions),
    );
}

$headers = array
(
		'name'					=> array('content' => 'Name'),
		'type'					=> array('content' => 'Type', "attr" => "style='width:110px;'"),
		'show_on_my_account'	=> array('content' => 'My Account Page', "attr" => "style='width:125px;'"),
		''						=> array('content' => 'Checkout SmartTag', "attr" => "style='width:145px;'"),
		'actions'				=> array('content' => 'Actions', "attr" => "style='width:60px;'")
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);
$dataGrid->width = "750px";

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No custom fields.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-custom-fields-dialog', 475, 335)" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create Custom Field</a>
	</div>
	
	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
</div>