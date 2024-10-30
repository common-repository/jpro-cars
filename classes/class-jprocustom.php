<?php
if( ! class_exists( 'jpro' ) ) {
	
	class jpro {
		
		static public function check( $option, $default ) {
			if( empty( $option ) )
				return $default;
			else 
				return $option;
		}
		
		static public function sanitize_post() {
			$details = array(
				'_car_title'			=> isset( $_POST['car-title'] ) 		? sanitize_text_field( $_POST['car-title'] ) : '',
				'_car_description'		=> isset( $_POST['car-description'] ) 	? $_POST['car-description'] : '',
				'_car_make'				=> isset( $_POST['car-make'] ) 			? sanitize_text_field( $_POST['car-make'] ) : '',
				'_car_model'			=> isset( $_POST['car-model'] )			? sanitize_text_field( $_POST['car-model'] ) : '',
				'_car_version'			=> isset( $_POST['car-version'] ) 		? sanitize_text_field( $_POST['car-version'] ) : '',
				'_car_year'				=> isset( $_POST['car-year'] ) 			? sanitize_text_field( $_POST['car-year'] ) : '',
				'_car_transmission'		=> isset( $_POST['car-transmission'] ) 	? sanitize_text_field( $_POST['car-transmission'] ) : '',
				'_car_doors'			=> isset( $_POST['car-doors'] ) 		? sanitize_text_field( $_POST['car-doors'] ) : '',
				'_car_fuel'				=> isset( $_POST['car-fuel'] ) 			? sanitize_text_field( $_POST['car-fuel'] ) : '',
				'_car_condition'		=> isset( $_POST['car-condition'] ) 	? sanitize_text_field( $_POST['car-condition'] ) : '',
				'_car_drive'			=> isset( $_POST['car-drive'] ) 		? sanitize_text_field( $_POST['car-drive'] ) : '',
				'_car_color'			=> isset( $_POST['car-color'] ) 		? sanitize_text_field( $_POST['car-color'] ) : '',
				'_car_price'			=> isset( $_POST['car-price'] ) 		? sanitize_text_field( $_POST['car-price'] ) : '',
				'_car_price_type'		=> isset( $_POST['car-price-type'] ) 	? sanitize_text_field( $_POST['car-price-type'] ) : '',
				'_car_warranty'			=> isset( $_POST['car-warranty'] ) 		? sanitize_text_field( $_POST['car-warranty'] ) : '',
				'_car_currency'			=> isset( $_POST['currency'] ) 			? sanitize_text_field( $_POST['currency'] ) : '',
				'_car_mileage'			=> isset( $_POST['car-mileage'] ) 		? sanitize_text_field( $_POST['car-mileage'] ) : '',
				'_car_vin'				=> isset( $_POST['car-vin'] ) 			? sanitize_text_field( $_POST['car-vin'] ) : '',
				'_car_engine'			=> isset( $_POST['car-engine'] ) 		? sanitize_text_field( $_POST['car-engine'] ) : '',
				'_car_horsepower'		=> isset( $_POST['car-horsepower'] )	? sanitize_text_field( $_POST['car-horsepower'] ) : '',
				'_car_seats'			=> isset( $_POST['car-seats'] ) 		? sanitize_text_field( $_POST['car-seats'] ) : '',
				'_car_equipment'		=> isset( $_POST['car-equipment'] ) 	? sanitize_term( $_POST['car-equipment'], 'car-equipment', '' ) : '',
				'_car_images'			=> isset( $_POST['car-images'] ) 		? sanitize_text_field( $_POST['car-images'] ) : '',
				'_seller_first_name'	=> isset( $_POST['seller-first-name'] ) ? sanitize_text_field( $_POST['seller-first-name'] ) : '',
				'_seller_last_name'		=> isset( $_POST['seller-last-name'] ) 	? sanitize_text_field( $_POST['seller-last-name'] ) : '',
				'_seller_email'			=> isset( $_POST['seller-email'] ) 		? sanitize_text_field( $_POST['seller-email'] ) : '',
				'_seller_phone'			=> isset( $_POST['seller-phone'] ) 		? sanitize_text_field( $_POST['seller-phone'] ) : '',
				'_seller_company'		=> isset( $_POST['seller-company'] ) 	? sanitize_text_field( $_POST['seller-company'] ) : '',
				'_seller_town'			=> isset( $_POST['seller-town'] ) 		? sanitize_text_field( $_POST['seller-town'] ) : '',
				'_seller_country'		=> isset( $_POST['seller-country'] ) 	? sanitize_text_field( $_POST['seller-country'] ) : '',
				'_seller_state'			=> isset( $_POST['seller-state'] ) 		? sanitize_text_field( $_POST['seller-state'] ) : ''
			);
			return $details;
		}
		
		static public function validation( $validate ) {
			$validation['car-version_req']			= isset( $validate['car-version_req'] ) 		? $validate['car-version_req'] : '';
			$validation['car-year_req']				= isset( $validate['car-year_req'] ) 			? $validate['car-year_req'] : '';
			$validation['car-mileage_req']			= isset( $validate['car-mileage_req'] ) 		? $validate['car-mileage_req'] : '';
			$validation['car-fuel_req']				= isset( $validate['car-fuel_req'] ) 			? $validate['car-fuel_req'] : '';
			$validation['car-engine_req']			= isset( $validate['car-engine_req'] ) 			? $validate['car-engine_req'] : '';
			$validation['car-horsepower_req']		= isset( $validate['car-horsepower_req'] ) 		? $validate['car-horsepower_req'] : '';
			$validation['car-transmission_req']		= isset( $validate['car-transmission_req'] )	? $validate['car-transmission_req'] : '';
			$validation['car-drive_req']			= isset( $validate['car-drive_req'] ) 			? $validate['car-drive_req'] : '';
			$validation['car-doors_req']			= isset( $validate['car-doors_req'] ) 			? $validate['car-doors_req'] : '';
			$validation['car-seats_req']			= isset( $validate['car-seats_req'] ) 			? $validate['car-seats_req'] : '';
			$validation['car-color_req']			= isset( $validate['car-color_req'] ) 			? $validate['car-color_req'] : '';
			$validation['car-condition_req']		= isset( $validate['car-condition_req'] ) 		? $validate['car-condition_req'] : '';
			$validation['car-vin_req']				= isset( $validate['car-vin_req'] ) 			? $validate['car-vin_req'] : '';
			$validation['car-price_req']			= isset( $validate['car-price_req'] ) 			? $validate['car-price_req'] : '';
			$validation['car-price-type_req']		= isset( $validate['car-price-type_req'] ) 		? $validate['car-price-type_req'] : '';
			$validation['car-warranty_req']			= isset( $validate['car-warranty_req'] ) 		? $validate['car-warranty_req'] : '';
			$validation['car-currency_req']			= isset( $validate['car-currency_req'] ) 		? $validate['car-currency_req'] : '';
			$validation['first-name_req']			= isset( $validate['first-name_req'] ) 			? $validate['first-name_req'] : '';
			$validation['last-name_req']			= isset( $validate['last-name_req'] ) 			? $validate['last-name_req'] : '';
			$validation['seller-company_req']		= isset( $validate['seller-company_req'] ) 		? $validate['seller-company_req'] : '';
			$validation['seller-phone_req']			= isset( $validate['seller-phone_req'] ) 		? $validate['seller-phone_req'] : '';
			$validation['seller-country_req']		= isset( $validate['seller-country_req'] ) 		? $validate['seller-country_req'] : '';
			$validation['seller-state_req']			= isset( $validate['seller-state_req'] ) 		? $validate['seller-state_req'] : '';
			$validation['seller-town_req']			= isset( $validate['seller-town_req'] ) 		? $validate['seller-town_req'] : '';
			$validation['car-version_show']			= isset( $validate['car-version_show'] ) 		? $validate['car-version_show'] : '';
			$validation['car-year_show']			= isset( $validate['car-year_show'] ) 			? $validate['car-year_show'] : '';
			$validation['car-mileage_show']			= isset( $validate['car-mileage_show'] ) 		? $validate['car-mileage_show'] : '';
			$validation['car-fuel_show']			= isset( $validate['car-fuel_show'] ) 			? $validate['car-fuel_show'] : '';
			$validation['car-engine_show']			= isset( $validate['car-engine_show'] ) 		? $validate['car-engine_show'] : '';
			$validation['car-horsepower_show']		= isset( $validate['car-horsepower_show'] ) 	? $validate['car-horsepower_show'] : '';
			$validation['car-transmission_show']	= isset( $validate['car-transmission_show'] ) 	? $validate['car-transmission_show'] : '';
			$validation['car-drive_show']			= isset( $validate['car-drive_show'] ) 			? $validate['car-drive_show'] : '';
			$validation['car-doors_show']			= isset( $validate['car-doors_show'] ) 			? $validate['car-doors_show'] : '';
			$validation['car-seats_show']			= isset( $validate['car-seats_show'] ) 			? $validate['car-seats_show'] : '';
			$validation['car-color_show']			= isset( $validate['car-color_show'] ) 			? $validate['car-color_show'] : '';
			$validation['car-condition_show']		= isset( $validate['car-condition_show'] ) 		? $validate['car-condition_show'] : '';
			$validation['car-vin_show']				= isset( $validate['car-vin_show'] ) 			? $validate['car-vin_show'] : '';
			$validation['car-price_show']			= isset( $validate['car-price_show'] ) 			? $validate['car-price_show'] : '';
			$validation['car-price-type_show']		= isset( $validate['car-price-type_show'] ) 	? $validate['car-price-type_show'] : '';
			$validation['car-warranty_show']		= isset( $validate['car-warranty_show'] ) 		? $validate['car-warranty_show'] : '';
			$validation['car-currency_show']		= isset( $validate['car-currency_show'] ) 		? $validate['car-currency_show'] : '';
			$validation['first-name_show']			= isset( $validate['first-name_show'] ) 		? $validate['first-name_show'] : '';
			$validation['last-name_show']			= isset( $validate['last-name_show'] ) 			? $validate['last-name_show'] : '';
			$validation['seller-company_show']		= isset( $validate['seller-company_show'] ) 	? $validate['seller-company_show'] : '';
			$validation['seller-phone_show']		= isset( $validate['seller-phone_show'] ) 		? $validate['seller-phone_show'] : '';
			$validation['seller-country_show']		= isset( $validate['seller-country_show'] ) 	? $validate['seller-country_show'] : '';
			$validation['seller-state_show']		= isset( $validate['seller-state_show'] ) 		? $validate['seller-state_show'] : '';
			$validation['seller-town_show']			= isset( $validate['seller-town_show'] ) 		? $validate['seller-town_show'] : '';
			
			return $validation;
		}
		
	}
	
}