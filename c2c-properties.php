<?php
/**
 * Plugin Name: C2C Properties
 * Description: Custom property listings with shortcode grid display and single property detail pages.
 * Version:     1.0.0
 * Author:      C2C Properties
 * Text Domain: c2c-properties
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'C2C_PROPERTIES_VERSION', '1.3.0' );
define( 'C2C_PROPERTIES_PATH', plugin_dir_path( __FILE__ ) );
define( 'C2C_PROPERTIES_URL', plugin_dir_url( __FILE__ ) );

/* ── Include classes ───────────────────────────────────────────── */
require_once C2C_PROPERTIES_PATH . 'includes/class-c2c-meta-box.php';
require_once C2C_PROPERTIES_PATH . 'includes/class-c2c-shortcode.php';
require_once C2C_PROPERTIES_PATH . 'includes/class-c2c-template-loader.php';

/* ── Register Custom Post Type ─────────────────────────────────── */
add_action( 'init', 'c2c_register_property_cpt' );

function c2c_register_property_cpt() {
    $labels = array(
        'name'               => 'Properties',
        'singular_name'      => 'Property',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Property',
        'edit_item'          => 'Edit Property',
        'new_item'           => 'New Property',
        'view_item'          => 'View Property',
        'search_items'       => 'Search Properties',
        'not_found'          => 'No properties found',
        'not_found_in_trash' => 'No properties found in Trash',
        'menu_name'          => 'Properties',
    );

    $args = array(
        'labels'       => $labels,
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-building',
        'supports'     => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'      => array( 'slug' => 'dev-property' ),
        'has_archive'  => false,
    );

    register_post_type( 'c2c_property', $args );
}

/* ── Flush rewrite rules on activation / deactivation ──────────── */
register_activation_hook( __FILE__, 'c2c_properties_activate' );
register_deactivation_hook( __FILE__, 'c2c_properties_deactivate' );

function c2c_properties_activate() {
    c2c_register_property_cpt();
    flush_rewrite_rules();
}

function c2c_properties_deactivate() {
    flush_rewrite_rules();
}

/* ── Enqueue frontend CSS on single property pages ─────────────── */
add_action( 'wp_enqueue_scripts', 'c2c_enqueue_frontend_assets' );

function c2c_enqueue_frontend_assets() {
    if ( is_singular( 'c2c_property' ) ) {
        wp_enqueue_style(
            'c2c-properties',
            C2C_PROPERTIES_URL . 'assets/css/c2c-properties.css',
            array(),
            C2C_PROPERTIES_VERSION
        );
    }
}

/* ── Boot classes ──────────────────────────────────────────────── */
new C2C_Meta_Box();
new C2C_Shortcode();
new C2C_Template_Loader();
