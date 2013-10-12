<?php
//Custom styler module
class ThemexStyler {

	public static $data;
	public static $id=__CLASS__;
	
	//Init module
	public static function init() {
	
		self::$data=ThemexCore::$components['custom_styles'];
		
		//add styles and scripts
		add_action('wp_head', array(__CLASS__,'renderStyles'), 99);
		
		add_action('wp_head', array(__CLASS__,'renderFonts'), 1);
		
		//add login logo
		add_action('login_head', array(__CLASS__,'adminLogo'));
		
		//add site footer
		add_action('wp_footer', array(__CLASS__,'siteFooter'));
	
	}
	
	//Apply custom styles
	public static function renderStyles() {
	
		global $post;
	
		//favicon
		$out='<link rel="shortcut icon" href="'.ThemexCore::getOption('favicon',THEME_URI.'framework/admin/images/favicon.ico').'" />';
		
		//styles
		$out.='<style type="text/css">';
		
		if(is_array(self::$data)) {
			
			foreach(self::$data as $style) {
				$out.=$style['elements'].'{';
				
				if(is_array($style['attributes'])) {
					foreach($style['attributes'] as $attr_name=>$option_id) {					
						if(ThemexCore::getOption($option_id)) {
						
							if($attr_name=='background-image') {
								$option='url('.ThemexCore::getOption($option_id).')';
							} else if($attr_name=='font-size') {
								$option=ThemexCore::getOption($option_id).'px';
							} else if($attr_name=='font-family') {
								$option=ThemexCore::getOption($option_id).', Arial, Helvetica, sans-serif';
							} else {
								$option=ThemexCore::getOption($option_id);
							}
							
							$out.=$attr_name.':'.$option.';';
						}						
					}
				}
				
				
				
				$out.='}';
			}
		}
		
		$out.=ThemexCore::getOption('css').'</style>';
			
		echo $out;
	}
	
	//Add custom fonts
	public static function renderFonts() {
		$out='<script type="text/javascript">var templateDirectory = "'.THEME_URI.'";</script>';
		$heading_font=ThemexCore::getOption('heading_font','Crete Round');
		$content_font=ThemexCore::getOption('content_font','Open Sans:400,400italic,600');
		$fonts=array();
		
		if($heading_font=='Crete Round') {
			$out.='<style type="text/css">@font-face {
				font-family: "Crete Round";
				src: url("'.THEME_URI.'fonts/CreteRound-Regular-webfont.eot");
				src: url("'.THEME_URI.'fonts/CreteRound-Regular-webfont.eot?#iefix") format("embedded-opentype"),
					 url("'.THEME_URI.'fonts/CreteRound-Regular-webfont.woff") format("woff"),
					 url("'.THEME_URI.'fonts/CreteRound-Regular-webfont.ttf") format("truetype"),
					 url("'.THEME_URI.'fonts/CreteRound-Regular-webfont.svg#CreteRoundRegular") format("svg");
				font-weight: normal;
				font-style: normal;
			}</style>';
		}	
	
		if(!in_array($heading_font, array('Arial', 'Helvetica', 'Crete Round'))) {
			$fonts[]='"'.$heading_font.'"';
		}
		
		if(!in_array($content_font, array('Arial', 'Helvetica'))) {
			if($content_font=='Open Sans') {
				$content_font='Open Sans:400,400italic,600';
			}
		
			$fonts[]='"'.$content_font.'"';
		}		
		
		if(!empty($fonts)) {
			$out.='<script type="text/javascript">
			WebFontConfig = {google: { families: [ '.implode($fonts,',').' ] } };
			(function() {
				var wf = document.createElement("script");
				wf.src = ("https:" == document.location.protocol ? "https" : "http") + "://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";
				wf.type = "text/javascript";
				wf.async = "true";
				var s = document.getElementsByTagName("script")[0];
				s.parentNode.insertBefore(wf, s);
			})();
			</script>';
		}
		
		echo $out;
	}
	
	//Page background
	public static function pageBackground() {
		global $post;
		$ID=0;
		$type='';
		$out='';
		
		if(isset($post)) {
			$ID=$post->ID;
			$type=$post->post_type;
		}
		
		if(is_singular()) {
			if($type=='lesson') {
				$ID=intval(get_post_meta($ID, '_lesson_course', true));
				$type='course';
			}			
			$out=get_post_meta($ID, '_'.$type.'_background', true);			
		}
		
		if($out=='') {
			if($site_background=get_option('themex_background_image')) {
				$out=$site_background;
			} else if($site_background=get_option('themex_background_pattern')) {
				$out=$site_background;
			} else {
				$out=THEME_URI.'images/bgs/site_bg.jpg';
			}
		}
		
		if(get_option('themex_background_tiled')!='true') {
			echo '<div class="substrate"><img src="'.$out.'" alt="" /></div>';
		}		
	}
	
	//Page title
	public static function pageTitle() {
		global $post;
		$out='';
		if(is_single()) {
			if($post->post_type=='lesson' && $post->_lesson_course) {
				$out=get_the_title($post->_lesson_course);
			} else if($post->post_type=='quiz' && ($lessons=ThemexCore::getRelatedItems($post->ID, 'lesson', 'quiz'))) {
				$out=get_the_title($lessons[0]->_lesson_course);
			} else {
				$categories=wp_get_post_terms($post->ID,'category');
				if(!empty($categories)) {
					$out=$categories[0]->name;
				} else {
					$out=$post->post_title;
				}
			}
		} else {
			$out=wp_title('', false);
		}
		
		echo $out;
	}
	
	//Admin Logo
	public static function adminLogo() {
		$logo_url=get_option('themex_login_logo');
		if($logo_url) {
			echo '<style type="text/css">h1 a { background-image:url('.$logo_url.') !important; }</style>';
		}
	}
	
	//Site Logo
	public static function siteLogo() {
		$logo_type=ThemexCore::getOption('logo_type', 'image');
		
		if($logo_type=='image') {
			$logo_image=ThemexCore::getOption('logo_image', THEME_URI.'images/logo.png');
			$out='<a href="'.SITE_URL.'" rel="home"><img src="'.$logo_image.'" alt="'.get_bloginfo('name').'"></a>';
		} else if($logo_type=='text') {
			$logo_text=ThemexCore::getOption('logo_text', 'Text Logo');
			$out='<h1><a href="'.SITE_URL.'" rel="home">'.$logo_text.'</a></h1>';
		}
		
		echo $out;
	}
	
	//Site copyright
	public static function siteCopyright() {
		$copyright=get_option('themex_copyright_text');
		if($copyright) {	
			$copyright=themex_html($copyright);
		} else {
			$copyright='Academy Theme &copy; 2013';
		}
		
		echo $copyright;
	}
	
	//Site footer
	public static function siteFooter() {
		$out='';
		
		$tracking_code=get_option('themex_tracking_code');
		if($tracking_code) {
			$out=themex_html($tracking_code);
		}
		
		echo $out;
	}
}