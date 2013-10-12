<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_EventLogView();

$eventsList = array_merge(array(""=>"All"), MM_EventLog::getEventsList());

$eventTypeValue = "";
if(!empty($_REQUEST["event_type"]))
{
	$eventTypeValue = $_REQUEST["event_type"];
}

$eventOptions = MM_HtmlUtils::generateSelectionsList($eventsList, $eventTypeValue);

$memberIdValue = "";
if(!empty($_REQUEST["member_id"]))
{
	$memberIdValue = $_REQUEST["member_id"];
}

$fromDateValue = "";
if(!empty($_REQUEST["from_date"]))
{
	$fromDateValue = $_REQUEST["from_date"];
}

$toDateValue = "";
if(!empty($_REQUEST["to_date"]))
{
	$toDateValue = $_REQUEST["to_date"];
}
?>
<script type='text/javascript'>
jQuery(document).ready(function()
{
	jQuery("#from_date").datepicker();
	jQuery("#to_date").datepicker();
});
</script>

<div class="mm-wrap">

<form method="post">
<div id="mm-form-container">
	<table style="width:600px;">
		<tr>
			<!-- LEFT COLUMN -->
			<td valign="top">
			<table cellspacing="5">
				<tr>
					<td>Event Type</td>
					<td>
						<select id='event_type' name='event_type'><?php echo $eventOptions; ?></select>
					</td>
				</tr>
				<tr>
					<td>Member ID</td>
					<td><input id="member_id" name="member_id" type="text" value="<?php echo $memberIdValue; ?>" /></td>
				</tr>
			</table>
			</td>
			
			<!-- RIGHT COLUMN -->
			<td valign="top">
			<table cellspacing="5">
				<tr>
					<td>From</td>
					<td>
						<img src="<?php echo MM_Utils::getImageUrl("calendar") ?>" style="vertical-align: middle" />
						<input id="from_date" name="from_date" type="text" value="<?php echo $fromDateValue; ?>" style="width: 152px" /> 
					</td>
				</tr>
				<tr>
					<td>To</td>
					<td>
						<img src="<?php echo MM_Utils::getImageUrl("calendar") ?>" style="vertical-align: middle" />
						<input id="to_date" name="to_date" type="text" value="<?php echo $toDateValue; ?>" style="width: 152px"  />
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	
	<input type="button" class="mm-button blue small" value="Show Events" onclick="mmjs.search();">
	<input type="button" class="mm-button small" value="Reset Form" onclick="mmjs.resetForm();">
</div>
</form>

<div style="width: 100%; margin-top: 10px; margin-bottom: 10px;" class="mm-divider"></div> 
	
<div id="mm-grid-container">
	<?php echo $view->generateDataGrid($_REQUEST); ?>
</div>				

</div>