<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_BundlesView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "bundle";

$rows = array();

foreach($data as $key => $item)
{
    $tag = new MM_Bundle($item->id, false);
    
	// Type / Products
	if($item->is_free != "1")
	{  	
	    $products = array();
	    $productIds = array();
	    
	    if(!empty($item->products)) 
	    {
		    foreach($item->products as $product)
	   		{
	   			$products[] = "<a href='".MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_PRODUCTS)."&autoload=".$product->id."'>".$product->name."</a>";
	   			$productIds[] = $product->id;
	   		}
	    }
	    
	    $bundleType = '<img src="'.MM_Utils::getImageUrl("money").'" style="vertical-align:middle" title="Paid Bundle" />';
		$productAssociations = "<img src='".MM_Utils::getImageUrl("cart")."' style='vertical-align:middle;' /> ".join(', ' , $products);
		$purchaseLinks = '<a title="Get purchase links" onclick="mmjs.showPurchaseLinks('.$item->id.',\''.addslashes($item->name).'\', \''.join(',' , $productIds).'\')" class="mm-button small"><img src="'.MM_Utils::getImageUrl('cash').'" style="vertical-align:middle;" /></a>';
	}
	else 
	{
		 $bundleType = ' <img src="'.MM_Utils::getImageUrl("no_money").'" style="vertical-align:middle" title="Free Bundle" />';
		 $productAssociations = MM_NO_DATA;
		 $purchaseLinks = MM_NO_DATA;
	}  
	
    // Name / Subscribers		    
    if(!empty($item->member_count))
    {
   		$item->name .= '<p class="has-members"><a href="'.MM_ModuleUtils::getUrl(MM_MODULE_MANAGE_MEMBERS, MM_MODULE_BROWSE_MEMBERS).'&bundleId='.$item->id.'">'.$item->member_count.' Subscribers</a></p>';
   	}
   	else
   	{
   		$item->name .= '<p class="no-members"><i>No Subscribers</i></p>';
   	}

    // Actions
    $actions = '<a title="Edit Bundle" onclick="mmjs.edit(\'mm-bundles-dialog\', \''.$item->id.'\')" style="cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';
    	
    if(!$tag->hasAssociations() && intval($item->member_count) <= 0)
    {
    	$actions .= '<a title="Delete Bundle" onclick="mmjs.remove(\''.$item->id.'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
    }
    else 
    {
    	$actions .= '<a title="This bundle is currently being used and cannot be deleted" style="margin-left: 5px;"><img src="'.MM_Utils::getImageUrl("delete-not-allowed").'" /></a>';
    }

	$rows[] = array
    (
    	array( 'content' => "<span title='ID [".$item->id."]'>".$item->name."</span>"),
    	array( 'content' => $bundleType),
    	array( 'content' => $productAssociations),
    	array( 'content' => $purchaseLinks),
    	array( 'content' => MM_Utils::getStatusImage($item->status)),
    	array( 'content' => $actions),
    );
}

$headers = array
(
	'name'			=> array('content' => '<a onclick="mmjs.sort(\'name\');" href="#">Name / Subscribers</a>'),
	'is_free'		=> array('content' => '<a onclick="mmjs.sort(\'is_free\');" href="#">Type</a>'),
	'products'		=> array('content' => 'Products', 'attr'=>'style="width:500px;"'),
   	'purchaselinks'	=> array('content' => 'Purchase Links'),
	'status'		=> array('content' => '<a onclick="mmjs.sort(\'status\');" href="#">Status</a>'),
	'actions'		=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No bundles.</i></p>";
}
?>
<div class="mm-wrap">
	
	<?php if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_BUNDLES)) { ?>
		<div class="mm-button-container">
			<a onclick="mmjs.create('mm-bundles-dialog')" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create Bundle</a>
		</div>
	
		<div class="clear"></div>
		
		<?php echo $dgHtml; ?>
	<?php } else { ?>
		<img src="<?php echo MM_Utils::getImageUrl('lock'); ?>" style="vertical-align:middle" /> 
		This feature is not available on your current plan. To get access, <a href="<?php echo MM_MemberMouseService::getUpgradeUrl(MM_MemberMouseService::$FEATURE_BUNDLES); ?>" target="_blank">upgrade your plan now</a>!
	<?php } ?>
</div>

<?php if(isset($_REQUEST["autoload"])) { ?>
<script type='text/javascript'>
jQuery(document).ready(function() {
	<?php
	if($_REQUEST["autoload"] == "new")
	{
		 echo 'mmjs.create(\'mm-bundles-dialog\');';
	}
	else
	{
		echo 'mmjs.edit(\'mm-bundles-dialog\', \''.$_REQUEST["autoload"].'\');';
	}
	?>
});
</script>
<?php } ?>