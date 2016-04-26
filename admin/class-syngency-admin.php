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
    private $measurements;
    private $image_sizes;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->options = get_option( 'syngency_options' );

		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

        // Measurement options
        $this->measurements = [
            'Height' => __( 'Height', 'syngency' ),
            'Bust' => __( 'Bust', 'syngency' ),
            'Waist' => __( 'Waist', 'syngency' ),
            'Hip' => __( 'Hip', 'syngency' ),
            'Dress' => __( 'Dress', 'syngency' ),
            'Shoe' => __( 'Shoe', 'syngency' ),
            'Hair' => __( 'Hair', 'syngency' ),
            'Eyes' => __( 'Eyes', 'syngency' ),
            'Chest' => __( 'Chest', 'syngency' ),
            'Suit' => __( 'Suit', 'syngency' ),
            'Collar' => __( 'Collar', 'syngency' ),
            'Cup' => __( 'Cup', 'syngency' ),
            'Inseam' => __( 'Inseam', 'syngency' ),
            'Sleeve' => __( 'Sleeve', 'syngency' ),
            'Weight' => __( 'Weight', 'syngency' ),
            'Outseam' => __( 'Outseam', 'syngency' ),
            'Apparel' => __( 'Apparel', 'syngency' ),
        ];

        // Measurement defaults
        if ( !is_array($this->options['measurements']) )
        {
            $this->options['measurements'] = [
                'Height','Bust','Waist','Hip','Dress','Shoe','Hair', 'Eyes'
            ];
        }

        // Image Sizes
        $this->image_sizes = [
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large'
        ];

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
            'syngency_option_group',
            'syngency_options',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'api_settings',
            'API Settings',
            array( $this, 'api_settings_info' ),
            'syngency-admin'
        );  

        add_settings_field(
            'domain',
            'Domain',
            array( $this, 'domain_callback' ),
            'syngency-admin',
            'api_settings'      
        );      

        add_settings_field(
            'api_key', 
            'API Key', 
            array( $this, 'api_key_callback' ), 
            'syngency-admin', 
            'api_settings'
        );

        add_settings_section(
            'wordpress_settings',
            'WordPress Settings',
            array( $this, 'wordpress_settings_info' ),
            'syngency-admin' 
        );

        add_settings_field(
            'measurements', 
            'Measurements',
            array( $this, 'measurements_callback' ),
            'syngency-admin', 
            'wordpress_settings'
        );

        add_settings_field(
            'image_size', 
            'Displayed Image Size',
            array( $this, 'image_size_callback' ),
            'syngency-admin', 
            'wordpress_settings'
        );

        add_settings_field(
            'link_size', 
            'Linked Image Size',
            array( $this, 'link_size_callback' ),
            'syngency-admin', 
            'wordpress_settings'
        );
        
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if ( is_string($value) )
        {
            foreach ( $input as $key => $value )
            {
                $new_input[$key] = sanitize_text_field($value);
            }            
        }
        else
        {
            $new_input = $input;
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
        echo '<p>Choose how Syngency content is displayed on your WordPress site.</p>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function domain_callback()
    {
        printf(
            '<input type="text" id="domain" name="syngency_options[domain]" value="%s" />',
            isset( $this->options['domain'] ) ? esc_attr( $this->options['domain']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" name="syngency_options[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }

    public function measurements_callback()
    {
        echo '<select name="syngency_options[measurements][]" multiple="multiple" style="width:200px;height:250px">';
        foreach ( $this->measurements as $measurement )
        {
            echo '<option';
            if ( in_array($measurement, $this->options['measurements']) )
            {
                echo ' selected="selected"';
            }
            echo '>' . $measurement . '</option>';
        }
        echo '</select>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function image_size_callback()
    {
        echo '<select name="syngency_options[image_size]">';
        foreach ( $this->image_sizes as $value => $label )
        {
            echo '<option value="' . $value . '"';
            if ( $value == $this->options['image_size'] )
            {
                echo ' selected="selected"';
            }
            echo '>' . $label . '</option>';
        }
        echo '</select>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function link_size_callback()
    {
        echo '<select name="syngency_options[link_size]">';
        echo '<option value="">None</option>';
        foreach ( $this->image_sizes as $value => $label )
        {
            echo '<option value="' . $value . '"';
            if ( $value == $this->options['link_size'] )
            {
                echo ' selected="selected"';
            }
            echo '>' . $label . '</option>';
        }
        echo '</select>';
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/syngency.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/syngency.js', array( 'jquery' ), $this->version, false );

	}

}