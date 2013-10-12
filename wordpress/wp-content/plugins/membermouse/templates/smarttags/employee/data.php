<h2>[MM_Employee_Data]</h2>

<a onclick="stl_js.insertContent('[MM_Employee_Data name=\'\']');" class="mm-button blue">Insert SmartTag</a>
<?php $docUrl = "http://support.membermouse.com/customer/portal/articles/1046714-mm_employee_data-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-button">View Documentation</a>

<p>This tag outputs data associated with the employee account associated with the ID passed. If not ID is passed either the default account is used or,
if the tag is used in the context of a welcome email or other email template, the employee account used to send that message is used.</p>