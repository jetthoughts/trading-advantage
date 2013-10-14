<?php
//Theme configuration
$config = array (

	//Modules
	'modules' => array(
	
		//basic modules
		'interface' => 'ThemexInterface',
		
		//additional
		'themex_user' => 'ThemexUser',
		'themex_woo' => 'ThemexWoo',
		'themex_course' => 'ThemexCourse',
		'themex_form' => 'ThemexForm',
		'themex_widgetiser' => 'ThemexWidgetiser',
		'themex_shortcoder' => 'ThemexShortcoder',
		'themex_styler' => 'ThemexStyler',
	),

	//Components
	'components' => array(
		
		//Theme Supports
		'supports' => array (
			'automatic-feed-links',
			'post-thumbnails',
			'woocommerce',
		),
		
		//Rewrite Rules
		'rewrite_rules' => array (
			'user' => array(
				'name' => 'user',
				'rule' => 'author_base',
				'rewrite' => 'user',
				'position' => 'top',
				'replace' => true,
			),
			
			'register' => array(
				'name' => 'register',
				'rule' => 'register/?',
				'rewrite' => 'index.php?register=1',
				'position' => 'top',
				'replace' => false,
			),
			
			'certificate' => array(
				'name' => 'certificate',
				'rule' => 'certificate/([^/]+)',
				'rewrite' => 'index.php?certificate=$matches[1]',
				'position' => 'top',
				'replace' => false,
			),
		),
		
		//User Roles
		'user_roles' => array (
			array(
				'role' => 'inactive',
				'name' => __('Inactive', 'academy'),
				'capabilities' => array(),
			),			
		),
		
		//Menus
		'custom_menus' => array (
			array(
				'slug' => 'main_menu',
				'name' => __('Main Menu','academy'),
			),
			
			array(
				'slug' => 'footer_menu',
				'name' => __('Footer Menu','academy'),
			),
		),
		
		//Image Sizes
		'image_sizes' => array (
		
			array(
				'name' => 'normal',
				'width' => 420,
				'height' => 420,
				'crop' => false,
			),
			
			array(
				'name' => 'extended',
				'width' => 738,
				'height' => 738,
				'crop' => false,
			),
			
		),
		
		//Editor styles
		'editor_styles' => array(
			'bordered'=>__('Bordered List', 'academy'),
			'checked'=>__('Checked List', 'academy'),
		),
		
		//Profile fields
		'profile_fields' => array(
			'first_name', 
			'last_name', 
			'signature', 
			'twitter', 
			'facebook', 
			'tumblr', 
			'linkedin', 
			'vimeo', 
			'google', 
			'youtube', 
			'flickr',
		),

		//Theme backend styles
		'admin_styles' => array (
								
			//admin panel style
			array(	'name' => 'themexAdmin',
					'uri' => THEMEX_URI.'admin/css/style.css'),
		
			//color picker
			array(	'name' => 'themexColorpicker',
					'uri' => THEMEX_URI.'admin/css/colorpicker.css'),
					
			//thickbox
			array(	'name' => 'thickbox' ),

		),
		
		//Theme frontend styles
		'user_styles' => array (

			//main style
			array(	'name' => 'main',
					'uri' => THEME_CSS_URI.'style.css'),
			
		),
		
		//Theme backend scripts
		'admin_scripts' => array (
		
			//thickbox
			array(	'name' => 'thickbox' ),
			
			//media upload
			array(	'name' => 'media-upload' ),
			
			//jquery slider
			array(	'name' => 'jquery-ui-slider' ),
		
			//panel interface
			array(	'name' => 'themex_admin',
					'uri' => THEMEX_URI.'admin/js/jquery.interface.js',
					'deps' => array('jquery')),
					
			//color picker
			array(	'name' => 'themex_colorpicker',
					'uri' => THEMEX_URI.'admin/js/jquery.colorpicker.js',
					'deps' => array('jquery')),
					
			//shortcodes popup
			array(	'name' => 'themex_shortcode_popup',
					'uri' => THEMEX_URI.'extensions/themex-shortcoder/js/popup.js',
					'deps' => array('jquery')),
					
			//shortcodes preview
			array(	'name' => 'themex_livequery',
					'uri' => THEMEX_URI.'extensions/themex-shortcoder/js/jquery.livequery.js',
					'deps' => array('jquery')),
					
			//shortcodes cloner
			array(	'name' => 'themex_appendo',
				'uri' => THEMEX_URI.'extensions/themex-shortcoder/js/jquery.appendo.js',
				'deps' => array('jquery')),
							
			
		),	
		
		//Theme frontend scripts
		'user_scripts' => array (
		
			//jquery
			array(	'name' => 'jquery' ),			
					
			//slider
			array(	'name' => 'themexSliderScript',
					'uri' => THEME_URI.'js/jquery.themexSlider.js'),
					
			//hover intent
			array(	'name' => 'ratyScript',
					'uri' => THEME_URI.'js/jquery.raty.min.js'),
					
			//hover intent
			array(	'name' => 'hoverIntentScript',
					'uri' => THEME_URI.'js/jquery.hoverIntent.min.js'),
					
			//media player
			array(	'name' => 'jPlayerScript',
					'uri' => THEME_URI.'js/jplayer/jquery.jplayer.min.js'),
					
			//placeholders script
			array(	'name' => 'placeholderScript',
					'uri' => THEME_URI.'js/jquery.placeholder.min.js'),
					
			//comment reply
			array(	'name' => 'comment-reply',
					'condition' => array('function'=>'is_single','value'=>'')),
					
			//custom
			array(	'name' => 'customScript',
					'uri' => THEME_URI.'js/jquery.custom.js'),
					
		),

		//Widget settings
		'widget_settings' => array (
			'before_widget' => '<div class="widget sidebar-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title"><h3 class="nomargin">',
			'after_title' => '</h3></div>',
		),
		
		//Default widget areas
		'widget_areas' => array (
			
		),
		
		//Widgets
		'widgets' => array (
			'themex_comments_widget',
			'themex_twitter_widget',
			'themex_authors_widget',
			'WP_Widget_Search',
			'WP_Widget_Recent_Comments',
		),	
		
		//Post types
		'post_types' => array (
			
			//Course
			array (
				'id' => 'course',
				'labels' => array (
					'name' => __('Courses','academy'),
					'singular_name' => __( 'Course','academy' ),
					'add_new' => __('Add New','academy'),
					'add_new_item' => __('Add New Course','academy'),
					'edit_item' => __('Edit Course','academy'),
					'new_item' => __('New Course','academy'),
					'view_item' => __('View Course','academy'),
					'search_items' => __('Search Courses','academy'),
					'not_found' =>  __('No Courses Found','academy'),
					'not_found_in_trash' => __('No Courses Found in Trash','academy'), 
					'parent_item_colon' => ''
				 ),
				'public' => true,
				'exclude_from_search' => false,
				'publicly_queryable' => true,
				'show_ui' => true, 
				'query_var' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title','editor','excerpt','thumbnail','author','revisions'),
				'rewrite' => array('slug' => __('course', 'academy')),
			),
			
			//Lesson
			array (
				'id' => 'lesson',
				'labels' => array (
					'name' => __('Lessons','academy'),
					'singular_name' => __( 'Lesson','academy' ),
					'add_new' => __('Add New','academy'),
					'add_new_item' => __('Add New Lesson','academy'),
					'edit_item' => __('Edit Lesson','academy'),
					'new_item' => __('New Lesson','academy'),
					'view_item' => __('View Lesson','academy'),
					'search_items' => __('Search Lessons','academy'),
					'not_found' =>  __('No Lessons Found','academy'),
					'not_found_in_trash' => __('No Lessons Found in Trash','academy'), 
					'parent_item_colon' => ''
				 ),
				'public' => true,
				'exclude_from_search' => true,
				'publicly_queryable' => true,
				'show_ui' => true, 
				'query_var' => true,
				'capability_type' => 'post',
				'hierarchical' => true,
				'menu_position' => null,
				'supports' => array('title','editor','comments','author','revisions','page-attributes'),
				'rewrite' => array('slug' => __('lesson', 'academy')),				
			)
		),
		
		//Taxonomies
		'taxonomies' => array (			
			array(	
				'taxonomy' => 'course_category',
				'object_type' => array('course'),					
				'settings' => array(
					'hierarchical' => true,
					'show_in_nav_menus' => true,
					'labels' => array(
	                    'name' 				=> __( 'Course Categories', 'academy'),
	                    'singular_name' 	=> __( 'Course Category', 'academy'),
						'menu_name'			=> __( 'Categories', 'academy' ),
	                    'search_items' 		=> __( 'Search Course Categories', 'academy'),
	                    'all_items' 		=> __( 'All Course Categories', 'academy'),
	                    'parent_item' 		=> __( 'Parent Course Category', 'academy'),
	                    'parent_item_colon' => __( 'Parent Course Category:', 'academy'),
	                    'edit_item' 		=> __( 'Edit Course Category', 'academy'),
	                    'update_item' 		=> __( 'Update Course Category', 'academy'),
	                    'add_new_item' 		=> __( 'Add New Course Category', 'academy'),
	                    'new_item_name' 	=> __( 'New Course Category Name', 'academy')
	            	),
					'rewrite' => array(
						'slug' => __('courses', 'academy'),
						'hierarchical' => true
					),
				)
			),
		),
		
		//Meta boxes
		'meta_boxes' => array(
		
			//Page
			array(
				'id' => 'page_metabox',
				'title' =>  __('Page Options', 'academy'),
				'page' => 'page',
				'context' => 'normal',
				'priority' => 'high',
				'options' => array(						
					array(	'name' => __('Page Background','academy'),
							'id' => 'background',
							'type' => 'uploader'),
				),			
			),
		

			//Course
			array(
				'id' => 'course_metabox',
				'title' =>  __('Course Options', 'academy'),
				'page' => 'course',
				'context' => 'normal',
				'priority' => 'high',				
				'options' => array(
					array(							
							'name' => __('Status', 'academy'),
							'desc' => __('Course status.','academy'),
							'type' => 'select',
							'id' => 'status',
							'options' => array(
								'premium' => __('Premium', 'academy'),
								'private' => __('Private', 'academy'),
								'free' => __('Free', 'academy'),								
							),
					),
				
					array(	'name' => __('Product','academy'),
							'desc' => __('Related product.','academy'),
							'id' => 'product',
							'type' => 'select_post',
							'post_type' => 'product'),	

					array(	'name' => __('Rating','academy'),
							'desc' => __('Course rating.','academy'),
							'id' => 'rating_value',
							'type' => 'number',
							'number_type' => 'float'),
							
					array(	'name' => __('Students','academy'),
							'desc' => __('Manage students.','academy'),
							'capability' => 'manage_options',
							'id' => 'users_manager',							
							'type' => 'users'),					
							
					array(	'name' => __('Certificate','academy'),
							'desc' => __('Course certificate. Use %username%, %date% and %title% codes to show them in the certificate text.','academy'),
							'id' => 'certificate',
							'type' => 'certificate'),
							
					array(	'name' => __('Background','academy'),
							'desc' => __('Course background.','academy'),
							'id' => 'background',
							'type' => 'uploader'),
				),
			),
			
			//Lesson
			array(
				'id' => 'lesson_metabox',
				'title' =>  __('Lesson Options', 'academy'),
				'page' => 'lesson',
				'context' => 'normal',
				'priority' => 'high',			
				'options' => array(	
					array(							
							'name' => __('Status', 'academy'),
							'desc' => __('Lesson status.','academy'),
							'type' => 'select',
							'id' => 'status',
							'options' => array(															
								'premium' => __('Premium', 'academy'),
								'free' => __('Free', 'academy'),
							),
					),				
					array(	'name' => __('Course','academy'),
							'desc' => __('Lesson course.','academy'),
							'id' => 'course',
							'type' => 'select_post',
							'post_type' => 'course'),
							
					array(	'name' => __('Prerequisite','academy'),
							'desc' => __('Lesson prerequisite.','academy'),
							'id' => 'prerequisite',
							'type' => 'select_post',
							'post_type' => 'lesson'),
							
					array(	'name' => __('Quiz','academy'),
							'desc' => __('Lesson quiz.','academy'),
							'id' => 'quiz',
							'type' => 'select_post',
							'post_type' => 'quiz'),

					array(	'name' => __('Attachments','academy'),
							'desc' => __('Lesson files.','academy'),
							'id' => 'attachments',
							'type' => 'attachments'),
				),
			)

		),
		
		'shortcodes' => array(
		
			//Columns
			'column' => array(
				'options' => array(),
				'shortcode' => '{{child_shortcode}}',
				'popup_title' => __('Insert Columns Shortcode', 'academy'),
				'child_shortcode' => array(
					'options' => array(
						'column' => array(
							'type' => 'select',
							'label' => __('Column Width', 'academy'),
							'options' => array(
								'one_sixth' => __('One Sixth', 'academy'),
								'one_sixth_last' => __('One Sixth Last', 'academy'),
								'one_fourth' => __('One Fourth', 'academy'),
								'one_fourth_last' => __('One Fourth Last', 'academy'),
								'one_third' => __('One Third', 'academy'),
								'one_third_last' => __('One Third Last', 'academy'),
								'five_twelfths' => __('Five Twelfths', 'academy'),
								'five_twelfths_last' => __('Five Twelfths Last', 'academy'),
								'one_half' => __('One Half', 'academy'),
								'one_half_last' => __('One Half Last', 'academy'),
								'seven_twelfths' => __('Seven Twelfths', 'academy'),
								'seven_twelfths_last' => __('Seven Twelfths Last', 'academy'),
								'two_thirds' => __('Two Thirds', 'academy'),
								'two_thirds_last' => __('Two Thirds Last', 'academy'),
								'three_fourths' => __('Three Fourths', 'academy'),
								'three_fourths_last' => __('Three Fourths Last', 'academy'),
							)
						),
						'content' => array(
							'std' => '',
							'type' => 'textarea',
							'label' => __('Column Content', 'academy'),
						)
					),
					'shortcode' => '[{{column}}]{{content}}[/{{column}}] ',
					'clone_button' => __('Add Column', 'academy')
				)
			),
		
			//Button
			'button' => array(
				'options' => array(
					'color' => array(
						'type' => 'select',
						'label' => __('Button Color', 'academy'),
						'options' => array(
							'primary' => __('Primary', 'academy'),
							'secondary' => __('Secondary', 'academy'),
							'dark' => __('Dark', 'academy'),	
						)
					),
					'size' => array(
						'type' => 'select',
						'label' => __('Button Size', 'academy'),
						'options' => array(
							'small' => __('Small', 'academy'),
							'medium' => __('Medium', 'academy'),
							'large' => __('Large', 'academy')
						)
					),
					'url' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('Button URL', 'academy'),
					),
					'target' => array(
						'type' => 'select',
						'label' => __('Button Target', 'academy'),
						'options' => array(
							'self' => __('Current Tab', 'academy'),
							'blank' => __('New Tab', 'academy'),
						)
					),
					'content' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('Button Text', 'academy'),
					)
				),
				'shortcode' => '[button url="{{url}}" target="{{target}}" size="{{size}}" color="{{color}}"]{{content}}[/button]',
				'popup_title' => __('Insert Button Shortcode', 'academy')
			),

			//Image
			'image' => array(
				'options' => array(
					'content' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('Image URL', 'academy'),
					),
					'url' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('Link URL', 'academy'),
					),					
				),
				'shortcode' => '[image url="{{url}}"]{{content}}[/image]',	
				'popup_title' => __('Insert Image Shortcode', 'academy')
			),
			
			//Courses
			'courses' => array(
				'options' => array(
					'category' => array(
						'type' => 'select_category',
						'label' => __('Courses Category', 'academy'),
						'taxonomy' => 'course_category',
					),
					'number' => array(
						'std' => '4',
						'type' => 'text',
						'label' => __('Courses Number', 'academy'),
					),					
					'order' => array(
						'type' => 'select',
						'label' => __('Courses Order', 'academy'),
						'options' => array(
							'date' => __('Date', 'academy'),
							'title' => __('Title', 'academy'),
							'users' => __('Users', 'academy'),
							'rand' => __('Random', 'academy'),
						)
					),
					'columns' => array(
						'type' => 'select',
						'label' => __('Columns Number', 'academy'),
						'options' => array(
							'1' => __('One', 'academy'),
							'2' => __('Two', 'academy'),
							'3' => __('Three', 'academy'),
							'4' => __('Four', 'academy'),
						)
					),
				),
				'shortcode' => '[courses category="{{category}}" number="{{number}}" columns="{{columns}}" order="{{order}}"]',		
				'popup_title' => __('Insert Courses Shortcode', 'academy')
			),
			

			'posts' => array(
				'options' => array(
					'category' => array(
						'type' => 'select_category',
						'label' => __('Posts Category', 'academy'),
						'taxonomy' => 'category',
					),
					'number' => array(
						'std' => '4',
						'type' => 'text',
						'label' => __('Posts Number', 'academy'),
					),					
					'order' => array(
						'type' => 'select',
						'label' => __('Posts Order', 'academy'),
						'options' => array(
							'date' => __('Date', 'academy'),
							'rand' => __('Random', 'academy'),
						)
					),
				),
				'shortcode' => '[posts category="{{category}}" number="{{number}}" order="{{order}}"]',		
				'popup_title' => __('Insert Posts Shortcode', 'academy')
			),
			
			//Tabs
			'tabs' => array(
				'options' => array(
					'type' => array(
							'type' => 'select',
							'label' => __('Tabs Type', 'academy'),
							'options' => array(
								'horizontal' => __('Horizontal', 'academy'),
								'vertical' => __('Vertical', 'academy'),
						)
					),
					'titles' => array(
						'type' => 'text',
						'label' => __('Tab Titles', 'academy'),
						'desc' => __('Enter the comma separated tab titles.', 'academy')
					),		
				),
				'shortcode' => '[tabs type="{{type}}" titles="{{titles}}"]{{child_shortcode}}[/tabs]',
				'popup_title' => __('Insert Tabs Shortcode', 'academy'),
				'child_shortcode' => array(
					'options' => array(
						'content' => array(
							'std' => '',
							'type' => 'textarea',
							'label' => __('Tab Content', 'academy'),
						)
					),
					'shortcode' => '[tab]{{content}}[/tab]',
					'clone_button' => __('Add Tab', 'academy')
				)
			),
			
			//Toggles
			'toggles' => array(
				'options' => array(
					'type' => array(
							'type' => 'select',
							'label' => __('Toggles Type', 'academy'),
							'options' => array(
								'multiple' => __('Multiple', 'academy'),
								'accordion' => __('Accordion', 'academy'),
						)
					),	
				),
				'shortcode' => '[toggles type="{{type}}"]{{child_shortcode}}[/toggles]',
				'popup_title' => __('Insert Toggles Shortcode', 'academy'),
				'child_shortcode' => array(
					'options' => array(
						'title' => array(
							'std' => '',
							'type' => 'text',
							'label' => __('Toggle Title', 'academy'),
						),
						'content' => array(
							'std' => '',
							'type' => 'textarea',
							'label' => __('Toggle Content', 'academy'),
						),
					),
					'shortcode' => '[toggle title="{{title}}"]{{content}}[/toggle]',
					'clone_button' => __('Add Toggle', 'academy')
				)
			),

			//Users
			'users' => array(
				'options' => array(
					'number' => array(
						'std' => '3',
						'type' => 'text',
						'label' => __('Users Number', 'academy'),
					),
					'order' => array(
						'type' => 'select',
						'label' => __('Users Order', 'academy'),
						'options' => array(
							'date' => __('Date', 'academy'),														
							'name' => __('Name', 'academy'),	
							'activity' => __('Activity', 'academy'),							
						)
					),
				),
				'shortcode' => '[users number="{{number}}" order="{{order}}"]',
				'popup_title' => __('Insert Users Shortcode', 'academy')
			),	
			
			//Google Map
			'map' => array(
				'no_preview' => true,
				'options' => array(
					'latitude' => array(
						'type' => 'text',
						'label' => __('Latitude', 'academy'),
					),
					'longitude' => array(
						'type' => 'text',
						'label' => __('Longitude', 'academy'),
					),
					'zoom' => array(
						'type' => 'text',
						'label' => __('Zoom', 'academy'),
					),
					'height' => array(
						'type' => 'text',
						'std' => '250',
						'label' => __('Height', 'academy'),
					),
					'description' => array(
						'type' => 'textarea',
						'std' => '',
						'label' => __('Description', 'academy'),
					),
				),
				'shortcode' => '[map latitude="{{latitude}}" longitude="{{longitude}}" zoom="{{zoom}}" description="{{description}}" height="{{height}}"]',
				'popup_title' => __('Insert Map Shortcode', 'academy')
			),
			
			//Block
			'block' => array(
				'options' => array(
					'title' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('Block Title', 'academy'),
					),
					'content' => array(
						'std' => '',
						'type' => 'textarea',
						'label' => __('Block Content', 'academy'),
					),
				),
				'shortcode' => '[block title="{{title}}"]{{content}}[/block]',			
				'popup_title' => __('Insert Block Shortcode', 'academy')
			),
			
			//Player
			'player' => array(
				'options' => array(
					'url' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('File URL', 'academy'),
					),
					'content' => array(
						'std' => '',
						'type' => 'text',
						'label' => __('File Title', 'academy'),
					),
				),
				'shortcode' => '[player url="{{url}}"]{{content}}[/player]',			
				'popup_title' => __('Insert Media Player Shortcode', 'academy')
			),
			
			//Contact Form
			'contact_form' => array(
				'shortcode' => '[contact_form]',
				'popup_title' => __('Insert Contact Form Shortcode', 'academy')
			),
		
		)
	),

);