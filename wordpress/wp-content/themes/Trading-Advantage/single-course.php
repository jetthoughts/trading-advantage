<?php get_header(); ?>
<div class="course-content clearfix <?php if((!ThemexCourse::isMember() || !ThemexCourse::isSubscriber()) && !ThemexCourse::isAuthor()) { ?>popup-container<?php } ?>">	
	<div class="sevencol column">
		<?php if(!empty(ThemexCourse::$data['course']['lessons'])) { ?>
		<h1><?php _e('Lessons', 'academy'); ?></h1>
		<?php if(ThemexCourse::isMember()) { ?>
		<?php get_template_part('module', 'progress'); ?>
		<?php } ?>
		<div class="lessons-listing">
			<?php 
			foreach(ThemexCourse::sortLessons(ThemexCourse::$data['course']['lessons']) as $lesson) {
				get_template_part('loop', 'lesson');
			}
			?>
		</div>
		<?php } ?>
	</div>
	<div class="course-questions fivecol column last">
		<?php if($questions=ThemexCourse::getQuestions()) { ?>
		<h1><?php _e('Questions', 'academy'); ?></h1>
		<ul class="styled-list style-2 bordered">
			<?php foreach($questions as $question) {?>
			<li><a href="<?php echo get_comment_link($question->comment_ID); ?>"><?php echo get_comment_meta($question->comment_ID, 'title', true); ?></a></li>
			<?php } ?>
		</ul>
		<?php } ?>
	</div>
</div>
<!-- /course content -->
<div class="popup hidden">
	<h2 class="popup-text">
	<?php if(ThemexCourse::isSubscriber()) { ?>
	<?php _e('Take the course to view this content', 'academy').'.'; ?>.
	<?php } else { ?>
	<?php _e('Subscribe to view this content', 'academy').'.'; ?>.
	<?php } ?>
	</h2>
</div>
<!-- /popup -->
<?php get_template_part('module', 'related'); ?>
<?php get_footer(); ?>