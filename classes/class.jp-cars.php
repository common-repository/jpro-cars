<?php
class JP_Cars {
	
	/**
	 * Class Constructor
	 * =================
	 * @since 0.4
	 */
	public function __construct() {
		$this->translation();
		
		// Rewrite rules
		add_action( 'init', array( $this, 'rewrite_rules' ) );
	}
	
	/**
	 * Custom Rewrite Rules
	 * ====================
	 * @since 0.6
	 */
	public function rewrite_rules() {
		$Settings	= new JP_Settings();
		$settings	= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );

		add_rewrite_rule( '^add-car/?', 'index.php?page_id='.$settings['cc_page_id'].'&jp=add-new-classified', 'top' );
		add_rewrite_rule( '^my-cars/?', 'index.php?page_id='.$settings['cc_page_id'].'&jp=my-classifieds', 'top' );
	}
	
	/**
	 * Search Cars Variations
	 * ======================
	 * @since 0.1
	 * @return array
	 */
	public function Query_Args( $query_args ) {
		$Settings	= new JP_Settings();
		$settings	= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
		
		if( is_array( $query_args ) )
			 extract( $query_args );
		
		if( empty( $make ) ) 		$make 		= 'all';
		if( empty( $model ) ) 		$model 		= 'all';
		if( empty( $year ) )		$year 		= 'all';
		if( empty( $fuel ) )		$fuel 		= 'all';
		if( empty( $condition ) )	$condition 	= 'all';
		if( empty( $mileage ) ) 	$mileage	= 'all';
		if( empty( $price ) ) 		$price		= 'all';
		
		/**
		 * Fix Paged on Static Homepage
		 * ============================
		 * @since 0.4
		 */
		if( get_query_var('paged') ) { $paged = get_query_var('paged'); }
		elseif( get_query_var('page') ) { $paged = get_query_var('page'); }
		else { $paged = 1; }
		
		##################################################
		# SEARCH CARS BY MAKE
		##################################################
		$this->taxQuery['carMake'] = '';
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-make'] ) && !empty( $_POST['car-make'] ) ) {
			
			// Store Search Parameters
			$this->tax['taxonomy']	= 'car-model';
			$this->tax['field']		= 'term_id';
			$this->tax['terms']		= esc_attr( $_POST['car-make'] );
			
			// Format Tax Query
			$this->taxQuery['carMake'] = array(
				'taxonomy'	=> $this->tax['taxonomy'], 
				'field' 	=> $this->tax['field'], 
				'terms' 	=> $this->tax['terms']
			);
			
		}
		##################################################
		# SEARCH CARS BY MODEL
		##################################################
		$this->taxQuery['carModel'] = '';
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-model'] ) && !empty( $_POST['car-model'] ) ) {
				
				// Store Search Parameters
				$this->tax['taxonomy']	= 'car-model';
				$this->tax['field']		= 'term_id';
				$this->tax['terms']		= esc_attr( $_POST['car-model'] );
				
				// Format Tax Query
				$this->taxQuery['carModel'] = array(
					'taxonomy'	=> $this->tax['taxonomy'],
					'field'		=> $this->tax['field'],
					'terms'		=> $this->tax['terms']
				);
		}
		##################################################
		# SEARCH CARS BY MADE YEAR FROM
		##################################################
		$this->metaQuery['carYear'] = '';
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-year'] ) && !empty( $_POST['car-year'] ) && empty( $_POST['car-year-to'] ) ) {
			
			// Format Meta Key & Value
			$this->metaKey['carYear'] = '_car_year';
			$this->metaVal['carYear'] = esc_attr( $_POST['car-year'] );
			
			// Format Meta Query
			$this->metaQuery['carYear'] = array( 
				'key'	=> $this->metaKey['carYear'], 
				'value' => $this->metaVal['carYear']
			);
			
		}
		else
		##################################################
		# SEARCH CARS BY MADE YEAR TO
		##################################################
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-year-to'] ) && !empty( $_POST['car-year-to'] ) && empty( $_POST['car-year'] ) ) {
			
			// Format Meta Query
			$this->metaKey['carYear'] = '_car_year';
			$this->metaVal['carYear'] = esc_attr( $_POST['car-year-to'] );
			
			// Format Meta Query
			$this->metaQuery['carYear'] = array(
				'key'		=> $this->metaKey['carYear'],
				'value'		=> $this->metaVal['carYear'],
				'compare'	=> '>=',
				'type'		=> 'numeric'
			);
		}
		else
		##################################################
		# IF CAR YEAR FROM && CAR YEAR TO
		##################################################
		if( isset( $_POSt['search-cars'] ) && !empty( $_POST['car-year'] ) && !empty( $_POST['car-year-to'] ) ) {
			
			// Format Meta Key
			$this->metaKey['carYear'] 	= '_car_year';
			
			// Format Meta Value
			$this->metaVal['carYear']	= esc_attr( $_POST['car-year'] );
			$this->metaVal['carYearTo']	= esc_attr( $_POST['car-year-to'] );
			
			// Format Meta Query
			$this->metaQuery['carYear'] = array(
				'key'		=> $this->metaKey['carYear'],
				'value'		=> array( $this->metaVal['carYear'], $this->metaVal['carYearTo'] ),
				'compare'	=> 'BETWEEN',
				'type'		=> 'numeric'
			);
		}
		##################################################
		# SEARCH CARS BY FUEL STATUS - @since v0.7
		##################################################
		$this->metaQuery['carFuel'] = '';
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-fuel'] ) && !empty( $_POST['car-fuel'] ) ) {
			
			// Format Meta Key & Value
			$this->metaKey['carFuel'] = '_car_fuel';
			$this->metaVal['carFuel'] = esc_attr( $_POST['car-fuel'] );
			
			// Format Meta Query
			$this->metaQuery['carFuel'] = array(
				'key'	=> $this->metaKey['carFuel'],
				'value'	=> $this->metaVal['carFuel']
			);
		}
		##################################################
		# SEARCH CARS BY CONDITION STATUS
		##################################################
		$this->metaQuery['carCondition'] = '';
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-condition'] ) && !empty( $_POST['car-condition'] ) ) {
			
			// Format Meta Key & Value
			$this->metaKey['carCondition'] = '_car_condition';
			$this->metaVal['carCondition'] = esc_attr( $_POST['car-condition'] );
			
			// Format Meta Query
			$this->metaQuery['carCondition'] = array( 
				'key'	=> $this->metaKey['carCondition'], 
				'value' => $this->metaVal['carCondition']
			);
			
		}
		##################################################
		# SEARCH CARS BY PRICE
		##################################################
		$this->metaQuery['carPrice'] = '';
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-price'] ) ) {
			
			// Format Meta Key & Value
			$this->metaKey['carPrice'] 		= '_car_price';
			$this->metaVal['carPrice'] 		= esc_attr( $_POST['car-price'] );
			
			// Extract lowest & highest prices
			$price = $this->metaVal['carPrice'];
			$price = explode( ';', $price );
			
			// Save lowest & highest prices into variables
			$this->priceSta = $price[0];
			$this->priceEnd = $price[1];
			
			// Format Meta Query
			$this->metaQuery['carPrice'] = array(
				'key'		=> $this->metaKey['carPrice'],
				'value'		=> array( $this->priceSta, $this->priceEnd ),
				'compare'	=> 'BETWEEN',
				'type'		=> 'numeric'
			);
		}
		##################################################
		# SEARCH CARS BY MILEAGE
		##################################################
		$this->metaQuery['carMileage'] = '';
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-mileage'] ) && !empty( $_POST['car-mileage'] ) ) {
			
			// Format Meta Key & Value
			$this->metaKey['carMileage'] = '_car_mileage';
			$this->metaVal['carMileage'] = esc_attr( $_POST['car-mileage'] );
			
			// Extract lowest & highest mileages
			$mileage = $this->metaVal['carMileage'];
			$mileage = explode( ';', $mileage );
			
			// Save lowest & highest mileages into variables
			$this->mileageSta = $mileage[0]; // Mileage start from
			$this->mileageEnd = $mileage[1]; // Mileage end from

			// Format Meta Query
			$this->metaQuery['carMileage'] = array( 
				'key'		=> $this->metaKey['carMileage'], 
				'value'		=> array( $this->mileageSta, $this->mileageEnd ), 
				'compare'	=> 'BETWEEN',
				'type'		=> 'numeric'
			);
		}
		##################################################
		# SEARCH CARS BY LOCATION
		##################################################
		$this->metaQuery['carLocation'] = '';
		if( isset( $_POST['search-cars'] ) && isset( $_POST['car-location'] ) && !empty( $_POST['car-location'] ) ) {
			
			// Format Meta Key & Value
			$this->metaKey['carLocation'] = '_seller_country';
			$this->metaVal['carLocation'] = esc_attr( $_POST['car-location'] );
			
			// Format Meta Query
			$this->metaQuery['carLocation'] = array(
				'key'	=> $this->metaKey['carLocation'],
				'value'	=> $this->metaVal['carLocation']
			);
		}
		##################################################
		# LIST CURRENT USER CLASSIFIEDS jp=my-classifieds
		##################################################
		if( isset( $_GET['jp'] ) && $_GET['jp'] == 'my-classifieds' ) {
			
			$author = get_current_user_id();
			$author = 'post_type=car-classifieds&author='.$author;
			
			return $author;
		}
		
		$order_date = isset( $_GET['order-date'] ) ? esc_attr( $_GET['order-date'] ) : '';
		
		// If search button fired, show cars by search parameters
		if( isset( $_POST['search-cars'] ) ) {
			
			// Format query to output selected cars
			$this->Query = array(
				'post_type'			=> 'car-classifieds',
				'posts_per_page' 	=> $settings['cars_per_page'],
				'orderby' 			=> 'date',
				'order' 			=> strtoupper( $order_date ),
				'tax_query' 		=> array( $this->taxQuery['carMake'], $this->taxQuery['carModel'] ),
				'meta_query' 		=> array( 
					$this->metaQuery['carYear'],
					$this->metaQuery['carFuel'],
					$this->metaQuery['carCondition'],
					$this->metaQuery['carPrice'],
					$this->metaQuery['carMileage'],
					$this->metaQuery['carLocation']
				),
				'posts_per_page' 	=> '10',
				'paged' 			=> $paged
			);
			
		} else { // Else if search not fired show all cars
			
			/**
			 * Car Make [jpro_cars make='Audi']
			 * ================================
			 * @since 0.4
			 */
			if( $make !== 'all' ) {
				
				// Remove blank space from make
				if( preg_match( '/ /', $make ) )
					$make = str_replace( ' ', '', $make );
				
				// If multiple makes added
				if( preg_match( '/,/', $make ) )
					$make = explode( ',', $make );
				
				// Convert string to lowercase
				if( is_array( $make ) ) {
					$make = array_map( 'strtolower', $make );
				}else{
					$make = strtolower( $make );
				}
				
				// Format meta query
				$carMake = array(
					'taxonomy'	=> 'car-model', 
					'field' 	=> 'slug', 
					'terms' 	=> $make
				);
			}
			
			/**
			 * Car Model [jpro_cars model='a4']
			 * ================================
			 * @since 0.4
			 */
			if( $model !== 'all' ) {
				
				// Remove blank space from model
				if( preg_match( '/ /', $model ) )
					$model = str_replace( ' ', '', $model );
				
				// If multiple models added
				if( preg_match( '/,/', $model ) )
					$model = explode( ',', $model );
				
				// Convert string to lowercase
				if( is_array( $model ) ) {
					$model = array_map( 'strtolower', $model );
				}else{
					$model = strtolower( $model );
				}
				
				// Format meta query
				$carModel = array(
					'taxonomy'	=> 'car-model',
					'field'		=> 'slug',
					'terms'		=> $model
				);
			}
			
			/**
			 * Car Year [jpro_cars year='2015']
			 * ================================
			 * @since 0.4
			 */
			if( $year !== 'all' ) {
				
				// Remove blank space from year
				if( preg_match( '/ /', $year ) )
					$year = str_replace( ' ', '', $year );
				
				// If multiple years added
				if( preg_match( '/,/', $year ) ) 
					$year = explode( ',', $year );
				
				// Format meta query
				$carYear = array(
					'key'	=> '_car_year',
					'value'	=> $year
				);
			}
			
			/**
			 * Car Fuel [jpro_cars fuel='diesel']
			 * =========================================
			 * @since 0.7
			 */
			if( $fuel !== 'all' ) {
				
				// Remove blank space from fuel
				if( preg_match( '/ /', $fuel ) )
					$fuel = str_replace( ' ', '', $fuel );
				
				// If multiple fuel added
				if( preg_match( '/,/', $fuel ) )
					$fuel = explode( ',', $fuel );
				
				// Convert string to lowercase
				if( is_array( $fuel ) ) {
					$fuel = array_map( 'strtolower', $fuel );
				}else{
					$fuel = strtolower( $fuel );
				}
				
				// Format Meta Query
				$carFuel = array(
					'key'	=> '_car_fuel',
					'value'	=> $fuel
				);
			}
			
			/**
			 * Car Condition [jpro_cars condition='new']
			 * =========================================
			 * @since 0.4
			 */
			if( $condition !== 'all' ) {
				
				// Remove blank space from condition
				if( preg_match( '/ /', $condition ) )
					$condition = str_replace( ' ', '', $condition );
				
				// If multiple conditions added
				if( preg_match( '/,/', $condition ) )
					$condition = explode( ',', $condition );
				
				// Convert string to lowercase
				if( is_array( $condition ) ) {
					$condition = array_map( 'strtolower', $condition );
				}else{
					$condition = strtolower( $condition );
				}
				
				// Format meta query
				$carCondition = array(
					'key'	=> '_car_condition',
					'value'	=> $condition
				);
			}
			
			/**
			 * Car Mileage [jpro_cars mileage='120000']
			 * ========================================
			 * @since 0.4
			 */
			if( $mileage !== 'all' ) {
				
				// Remove blank space from mileage
				if( preg_match( '/ /', $mileage ) )
					$mileage = str_replace( ' ', '', $mileage );
				
				// Remove dot from mileage
				if( preg_match( '/./', $mileage ) )
					$mileage = str_replace( '.', '', $mileage );
				
				// If multiple mileages added
				if( preg_match( '/,/', $mileage ) )
					$mileage = explode( ',', $mileage );
				
				// Format meta query
				$carMileage = array(
					'key'	=> '_car_mileage',
					'value'	=> $mileage
				);
			}
			
			/**
			 * Car Price [jpro_cars price='15000']
			 * ===================================
			 * @since 0.4
			 */
			if( $price !== 'all' ) {
				
				// Remove blank space from price
				if( preg_match( '/ /', $price ) )
					$price = str_replace( ' ', '', $price );
				
				// Remove dot from price
				if( preg_match( '/./', $price ) )
					$price = str_replace( '.', '', $price );
				
				// If multiple prices added
				if( preg_match( '/,/', $price ) )
					$price = explode( ',', $price );
				
				// Format meta query
				$carPrice = array(
					'key'	=> '_car_price',
					'value'	=> $price
				);
			}
			
			$carMake		= isset( $carMake ) ? $carMake : '';
			$carModel		= isset( $carModel ) ? $carModel : '';
			$carYear		= isset( $carYear ) ? $carYear : '';
			$carFuel		= isset( $carFuel ) ? $carFuel : '';
			$carCondition	= isset( $carCondition ) ? $carCondition : '';
			$carMileage		= isset( $carMileage ) ? $carMileage : '';
			$carPrice		= isset( $carPrice ) ? $carPrice : '';
			
			// Format query to output all cars
			$this->Query = array(
				'post_type' 		=> 'car-classifieds',
				'posts_per_page' 	=> $settings['cars_per_page'], 
				'orderby' 			=> 'date', 
				'order' 			=> strtoupper( $order_date ),
				'tax_query'			=> array( $carMake, $carModel ),
				'meta_query'		=> array(
					$carYear,
					$carFuel,
					$carCondition,
					$carMileage,
					$carPrice
				),
				'posts_per_page' 	=> '10',
				'paged' 			=> $paged
			);
			
		}
		return $this->Query;
	}
	
	/**
	 * Loop car make's in text loop
	 * OUTPUT: eg: Audi, BMW ...
	 * 
	 * @return array
	 */
	 public function loop_make() {
		
		$tax_terms = get_terms( 
			'car-model', 
			'orderby=name&order=ASC&hide_empty=0&hierarchical=0' 
		);
		
		if( ! empty( $tax_terms ) && ! is_wp_error( $tax_terms ) ) {
			foreach ( $tax_terms as $tax_term ) {
				
				// Loop only parent terms
				if( $tax_term->parent == '0' ) {
					$terms[] = $tax_term;
				}
			}
		
			return $terms;
		}
	 }
	
	/**
	 * Loop car model's in text loop
	 * OUTPUT: A4, X5 ...
	 *
	 * @return array
	 */
	 public function loop_model() {
		
		$tax_terms = get_terms( 
			'car-model', 
			'orderby=name&order=ASC&hide_empty=0&hierarchical=0'
		);
		
		if( ! empty( $tax_terms ) && ! is_wp_error( $tax_terms ) ) {
			foreach ( $tax_terms as $tax_term ) {
				
				// Loop only child terms
				if( $tax_term->parent !== '0' ) {
					$terms[] = $tax_term;
				}
				
			}
			
			return $terms;
		}
	 }
	 
	/**
	 * Loop car year in number loop
	 * OUTPUT: 1930, 1931 ...
	 *
	 * @return array
	 */
	 public function year() {
		$numbers = range( 2015, 1930 );
	
		foreach( $numbers as $number ) {
			$array[] = $number;
		}
		
		return $array;
	 }
	 
	/**
	 * Get car make on single post (Frontend)
	 *
	 * @return array
	 */
	 public function get_make() {
		global $post;
		
		$args = array(
			'orderby'	=> 'name',
			'order'		=> 'ASC',
			'fields'	=> 'all'
		);
		
		$tax_terms = wp_get_post_terms($post->ID, 'car-model', $args);
		
		foreach ($tax_terms as $tax_term) {
			
			// Loop only parent terms
			if( $tax_term->parent == '0' ) {
				return  $tax_term->name;
			}
			
		}
	 }
	 
	/**
	 * Get car model on single post (Frontend)
	 * 
	 * @return array
	 */
	 public function get_model() {
		global $post;
		
		$args = array(
			'orderby'	=> 'name',
			'order'		=> 'ASC',
			'fields'	=> 'all'
		);
		
		$tax_terms = wp_get_post_terms($post->ID, 'car-model', $args);
		
		foreach ($tax_terms as $tax_term) {
			
			// Loop only child terms
			if( $tax_term->parent ) {
				return  $tax_term->name;
			}
			
		}
	 }
	 
	/**
	 * Get car meta details (Frontend)
	 * Loop (if ID provided) else Single pages
	 * 
	 * @return string
	 */
	public function get_meta( $key ) {
		return sanitize_text_field( get_post_meta( get_the_ID(), $key, true ) );
	}
	
	/**
	 * Get properly formated car price
	 * ===============================
	 * @since 0.4
	 */
	public function get_price( $price = NULL ) {
		$currencies = unserialize( get_option( '_jp_cars_currencies' ) ); // Get currencies from database
		
		$car_currency	= $this->get_meta('_car_currency'); // Get car currency
		
		// If car price not specified, let's pull price from database
		$car_price = isset( $price ) ? $price : $this->get_meta('_car_price');
		
		// If car price not set, set by default 100
		if( empty( $car_price ) ) $car_price = 100;
		
		// Format price like 100,00
		$car_price = number_format($car_price);
		
		// Take only currency we are interest in and get details from it
		$currency = $currencies[$car_currency];
		
		// If currency symbol or position missing let's set default ones
		if( !$currency['symbol'] ) $currency['symbol'] = '$';
		if( !$currency['position'] ) $currency['position'] = 'right';
		
		// Format car price & currency output
		switch( $currency['position'] ):
		
			case $currency['position'] == 'left':
				 $price = $currency['symbol'].$car_price;
			break;
			
			case $currency['position'] == 'right':
				 $price = $car_price.$currency['symbol'];
			break;
			
			case $currency['position'] == 'left_space':
				 $price = $currency['symbol'].' '.$car_price;
			break;
			
			case $currency['position'] == 'right_space':
				 $price = $car_price.' '.$currency['symbol'];
			break;
		
		endswitch;
		
		return $price;
	}
	
	/**
	 * Get Meta Minimum && Maximum Values
	 * Loop Trough PostMeta, Take All Values
	 * & Return Min & Max Values
	 *
	 * @since 0.3
	 * @return array
	 */
	public function get_meta_min_max( $meta_key ) {
		global $wpdb;
		
		$meta_val = $wpdb->get_col( $wpdb->prepare(
			"
			SELECT meta_value 
			FROM $wpdb->postmeta 
			WHERE meta_key = %s
			",
			$meta_key
		) );
		
		// If found results into database
		if( !empty( $meta_val[0] ) ) {
			foreach( $meta_val as $values ) {
				$value[] = $values;
			}
			
			$value = array_filter( $value ); // Filter array
			
			// Take value min & max values
			$value_min = min( $value );
			$value_max = max( $value );
		}

		// If price min empty
		if( empty( $value_min ) ) {
			$value_min = '1';
		}

		// If price max empty
		if( empty( $value_max ) ) {
			$value_max = '500000';
		}

		return array(
			'min' => $value_min,
			'max' => $value_max
		);
	}
	
	/**
	 * Generate Years for DropDown Select
	 *
	 * @since 1.3
	 */
	public static function dropdown_years( $selected, $from = 2017, $to = 1900 ) {
		$years = range( $from, $to );
		foreach( $years as $year ) {
			echo '<option value="'. esc_attr( $year ) .'" '. selected( $selected, $year, false ) .'>' . $year . '</option>';
		}
	}
	
	/**
	 * Add Strings for Translation
	 *
	 * @since 0.4
	 */
	public function translation() {
		return array(
			__( 'fixed', 'jprocars' ),
			__( 'automatic', 'jprocars' ),
			__( 'electric', 'jprocars' ),
			__( 'diesel', 'jprocars' ),
			__( 'gasoline', 'jprocars' ),
			__( 'hybrid', 'jprocars' ),
			__( 'semi-automatic', 'jprocars' )
		);
	}
} 
global $JP_Cars;
$JP_Cars = new JP_Cars();
?>