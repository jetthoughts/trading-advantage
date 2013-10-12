<?php
//Custom form module
class ThemexForm {

	public static $data;
	public static $id=__CLASS__;
	
	//Init module
	public static function init() {
		
		//ajax actions
		add_action('wp_ajax_themex_form', array(__CLASS__,'processData'));
		add_action('wp_ajax_nopriv_themex_form', array(__CLASS__,'processData'));
	
	}

	//Refresh module stored data
	public static function refresh() {
		self::$data=ThemexCore::getOption(self::$id);
	}
	
	//Change module stored data
	public static function change() {
	
		//refresh stored data
		self::refresh();
		
		//set action type
		$type=$_POST['type'];
		
		//choose method
		if($type=='add_field') {
			self::addField($_POST['slug']);
		} else if($type=='remove_field') {
			self::removeField($_POST['slug'], $_POST['field_id']);
		}
		
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
	
	//Render module settings
	public static function renderSettings($slug) {
	
		//get module stored data
		self::refresh();
		$out='<div class="themex_form">';
		
		//success message option
		$out.=ThemexInterface::renderOption(array(
				'type' => 'textarea',
				'id' => self::$id.'['.$slug.'][message]',
				'name' => __('Success Message','academy'),
				'default' => isset(self::$data[$slug]['message'])?self::$data[$slug]['message']:'',
			)
		);
		
		//captcha option
		$out.=ThemexInterface::renderOption(array(
					'type' => 'checkbox',
					'id' => self::$id.'['.$slug.'][captcha]',
					'default' => isset(self::$data[$slug]['captcha'])?self::$data[$slug]['captcha']:'false',
					'name' => __('Enable Captcha Protection','academy')
				)
		);
		
		//settings for stored fields
		if(isset(self::$data[$slug]['fields']) && is_array(self::$data[$slug]['fields'])) {
		
			foreach(self::$data[$slug]['fields'] as $field_id=>$field) {
			
				$out.='<div class="themex_section" id="'.$field_id.'">';
				
				//field type
				$out.=ThemexInterface::renderOption(array(
						'type' => 'select',
						'name' => __('Field Type','academy'),
						'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][type]',
						'default' => ThemexCore::getArrayValue(self::$data[$slug]['fields'][$field_id],'type',''),
						'options' => array('text'=>__('Text','academy'),'message'=>__('Message','academy'),'number'=>__('Number','academy'),'date'=>__('Date','academy'),'email'=>__('Email','academy'),'select'=>__('Select','academy'))
					)
				);
				
				//field label
				$out.=ThemexInterface::renderOption(array(
						'type' => 'text',
						'name' => __('Field Label','academy'),
						'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][label]',
						'default' => ThemexCore::getArrayValue(self::$data[$slug]['fields'][$field_id],'label',''),
					)
				);	
				
				//field options
				$out.=ThemexInterface::renderOption(array(
						'type' => 'text',
						'description' => __('Enter in comma separated options.','academy'),
						'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][options]',
						'default' => ThemexCore::getArrayValue(self::$data[$slug]['fields'][$field_id],'options',''),
						'hidden' => true,
						'name' => __('Field Options','academy')
					)
				);	
				
				//actions
				$out.='<div class="themex_icon add_field" data-slug="'.$slug.'"></div><div class="themex_icon remove_field" data-slug="'.$slug.'" data-id="'.$field_id.'"></div>';
				
				//close section
				$out.='</div>';
			
			}
			
		//default settings
		} else {
		
			//generate field id
			$field_id=uniqid();
			
			$out.='<div class="themex_section" id="'.$field_id.'">';
		
			//field type
			$out.=ThemexInterface::renderOption(array(
						'type' => 'select',
						'name' => __('Field Type','academy'),
						'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][type]',
						'std' => isset(self::$data[$slug]['fields'][$field_id]['type'])?self::$data[$slug]['fields'][$field_id]['type']:'text',
						'options' => array('text'=>__('Text','academy'),'message'=>__('Message','academy'),'number'=>__('Number','academy'),'date'=>__('Date','academy'),'email'=>__('Email','academy'),'select'=>__('Select','academy'))						
					)
				);
			
			//field label
			$out.=ThemexInterface::renderOption(array(
						'type' => 'text',
						'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][label]',
						'name' => __('Field Label','academy'),
					)
				);
				
			//field options
			$out.=ThemexInterface::renderOption(array(
					'type' => 'text',
					'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][options]',
					'description' => __('Enter in comma separated options.','academy'),
					'hidden' => true,
					'name' => __('Field Options','academy')
				)
			);
				
			//actions
			$out.='<div class="themex_icon add_field" data-slug="'.$slug.'"></div><div class="themex_icon remove_field" data-slug="'.$slug.'" data-id="'.$field_id.'"></div>';
				
			//close section
			$out.='</div>';
			
			//save field
			self::$data[$slug]['fields'][$field_id]=array();
			
			self::save();
				
		}

		$out.='</div>';
		
		return $out;
		
	}
	
	public static function renderData($slug, $after='') {
	
		//refresh stored settings
		self::refresh();
		
		$date_format=get_option('date_format');
		$out='<div class="formatted-form" id="'.$slug.'">';		
		$out.='<form class="contact-form" action="'.AJAX_URL.'" method="POST"><div class="message"></div>';
		
		//render each form field
		if(is_array(self::$data[$slug]['fields'])) {
			$index=0;
			foreach(self::$data[$slug]['fields'] as $field_id=>$field) {
			
				if(!isset($field['type'])) {
					break;
				}
			
				$option['attributes']=array( 
					'data-validation' => $field['type'],
					'placeholder' => $field['label'],
				);
				
				$option['id']=$field_id;
				$option['wrap']=false;
				
				switch($field['type']) {
				
					case 'message':
						$option['type']='textarea';		
						$option['attributes']['rows']='5';
					break;
					
					case 'select':
						$option['type']='select';					
						$option['options']=explode(',', $field['options']);	
						foreach($option['options'] as $key=>$value) {
							$new_options[$value]=$value;
						}
						$option['options']=$new_options;
					break;
					
					case 'date':
						$option['type']='date';	
					break;
					
					default:
						$option['type']='text';
					break;
					
				}
				
				
				
				if($field['type']!='message') {
					$out.='<div class="sixcol column ';
					if(($index+1) % 2==0) {
						$out.='last';
					}
					$out.='">';
				}
				
				$out.='<div class="clear"></div><div class="field-wrapper">'.ThemexInterface::renderOption($option).'</div>';
				
				if($field['type']!='message') {
					$out.='</div>';
				}
				
				$index++;
			}
		}
		
		if(isset(self::$data[$slug]['captcha']) && self::$data[$slug]['captcha']=='true') {
			$out.='<div class="form-captcha">';
			$out.='<img src="'.THEMEX_URI.'extensions/themex-form/captcha.php" alt="" />';
			$out.='<input name="captcha" type="text" id="captcha" size="6" value="" />';
			$out.='</div>';
		}
		
		$out.='<div class="clear"></div><a class="submit-button button" href="#" data-slug="'.$slug.'"><span>'.__('Send Message','academy').'</span></a>';
		$out.='<div class="form-loader"></div>';
		$out.=$after.'<input type="hidden" class="action" value="themex_form" /></form></div>';
		
		echo $out;
	
	}
	
	//Process user data
	public static function processData() {
	
		//refresh stored settings
		self::refresh();
	
		parse_str($_POST['data'], $data);
		$data['slug']='contact_form';
	
		//get captcha
		session_start();
		$posted_code=md5($data['captcha']);
		$session_code = $_SESSION['captcha'];
		
		//check errors	
		if(is_array(self::$data[$data['slug']]['fields'])) {
			$errors='';
			
			if($session_code != $posted_code && self::$data[$data['slug']]['captcha']=='true') {
				$errors.='<li>'.__('Verification code is incorrect','academy').'.</li>';
			}
			
			foreach(self::$data[$data['slug']]['fields'] as $field_id=>$field) {
				$value=$data[$field_id];
				if(trim($value)=='') {
					$errors.='<li>"'.$field['label'].'" '.__('field is required','academy').'</li>';
				}
				if($field['type']=='number' && !is_numeric($value) && $value!='') {
					$errors.='<li>"'.$field['label'].'" '.__('field can only contain numbers','academy').'.</li>';
				}
				if($field['type']=='email' && !preg_match("/^([a-zA-Z0-9_\.-]+)@([a-zA-Z0-9_\.-]+)\.([a-zA-Z\.]{2,6})$/",$value) && $value!='') {
					$errors.='<li>'.__('You have entered an invalid email address','academy').'.</li>';
				}
			}
		}

		//errors response
		if($errors != '') { 
			echo '<div class="error"><ul>'.$errors.'</ul></div>';		
		//send email
		} else {
			//message
			$message='';			
			if(is_array(self::$data[$data['slug']]['fields'])) {
				foreach(self::$data[$data['slug']]['fields'] as $field_id=>$field) {
					$message.=self::$data[$data['slug']]['fields'][$field_id]['label'].': '.$data[$field_id].PHP_EOL;
				}
			}

			//send message
			if(self::sendEmail($message, $data['slug'])) {
				echo '<div class="success"><ul><li>'.self::$data[$data['slug']]['message'].'</li></ul></div>';
			}
		}		
		
		die();
		
	}
	
	public static function sendEmail($message, $slug='') {		
		//headers
		$headers = "MIME-Version: 1.0" . PHP_EOL;
		$headers .= "Content-Type: text/html; charset=UTF-8".PHP_EOL;
		
		//subject
		$subject=__('Feedback','academy');
		
		//address
		$address=get_option('admin_email');
		
		if(wp_mail($address, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $headers)) {
			return true;
		}
		return false;
	}
	
	//Add form field
	public static function addField($slug) {
		
		//generate field id
		$field_id=uniqid();
		
		self::$data[$slug]['fields'][$field_id]=array();	
		
		//save static data
		self::save();
				
		//server response
		$out='<div class="themex_section hidden" id="'.$field_id.'">';
	
		//field type
		$out.=ThemexInterface::renderOption(array(
					'type' => 'select',
					'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][type]',
					'std' => self::$data[$slug]['fields'][$field_id]['type'],
					'name' => __('Field Type','academy'),
					'options' => array('text'=>__('Text','academy'),'message'=>__('Message','academy'),'number'=>__('Number','academy'),'date'=>__('Date','academy'),'email'=>__('Email','academy'),'select'=>__('Select','academy'))
				)
			);
		
		//field label
		$out.=ThemexInterface::renderOption(array(
					'type' => 'text',
					'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][label]',
					'name' => __('Field Label','academy')
				)
			);
			
		//field options
		$out.=ThemexInterface::renderOption(array(
				'type' => 'text',
				'id' => self::$id.'['.$slug.'][fields]['.$field_id.'][options]',
				'default' => self::$data[$slug]['fields'][$field_id]['options'],
				'description' => __('Enter in comma separated options.','academy'),
				'hidden' => true,
				'name' => __('Field Options','academy')			
			)
		);
			
		//action buttons
		$out.='<div class="themex_icon add_field" data-slug="'.$slug.'"></div><div class="themex_icon remove_field" data-slug="'.$slug.'"></div>';
			
		//close section
		$out.='</div>';			

		echo $out;
		
	}
	
	//Remove form field
	public static function removeField($slug, $field_id) {
		
		if(count(self::$data[$slug]['fields'])>1) {

			//unset current field
			unset(self::$data[$slug]['fields'][$field_id]);
			
		}
		
		//save fields
		self::save();
		
	}
	
}