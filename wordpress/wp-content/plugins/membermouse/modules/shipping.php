<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_ShippingMethodsView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "asc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->width = "500px;";
$dataGrid->recordName = "shipping method";

$rows = array();

$headers = array
(	    
   	'name'				=> array('content' => 'Name'),
   	'rate'				=> array('content' => 'Rate'),
   	'actions'			=> array('content' => 'Actions')
);

foreach($data as $key=>$item)
{	
	
    // Actions
	$actions = '<a title="Edit Shipping Method" onclick="mmjs.edit(\'mm-shipping-dialog\', {id:'.$item->id.',mm_setting_type:\'shipping\'}, 300,195)" style="margin-left: 5px; cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';
	$actions .= '<a title="Delete Shipping Method" onclick="mmjs.remove(\''.$item->id.'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
    $rate = (floatval($item->rate)>0)?_mmf($item->rate,$item->currency):MM_NO_DATA;
	
	$rows[] = array
    (
    	array('content' => "<img src=".MM_Utils::getImageUrl("key")." style='vertical-align:middle;' title='".MM_ShippingMethod::$FLATRATE_SHIPPING."-{$item->id}' /> <span title='ID [".$item->id."]'>".$item->option_name."</span>"),
    	array('content' => $rate),
    	array('content' => $actions),
    );
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No shipping methods.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-shipping-dialog', 300,195)" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create Shipping Method</a>
	</div>
	
	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
</div>