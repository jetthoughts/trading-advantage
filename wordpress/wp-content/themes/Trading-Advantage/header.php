<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
	
	<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php echo THEME_URI; ?>js/html5.js"></script>
	<![endif]-->
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div class="site-wrap">
		<div class="header-wrap">
			<header class="site-header">
				<div class="row">
					<div class="site-logo left">
					<?php ThemexStyler::siteLogo(); ?>
					</div>
					<!-- /logo -->
					<div class="header-options right clearfix">					
						<div class="login-options right">
							<?php if(ThemexUser::userActive()) { ?>
							<div class="button-wrap left">
								<a href="<?php echo wp_logout_url(SITE_URL); ?>" class="button dark">
									<span><span class="button-icon logout"></span><?php _e('Sign Out','academy'); ?></span>
								</a>							
							</div>
							<div class="button-wrap left">
								<a href="<?php echo ThemexUser::$data['profile_page_url']; ?>" class="button">
									<span><span class="button-icon register"></span><?php _e('My Profile','academy'); ?></span>
								</a>						
							</div>							
							<?php } else { ?>						
							<div class="button-wrap left tooltip login-button">
								<a href="#" class="button dark"><span><span class="button-icon login"></span><?php _e('Sign In','academy'); ?></span></a>
								<div class="tooltip-wrap">
									<div class="tooltip-text">
										<form action="<?php echo AJAX_URL; ?>" class="login-form popup-form" method="POST">
											<div class="message"></div>
											<div class="field-wrap"><input type="text" name="user_login" value="<?php _e('Username','academy'); ?>" /></div>
											<div class="field-wrap"><input type="password" name="user_password" value="<?php _e('Password','academy'); ?>" /></div>
											<input type="hidden" name="login_nonce" class="nonce" value="<?php echo wp_create_nonce('login_nonce'); ?>" />
											<input type="hidden" class="action" value="themex_login" />
											<div class="button-wrap left nomargin">
												<a href="#" class="button submit-button">
													<span><?php _e('Sign In','academy'); ?></span>
												</a>
											</div>
											<?php if(ThemexUser::getOption('facebook_login')=='true') { ?>											
											<div class="button-wrap left">
												<a href="#" title="<?php _e('Sign in with Facebook','academy'); ?>" class="button facebook-button">
													<span><span class="button-icon facebook"></span></span>
												</a>
											</div>
											<?php } ?>
											<div class="button-wrap switch-button left">
												<a href="#" class="button dark" title="<?php _e('Password Recovery','academy'); ?>">
													<span><span class="button-icon help"></span></span>
												</a>
											</div>					
										</form>
									</div>
								</div>
								<div class="tooltip-wrap password-form">
									<div class="tooltip-text">
										<form action="<?php echo AJAX_URL; ?>" class="password-form popup-form" method="POST">
											<div class="message"></div>
											<div class="field-wrap"><input type="text" name="user_email" value="<?php _e('Email','academy'); ?>" /></div>
											<input type="hidden" name="password_nonce" class="nonce" value="<?php echo wp_create_nonce('password_nonce'); ?>" />
											<input type="hidden" class="action" value="themex_password" />
											<div class="button-wrap left nomargin">
												<a href="#" class="button submit-button">
													<span><?php _e('Reset Password','academy'); ?></span>
												</a>
											</div>
										</form>
									</div>
								</div>
							</div>
							<?php if(get_option('users_can_register')) { ?>
							<div class="button-wrap left">
								<a href="<?php echo ThemexUser::$data['register_page_url']; ?>" class="button">
									<span><span class="button-icon register"></span><?php _e('Register','academy'); ?></span>
								</a>
							</div>
							<?php } ?>
							<?php } ?>
						</div>
						<!-- /login options -->										
						<div class="search-form right">
							<?php get_search_form(); ?>
						</div>
						<!-- /search form -->
						<?php if($share_code=ThemexCore::getOption('share_code')) { ?>
						<div class="button-wrap share-button tooltip right">
							<a href="#" class="button dark"><span><span class="button-icon plus nomargin"></span></span></a>
							<div class="tooltip-wrap">
								<div class="corner"></div>
								<div class="tooltip-text"><?php echo themex_html($share_code); ?></div>
							</div>
						</div>
						<!-- /share button -->
						<?php } ?>
					</div>
					<!-- /header options -->
					<div class="mobile-search-form">
						<?php get_search_form(); ?>
					</div>
					<!-- /mobile search form -->
					<nav class="header-navigation right">
						<?php wp_nav_menu( array( 'theme_location' => 'main_menu','container_class' => 'menu' ) ); ?>						
						<div class="select-menu">
							<?php ThemexInterface::renderDropdownMenu('main_menu'); ?>
							<span>&nbsp;</span>
						</div><!--/ select menu-->
					</nav>
					<!-- /navigation -->						
				</div>			
			</header>
			<!-- /header -->
		</div>
		<div class="featured-content">
			<?php 
			ThemexStyler::pageBackground();
			if(is_front_page() && !ThemexUser::isLoginPage()) {
				get_template_part('module','slider');
			} else {
			?>
			<div class="row">
			<?php
			if(get_post_type()=='course' && is_single()) {
				get_template_part('module','course');
			} else {
			?>
			<div class="page-title">
				<h1 class="nomargin"><?php ThemexStyler::pageTitle(); ?></h1>
			</div>
			<!-- /page title -->
			<?php }	?>
			</div>
			<?php } ?>
		</div>
		<!-- /featured -->
		<div class="main-content">
			<div class="row">