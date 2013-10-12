<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_MembershipLevelsView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$data = $view->getData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "membership level";

$rows = array();

foreach($data as $key=>$item)
{	
    $membership = new MM_MembershipLevel($item->id, false);
	
	// Default Flag
	$defaultDescription = "Any free membership level can be marked as the default membership level. The default membership level is used when a customer&rsquo;s first purchase is for a bundle. In this scenario, a new account will be created for the customer with the default membership level and the bundle will be applied to their account.";
    
	if($item->is_default == '1') 
	{
		$defaultFlag = "<a title='Default Membership Level\n\n".$defaultDescription."' style='margin-right:5px;'><img src='".MM_Utils::getImageUrl("default_flag")."' /></a>";
	}
	else if($item->status == '1' && $item->is_free == '1')
	{
		$defaultFlag = "<a title='Set as Default Membership Level\n\n".$defaultDescription."' onclick='mmjs.setDefault(\"".$item->id."\")' style='cursor:pointer'><img src='".MM_Utils::getImageUrl("set_default")."' /></a>";
	} 
	else	
	{
		$defaultFlag = "<a style='margin-right:5px;'><img src='".MM_Utils::getImageUrl("clear")."' /></a>";
	}
    	
   	// Product Assocations
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
   		
   		$productAssociations = "<a title='Products' style='margin-right:5px;'><img src='".MM_Utils::getImageUrl("cart")."' /></a> ".join(', ' , $products);
   		$membershipLevel = '<img src="'.MM_Utils::getImageUrl("money").'" style="vertical-align:middle" title="Paid Membership Level" />';
   		$purchaseLinks = '<a title="Get purchase links" onclick="mmjs.showPurchaseLinks('.$item->id.',\''.addslashes($item->name).'\', \''.join(',' , $productIds).'\')" class="mm-button small"><img src="'.MM_Utils::getImageUrl('cash').'" style="vertical-align:middle;" /></a>';
   	}
   	else 
   	{
    	$membershipLevel = '<img src="'.MM_Utils::getImageUrl("no_money").'" style="vertical-align:middle" title="Free Membership Level" />';
    	$productAssociations = MM_NO_DATA;
    	$purchaseLinks = '<a title="Get purchase links" onclick="mmjs.showPurchaseLinks('.$item->id.',\''.addslashes($item->name).'\', \'\')" class="mm-button small"><img src="'.MM_Utils::getImageUrl('cash').'" style="vertical-align:middle;" /></a>';
   	}
	
    // Name / Subscribers		    
    if(!empty($item->member_count))
    {
   		$item->name .= '<p class="has-members" style="margin-left:25px"><a href="'.MM_ModuleUtils::getUrl(MM_MODULE_MANAGE_MEMBERS, MM_MODULE_BROWSE_MEMBERS).'&membershipId='.$item->id.'">'.$item->member_count.' Members</a></p>';
   	}
   	else
   	{
   		$item->name .= '<p class="no-members" style="margin-left:25px"><i>No Subscribers</i></p>';
   	}
    
    // Bundles   	
    $bundles = array();
    
    if(!empty($item->bundles)) 
    {
	   	foreach($item->bundles as $bundle) 
	   	{
	   		$bundles[] = "<a href='".MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_BUNDLES)."&autoload=".$bundle->id."'>".$bundle->name."</a>";
	   	}
    }
	
    
    if(!empty($bundles))
    {
    	$item->bundles = "<a title='Bundles' style='margin-right:5px;'><img src='".MM_Utils::getImageUrl("package")."' /></a> ".join(', ' , $bundles);
    }
    else
    {
    	$item->bundles = MM_NO_DATA;
    }
    
    // Actions
    $actions = '<a title="Edit Membership Level" onclick="mmjs.edit(\'mm-member-types-dialog\', \''.$item->id.'\')" style="margin-left: 5px; cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';
   	
    if(!$membership->hasAssociations() && intval($item->member_count) <= 0)
    {
    	$actions .= '<a title="Delete Membership Level" onclick="mmjs.remove(\''.$item->id.'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
    }
    else 
    {
    	$actions .= '<a title="This membership level is currently being used and cannot be deleted" style="margin-left: 5px;"><img src="'.MM_Utils::getImageUrl("delete-not-allowed").'" /></a>';
    }
    	
    $rows[] = array
    (
    	array('content' => $defaultFlag." <span title='ID [".$item->id."]'>".$item->name."</span>"),
    	array('content' => $membershipLevel),
    	array('content' => $productAssociations),
    	array('content' => $item->bundles),
    	array('content' => $purchaseLinks),
    	array('content' => MM_Utils::getStatusImage($item->status)),
    	array('content' => $actions),
    );
}

$headers = array
(	    
   	'name'			=> array('content' => '<a onclick="mmjs.sort(\'name\');" href="#">Name / Subscribers</a>'),
	'is_free'		=> array('content' => '<a onclick="mmjs.sort(\'is_free\');" href="#">Type</a>'),
	'products'		=> array('content' => 'Products', 'attr'=>'style="width:400px;"'),
   	'bundles'		=> array('content' => 'Bundles', 'attr'=>'style="width:200px;"'),
   	'purchaselinks'	=> array('content' => 'Purchase Links'),
   	'status'		=> array('content' => '<a onclick="mmjs.sort(\'status\');" href="#">Status</a>'),
   	'actions'		=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No membership levels.</i></p>";
}
?>
<div class="mm-wrap">
	
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-member-types-dialog')" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create Membership Level</a>
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
		 echo 'mmjs.create(\'mm-member-types-dialog\')';
	}
	else
	{
		echo 'mmjs.edit(\'mm-member-types-dialog\', \''.$_REQUEST["autoload"].'\');';
	}
	?>
});
</script>
<?php } ?>