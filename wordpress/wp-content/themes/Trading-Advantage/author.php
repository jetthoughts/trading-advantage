<?php get_header(); ?>
<div class="user-profile sevencol column">
	<?php if(ThemexUser::isProfilePage()) { ?>
	<form action="<?php echo ThemexUser::$data['profile_page_url']; ?>" class="formatted-form user-profile-form" enctype="multipart/form-data" method="POST">
		<div class="user-image">
			<div class="bordered-image thick-border">
				<?php echo get_avatar( ThemexUser::$data['user']->ID ); ?>
			</div>
			<div class="user-image-uploader">
				<a href="#" class="button"><span><span class="button-icon upload"></span><?php _e('Upload','academy'); ?></span></a>
				<input type="file" class="shifted" name="avatar" />
			</div>
		</div>
		<div class="user-description">
				<?php if(!empty(ThemexUser::$data['messages'])) { ?>
				<div class="message"><?php ThemexUser::renderMessages(); ?></div>
				<?php } ?>
				<div class="sixcol column">
					<div class="field-wrapper">
						<input type="text" name="user_first_name" value="<?php echo ThemexUser::$data['user']->first_name; ?>" placeholder="<?php _e('First Name','academy'); ?>" />
					</div>								
				</div>
				<div class="sixcol column last">
					<div class="field-wrapper">
						<input type="text" name="user_last_name" value="<?php echo ThemexUser::$data['user']->last_name; ?>" placeholder="<?php _e('Last Name','academy'); ?>" />
					</div>
				</div>				
				<div class="clear"></div>
				<div class="field-wrapper">
					<input type="text" name="user_signature" value="<?php echo ThemexUser::$data['user']->signature; ?>" placeholder="<?php _e('Signature','academy'); ?>" />
				</div>				
				<?php ThemexInterface::renderEditor('user_description', wpautop(ThemexUser::$data['user']->description)); ?>
				<div class="sixcol column">
					<div class="field-wrapper">
						<input type="text" name="user_facebook" value="<?php echo ThemexUser::$data['user']->facebook; ?>" placeholder="<?php _e('Facebook','academy'); ?>" />
					</div>								
				</div>
				<div class="sixcol column last">
					<div class="field-wrapper">
						<input type="text" name="user_twitter" value="<?php echo ThemexUser::$data['user']->twitter; ?>" placeholder="<?php _e('Twitter','academy'); ?>" />
					</div>
				</div>				
				<div class="clear"></div>
				<div class="sixcol column">
					<div class="field-wrapper">
						<input type="text" name="user_google" value="<?php echo ThemexUser::$data['user']->google; ?>" placeholder="<?php _e('Google','academy'); ?>+" />
					</div>								
				</div>
				<div class="sixcol column last">
					<div class="field-wrapper">
						<input type="text" name="user_tumblr" value="<?php echo ThemexUser::$data['user']->tumblr; ?>" placeholder="<?php _e('Tumblr','academy'); ?>" />
					</div>
				</div>				
				<div class="clear"></div>
				<div class="sixcol column">
					<div class="field-wrapper">
						<input type="text" name="user_linkedin" value="<?php echo ThemexUser::$data['user']->linkedin; ?>" placeholder="<?php _e('LinkedIn','academy'); ?>" />
					</div>								
				</div>
				<div class="sixcol column last">
					<div class="field-wrapper">
						<input type="text" name="user_flickr" value="<?php echo ThemexUser::$data['user']->flickr; ?>" placeholder="<?php _e('Flickr','academy'); ?>" />
					</div>
				</div>				
				<div class="clear"></div>
				<div class="sixcol column">
					<div class="field-wrapper">
						<input type="text" name="user_youtube" value="<?php echo ThemexUser::$data['user']->youtube; ?>" placeholder="<?php _e('YouTube','academy'); ?>" />
					</div>								
				</div>
				<div class="sixcol column last">
					<div class="field-wrapper">
						<input type="text" name="user_vimeo" value="<?php echo ThemexUser::$data['user']->vimeo; ?>" placeholder="<?php _e('Vimeo','academy'); ?>" />
					</div>
				</div>				
				<div class="clear"></div>
				<a href="#" class="button submit-button"><span><span class="button-icon save"></span><?php _e('Save Changes','academy'); ?></span></a>
		</div>
	</form>
	<?php } else { ?>
	<div class="user-image">
		<div class="bordered-image thick-border">
			<?php echo get_avatar(ThemexUser::$data['current_user']->ID); ?>
		</div>
		<?php get_template_part('module', 'links'); ?>
	</div>
	<div class="user-description">
		<h1><?php echo ThemexUser::getFullName(ThemexUser::$data['current_user']); ?></h1>
		<div class="signature"><?php echo ThemexUser::$data['current_user']->signature; ?></div>
		<?php echo wpautop(ThemexUser::$data['current_user']->description); ?>
	</div>
	<?php } ?>
</div>
<div class="fivecol column last">
	<?php if(ThemexUser::isProfilePage() && ($subscription=ThemexCourse::getSubscriptionTime())) { ?>
	<h2 class="secondary"><?php echo $subscription; ?>.</h2>
	<?php } ?>
	<?php if(ThemexUser::isProfilePage() || ThemexCore::getOption('user_courses')!='true') { ?>
	<?php if($courses=ThemexCourse::getUserCourses(ThemexUser::$data['current_user']->ID)) { ?>
	<div class="user-courses-listing">
		<?php foreach($courses as $course) { ?>
		<?php ThemexCourse::initCourse($course->ID); ?>
		<div class="course-item <?php if(!ThemexCourse::isCompletedCourse(0)){ ?>started<?php } ?>">
			<div class="course-title">
				<?php if(ThemexCourse::$data['course']['author']->ID==ThemexUser::$data['current_user']->ID) { ?>
				<div class="course-status"><?php _e('Author', 'academy'); ?></div>
				<?php } ?>
				<h4 class="nomargin"><a href="<?php echo get_permalink($course->ID); ?>"><?php echo get_the_title($course->ID); ?></a></h4>
				<?php get_template_part('module', 'progress'); ?>				
			</div>
			<?php if(ThemexCourse::$data['rating']!='true') { ?>
			<div class="course-meta">			
			<?php get_template_part('module','rating'); ?>			
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php } else { ?>
	<h2 class="secondary"><?php _e('No courses yet.', 'academy'); ?></h2>
	<?php } ?>
	<?php } ?>
</div>
<?php get_footer(); ?>