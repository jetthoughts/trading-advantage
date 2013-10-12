<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_REQUEST[MM_Session::$PARAM_USER_ID])) 
{
	$user = new MM_User($_REQUEST[MM_Session::$PARAM_USER_ID]);
	
	if($user->isValid()) 
	{
		include_once MM_MODULES."/details.header.php";
		
		// prepare data grid
		$view = new MM_TransactionHistoryView();
		$dataGrid = new MM_DataGrid($_REQUEST, "date", "desc", 10);
		$data = $view->getViewData($user->getId(),$dataGrid);
		$rows = $view->generateRows($data, true);
		$dataGrid->setTotalRecords($data);
		$dataGrid->recordName = "transaction";
		
		$headers = array
		(
			'orderNumber'   => array('content' => '<a onclick="mmjs.sort(\'orderNumber\');" href="#">Order#</a>'),
		   	'date'			=> array('content' => '<a onclick="mmjs.sort(\'date\');" href="#">Date</a>'),
		   	'productName'	=> array('content' => '<a onclick="mmjs.sort(\'productName\');" href="#">Product Name</a>'),
		   	'amount'		=> array('content' => '<a onclick="mmjs.sort(\'amount\');" href="#">Amount</a>'),
		   	'transType'		=> array('content' => '<a onclick="mmjs.sort(\'transType\');" href="#">Type</a>'),
		   	'affiliate'		=> array('content' => 'Affiliate'),
		   	'actions'		=> array('content' => 'Actions')
		);
		
		$dataGrid->setHeaders($headers);
		$dataGrid->setRows($rows);
		
		$dgHtml = $dataGrid->generateHtml();
		
		if($dgHtml == "") 
		{
			$dgHtml = "<p><i>No transactions.</i></p>";
		}
?>
<div class="mm-wrap">
	<div id="mm-form-container">
		<input type="hidden" name="user_id" value="<?php echo $user->getId();?>"/>
		
		<?php if(isset($_GET["page"])) { ?>
		<input type="hidden" name="page" value="<?php echo $_GET["page"];?>"/>
		<?php } ?>
		
		<?php if(isset($_GET["module"])) { ?>
		<input type="hidden" name="module" value="<?php echo $_GET["module"];?>"/>
		<?php } ?>
	</div>
	
	<?php echo $dgHtml; ?>
</div>

<div id="mm-issue-refund-dialog" style="display:none;" title="Refund Request" style="font-size:11px;">
</div>
<?php 
	}
	else 
	{
		echo "<div style=\"margin-top:10px;\"><i>Invalid Member ID</i></div>";
	}
}
?>