<?php
/*
Plugin Name: Cyberchimpsextras
Plugin URI: http://cyberchimps.com
Description: Added functionality for Cyberchimps themes
Version: 1.0.0
Author: CyberChimps
Author URI: http://www.cyberchimps.com
License: GPL2

Copyright 2013  CyberChimps  (email : support@cyberchimps.com)

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

if( !class_exists( 'cyberchimpsextras' ) ) {
	class cyberchimpsextras {

		public $options;

		public $plugin_options;

		public function __construct() {

			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'wp_head', array( &$this, 'cyberchimps_head' ) );
		}

		/**
		 * Stuff to do when you activate
		 */
		public static function activate() {
		}

		/**
		 * Clean up after Deactivation
		 */
		public static function deactivate() {
		}

		/**
		 * Hook into WP admin_init
		 */
		public function admin_init() {
		
			// Check of the theme is from CyberChimps
			if( $this->is_cyberchimps() ) {
			
				// Add filter to add extra options into theme options.
				add_filter( 'cyberchimps_field_list', array( &$this, 'cyberchimpsextras_options' ), 10, 1 );
			}
		}
		
		/**
		 * Test to see if the current theme is from Cyberchimps but not Responsive or Resposnive Pro.
		 *
		 * @return bool
		 */
		public static function is_cyberchimps() {
		
			// Get the theme object.
			$theme = wp_get_theme();
			
			if( 'CyberChimps' == $theme->get( 'Author' ) && !( 'Responsive' == $theme->Name || 'Responsive Pro' == $theme->Name ) ) {
				return true;
			}
			else {
				return false;
			}
		}

		/**
		 * Add extra options into theme options.
		 *
		 * @param $fields_list
		 *
		 * @return array.
		 */
		public function cyberchimpsextras_options( $fields_list ) {

			$fields_list[] = array(
				'id'      => 'google_analytics',
				'name'    => __( 'Google Analytics', 'cyberchimps_core' ),
				'type'    => 'textarea',
				'desc'    => __( 'Copy and paste your Google Analytics code here', 'cyberchimps_core' ),
				'section' => 'cyberchimps_header_options_section',
				'heading' => 'cyberchimps_header_heading'
			);

			return $fields_list;
		}

		/**
		 * Hooked to wp_head
		 */
		public function cyberchimps_head() {

			// Test if using CyberChimps theme. If yes load from CyberChimps options else load from plugin options
			if( $this->is_cyberchimps() ) {
		
				// Get google analytics code.
				$code = cyberchimps_get_option( 'google_analytics', '' );
				
				// Check if the code is not empty then add it.
				if( $code != '' ) {
					echo '<script type="text/javascript">' . $code . '</script>';
				}
			}
		}
	}
}

/**
 * Initialize Plugin
 */

if( class_exists( 'cyberchimpsextras' ) ) {

	// Installation and uninstallation hooks
	register_activation_hook( __FILE__, array( 'cyberchimpsextras', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'cyberchimpsextras', 'deactivate' ) );

	// Initialise Class
	$cyberchimpsextras = new cyberchimpsextras();
}

if( isset( $cyberchimpsextras ) ) {
	/**
	 * Add settings link to plugin activate page
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	function plugin_settings_link( $links ) {
		$settings_link = '<a href="themes.php?page=cyberchimps-theme-options">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	$plugin = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$plugin", 'plugin_settings_link' );
}