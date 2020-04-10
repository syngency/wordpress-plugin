<?php

use Liquid\Template;

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
 * Defines the plugin nam and  version
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
	 * @since      1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = get_option('syngency_options');

		// Register shortcode - [syngency division="division-url" office="office-url"]
		add_shortcode('syngency', array($this, 'router'));

		// Catch query vars
		add_filter('query_vars', function($vars) {
		    $vars[] = "model";
		    return $vars;
		});

	}

	/**
	 * Render Liquid template
	 *
	 * @since      1.1.0
	 * @param      array     $name       Template name
	 * @param      array     $data       Template data
	 */
	public function render_template($name,$data)
	{
		$liquid = new Template();
		try {
			$liquid->parse($this->options[$name . '_template']);
			$html = $liquid->render($data);
		} catch ( Exception $e ) {
			$html = "<pre><strong>Syngency Template Error:</strong> " . $e->getMessage() . "</pre>";
		}
		return $html;
	}

	/**
	 * Route to the respective method based on URL
	 *
	 * @since      1.1.0
	 * @param      array     $atts       Attributes passed by the shortcode
	 */
	public function router( $atts ) {

        // Render HTML output
        ob_start();
		if ( get_query_var('model') ) {
			// Model Portfolio
			$atts['model'] = get_query_var('model');
			$this->get_model($atts);
		} else {
			// Division
			$this->get_division($atts);
		}
        return ob_get_clean();

	}

	/**
	 * Get division based on attributes passed by shortcode
	 *
	 * @since      1.0.0
	 * @param      array     $atts       Attributes passed by the shortcode
	 */
	public function get_division($atts) {

		if (!isset($atts['division'])) {
			return false;
		}

		// Build request URL
        $request_url = 'http://';
        
        // Office
        if ( isset($atts['office']) ) {
            $request_url .= $atts['office'] . '.';
        }
        $request_url .= $this->options['domain'] . '/divisions/' . $atts['division'];

        // Gender
        if ( isset($atts['gender']) ) {
            $request_url .= '/' . $atts['gender']; 
        }

        $request_url .= '.json';
		
		$request_args = array(
		  'headers' => array(
		    'Authorization' => 'API-Key ' . $this->options['api_key']
		  ),
		  'timeout' => 30
		);
		$response = wp_remote_get( $request_url, $request_args );
		if ( is_wp_error( $response ) ) {
			$output = '<pre>Wordpress Error: ' . $response->get_error_message() . '</pre>';
		} else {
			switch ( wp_remote_retrieve_response_code($response) ) {
				case 200:
					$body = wp_remote_retrieve_body($response);
					$models = json_decode($body);
					foreach ( $models as $model ) {
						$model->url = get_permalink() . basename($model->url);
					}
					$output = $this->render_template('division',array('options' => $this->options, 'models' => $models));
				break;
				case 401:
				case 403:
					$output = '<pre>Invalid API Key</pre>';
				break;
				case 404:
					$output = '<pre>Invalid Syngency URL: ' . $request_url . '</pre>';
				break;
			}
		}
		echo $output;
	}

	/**
	 * Get model portfolio based on attributes passed by shortcode
	 *
	 * @since      1.0.0
	 * @param      array     $atts       Attributes passed by the shortcode
	 */
	public function get_model( $atts ) {

		if (!isset($atts['model'])) {
			return false;
		}

		// Remove gender from division attribute if present
		if ( strpos($atts['division'],'/') ) {
			$atts['division'] = explode('/',$atts['division'])[0];
		}

		$request_url = 'http://' . $this->options['domain'] . '/divisions/' . $atts['division']. '/portfolios/' . $atts['model'] . '.json';
		$request_args = array(
		  'headers' => array(
		    'Authorization' => 'API-Key ' . $this->options['api_key']
		  ),
		  'timeout' => 30
		);
		$response = wp_remote_get( $request_url, $request_args );
		if ( is_wp_error( $response ) ) {
			$output = '<pre>Wordpress Error: ' . $response->get_error_message() . '</pre>';
		} else {
			switch ( wp_remote_retrieve_response_code($response) ) {
				case 200:
					$body = wp_remote_retrieve_body($response);	
					$model = json_decode($body);
					// Set link url for galleries
					if ( isset($model->galleries) ) {
						foreach ( $model->galleries as $gallery ) {
							foreach ( $gallery->files as $file ) {
								$file->image_url = $file->{$this->options['image_size'] . '_url'};
								$file->link_url = $file->{$this->options['link_size'] . '_url'};
							}
						}
					}
					$output = $this->render_template('model',array('options' => $this->options, 'model' => $model));
				break;
				case 401:
				case 403:
					$output = '<pre>Invalid API Key</pre>';
				break;
				case 404:
					$output = '<pre>Invalid Syngency URL: ' . $request_url . '</pre>';
				break;
			}
		}
		echo $output;
	}
	
}