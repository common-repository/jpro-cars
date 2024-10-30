<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add Class to Body
 *
 * @since 0.7
 */
add_action( 'body_class', 'jpro_body_class' );
function jpro_body_class( $classes ) {
	$Settings = new JP_Settings();
	$settings = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
	if( is_page( $settings['cc_page_id'] ) ) {
		$classes[] = 'jprocars';
	}
	else	
	if( is_single() ) {
		$classes[] = 'jprocars-single';
	}
	return $classes;
}

/**
 * Load Scripts on Frontend
 * ========================
 * @since 0.1
 */
add_action( 'wp_enqueue_scripts', 'jpro_enqueue_frontend_scripts' );
function jpro_enqueue_frontend_scripts() {
	global $post;
	
	$Settings = new JP_Settings();
	$settings = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
	
	/**
	 * Check if jQuery is loaded, if not enqueue it
	 * ============================================
	 * @since 0.3
	 */
	if( ! wp_script_is( 'jquery' ) ) {
		wp_enqueue_script( 'jquery' );
	}
	
	/**
	 * Enqueue Below Scripts & Stylesheets Only on JPro Cars Pages
	 * Don't Enqueue Anything if on Admin Dashboard
	 */
	if( ! is_admin() || $post->post_type == 'car-classifieds' ) {
		
		if( ( ! wp_style_is( 'bootstrap', 'queue' ) ) && ( ! wp_style_is( 'bootstrap', 'done' ) ) ) {
			
			// Bootstrap CSS
			wp_register_style( 'bootstrap', JPRO_CAR_URI . 'assets/css/bootstrap.css', array(), '3.2.0' );
			wp_enqueue_style( 'bootstrap' );
			
			// Bootstrap jQuery
			wp_register_script( 'bootstrap', JPRO_CAR_URI . 'assets/js/bootstrap.min.js', array(), '3.3.0', true ); // In Footer
			wp_enqueue_script( 'bootstrap' );
			
		}
		
		// Enqueue Main CSS File
		wp_register_style( 'car-classifieds', JPRO_CAR_URI . 'assets/css/car-classifieds.css', array() );
		wp_enqueue_style( 'car-classifieds' );
		
		// Enqueue FontAwesome if Needed
		if( ( ! wp_style_is( 'fontawesome', 'queue' ) ) && ( ! wp_style_is( 'fontawesome', 'done' ) ) ) {
			wp_register_style( 'font-awesome', JPRO_CAR_URI . 'assets/css/font-awesome.min.css', array(), '4.2.0' );
			wp_enqueue_style( 'font-awesome' );
		}
		
		### Magnific Popup CSS ###
		wp_register_style( 'magnific-popup', JPRO_CAR_URI . 'assets/css/magnific-popup.css', array(), '1.0.0' );
		wp_enqueue_style( 'magnific-popup' );
		 
		/**
		 * Edit & Add Classifieds Pages
		 * ============================
		 * Enqueue jQuery UI Sortable - @since 0.2
		 * Enqueue jQuery Validation  - @since 0.7
		 */
		if( isset( $_GET['jp'] ) && $_GET['jp'] == 'edit-classified' && isset( $_GET['id'] ) || isset( $_GET['jp'] ) && $_GET['jp'] == 'add-new-classified' ) {
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jprocars-validation', JPRO_CAR_URI . 'assets/js/validation.js' );
		}
		
		// Range Slider CSS
		wp_register_style( 'rangeSlider', JPRO_CAR_URI . 'assets/css/ion.rangeSlider.css', array(), '2.0.3' );
		wp_enqueue_style( 'rangeSlider' );
		 
		// Range Slider Flat Skin CSS
		wp_register_style( 'rangeSliderSkin', JPRO_CAR_URI . 'assets/css/ion.rangeSlider.skinFlat.css', array(), '2.0.3' );
		wp_enqueue_style( 'rangeSliderSkin' );
		
		// Range Slider jQuery
		wp_register_script( 'rangeSlider', JPRO_CAR_URI . 'assets/js/ion.rangeSlider.min.js', array(), '2.0.6', true ); // In Footer
		wp_enqueue_script( 'rangeSlider' );
	
		// Magnific Popup jQuery
		wp_register_script( 'magnific-popup', JPRO_CAR_URI . 'assets/js/jquery.magnific-popup.min.js', array(), '1.0.0', true ); // In Footer
		wp_enqueue_script( 'magnific-popup' );
	}
	
	/**
	 * Enqueue Owl Carousel Scripts Only if "jpro_cars_carousel" Shortcode Found in Post Content
	 * =========================================================================================
	 * @since 0.3
	 */
	if( has_shortcode( $post->post_content, 'jpro_cars_slider' ) ) {
		
		// Shortcodes Stylesheet
		wp_register_style( 'jpro-shortcodes', JPRO_CAR_URI . 'assets/css/shortcodes.css', array(), '0.1' );
		wp_enqueue_style( 'jpro-shortcodes' );
		
		// Owl Carousel Stylesheet
		wp_register_style( 'owl-carousel', JPRO_CAR_URI . 'assets/css/owl.carousel.css', array(), '2.0.0' );
		wp_enqueue_style( 'owl-carousel' );

		// Owl Carousel jQuery
		wp_register_script( 'owl-carousel', JPRO_CAR_URI . 'assets/js/owl.carousel.min.js', array(), '2.0.0', true ); // In Footer
		wp_enqueue_script( 'owl-carousel' );
		
	}
}
/**
 * Owl Carousel Init Callback
 * ==========================
 * @since 0.3
 * @todo: improve
 */
function jp_owl_carousel_init( $items ) {
	echo '
	<script>
	jQuery(document).ready(function($) {
		$(".owl-carousel").owlCarousel({
			autoplay:true,
			loop:true,
			margin:10,
			responsiveClass:true,
			responsive:{
				0:{
					items:'.$items.',
					nav:false
				},
				600:{
					items:'.$items.',
					nav:false
				},
				1000:{
					items:'.$items.',
					nav:false,
					loop:true
				}
			}
		});
	});
	</script>';
}
/**
 * Single Car Page: retrive car details
 * Model, Equipment, Body Type ...
 * ====================================
 * @since 0.1
 * @todo: improve
 */
function car_info( $taxonomy ) {
	global $post;
	
	$args = array(
		'orderby'	=> 'name',
		'order'		=> 'ASC',
		'fields'	=> 'all'
	);
	
	$tax_terms = wp_get_post_terms($post->ID, $taxonomy, $args);
	
	foreach ($tax_terms as $tax_term) {
		echo  $tax_term->name;
	}
}
/**
 * Loop All Cars Details
 * =====================
 * @since 0.1
 * @todo: remove
 */
function jpro_loop_car_details() {
	$args = array(
		'orderby'	=> 'name',
		'order'		=> 'ASC',
		'fields'	=> 'all'
	);
}
/**
 * Loop car makes
 * Final result: <option value="$value">$key</option>
 * ==================================================
 * @since 0.1
 * @todo: improve
 */
function jpro_get_car_makes() {
	$tax_terms = get_terms( 'car-model', 'orderby=name&order=ASC&hide_empty=0&hierarchical=0' );
	
	if( ! empty( $tax_terms ) && ! is_wp_error( $tax_terms ) ) {
		foreach ( $tax_terms as $tax_term ) {
			// Loop only parent terms
			if( $tax_term->parent == '0' ) {
				//echo  '<option value="'.$tax_term->term_id.'">'.$tax_term->name.'</option>';
				$terms[] = $tax_term;
			}
		}
		return $terms;
	}	
}
/**
 * Loop all car models
 * Final result: <option value="$value">$key</option>
 * ==================================================
 * @since 0.1
 * @todo: improve
 */
function jpro_get_car_models() {
	$tax_terms = get_terms( 'car-model', 'orderby=name&order=ASC&hide_empty=0&hierarchical=0' );
	
	if( ! empty( $tax_terms ) && ! is_wp_error( $tax_terms ) ) {
		foreach ( $tax_terms as $tax_term ) {
			// Loop only child terms
			if( $tax_term->parent !== '0' ) {
				echo  '<option value="'.strtolower($tax_term->name).'">'.$tax_term->name.'</option>';
			}
		}
	}
}
/**
 * Loop car body types
 * Final result: <option value="$value">$key</option>
 * ==================================================
 * @since 0.1
 * @todo: improve
 */
function jpro_get_car_body_types() {
	$tax_terms = get_terms( 'car-body-type', 'orderby=name&order=ASC&hide_empty=0&hierarchical=0' );
	
	if( ! empty( $tax_terms ) && ! is_wp_error( $tax_terms ) ) {
		foreach ( $tax_terms as $tax_term ) {
			// Loop only parent terms
			if( $tax_term->parent == '0' ) {
				echo  '<option value="'.strtolower($tax_term->name).'">'.$tax_term->name.'</option>';
			}
		}
	}
}
/**
 * Calculate Expiration Dates
 * Membership Expiration
 * Classifieds Expiration
 * ==========================
 * @since 0.1
 * @todo: improve
 */
function jpro_expiration_calculator( $type, $expiration ) {
	
	if( $type == 'membership' ) {
		if( $expiration == '0' or $expiration == 'never' ) {
			return "never";
		} else {
			return date('Y-m-d', strtotime("+".esc_attr($expiration)." months"));
		}
	}
	
	if( $type == 'classifieds' ) {
		
	}
}

/**
 * Currencies
 * ==========
 * @since 0.4
 */
function jpro_get_currencies( $details = false ) {
	$currencies = array(
	
		'AED' => array(
			'iso'		=> 'AED',
			'name'		=> 'United Arab Emirates Dirham',
			'symbol'	=> 'AED'
		),
		
		'AFN' => array(
			'iso'		=> 'AFN',
			'name'		=> 'Afghanistan Afghani',
			'symbol'	=> '&#1547;'
		),
		
		'ALL' => array(
			'iso'		=> 'ALL',
			'name'		=> 'Albania Lek',
			'symbol'	=> '&#76;&#101;&#107;'
		),
		
		'AMD' => array(
			'iso'		=> 'AMD',
			'name'		=> 'Armenia Dram',
			'symbol'	=> 'AMD'
		),
		
		'ANG' => array(
			'iso'		=> 'ANG',
			'name'		=> 'Netherlands Antilles Guilder',
			'symbol'	=> '&#402;'
		),
		
		'AOA' => array(
			'iso'		=> 'AOA',
			'name'		=> 'Angola Kwanza',
			'symbol'	=> 'AOA'
		),
		
		'ARS' => array(
			'iso'		=> 'ARS',
			'name'		=> 'Argentina Peso',
			'symbol'	=> '&#36;'
		),
		
		'AUD' => array(
			'iso'		=> 'AUD',
			'name'		=> 'Australia Dollar',
			'symbol'	=> '&#36;'
		),
		
		'AWG' => array(
			'iso'		=> 'AWG',
			'name'		=> 'Aruba Guilder',
			'symbol'	=> '&#402;'
		),
		
		'AZN' => array(
			'iso'		=> 'AZN',
			'name'		=> 'Azerbaijan New Manat',
			'symbol'	=> '&#1084;&#1072;&#1085;'
		),
		
		'BAM' => array(
			'iso'		=> 'BAM',
			'name'		=> 'Bosnia and Herzegovina Convertible Marka',
			'symbol'	=> '&#75;&#77;'
		),
		
		'BBD' => array(
			'iso'		=> 'BBD',
			'name'		=> 'Barbados Dollar',
			'symbol'	=> '&#36;'
		),
		
		'BDT' => array(
			'iso'		=> 'BDT',
			'name'		=> 'Bangladesh Taka',
			'symbol'	=> 'BDT'
		),
		
		'BGN' => array(
			'iso'		=> 'BGN',
			'name'		=> 'Bulgaria Lev',
			'symbol'	=> '&#1083;&#1074;'
		),
		
		'BHD' => array(
			'iso'		=> 'BHD',
			'name'		=> 'Bahrain Dinar',
			'symbol'	=> 'BHD'
		),
		
		'BIF' => array(
			'iso'		=> 'BIF',
			'name'		=> 'Burundi Franc',
			'symbol'	=> 'BIF'
		),
		
		'BMD' => array(
			'iso'		=> 'BMD',
			'name'		=> 'Bermuda Dollar',
			'symbol'	=> '&#36;'
		),
		
		'BND' => array(
			'iso'		=> 'BND',
			'name'		=> 'Brunei Darussalam Dollar',
			'symbol'	=> '&#36;'
		),
		
		'BOB' => array(
			'iso'		=> 'BOB',
			'name'		=> 'Bolivia Boliviano',
			'symbol'	=> '&#36;&#98;'
		),
		
		'BRL' => array(
			'iso'		=> 'BRL',
			'name'		=> 'Brazil Real',
			'symbol'	=> '&#82;&#36;'
		),
		
		'BSD' => array(
			'iso'		=> 'BSD',
			'name'		=> 'Bahamas Dollar',
			'symbol'	=> '&#36;'
		),
		
		'BTN' => array(
			'iso'		=> 'BTN',
			'name'		=> 'Bhutan Ngultrum',
			'symbol'	=> 'BTN'
		),
		
		'BWP' => array(
			'iso'		=> 'BWP',
			'name'		=> 'Botswana Pula',
			'symbol'	=> '&#80;'
		),
		
		'BYR' => array(
			'iso'		=> 'BYR',
			'name'		=> 'Belarus Ruble',
			'symbol'	=> '&#112;&#46;'
		),
		
		'BZD' => array(
			'iso'		=> 'BZD',
			'name'		=> 'Belize Dollar',
			'symbol'	=> '&#66;&#90;&#36;'
		),
		
		'CAD' => array(
			'iso'		=> 'CAD',
			'name'		=> 'Canada Dollar',
			'symbol'	=> '&#36;'
		),
		
		'CDF' => array(
			'iso'		=> 'CDF',
			'name'		=> 'Congo/Kinshasa Franc',
			'symbol'	=> 'CDF'
		),
		
		'CHF' => array(
			'iso'		=> 'CHF',
			'name'		=> 'Switzerland Franc',
			'symbol'	=> '&#67;&#72;&#70;'
		),
		
		'CLP' => array(
			'iso'		=> 'CLP',
			'name'		=> 'Chile Peso',
			'symbol'	=> '&#36;'
		),
		
		'CNY' => array(
			'iso'		=> 'CNY',
			'name'		=> 'China Yuan Renminbi',
			'symbol'	=> '&#165;'
		),
		
		'COP' => array(
			'iso'		=> 'COP',
			'name'		=> 'Colombia Peso',
			'symbol'	=> '&#36;'
		),
		
		'CRC' => array(
			'iso'		=> 'CRC',
			'name'		=> 'Costa Rica Colon',
			'symbol'	=> '&#8353;'
		),
		
		'CUC' => array(
			'iso'		=> 'CUC',
			'name'		=> 'Cuba Convertible Peso',
			'symbol'	=> 'CUC'
		),
		
		'CUP' => array(
			'iso'		=> 'CUP',
			'name'		=> 'Cuba Peso',
			'symbol'	=> '&#8369;'
		),
		
		'CVE' => array(
			'iso'		=> 'CVE',
			'name'		=> 'Cape Verde Escudo',
			'symbol'	=> 'CVE'
		),
		
		'CZK' => array(
			'iso'		=> 'CZK',
			'name'		=> 'Czech Republic Koruna',
			'symbol'	=> '&#75;&#269;'
		),
		
		'DJF' => array(
			'iso'		=> 'DJF',
			'name'		=> 'Djibouti Franc',
			'symbol'	=> 'DJF'
		),
		
		'DKK' => array(
			'iso'		=> 'DKK',
			'name'		=> 'Denmark Krone',
			'symbol'	=> '&#107;&#114;'
		),
		
		'DOP' => array(
			'iso'		=> 'DOP',
			'name'		=> 'Dominican Republic Peso',
			'symbol'	=> '&#82;&#68;&#36;'
		),
		
		'DZD' => array(
			'iso'		=> 'DZD',
			'name'		=> 'Algeria Dinar',
			'symbol'	=> 'DZD'
		),
		
		'EGP' => array(
			'iso'		=> 'EGP',
			'name'		=> 'Egypt Pound',
			'symbol'	=> '&#163;'
		),
		
		'ERN' => array(
			'iso'		=> 'ERN',
			'name'		=> 'Eritrea Nakfa',
			'symbol'	=> 'ERN'
		),
		
		'ETB' => array(
			'iso'		=> 'ETB',
			'name'		=> 'Ethiopia Birr',
			'symbol'	=> 'ETB'
		),
		
		'EUR' => array(
			'iso'		=> 'EUR',
			'name'		=> 'Euro Member Countries',
			'symbol'	=> '&#8364;'
		),
		
		'FJD' => array(
			'iso'		=> 'FJD',
			'name'		=> 'Fiji Dollar',
			'symbol'	=> '&#36;'
		),
		
		'FKP' => array(
			'iso'		=> 'FKP',
			'name'		=> 'Falkland Islands (Malvinas) Pound',
			'symbol'	=> '&#163;'
		),
		
		'GBP' => array(
			'iso'		=> 'GBP',
			'name'		=> 'United Kingdom Pound',
			'symbol'	=> '&#163;'
		),
		
		'GEL' => array(
			'iso'		=> 'GEL',
			'name'		=> 'Georgia Lari',
			'symbol'	=> 'GEL'
		),
		
		'GGP' => array(
			'iso'		=> 'GGP',
			'name'		=> 'Guernsey Pound',
			'symbol'	=> '&#163;'
		),
		
		'GHS' => array(
			'iso'		=> 'GHS',
			'name'		=> 'Ghana Cedi',
			'symbol'	=> '&#162;'
		),
		
		'GIP' => array(
			'iso'		=> 'GIP',
			'name'		=> 'Gibraltar Pound',
			'symbol'	=> '&#163;'
		),
		
		'GMD' => array(
			'iso'		=> 'GMD',
			'name'		=> 'Gambia Dalasi',
			'symbol'	=> 'GMD'
		),
		
		'GNF' => array(
			'iso'		=> 'GNF',
			'name'		=> 'Guinea Franc',
			'symbol'	=> 'GNF'
		),
		
		'GTQ' => array(
			'iso'		=> 'GTQ',
			'name'		=> 'Guatemala Quetzal',
			'symbol'	=> '&#81;'
		),
		
		'GYD' => array(
			'iso'		=> 'GYD',
			'name'		=> 'Guyana Dollar',
			'symbol'	=> '&#36;'
		),
		
		'HKD' => array(
			'iso'		=> 'HKD',
			'name'		=> 'Hong Kong Dollar',
			'symbol'	=> '&#36;'
		),
		
		'HNL' => array(
			'iso'		=> 'HNL',
			'name'		=> 'Honduras Lempira',
			'symbol'	=> '&#76;'
		),
		
		'HRK' => array(
			'iso'		=> 'HRK',
			'name'		=> 'Croatia Kuna',
			'symbol'	=> '&#107;&#110;'
		),
		
		'HTG' => array(
			'iso'		=> 'HTG',
			'name'		=> 'Haiti Gourde',
			'symbol'	=> 'HTG'
		),
		
		'HUF' => array(
			'iso'		=> 'HUF',
			'name'		=> 'Hungary Forint',
			'symbol'	=> '&#70;&#116;'
		),
		
		'IDR' => array(
			'iso'		=> 'IDR',
			'name'		=> 'Indonesia Rupiah',
			'symbol'	=> '&#82;&#112;'
		),
		
		'ILS' => array(
			'iso'		=> 'ILS',
			'name'		=> 'Israel Shekel',
			'symbol'	=> '&#8362;'
		),
		
		'IMP' => array(
			'iso'		=> 'IMP',
			'name'		=> 'Isle of Man Pound',
			'symbol'	=> '&#163;'
		),
		
		'INR' => array(
			'iso'		=> 'INR',
			'name'		=> 'India Rupee',
			'symbol'	=> '&#8377;'
		),
		
		'IQD' => array(
			'iso'		=> 'IQD',
			'name'		=> 'Iraq Dinar',
			'symbol'	=> 'IQD'
		),
		
		'IRR' => array(
			'iso'		=> 'IRR',
			'name'		=> 'Iran Rial',
			'symbol'	=> '&#65020;'
		),
		
		'ISK' => array(
			'iso'		=> 'ISK',
			'name'		=> 'Iceland Krona',
			'symbol'	=> '&#107;&#114;'
		),
		
		'JEP' => array(
			'iso'		=> 'JEP',
			'name'		=> 'Jersey Pound',
			'symbol'	=> '&#163;'
		),
		
		'JMD' => array(
			'iso'		=> 'JMD',
			'name'		=> 'Jamaica Dollar',
			'symbol'	=> '&#74;&#36;'
		),
		
		'JOD' => array(
			'iso'		=> 'JOD',
			'name'		=> 'Jordan Dinar',
			'symbol'	=> 'JOD'
		),
		
		'JPY' => array(
			'iso'		=> 'JPY',
			'name'		=> 'Japan Yen',
			'symbol'	=> '&#165;'
		),
		
		'KES' => array(
			'iso'		=> 'KES',
			'name'		=> 'Kenya Shilling',
			'symbol'	=> 'KES'
		),
		
		'KGS' => array(
			'iso'		=> 'KGS',
			'name'		=> 'Kyrgyzstan Som',
			'symbol'	=> '&#1083;&#1074;'
		),
		
		'KHR' => array(
			'iso'		=> 'KHR',
			'name'		=> 'Cambodia Riel',
			'symbol'	=> '&#6107;'
		),
		
		'KMF' => array(
			'iso'		=> 'KMF',
			'name'		=> 'Comoros Franc',
			'symbol'	=> 'KMF'
		),
		
		'KPW' => array(
			'iso'		=> 'KPW',
			'name'		=> 'Korea (North) Won',
			'symbol'	=> '&#8361;'
		),
		
		'KRW' => array(
			'iso'		=> 'KRW',
			'name'		=> 'Korea (South) Won',
			'symbol'	=> '&#8361;'
		),
		
		'KWD' => array(
			'iso'		=> 'KWD',
			'name'		=> 'Kuwait Dinar',
			'symbol'	=> 'KWD'
		),
		
		'KYD' => array(
			'iso'		=> 'KYD',
			'name'		=> 'Cayman Islands Dollar',
			'symbol'	=> '&#36;'
		),
		
		'KZT' => array(
			'iso'		=> 'KZT',
			'name'		=> 'Kazakhstan Tenge',
			'symbol'	=> '&#1083;&#1074;'
		),
		
		'LAK' => array(
			'iso'		=> 'LAK',
			'name'		=> 'Laos Kip',
			'symbol'	=> '&#8365;'
		),
		
		'LBP' => array(
			'iso'		=> 'LBP',
			'name'		=> 'Lebanon Pound',
			'symbol'	=> '&#163;'
		),
		
		'LKR' => array(
			'iso'		=> 'LKR',
			'name'		=> 'Sri Lanka Rupee',
			'symbol'	=> '&#8360;'
		),
		
		'LRD' => array(
			'iso'		=> 'LRD',
			'name'		=> 'Liberia Dollar',
			'symbol'	=> '&#36;'
		),
		
		'LSL' => array(
			'iso'		=> 'LSL',
			'name'		=> 'Lesotho Loti',
			'symbol'	=> 'LSL'
		),
		
		'LYD' => array(
			'iso'		=> 'LYD',
			'name'		=> 'Libya Dinar',
			'symbol'	=> 'LYD'
		),
		
		'MAD' => array(
			'iso'		=> 'MAD',
			'name'		=> 'Morocco Dirham',
			'symbol'	=> 'MAD'
		),
		
		'MDL' => array(
			'iso'		=> 'MDL',
			'name'		=> 'Moldova Leu',
			'symbol'	=> 'MDL'
		),
		
		'MGA' => array(
			'iso'		=> 'MGA',
			'name'		=> 'Madagascar Ariary',
			'symbol'	=> 'MGA'
		),
		
		'MKD' => array(
			'iso'		=> 'MKD',
			'name'		=> 'Macedonia Denar',
			'symbol'	=> '&#1076;&#1077;&#1085;'
		),
		
		'MMK' => array(
			'iso'		=> 'MMK',
			'name'		=> 'Myanmar (Burma) Kyat',
			'symbol'	=> 'MMK'
		),
		
		'MNT' => array(
			'iso'		=> 'MNT',
			'name'		=> 'Mongolia Tughrik',
			'symbol'	=> '&#8366;'
		),
		
		'MOP' => array(
			'iso'		=> 'MOP',
			'name'		=> 'Macau Pataca',
			'symbol'	=> 'MOP'
		),
		
		'MRO' => array(
			'iso'		=> 'MRO',
			'name'		=> 'Mauritania Ouguiya',
			'symbol'	=> 'MRO'
		),
		
		'MUR' => array(
			'iso'		=> 'MUR',
			'name'		=> 'Mauritius Rupee',
			'symbol'	=> '&#8360;'
		),
		
		'MVR' => array(
			'iso'		=> 'MVR',
			'name'		=> 'Maldives (Maldive Islands) Rufiyaa',
			'symbol'	=> 'MVR'
		),
		
		'MWK' => array(
			'iso'		=> 'MWK',
			'name'		=> 'Malawi Kwacha',
			'symbol'	=> 'MWK'
		),
		
		'MXN' => array(
			'iso'		=> 'MXN',
			'name'		=> 'Mexico Peso',
			'symbol'	=> '&#36;'
		),
		
		'MYR' => array(
			'iso'		=> 'MYR',
			'name'		=> 'Malaysia Ringgit',
			'symbol'	=> '&#82;&#77;'
		),
		
		'MZN' => array(
			'iso'		=> 'MZN',
			'name'		=> 'Mozambique Metical',
			'symbol'	=> '&#77;&#84;'
		),
		
		'NAD' => array(
			'iso'		=> 'NAD',
			'name'		=> 'Namibia Dollar',
			'symbol'	=> '&#36;'
		),
		
		'NGN' => array(
			'iso'		=> 'NGN',
			'name'		=> 'Nigeria Naira',
			'symbol'	=> '&#8358;'
		),
		
		'NIO' => array(
			'iso'		=> 'NIO',
			'name'		=> 'Nicaragua Cordoba',
			'symbol'	=> '&#67;&#36;'
		),
		
		'NOK' => array(
			'iso'		=> 'NOK',
			'name'		=> 'Norway Krone',
			'symbol'	=> '&#107;&#114;'
		),
		
		'NPR' => array(
			'iso'		=> 'NPR',
			'name'		=> 'Nepal Rupee',
			'symbol'	=> '&#8360;'
		),
		
		'NZD' => array(
			'iso'		=> 'NZD',
			'name'		=> 'New Zealand Dollar',
			'symbol'	=> '&#36;'
		),
		
		'OMR' => array(
			'iso'		=> 'OMR',
			'name'		=> 'Oman Rial',
			'symbol'	=> '&#65020;'
		),
		
		'PAB' => array(
			'iso'		=> 'PAB',
			'name'		=> 'Panama Balboa',
			'symbol'	=> '&#66;&#47;&#46;'
		),
		
		'PEN' => array(
			'iso'		=> 'PEN',
			'name'		=> 'Peru Nuevo Sol',
			'symbol'	=> '&#83;&#47;&#46;'
		),
		
		'PGK' => array(
			'iso'		=> 'PGK',
			'name'		=> 'Papua New Guinea Kina',
			'symbol'	=> 'PGK'
		),
		
		'PHP' => array(
			'iso'		=> 'PHP',
			'name'		=> 'Philippines Peso',
			'symbol'	=> '&#8369;'
		),
		
		'PKR' => array(
			'iso'		=> 'PKR',
			'name'		=> 'Pakistan Rupee',
			'symbol'	=> '&#8360;'
		),
		
		'PLN' => array(
			'iso'		=> 'PLN',
			'name'		=> 'Poland Zloty',
			'symbol'	=> '&#122;&#322;'
		),
		
		'PYG' => array(
			'iso'		=> 'PYG',
			'name'		=> 'Paraguay Guarani',
			'symbol'	=> '&#71;&#115;'
		),
		
		'QAR' => array(
			'iso'		=> 'QAR',
			'name'		=> 'Qatar Riyal',
			'symbol'	=> '&#65020;'
		),
		
		'RON' => array(
			'iso'		=> 'RON',
			'name'		=> 'Romania New Leu',
			'symbol'	=> '&#108;&#101;&#105;'
		),
		
		'RSD' => array(
			'iso'		=> 'RSD',
			'name'		=> 'Serbia Dinar',
			'symbol'	=> '&#1044;&#1080;&#1085;&#46;'
		),
		
		'RUB' => array(
			'iso'		=> 'RUB',
			'name'		=> 'Russia Ruble',
			'symbol'	=> '&#1088;&#1091;&#1073;'
		),
		
		'RWF' => array(
			'iso'		=> 'RWF',
			'name'		=> 'Rwanda Franc',
			'symbol'	=> 'RWF'
		),
		
		'SAR' => array(
			'iso'		=> 'SAR',
			'name'		=> 'Saudi Arabia Riyal',
			'symbol'	=> '&#65020;'
		),
		
		'SBD' => array(
			'iso'		=> 'SBD',
			'name'		=> 'Solomon Islands Dollar',
			'symbol'	=> '&#36;'
		),
		
		'SCR' => array(
			'iso'		=> 'SCR',
			'name'		=> 'Seychelles Rupee',
			'symbol'	=> '&#8360;'
		),
		
		'SDG' => array(
			'iso'		=> 'SDG',
			'name'		=> 'Sudan Pound',
			'symbol'	=> 'SDG'
		),
		
		'SEK' => array(
			'iso'		=> 'SEK',
			'name'		=> 'Sweden Krona',
			'symbol'	=> '&#107;&#114;'
		),
		
		'SGD' => array(
			'iso'		=> 'SGD',
			'name'		=> 'Singapore Dollar',
			'symbol'	=> '&#36;'
		),
		
		'SHP' => array(
			'iso'		=> 'SHP',
			'name'		=> 'Saint Helena Pound',
			'symbol'	=> '&#163;'
		),
		
		'SLL' => array(
			'iso'		=> 'SLL',
			'name'		=> 'Sierra Leone Leone',
			'symbol'	=> 'SLL'
		),
		
		'SOS' => array(
			'iso'		=> 'SOS',
			'name'		=> 'Somalia Shilling',
			'symbol'	=> '&#83;'
		),
		
		'SPL' => array(
			'iso'		=> 'SPL',
			'name'		=> 'Seborga Luigino',
			'symbol'	=> 'SPL'
		),
		
		'SRD' => array(
			'iso'		=> 'SRD',
			'name'		=> 'Suriname Dollar',
			'symbol'	=> '&#36;'
		),
		
		'STD' => array(
			'iso'		=> 'STD',
			'name'		=> 'São Tomé and Príncipe Dobra',
			'symbol'	=> 'STD'
		),
		
		'SVC' => array(
			'iso'		=> 'SVC',
			'name'		=> 'El Salvador Colon',
			'symbol'	=> '&#36;'
		),
		
		'SYP' => array(
			'iso'		=> 'SYP',
			'name'		=> 'Syria Pound',
			'symbol'	=> '&#163;'
		),
		
		'SZL' => array(
			'iso'		=> 'SZL',
			'name'		=> 'Swaziland Lilangeni',
			'symbol'	=> 'SZL'
		),
		
		'THB' => array(
			'iso'		=> 'THB',
			'name'		=> 'Thailand Baht',
			'symbol'	=> '&#3647;'
		),
		
		'TJS' => array(
			'iso'		=> 'TJS',
			'name'		=> 'Tajikistan Somoni',
			'symbol'	=> 'TJS'
		),
		
		'TMT' => array(
			'iso'		=> 'TMT',
			'name'		=> 'Turkmenistan Manat',
			'symbol'	=> 'TMT'
		),
		
		'TND' => array(
			'iso'		=> 'TND',
			'name'		=> 'Tunisia Dinar',
			'symbol'	=> 'TND'
		),
		
		'TOP' => array(
			'iso'		=> 'TOP',
			'name'		=> 'Tonga Pa\'anga',
			'symbol'	=> 'TOP'
		),
		
		'TRY' => array(
			'iso'		=> 'TRY',
			'name'		=> 'Turkey Lira',
			'symbol'	=> '&#8378;'
		),
		
		'TTD' => array(
			'iso'		=> 'TTD',
			'name'		=> 'Trinidad and Tobago Dollar',
			'symbol'	=> '&#84;&#84;&#36;'
		),
		
		'TVD' => array(
			'iso'		=> 'TVD',
			'name'		=> 'Tuvalu Dollar',
			'symbol'	=> '&#36;'
		),
		
		'TWD' => array(
			'iso'		=> 'TWD',
			'name'		=> 'Taiwan New Dollar',
			'symbol'	=> '&#78;&#84;&#36;'
		),
		
		'TZS' => array(
			'iso'		=> 'TZS',
			'name'		=> 'Tanzania Shilling',
			'symbol'	=> 'TZS'
		),
		
		'UAH' => array(
			'iso'		=> 'UAH',
			'name'		=> 'Ukraine Hryvnia',
			'symbol'	=> '&#8372;'
		),
		
		'UGX' => array(
			'iso'		=> 'UGX',
			'name'		=> 'Uganda Shilling',
			'symbol'	=> 'UGX'
		),
		
		'USD' => array(
			'iso'		=> 'USD',
			'name'		=> 'United States Dollar',
			'symbol'	=> '&#36;'
		),
		
		'UYU' => array(
			'iso'		=> 'UYU',
			'name'		=> 'Uruguay Peso',
			'symbol'	=> '&#36;&#85;'
		),
		
		'UZS' => array(
			'iso'		=> 'UZS',
			'name'		=> 'Uzbekistan Som',
			'symbol'	=> '&#1083;&#1074;'
		),
		
		'VEF' => array(
			'iso'		=> 'VEF',
			'name'		=> 'Venezuela Bolivar',
			'symbol'	=> '&#66;&#115;'
		),
		
		'VND' => array(
			'iso'		=> 'VND',
			'name'		=> 'Viet Nam Dong',
			'symbol'	=> '&#8363;'
		),
		
		'VUV' => array(
			'iso'		=> 'VUV',
			'name'		=> 'Vanuatu Vatu',
			'symbol'	=> 'VUV'
		),
		
		'WST' => array(
			'iso'		=> 'WST',
			'name'		=> 'Samoa Tala',
			'symbol'	=> 'WST'
		),
		
		'XCD' => array(
			'iso'		=> 'XCD',
			'name'		=> 'East Caribbean Dollar',
			'symbol'	=> '&#36;'
		),
		
		'YER' => array(
			'iso'		=> 'YER',
			'name'		=> 'Yemen Rial',
			'symbol'	=> '&#65020;'
		),
		
		'ZAR' => array(
			'iso'		=> 'ZAR',
			'name'		=> 'South Africa Rand',
			'symbol'	=> '&#82;'
		),
		
		'ZMW' => array(
			'iso'		=> 'ZMW',
			'name'		=> 'Zambia Kwacha',
			'symbol'	=> 'ZMW'
		),
		
		'ZWD' => array(
			'iso'		=> 'ZWD',
			'name'		=> 'Zimbabwe Dollar',
			'symbol'	=> '&#90;&#36;'
		),
	
	);
	
	if( $details ) {
		$currencies = $currencies[$details];
	}
	
	return $currencies;
}
?>