<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="content">
    <div class="container">
<?php the_post(); ?>
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php comments_template('/questions.php'); ?>
	<?php get_template_part('sidebar', 'lesson'); ?>
<?php get_footer(); ?>