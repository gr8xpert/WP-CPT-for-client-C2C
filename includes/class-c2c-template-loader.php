<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class C2C_Template_Loader {

    public function __construct() {
        add_filter( 'single_template', array( $this, 'load_single_template' ) );
    }

    /**
     * Load the plugin's single property template.
     * Theme can override by placing single-c2c_property.php in the theme directory.
     */
    public function load_single_template( $template ) {
        if ( ! is_singular( 'c2c_property' ) ) {
            return $template;
        }

        // Allow theme override
        $theme_template = locate_template( 'single-c2c_property.php' );
        if ( $theme_template ) {
            return $theme_template;
        }

        $plugin_template = C2C_PROPERTIES_PATH . 'templates/single-property.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }

        return $template;
    }
}
