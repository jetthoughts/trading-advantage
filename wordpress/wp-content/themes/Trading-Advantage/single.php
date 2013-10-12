<?php 
get_header(); 

$layout=ThemexCore::getOption('blog_layout','right');
$date_format=get_option('date_format');

if($layout=='left') {
?>
<aside class="sidebar column fourcol">
<?php get_sidebar(); ?>
</aside>
<div class="column eightcol last">
<?php } else if($layout=='right') { ?>
<div class="column eightcol">
<?php } else { ?>
<div class="fullwidth-block">
<?php } ?>
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
			<footer class="post-footer">
				<div class="sixcol column">
					<?php if(comments_open()) { ?>
					<div class="post-comment-count"><?php comments_number('0','1','%'); ?></div>
					<?php } ?>
					<time class="post-date nomargin" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time($date_format); ?></time>
					<?php if(ThemexCore::getOption('blog_author')!='true') { ?>
					<div class="post-author nomargin">&nbsp;<?php _e('by', 'academy'); ?> <?php the_author_posts_link(); ?></div>
					<?php } ?>
					<div class="post-categories">&nbsp;<?php _e('in', 'academy'); ?> <?php the_category(', '); ?></div>
				</div>
				<div class="sixcol column last">
					<div class="tagcloud"><?php the_tags('','',''); ?></div>
				</div>				
			</footer>
		</div>		
	</article>
	<?php comments_template(); ?>
</div>
<?php if($layout=='right') { ?>
<aside class="sidebar column fourcol last">
<?php get_sidebar(); ?>
</aside>
<?php } ?>
<?php get_footer(); ?>