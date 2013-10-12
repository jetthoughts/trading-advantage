<?php ThemexUser::$data['current_user']=$GLOBALS['user']; ?>
<div class="expert-preview">
	<div class="expert-meta">
		<div class="expert-image bordered-image">
			<a href="<?php echo get_author_posts_url(ThemexUser::$data['current_user']->ID); ?>"><?php echo get_avatar(ThemexUser::$data['current_user']->ID); ?></a>
		</div>
		<?php get_template_part('module', 'links'); ?>
	</div>							
	<div class="expert-text">
		<h4 class="nomargin">
			<a href="<?php echo get_author_posts_url(ThemexUser::$data['current_user']->ID); ?>">
			<?php echo ThemexUser::getFullName(ThemexUser::$data['current_user']); ?>
			</a>
		</h4>
		<span class="expert-signature"><?php echo ThemexUser::$data['current_user']->signature; ?></span>
		<?php echo ThemexUser::getDescription(ThemexUser::$data['current_user']->description); ?>
	</div>
</div>