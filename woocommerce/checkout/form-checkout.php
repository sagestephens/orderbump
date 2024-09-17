<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	
	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
	
	<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
	
	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

<div id="order_review" class="woocommerce-checkout-review-order">
    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
</div>

<button id="custom-checkout-button" class="button alt">
    Proceed to Checkout
</button>

<!-- Modal for Upsell -->
<div id="upsell-modal" style="display:none;">
    <div class="modal-content">
        <p>Would you like to add an upsell product?</p>

        <!-- Upsell Products Section -->
        <div class="upsell-products">
            <?php
            $category_id = 140; // Replace with your upsell category ID

            // Query the products
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 3,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $category_id,
                        'operator' => 'IN',
                    ),
                ),
            );

            $loop = new WP_Query($args);
            if ($loop->have_posts()) : ?>
                <ul>
                    <?php while ($loop->have_posts()) : $loop->the_post(); global $product; ?>
                        <li class="upsell-product">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo woocommerce_get_product_thumbnail(); ?>
                                <h4><?php the_title(); ?></h4>
                            </a>
                            <p><?php echo $product->get_price_html(); ?></p>
                            <button class="add-to-cart-button" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                Add to Cart
                            </button>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; wp_reset_postdata(); ?>
        </div>

        <!-- Timer and No Thanks Button -->
        <button id="no-thanks-button">No Thanks</button>
        <p id="timer">This offer expires in <span id="countdown">10</span> seconds...</p>
    </div>
</div>


<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
