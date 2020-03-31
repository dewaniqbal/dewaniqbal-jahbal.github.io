<?php

/**
 * Load more ajax handler for "Recent Posts Grid" element
 *
 * @return void
 */
function auxels_ajax_handler_element_load_more(){
    if( ! defined( 'AUXIN_INC' ) ){
        wp_send_json_success("Phlox theme is required.");
    }
    if( empty( $_POST["handler"] ) ){
        wp_send_json_success("Please specify a handler.");
    }
    // Direct call is not alloweded
    if( empty( $_POST['action'] ) ){
        wp_send_json_error( __( 'Ajax action not found.', 'auxin-elements' ) );
    }
    if( empty( $_POST['args'] ) ){
        wp_send_json_error( __( 'Ajax args is required.', 'auxin-elements' ) );
    }
    // Authorize the call
    if( ! wp_verify_nonce( $_POST['nonce'], 'auxin_front_load_more' ) ){
        wp_send_json_error( __( 'Authorization failed.', 'auxin-elements' ) );
    }

    $ajax_args      = $_POST['args'];
    $element_markup = '';

    // include the required resources
    require_once( AUXELS_INC_DIR . '/general-functions.php' );
    require_once( THEME_DIR . AUXIN_INC . 'include/functions.php' );
    require_once( THEME_DIR . AUXIN_INC . 'include/templates/templates-post.php' );

    // take required actions based on custom handler (element base name)
    switch( $_POST['handler'] ) {

        case 'aux_recent_posts':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-grid-carousel.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_callback( $ajax_args );
            break;

        case 'aux_recent_posts_land_style':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-land-style.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_land_style_callback( $ajax_args );
            break;

        case 'aux_recent_posts_masonry':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-masonry.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_masonry_callback( $ajax_args );
            break;

        case 'aux_recent_posts_tiles':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-tiles.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_tiles_callback( $ajax_args );
            break;

        case 'aux_recent_posts_timeline':
            require_once( AUXELS_INC_DIR . '/elements/recent-posts-timeline.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_posts_timeline_callback( $ajax_args );
            break;

        case 'aux_recent_news':
            require_once( AUXNEW_INC_DIR . '/elements/recent-news.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_news_callback( $ajax_args );
            break;

        case 'aux_recent_news_big_grid':
            require_once( AUXNEW_INC_DIR . '/elements/recent-news-big-grid.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_news_big_grid_callback( $ajax_args );
            break;

        case 'aux_recent_portfolios_grid':
            require_once( AUXPFO_INC_DIR . '/elements/recent-portfolios.php'    );

            // Get the element markup
            $element_markup = auxin_widget_recent_portfolios_grid_callback( $ajax_args );
            break;

        case 'aux_flexible_recent_posts':
            require_once( AUXPRO_INC_DIR . '/elements/flexible-recent-posts.php'    );

            // Get the element markup
            $element_markup = auxin_widget_flexible_recent_posts_callback( $ajax_args );
            break;

        default:
            wp_send_json_error( __( 'Not a valid handler.', 'auxin-elements' ) );
            break;
    }

    // if the output is empty
    if( empty( $element_markup ) ){
        wp_send_json_error( __( 'No data received.', 'auxin-elements' ) );
    }

    wp_send_json_success( $element_markup );
}

add_action( 'wp_ajax_load_more_element', 'auxels_ajax_handler_element_load_more' );
add_action( 'wp_ajax_nopriv_load_more_element', 'auxels_ajax_handler_element_load_more' );

/**
 * Remove Product from Cart via Ajax
 *
 * @return void
 */
function auxels_remove_product_from_cart() {

	if ( !class_exists( 'WooCommerce' ) )
		return;

	global $woocommerce;

	try {

		$nonce 			= $_POST['verify_nonce'];
		$id 			= $_POST['product_id'];

	    if( ! isset( $_POST['product_id'] ) || ! wp_verify_nonce( $nonce, 'remove_cart-' . $id ) ){
	    	wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('Verification failed!', 'auxin-elements') ) );
	    }

		$cart 			= $woocommerce->cart;
		$cart_id 		= $cart->generate_cart_id($id);
		$cart_item_id 	= $cart->find_product_in_cart($cart_id);

		if( $cart_item_id ) {
			$cart->set_quantity( $cart_item_id, 0 );
		}

		$cart->calculate_totals();

		$response = array(
			'total'		=> 	wc_format_decimal( $cart->cart_contents_total, 2 ),
			'count'		=> 	$cart->cart_contents_count,
			'empty'		=>	sprintf( '<div class="aux-card-box">%s</div>',  __( 'Your cart is currently empty.', 'auxin-elements' ) ),
			'notif'		=>	sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-message">%s</div>',  __('Item has been removed from your shopping cart.', 'auxin-elements') )
		);

		wp_send_json_success( $response );

    } catch (Exception $e) {
        wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('An Error Occurred!', 'auxin-elements') ) );
    }

}
add_action( 'wp_ajax_auxels_remove_from_cart', 'auxels_remove_product_from_cart' );
add_action( 'wp_ajax_nopriv_auxels_remove_from_cart', 'auxels_remove_product_from_cart' );


/**
 * Add to Cart via Ajax
 */
function auxels_add_product_to_cart() {

	if ( ! class_exists( 'WooCommerce' ) )
		return;

	global $woocommerce;

	try {

		$product_id        = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : '';
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );
		$verify_nonce      = isset( $_POST['verify_nonce'] ) ? $_POST['verify_nonce'] : '';
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

		if( empty( $product_id ) || ! wp_verify_nonce( $verify_nonce, 'aux_add_to_cart-' . $product_id ) ){
			wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('Verification failed!', 'auxin-elements') ) );
		} else {
			// Add item to cart
			if( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity ) ) {
				$args  = isset( $_POST['args'] ) ? $_POST['args'] : array(
		            'title'          => '',
		            'css_class'      => '',
		            'dropdown_class' => '',
		            'color_class'    => 'aux-black',
		            'action_on'      => 'click',
		            'cart_url'       => '#',
		            'dropdown_skin'  => '',
		        );
				$items = auxin_get_cart_items( $args );
				$count = $woocommerce->cart->cart_contents_count;
				$total = auxin_get_cart_basket( $args, $count );

				$data  = array(
					'items' => $items,
					'total' => $total,
					'notif' => sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-message"><a href="%s" class="button wc-forward">%s</a> "%s" %s</div>', esc_url( wc_get_cart_url() ) , __( 'View cart', 'auxin-elements' ), get_the_title( $product_id ) , __('has been added to your cart.', 'auxin-elements') )
				);
				// Send json success
				wp_send_json_success( $data );
			} else {
				wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('Sorry, this product cannot be purchased.', 'auxin-elements') ) );
			}

		}

    } catch( Exception $e ){
        wp_send_json_error( sprintf( '<div class="aux-woocommerce-ajax-notification woocommerce-error">%s</div>',  __('An Error Occurred!', 'auxin-elements') ) );
    }

}
add_action( 'wp_ajax_auxels_add_to_cart', 'auxels_add_product_to_cart' );
add_action( 'wp_ajax_nopriv_auxels_add_to_cart', 'auxels_add_product_to_cart' );



/**
 * Ajax Search Handler
 *
 * @return void
 */
function auxels_ajax_search() {

    $search_post_type = post_type_exists( 'product' ) ? 'product' : 'post';
    $second_post_type = 'post';

    $s   = trim( sanitize_text_field( $_GET['s'] ) );
    if ( "0" == $cat = trim( sanitize_text_field( $_GET['cat'] ) ) ) {
        $cat = '';
    }

    // Dom Wrapper
    $empty_wrapper_start = "<div class='aux-empty-result'>";
    $empty_wrapper_end = "</div>";

    // Emtpy Result Message and Dom - when main post type is empty or both post types or empty
    $empty_result_message = esc_html__( "No Result! %s Nothing Found For: %s From %s", 'auxin-elements' );
    $both_empty_result_message = esc_html__( "No Result! %s Nothing Found For: %s From %s And %s", 'auxin-elements');

    $empty_result_dom = $empty_wrapper_start.
                            '<span class="aux-empty-result">' .
                            sprintf(
                                $empty_result_message,
                                '</br>',
                                '<span class="aux-search-phrase">"' .$s. '"</span><br>',
                                '<span class="aux-post-type">' .$search_post_type. '</span>'
                            ).
                            "</span>".
                        $empty_wrapper_end;

    $both_empty_result_dom = $empty_wrapper_start.
                                "<span class='aux-empty-result'>".
                                sprintf(
                                    $both_empty_result_message,
                                    '</br>',
                                    '<span class="aux-search-phrase">"' .$s. '"</span><br>',
                                    '<span class="aux-post-type">' .$search_post_type. '</span>',
                                    '<span class="aux-post-type">' .$second_post_type. '</span>'
                                ).
                                "</span>".
                            $empty_wrapper_end;


    // Start Searching First Post type
    $search_instance = new Auxels_Search_Post_Type($s,$cat,$search_post_type,'');
    if ( $search_post_type == 'product')
        $first_result = $search_instance->search_products();
    else if ($search_post_type == 'portfolio' )
        $first_result = $search_instance->search_portfolio();
    else
        $first_result = $search_instance->search_general_post_types();

    if ( empty( $first_result ) )
        $first_result = $empty_result_dom;

    if ( ! auxin_get_option( 'fullscreen_second_search_result', false )) {
        echo $first_result;
        die();
    }

    // Start Searching second post type
    $search_instance->set_query_args(array( 'post_type' => $second_post_type));
    if ( $second_post_type == 'product')
        $second_result = $search_instance->search_products();
    else if ($second_post_type == 'portfolio' )
        $second_result = $search_instance->search_portfolio();
    else
        $second_result = $search_instance->search_general_post_types();

    if ( ! empty( $second_result ) ) {

        $first_result .= "<span class='aux-other-search-result-label'>".esc_html__( 'From', 'auxin-elements' ).' '.$second_post_type."</span>";
        $first_result .= "<div class='aux-other-search-result'>";
        $first_result .= $second_result."</div>";

    } else if ( $first_result == $empty_result_dom) {
        $first_result = $both_empty_result_dom;
    }

    echo $first_result;
    die();
}

add_action( 'wp_ajax_auxin_ajax_search', 'auxels_ajax_search' );
add_action( 'wp_ajax_nopriv_auxin_ajax_search', 'auxels_ajax_search');