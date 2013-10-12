<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$employee = new MM_Employee($p->id);
$disableRole = "";
global $current_user;

// employees can't edit their own role
if($current_user->ID == $employee->getUserId())
{
	$disableRole = "disabled='disabled'";
}
?>
<div id="mm-form-container">
	<table cellspacing="10">
		<tr>
			<td colspan="2">
			<span style="font-size:11px;">
			Employee accounts are used to grant access to additional members of your team. Once the 
			account has been created, the employee can login with the email address and password associated with the account. Employees with
			the <code>Adminstrator</code> role have the same permissions as a standard WordPress administrator and have access to all MemberMouse
			configuration modules. Employess with the <code>Sales</code> or <code>Support</code> role will only be able to access the MemberMouse 
			member management pages. 
			</span>
			</td>
		</tr>
		<tr>
			<td>Role</td>
			<td>
				<select id='mm-role-id' <?php echo $disableRole; ?>>
				<?php 
				echo MM_HtmlUtils::generateSelectionsList(MM_Role::getRoleList(), $employee->getRoleId());
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Display Name* </td>
			<td><input id="mm-display-name" type="text" class="medium-text" value='<?php echo $employee->getDisplayName(); ?>'/></td>
		</tr>
		<tr>
			<td>Email*</td>
			<td><input id="mm-email" type="text" class="medium-text" value='<?php echo $employee->getEmail(); ?>' <?php echo ($employee->isValid()) ? "disabled":""; ?>/></td>
		</tr>
		<?php if(!$employee->isValid()) { ?>
		<tr>
			<td>Password*</td>
			<td><input id="mm-password" type="password" value='' /></td>
		</tr>
		<?php } ?>
		<tr>
			<td>Real Name</td>
			<td>
				<input id="mm-first-name" type="text" value='<?php echo $employee->getFirstName(); ?>'  />
				<input id="mm-last-name" type="text" value='<?php echo $employee->getLastName(); ?>'  />
			</td>
		</tr>
		<tr>
			<td>Phone</td>
			<td><input id="mm-phone" type="text" value='<?php echo $employee->getPhone(); ?>' /></td>
		</tr>
	</table>
	
	<input id='id' type='hidden' value='<?php echo $employee->getId(); ?>' />
	<input id='mm-is-default' type='hidden' value='<?php echo $employee->isDefault(); ?>' />
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.save();" class="mm-button blue">Save Employee</a>
<a href="javascript:mmjs.closeDialog();" class="mm-button">Cancel</a>
</div>
</div>