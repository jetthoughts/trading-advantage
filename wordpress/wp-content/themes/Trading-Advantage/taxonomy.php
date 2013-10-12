<?php get_header(); ?>
<?php if(ThemexCourse::$data['view']=='list') { ?>
<?php if(ThemexCourse::$data['layout']=='left') { ?>
<aside class="sidebar fourcol column">
<?php get_sidebar(); ?>
</aside>
<div class="eightcol column last">
<?php } else { ?>
<div class="eightcol column">
<?php } ?>
	<?php echo category_description(); ?>
	<div class="posts-listing">
		<?php 
		while ( have_posts() ) {
		the_post();
		?>
		<article <?php post_class('post clearfix'); ?>>
			<div class="column fivecol post-image">
				<?php get_template_part('loop', 'course'); ?>
			</div>
			<div class="course-description column sevencol last">
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<?php the_excerpt(); ?>
				<footer class="post-footer">
				<?php get_template_part('module', 'form'); ?>
				</footer>
			</div>
		</article>
		<?php } ?>
	</div>
	<?php ThemexInterface::renderPagination(); ?>
</div>
<?php if(ThemexCourse::$data['layout']!='left') { ?>
<aside class="sidebar fourcol column last">
<?php get_sidebar(); ?>
</aside>
<?php } ?>
<?php } else { ?>
<?php echo category_description(); ?>
<div class="courses-listing clearfix">
	<?php 
	$counter=0;	
	while ( have_posts() ) {
	the_post();
	$counter++;
	?>
	<div class="column <?php echo ThemexCourse::$data['layout']; ?>col <?php echo $counter==ThemexCourse::$data['columns']?'last':''; ?>">
	<?php get_template_part('loop','course'); ?>
	</div>
		<?php if($counter==ThemexCourse::$data['columns']) { ?>
		<div class="clear"></div>
		<?php 
		$counter=0;
		}
	}
	?>
</div>
<?php ThemexInterface::renderPagination(); ?>
<?php } ?>
<?php get_footer(); ?>