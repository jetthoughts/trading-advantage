<?php
//Custom user module
class ThemexUser {

	public static $data;
	public static $id=__CLASS__;	
	
	//Init module
	public static function init() {
	
		//refresh module data
		self::refresh();		
		
		//ajax actions
		add_action('wp_ajax_nopriv_themex_register', array(__CLASS__,'registerUser'));		
		add_action('wp_ajax_nopriv_themex_login', array(__CLASS__,'loginUser'));
		add_action('wp_ajax_nopriv_themex_password', array(__CLASS__,'recoverUser'));
		
		//admin bar
		add_filter('show_admin_bar', array(__CLASS__,'renderAdminBar'));
		
		//get users data
		add_action('wp', array(__CLASS__,'initUser'), 1);
		add_action('admin_init', array(__CLASS__,'initUser'));
		
		//save user data
		add_action('template_redirect', array(__CLASS__,'saveData'));
		
		//activate user
		add_action('template_redirect', array(__CLASS__,'activateUser'));
		
		//set avatar
		add_filter('get_avatar', array(__CLASS__,'getAvatar'), 10, 5);
		
		//show register page
		add_filter('template_include', array(__CLASS__,'renderLoginPage'), 100, 1);
		
		//set login title
		add_filter('wp_title', array(__CLASS__,'setLoginTitle'), 10, 2);
		
		//profile image
		add_filter('show_user_profile', array(__CLASS__,'renderAvatar'));
		add_filter('edit_user_profile', array(__CLASS__,'renderAvatar'));
		add_action('edit_user_profile_update', array(__CLASS__,'saveAvatar'));
		add_action('personal_options_update', array(__CLASS__,'saveAvatar'));
		
		//profile text
		add_filter('show_user_profile', array(__CLASS__,'renderBiography'));
		add_filter('edit_user_profile', array(__CLASS__,'renderBiography'));
		
		//facebook login
		if(self::getOption('facebook_login')) {
			add_action('wp_footer', array(__CLASS__,'loadFacebookAPI'));
			add_action('init', array(__CLASS__,'renderFacebookPage'));
			add_filter('init', array(__CLASS__,'loginFacebookUser'), 90);
			add_action('wp_logout', array(__CLASS__,'logoutFacebookUser'));
		}
	}
	
	//Refresh module stored data
	public static function refresh() {
		self::$data=ThemexCore::getOption(self::$id);
		self::$data['register_page_url']=ThemexCore::generateURL(ThemexCore::$components['rewrite_rules']['register']['name']);
		self::$data['messages']=array();
	}
	
	//Save module static data
	public static function save() {
		ThemexCore::updateOption(self::$id,self::$data);
	}
	
	//Save module settings
	public static function saveSettings($data) {
	
		//refresh stored data
		self::refresh();
		
		//search for widget areas options
		foreach($data as $key=>$value) {
			if($key==self::$id) {				
				self::$data=$value;
			}
		}
		
		//save static data
		self::save();
	}
		
	//Render settings
	public static function renderSettings($slug) {
		//get module stored data
		self::refresh();
		
		$options=array(
			array(
				'type' => 'checkbox',
				'id' => self::$id.'[facebook_login]',
				'default' => isset(self::$data['facebook_login'])?self::$data['facebook_login']:'false',
				'description' => __('Check this option to allow users to sign in with Facebook.','academy'),
				'name' => __('Enable Facebook Connect','academy'),
				'attributes' => array('class'=>'themex_parent'),
			),
			
			array(
				'type' => 'text',
				'id' => self::$id.'[facebook_id]',
				'default' => isset(self::$data['facebook_id'])?self::$data['facebook_id']:'',
				'name' => __('Facebook Application ID','academy'),
				'attributes' => array('class'=>'themex_child'),
				'hidden' => true,
			),
			
			array(
				'type' => 'text',
				'id' => self::$id.'[facebook_secret]',
				'default' => isset(self::$data['facebook_secret'])?self::$data['facebook_secret']:'',
				'name' => __('Facebook Application Secret','academy'),
				'attributes' => array('class'=>'themex_child'),
				'hidden' => true,
			),
			
			array(
				'type' => 'checkbox',
				'id' => self::$id.'[confirmation]',
				'default' => isset(self::$data['confirmation'])?self::$data['confirmation']:'false',
				'description' => __('Check this option if user email confirmation required.','academy'),
				'name' => __('Enable Email Confirmation','academy'),
			),
			
			array(
				'type' => 'checkbox',
				'id' => self::$id.'[captcha]',
				'default' => isset(self::$data['captcha'])?self::$data['captcha']:'false',
				'description' => __('Check this option to enable captcha protection.','academy'),
				'name' => __('Enable Captcha Protection','academy'),
			),
			
			array(
				'type' => 'textarea',
				'id' => self::$id.'[register_message]',
				'name' => __('Registration Message','academy'),
				'description' => __('Message that is sent to user after registration. Use %username%, %password% and %link% codes to show them in the message text.','academy'),
				'default' => isset(self::$data['register_message'])?self::$data['register_message']:'',
			),
			
			array(
				'type' => 'textarea',
				'id' => self::$id.'[password_message]',
				'name' => __('Password Reset Message','academy'),
				'description' => __('Message that is sent when user lost the password. Use %username% and %link% codes to show them in the message text.','academy'),
				'default' => isset(self::$data['password_message'])?self::$data['password_message']:'',
			),
		);
		
		$out='<div class="'.$slug.'">';
		foreach($options as $option) {
			$out.=ThemexInterface::renderOption($option);
		}
		$out.='</div>';
		
		return $out;
	}
	
	//Init users data
	public static function initUser() {
		self::$data['user']=wp_get_current_user();
		self::$data['current_user']=self::$data['user'];
		self::$data['profile_page_url']=get_author_posts_url(get_current_user_id());		
		
		if(get_query_var('author')) {
			self::$data['current_user']=get_userdata(get_query_var('author'));
		}
	}
	
	//Render admin bar
	public static function renderAdminBar() {
		if(current_user_can('edit_posts') && get_user_option('show_admin_bar_front', get_current_user_id())!='false') {
			return true;
		}
		
		return false;
	}
	
	//Render register page
	public static function renderLoginPage($template) {
		if(ThemexUser::isLoginPage()) {
			if(self::userActive()) {
				wp_redirect(self::$data['profile_page_url']);
				exit;
			} else {
				$template = THEME_PATH.'template-register.php';
			}
		}
		
		return $template;
	}
	
	//Set login title
	public static function setLoginTitle($title, $sep) {
		if(ThemexUser::isLoginPage()) {
			if(get_option('users_can_register')) {
				$title=__('Registration', 'academy');
			} else {
				$title=__('Authorization', 'academy');
			}
			$title.=' '.$sep.' ';
		}
		
		return $title;
	}
	
	//Get Avatar
	public static function getAvatar($avatar, $user, $size, $default, $alt) {
		
		if(isset($user->user_id)) {
			$user=$user->user_id;
		}
		
		$avatar_id=get_user_meta($user, 'avatar', true);
		$default=wp_get_attachment_image_src( $avatar_id, 'thumbnail');
		
		if(!isset($default[0])) {
			$default[0]=THEME_URI.'images/avatar.png';
		}
		
		return '<img src="'.$default[0].'" class="avatar" width="'.$size.'" alt="'.$alt.'" />';
	}
	
	//Render Avatar
	public static function renderAvatar($user) {	
		$out='';		
		if(current_user_can('manage_options')) {
			$out='<table class="form-table"><tbody><tr>';
			$out.='<th><label for="avatar">'.__('Profile Photo', 'academy').'</label></th><td><div class="themex_avatar">';
			$out.=get_avatar( $user->ID );
			$out.='<input type="hidden" name="avatar" /><a href="#" class="button upload_button">'.__('Upload','academy').'</a>';
			$out.='</div></td></tr></tbody></table>';
		}
		
		echo $out;
	}
	
	//Save Avatar
	public static function saveAvatar($user) {
		global $wpdb;
		
		if(isset($_POST['avatar']) && !empty($_POST['avatar']) && current_user_can('manage_options')) {
			$src=esc_url($_POST['avatar']);
			$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$src'";
			$avatar = $wpdb->get_var($query);
			update_user_meta( $user, 'avatar', $avatar);
		}
	}
	
	//Render Biography
	public static function renderBiography($user) {
		$out='<table class="form-table"><tbody><tr>';
		$out.='<th><label for="description">'.__('Profile Text', 'academy').'</label></th><td>';
		
		ob_start();
		ThemexInterface::renderEditor('description', wpautop(get_user_meta( $user->ID, 'description', true)));
		$out.=ob_get_contents();
		ob_end_clean();
		
		$out.='</tr></td></tbody></table>';
		
		echo $out;
	}
	
	//Login user
	public static function loginUser() {
		self::refresh();
		parse_str($_POST['data'], $creds);
		
		self::$data['messages']=array();
		$creds['remember']=true;
		
		$user=wp_signon($creds, false);
		
		self::$data['messages']=array();
		if(is_wp_error($user) || empty($creds['user_login']) || empty($creds['user_password'])){
			self::$data['messages'][]=__('Invalid username or password','academy');
		} else if(array_shift($user->roles)=='inactive') {
			self::$data['messages'][]=__('This account is not activated', 'academy');
		} else {
			self::renderMessages('<a href="'.get_author_posts_url($user->ID).'" class="redirect"></a>');
		}
		
		if(!empty(self::$data['messages'])){		
			wp_logout();
			self::renderMessages();
		}

		die();
	}

	//Register user
	public static function registerUser() {
		self::refresh();
		parse_str($_POST['data'], $userdata);
		
		self::$data['messages']=array();
		
		if(isset(self::$data['captcha']) && self::$data['captcha']=='true') {		
			session_start();
			$posted_code=md5($userdata['captcha']);
			$session_code = $_SESSION['captcha'];
			
			if($session_code!=$posted_code) {
				self::$data['messages'][]=__('Verification code is incorrect','academy');
			}
		}
		
		if(empty($userdata['user_email']) || empty($userdata['user_email']) || empty($userdata['user_password']) || empty($userdata['user_password_repeat'])) {
			self::$data['messages'][]=__('Please fill in all fields.','academy');
		} else {
			if(!is_email($userdata['user_email'])) {
				self::$data['messages'][]=__('Invalid email address.','academy');
			} else if(email_exists($userdata['user_email'])) {
				self::$data['messages'][]=__('This email is already in use.','academy');
			}
			
			if(!validate_username($userdata['user_login'])) {
				self::$data['messages'][]=__('Invalid character used in username.','academy');
			} else	if(username_exists($userdata['user_login'])) {
				self::$data['messages'][]=__('This username is already taken.','academy');
			}
			
			if(strlen($userdata['user_password'])<4) {
				self::$data['messages'][]=__('Password must be at least 4 characters long.','academy');
			} else if(strlen($userdata['user_password'])>16) {
				self::$data['messages'][]=__('Password must be not more than 16 characters long.','academy');
			} else if(preg_match("/^([a-zA-Z0-9]{1,20})$/",$userdata['user_password'])==0) {
				self::$data['messages'][]=__('Invalid character used in password.','academy');
			} else if($userdata['user_password']!=$userdata['user_password_repeat']) {
				self::$data['messages'][]=__('Passwords do not match.','academy');
			}
			
			if(!isset($userdata['register_url'])) {
				self::$data['messages'][]=__('Registration page does not exist.','academy');
			}
		}
		
		if(!empty(self::$data['messages'])){
			self::renderMessages();
		} else {		
			$user=wp_create_user($userdata['user_login'], $userdata['user_password'], $userdata['user_email']);
			wp_new_user_notification($user);
			$mail_message=self::$data['register_message'];
			$confirm_code='';
			
			if(empty($mail_message)) {
				$mail_message='Hi, %username%! Welcome to '.get_bloginfo('name').'.';
			}
			
			if(isset(self::$data['confirmation']) && self::$data['confirmation']=='true') {
				$message=__('Registration complete! Check your mailbox to activate the account.','academy');
				$subject=__('Account Confirmation','academy');
				$confirm_code=md5(uniqid());
				
				$user=new WP_User($user);
				$user->remove_role(get_option('default_role'));
				$user->add_role('inactive');
				add_user_meta($user->ID, 'activation_code', $confirm_code, true);	

				if(intval(substr($userdata['register_url'], -1))==1) {
					$userdata['register_url'].='&';
				} else {
					$userdata['register_url'].='?';
				}
				
				$mail_message=str_replace('%link%', $userdata['register_url'].'activate='.urlencode($confirm_code), $mail_message);
			} else {			
				wp_signon($userdata, false);
				$message='<a href="'.get_author_posts_url($user).'" class="redirect"></a>';
				$subject=__('Registration Complete','academy');
			}
			
			$mail_message=str_replace('%username%', $userdata['user_login'], $mail_message);			
			$mail_message=str_replace('%password%', $userdata['user_password'], $mail_message);
			
			self::sendEmail($userdata['user_email'], $subject, themex_html($mail_message));		
			self::renderMessages($message);
		}

		die();
	}
	
	//Recover user password
	public static function recoverUser() {
		global $wpdb, $current_site;
		parse_str($_POST['data'], $userdata);
		
		self::$data['messages']=array();
		
		if(email_exists(sanitize_email($userdata['user_email']))) {
			$user=get_user_by('email', sanitize_email($userdata['user_email']));
			do_action('lostpassword_post');
			
			$user_login=$user->user_login;
			$user_email=$user->user_email;			
			
			do_action('retrieve_password', $user_login);
			$allow=apply_filters('allow_password_reset', true, $user->ID);
			
			if(!$allow || is_wp_error($allow)) {
				self::$data['messages'][]=__('Password recovery not allowed','academy');
				self::renderMessages();
			} else {
				$key=$wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
				
				if ( empty($key) ) {
					$key = wp_generate_password(20, false);
					do_action('retrieve_password_key', $user_login, $key);
					$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
				}
				
				$link=network_site_url('wp-login.php?action=rp&key='.urlencode($key).'&login='.urlencode($user_login),'login');
				$mail_message=self::$data['password_message'];
				
				if(empty($mail_message)) {
					$mail_message='Hi, %username%! To reset your password, visit the following link: %link%.';
				}
				
				$mail_message=str_replace('%username%', $user->user_login, $mail_message);
				$mail_message=str_replace('%link%', $link, $mail_message);
				
				if(self::sendEmail($user_email, __('Password Recovery','academy'), themex_html($mail_message))) {
					self::renderMessages(__('Password reset link is sent','academy'));
				} else {
					self::$data['messages'][]=__('Error sending email','academy');
				}				
			}
			
		} else {
			self::$data['messages'][]=__('Invalid email address','academy');				
		}		
		
		if(!empty(self::$data['messages'])) {
			self::renderMessages();
		}
		
		die();
	}	
	
	//Save Profile
	public static function saveData() {
	
		//check author
		if(!is_author()) {
			return;
		}
	
		//change avatar
		if (isset($_FILES['avatar']) && $_FILES['avatar']['name']!='') {
			require_once(ABSPATH.'wp-admin/includes/image.php');
		
			$uploads = wp_upload_dir();
			$filetype=wp_check_filetype($_FILES['avatar']['name'], null );
			$filename=basename('avatar_'.self::$data['user']->ID.'.'.$filetype['ext']);
			$filepath=$uploads['path'].'/'.$filename;
			
			if ($filetype['ext']!='png' && $filetype['ext']!='jpg' && $filetype['ext']!='jpeg') {
				self::$data['messages'][]=__('Only JPG and PNG images are allowed.','academy');
			} else if(is_uploaded_file($_FILES['avatar']['tmp_name'])) {
				
				//remove previous avatar
				wp_delete_attachment(self::$data['user']->avatar);
			
				if(move_uploaded_file($_FILES['avatar']['tmp_name'], $filepath)) {	
					$attachment = array(
						'guid' => $uploads['url'].'/'.$filename,
						'post_mime_type' => $filetype['type'],
						'post_title' => sanitize_title(current(explode('.', $filename))),
						'post_content' => '',
						'post_status' => 'inherit',
					);
					
					//set new avatar
					self::$data['user']->avatar=wp_insert_attachment( $attachment, $attachment['guid'], 0 );
					update_user_meta(self::$data['user']->ID, 'avatar', self::$data['user']->avatar);
					update_post_meta(self::$data['user']->avatar, '_wp_attached_file', substr($uploads['subdir'], 1).'/'.$filename);
					
					//generate thumbnail
					$metadata=wp_generate_attachment_metadata(self::$data['user']->avatar, $filepath);
					wp_update_attachment_metadata(self::$data['user']->avatar, $metadata);
				}			
			} else {
				self::$data['messages'][]=__('This image is too large for uploading.','academy');
			}
		}
		
		//text fields
		if(isset(ThemexCore::$components['profile_fields'])) {
			foreach(ThemexCore::$components['profile_fields'] as $field_name) {
				if(isset($_POST['user_'.$field_name])) {
					self::$data['user']->$field_name=sanitize_text_field($_POST['user_'.$field_name]);
					update_user_meta(self::$data['user']->ID, $field_name, self::$data['user']->$field_name);
					self::$data['user']->$field_name=themex_stripslashes(self::$data['user']->$field_name);
				}
			}
		}
		
		//description
		if(isset($_POST['user_description'])) {			
			self::$data['user']->description=wp_kses(wpautop($_POST['user_description']), array(
				'strong' => array(),
				'em' => array(),
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'p' => array(),
				'br' => array(),
			));

			update_user_meta(self::$data['user']->ID, 'description', self::$data['user']->description );
			self::$data['user']->description=themex_stripslashes(self::$data['user']->description);
		}
	}
	
	//Activate User
	public static function activateUser() {
		if(isset($_GET['activate'])) {	
			$users=get_users(array(
				'meta_key' => 'activation_code',
				'meta_value' => sanitize_text_field($_GET['activate']),
			));
			
			if(isset($users[0])) {
				$users[0]->remove_role('inactive');
				$users[0]->add_role(get_option('default_role'));
				update_user_meta($users[0]->ID, 'activation_code', '');				
				self::$data['account_activated']=true;
			}
		}
	}
	
	//Send Email
	public static function sendEmail($to, $subject, $message) {
		$headers = "MIME-Version: 1.0" . PHP_EOL;
		$headers .= "Content-Type: text/html; charset=UTF-8".PHP_EOL;		
		
		if(wp_mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $headers)) {
			return true;
		}
		return false;
	}
	
	//Check active user
	public static function userActive() {
		if(is_user_logged_in() && array_shift(self::$data['user']->roles)!='inactive') {
			return true;
		}
		
		return false;
	}
	
	//Check profile page
	public static function isProfilePage() {
		return get_query_var('author') && self::$data['user']->ID==get_query_var('author');
	}
	
	
	//Check login page
	public static function isLoginPage() {
		if(get_query_var(ThemexCore::$components['rewrite_rules']['register']['name'])) {
			return true;
		}
		
		return false;
	}	
	
	//Render messages
	public static function renderMessages($success_message=null) {
		$out='';
		if(isset($success_message)) {
			$out.='<div class="success"><ul><li>'.$success_message.'</li></ul></div>';
		} else {
			$out.='<div class="error"><ul>';
			foreach(self::$data['messages'] as $message) {
				$out.='<li>'.$message.'</li>';
			}
			$out.='</ul></div>';
		}
		echo $out;
	}
	
	//Get full name
	public static function getFullName($user) {
		$out='';
		
		if($user->first_name!='') {
			$out.=$user->first_name.' ';
		}
		$out.=$user->last_name;
		
		return $out;
	}
	
	//Get description
	public static function getDescription($description='') {
	
		$divider=strpos($description, '</p>');
		if($divider!==false) {
			return substr($description, 0, $divider).'</p>';
		}
		
		return $description;
	}
	
	//Load Facebook API
	public static function loadFacebookAPI($logout=false) {
		$out='<div id="fb-root"></div>
		<script type="text/javascript">
		window.fbAsyncInit = function() {
		FB.init({			
		appId      : "'.self::getOption('facebook_id').'",
		channelUrl : "'.home_url('?facebook=1').'",
		status     : true,
		cookie     : true,
		xfbml      : true,
		oauth	   : true
		});';

		if($logout) {
			$out.='FB.getLoginStatus(function(response) {
			if (response.status === "connected") {
			FB.logout(function(response) {
			window.location.href="'.home_url().'";
			});
			} else {
			window.location.href="'.home_url().'";
			}
			});';
		}

		$out.='};
		(function(d){
		var js, id = "facebook-jssdk"; if (d.getElementById(id)) {return;}
		js = d.createElement("script"); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/'.self::getFacebookLocale().'/all.js";
		d.getElementsByTagName("head")[0].appendChild(js);
		}(document));
		</script>';
		
		echo $out;
	}
	
	//Get Facebook locale
	public static function getFacebookLocale() {
		$locale = get_locale();
		$locales = array(
			'ca_ES', 'cs_CZ', 'cy_GB', 'da_DK', 'de_DE', 'eu_ES', 'en_PI', 'en_UD', 'ck_US', 'en_US', 'es_LA', 'es_CL', 'es_CO', 'es_ES', 'es_MX',
			'es_VE', 'fb_FI', 'fi_FI', 'fr_FR', 'gl_ES', 'hu_HU', 'it_IT', 'ja_JP', 'ko_KR', 'nb_NO', 'nn_NO', 'nl_NL', 'pl_PL', 'pt_BR', 'pt_PT',
			'ro_RO', 'ru_RU', 'sk_SK', 'sl_SI', 'sv_SE', 'th_TH', 'tr_TR', 'ku_TR', 'zh_CN', 'zh_HK', 'zh_TW', 'fb_LT', 'af_ZA', 'sq_AL', 'hy_AM',
			'az_AZ', 'be_BY', 'bn_IN', 'bs_BA', 'bg_BG', 'hr_HR', 'nl_BE', 'en_GB', 'eo_EO', 'et_EE', 'fo_FO', 'fr_CA', 'ka_GE', 'el_GR', 'gu_IN',
			'hi_IN', 'is_IS', 'id_ID', 'ga_IE', 'jv_ID', 'kn_IN', 'kk_KZ', 'la_VA', 'lv_LV', 'li_NL', 'lt_LT', 'mk_MK', 'mg_MG', 'ms_MY', 'mt_MT',
			'mr_IN', 'mn_MN', 'ne_NP', 'pa_IN', 'rm_CH', 'sa_IN', 'sr_RS', 'so_SO', 'sw_KE', 'tl_PH', 'ta_IN', 'tt_RU', 'te_IN', 'ml_IN', 'uk_UA',
			'uz_UZ', 'vi_VN', 'xh_ZA', 'zu_ZA', 'km_KH', 'tg_TJ', 'ar_AR', 'he_IL', 'ur_PK', 'fa_IR', 'sy_SY', 'yi_DE', 'gn_PY', 'qu_PE', 'ay_BO',
			'se_NO', 'ps_AF', 'tl_ST',
		);
		
		$locale = str_replace('-', '_', $locale);
		if(strlen($locale)==2) {
			$locale = strtolower($locale).'_'.strtoupper($locale);
		}
		
		if (!in_array($locale, $locales)) {
			$locale='en_US';
		}
		
		return $locale;
	}
	
	//Render Facebook page
	public static function renderFacebookPage() {
		if (isset($_GET['facebook'])) {
			$limit=60*60*24*365;
			header('Pragma: public');
			header('Cache-Control: max-age='.$limit);
			header('Expires: '.gmdate('D, d M Y H:i:s', time()+$limit).' GMT');
			echo '<script src="//connect.facebook.net/'.self::getFacebookLocale().'/all.js"></script>';
			exit;
		}
	}
	
	//Login Facebook user
	public static function loginFacebookUser() {
		if(!is_user_logged_in() && isset($_COOKIE['fbsr_'.self::getOption('facebook_id')])) {
			$cookie=self::parseFacebookCookie();
			
			if(isset($cookie['user_id'])) {
				$users=get_users(array(
					'meta_key' => 'facebook_id', 
					'meta_value' => $cookie['user_id'],
				));
					
				if(!empty($users) && $users[0]->ID!=1) {
					wp_set_auth_cookie($users[0]->ID, true);
					wp_redirect(self::$data['register_page_url']);				
					exit();
				} else {
					$profile=self::getFacebookProfile($cookie['user_id'], array(
						'fields' => 'username,first_name,last_name,email',
						'code' => $cookie['code'],
						'sslverify' => 0,
					));
					
					if(isset($profile['email'])) {
						if(!isset($profile['username'])) {
							if(isset($profile['first_name'])) {
								$profile['username']=$profile['first_name'];
							} else if(isset($profile['last_name'])) {
								$profile['username']=$profile['last_name'];
							}
						}
						
						$profile['username']=sanitize_user($profile['username']);
						while(username_exists($profile['username'])) {
							$profile['username'].=rand(0,9);
						}
						
						$user=wp_create_user($profile['username'], wp_generate_password(10), $profile['email']);	

						if(!is_wp_error($user) && $user!=1) {
							wp_set_auth_cookie($user, true);
							add_user_meta($user, 'facebook_id', $profile['id'], true);
							
							if(isset($profile['first_name'])) {
								update_user_meta($user, 'first_name', $profile['first_name']);
							}
							
							if(isset($profile['last_name'])) {
								update_user_meta($user, 'last_name', $profile['last_name']);
							}							
						}
						
						wp_redirect(self::$data['register_page_url']);				
						exit();
					}
				}				
			}
		}
	}
	
	//Logout Facebook user
	public static function logoutFacebookUser() {
		if(isset($_COOKIE['fbsr_'.self::getOption('facebook_id')])) {
			$domain = '.'.parse_url(home_url('/'), PHP_URL_HOST);
			setcookie('fbsr_'.self::getOption('facebook_id'), ' ', time()-31536000, '/', $domain);
			
			$out='<html><head></head><body>';
			ob_start();
			self::loadFacebookAPI(true);
			$out.=ob_get_contents();
			ob_end_clean();
			$out.='</body></html>';
			
			echo $out;
			exit();
		}
	}
	
	//Get Facebook profile
	public static function getFacebookProfile($ID, $fields=array()) {
		if (!empty($fields['code'])) {
			$response=wp_remote_get('https://graph.facebook.com/oauth/access_token?client_id='.self::getOption('facebook_id').'&redirect_uri=&client_secret='.self::getOption('facebook_secret').'&code='.$fields['code'], array('sslverify' => false));	
			if (!is_wp_error($response) && wp_remote_retrieve_response_code($response)==200) {
				parse_str($response['body'], $response);
				$fields['access_token']=$response['access_token'];		
			} else {
				return false;
			}
		}
		
		$url='https://graph.facebook.com/'.$ID.'?'.http_build_query($fields);
		$response=wp_remote_get($url, $fields);
		
		if (!is_wp_error($response) && $response) {
			$response=json_decode($response['body'], true);
			return $response;
		}
		
		return false;
	}
	
	//Parse Facebook cookie
	public static function parseFacebookCookie() {
		$cookie = array();		
		if(list($encoded_sign, $payload)=explode('.', $_COOKIE['fbsr_'.self::getOption('facebook_id')], 2)){
			$sign=base64_decode(strtr($encoded_sign, '-_', '+/')); 
			if (hash_hmac('sha256', $payload, self::getOption('facebook_secret'), true)==$sign){
				$cookie=json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
			}
		}
		
		return $cookie;
	}
	
	//Get module option
	public static function getOption($ID, $default='') {
		if(isset(self::$data[$ID])) {
			return self::$data[$ID];
		}
		
		return $default;
	}
	
}