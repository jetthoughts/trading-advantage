<?php if(ThemexCourse::$data['related']!='true' && ($courses=ThemexCourse::getRelatedCourses($post->ID))) { ?>
<h1><?php _e('Related Courses', 'academy'); ?></h1>
<div class="row"><div class="courses-listing clearfix">
	<?php
	$counter=0;
	foreach($courses as $post) {
	$counter++;
	?>
	<div class="col-md-3">
		<?php get_template_part('loop', 'course'); ?>
	</div>
	<?php }	wp_reset_query(); ?>
</div></div>
<?php } ?>