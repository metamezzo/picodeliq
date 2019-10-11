<?php
/**
 * Picodeliq Class
 *
 * @author     PICODELIQ
 * @since      0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Picodeliq' ) ) :

    class Picodeliq {

        // Setup class
        public function __construct() {

            // Actions & filters
            add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
            add_action( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
            add_action( 'customize_register', array( $this, 'customize_register' ) );

            // Add prefooter section
            // add_action( 'et_after_main_content', array( $this, 'prefooter_section' ) );

            // Add theme shortcodes
            add_shortcode( 'curryear', array( $this, 'current_year' ) );

        }

        // Setup theme textdomain
        public function theme_setup() {

            load_child_theme_textdomain( 'picodeliq', get_stylesheet_directory() . '/languages' );

        }

        // Remove jQuery migrate
        // ref: https://dotlayer.com/what-is-migrate-js-why-and-how-to-remove-jquery-migrate-from-wordpress/
        public function remove_jquery_migrate($scripts) {
            if ( !is_admin() && isset($scripts->registered['jquery']) ) {
                $script = $scripts->registered['jquery'];
                
                if ( $script->deps ) { // Check whether the script has any dependencies
                    $script->deps = array_diff($script->deps, array(
                        'jquery-migrate'
                    ));
                }
            }
        }

        // Enqueue scripts and styles
        public function enqueue_assets() { 

            // Enqueue stylesheets starting with parent Divi styles
            wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
            wp_enqueue_style( 'pico-style', get_stylesheet_directory_uri().'/dist/css/main.css' );
        
            // Register theme scripts
            wp_register_script( 'pico-script', get_stylesheet_directory_uri().'/dist/js/main.bundle.js', array( 'jquery', 'divi-custom-script' ), null, true );

            // Enqueue theme scripts
            wp_enqueue_script( 'pico-script' );

        }

        // Current year shortcode
        public function current_year() {

            return date( 'Y' );

        }

        // Theme customizer options
        public function customize_register( $wp_customize ) {

            // PICODELIQ Theme Options section
            $wp_customize->add_section( 'theme_options',
                array(
                    'title'       => __('PICODELIQ Theme Options', 'picodeliq'), // Visible title of section
                    'priority'    => 200, // 35 ~ after Site Identity, 100 ~ after Menu, 110 ~ after Widgets, 200 ~ after Additional CSS
                    'capability'  => 'edit_theme_options', //Capability needed to tweak
                    'description' => __( 'Customize PICODELIQ theme options', 'picodeliq' ), //Descriptive tooltip
                )
            );

            // PICODELIQ LinkedIn profile settings
            $wp_customize->add_setting( 'linkedin_url', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
                array(
                    'default'           => '#', //Default setting/value to save
                    'type'              => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
                    'capability'        => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
                    'transport'         => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
                    'sanitize_callback' => 'esc_url_raw',
                )
            );

            // PICODELIQ LinkedIn profile control
            $wp_customize->add_control( 'linkedin_url',
                array(
                    'label'     => __( 'LinkedIn profile', 'picodeliq' ),
                    'section'   => 'theme_options',
                    'settings'  => 'linkedin_url',
                    'type'      => 'text',
                )
            );

        }

        // Pre-footer section - ID == nnn
        // public function prefooter_section() {

        //     echo do_shortcode( '[et_pb_section global_module="nnn"][/et_pb_section]' );

        // }
        
    } // class Picodeliq

endif;

return new Picodeliq();
