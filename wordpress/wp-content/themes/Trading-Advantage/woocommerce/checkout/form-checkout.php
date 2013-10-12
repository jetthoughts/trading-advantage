<?php
if (!defined('ABSPATH')) {
	exit;
}

global $woocommerce;

$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() );
$woocommerce_checkout = $woocommerce->checkout();

$product=reset($woocommerce->cart->get_cart());
$item=ThemexWoo::getRelatedItem($product['product_id']);

$GLOBALS['query']=new WP_Query();
if($item!==false) {
	$GLOBALS['query']=new WP_Query(array(
		'post_type'=>$item->post_type,
		'post__in'=>array($item->ID),
	));
}

if($GLOBALS['query']->have_posts()) {
?>
<?php do_action('woocommerce_before_checkout_form'); ?>
<form name="checkout" method="post" class="checkout course-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">
	<div class="threecol column">
	<?php	
	$GLOBALS['query']->the_post();
	get_template_part('loop', $item->post_type);
	?>
	</div>
	<?php if ( sizeof( $woocommerce_checkout->checkout_fields ) > 0 ) : ?>
		<?php do_action( 'woocommerce_checkout_before_customer_details'); ?>
		<div class="fourcol column" class="order_details" id="customer_details">
			<?php $woocommerce->show_messages(); ?>
			<?php do_action('woocommerce_checkout_billing'); ?>
			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
			<?php if (woocommerce_get_page_id('terms')>0) : ?>
			<p class="form-row terms">
				<input type="checkbox" class="input-checkbox" name="terms" <?php if (isset($_POST['terms'])) echo 'checked="checked"'; ?> id="terms" />
				<label for="terms" class="checkbox"><?php _e('I accept the', 'academy'); ?> <a href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('terms')) ); ?>" target="_blank"><?php _e('terms &amp; conditions', 'academy'); ?></a></label>
			</p>
			<?php endif; ?>
			<div class="formatted-form">
				<?php do_action('woocommerce_before_order_notes', $checkout); ?>
				<?php do_action('woocommerce_after_order_notes', $checkout); ?>
			</div>
			<?php if ( $checkout->enable_guest_checkout ) : ?>
			<input id="createaccount" type="hidden" name="createaccount" value="1" />
			<?php endif; ?>
			<input id="shiptobilling-checkbox" type="hidden" name="shiptobilling" value="1" />	
			<input id="createaccount" type="hidden" name="createaccount" value="1">
			<input type="hidden" name="register_url" value="<?php echo ThemexUser::$data['register_page_url']; ?>" />
			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
		</div>
		<?php do_action( 'woocommerce_checkout_after_customer_details'); ?>		
	<?php endif; ?>
	<div class="fivecol column last">
		<?php do_action('woocommerce_checkout_order_review'); ?>
	</div>
	<div class="clear"></div>
</form>
<?php do_action('woocommerce_after_checkout_form'); ?>
<?php 
} else if(file_exists(ABSPATH.'wp-content/plugins/woocommerce/templates/checkout/form-checkout.php')) {
	include(ABSPATH.'wp-content/plugins/woocommerce/templates/checkout/form-checkout.php');
}
?>