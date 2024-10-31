<?php

/**
 * Plugin Name: Product Table for WooCommerce
 * Description: Product table for your WooCommerce store. Create tables & price lists easily!
 * Version: 1.0.3
 * Author: Veyroo
 * Author URI: https://veyroo.com
 * Text Domain: pt-product-table
 * Domain Path: /languages/
 *
 * @package ProductTable
 * @version 1.0.3
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !function_exists( 'pt_fs' ) ) {
    // Create a helper function for easy SDK access.
    function pt_fs()
    {
        global  $pt_fs ;
        
        if ( !isset( $pt_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $pt_fs = fs_dynamic_init( array(
                'id'             => '3664',
                'slug'           => 'product-table',
                'type'           => 'plugin',
                'public_key'     => 'pk_298368c31e2d51b641b7036b65a83',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug' => 'pt-product-table',
            ),
                'is_live'        => true,
            ) );
        }
        
        return $pt_fs;
    }
    
    // Init Freemius.
    pt_fs();
    // Signal that SDK was initiated.
    do_action( 'pt_fs_loaded' );
}


if ( !class_exists( 'PT_Product_Table' ) ) {
    /**
     * Main class
     *
     * @version 1.0.3
     * @since 1.0.0
     */
    class PT_Product_Table
    {
        /**
         * Construct
         *
         * @since 1.0.0
         */
        public function __construct()
        {
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-pt-dashboard.php';
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-pt-ajax.php';
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-pt-shortcode.php';
        }
    
    }
    new PT_Product_Table();
}
