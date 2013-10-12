<form action="<?php echo ThemexCourse::getAction(get_permalink()); ?>" method="POST">
	<input type="hidden" name="course_id" value="<?php the_ID(); ?>" />
	<?php if(!ThemexCourse::isSubscriber()) { ?>
		<?php if(ThemexCourse::$data['course']['status']!='private') { ?>
		<input type="hidden" name="course_action" value="subscribe" />
		<input type="hidden" name="plan_id" value="<?php echo ThemexCourse::$data['course']['plans'][0]; ?>" />
		<a href="#" class="button medium price-button submit-button left"><span class="caption"><?php _e('Subscribe Now', 'academy'); ?></span></a>
		<?php } ?>
	<?php } else if(!ThemexCourse::isMember()) { ?>
		<?php if(ThemexCourse::$data['course']['status']!='private') { ?>
		<input type="hidden" name="course_action" value="add" />
		<a href="#" class="button medium price-button submit-button left">
			<span class="caption"><?php _e('Take This Course', 'academy'); ?></span>
			<?php if(ThemexCourse::$data['course']['status']=='premium') { ?>
			<span class="price"><?php echo ThemexCourse::$data['course']['price']; ?></span>
			<?php } ?>
		</a>
		<?php } ?>
	<?php } else if(ThemexCourse::$data['unsubscribe']!='true') { ?>
	<input type="hidden" name="course_action" value="remove" />					
	<a href="#" class="button secondary medium submit-button left"><span><?php _e('Unsubscribe Now', 'academy'); ?></span></a>
	<?php } ?>
</form>
<?php if(ThemexCourse::hasCertificate()) { ?>
<a href="<?php echo ThemexCourse::getCertificateURL(); ?>" target="_blank" class="button medium certificate-button"><span><?php _e('View Certificate', 'academy'); ?></span></a>
<?php } ?>