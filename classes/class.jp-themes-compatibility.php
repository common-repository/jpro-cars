<?php
/**
 * Themes Compatibility Class
 *
 * @since 0.2
 */
if( !class_exists( 'JP_Compatibility' ) ):
	class JP_Compatibility {
		
		/**
		 * Init Function
		 *
		 * @since 0.4
		 */
		public function init() {
			$theme = wp_get_theme();
			
			// TwentyFourteen theme sidebar width fix
			if( $theme->get('Name') == 'Twenty Fourteen' ) {
				add_filter( 'wp_head', array( $this, 'TwentyFourteen' ) );
			}
			
			else

			// Customizr theme fix
			if( $theme->get('Name') == 'Customizr' ) {
				add_action( 'wp_head', array( $this, 'Customizr' ) );
			}
		}
		
		/**
		 * TwentyFourteen Theme Compatibility
		 * Sidebar Widgets Width Increment
		 *
		 * @since 0.2
		 */
		public function TwentyFourteen() {
			$content = '';
			
			$content .= '<style type="text/css">';
			$content .= '#secondary{width:225px;}';
			$content .= '</style>';
			
			echo $content;
		}
		
		/**
		 * Customizr Theme Compatibility
		 * Hide last loop title & content
		 *
		 * @since 0.6
		 */
		public function Customizr() {
			global $post;
			$con = '';
			
			$content .= '<script>';
			$content .= 'jQuery("document").ready(function($) {';
			$content .= '$("header").last().hide();';
			$content .= '$("section").last().hide();';
			$content .= '});';
			$content .= '</script>';
			
			// If jpro_cars shortcode used, echo content
			if( has_shortcode( $post->post_content, 'jpro_cars') ) {
				echo $content;
			}
		}
	}
	global $JP_Compatibility;
	$JP_Compatibility = new JP_Compatibility();
	add_action( 'init', array( $JP_Compatibility, 'init' ) );
endif;
?>