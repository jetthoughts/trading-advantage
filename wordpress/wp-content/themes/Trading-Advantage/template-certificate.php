<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php _e('Certificate', 'academy'); ?> | <?php bloginfo('name'); ?></title>
<?php wp_head(); ?>
</head>
<body <?php body_class('single-certificate'); ?>>
<?php $certificate=ThemexCourse::getCertificate(); ?>
<?php if(isset($certificate['completed']) && (!empty($certificate['image']) || !empty($certificate['text']))) { ?>
	<div class="certificate-wrap <?php if(!empty($certificate['image'])) { ?>certificate-image<?php } ?>">
		<?php echo $certificate['image']; ?>
		<div class="certificate-text">
		<?php echo $certificate['text']; ?>		
		</div>
	</div>
	<?php if($certificate['user']->ID==get_current_user_id()) { ?>
	<a href="#" class="button print-button"><span><?php _e('Print Certificate', 'academy'); ?></span></a>
	<?php } ?>
<?php } else { ?>
<div class="certificate-error">
	<h1><?php _e('Certificate not found', 'academy'); ?>.</h1>
</div>
<?php } ?>	
<?php wp_footer(); ?>
</body>
</html>