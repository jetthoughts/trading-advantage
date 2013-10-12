<?php get_header(); ?>
<div class="column eightcol">
	<?php the_post(); ?>
	<?php get_template_part('loop', 'testimonial'); ?>
</div>
<aside class="sidebar column fourcol last">
<?php get_sidebar(); ?>
</aside>
<?php get_footer(); ?>