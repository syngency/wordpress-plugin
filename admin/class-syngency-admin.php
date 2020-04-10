<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and admin hooks
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
        $this->defaults = [];

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

        // Image Sizes
        $this->image_sizes = [
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large'
        ];

        // Load default templates
        $templates = ['division','model'];
        foreach ( $templates as $template ) {
            $template_path = plugin_dir_path( __FILE__ ) . 'templates/' . $template . '-default.liquid';
            if ( file_exists($template_path) ) {
                $this->defaults[$template . '_template'] = file_get_contents($template_path);
            }
        }
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
        // API SETTINGS
        
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

        // WORDPRESS SETTINGS

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
            'Gallery Image Size',
            array( $this, 'image_size_callback' ),
            'syngency-admin', 
            'wordpress_settings'
        );

        add_settings_field(
            'link_size', 
            'Gallery Images Link To',
            array( $this, 'link_size_callback' ),
            'syngency-admin', 
            'wordpress_settings'
        );

        // TEMPLATES

        add_settings_section(
            'wordpress_templates',
            'Templates',
            array( $this, 'templates_info' ),
            'syngency-admin'
        );

        add_settings_field(
            'division_template', 
            'Divisions',
            array( $this, 'division_template_callback' ),
            'syngency-admin', 
            'wordpress_templates'
        );

        add_settings_field(
            'model_template', 
            'Models',
            array( $this, 'model_template_callback' ),
            'syngency-admin', 
            'wordpress_templates'
        );

        // DIVISIONS

        add_settings_section(
            'divisions',
            'Divisions',
            array( $this, 'divisions_list' ),
            'syngency-admin'
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
        if ( is_string($input) )
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
     * Print the API Settings text
     */
    public function api_settings_info()
    {
        echo '<p>Enter the following information from your Syngency Settings below.</p>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function domain_callback()
    {
        printf(
            '<input type="text" id="domain" class="regular-text" name="syngency_options[domain]" value="%s" />',
            isset( $this->options['domain'] ) ? esc_attr( $this->options['domain']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" class="regular-text" name="syngency_options[api_key]" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }

    /** 
     * Print the WordPress Settings text
     */
    public function wordpress_settings_info()
    {
        echo '<p>Choose how Syngency content is displayed on your WordPress site.</p>';
    }

    public function measurements_callback()
    {
        echo '<select name="syngency_options[measurements][]" multiple="multiple" style="width:200px;height:250px">';
        foreach ( $this->measurements as $measurement )
        {
            echo '<option';
            if ( isset($this->options['measurements']) && in_array($measurement, $this->options['measurements']) )
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
            if ( isset($this->options['link_size']) && $value == $this->options['image_size'] )
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
            if ( isset($this->options['link_size']) && $value == $this->options['link_size'] )
            {
                echo ' selected="selected"';
            }
            echo '>' . $label . '</option>';
        }
        echo '</select>';
    }

    /** 
     * Print the Templates text
     */
    public function templates_info()
    {
        echo '<p>These templates dictate how the Division and Models pages are displayed on your site.</p>';
    }

    public function division_template_callback()
    {
        $val = (!isset($this->options['division_template']) || empty($this->options['division_template'])) ? $this->defaults['division_template'] : $this->options['division_template'];
        echo '<textarea name="syngency_options[division_template]" id="syngency-division-template">' . esc_textarea($val) . '</textarea>';
    }

    public function model_template_callback()
    {
        $val = (!isset($this->options['model_template']) || empty($this->options['model_template'])) ? $this->defaults['model_template'] : $this->options['model_template'];
        echo '<textarea name="syngency_options[model_template]" id="syngency-model-template">' . esc_textarea($val) . '</textarea>';
    }

    /**
     * Divisions
     */

    public function get_shortcode_attributes($shortcode_html)
    {
        $attributes = [];
        preg_match_all('/(\w+)\s*=\s*"(.*?)"/i', $shortcode_html, $matches);
        for ($i = 0; $i < count($matches[1]); $i++) {
            $attributes[$matches[1][$i]] = $matches[2][$i];
        }
        return $attributes;
    }

    public function divisions_list()
    {
        global $wpdb;

        $request_url = 'http://' . $this->options['domain'] . '/divisions.json';
        $request_args = array(
          'headers' => array(
            'Authorization' => 'API-Key ' . $this->options['api_key']
          ),
          'timeout' => 30
        );
        $response = wp_remote_get( $request_url, $request_args );
        if ( is_wp_error( $response ) ) {
            echo '<pre>Wordpress Error: ' . $response->get_error_message() . '</pre>';
        } else {
            if ( wp_remote_retrieve_response_code($response) == 200 ) {
                $body = wp_remote_retrieve_body($response); 
                $divisions = json_decode($body);
            } else {
                echo "<pre>Could not fetch URL: $request_url</pre>";
            }
        }

        $output = '<p>These pages are setup as Syngency divisions:</p>
                    <table style="width:500px;text-align:left">
                        <thead>
                            <tr>
                                <th>WordPress Page</th>
                                <th>Syngency Division</th>
                            </tr>
                        </thead>
                        <tbody>';

        $query = "SELECT ID, post_title, post_content, post_name FROM " . $wpdb->posts . " WHERE post_content LIKE '%[syngency%' AND post_status = 'publish' AND post_type = 'page'";
        $pages = $wpdb->get_results($query);
        foreach ( $pages as $page ) {
            
            // Register endpoint
            add_rewrite_rule( $page->post_name . '/(.+?)/?$', 'index.php?pagename=' . $page->post_name . '&model=$matches[1]', 'top' );

            $shortcode_attributes = $this->get_shortcode_attributes($page->post_content);
            $output .= '<tr><td><a href="/wp-admin/post.php?post=' . $page->ID . '&action=edit">' . $page->post_title . '</a></td>';
            foreach ( $divisions as $division ) {
                if ( $shortcode_attributes['division'] == $division->url ) {
                    $output .= '<td>' . $division->name . '</td>';
                }
            }
            $output .= '</tr>';
        }
        $output .= '</tbody></table>';
        flush_rewrite_rules();
        echo $output;
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        wp_enqueue_style('wp-codemirror');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
        $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
        wp_localize_script('jquery', 'cm_settings', $cm_settings);
        wp_enqueue_script('wp-theme-plugin-editor');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/syngency.js', array( 'jquery' ), $this->version, false );
	}

}