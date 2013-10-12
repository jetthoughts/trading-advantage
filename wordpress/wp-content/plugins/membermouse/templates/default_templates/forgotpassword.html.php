<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<link rel='stylesheet' type='text/css' href='<?php echo $p->resourceDirectory; ?>css/user/mm-forgotpassword.css' />

[MM_Form type='forgotPassword']
<div class="mm-forgot-password">
[MM_Form_Message type='error']
[MM_Form_Message type='success']

<h3>Enter your email address below</h3>
[MM_Form_Field name='email']
[MM_Form_Button type='submit' label='Submit']
</div>
[/MM_Form]