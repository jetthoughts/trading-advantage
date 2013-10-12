<?php
//Theme shortcodes class
class ThemexShortcoder {

	public static $data;
	public static $id;
	
	//Init module
	public static function init() {
	
		//init shortcode functions
		require_once(THEMEX_PATH.'extensions/themex-shortcoder/shortcodes.php');
	
		//get shortcodes config
		self::$data=ThemexCore::$components['shortcodes'];
	
		//add tinymce plugin
		add_action('admin_init', array( __CLASS__, 'addShortcoder' ));
		
		//activate shortcodes
		add_filter('widget_text', 'do_shortcode');
		add_filter('the_excerpt', 'do_shortcode');
		
		//get current shortcode
		if(isset($_GET['popup'])) {
			self::$id=trim($_GET['popup']);
		}		
		
	}
	
	//Add button and plugin
	public static function addShortcoder() {		
		add_filter( 'mce_external_plugins', array( __CLASS__, 'addPlugin' ) );
		add_filter( 'mce_buttons', array( __CLASS__, 'addButton' ) );
	}
	
	//Add plugin
	public static function addPlugin( $plugin_array ) {
		$plugin_array['themexShortcoder'] = THEMEX_URI.'extensions/themex-shortcoder/js/plugin.js';
		return $plugin_array;
	}
	
	//Add editor button
	public static function addButton( $buttons ) {
		array_push( $buttons, '|', 'themex_button' );
		return $buttons;
	}
	
	//Render current shortcode settings
	public static function renderSettings() {
	
		//post instance
		global $post;
		
		if(is_array(self::$data))
		{

			//current shortcode options
			$out.='<div id="themex_shortcode" class="hidden">'.self::$data[self::$id]['shortcode'].'</div>';
			$out.='<div id="themex_popup" class="hidden">'.self::$id.'</div>';
			
			//render each option
			if(isset(self::$data[self::$id]['options'])) {
				foreach( self::$data[self::$id]['options'] as $option_id=>$option ) {
				
					//add options prefix
					$option_id='themex_'.$option_id;
					
					//shortcode option start
					$out.='<tbody>';
					$out.='<tr class="form-row">';
					$out.='<td class="label">'.$option['label'].'</td>';
					$out.='<td class="field">';				
					
					switch($option['type']) {
					
						case 'text' :
							$out.='<input type="text" class="themex_form-text themex-input" name="'.$option_id.'" id="'.$option_id.'" value="'.$option['std'].'" />';
						break;
						
						case 'textarea' :
							$out.='<textarea rows="10" cols="30" name="'.$option_id.'" id="'.$option_id.'" class="themex_form-textarea themex-input">'.$option['std'].'</textarea>';
						break;
							
						case 'select' :						

							$out.='<select name="'.$option_id.'" id="'.$option_id.'" class="themex_form-select themex-input">';						
							foreach( $option['options'] as $value=>$name ) {
								$out.='<option value="'.$value.'">'.$name.'</option>';
							}						
							$out.='</select>';				
							
						break;
						
						case 'select_post':				
							$query=new WP_Query(array(
								'showposts'=>-1, 
								'post_type' => $option['post_type'], 
								'orderby' => 'title', 
								'order' => 'ASC',
							));
							
							$temp_post=$post;							
							$out.='<select id="'.$option_id.'" name="'.$option_id.'">';
							$out.='<option value="0">'.__('None', 'academy').'</option>';
							
							if($query->have_posts()) {
								while ($query->have_posts()) {
									$query->the_post();
									$out.='<option value="'.$post->ID.'">'.$post->post_title.'</option>';
								}
							}									
							$out.='</select>';
							$post=$temp_post;
						break;
						
						case 'select_page':
						
							$args=array(
								'selected'         => $value,
								'echo'             => 0,
								'id'             => $option_id
							);	
							ob_start();
							echo wp_dropdown_pages($args);
							$out.=ob_get_contents();
							ob_end_clean();		
							
						break;
						
						case 'select_category':
						
							$taxonomy='category';
							if(isset($option['taxonomy'])) {
								$taxonomy=$option['taxonomy'];
							}
							
							$args=array(
								'hide_empty'         => 0,
								'show_option_all'    => __('All Categories','academy'),
								'echo'               => 0,
								'hierarchical'       => 0, 
								'name'               => $option_id,
								'id'				 => $option_id,
								'class'              => 'postform',
								'depth'              => 0,
								'tab_index'          => 0,
								'taxonomy'           => $taxonomy,
								'hide_if_empty'      => false
							);	
							
							$out.= wp_dropdown_categories($args);
							
						break;
						
						case 'select_portfolio_category':
						
							$args=array(
								'show_option_all'    => __('All','academy'),
								'id'                 => $option_id,
								'hide_empty'         => 0,
								'taxonomy'           => 'portfolio_category',
							);	
							ob_start();
							wp_dropdown_categories($args);
							$out.=ob_get_contents();
							ob_end_clean();	
							
						break;
							
						case 'checkbox' :
							
							$out.='<label for="'.$option_id.'" class="themex_form-checkbox">';
							$out.='<input type="checkbox" class="themex-input" name="'.$option_id.'" id="'.$option_id.'" '.($option['std']?'checked':'').' />';
							$out.=' '.$option['checkbox_text'].'</label>';
							
						break;
					}
					
					//shortcode option end
					$out.='<span class="themex_form-desc">'.$option['desc'].'</span>';
					$out.='</td>';
					$out.='</tr>';					
					$out.='</tbody>';
				}
			}
			
			//render child shortcode
			if(isset(self::$data[self::$id]['child_shortcode'])) {
				
				//child shortcode start
				$out.='<tbody>';
				$out.='<tr class="form-row has-child">';
				$out.='<td><a href="#" id="form-child-add" class="button-secondary">'.self::$data[self::$id]['child_shortcode']['clone_button'].'</a>';
				$out.='<div class="child-clone-rows">';
				
				//child shortcode option
				$out.='<div id="themex_cshortcode" class="hidden">'.self::$data[self::$id]['child_shortcode']['shortcode'].'</div>';
				
				//row to clone
				$out.='<div class="child-clone-row">';
				$out.='<ul class="child-clone-row-form">';				
		
				//render each option
				foreach(self::$data[self::$id]['child_shortcode']['options'] as $child_option_id=>$child_option) {
				
					//add options prefix
					$child_option_id='themex_'.$child_option_id;
					
					//shortcode option start
					$out.='<li class="child-clone-row-form-row">';
					$out.='<div class="child-clone-row-label">';
					$out.='<label>'.$child_option['label'].'</label>';
					$out.='</div>';
					$out.='<div class="child-clone-row-field">';
					
					//cloned row start
					$out	.='<span class="child-clone-row-desc">'.$child_option['desc'].'</span>';
					$out.='</div>';
					$out.='</li>';
					
					switch( $child_option['type'] ) {
					
						case 'text':
							$out.='<input type="text" class="themex_form-text themex-cinput" name="'.$child_option_id.'" id="'.$child_option_id.'" value="'.$child_option['std'].'" />';
						break;
							
						case 'textarea':
							$out.='<textarea rows="10" cols="30" name="'.$child_option_id.'" id="'.$child_option_id.'" class="themex_form-textarea themex-cinput">'.$child_option['std'].'</textarea>';
						break;
							
						case 'select':							
	
							$out.='<select name="'.$child_option_id.'" id="'.$child_option_id.'" class="themex_form-select themex-cinput">';
							
							foreach( $child_option['options'] as $value => $name ) {
								$out.='<option value="'.$value.'">'.$name.'</option>';
							}
							
							$out.='</select>';							
						
						break;
							
						case 'checkbox':
							
							$out.='<label for="'.$child_option_id.'" class="themex_form-checkbox">';
							$out.='<input type="checkbox" class="themex-cinput" name="'.$child_option_id.'" id="'.$child_option_id.'" '.($child_option['std']?'checked':'').' />';
							$out.=' '.$child_option['checkbox_text'].'</label>';				
							
						break;
						
					}
				}
				
				//shortcode option end
				$out.='</ul>';
				$out.='<a href="#" class="child-clone-row-remove">'.__('Remove','academy').'</a>';
				$out.='</div>';
				
				//cloned rows end
				$out.='</div>';
				$out.='</td>';
				$out.='</tr>';					
				$out.='</tbody>';
				
			}
			
			return $out;
		}
	}
}