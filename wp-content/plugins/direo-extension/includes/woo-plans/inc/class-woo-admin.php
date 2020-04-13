<?php

/**
 * Functions hooked to the WooCommerce plugin hooks.
 *
 * @package       directorist-woocommerce-pricing-plans
 * @subpackage    adirectorist-woocommerce-pricing-plans/admin
 * @copyright     Copyright (c) 2017, aazztech
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since         1.0.0
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
/**
 * DWPP_WooCommerce_Admin Class
 *
 * @since    1.0.0
 * @access   public
 */
class DWPP_WooCommerce_Admin {
    /**
     * Add our custom product type to the "types" array.
     *
     * @since     1.0.0
     * @access    public
     *
     * @param     array     $types    Array of WooCommerce product types.
     * @return    array     $types    Array. Filtered WooCommerce product types.
     */
    public function product_type_selector( $types ){

        $types['listings_package'] = __( 'Listings Package', 'dwpp-woo-plans' );
        return $types;
    }
}
