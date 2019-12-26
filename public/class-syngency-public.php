<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://syngency.com/add-ons/wordpress
 * @since      1.0.0
 *
 * @package    Syngency
 * @subpackage Syngency/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Syngency
 * @subpackage Syngency/public
 * @author     Ryan Marshall <ryan@syngency.com>
 */
class Syngency_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = get_option('syngency_options');
		$this->page = [];

		add_shortcode('syngency', array($this, 'router'));
	}

	public function parse_template($template,$data)
	{
		extract($data);
		include(__DIR__  . '/templates/syngency-' . $template . '.php');
	}

	public function router( $atts ) {

		// Surely WordPress provides a more elegant way of checking this
		$url = explode('/',rtrim($_SERVER['REQUEST_URI'],'/'));
		$last_segment = end($url);
		$real_permalink = explode('/',rtrim(get_permalink(),'/'));
		$real_last_segment = end($real_permalink);
		if ( $last_segment !== $real_last_segment ) {
			// Model Portfolio
			$atts['model'] = $last_segment;
			$this->get_model($atts);
		} else {
			// Division
			$this->get_division($atts);
		}

	}

	public function get_division($atts) {

		$request_url = 'http://' . $this->options['domain'] . '/divisions/' . $atts['division'] . '.json';
		$request_params = [];

		// Gender
		if ( isset($atts['gender']) ) {
			$request_params['gender'] = $atts['gender']; 
		}
		// Office
		if ( isset($atts['office_id']) ) {
			$request_params['office_id'] = $atts['office-id'];
		}
		// Add params
		if ( count($request_params) ) {
			$request_url .= '?' . http_build_query($request_params);
		}
		
		$request_args = array(
		  'headers' => array(
		    'Authorization' => 'API-Key ' . $this->options['api_key']
		  )
		);
		$response = wp_remote_request( $request_url, $request_args );

		if ( wp_remote_retrieve_response_code($response) == 200 ) {
			$body = wp_remote_retrieve_body($response);
			$models = json_decode($body);
			
			// Register endpoints
			foreach ( $models as $model ) {
				$endpoint = basename($model->url);
				add_rewrite_endpoint( $endpoint, EP_PAGES, 'ryan' );
			}
			flush_rewrite_rules();
			$output = $this->parse_template('division',array('models' => $models));
		} else {
			$output = false;
		}
		return $output;
	}

	public function get_model( $atts ) {

		if (!isset($atts['model'])) {
			return false;
		}

		$request_url = 'http://' . $this->options['domain'] . '/portfolios/' . $atts['model'] . '.json';
		$request_args = array(
		  'headers' => array(
		    'Authorization' => 'API-Key ' . $this->options['api_key']
		  )
		);
		$response = wp_remote_request( $request_url, $request_args );
		if ( wp_remote_retrieve_response_code($response) == 200 )
		{
			$body = wp_remote_retrieve_body($response);	
			$model = json_decode($body);
			$output = $this->parse_template('model',array('model' => $model));
		}
		else
		{
			$output = false;
		}
		return $output;
	
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/syngency.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/syngency.js', array( 'jquery' ), $this->version, false );

	}

}