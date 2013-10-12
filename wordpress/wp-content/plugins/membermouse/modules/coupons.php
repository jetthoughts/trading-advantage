<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_CouponView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "coupon";

$rows = array();

foreach($data as $key => $item)
{
    $coupon = new MM_Coupon($item->id);
    
	$availableDates = "";
	$endDate = $coupon->getEndDate(true);
	
	if(!empty($endDate))
	{    	
		$availableDates = $coupon->getStartDate(true)." - ".$endDate;
	}
	else
	{
		$availableDates = "After ". $coupon->getStartDate(true);
	}
	
	switch($coupon->getQuantity())
	{
		case "-1":
		case "":
			$quantityDescription = number_format($item->quantity_used)." used";
			break;
			
		default:
			$quantityDescription = number_format($item->quantity_used)." of ".number_format($coupon->getQuantity())." used ";
			break;
	}
	
	$description = "";
	
	switch($coupon->getCouponType())
	{
		case MM_Coupon::$TYPE_PERCENTAGE:
			$description = "<span style='font-family:courier;'>".$coupon->getCouponValue()."%</span> off";
			break;
			
		case MM_Coupon::$TYPE_DOLLAR:
			$description = "<span style='font-family:courier;'>".$coupon->getCouponValue(true)."</span> off";
			break;
			
		case MM_Coupon::$TYPE_FREE:
			$description = "<span style='font-family:courier;'>FREE</span>";
			break;
	}
	
	if($coupon->getCouponType() != MM_Coupon::$TYPE_FREE)
	{
		if($coupon->getRecurringBillingSetting() == "all")
		{
			$description .= " all charges";
		}
		else
		{
			$description .= " the first charge";
		}
	}
	
	$actions = '<a title="Edit Coupon" onclick="mmjs.edit(\'mm-coupons-dialog\', \''.$coupon->getId().'\', 620, 615)" style="margin-left: 5px; cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';

	if(!MM_Coupon::isBeingUsed($coupon->getId()))
    {
    	$actions .= '<a title="Delete Coupon" onclick="mmjs.remove(\''.$coupon->getId().'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
    }
    else 
    {
    	$actions .= '<a title="This coupon is currently being used and cannot be deleted" style="margin-left: 5px;"><img src="'.MM_Utils::getImageUrl("delete-not-allowed").'" /></a>';
    }
    
    $rows[] = array
    (
    		array( 'content' => "<span title='ID [".$coupon->getId()."]'>".$coupon->getCouponName()."</span>"),
    		array( 'content' => "<span style='font-family:courier;'>".strtoupper($coupon->getCouponCode())."</span>"),
    		array( 'content' => $description),
    		array( 'content' => $quantityDescription),
    		array( 'content' => $availableDates),
    		array( 'content' => (empty($item->product_restrictions) ? MM_NO_DATA : $item->product_restrictions)),
    		array( 'content' => $actions)
    );
}

$headers = array
(
	'name'					=> array('content' => '<a onclick="mmjs.sort(\'c.coupon_name\');" href="#">Name</a>'),
	'coupon_code'			=> array('content' => '<a onclick="mmjs.sort(\'c.coupon_code\');" href="#">Coupon Code</a>'),
	'description'			=> array('content' => 'Description'),
	'quantity_used'			=> array('content' => '<a onclick="mmjs.sort(\'quantity_used\');" href="#"># Used</a>'),
	'start_date_end_date'	=> array('content' => '<a onclick="mmjs.sort(\'c.start_date\');" href="#">Valid Dates</a>'),
	'product_restrictions'	=> array('content' => 'Product Restrictions'),
	'actions'				=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No coupons.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-coupons-dialog', 620, 615)" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create Coupon</a>
	</div>

	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
</div>