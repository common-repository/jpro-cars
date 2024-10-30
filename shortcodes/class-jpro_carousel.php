<?php
if( ! defined( 'ABSPATH' ) ) exit;
/*---------------------------------------------------------------------
 * JPRO CAROUSEL - SHORTCODE | Rewritten @since v0.7
 *--------------------------------------------------------------------*/
if( ! class_exists( 'JPROCUSTOM_SHORTCODE_CAROUSEL' ) ) {
	
	class JPROCUSTOM_SHORTCODE_CAROUSEL {
		
		function __construct() {
			
			add_shortcode( 'jpro_cars_slider', array( $this, 'shortcode' ) );
			
		}

		/**
		 * Shortcode Initialization
		 *
		 * @rewritten
		 * @since 0.7
		 */
		function shortcode( $atts, $content ) {
			ob_start();
			
			$html  = do_shortcode( $content );
			$html .= self::render( $atts );
			$html  = ob_get_contents();
			
			ob_end_clean();
			
			return $html;
		}
		
		/**
		 * Render Output
		 *
		 * @rewritten
		 * @since 0.7
		 */
		static private function render( $atts ) {
			global $post, $JP_Cars; 
			
			if( ! empty( $atts ) ) {
				extract( $atts );
			}
			
			// Load Posts Number
			if( empty( $load_cars ) ) {
				$load_cars = 9;
			}
			
			if( empty( $items ) ) {
				$items = 3;
			}
			
			if( $make ) {
				$make  = strtolower( $make );
			}
			
			if( $model ) {
				$model = strtolower( $model );
			}
			
			// If car make specified in shortcode
			$tax_query['car-make'] = '';
			if( ! empty( $make ) ) {
				$tax_query['car-make'] = array(
					'taxonomy' => 'car-model',
					'field' => 'slug',
					'terms' => $make
				);
			}
			
			// If car model specified in shortcode
			$tax_query['car-model'] = '';
			if( ! empty( $model ) ) {
				$tax_query['car-model'] = array(
					'taxonomy' => 'car-model',
					'field' => 'slug',
					'terms' => $model
				);
			}
			
			// If car equipment specified in shortcode
			$tax_query['car-equipment'] = '';
			if( ! empty( $equipment ) ) {
				$tax_query['car-equipment'] = array(
					'taxonomy' => 'car-equipment',
					'field' => 'slug',
					'terms' => $equipment
				);
			}
			
			$args = array(
				'post_type' => 'car-classifieds',
				'post_status' => 'publish',
				'posts_per_page' => $load_cars,
				'tax_query' => array( $tax_query['car-make'], $tax_query['car-model'], $tax_query['car-equipment'] )
			);
			
			$my_query = null;
			$my_query = new WP_Query( $args );
			
			if( $my_query->have_posts() ) {
				
				if( isset( $title ) ) {
					echo '<h2 class="jp-shortcode carousel-title">'.$title.'</h2>';
				}
				
				echo '<div class="owl-carousel jp-shortcode">'; // Carousel container start
				
				while( $my_query->have_posts() ): $my_query->the_post();
					
					// Get all car images
					$image 		= get_post_meta( $post->ID, '_car_images', true );
					$price		= get_post_meta( $post->ID, '_car_price', true );
					$currency 	= get_post_meta( $post->ID, '_car_currency', true );
					$warranty	= get_post_meta( $post->ID, '_car_warranty', true );
					
					// If multiple images per car found, select first one
					if( preg_match( "/,/", $image ) ) {
						$image = explode( ',', $image );
						$image = $image[0];
					}
					
					// Add SSL support
					if( is_ssl() ) {
						$image = str_replace( 'http://', 'https://', $image );
					}
					
					echo '<div>';
					echo '<a href="'.get_the_permalink().'" title="'.get_the_title().'"><img src="'. $image .'" alt="'. get_the_title() .'"></a>';

					if( !empty( $price ) ) {
						echo '<span class="jp-shortcode car-price">'.$JP_Cars->get_price( $price ).'</span>';
					}
					
					if( $warranty == 'yes' ) {
						echo '<div class="warranty">'.__( 'warranty', 'jprocars' ).'</div>';
					}
					
					echo '</div>';
					
				endwhile;
				
				echo '</div>'; // Carousel container end
				
				// Call Owl Carousel Init Function
				add_action( 'wp_footer', jp_owl_carousel_init( $items ) );
			} 
			
			wp_reset_query();
		}
	}
	new JPROCUSTOM_SHORTCODE_CAROUSEL;
}