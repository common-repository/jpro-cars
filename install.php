<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) 
	exit;

/**
 * Fire Action on Plugin Installation
 *
 * @since 0.1
 */
class JP_Cars_Install {
	/**
	 * Insert Default Data into Database
	 *
	 * @since 0.1
	 */
	static function install() {
		global $wpdb;
		
		/**
		 * Insert Default Settings "_jp_cars_settings"
		 * ===========================================
		 * @since 0.1
		 */
		if( ! get_option( '_jp_cars_settings' ) ) {
			$jp_cars_settings = array(
				'mode'						=> 'classifieds',
				'cc_page_id'				=> '',
				'cars_per_page'				=> '9',
				'payment_success'			=> '',
				'payment_failed'			=> ''
			);
			update_option( '_jp_cars_settings', serialize( $jp_cars_settings ) );
		}
		
		if( ! get_option('_jp_cars_validation' ) ) {
			$defaults = array(
				'car-version_req' => '',
				'car-version_show' => 'on',
				'car-year_req' => 'on',
				'car-year_show' => 'on',
				'car-mileage_req' => 'on',
				'car-mileage_show' => 'on',
				'car-fuel_req' => 'on',
				'car-fuel_show' => 'on',
				'car-engine_req' => 'on',
				'car-engine_show' => 'on',
				'car-horsepower_req' => 'on',
				'car-horsepower_show' => 'on',
				'car-transmission_req' => 'on',
				'car-transmission_show' => 'on',
				'car-drive_req' => 'on',
				'car-drive_show' => 'on',
				'car-doors_req' => 'on',
				'car-doors_show' => 'on',
				'car-seats_req' => 'on',
				'car-seats_show' => 'on',
				'car-color_req' => '',
				'car-color_show' => 'on',
				'car-condition_req' => 'on',
				'car-condition_show' => 'on',
				'car-vin_req' => '',
				'car-vin_show' => 'on',
				'car-price_req' => 'on',
				'car-price_show' => 'on',
				'car-price-type_req' => 'on',
				'car-price-type_show' => 'on',
				'car-warranty_req' => 'on',
				'car-warranty_show' => 'on',
				'car-currency_req' => 'on',
				'car-currency_show' => 'on',
				'first-name_req' => 'on',
				'first-name_show' => 'on',
				'last-name_req' => 'on',
				'last-name_show' => 'on',
				'seller-company_req' => '',
				'seller-company_show' => 'on',
				'seller-phone_req' => 'on',
				'seller-phone_show' => 'on',
				'seller-country_req' => 'on',
				'seller-country_show' => 'on',
				'seller-state_req' => '',
				'seller-state_show' => 'on',
				'seller-town_req' => 'on',
				'seller-town_show' => 'on'
			);
			update_option( '_jp_cars_validation', serialize( $defaults ) );
		}
		
		/**
		 * Insert Default Currencies "_jp_currencies"
		 * ==========================================
		 * @since 0.4
		 */
		if( !get_option( '_jp_cars_currencies' ) ) {
			$jp_cars_currencies = array(
				'EUR' => array(
					'iso'		=> 'EUR',
					'name'		=> 'Euro Member Countries',
					'symbol'	=> '&#8364;',
					'position'	=> 'left'
				),
				'GBP' => array(
					'iso'		=> 'GBP',
					'name'		=> 'United Kingdom Pound',
					'symbol'	=> '&#163;',
					'position'	=> 'left'
				),
				'INR' => array(
					'iso'		=> 'INR',
					'name'		=> 'India Rupee',
					'symbol'	=> '&#8377;',
					'position'	=> 'left'
				),
				'JPY' => array(
					'iso'		=> 'JPY',
					'name'		=> 'Japan Yen',
					'symbol'	=> '&#165;',
					'position'	=> 'left'
				),
				'RUB' => array(
					'iso'		=> 'RUB',
					'name'		=> 'Russia Ruble',
					'symbol'	=> '&#1088;&#1091&#1073;',
					'position'	=> 'left'
				),
				'USD' => array(
					'iso'		=> 'USD',
					'name'		=> 'United States Dollar',
					'symbol'	=> '&#36;',
					'position'	=> 'left'
				)
			);
			update_option( '_jp_cars_currencies', serialize( $jp_cars_currencies ) );
		}
		
		/**
		 * Insert Default Email Templates "_jp_cars_email_templates"
		 * =========================================================
		 * @since 0.1
		 */
		if( !get_option( '_jp_cars_email_templates' ) ) {
			$jp_cars_email_templates = array(
				'membership_purchase_subject' => 'Membership Purchase Successful',
				'membership_purchase_message' => '
					Hello {$first_name}
													
					You have successfuly purchased {package_title}
													
					Check details below:

					Date purchased: {package_purchased_date}

					Date expire: {package_expire_date}

					Classifieds posting limit: {package_classifieds_posting_limit}

					Classifieds image uploading limit: {package_classifieds_image_limit}

					Check more details here: {package_url}',
				'classified_submited_subject' => 'Classified Submition Successful',
				'classified_submited_message' => '
					Hello {first_name}

					Your classified #{classified_id} was submited successfuly.

					You can view it live here: {classified_url}',
			);
			update_option( '_jp_cars_email_templates', serialize( $jp_cars_email_templates ) );
		}
		/**
		 * Create jp_transactions Table in Database
		 *
		 * @since 0.1
		 */ 
		$charset_collate = $wpdb->get_charset_collate();
		
		$table_name = $wpdb->prefix . "jp_cars_transactions"; 
		
		$sql = "CREATE TABLE $table_name (
			    id int(100) NOT NULL AUTO_INCREMENT,
			    user_id int(5) NOT NULL,
				username varchar(50) NOT NULL,
			    user_email varchar(100) NOT NULL,
			    package_id varchar(20) NOT NULL,
			    package_title varchar(255) NOT NULL,
			    gateway varchar(20) NOT NULL,
			    amount int(10) NOT NULL,
			    currency varchar(3) NOT NULL,
			    date date NOT NULL,
			    status varchar(20) NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }
}
register_activation_hook( JPRO_CAR_DIR . 'jprocars.php', array( 'JP_Cars_Install', 'install' ) );
?>