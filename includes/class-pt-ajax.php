<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'PT_AJAX' ) ) {
    /**
     * AJAX
     *
     * AJAX related functions
     *
     * @package ProductTable/Classes
     * @since   1.0.0
     */
    class PT_AJAX
    {
        /**
         * Construct
         *
         * @since 1.0.0
         */
        public function __construct()
        {
            add_action( 'wp_ajax_datatable', array( $this, 'data' ) );
            add_action( 'wp_ajax_nopriv_datatable', array( $this, 'data' ) );
        }
        
        /**
         * Gets table data via AJAX
         *
         * @since 1.0.0
         * @return string JSON string of table data
         */
        public function data()
        {
            // Get categories
            $categories = sanitize_text_field( $_REQUEST['categories'] );
            // Get products
            
            if ( !empty($categories) ) {
                $args = array(
                    'limit'    => -1,
                    'category' => explode( ',', $categories ),
                );
            } else {
                $args = array(
                    'limit' => -1,
                );
            }
            
            $products = wc_get_products( $args );
            // Create array of products to return
            $data = array();
            $nestedData = array();
            
            if ( !empty($products) ) {
                $i = 0;
                if ( !empty($products) ) {
                    foreach ( $products as $product ) {
                        $product_id = $product->get_id();
                        $nestedData[$i][0] = $product->get_image( 'woocommerce_thumbnail' );
                        $nestedData[$i][1] = $product_id;
                        $nestedData[$i][2] = $product->get_sku();
                        $nestedData[$i][3] = $product->get_title();
                        $categories = '';
                        foreach ( $product->get_category_ids() as $category_id ) {
                            $categories .= get_term( $category_id )->name . ', ';
                        }
                        $nestedData[$i][4] = rtrim( $categories, ', ' );
                        $nestedData[$i][5] = $product->get_price_html();
                        $i = $i + 1;
                    }
                }
                // Encode json
                $json_data = array(
                    'recordsTotal' => count( $products ),
                    'data'         => $nestedData,
                );
                echo  json_encode( $json_data ) ;
            } else {
                // Encode json
                $json_data = array(
                    'data' => array(),
                );
                echo  json_encode( $json_data ) ;
            }
            
            exit;
        }
        
        /**
         * Find a variation from selected options via AJAX
         *
         * @since 1.0.0
         * @return string JSON string of variation data
         */
        public function variation_lookup()
        {
            // Available in premium
            echo  0 ;
            exit;
        }
        
        /**
         * Adds to cart via AJAX
         *
         * @since 1.0.0
         * @return string JSON string of add to cart data
         */
        public function add_to_cart()
        {
            // Available in premium
            echo  0 ;
            exit;
        }
    
    }
    new PT_AJAX();
}
