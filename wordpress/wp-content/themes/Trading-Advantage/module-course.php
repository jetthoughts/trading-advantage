<?php the_post(); ?>
<?php ThemexCourse::initCourse($post->ID); ?>
<div class="threecol column">
<?php get_template_part('loop','course'); ?>
</div>
<?php if(ThemexCourse::hasMembers()) { ?>
<div class="sixcol column">
<?php } else { ?>
<div class="ninecol column last">
<?php } ?>
	<div class="course-description widget <?php echo ThemexCourse::$data['course']['status']; ?>-course">
		<div class="widget-title"><h4 class="nomargin"><?php _e('Description', 'academy'); ?></h4></div>
		<div class="widget-content">
			<?php the_content(); ?>			
			<footer class="course-footer">
			<?php get_template_part('module', 'form'); ?>
			</footer>			
		</div>						
	</div>
</div>
<div class="threecol column last">
<?php get_template_part('module', 'users'); ?>
</div>