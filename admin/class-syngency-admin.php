<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and enqueue hooks for 
 * the admin-specific stylesheet and JavaScript.
 *
 * @package    Syngency
 * @subpackage Syngency/admin
 * @author     Ryan Marshall <ryan@syngency.com>
 */

class Syngency_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	
	/**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

	}

	/**
     * Add options page
     */
    public function add_settings_page()
    {
        add_options_page(
            'Syngency Admin', 
            'Syngency', 
            'manage_options', 
            'syngency-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'syngency_option_name' );

        ?>
        <div class="wrap">
            <h2>Syngency</h2>           
            <form method="post" action="options.php">
            <?php
                settings_fields( 'syngency_option_group' );   
                do_settings_sections( 'syngency-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'syngency_option_group', // Option group
            'syngency_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'api_settings', // ID
            'API Settings', // Title
            array( $this, 'api_settings_info' ), // Callback
            'syngency-admin' // Page
        );  

        add_settings_field(
            'domain', // ID
            'Domain', // Title 
            array( $this, 'domain_callback' ), // Callback
            'syngency-admin', // Page
            'api_settings' // Section           
        );      

        add_settings_field(
            'api_key', 
            'API Key', 
            array( $this, 'api_key_callback' ), 
            'syngency-admin', 
            'api_settings'
        );

        add_settings_section(
            'wordpress_settings', // ID
            'WordPress Settings', // Title
            array( $this, 'wordpress_settings_info' ), // Callback
            'syngency-admin' // Page
        );

        /*
        add_settings_field(
            'division_page_id', 
            'Division',
            array( $this, 'division_page_callback' ),
            'syngency-admin', 
            'wordpress_settings'
        );

        add_settings_field(
            'portfolio_page_id', 
            'Portfolio', 
            array( $this, 'portfolio_page_callback' ),
            'syngency-admin', 
            'wordpress_settings'
        );
        */
        
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        foreach ( $input as $key => $value )
        {
        	$new_input[$key] = sanitize_text_field($value);
        }
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function api_settings_info()
    {
        echo '<p>Enter the following information from your Syngency Settings below.</p>';
        echo '<p>Under <strong>Settings > API</strong> in Syngency, set <em>IP Address</em> to <strong>' . $_SERVER['SERVER_ADDR'] . '</strong></p>';
    }

    /** 
     * Print the Section text
     */
    public function wordpress_settings_info()
    {
        echo '<p>Select the WordPress pages used to display your Syngency content:</p>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function domain_callback()
    {
        printf(
            '<input type="text" id="domain" name="syngency_option_name[domain]" value="%s" />',
            isset( $this->options['domain'] ) ? esc_attr( $this->options['domain']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" name="syngency_option_name[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }

    public function division_page_callback()
    {
        echo '<select name="syngency_option_name[division_page_id]">';
        foreach ( $this->pages as $page )
        {
            echo '<option value="' . $page->ID . '"';
            if ( $this->options['division_page_id'] == $page->ID ) echo ' selected="selected"';
            echo '>' . $page->post_title . '</option>';
        }
        echo '</select>';
    }

    public function portfolio_page_callback()
    {
        echo '<select name="syngency_option_name[portfolio_page_id]">';
        foreach ( $this->pages as $page )
        {
            echo '<option value="' . $page->ID . '"';
            if ( $this->options['portfolio_page_id'] == $page->ID ) echo ' selected="selected"';
            echo '>' . $page->post_title . '</option>';
        }
        echo '</select>';
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/syngency-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/syngency-admin.js', array( 'jquery' ), $this->version, false );

	}

}