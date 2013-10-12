<div class="lesson-item <?php if($GLOBALS['lesson']->post_parent!=0) { ?>lesson-child<?php } ?> <?php if(ThemexCourse::isMember() && ThemexCourse::isCompletedLesson($GLOBALS['lesson']->ID)) { ?>completed<?php } ?>">
	<div class="lesson-title">
		<?php if($GLOBALS['lesson']->_lesson_status=='free') { ?>
		<div class="course-status"><?php _e('Free', 'academy'); ?></div>
		<?php } ?>
		<h4 class="nomargin"><a href="<?php echo get_permalink($GLOBALS['lesson']->ID); ?>" class="<?php if($GLOBALS['lesson']->_lesson_status=='free') { ?>no-popup<?php } ?>"><?php echo get_the_title($GLOBALS['lesson']->ID); ?></a></h4>
		<?php if(ThemexCourse::isMember() && $GLOBALS['lesson']->_lesson_quiz && ThemexCourse::getLessonMark($GLOBALS['lesson']->ID)) { ?>
		<?php ThemexCourse::$data['course']['progress']=ThemexCourse::getLessonMark($GLOBALS['lesson']->ID); ?>
		<?php get_template_part('module', 'progress'); ?>
		<?php } ?>		
	</div>
	<?php if($attachments=ThemexCore::parseMeta($GLOBALS['lesson']->ID, 'lesson', 'attachments')) { ?>
	<div class="lesson-attachments">
		<?php
		foreach($attachments as $attachment) {			
			if($attachment['url']!='') {
		?>
		<a href="
		<?php 
		if((ThemexCourse::isMember() && ThemexCourse::isSubscriber()) || ThemexCourse::isAuthor() || $GLOBALS['lesson']->_lesson_status=='free') {
			echo $attachment['url']; 
		} else {
			echo '#';
		}
		?>
		" target="_blank" title="<?php echo $attachment['title']; ?>" class="<?php echo $attachment['type']; ?> <?php if($GLOBALS['lesson']->_lesson_status=='free') { ?>no-popup<?php } ?>"></a>
		<?php 
			}
		}
		?>
	</div>
	<?php } else { ?>
	<div class="lesson-title"></div>
	<?php } ?>
</div>