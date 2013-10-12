<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

// Attempt to correct any invalid employees. Otherwise, delete invalid accounts 
global $wpdb;
$sql = "SELECT ea.* FROM ".MM_TABLE_EMPLOYEE_ACCOUNTS." ea LEFT JOIN {$wpdb->users} u ON (ea.user_id = u.ID) ".
	   "where ((ea.user_id IS NULL) OR (u.ID IS NULL))";
$results = $wpdb->get_results($sql);
$invalid_account_ids = array();
if ($wpdb->num_rows > 0)
{
	//either these accounts are not linked or the user_id is not valid (user with that ID does not exist in WP)
	foreach ($results as $nullAccount)
	{
		$currentAccount = new MM_Employee($nullAccount->id);
		if ($currentAccount->isValid())
		{
			$currentAccount->commitData(); //the commit logic will clean up any invalid links
			
			if (($currentAccount->getUserId() instanceof WP_Error) || ($currentAccount->getUserId() <= 0))
			{
				$invalid_account_ids[] = $currentAccount->getId();
			}
		}
	}
}

if (count($invalid_account_ids) > 0)
{
	$successfully_deleted_accounts = array();
	
	// attempt to delete all invalid accounts
	for($i = 0; $i < count($invalid_account_ids); $i++) 
	{
		$account = new MM_Employee($invalid_account_ids[$i]);
		
		if($account->isValid()) 
		{
			$userEmail = $account->getEmail();
			$response = $account->delete();
			
			// if account was deleted successfully, store it in $successfully_deleted_accounts
			if($response) 
			{
				$successfully_deleted_accounts[] = $userEmail;
			}
		}
	}
	
	// if one of more accounts were deleted successfully, display a message
	if(count($successfully_deleted_accounts) > 0) 
	{
		$error = "The following invalid accounts were detected and have been deleted: <strong>".implode(", ",$successfully_deleted_accounts)."</strong>";
		echo "<div class='updated'>";
		echo "<p>".$error."</p>";
		echo "</div>";
	}
}

// prepare data grid
$view = new MM_EmployeesView();
$dataGrid = new MM_DataGrid($_REQUEST, "display_name", "asc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "employee";

$rows = array();

foreach($data as $key => $item)
{
	// Default
	$defaultDescription = "The default employee email address is used when MemberMouse sends a customer an email (i.e. the forgot password email)";
    
	if($item->is_default == '1') 
	{
		$defaultFlag = "<a title='Default Employee\n\n".$defaultDescription."' style='margin-right:5px;'><img src='".MM_Utils::getImageUrl("default_flag")."' /></a>";
	}
	else 
	{
		$defaultFlag = "<a title='Set as Default Employee\n\n".$defaultDescription."' onclick='mmjs.setDefault(\"".$item->id."\")' style='cursor:pointer'><img src='".MM_Utils::getImageUrl("set_default")."' /></a>";
	}
	
	// Full Name
	$realName = MM_NO_DATA;
	
	if($item->first_name != "")
	{
		$realName = $item->first_name;
		
		if($item->last_name != "")
		{
			$realName .= " ".$item->last_name." ";
		}
	}
	else if($item->last_name != "") 
	{
		$realName = $item->last_name." ";
	}
	
	// Phone
	if($item->phone != "")
	{
		$phone = $item->phone;
	}
	else
	{
		$phone = MM_NO_DATA;
	}
	
	// Role Name
 	$item->role_name = MM_Role::getRoleName($item->role_id);
	if(empty($item->role_name))
	{
		$item->role_name = MM_NO_DATA;
	}
    	
    // Actions
    $actions = '<a title="Edit Employee" onclick="mmjs.edit(\'mm-employee-accounts-dialog\', \''.$item->id.'\', 540, 450)" style="margin-left: 5px; cursor:pointer"><img src="'.MM_Utils::getImageUrl("edit").'" /></a>';
    
    if(!MM_Employee::isBeingUsed($item->id))
    {
    	$actions .= '<a title="Delete Employee" onclick="mmjs.removeAccount(\''.$item->id.'\')" style="margin-left: 5px; cursor:pointer;"><img src="'.MM_Utils::getImageUrl("delete").'" /></a>';
    }
    else 
    {
    	$actions .= '<a title="This employee is currently being used and cannot be deleted" style="margin-left: 5px;"><img src="'.MM_Utils::getImageUrl("delete-not-allowed").'" /></a>';
    }
    
    $rows[] = array
    (
    	array( 'content' => $defaultFlag." <span title='ID [".$item->id."]'>".$item->display_name."</span>"),
    	array( 'content' => $realName),
    	array( 'content' => $item->email),
    	array( 'content' => $phone),
    	array( 'content' => $item->role_name),
    	array( 'content' => $actions),
    );
}

$headers = array
(
   	'display_name'	=> array('content' => '<a onclick="mmjs.sort(\'display_name\');" href="#" style="margin-left:22px;">Display Name</a>'),
   	'first_name'	=> array('content' => '<a onclick="mmjs.sort(\'first_name\');" href="#">Real Name</a>'),
   	'email'			=> array('content' => '<a onclick="mmjs.sort(\'email\');" href="#">Email</a>'),
   	'phone'			=> array('content' => '<a onclick="mmjs.sort(\'phone\');" href="#">Phone</a>'),
   	'role_id'		=> array('content' => '<a onclick="mmjs.sort(\'role_id\');" href="#">Role</a>'),
   	'actions'		=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No employees.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-employee-accounts-dialog', 540, 475)" class="mm-button green small"><img src="<?php echo MM_Utils::getImageUrl('add'); ?>" style="vertical-align:middle;" /> Create Employee</a>
	</div>

	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
</div>