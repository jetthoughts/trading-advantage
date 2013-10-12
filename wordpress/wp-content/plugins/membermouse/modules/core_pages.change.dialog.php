<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<div id='mm_change_core_page_container'>
<table cellpadding="5px">
	<tr>
		<td>Choose a page to use in place of the current page:</td>
	</tr>
	<?php if(!empty($p->options)){ ?>
	<tr>
		<td>
			<select id='new_page_id'>
			<?php echo $p->options; ?>
			</select>
		</td>
	</tr>
	<?php } else { ?>
	<tr><td><img src='<?php echo MM_Utils::getImageUrl('error'); ?>' /> You do not have any available pages.  <a href='post-new.php?post_type=page' target='_top'>Click here</a> to add a page.</td></tr>
	<?php } ?>
</table>
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:corepages_js.updateCorePage();" class="mm-button blue">Change Core Page</a>
<a href="javascript:corepages_js.closeDialog();" class="mm-button">Cancel</a>
</div>
</div>