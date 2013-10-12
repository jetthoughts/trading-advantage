<?php if(ThemexCourse::$data['related']!='true' && ($courses=ThemexCourse::getRelatedCourses($post->ID))) { ?>
<h1><?php _e('Related Courses', 'academy'); ?></h1>
<div class="courses-listing clearfix">
	<?php 
	$counter=0;
	foreach($courses as $post) { 
	$counter++;
	?>
	<div class="threecol column <?php if($counter==4) { ?>last<?php } ?>">
		<?php get_template_part('loop', 'course'); ?>
	</div>
	<?php }	wp_reset_query(); ?>	
</div>
<?php } ?>