<?php
//Custom woocommerce module
class ThemexWoo {

	public static $data;
	public static $woocommerce;
	public static $id=__CLASS__;
	
	//Init module
	public static function init() {
	
		//refresh module data
		self::refresh();
		
		if(self::isActive()) {
		
			//get plugin instance
			self::$woocommerce=$GLOBALS['woocommerce'];
		
			//filter plugin pages
			add_filter('template_redirect', array(__CLASS__,'filterPages'));
			
			//filter checkout fields
			add_filter( 'woocommerce_checkout_fields', array(__CLASS__, 'filterFields'), 10, 1 );
			
			//filter body classes
			add_filter('body_class', array(__CLASS__, 'filterClasses'), 99);
			
			//change order status
			add_action( 'woocommerce_order_status_completed', array(__CLASS__, 'completeOrder') );
			add_action( 'woocommerce_order_status_pending', array(__CLASS__, 'uncompleteOrder') );
			add_action( 'woocommerce_order_status_failed', array(__CLASS__, 'uncompleteOrder') );
			add_action( 'woocommerce_order_status_on-hold', array(__CLASS__, 'uncompleteOrder') );
			add_action( 'woocommerce_order_status_processing', array(__CLASS__, 'uncompleteOrder') );
			add_action( 'woocommerce_order_status_refunded', array(__CLASS__, 'uncompleteOrder') );
			add_action( 'woocommerce_order_status_cancelled', array(__CLASS__, 'uncompleteOrder') );
			
		}		
	}
	
	//Refresh module stored data
	public static function refresh() {
		self::$data=ThemexCore::getOption(self::$id);
	}
	
	//Save module static data
	public static function save() {
		ThemexCore::updateOption(self::$id,self::$data);
	}
	
	//Save module settings
	public static function saveSettings($data) {
	
		//refresh module data
		self::refresh();
		
		//set module data
		if(!isset($data[self::$id])) {
			$data[self::$id]='';
		}
		
		self::$data=$data[self::$id];
		
		//save module data
		self::save();
	}
	
	//Render module settings
	public static function renderSettings() {
	
		$out='';
	
		$out.=ThemexInterface::renderOption(array(	
			'name' => __('Show Country Field','academy'),
			'id' => self::$id.'[billing_country]',
			'type' => 'checkbox',
			'default' => isset(self::$data['billing_country'])?self::$data['billing_country']:''
		));
		
		$out.=ThemexInterface::renderOption(array(	
			'name' => __('Show City Field','academy'),
			'id' => self::$id.'[billing_city]',
			'type' => 'checkbox',
			'default' => isset(self::$data['billing_city'])?self::$data['billing_city']:''
		));
			
		$out.=ThemexInterface::renderOption(array(
			'name' => __('Show State Field','academy'),
			'id' => self::$id.'[billing_state]',
			'type' => 'checkbox',
			'default' => isset(self::$data['billing_state'])?self::$data['billing_state']:''
		));
			
		$out.=ThemexInterface::renderOption(array(
			'name' => __('Show Address Fields','academy'),
			'id' => self::$id.'[billing_address]',
			'type' => 'checkbox',
			'default' => isset(self::$data['billing_address'])?self::$data['billing_address']:''
		));
			
		$out.=ThemexInterface::renderOption(array(	
			'name' => __('Show Postcode Field','academy'),
			'id' => self::$id.'[billing_postcode]',
			'type' => 'checkbox',
			'default' => isset(self::$data['billing_postcode'])?self::$data['billing_postcode']:''
		));
			
		$out.=ThemexInterface::renderOption(array(	
			'name' => __('Show Company Field','academy'),
			'id' => self::$id.'[billing_company]',
			'type' => 'checkbox',
			'default' => isset(self::$data['billing_company'])?self::$data['billing_company']:''
		));
			
		$out.=ThemexInterface::renderOption(array(
			'name' => __('Show Phone Field','academy'),
			'id' => self::$id.'[billing_phone]',
			'type' => 'checkbox',
			'default' => isset(self::$data['billing_phone'])?self::$data['billing_phone']:''
		));
	
		return $out;
	}
	
	//Filter plugin pages
	public static function filterPages() {
	
		if(self::isActive()) {
			if(isset($_GET['order']) && self::getOrderStatus($_GET['order'])=='completed') {
				$item=self::getRelatedItem($_GET['order']);
				
				if($item!==false) {
					if($item->post_type=='course') {
						wp_redirect(get_permalink($item->ID));
						exit;
					} else if($item->post_type=='plan') {
						$category=get_term_link(intval(get_post_meta($item->ID, '_plan_category', true)), 'course_category');
						if(!is_wp_error($category)) {
							wp_redirect($category);
							exit;
						}						
					}					
				}			
			}
		}			
	}
	
	//Filter plugin fields
	public static function filterFields($fields) {
		self::$data['billing_first_name']=true;
		self::$data['billing_last_name']=true;
		self::$data['billing_email']=true;
		self::$data['shipping_first_name']=true;
		self::$data['shipping_last_name']=true;
		self::$data['order_comments']=true;
		
		foreach($fields as $form_key=>$form) {
			foreach($form as $field_key=>$field) {
				if(isset(self::$data[$field_key]) || isset(self::$data[str_replace('shipping_', 'billing_', $field_key)]) || isset(self::$data[substr($field_key, 0, -2)]) || substr($field_key, 0, 8)=='account_') {
					if(isset($fields[$form_key][$field_key]['label'])) {					
						$fields[$form_key][$field_key]['placeholder']=$fields[$form_key][$field_key]['label'];
					}					
				} else {
					unset($fields[$form_key][$field_key]);
				}
			}			
		}
		
		return $fields;
	}
	
	//Filter body classes
	public static function filterClasses($classes) {
		$classes=array_diff($classes, array('woocommerce-page', 'woocommerce'));	
		return $classes;
	}
	
	//Filter form value
	public static function filterValue($value, $key='', $prefix='') {
		if(isset($value) && $value!='') {
			return $value;
		}
		
		return get_user_meta(get_current_user_id(), str_replace($prefix, '', $key), true);
	}
	
	//Chech plugin activity
	public static function isActive() {
		if(class_exists('Woocommerce')) {
			return true;
		}
		
		return false;
	}
	
	//Get product price
	public static function getPrice($ID=0) {
		if(self::isActive()) {
			if(is_checkout()) {
				$price=self::$woocommerce->cart->get_total();
			} else {
				if(class_exists('WC_Subscriptions_Product') && WC_Subscriptions_Product::get_price_string($ID)!='') {
					$price=WC_Subscriptions_Product::get_price_string($ID);
					$prices=explode(' / ', $price);
					
					if(isset($prices[0])) {
						$price=$prices[0];
					}				
				} else {
					$product=new WC_Product_Simple($ID);
					$price=$product->get_price_html();
				}
			}			
			
			return $price;
		}
		
		return 0;		
	}
	
	//Complete order
	public static function completeOrder($ID=0) {
		$item=self::getRelatedItem($ID);	

		if($item->post_type=='course') {
			ThemexCourse::addUser($item->ID, $item->post_author);
		} else {
			ThemexCourse::subscribeUser($item->ID, $item->post_author);
		}
	}
	
	//Uncomplete order
	public static function uncompleteOrder($ID=0) {
		$item=self::getRelatedItem($ID);
		
		if($item->post_type=='course') {
			ThemexCourse::removeUser($item->ID, $item->post_author);
		} else if($item->post_type=='plan') {
			ThemexCourse::unsubscribeUser($item->ID, $item->post_author);
		}	
	}
	
	//Get related item
	public static function getRelatedItem($ID=0) {
		
		$item=false;
		$order=new WC_Order( intval($ID) );	
		$products=$order->get_items();		
		
		if(!empty($products)) {
			$product=reset($products);
			$ID=$product['product_id'];
		}
		
		$items=get_posts(array(
			'numberposts'=>1,
			'post_type'=>array('course', 'plan'),
			'meta_query'=>array(
				'relation' => 'OR',
				array(
					'key'=>'_course_product',
					'value'=>$ID,
				),
				array(
					'key'=>'_plan_product',
					'value'=>$ID,
				),
			),			
		));		

		if(!empty($items)) {			
			$item=$items[0];			
			if(!empty($products)) {
				$item->post_author=$order->user_id;
			}
		}
		
		return $item;
	}
	
	//Get order status
	public static function getOrderStatus($ID=0) {
		$order=new WC_Order( $ID );
		return $order->status;
	}
	
	//Add product and redirect
	public static function addProduct($ID=0) {
		self::$woocommerce->cart->empty_cart();
		self::$woocommerce->cart->add_to_cart($ID, 1);
		wp_redirect(self::$woocommerce->cart->get_checkout_url());
		exit();
	}
}