<?php
/*
Plugin Name: Alpaca Forms
Plugin URI: https://github.com/mhuijser/alpaca-forms
Description: A small plugin to realize Alpaca driven forms in wordpress.
Version: 0.1
Author: mhuijser
Author Email: mhuijser@gmail.com
License:

  Copyright 2014 mhuijser (mhuijser@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

require_once ABSPATH . WPINC . '/class-phpmailer.php';

class AlpacaForms {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'Alpaca_Forms';
	const slug = 'alpaca_forms';
	
	/**
	 * Constructor
	 */
	function __construct() {
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_alpaca_forms' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_alpaca_forms' ) );
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_alpaca_forms() {
		// do not generate any output here
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_alpaca_forms() {
		// Setup localization
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// Register the shortcode [alpacaform]
		add_shortcode( 'alpacaform', array( &$this, 'render_shortcode' ) );
	
		if ( is_admin() ) {
			//this will run when in the WordPress admin
		} else {
			//this will run when on the frontend
		}

		/*
		 * TODO: Define custom functionality for your plugin here
		 *
		 * For more information: 
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'your_action_here', array( &$this, 'action_callback_method_name' ) );
		add_filter( 'your_filter_here', array( &$this, 'filter_callback_method_name' ) );    
	}

	function action_callback_method_name() {
		// TODO define your action method here
	}

	function filter_callback_method_name() {
		// TODO define your filter method here
	}

	function render_shortcode($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'from_email' => 'info@example.com',
			'from_name' => 'From Name',
			'to_email' => 'info@example.com',
			'subject' => 'Subject',
			'form' => 'declaration',
			'thankyou_message' => 'Thanks for submitting the form!'
			), $atts));
		// you can now access the attribute values using $attr1 and $attr2

		if ($_SERVER['REQUEST_METHOD'] === 'GET')  {
		  	echo '<div id="form"></div>';
			$this->load_file( self::slug . '-form-' . $form  . '-alpaca-script', '/js/' . $form . '.js', true );
		} else {
			$this->post($from_email,$from_name,$to_email,$subject);
			echo $thankyou_message;
		}
	}
  
	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {
			$this->load_file( self::slug . '-admin-script', '/js/admin.js', true );
			$this->load_file( self::slug . '-admin-style', '/css/admin.css' );
		} else {
			$this->load_file( self::slug . '-script', '/js/widget.js', true );
			$this->load_file( self::slug . '-style', '/css/widget.css' );
		} // end if/else

		// Alpaca depends on these Scripts/styles
		$this->load_external_file( self::slug . '-handlebars-script', '//cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.min.js', true );
		$this->load_external_file( self::slug . '-bootstrap-style', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css' );
		$this->load_external_file( self::slug . '-bootstrap-script', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js', true );

		// Alpaca
		$this->load_external_file( self::slug . '-basealpaca-style', '//code.cloudcms.com/alpaca/1.5.11/bootstrap/alpaca.min.css');
		$this->load_external_file( self::slug . '-basealpaca-script', '//code.cloudcms.com/alpaca/1.5.11/bootstrap/alpaca.min.js', true );
		$this->load_file( self::slug . '-customalpaca-style', '/css/alpaca.css' );

		// JQuery price format
		$this->load_file( self::slug . '-script', '/js/jquery/jquery.price_format.2.0.min.js', true );

	} // end register_scripts_and_styles
	
	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name			The ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file( $name, $file_path, $is_script = false ) {
		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__) . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, array('jquery')); //depends on jquery
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

	} // end load_file

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_external_file( $name, $file_path, $is_script = false ) {
		if( $is_script ) {
			wp_register_script( $name, $file_path, array('jquery') ); //depends on jquery
			wp_enqueue_script( $name );
		} else {
			wp_register_style( $name, $file_path );
			wp_enqueue_style( $name );
		} // end if
	} // end load_file

	/*
	 * This function takes care of the mailing all post attributes and attachment(s)
	 */
	private function post($from_email="info@example.com",$from_name="From Name",$to_email="info@example.com",$subject="Subject") {
		$email = new PHPMailer();

		$message = '
    <html>
    <head>
    <title>HTML email</title>
    </head>
    <body>
    <b>Ingevulde gegevens</b><br/>
    <table style="width:100%">';

		foreach ($_POST as $key => $value) {
			$message .= '<tr>';
			$message .= '<td style="border:1px solid black;">' . htmlspecialchars($key).'</td>
    			 <td style="border:1px solid black;">' . htmlspecialchars($value).'</td>
    			 ';
			$message .= '</tr>';

			if ($key == 'email') {
				$email->addCC($value);
			}
		}

		$message .= '
    </table>
    </body>
    </html>
  ';

		foreach ($_FILES as $key => $value) {
			$email->AddAttachment( $value['tmp_name'], $value['name']);
		}

		$email->From      = $from_email;
		$email->FromName  = $from_name;
		$email->Subject   = $subject;
		$email->Body      = $message;
		$email->AddAddress( $to_email );

		$email->isHTML(true);
		//$email->Send();
	}
  
} // end class

new AlpacaForms();

?>
