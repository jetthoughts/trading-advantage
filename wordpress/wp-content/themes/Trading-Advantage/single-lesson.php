<?php get_header(); ?>
<?php the_post(); ?>
<div class="eightcol column">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php comments_template('/questions.php'); ?>
</div>
<aside class="sidebar fourcol column last">
	<?php get_template_part('sidebar', 'lesson'); ?>
</aside>
<?php get_footer(); ?>