<?php if(have_comments() || comments_open()) { ?>
<div class="questions clearfix" id="comments">
	<h1><?php _e('Questions', 'academy'); ?></h1>
	<?php if(have_comments()) { ?>		
	<div class="questions-listing toggles-wrap">
		<ul>
			<?php
			wp_list_comments(array(
				'per_page'=>-1,
				'avatar_size'=>75,
				'callback'=>array('ThemexCourse','renderQuestion'),
			)); 
			?>
		</ul>
	</div>
	<?php } ?>
	<?php if(comments_open() && ThemexUser::userActive() && (ThemexCourse::isMember() || ThemexCourse::isAuthor())) { ?>
	<div class="question-form eightcol column last">
		<?php comment_form(); ?>
	</div>
	<?php } ?>
</div>
<?php } ?>