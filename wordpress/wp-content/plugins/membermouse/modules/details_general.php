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
		
		$columnWidth = 125;
		$canChangeDaysCalc = true;
		if($user->getStatus() == MM_Status::$PAUSED)
		{
			$canChangeDaysCalc = false;
		}
		
		if($user->doesExpire())
		{
			$expirationDate = date("m/d/Y", strtotime($user->getExpirationDate()));
		}
		
		$customDateSelected = "";
		$fixedSelected = "";
		$joinDateSelected = "";	
		$customDateValue = "";
		$fixedValue = "";
		switch($user->getDaysCalcMethod())
		{
			case MM_DaysCalculationTypes::$CUSTOM_DATE:
				$calcMethod = MM_DaysCalculationTypes::$CUSTOM_DATE;
				$customDateValue  = date("m/d/Y", strtotime($user->getDaysCalcValue()));
				$customDateSelected = "checked";
				break;
				
			case MM_DaysCalculationTypes::$FIXED:
				$calcMethod = MM_DaysCalculationTypes::$FIXED;
				$fixedValue = $user->getDaysCalcValue();
				$fixedSelected = "checked";
				break;
				
			default:
				$calcMethod = MM_DaysCalculationTypes::$JOIN_DATE;
				$joinDateSelected = "checked";
				break;
		}
?>
<div id="mm-form-container">
	<input type="hidden" id="page" value="<?php echo $_REQUEST["page"];?>"/>
	<input type="hidden" id="module" value="<?php echo $_REQUEST["module"];?>"/>
	<table cellspacing="8">
		<tr>
			<td width="<?php echo $columnWidth; ?>px;">Membership Status</td>
			<td>
				<?php 
				$statusDesc = MM_Status::getImage($user->getStatus())." ";
				
				switch($user->getStatus())
				{
					case MM_Status::$ACTIVE:
					case MM_Status::$OVERDUE:
						$statusDesc .= "Account became ".MM_Status::getName($user->getStatus(), true)." on ".$user->getStatusUpdatedDate(true);
						break;

					case MM_Status::$CANCELED:
					case MM_Status::$PAUSED:
					case MM_Status::$LOCKED:
						$statusDesc .= "Account was ".MM_Status::getName($user->getStatus(), true)." on ".$user->getStatusUpdatedDate(true);
						break;
						
					case MM_Status::$ERROR:
						$statusDesc .= "An error was encountered when creating this account.";
						break;
						
					case MM_Status::$PENDING:
						$statusDesc .= "Account is pending activation";
						break;
						
					case MM_Status::$EXPIRED:
						$statusDesc .= "Account ".MM_Status::getName($user->getStatus(), true)." on ".$user->getStatusUpdatedDate(true);
						break;
				}
				?>
				
				<div style="margin-bottom:5px;">
					<?php echo $statusDesc; ?>
				</div>
				<?php if($user->isComplimentary()) { ?>
				<div style="margin-bottom:5px;">
					<img src="<?php echo MM_Utils::getImageUrl("award_star_gold") ?>" title="Membership is complimentary" style="vertical-align:middle;" />
					This account is complimentary
				</div>
				<?php } ?>
			</td>
		</tr>
		
		<?php if(($user->getStatus() == MM_Status::$ERROR || $user->getStatus() == MM_Status::$PENDING) && $user->getStatusMessage() != "") { ?>
		<tr>
			<td></td>
			<td><div style="width:500px; font-size:11px; color:#555;"><em><?php echo $user->getStatusMessage(); ?></em></div></td>
		</tr>
		<?php } ?>
		
		<tr><td colspan="2"><input type="hidden" id="mm-id" value="<?php echo $user->getId(); ?>" /></td></tr>
		
		<tr>
			<td>Account Stats</td>
			<td>
				<div style="margin-bottom:5px;">
					<img src="<?php echo MM_Utils::getImageUrl('vcard'); ?>" style="vertical-align:middle;" /> Member ID is <span style='font-family:courier'><?php echo $user->getId(); ?></span>
				</div>
				<div style="margin-bottom:5px;">
					<img src="<?php echo MM_Utils::getImageUrl('date'); ?>" style="vertical-align:middle;" /> Account created on <?php echo $user->getRegistrationDate(true); ?>
				</div>
				
				<?php 
				if($user->isImported())
				{
				?>
				<div style="margin-bottom:5px;">
					<img src="<?php echo MM_Utils::getImageUrl('user_go'); ?>" style="vertical-align:middle;" /> Account imported on <?php echo $user->getStatusUpdatedDate(true); ?>
				</div>
				<?php		
				}
				?>
				
				<?php
				$welcomeEmailSent = $user->getWelcomeEmailSentDate();
				
				if(!empty($welcomeEmailSent))
				{
					echo "<div style=\"margin-bottom:5px;\">";
					echo "<img src=\"".MM_Utils::getImageUrl('email_check')."\" style=\"vertical-align:middle;\" /> Welcome email sent on ".$user->getWelcomeEmailSentDate(true);
					echo "</div>";
				}
				?>
			</td>
		</tr>
		
		<tr><td colspan="2"></td></tr>
		
		<tr>
			<td>Engagement Stats</td>
			<td>
				<?php
				$loginCount = $user->getLoginCount();
				
				if($loginCount > 0)
				{
				?>
					<div style="margin-bottom:5px;">
						<img src="<?php echo MM_Utils::getImageUrl('date'); ?>" style="vertical-align:middle;" /> Last Login Date: <?php echo $user->getLastLoginDate(true); ?>
					</div>
					<div style="margin-bottom:5px;">
						<img src="<?php echo MM_Utils::getImageUrl('world'); ?>" style="vertical-align:middle;" /> Last Login IP: <span style='font-family:courier; font-size:12px;'><a href="http://www.infosniper.net/index.php?ip_address=<?php echo $user->getLastLoginIpAddress() ?>&map_source=1&two_maps=1&overview_map=1" target="_blank"><?php echo $user->getLastLoginIpAddress() ?></a></span>
					</div>
					<div style="margin-bottom:5px;">
						<img src="<?php echo MM_Utils::getImageUrl('key'); ?>" style="vertical-align:middle;" /> Logins: 
						<span style='font-family:courier; font-size:14px;'><?php echo $user->getLoginCount(); ?></span>
						<a href="?page=<?php echo MM_MODULE_REPORTS."&module=".MM_MODULE_EVENT_LOG."&event_type=".MM_EventLog::$EVENT_TYPE_LOGIN."&member_id=".$user->getId(); ?>" style="font-size:11px;">view details</a>
					</div>
					<div style="margin-bottom:5px;">
						<img src="<?php echo MM_Utils::getImageUrl('page_green'); ?>" style="vertical-align:middle;" /> Pages Accessed: 
						<span style='font-family:courier; font-size:14px;'><?php echo $user->getPageAccessCount(); ?></span>
						<a href="?page=<?php echo MM_MODULE_REPORTS."&module=".MM_MODULE_EVENT_LOG."&event_type=".MM_EventLog::$EVENT_TYPE_PAGE_ACCESS."&member_id=".$user->getId()."&sortby=url"; ?>" style="font-size:11px;">view details</a>
					</div>
				<?php 
				}
				else
				{
					echo "<em>Member hasn't logged in yet</em>";	
				}
				?>
			</td>
		</tr>
		
		<tr><td colspan="2"></td></tr>
		
		<tr>
			<td>Tools</td>
			<td>
				<div style="margin-bottom:5px;">
					<img src="<?php echo MM_Utils::getImageUrl('user_key'); ?>" style='vertical-align:middle;' /> 
					<a style='cursor: pointer;' onclick="mmjs.loginAsMember('<?php echo $user->getId(); ?>');">Login as this member</a>
				</div>
				<div style="margin-bottom:5px;">
					<img src="<?php echo MM_Utils::getImageUrl('email_go'); ?>" style='vertical-align:middle;' /> 
					<a style='cursor: pointer;' onclick="mmjs.sendPasswordEmail('<?php echo $user->getId(); ?>');">Email current password to member</a>
				</div>
				<div>
					<img src="<?php echo MM_Utils::getImageUrl('email_go'); ?>" style='vertical-align:middle;' /> 
					<a style='cursor: pointer;' onclick="mmjs.sendWelcomeEmail('<?php echo $user->getId(); ?>');">Resend welcome email to member</a>
				</div>
			</td>
		</tr>
		
		<tr><td colspan="2"><div style="width: 600px; margin-top: 8px; margin-bottom: 8px;" class="mm-divider"></div></td></tr>
		
		<tr>
			<td>First Name</td>
			<td><input id="mm-first-name" type="text" style="width:200px;" value="<?php echo $user->getFirstName() ?>"></td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td><input id="mm-last-name" type="text" style="width:200px;" value="<?php echo $user->getLastName() ?>"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input id="mm-email" type="text" style="width:200px;" value="<?php echo $user->getEmail() ?>"></td>
		</tr>
		<tr>
			<td>Username</td>
			<td><input id="mm-username" type="text" style="width:200px;" value="<?php echo $user->getUsername() ?>"></td>
		</tr>
		<tr>
			<td>Phone</td>
			<td><input id="mm-phone" type="text" style="width:200px;" value="<?php echo $user->getPhone() ?>"></td>
		</tr>
		<tr>
			<td>Notes</td>
			<td>
				<textarea id="mm-notes" rows="6" style="font-family:courier; width:450px;"><?php echo $user->getNotes() ?></textarea>
			</td>
		</tr>
	</table>
	
	<?php if($user->doesExpire()) { ?>
	<table cellspacing="8">
		<tr><td colspan="2"><div style="width: 600px; margin-top: 8px; margin-bottom: 8px;" class="mm-divider"></div></td></tr>
			
		<tr>
			<td width="<?php echo $columnWidth; ?>px;">Membership Expires</td>
			<td>
				<img src="<?php echo MM_Utils::getImageUrl("calendar") ?>" style="vertical-align: middle" />
				<input id="mm-expiration-date" type="text" style="width: 152px" value="<?php echo $expirationDate; ?>" /> 
			</td>
		</tr>
	</table>
	<?php } ?>
	
	<div style="width: 600px; margin-top: 8px; margin-bottom: 8px;" class="mm-divider"></div> 
	
	<table cellspacing="8">
		<?php
			$calcMethodDesc = "This determines how MemberMouse will calculate the number of days someone has been a member. This is used primarily in determining where a member is in a drip content schedule and therefore what content they get access to. By default, the calculation is done based on a member's registration date, but you can choose to have the calculation done based on a custom date or fix the number of days to a specific number.";
		?>
		<tr>
			<td width="<?php echo $columnWidth; ?>px;">'Days as Member' Calculation Method <img src="<?php echo MM_Utils::getImageUrl("information"); ?>" style="vertical-align:middle;" title="<?php echo $calcMethodDesc; ?>" /></td>
			<td>
				<?php if(!$canChangeDaysCalc) { ?>
				<div style="margin-bottom:5px; width:480px;">
					<img src='<?php echo MM_Utils::getImageUrl("exclamation"); ?>' style='vertical-align: middle; '/> You can modify the number of days this member is fixed at, 
					but to change the calculation method you must change the member's status to Active.
				</div>
				<?php } ?> 
				<div style="margin-bottom:5px;">
					<input type='radio' <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> onchange="mmjs.setCalcMethod('<?php echo MM_DaysCalculationTypes::$JOIN_DATE; ?>');" id='mm-calc-method-reg-date' <?php echo $joinDateSelected; ?> name='mm-calc-method' /> By join date
				</div>
				<div style="margin-bottom:5px;">
					<input type='radio' <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> onchange="mmjs.setCalcMethod('<?php echo MM_DaysCalculationTypes::$CUSTOM_DATE; ?>');" id='mm-calc-method-custom-date'  <?php echo $customDateSelected; ?> name='mm-calc-method' /> By custom date 
				
						<img src="<?php echo MM_Utils::getImageUrl("calendar") ?>" style="vertical-align: middle" />
						<input <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> id="mm-custom-date" type="text" style="width: 152px" value="<?php echo $customDateValue; ?>" /> 
				</div>
				<div style="margin-bottom:5px;">
					<input type='radio' onchange="mmjs.setCalcMethod('<?php echo MM_DaysCalculationTypes::$FIXED; ?>');" id='mm-calc-method-fixed'  <?php echo $fixedSelected; ?>  name='mm-calc-method' /> Fixed at <input id="mm-fixed" type="text" value="<?php echo $fixedValue; ?>"  style="width: 52px" /> days <br />
				</div>
				 	
				<input type='hidden' id='mm-calc-method' value="<?php echo $calcMethod; ?>" /> 
			</td>
		</tr>
	</table>
	
	<div style="width: 600px; margin-top: 8px; margin-bottom: 8px;" class="mm-divider"></div> 
	
	<table>
		<tr>
			<td width="<?php echo $columnWidth; ?>px;">Change Password</td>
			<td>
				<div style="margin-bottom:5px;">
					<span style="margin-right:18px;">New Password</span> <input id="mm-new-password" type="password">
				</div>
				<div style="margin-bottom:5px;">
					Confirm Password <input id="mm-confirm-password" type="password">
				</div>
			</td>
		</tr>
	</table>
	
	<div style='clear: both; height:10px;'></div>
	
	<div style="width:600px">
		<input type="button" class="mm-button blue small" value="Update Member" onclick="mmjs.updateMember(<?php echo $user->getId(); ?>);">
		
		<?php if(($user->getStatus() == MM_Status::$ERROR) || ($user->getStatus() == MM_Status::$PENDING) || !$user->hasActiveSubscriptions()) { ?>
		<span style="float:right;">
			<input type="button" class="mm-button red small" value="Delete Member" onclick="mmjs.deleteMember(<?php echo $user->getId(); ?>, '<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_BROWSE_MEMBERS); ?>');">
		</span>
		<?php } ?>
	</div>
</div>

<div style='clear: both; height:20px;'></div>

<script type='text/javascript'>
jQuery(document).ready(function()
{
	jQuery("#mm-custom-date").datepicker();		
	jQuery("#mm-expiration-date").datepicker();	
});
</script>
<?php 
}
else 
{
	echo "<div style=\"margin-top:10px;\"><em>Invalid Member ID</em></div>";
}
}
?>