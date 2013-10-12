<?php 
global $woocommerce; 
$available_methods = $woocommerce->shipping->get_available_shipping_methods();
?>
<div id="order_review" class="order_review">
	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name"><?php _e( 'Product', 'academy' ); ?></th>
				<th class="product-total"><?php _e( 'Total', 'academy' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr class="cart-subtotal">
				<th><?php _e( 'Cart Subtotal', 'academy' ); ?></th>
				<td><?php echo $woocommerce->cart->get_cart_subtotal(); ?></td>
			</tr>
			<?php if ( $woocommerce->cart->get_discounts_before_tax() ) : ?>
			<tr class="discount">
				<th><?php _e( 'Cart Discount', 'academy' ); ?></th>
				<td>-<?php echo $woocommerce->cart->get_discounts_before_tax(); ?></td>
			</tr>
			<?php endif; ?>
			<?php if ( $woocommerce->cart->needs_shipping() && $woocommerce->cart->show_shipping() ) : ?>
				<?php do_action('woocommerce_review_order_before_shipping'); ?>
				<tr class="shipping">
					<th><?php _e( 'Shipping', 'academy' ); ?></th>
					<td><?php woocommerce_get_template( 'cart/shipping-methods.php', array( 'available_methods' => $available_methods ) ); ?></td>
				</tr>
				<?php do_action('woocommerce_review_order_after_shipping'); ?>
			<?php endif; ?>
			<?php foreach ( $woocommerce->cart->get_fees() as $fee ) : ?>
				<tr class="fee fee-<?php echo $fee->id ?>">
					<th><?php echo $fee->name ?></th>
					<td>
					<?php
						if ( $woocommerce->cart->tax_display_cart == 'excl' )
							echo woocommerce_price( $fee->amount );
						else
							echo woocommerce_price( $fee->amount + $fee->tax );
					?>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php			
				if ( $woocommerce->cart->tax_display_cart == 'excl' ) {

					$taxes = $woocommerce->cart->get_formatted_taxes();
					if ( sizeof( $taxes ) > 0 ) {

						$has_compound_tax = false;

						foreach ( $taxes as $key => $tax ) {
							if ( $woocommerce->cart->tax->is_compound( $key ) ) {
								$has_compound_tax = true;
								continue;
							}
							?>
							<tr class="tax-rate tax-rate-<?php echo $key; ?>">
								<th><?php echo $woocommerce->cart->tax->get_rate_label( $key ); ?></th>
								<td><?php echo $tax; ?></td>
							</tr>
							<?php
						}

						if ( $has_compound_tax ) {
							?>
							<tr class="order-subtotal">
								<th><?php _e( 'Subtotal', 'academy' ); ?></th>
								<td><?php echo $woocommerce->cart->get_cart_subtotal( true ); ?></td>
							</tr>
							<?php
						}

						foreach ( $taxes as $key => $tax ) {
							if ( ! $woocommerce->cart->tax->is_compound( $key ) )
								continue;
							?>
							<tr class="tax-rate tax-rate-<?php echo $key; ?>">
								<th><?php echo $woocommerce->cart->tax->get_rate_label( $key ); ?></th>
								<td><?php echo $tax; ?></td>
							</tr>
							<?php
						}

					} elseif ( $woocommerce->cart->get_cart_tax() ) {
						?>
						<tr class="tax">
							<th><?php _e( 'Tax', 'academy' ); ?></th>
							<td><?php echo $woocommerce->cart->get_cart_tax(); ?></td>
						</tr>
						<?php
					}
				}
			?>
			<?php if ($woocommerce->cart->get_discounts_after_tax()) : ?>
			<tr class="discount">
				<th><?php _e( 'Order Discount', 'academy' ); ?></th>
				<td>-<?php echo $woocommerce->cart->get_discounts_after_tax(); ?></td>
			</tr>
			<?php endif; ?>
			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
			<tr class="total">
				<th><strong><?php _e( 'Order Total', 'academy' ); ?></strong></th>
				<td>
					<strong><?php echo $woocommerce->cart->get_total(); ?></strong>
					<?php
						if ( $woocommerce->cart->tax_display_cart == 'incl' ) {
							$tax_string_array = array();
							$taxes = $woocommerce->cart->get_formatted_taxes();

							if ( sizeof( $taxes ) > 0 ) {
								foreach ( $taxes as $key => $tax ) {
									$tax_string_array[] = sprintf( '%s %s', $tax, $woocommerce->cart->tax->get_rate_label( $key ) );
								}
							} elseif ( $woocommerce->cart->get_cart_tax() ) {
								$tax_string_array[] = sprintf( '%s tax', $tax );
							}

							if ( ! empty( $tax_string_array ) ) {
								?><small class="includes_tax"><?php printf( __( '(Includes %s)', 'academy' ), implode( ', ', $tax_string_array ) ); ?></small><?php
							}
						}
					?>
				</td>
			</tr>
			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
		</tfoot>
		<tbody>
			<?php
				do_action( 'woocommerce_review_order_before_cart_contents' );

				if (sizeof($woocommerce->cart->get_cart())>0) :
					foreach ($woocommerce->cart->get_cart() as $item_id => $values) :
						$_product = $values['data'];
						if ($_product->exists() && $values['quantity']>0) :
							echo '
								<tr class="' . esc_attr( apply_filters('woocommerce_checkout_table_item_class', 'checkout_table_item', $values, $item_id ) ) . '">
									<td class="product-name">' . $_product->get_title() . ' <strong class="product-quantity">&times; ' . $values['quantity'] . '</strong>' . $woocommerce->cart->get_item_data( $values ) . '</td>
									<td class="product-total">' . apply_filters( 'woocommerce_checkout_item_subtotal', $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $item_id ) . '</td>
								</tr>';
						endif;
					endforeach;
				endif;

				do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody>
	</table>
	<h2 id="order_review_heading"><?php _e('Payment Method', 'academy'); ?></h2>
	<div id="payment">
		<?php if ($woocommerce->cart->needs_payment()) : ?>
		<div class="accordion toggles-wrap payment-listing">
			<?php
				$available_gateways = $woocommerce->payment_gateways->get_available_payment_gateways();
				if ($available_gateways) :
					$counter=0;
					if (sizeof($available_gateways)) :
						$default_gateway = get_option('woocommerce_default_gateway');
						if (isset($_SESSION['_chosen_payment_method']) && isset($available_gateways[$_SESSION['_chosen_payment_method']])) :
							$available_gateways[$_SESSION['_chosen_payment_method']]->set_current();
						elseif (isset($available_gateways[$default_gateway])) :
							$available_gateways[$default_gateway]->set_current();
						else :
							current($available_gateways)->set_current();
						endif;
					endif;
					foreach ($available_gateways as $gateway ) :
						?>
						<div class="toggle-container <?php if ($gateway->chosen) echo 'expanded'; ?>">
							<label for="payment_method_<?php echo $gateway->id; ?>" class="toggle-title"><h5 class="nomargin"><?php echo $gateway->get_title(); ?></h5></label>
							<input type="radio" id="payment_method_<?php echo $gateway->id; ?>" class="hidden" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php if ($gateway->chosen) echo 'checked="checked"'; ?> />							
							<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
							<div class="toggle-content payment_method_<?php echo $gateway->id; ?>"><?php $gateway->payment_fields(); ?></div>
							<?php endif; ?>			
						</div>
						<?php
						$counter++;
					endforeach;
				endif;
			?>
		</div>
		<?php endif; ?>
		<div class="form-row place-order">
			<?php $woocommerce->nonce_field('process_checkout')?>	
			<?php if (woocommerce_get_page_id('terms')>0) : ?>
			<div class="terms">
				<input type="checkbox" class="input-checkbox" name="terms" <?php checked( isset( $_POST['terms'] ), true ); ?> id="terms" />
				<label for="terms" class="checkbox"><?php _e( 'I have read and accept the', 'academy' ); ?> <a href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('terms')) ); ?>" target="_blank"><?php _e( 'terms &amp; conditions', 'academy' ); ?></a></label>
			</div>
			<?php endif; ?>
			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
			<input type="submit" class="button alt place-order-button" name="woocommerce_checkout_place_order" id="place_order" value="<?php echo apply_filters('woocommerce_order_button_text', __( 'Place order', 'academy' )); ?>" />
			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
		</div>
	</div>
</div>