<?php $price=ThemexCourse::getPlanPrice($post->ID); ?>
<div class="widget aligncenter">
	<div class="widget-title"><h1 class="nomargin aligncenter"><?php the_title(); ?></h1></div>
	<div class="plan-preview">
		<?php if(!empty($price)) { ?>
		<div class="plan-price"><?php echo $price; ?></div>
		<?php } ?>
		<div class="plan-description">	
		<?php the_content(); ?>	
		</div>
		<?php if(ThemexWoo::isActive() && !array_key_exists(get_the_ID(), ThemexCourse::getSubscriptions()) && !is_checkout() && !isset($_GET['order'])) { ?>
		<footer class="plan-footer">
			<form action="<?php echo ThemexCourse::getAction(get_permalink()); ?>" method="POST">
				<input type="hidden" name="course_action" value="subscribe" />
				<input type="hidden" name="course_id" value="0" />
				<input type="hidden" name="plan_id" value="<?php the_ID(); ?>" />
				<a href="#" class="button submit-button <?php if(!ThemexCourse::isPrimaryPlan($post->ID)) { ?>secondary<?php } ?>"><span><?php _e('Subscribe Now', 'academy'); ?></span></a>
			</form>
		</footer>
		<?php } ?>
	</div>
</div>