<?php ThemexCourse::initCourse($post->ID); ?>
<?php if(has_post_thumbnail()) { ?>
<div class="course-preview <?php echo ThemexCourse::$data['course']['status']; ?>-course">
	<div class="course-image">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('normal'); ?></a>
		<?php if(!empty(ThemexCourse::$data['course']['price']) && empty(ThemexCourse::$data['course']['plans']) && ThemexCourse::$data['course']['status']!='private') { ?>
		<div class="course-price">
			<div class="corner-wrap">
				<div class="corner"></div>
				<div class="corner-background"></div>
			</div>
			<div class="price-text"><?php echo ThemexCourse::$data['course']['price']; ?></div>
		</div>
		<?php } ?>
	</div>
	<div class="course-meta">
		<header class="course-header">
			<h5 class="nomargin"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
			<a href="<?php echo ThemexCourse::$data['course']['author']->user_link; ?>" class="author"><?php echo ThemexCourse::$data['course']['author']->full_name; ?></a>
		</header>
		<?php if(ThemexCourse::$data['rating']!='true' || ThemexCourse::$data['users_number']!='true') { ?>
		<footer class="course-footer clearfix">
			<?php if(ThemexCourse::$data['users_number']!='true') { ?>		
			<div class="course-users left"><?php echo count(ThemexCourse::$data['course']['users']); ?></div>
			<?php } ?>
			<?php if(ThemexCourse::$data['rating']!='true') { ?>
			<?php get_template_part('module', 'rating'); ?>
			<?php } ?>
		</footer>
		<?php } ?>
	</div>
</div>
<?php } ?>