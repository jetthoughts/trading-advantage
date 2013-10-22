<?php get_header(); ?>
<?php the_post(); ?>
<?php get_template_part('sidebar', 'lesson'); ?>
<div id="content">
    <div class="container">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php comments_template('/questions.php'); ?>
<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>