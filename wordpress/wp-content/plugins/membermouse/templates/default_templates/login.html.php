<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<link rel='stylesheet' type='text/css' href='<?php echo $p->resourceDirectory; ?>css/user/mm-login.css' />

[MM_Form type='login']
<div class="mm-login">
[MM_Form_Message type='error']

<h3>Enter your username and password below</h3>

<table>
    <tr>
      	<td class="mm-label-column">
      		<span class='mm-label'>Username</span>
      	</td>
      	<td class="mm-field-column">
      		[MM_Form_Field name='username']
      	</td>
    </tr>
    <tr>
      	<td class="mm-label-column">
      		<span class='mm-label'>Password</span>
      	</td>
      	<td class="mm-field-column">
      		[MM_Form_Field name='password']
      	</td>
    </tr>
    <tr>
      	<td class="mm-label-column"></td>
      	<td class="mm-field-column">
      		[MM_Form_Button type='login' label='Login' color='blue']
      		[MM_Form_Field name='rememberMe' label='Remember me']
      	</td>
    </tr>
    <tr>
      	<td class="mm-label-column"></td>
      	<td class="mm-field-column">
      		<a href="[MM_CorePage_Link type='forgotPassword']" class="mm-forgot-password">Forgot Password</a>
      	</td>
    </tr>
</table>
</div>
[/MM_Form]