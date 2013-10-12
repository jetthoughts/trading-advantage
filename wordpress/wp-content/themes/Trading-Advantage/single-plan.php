<?php get_header(); ?>
<div class="column eightcol">
	<?php the_post(); ?>
	<article class="single-post">
		<div class="post-content">
			<?php the_content(); ?>
			<?php if(ThemexWoo::isActive()) { ?>
			<form action="<?php echo ThemexCourse::getAction(get_permalink()); ?>" method="POST">
				<input type="hidden" name="course_action" value="subscribe" />
				<input type="hidden" name="course_id" value="0" />
				<input type="hidden" name="plan_id" value="<?php the_ID(); ?>" />				
				<a href="#" class="button medium submit-button left"><span><?php _e('Subscribe Now', 'academy'); ?></span></a>
			</form>
			<?php } ?>
		</div>
	</article>
</div>
<aside class="sidebar column fourcol last">
<?php get_sidebar(); ?>
</aside>
<?php get_footer(); ?>