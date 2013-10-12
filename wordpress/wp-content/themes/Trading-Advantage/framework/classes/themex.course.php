<?php
//Custom course module
class ThemexCourse {

	public static $data;
	public static $id=__CLASS__;
	
	//Init module
	public static function init() {
	
		//get module stored data
		self::refresh();

		//save course data
		add_action('template_redirect', array(__CLASS__,'saveData'));
		
		//check permissions
		add_action('template_redirect', array(__CLASS__,'checkMembers'));
		
		//init courses layout
		add_filter( 'pre_get_posts',  array(__CLASS__,'initLayout'));
		
		//add statistics page
		add_action('admin_menu', array(__CLASS__,'addStatistics'));
		
		//filter content embeds
		add_filter('embed_oembed_html', array(__CLASS__,'filterEmbeds'), 99, 4 );
		
		//filter user data
		add_action('user_register', array(__CLASS__,'filterUser'));
		
		//save course post
		add_filter( 'save_post',  array(__CLASS__,'saveCourse'));
		
		//ajax actions
		add_action('wp_ajax_themex_rating', array(__CLASS__,'setRating'));
		add_action('wp_ajax_nopriv_themex_rating', array(__CLASS__,'setRating'));
		
		//filter questions
		add_filter( 'comment_form_defaults', array(__CLASS__,'addQuestionForm'));
		add_action( 'comment_post', array(__CLASS__,'saveQuestion'));
		add_filter( 'preprocess_comment', array(__CLASS__,'validateQuestion'));
		
		//add lesson columns
		add_filter('manage_lesson_posts_columns', array(__CLASS__,'addLessonColumns'));
		add_action('manage_lesson_posts_custom_column', array(__CLASS__,'renderLessonColumns'), 10, 2); 
		add_action('pre_get_posts', array(__CLASS__,'sortLessonColumns'));
		
		//render certificate
		add_filter('template_include', array(__CLASS__,'renderCertificate'), 100, 1);
	}	
	
	//Refresh module stored data
	public static function refresh() {
		self::$data=ThemexCore::getOption(self::$id);

		self::$data['related']=ThemexCore::getOption('course_related', 'false');
		self::$data['users_limit']=ThemexCore::getOption('course_users_limit', 9);
		self::$data['questions_number']=ThemexCore::getOption('course_questions_number', 7);
		self::$data['users']=ThemexCore::getOption('course_users', 'false');
		self::$data['users_number']=ThemexCore::getOption('course_users_number', 'false');
		self::$data['rating']=ThemexCore::getOption('course_rating', 'false');
		self::$data['unsubscribe']=ThemexCore::getOption('course_unsubscribe', 'false');
		self::$data['view']=ThemexCore::getOption('course_view', 'grid');
		self::$data['layout']=ThemexCore::getOption('course_layout_'.self::$data['view'], 'three');
		self::$data['limit']=ThemexCore::getOption('course_limit', 12);
		self::$data['columns']=self::$data['layout']=='three'?4:3;
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
		
		$out='';
		
		return $out;
	}
	
	//Save course post
	public static function saveCourse($ID) {
		
		global $post;
	
		if ((isset($_POST['themex_nonce']) && !wp_verify_nonce($_POST['themex_nonce'], $ID)) || !current_user_can('manage_options')) {
			return $ID;
		}
		
		if(isset($_POST['add_user']) && isset($_POST['add_user_id'])) {
			if($post->post_type=='course') {
				self::addUser($ID, intval($_POST['add_user_id']));
			} else if($post->post_type=='plan') {
				self::subscribeUser($ID, intval($_POST['add_user_id']));
			}
		} else if(isset($_POST['remove_user']) && isset($_POST['remove_user_id'])) {
			if($post->post_type=='course') {
				self::removeUser($ID, intval($_POST['remove_user_id']));
			} else if($post->post_type=='plan') {
				self::unsubscribeUser($ID, intval($_POST['remove_user_id']));
			}
		}
	}
	
	//Filter content embeds
	public static function filterEmbeds($html, $url, $attr, $ID) {
		return '<div class="embedded-video">'.$html.'</div>';
	}
	
	//Get course authors
	public static function getAuthors($args) {
		if($args['orderby']=='post_count') {
			add_action('pre_user_query', array( __CLASS__, 'filterAuthors'));
		}
		
		$authors=get_users($args);
		
		return $authors;
	}
	
	//Filter course authors
	public static function filterAuthors($args) {		
		$args->query_from = str_replace("post_type = 'post'", "post_type = 'course'", $args->query_from);	
		remove_action('pre_user_query', array( __CLASS__, 'filterAuthors'));
		
		return $args;
	}
	
	//Get course questions
	public static function getQuestions() {
		add_filter( 'comments_clauses', array( __CLASS__, 'filterQuestions'));	
		return get_comments(array(
			'parent' => 0,
			'number' => self::$data['questions_number'],
		));
	}
	
	//Filter course questions
	public static function filterQuestions($query) {
		$IDs='0,';
		foreach(self::$data['course']['lessons'] as $lesson) {
			$IDs.=$lesson->ID.',';
		}
		$IDs=substr($IDs,0,-1);
		
        $filter = " AND comment_post_ID IN ( $IDs )";
        if (strpos( $query['where'], ' AND comment_post_ID =' )!==false) {
            $query['where'] = preg_replace('~ AND comment_post_ID = \d+~', $filter, $query['where']);
        } else {
            $query['where'] .= $filter;
        }

        remove_filter('comments_clauses', array( __CLASS__, 'filterQuestions'));
        return $query;
	}
	
	//Add question form
	public static function addQuestionForm($default) {
		$commenter = wp_get_current_commenter();
		
		if(get_post_type()=='lesson') {
			$default['comment_field']='<div class="formatted-form"><div class="field-wrapper"><input id="title" name="title" type="text" placeholder="'.__('Question', 'academy').'" /></div>';
			$default['comment_field'].='<div class="field-wrapper"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="'.__('Comment', 'academy').'"></textarea></div></div>';
			$default['comment_notes_before']='';
			$default['comment_notes_after']='';
			$default['logged_in_as']='';
		} else {
			$default['logged_in_as']='<div class="formatted-form">';
			$default['comment_notes_before']='<div class="formatted-form">';
			$default['comment_notes_after']='</div>';
			$default['fields']['author']='<div class="field-wrapper"><input id="author" name="author" type="text" value="" size="30" placeholder="'.__('Name', 'academy').'" /></div>';
			$default['fields']['email']='<div class="field-wrapper"><input id="email" name="email" type="text" value="" size="30" placeholder="'.__('Email', 'academy').'" /></div>';
			$default['fields']['url']='';
			$default['comment_field']='<div class="field-wrapper"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="'.__('Comment', 'academy').'"></textarea></div>';
		}		
		
		$default['title_reply']='';
		$default['title_reply_to']='';
		$default['label_submit']=__('Add Comment', 'academy');
		
		return $default;
	}
	
	//Save question
	public static function saveQuestion($ID) {
		add_comment_meta( $ID, 'title', sanitize_text_field($_POST[ 'title' ]) );
	}
	
	//Validate question
	public static function validateQuestion($commentdata) {
		if (get_post_type()=='lesson' && $commentdata['comment_parent']==0 && (!isset( $_POST['title'] ) || $_POST['title']=='')) {
			wp_die('<strong>'.__('ERROR', 'academy').'</strong>: '.__( 'please type a question.', 'academy' ));			
		}
		
		return $commentdata;
	}
	
	//Render question
	public static function renderQuestion($comment, $args, $depth) {
		$GLOBALS['comment']=$comment;
		$GLOBALS['depth']=$depth;
		get_template_part('loop', 'question');
	}
	
	//Count questions
	public static function questionsNumber($ID=0) {
		$comments=get_comments(array(
			'parent' => $ID,
			'count' => true,
		));
		
		return $comments;
	}
	
	//Init current course
	public static function initCourse($ID=0, $extended=true) {
		
		self::$data['course']['ID']=$ID;
		self::$data['course']['author']=self::getAuthor();
		self::$data['course']['users']=ThemexCore::parseMeta($ID, 'course', 'users');
		self::$data['course']['subscriptions']=self::getSubscriptions();
		self::$data['course']['plans']=self::getPlans();
		self::$data['course']['rating']=self::getRating();
		self::$data['course']['progress']=self::getProgress();
		self::$data['course']['lessons']=ThemexCore::getRelatedItems($ID, 'lesson', 'course');		
		self::$data['course']['product']=get_post_meta(self::$data['course']['ID'], '_course_product', true);
		self::$data['course']['status']=self::getStatus();
		self::$data['course']['price']=self::getPrice();

	}
	
	//Save Profile
	public static function saveData() {
	
		if(isset($_POST['course_action']) && isset($_POST['course_id'])) {
			self::initCourse($_POST['course_id']);
			
			switch($_POST['course_action']) {
				case 'add':
					if(self::$data['course']['status']=='premium' && ThemexWoo::isActive()) {
						ThemexWoo::addProduct(self::$data['course']['product']);
					} else {
						self::addUser(intval($_POST['course_id']));
					}
				break;
				
				case 'remove':
					self::removeUser(intval($_POST['course_id']));
				break;
				
				case 'complete':
					self::completeLesson(intval($_POST['lesson_id']));
				break;
				
				case 'uncomplete':
					self::uncompleteLesson(intval($_POST['lesson_id']));					
				break;
				
				case 'pass':
					self::checkQuiz(intval($_POST['lesson_id']));
				break;
				
				case 'subscribe':
					if(ThemexWoo::isActive()) {
						ThemexWoo::addProduct(get_post_meta($_POST['plan_id'], '_plan_product', true));
					} else {
						self::subscribeUser(intval($_POST['plan_id']));
					}					
				break;
			}
		}
	}
	
	//Get course status
	public static function getStatus() {
	
		$status=get_post_meta(self::$data['course']['ID'], '_course_status', true);		
		if(empty($status)) {
			$status='free';
		}
		
		return $status;
	}
	
	//Get course price
	public static function getPrice() {
	
		$price=__('Free','academy');
		
		if(self::$data['course']['status']=='premium') {
			$product_price=ThemexWoo::getPrice(self::$data['course']['product']);
			
			if(preg_match('/\\d/', $product_price)>0 && ThemexWoo::isActive()) {
				$price=$product_price;
			} else {
				self::$data['course']['status']='free';
			}
		}
		
		return $price;
	}
	
	//Get plan price
	public static function getPlanPrice($ID=0) {
	
		$price='';
		if(ThemexWoo::isActive()) {
			$plan_price=ThemexWoo::getPrice(get_post_meta($ID, '_plan_product', true));
			$period=intval(get_post_meta($ID, '_plan_period', true));
			
			switch($period) {
				case 7: 
					$period=__('week', 'academy');
				break;
				
				case 31: 
					$period=__('month', 'academy');
				break;
				
				case 365: 
					$period=__('year', 'academy');
				break;
				
				default:
					$period=round($period/31).' '.__('months', 'academy');
				break;
			}
			
			if(preg_match('/\\d/', $plan_price)>0) {
				$price='<span>'.$plan_price.'</span> / '.$period;
			}	
		}		
		
		return $price;
	}
	
	//Detect primary plan
	public static function isPrimaryPlan($ID=0) {
		$primary=false;
		
		if(!isset(self::$data['course']['plans'])) {
			self::$data['course']['plans']=get_posts(array(
				'post_type' => 'plan',
				'numberposts' => 1,
			));
		}
		
		if(!empty(self::$data['course']['plans']) && $ID==self::$data['course']['plans'][0]->ID) {
			$primary=true;
		}
		
		return $primary;		
	}
	
	//Get course plans
	public static function getPlans() {
		$categories=wp_get_post_terms(self::$data['course']['ID'], 'course_category', array('fields' => 'ids'));
		$plans=ThemexCore::getRelatedItems($categories, 'plan', 'category', false, false, true);
		
		return $plans;		
	}	
	
	//Get user subscriptions
	public static function getSubscriptions($ID=null) {
		$subscriptions=array();
		if(!isset($ID)) {
			$ID=get_current_user_id();
		}
	
		if(isset(self::$data['course']['subscriptions']) && !isset($_POST['course_action'])) {
			$subscriptions=self::$data['course']['subscriptions'];
		} else {
			$plans=ThemexCore::getRelatedItems($ID, 'plan', 'users', true, true, true);			
			
			foreach($plans as $plan_ID) {
				$users=ThemexCore::parseMeta($plan_ID, 'plan', 'users');
				if(time()<intval($users[$ID])) {
					$subscriptions[$plan_ID]=intval($users[$ID]);
				} else {
					self::unsubscribeUser($plan_ID);
				}
			}
			
			asort($subscriptions);			
		}
		
		return $subscriptions;
	}
	
	//Get subscription time
	public static function getSubscriptionTime() {
		$subscriptions=self::getSubscriptions();
		$message='';
		
		if(!empty($subscriptions)) {
			$plan=key($subscriptions);			
			$date=round((reset($subscriptions)-time())/86400);
			$message='"'.get_the_title($plan).'" '.__('subscription expires in', 'academy').' ';
			
			if($date!=0) {
				$message.=$date.' ';
				if($date==1) {
					$message.=__('day', 'academy');
				} else {
					$message.=__('days', 'academy');
				}
			} else {
				$message.=__('less than a day', 'academy');
			}
		}
		
		return $message;
	}
	
	//Detect course subscriber
	public static function isSubscriber() {
		$subscriptions=array_intersect(self::$data['course']['plans'], array_keys(self::$data['course']['subscriptions']));
		
		if(empty(self::$data['course']['plans']) || !empty($subscriptions)) {
			return true;
		}
		
		return false;
	}
	
	//Get course progress
	public static function getProgress($ID=0) {
	
		$lessons=ThemexCore::getRelatedItems(self::$data['course']['ID'], 'lesson', 'course');
		$progress=0;
		
		foreach($lessons as $lesson) {
			if(self::isCompletedLesson($lesson->ID, $ID)) {
				$progress++;
			}
		}
		
		$progress=count($lessons)==0?0:100*($progress/count($lessons));
		
		return $progress;
	}
	
	//Get course date
	public static function getDate($ID=0, $user=0) {	
		$users=ThemexCore::parseMeta($ID, 'course', 'graduates');
		$date='';

		if(isset($users[$user])) {
			$date=date(get_option('date_format'), intval($users[$user]));
		}
		
		return $date;
	}
	
	//Get Course Mark
	public static function getMark($ID=0) {
		$mark=0;
	
		if(isset(self::$data['course']['lessons']) && count(self::$data['course']['lessons'])!=0) {
			foreach(self::$data['course']['lessons'] as $lesson) {
				$users=ThemexCore::parseMeta($lesson->ID, 'lesson', 'users');
				if(isset($users[$ID])) {
					$mark+=intval($users[$ID]);
				}
			}
			
			$mark=$mark/count(self::$data['course']['lessons']);
		}
		
		return $mark;
	}
	
	//Get course rating
	public static function getRating() {
		$rating['value']=floatval(get_post_meta(self::$data['course']['ID'], '_course_rating_value', true));
		parse_str(get_post_meta(self::$data['course']['ID'], '_course_rating_users', true), $rating['users']);
		$rating['readonly']=true;
		
		if(self::isMember() && !in_array(get_current_user_id(), $rating['users'])) {
			$rating['readonly']=false;
		}
		
		return $rating;
	}
	
	//Set course rating
	public static function setRating() {
		self::initCourse(intval($_POST['id']));
		$new_rating=intval($_POST['rating']);
		$old_rating=self::$data['course']['rating']['value'];
		$users=self::$data['course']['rating']['users'];
		
		if(self::isMember() && !in_array(get_current_user_id(), $users) && $new_rating<6 && $new_rating>0) {
			$rating=(count($users)*$old_rating+$new_rating)/(count($users)+1);
			$users[]=get_current_user_id();
			
			update_post_meta(self::$data['course']['ID'], '_course_rating_value', $rating);
			update_post_meta(self::$data['course']['ID'], '_course_rating_users', http_build_query($users));
		}
		
		die;
	}
	
	//Get active users
	public static function getActiveUsers() {

		$users=array();
		$courses=get_posts(array(
			'post_type' => 'course',
			'numberposts' => -1,
		));
		
		foreach($courses as $course) {
			if(!empty($course->_course_users)) {
				self::initCourse($course->ID);
				foreach(self::$data['course']['users'] as $user_id) {				
					self::$data['course']['progress']=self::getProgress($user_id);
					
					if(!isset($users[$user_id])) {
					
						$user_data=get_userdata(intval($user_id));
						if($user_data!==false) {
							$user_data->user_registered=date('m/d/Y', strtotime($user_data->user_registered));
							$user_data->user_active_courses=1;
							$user_data->user_completed_courses=0;
							$user_data->user_mark=self::getUserMark($user_id);
							
							if(self::isCompletedCourse()) {
								$user_data->user_completed_courses++;								
								$user_data->user_courses=array(self::$data['course']['ID']);
							}
							
							$users[$user_id]=$user_data;
						}
						
					} else {
					
						$users[$user_id]->user_active_courses++;						
						if(self::isCompletedCourse()) {
							$users[$user_id]->user_completed_courses++;							
							$users[$user_id]->user_courses[]=self::$data['course']['ID'];
						}
						
					}
				}
			}			
		}
		
		if(!empty($users)) {
			usort($users, array(__CLASS__, 'compareUsers'));
		}
		
		return $users;
	}
	
	//Get random users
	public static function getRandomUsers() {
		$users_number=intval(self::$data['users_limit']);
		$users=self::filterUsers(self::$data['course']['users']);
		shuffle($users);
		$users=array_slice($users, 0, $users_number);
		
		return $users;
	}
	
	//Compare course users
	public static function compareUsers($a, $b) {
		return strcasecmp($a->user_login, $b->user_login);
	}
	
	//Detect course author
	public static function isAuthor() {
		if(self::$data['course']['author']->ID==get_current_user_id() || current_user_can('manage_options')) {
			return true;
		}
		
		return false;
	}
	
	//Get course author
	public static function getAuthor() {
		$post=get_post(self::$data['course']['ID']);
		$user=get_userdata($post->post_author);
		$user->full_name=ThemexUser::getFullName($user);
		$user->user_link=get_author_posts_url($user->ID);
		
		return $user;
	}
	
	//Get course action
	public static function getAction($url='') {
		
		if(!ThemexUser::userActive() && (isset(self::$data['course']['status']) && self::$data['course']['status']=='free')) {
			$url=ThemexUser::$data['register_page_url'];
		}
		
		return $url;
	}
	
	//Get user courses
	public static function getUserCourses($ID) {
		$user_courses=ThemexCore::getRelatedItems($ID, 'course', 'users', true);
		$author_courses=get_posts(array(
			'post_type' => 'course',
			'numberposts' => -1,
			'author' => $ID,
		));
		
		$course_IDs=array(-1);
		if(!empty($user_courses) || !empty($author_courses)) {
			$course_IDs=wp_list_pluck(array_merge($user_courses, $author_courses), 'ID');
		}

		$courses=get_posts(array(
			'post_type' => 'course',
			'numberposts' => -1,
			'post__in' => $course_IDs,
		));
		
		return $courses;
	}
	
	//Get related courses
	public static function getRelatedCourses($ID=0) {
		$categories=array();
		$terms=wp_get_post_terms( $ID, 'course_category');
		foreach($terms as $term) {
			$categories[]=$term->slug;
		}

		$courses=get_posts(array(
			'post_type' => 'course',
			'numberposts' => 4,
			'post__not_in' => array($ID),
			'tax_query' => array(
				array(
					'taxonomy' => 'course_category',
					'field' => 'slug',
					'terms' => $categories,
				)
			),
		));
		
		if(count($courses)<4) {
			$rest=get_posts(array(
				'post_type' => 'course',
				'numberposts' => 4-count($courses),
				'post__not_in' => array($ID),
				'tax_query' => array(
					array(
						'taxonomy' => 'course_category',
						'field' => 'slug',
						'terms' => $categories,
						'operator'  => 'NOT IN',
					)
				),
			));
			$courses=array_merge($courses, $rest);
		}		
				
		return $courses;
	}
	
	//Init page layout
	public static function initLayout($query) {

		if(isset($query->query_vars['course_category'])) {					
			$query->query_vars['posts_per_page']=self::$data['limit'];
		}
		
		return $query;
	}
	
	//Filter course members
	public static function checkMembers() {
		if(in_array(get_post_type(), array('lesson', 'quiz'))) {
		
			$lesson_ID=$GLOBALS['post']->ID;
			$course_ID=$GLOBALS['post']->_lesson_course;			
			if(get_post_type()=='quiz' && $lessons=ThemexCore::getRelatedItems($GLOBALS['post']->ID, 'lesson', 'quiz')) {
				$lesson_ID=$lessons[0]->ID;
				$course_ID=get_post_meta($lessons[0]->ID, '_lesson_course', true);
			}

			self::initCourse($course_ID);
			if((!self::isMember() || !self::isSubscriber()) && !self::isAuthor() && $GLOBALS['post']->_lesson_status!='free') {
				wp_redirect(get_permalink(self::$data['course']['ID']));
				exit;
			}
		}
	}
	
	//Check course members
	public static function hasMembers() {
		if(self::$data['users']!='true' && !empty(self::$data['course']['users'])) {
			return true;
		}
		
		return false;
	}
	
	//Detect course member
	public static function isMember() {
		if(is_user_logged_in() && in_array(get_current_user_id(), self::$data['course']['users'])) {
			return true;
		}
		
		return false;
	}
	
	//Filter lesson columns
	public static function addLessonColumns($columns) {	
		$before=array_slice($columns, 0, 2, true);
		$after=array_slice($columns, 2, count($columns)-2, true);
		$columns['lesson_course']=__('Course', 'academy');
		
		return array_merge($before, array('lesson_course' => __('Course', 'academy')), $after);
	}
	
	//Render lesson columns
	public static function renderLessonColumns($column, $ID) {
		if($column=='lesson_course'){
			$ID=intval(get_post_meta($ID, '_'.$column, true));
			$title='&mdash;';
			
			if($ID!=0) {
				$title='<a href="'.admin_url('edit.php').'?post_type=lesson&lesson_course='.$ID.'">'.get_the_title($ID).'</a>';
			}
			
			echo $title;
		}
	}
	
	//Sort lesson columns
	public static function sortLessonColumns($query) {
		global $pagenow;	
		if(is_admin() && $pagenow=='edit.php' && isset($_GET['lesson_course'])) {
			$query->set('meta_key', '_lesson_course');
			$query->set('meta_value', intval($_GET['lesson_course']));
		}
		
		return $query;
	}
	
	//Sort hierarchical lessons
	public static function sortLessons($lessons) {
		$sorted_lessons=array();
		$lessons=wp_list_pluck($lessons,'ID');
		$lessons=$items=get_posts(array(
			'numberposts' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_type' => 'lesson',
			'post__in' => $lessons,
		));
		
		foreach($lessons as $parent_lesson) {
			if($parent_lesson->post_parent==0) {
				$sorted_lessons[]=$parent_lesson;
				
				foreach($lessons as $child_lesson) {					
					if($child_lesson->post_parent==$parent_lesson->ID) {
						$sorted_lessons[]=$child_lesson;
					}
				}
			}	
		}
	
		return $sorted_lessons;
	}
	
	//Get lesson URL
	public static function getLessonURL($ID=0, $next=true) {
		$lessons=self::sortLessons(self::$data['course']['lessons']);
		$lesson_index=0;
		
		$step=1;
		if(!$next) {
			$step=-1;
		}
		
		foreach($lessons as $index=>$lesson) {
			if($lesson->ID==$ID) {
				$lesson_index=$index;
			}
		}
		
		if(($lesson_index!=(count($lessons)-1) && $next) || ($lesson_index!=0 && !$next)) {
			return get_permalink($lessons[$lesson_index+$step]->ID);
		}
		
		return false;
	}
	
	//Check if lesson completed
	public static function isCompletedLesson($ID=0, $user_ID=0) {
		if($user_ID==0) {
			$user_ID=ThemexUser::$data['current_user']->ID;
		}
	
		$users=ThemexCore::parseMeta($ID, 'lesson', 'users');
		if($ID==0 || (!empty($users) && isset($users[$user_ID]))) {
			return true;
		}
		
		return false;
	}
	
	//Check if course completed
	public static function isCompletedCourse($limit=100) {
		if(in_array(self::$data['course']['progress'], array($limit, 100))) {
			return true;
		}
		
		return false;
	}
	
	//Complete course lesson
	public static function completeLesson($ID=0, $mark=100, $protected=false) {
		$users=ThemexCore::parseMeta($ID, 'lesson', 'users');		
		$quiz=intval(get_post_meta($ID, '_lesson_quiz', true));
		
		if(!isset($users[get_current_user_id()]) && (($quiz!=0 && $protected) || !$protected) && self::isMember()) {
			$users[get_current_user_id()]=$mark;
			update_post_meta($ID, '_lesson_users', http_build_query($users));
		}
		
		self::initCourse(self::$data['course']['ID']);
		if(self::isCompletedCourse()) {
			self::addGraduate(self::$data['course']['ID'], get_current_user_id());
		}
	}
	
	//Uncomplete course lesson
	public static function uncompleteLesson($ID=0, $user=null) {
		$users=ThemexCore::parseMeta($ID, 'lesson', 'users');
		if(!isset($user)) {
			$user=get_current_user_id();
		}
		
		if(isset($users[$user])) {
			unset($users[$user]);
			update_post_meta($ID, '_lesson_users', http_build_query($users));
		}
	}
	
	//Add course graduate
	public static function addGraduate($ID=0, $user=null) {
		$users=ThemexCore::parseMeta($ID, 'course', 'graduates');
		if(!isset($user)) {
			$user=get_current_user_id();
		}
		
		if(!isset($users[$user]) && self::isMember()) {
			$users[$user]=current_time('timestamp');
		}
		
		update_post_meta($ID, '_course_graduates', http_build_query($users));
	}
	
	//Add course user
	public static function addUser($ID=0, $user=null) {
	
		$users=self::filterUsers(ThemexCore::parseMeta($ID, 'course', 'users'));
		if(!isset($user)) {
			$user=get_current_user_id();
		}
		
		if(!isset(self::$data['course']['ID']) || (!self::isMember() && self::isSubscriber())) {
			$users[]=$user;
		}
		
		update_post_meta($ID, '_course_users', http_build_query($users));
		update_post_meta($ID, '_course_users_number', count($users));
	}
	
	//Remove course user
	public static function removeUser($ID=0, $user=null) {
		if(!isset($user)) {
			$user=get_current_user_id();
		}
	
		$users=self::filterUsers(ThemexCore::parseMeta($ID, 'course', 'users'));	
		$user=array_search($user, $users);		
		
		if ($user!==false) {
			unset($users[$user]);
		}
		
		update_post_meta($ID, '_course_users', http_build_query($users));
		update_post_meta($ID, '_course_users_number', count($users));
	}
	
	//Subscribe user
	public static function subscribeUser($ID=0, $user=null) {
		$users=ThemexCore::parseMeta($ID, 'plan', 'users');
		if(!isset($user)) {
			$user=get_current_user_id();
		}
		
		$users[$user]=time()+86400*intval(get_post_meta($ID, '_plan_period', true));
		update_post_meta($ID, '_plan_users', http_build_query($users));
	}
	
	//Unsubscribe user
	public static function unsubscribeUser($ID=0, $user=null) {
		$users=ThemexCore::parseMeta($ID, 'plan', 'users');
		if(!isset($user)) {
			$user=get_current_user_id();
		}
		
		unset($users[$user]);
		update_post_meta($ID, '_plan_users', http_build_query($users));
	}
	
	//Filter users
	public static function filterUsers($users) {
		$filtered_users=array();
		
		foreach($users as $user_key=>$user_ID) {
			$user=get_userdata($user_ID);
			if($user!=false) {
				$filtered_users[$user_key]=$user_ID;
			}
		}
		
		return $filtered_users;
	}
	
	//Filter user
	public static function filterUser($ID) {
		$courses=self::getUserCourses($ID);		
		foreach($courses as $course) {
			self::removeUser($course->ID, $ID);
			$lessons=ThemexCore::getRelatedItems($course->ID, 'lesson', 'course');
			
			foreach($lessons as $lesson) {
				self::uncompleteLesson($lesson->ID, $ID);
			}
		}
		
		$subscriptions=self::getSubscriptions($ID);
		foreach($subscriptions as $subscription) {
			self::unsubscribeUser($subscription->ID, $ID);
		}
	}
	
	//Get completed courses
	public static function getCompletedCourses($users=array()) {
		$courses=array();
		
		if(!empty($users)) {
			foreach($users as $user) {
				if(isset($user->user_courses) && is_array($user->user_courses)) {
					$courses=array_merge($courses, $user->user_courses);
				}
			}
			
			$courses=array_unique($courses);			
		}
		
		return count($courses);
	}
	
	//Get average courses
	public static function getAverageCourses($users=array()) {
		$number=0;
		
		if(!empty($users)) {
			foreach($users as $user) {
				$number=$number+$user->user_active_courses;
			}
			
			$number=$number/count($users);
		}
		
		return round($number, 1);		
	}
	
	//Get course mark
	public static function getUserMark($ID=0) {		
		$lessons=ThemexCore::getRelatedItems($ID, 'lesson', 'users', true, true);
		$mark=0;
	
		if(!empty($lessons)) {
			foreach($lessons as $lesson) {
				$mark=$mark+self::getLessonMark($lesson->ID, $ID);
			}
			
			$mark=round(($mark/count($lessons)), 1);
		}
		
		return $mark;
	}
	
	//Get lesson mark
	public static function getLessonMark($ID=0, $user_ID=0) {
	
		if($user_ID==0) {
			$user_ID=get_current_user_id();
		}
		
		$users=ThemexCore::parseMeta($ID, 'lesson', 'users');
		if(!empty($users) && isset($users[$user_ID])) {
			return $users[$user_ID];
		}
		
		return 0;
		
	}
	
	//Get average mark
	public static function getAverageMark($users=array()) {
		$mark=0;
		
		if(!empty($users)) {
			foreach($users as $user) {
				$mark=$mark+$user->user_mark;			
			}
			
			$mark=$mark/count($users);
		}
		
		return round($mark, 1);		
	}
	
	//Check lesson quiz
	public static function checkQuiz($ID=0) {
		$quiz_ID=intval(get_post_meta($ID, '_lesson_quiz', true));
		$questions=ThemexCore::parseMeta($quiz_ID, 'quiz', 'questions');
		$pass_mark=intval(get_post_meta($quiz_ID, '_quiz_passmark', true));		
		
		if(!empty($questions) && self::isMember()) {
		
			$result_mark=0;
			foreach($questions as $question_key=>$question) {
				if(!isset($question['results'])) {
					$question['results'][0]='true';
				}
				
				$question_passed=true;
				foreach($question['answers'] as $answer_key=>$answer) {
					$answer_name='answer_'.$question_key.'_'.$answer_key;
					if((isset($question['results'][$answer_key]) && !isset($_POST[$answer_name])) || (!isset($question['results'][$answer_key]) && isset($_POST[$answer_name]))) {
						$question_passed=false;
					}
				}
				
				if($question_passed) {
					$result_mark++;
				}
			}
			
			$result_mark=($result_mark/count($questions))*100;
			
			if($result_mark>=$pass_mark) {
				self::completeLesson($ID, round($result_mark, 1), true);
				self::$data['messages']['success']=sprintf(__('Congratulations! You have passed this quiz achieving %d%%!', 'academy'), $result_mark);
			} else {
				self::uncompleteLesson($ID);
				self::$data['messages'][]=sprintf(__('You require %d%% to pass this quiz.', 'academy'), $pass_mark);
			}
		}
	}
	
	//Check quiz question
	public static function checkQuestion($question, $answer) {
		$result='';	
		$keys=explode('_', $answer);
		
		if(!isset($question['results'])) {
			$question['results'][0]='true';
		}
		
		if(isset(self::$data['messages']['success']) && isset($_POST[$answer]) && ThemexCore::getOption('course_retake')=='true') {
			if(isset($question['results'][$keys[2]])) {
				$result='success';
			} else {
				$result='error';
			}
		}		
		
		return $result;	
	}
	
	//Add statistics page
	public static function addStatistics() {
		add_submenu_page( 'edit.php?post_type=course', __('Statistics', 'academy'), __('Statistics', 'academy'), 'edit_posts', 'statistics', array(__CLASS__, 'renderStatistics')); 
	}
	
	//Render statistics page
	public static function renderStatistics() {
		self::initStatistics();
		get_template_part('module', 'statistics');
	}
	
	//Init courses statistics
	public static function initStatistics() {
	
		self::$data['statistics']['user']['ID']=0;
		if(isset($_GET['user']) && intval($_GET['user'])!=0) {
			self::$data['statistics']['user']['ID']=intval($_GET['user']);
			self::$data['statistics']['lessons']=ThemexCore::getRelatedItems($_GET['user'], 'lesson', 'users', true, true);
		}
		
		self::$data['statistics']['users']=self::getActiveUsers();
		self::$data['statistics']['user']['total']=count_users();
		self::$data['statistics']['user']['total']=self::$data['statistics']['user']['total']['total_users'];
		self::$data['statistics']['user']['active']=count(self::$data['statistics']['users']);
		self::$data['statistics']['user']['mark']=self::getAverageMark(self::$data['statistics']['users']);
		
		self::$data['statistics']['course']['total']=wp_count_posts('course');
		self::$data['statistics']['course']['total']=self::$data['statistics']['course']['total']->publish;
		self::$data['statistics']['course']['completed']=self::getCompletedCourses(self::$data['statistics']['users']);
		self::$data['statistics']['course']['average']=self::getAverageCourses(self::$data['statistics']['users']);		
	}
	
	//Check certificate
	public static function hasCertificate() {
		if(self::isMember() && self::isCompletedCourse()) {
			parse_str(get_post_meta(self::$data['course']['ID'], '_course_certificate', true), $content);
			
			if((isset($content[0]) && !empty($content[0])) || (isset($content[1]) && !empty($content[1]))) {
				return true;
			}
		}
	
		return false;
	}
	
	//Get certificate URL
	public static function getCertificateURL() {
		$code=strlen(get_current_user_id()).get_current_user_id().self::$data['course']['ID'];
		$url=ThemexCore::generateURL(ThemexCore::$components['rewrite_rules']['certificate']['name'], $code);
		
		return $url;
	}
	
	//Get certificate
	public static function getCertificate() {
		$certificate=array();
		
		if($code=get_query_var(ThemexCore::$components['rewrite_rules']['certificate']['name'])) {
			$certificate['user']=get_userdata(intval(substr($code, 1, intval(substr($code, 0, 1)))));
			$certificate['course']=get_post(intval(substr($code, intval(substr($code, 0, 1))+count($certificate['user']))));
			
			if($certificate['user'] && $certificate['course']) {
				self::initCourse($certificate['course']->ID);				
				$certificate['progress']=self::getProgress($certificate['user']->ID);
				
				if($certificate['progress']==100 && in_array($certificate['user']->ID, self::$data['course']['users'])) {
					$certificate['completed']=true;
					parse_str($certificate['course']->_course_certificate, $content);
					
					//image
					$certificate['image']='';
					if(isset($content[0]) && !empty($content[0])) {
						$certificate['image']='<img src="'.$content[0].'" alt="" />';
					}
					
					//text
					$certificate['text']='';
					if(isset($content[1]) && !empty($content[1])) {
						$certificate['text']=str_replace('%username%', ThemexUser::getFullName($certificate['user']), $content[1]);
						$certificate['text']=str_replace('%date%', self::getDate($certificate['course']->ID, $certificate['user']->ID), $certificate['text']);
						$certificate['text']=str_replace('%title%', get_the_title($certificate['course']->ID), $certificate['text']);
						$certificate['text']=wpautop(themex_html($certificate['text']));
					}
				}
			}
		}
		
		return $certificate;
	}
	
	//Render certificate
	public static function renderCertificate($template) {
		if(get_query_var(ThemexCore::$components['rewrite_rules']['certificate']['name'])) {
			$template = THEME_PATH.'template-certificate.php';
		}
		
		return $template;
	}
	
	//Render messages
	public static function renderMessages() {
		$out='';
		if(array_key_exists('success', self::$data['messages'])) {
			$out.='<div class="success">';
		} else {
			$out.='<div class="error">';
		}
		
		$out.='<ul>';
		foreach(self::$data['messages'] as $message) {
			$out.='<li>'.$message.'</li>';
		}
		$out.='</ul></div>';
		
		echo $out;
	}
}