<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_ProductView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "product";

$rows = array();

foreach($data as $key=>$item)
{	
	$product = new MM_Product($item->id);
	
	// Actions
	$actions = '<a title="Edit Product" onclick="mmjs.edit(\'mm-products-dialog\', \''.$product->getId().'\', 580, 600)" style="margin-left: 5px; cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';
	
	if(!MM_Product::isBeingUsed($product->getId()) && !MM_Product::hasBeenPurchased($product->getId()))
	{
		$actions .= '<a title="Delete Product" onclick="mmjs.remove(\''.$product->getId().'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
	}
	else
	{
		$actions .= '<a title="This product is currently being used and cannot be deleted" style="margin-left: 5px;"><img src="'.MM_Utils::getImageUrl("delete-not-allowed").'" /></a>';
	}
	
	$purchaseLinks = '<a title="Get purchase links" onclick="mmjs.showPurchaseLinks('.$product->getId().',\''.addslashes($product->getName()).'\')" class="mm-button small"><img src="'.MM_Utils::getImageUrl('cash').'" style="vertical-align:middle;" /></a>';
	
	
	// Associated Access
	$accessGranted = "";
	
	$membership = $product->getAssociatedMembership();
	
	if($membership->isValid())
	{
		$accessGranted .= "<img title='Membership Level' style='vertical-align: middle;' src=".MM_Utils::getImageUrl("user")." /> <a href='".MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_MEMBERSHIP_LEVELS)."&autoload=".$membership->getId()."'>".$membership->getName()."</a>";
	}
	
	if(empty($accessGranted))
	{
		$bundle = $product->getAssociatedBundle();
	
		if($bundle->isValid())
		{
			$accessGranted .= "<img title='Bundle' style='vertical-align: middle;' src=".MM_Utils::getImageUrl("package")." /> <a href='".MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_BUNDLES)."&autoload=".$bundle->getId()."'>".$bundle->getName()."</a>";
		}
	}
	
	if(empty($accessGranted))
	{
		$accessGranted = MM_NO_DATA;
	}
	
	
	// Attributes
	$attributes = "";
	
	if($product->hasTrial())
	{
		$attributes .= "<img title='Has Trial' style='margin-right:5px;' src='".MM_Utils::getImageUrl("time")."' />";
	}
	else
	{
		$attributes .= "<img title='No Trial' style='margin-right:5px;' src='".MM_Utils::getImageUrl("bullet_white")."' />";
	}
	
	if($product->isRecurring())
	{
		if($product->doLimitPayments())
		{
			$attributes .= "<img title='Payment Plan' style='margin-right:5px;' src='".MM_Utils::getImageUrl("calendar")."' />";
		}
		else 
		{
			$attributes .= "<img title='Subscription' style='margin-right:5px;' src='".MM_Utils::getImageUrl("refresh")."' />";
		}
	}
	else
	{
		$attributes .= "<img title='No Recurring' style='margin-right:5px;' src='".MM_Utils::getImageUrl("bullet_white")."' />";
	}
	
	if($product->isShippable())
	{
		$attributes .= "<img title='Requires Shipping' style='margin-right:5px;' src='".MM_Utils::getImageUrl("lorry")."' />";
	}
	else
	{
		$attributes .= "<img title='No Shipping Required' style='margin-right:5px;' src='".MM_Utils::getImageUrl("bullet_white")."' />";
	}
	
	if($product->getSku() != "")
	{
		$attributes .= "<img title='SKU [".$product->getSku()."]' style='margin-right:5px;' src='".MM_Utils::getImageUrl("barcode")."' />";
	}
	else
	{
		$attributes .= "<img title='No SKU' style='margin-right:5px;' src='".MM_Utils::getImageUrl("bullet_white")."' />";
	}
	
	
    $rows[] = array
    (
    	array('content' => "<span title='ID [".$product->getId()."]'>".$product->getName()."</span>"),
    	array('content' => $product->getBillingDescription()),
    	array('content' => $attributes),
    	array('content' => $accessGranted),
    	array('content' => $purchaseLinks),
    	array('content' => MM_Utils::getStatusImage($product->getStatus())),
    	array('content' => $actions)
    );
}

$headers = array
(
		'name'			=> array('content' => '<a onclick="mmjs.sort(\'name\');" href="#">Name</a>'),
		'billing'		=> array('content' => 'Billing Description'),
		'attributes'	=> array('content' => 'Attributes'),
		'access'		=> array('content' => 'Associated Access'),
		'links'			=> array('content' => 'Purchase Links'),
		'status'		=> array('content' => '<a onclick="mmjs.sort(\'status\');" href="#">Status</a>'),
		'actions'		=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") 
{
	$dgHtml = "<p><i>No products found.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-products-dialog', 580, 600)" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create Product</a>
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
		 echo 'mmjs.create(\'mm-products-dialog\', 580, 600);';
	}
	else
	{
		echo 'mmjs.edit(\'mm-products-dialog\', \''.$_REQUEST["autoload"].'\', 580, 600);';
	}
	?>
});
</script>
<?php } ?>