<?php get_header(); ?>
<?php if(get_option('users_can_register')) { ?>
<div class="eightcol column">
	<h1><?php _e('Register','academy'); ?></h1>
	<div class="formatted-form">
		<form class="register-form" action="<?php echo AJAX_URL; ?>" method="POST">
			<div class="message">
			<?php if(ThemexUser::getOption('account_activated')) { ?>
			<div class="success">
				<ul>
					<li><?php _e('Your account is activated! Feel free to sign in now.', 'academy'); ?></li>
				</ul>
			</div>
			<?php } ?>
			</div>
			<div class="sixcol column">
				<div class="field-wrapper">
					<input type="text" name="user_login" placeholder="<?php _e('Username','academy'); ?>" />
				</div>								
			</div>
			<div class="sixcol column last">
				<div class="field-wrapper">
					<input type="text" name="user_email" placeholder="<?php _e('Email','academy'); ?>" />
				</div>
			</div>
			<div class="clear"></div>
			<div class="sixcol column">
				<div class="field-wrapper">
					<input type="password" name="user_password" placeholder="<?php _e('Password','academy'); ?>" />
				</div>
			</div>
			<div class="sixcol column last">
				<div class="field-wrapper">
					<input type="password" name="user_password_repeat" placeholder="<?php _e('Repeat Password','academy'); ?>" />
				</div>
			</div>
			<div class="clear"></div>			
			<?php if(ThemexUser::getOption('captcha')=='true') { ?>
			<div class="form-captcha">
				<img src="<?php echo THEMEX_URI; ?>extensions/themex-form/captcha.php" alt="" />
				<input type="text" name="captcha" id="captcha" size="6" value="" />
			</div>
			<div class="clear"></div>
			<?php } ?>
			<a href="#" class="button submit-button left"><span><span class="button-icon register"></span><?php _e('Register','academy'); ?></span></a>
			<div class="form-loader"></div>
			<input type="hidden" name="register_url" value="<?php echo ThemexUser::$data['register_page_url']; ?>" />
			<input type="hidden" name="register_nonce" class="nonce" value="<?php echo wp_create_nonce('register_nonce'); ?>" />
			<input type="hidden" class="action" value="themex_register" />
		</form>
	</div>
</div>
<?php } ?>
<div class="fourcol column last">
	<?php if(get_option('users_can_register')) { ?>
	<h1><?php _e('Sign In','academy'); ?></h1>
	<?php } ?>
	<div class="formatted-form">
		<form class="login-form" action="<?php echo AJAX_URL; ?>" method="POST">
			<div class="message"></div>
			<div class="field-wrapper">
				<input type="text" name="user_login" placeholder="<?php _e('Username','academy'); ?>" />
			</div>
			<div class="field-wrapper">
				<input type="password" name="user_password" placeholder="<?php _e('Password','academy'); ?>" />
			</div>			
			<a href="#" class="button submit-button left"><span><span class="button-icon login"></span><?php _e('Sign In','academy'); ?></span></a>
			<?php if(ThemexUser::getOption('facebook_login')=='true') { ?>
			<a href="#" title="<?php _e('Sign in with Facebook','academy'); ?>" class="button facebook-button left">
				<span><span class="button-icon facebook"></span></span>
			</a>
			<?php } ?>
			<div class="form-loader"></div>
			<input type="hidden" name="login_nonce" class="nonce" value="<?php echo wp_create_nonce('login_nonce'); ?>" />
			<input type="hidden" class="action" value="themex_login" />
		</form>
	</div>					
</div>
<?php get_footer(); ?>