<?php if(have_comments() || comments_open()) { ?>
<div class="post-comments clearfix" id="comments">
	<h1><?php _e('Comments', 'academy'); ?></h1>
	<?php if(have_comments()) { ?>
	<div class="comments-listing" id="comments">
		<ul>
		<?php
		wp_list_comments(array(
			'avatar_size'=>75,
			'callback'=>array('ThemexInterface','renderComment'),
		));
		?>
		</ul>
	</div>
	<div class="pagination">
	<?php paginate_comments_links(array('prev_text' => '', 'next_text' => '')); ?>
	</div>
	<?php } ?>
	<?php if(comments_open()) { ?>
	<div class="comment-form eightcol column last">
		<?php comment_form(); ?>
	</div>
	<?php } ?>
</div>
<?php } ?>