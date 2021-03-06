<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_ApiView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "asc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->width = "700px";
$dataGrid->recordName = "API credential";

$rows = array();

$headers = array
(	    
   	'name'				=> array('content' => 'Name'),
   	'api_key'			=> array('content' => 'Key'),
   	'api_secret'		=> array('content' => 'Password'),
   	'status'			=> array('content' => 'Status'),
   	'actions'			=> array('content' => 'Actions')
);

foreach($data as $key=>$item)
{	
    // Actions
	$actions = '<a title="Edit API Credentials" onclick="mmjs.edit(\'mm-api-keys-dialog\', \''.$item->id.'\', 500, 280)" style="margin-left: 5px; cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';
	$actions .= '<a title="Delete API Credentials" onclick="mmjs.remove(\''.$item->id.'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
    $rows[] = array
    (
    	array('content' => "<span title='ID [".$item->id."]'>".$item->name."</span>"),
    	array('content' => "<span style='font-family:courier; font-size:12px;'>".$item->api_key."</span>"),
    	array('content' => "<span style='font-family:courier; font-size:12px;'>".$item->api_secret."</span>"),
    	array('content' => MM_Utils::getStatusImage($item->status)),
    	array('content' => $actions),
    );
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No API credentials found.</i></p>";
}
?>
<div class="mm-wrap">
	<?php if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_API)) { ?>
		<p style="margin-top:10px">
			<span class="mm-section-header">API URLs</span>
		</p>
		<p style="margin-left:15px;">
			Standard URL:<br/>
			<span style="font-family:courier; font-size:11px;">
				<input id="mm-api-url" type="text" readonly value="<?php echo MM_API_URL; ?>" style="width:600px" onclick="jQuery('#mm-api-url').focus(); jQuery('#mm-api-url').select();" />
			</span>
		</p>
		<p style="margin-left:15px;">
			Secure URL:<br/>
			<span style="font-family:courier; font-size:11px;">
				<input id="mm-secure-api-url" type="text" readonly value="<?php echo preg_replace("/(http\:)/", "https:", MM_API_URL); ?>" style="width:600px" onclick="jQuery('#mm-secure-api-url').focus(); jQuery('#mm-secure-api-url').select();" />
			</span>
		</p>

		<div style="width: 700px; margin-top: 20px; margin-bottom: 20px;" class="mm-divider"></div>
		
		<a onclick="mmjs.create('mm-api-keys-dialog', 500,280)" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create API Credentials</a>
		
		<div class="clear"></div>
		
		<?php echo $dgHtml; ?>
	<?php } else { ?>
		<img src="<?php echo MM_Utils::getImageUrl('lock'); ?>" style="vertical-align:middle" /> 
		This feature is not available on your current plan. To get access, <a href="<?php echo MM_MemberMouseService::getUpgradeUrl(MM_MemberMouseService::$FEATURE_API); ?>" target="_blank">upgrade your plan now</a>!
	<?php } ?>
</div>