<?php
//Widget Name: Authors Widget

class themex_authors_widget extends WP_Widget {

	//Widget Setup
	function __construct() {
		$widget_ops = array('classname' => 'widget-users', 'description' => __( 'Selected blog authors', 'academy' ) );
		parent::__construct('blog-authors', __('Blog Authors','academy'), $widget_ops);
		$this->alt_option_name = 'widget_blog_authors';
	}

	//Widget view
	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		
		if($instance['number']=='') $instance['number']='6';
		if($instance['order']=='') $instance['order']='registered';
		
		$orderdir='DESC';
		if($instance['order']=='display_name') {
			$orderdir='ASC';
		}
		
		$out=$before_widget;
		
		//show title
		$title=apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Blog Authors', 'academy' ) : $instance['title'], $instance, $this->id_base );
		$out.=$before_title.$title.$after_title;

	
			$counter=0;
			$users=get_users(array(
				'number' => $instance['number'],
				'orderby' => $instance['order'],
				'order' => $orderdir,
			));
			
			$out.='<div class="users-listing">';
			foreach($users as $user) {
				$counter++;
				
				$out.='<div class="user-image ';
				if($counter==3){
					$out.='last';
				}
				$out.='"><div class="bordered-image">';
				$out.='<a title="'.ThemexUser::getFullName($user).'" href="'.get_author_posts_url($user->ID).'">'.get_avatar( $user->ID ).'</a>';
				$out.='</div></div>';
				
				if($counter==3) {
					$out.='<div class="clear"></div>';
					$counter=0;
				}
			}
			$out.='</div>';
		
		$out.=$after_widget;
		
		echo $out;
	}

	//Update widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['number'] = intval($new_instance['number']);
		$instance['order'] = strip_tags($new_instance['order']);
		return $instance;
	}
	
	//Widget form
	function form( $instance ) {
		//Defaults
		$defaults = array(
			'number'=>'6',
			'order'=>'registered',
			'title' => '',
		);
		$instance = wp_parse_args( (array)$instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'academy'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Authors Number', 'academy'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order By', 'academy'); ?>:</label>
			<select class="widefat" type="order" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
				<option value="registered" <?php if($instance['order']=='registered') echo 'selected="selected"'; ?>><?php _e('Date', 'academy') ?></option>
				<option value="display_name" <?php if($instance['order']=='display_name') echo 'selected="selected"'; ?>><?php _e('Name', 'academy') ?></option>
				<option value="post_count" <?php if($instance['order']=='post_count') echo 'selected="selected"'; ?>><?php _e('Activity', 'academy') ?></option>
			</select>
		</p>
	<?php
	}
}