<?php 
get_header(); 

$date_format=get_option('date_format'); ?>
<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="content">
    <div class="container">

	<?php the_post(); ?>
	<article class="single-post">
		<?php if(has_post_thumbnail() && ThemexCore::getOption('blog_image')!='true') { ?>
		<div class="post-image">
			<div class="bordered-image thick-border">
				<?php the_post_thumbnail('extended'); ?>
			</div>
		</div>
		<?php } ?>
		<div class="post-content">
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</div>
	</article>

<?php get_footer(); ?>