<?php 
if( ! defined( 'ABSPATH' ) ) 
	exit; // Exit if accessed directly

global $JP_Country;
/*
	1. Let's check if user is logged in or not, if not show him message to login or register
	2. Let's check if current user have membership package
	3. Let's check if current user membership package is active or expired
	4. Let's check if current user classifieds limit is exceeded or not
	5. If user classfieds post limit is reached on free package, disable user purchase free package again. No cheating please :)
	
	Step 5 is located on select_membership.php line 160 - if user selected package is FREE and he want's to choose again FREE package to overwrite limits, the checkout button will be disabled until free package expire.
	
	If everything is ok from first 3 steps above, current user can proceed with adding classifieds.
*/
	$Settings 			= new JP_Settings();
	$general_settings	= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true ); // Get general settings
	$validate			= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_validation', true ); // Get validation settings
	$membership			= $Settings->getSettings( 'WP_USERMETA', '_jp_cars_membership', true ); // Get current user membership package details
	$currencies			= unserialize( get_option( '_jp_cars_currencies' ) );
	$current_date 		= date('Y-m-d'); // get current day date
	
	$validation = jpro::validation( $validate );
	
	// Step 1 (@since 0.3)
	if( !is_user_logged_in() ) {
		$problem 	= true;
		$login 		= '<a href="'.esc_url( wp_login_url( get_permalink( $general_settings['cc_page_id'] ) ) ).'">'.__( 'login', 'jprocars' ).'</a>';
		$register 	= '<a href="'.wp_registration_url().'">'.__( 'register', 'jprocars' ).'</a>';
		$message 	= __( 'You must', 'jprocars' ).' '.$login.' '.__( 'or', 'jprocars' ).' '.$register.' '.__( 'first.', 'jprocars' );
	}
	
	// Step 2
	if( is_user_logged_in() && !$membership ) {
		$problem = true; 
		$message = __( 'You must buy membership first!', 'jprocars' );
	}
	
	// Step 3
	if( is_user_logged_in() && $membership && strtotime( $membership['expire_date'] ) < strtotime( $current_date ) && $membership['expire_date'] !== 'never' ) {
		$problem = true; 
		$message = __( 'Your membership package has expired. Please choose new membership package!', 'jprocars' );
	}
		
	// Step 4
	if( is_user_logged_in() && $membership && $membership['classifieds_limit'] < 1 && $membership['classifieds_limit'] !== 'unlimited' ) { // If limit is lower than 1 there is problem
		$problem  = true;
		$err_code = 'classifieds_limit_exceeded';
		$message  = __( 'You have exceeded your classifieds post limit! You can purchase new membership package.', 'jprocars' );
	}
	
	$error = isset( $error ) ? $error : '';
	
	// If user have no membership package or want to change current membership package
	if( isset( $problem ) && $problem || isset( $_GET['jp'] ) && $_GET['jp'] == 'change-membership' ):
		
		require_once 'select_membership.php';
	
	################################################################################
	# Current user have a membership package and its allowed to add new classifieds
	################################################################################
	
	else: // If user have membership package, let him to add new classifieds ?>
	
	<?php 
		##########################################################
		# Sanitize all submited data & add it into array $details
		##########################################################
		$details = jpro::sanitize_post();
		
		$retrived_nonce = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '';
		
		if( isset( $_POST['submit-car'] ) &&  wp_verify_nonce( $retrived_nonce, 'add_classified' ) ):
			######################################################
			# Check if all required fields are submited
			######################################################
			$error['_car_title'] = '';
			if( empty( $details['_car_title'] ) ):
				$error['_car_title'] = true;
			endif;
			
			$error['_car_description'] = '';
			if( empty( $details['_car_description'] ) ):
				$error['_car_description'] = true;
			endif;
			
			$error['_car_make'] = '';
			if( empty( $details['_car_make'] ) ):
				$error['_car_make'] = true;
			endif;
			
			$error['_car_model'] = '';
			if( empty( $details['_car_model'] ) ):
				$error['_car_model'] = true;
			endif;
			
			if( $validation['car-version_req'] && $validation['car-version_show'] ) {
				$error['_car_version'] = '';
				if( empty( $details['_car_version'] ) ):
					$error['_car_version'] = true;
				endif;
			}
			
			if( $validation['car-year_req'] && $validation['car-year_show'] ) {
				$error['_car_year'] = '';
				if( empty( $details['_car_year'] ) ):
					$error['_car_year'] = true;
				endif;
			}
			
			if( $validation['car-transmission_req'] && $validation['car-transmission_show'] ) {
				$error['_car_transmission'] = '';
				if( empty( $details['_car_transmission'] ) ):
					$error['_car_transmission'] = true;
				endif;
			}
			
			if( $validation['car-doors_req'] && $validation['car-doors_show'] ) {
				$error['_car_doors'] = '';
				if( empty( $details['_car_doors'] ) ):
					$error['_car_doors'] = true;
				endif;
			}
			
			if( $validation['car-fuel_req'] && $validation['car-fuel_show'] ) {
				$error['_car_fuel'] = '';
				if( empty( $details['_car_fuel'] ) ):
					$error['_car_fuel'] = true;
				endif;
			}
			
			if( $validation['car-condition_req'] && $validation['car-condition_show'] ) {
				$error['_car_condition'] = '';
				if( empty( $details['_car_condition'] ) ):
					$error['_car_condition'] = true;
				endif;
			}
			
			if( $validation['car-vin_req'] && $validation['car-vin_show'] ) {
				$error['_car_vin'] = '';
				if( empty( $details['_car_vin'] ) ):
					$error['_car_vin'] = true;
				endif;
			}
			
			if( $validation['car-drive_req'] && $validation['car-drive_show'] ) {
				$error['_car_drive'] = '';
				if( empty( $details['_car_drive'] ) ):
					$error['_car_drive'] = true;
				endif;
			}
			
			if( $validation['car-price_req'] && $validation['car-price_show'] ) {
				$error['_car_price'] = '';
				if( empty( $details['_car_price'] ) ):
					$error['_car_price'] = true;
				endif;
			}
			
			if( $validation['car-price-type_req'] && $validation['car-price-type_show'] ) {
				$error['_car_price_type'] = '';
				if( empty( $details['_car_price_type'] ) ):
					$error['_car_price_type'] = true;
				endif;
			}
			
			if( $validation['car-currency_req'] && $validation['car-currency_show'] ) {
				$error['_car_currency'] = '';
				if( empty( $details['_car_currency'] ) ):
					$error['_car_currency'] = true;
				endif;
			}
			
			if( $validation['car-warranty_req'] && $validation['car-warranty_show'] ) {
				$error['_car_warranty'] = '';
				if( empty( $details['_car_warranty'] ) ):
					$error['_car_warranty'] = true;
				endif;
			}
			
			if( $validation['car-mileage_req'] && $validation['car-mileage_show'] ) {
				$error['_car_mileage'] = '';
				if( empty( $details['_car_mileage'] ) ):
					$error['_car_mileage'] = true;
				endif;
			}
			
			if( $validation['car-engine_req'] && $validation['car-engine_show'] ) {
				$error['_car_engine'] = '';
				if( empty( $details['_car_engine'] ) ):
					$error['_car_engine'] = true;
				endif;
			}
			
			if( $validation['car-horsepower_req'] && $validation['car-horsepower_show'] ) {
				$error['_car_horsepower'] = '';
				if( empty( $details['_car_horsepower'] ) ):
					$error['_car_horsepower'] = true;
				endif;
			}
			
			if( $validation['car-seats_req'] && $validation['car-seats_show'] ) {
				$error['_car_seats'] = '';
				if( empty( $details['_car_seats'] ) ):
					$error['_car_seats'] = true;
				endif;
			}
			
			if( $validation['car-color_req'] && $validation['car-color_show'] ) {
				$error['_car_color'] = '';
				if( empty( $details['_car_color'] ) ):
					$error['_car_color'] = true;
				endif;
			}
			
			$error['_car_images'] = '';
			if( empty( $details['_car_images'] ) ):
				$error['_car_images'] = true;
			endif;
			
			if( $validation['first-name_req'] && $validation['first-name_show'] ) {
				$error['_seller_first_name'] = '';
				if( empty( $details['_seller_first_name'] ) ):
					$error['_seller_first_name'] = true;
				endif;
			}
			
			if( $validation['last-name_req'] && $validation['last-name_show'] ) {
				$error['_seller_last_name'] = '';
				if( empty( $details['_seller_last_name'] ) ):
					$error['_seller_last_name'] = true;
				endif;
			}
			
			if( $validation['seller-company_req'] && $validation['seller-company_show'] ) {
				$error['_seller_company'] = '';
				if( empty( $details['_seller_company'] ) ):
					$error['_seller_company'] = true;
				endif;
			}
			
			$error['_seller_email'] = '';
			if( empty( $details['_seller_email'] ) ):
				$error['_seller_email'] = true;
			endif;
			
			if( $validation['seller-phone_req'] && $validation['seller-phone_show'] ) {
				$error['_seller_phone'] = '';
				if( empty( $details['_seller_phone'] ) ):
					$error['_seller_phone'] = true;
				endif;
			}
			
			if( $validation['seller-country_req'] && $validation['seller-country_show'] ) {
				$error['_seller_country'] = '';
				if( empty( $details['_seller_country'] ) ):
					$error['_seller_country'] = true;
				endif;
			}
			
			if( $validation['seller-state_req'] && $validation['seller-state_show'] ) {
				$error['_seller_state'] = '';
				if( empty( $details['_seller_state'] ) ):
					$error['_seller_state'] = true;
				endif;
			}
			
			if( $validation['seller-town_req'] && $validation['seller-town_show'] ) {
				$error['_seller_town'] = '';
				if( empty( $details['_seller_town'] ) ):
					$error['_seller_town'] = true;
				endif;
			}
			######################################################
			# If errors found when submit new car, show notice
			######################################################
			if( $error && array_filter( $error ) ) {
				echo '<div class="alert alert-danger">';
					printf( '<strong>%s:</strong> %s', __( 'Error', 'jprocars' ), __( 'please correct errors found below!', 'jprocars' ) );
				echo '</div>';
			######################################################
			# Else if no errors save post to database
			######################################################
			}else{
				
				// Get car equipment id's and format it for tax_input
				if( !empty( $_POST['car-equipment'] ) ) {
					$equipments = $_POST['car-equipment'];
					if( is_array( $equipments ) ) {
						foreach( $equipments as $key => $value ) {
							$equipment[] = $value;
						}
						$equipment = implode(",", $equipment);
					}else{
						$equipment = $_POST['car-equipment'];
					}
				}else{
					$equipment = '';
				}
				
				// Format post - sanitization handled by Wordpress
				$my_post = array(
					'post_title'	=> wp_strip_all_tags($details['_car_title']),
					'post_content'	=> $details['_car_description'],
					'post_status'	=> 'publish',
					'post_author'	=> get_current_user_id(),
					'post_type'		=> 'car-classifieds',
					'tax_input'		=> array( 'car-model' => $details['_car_make'].','.$details['_car_model'], 'car-equipment' => $equipment )
				);
				
				// Insert post into database
				$post_id = wp_insert_post( $my_post );
				if( $post_id ) {
					
					#######################################################################
					# INSERT POST META - @updated since v0.7
					#######################################################################
					if( $validation['car-version_show'] ) {
						update_post_meta( $post_id, '_car_version', $details['_car_version'] );
					}
					if( $validation['car-year_show'] ) {
						update_post_meta( $post_id, '_car_year', $details['_car_year'] );
					}
					if( $validation['car-transmission_show'] ) {
						update_post_meta( $post_id, '_car_transmission', $details['_car_transmission'] );
					}
					if( $validation['car-doors_show'] ) {
						update_post_meta( $post_id, '_car_doors', $details['_car_doors'] );
					}
					if( $validation['car-fuel_show'] ) {
						update_post_meta( $post_id, '_car_fuel', $details['_car_fuel'] );
					}
					if( $validation['car-condition_show'] ) {
						update_post_meta( $post_id, '_car_condition', $details['_car_condition'] );
					}
					if( $validation['car-drive_show'] ) {
						update_post_meta( $post_id, '_car_drive', $details['_car_drive'] );
					}
					if( $validation['car-color_show'] ) {
						update_post_meta( $post_id, '_car_color', $details['_car_color'] );
					}
					if( $validation['car-price_show'] ) {
						update_post_meta( $post_id, '_car_price', $details['_car_price'] );
					}
					if( $validation['car-price-type_show'] ) {
						update_post_meta( $post_id, '_car_price_type', $details['_car_price_type'] );
					}
					if( $validation['car-warranty_show'] ) {
						update_post_meta( $post_id, '_car_warranty', $details['_car_warranty'] );
					}
					if( $validation['car-currency_show'] ) {
						update_post_meta( $post_id, '_car_currency', $details['_car_currency'] );
					}
					if( $validation['car-mileage_show'] ) {
						update_post_meta( $post_id, '_car_mileage', $details['_car_mileage'] );
					}
					if( $validation['car-vin_show'] ) {
						update_post_meta( $post_id, '_car_vin', $details['_car_vin'] );
					}
					if( $validation['car-engine_show'] ) {
						update_post_meta( $post_id, '_car_engine', $details['_car_engine'] );
					}
					if( $validation['car-horsepower_show'] ) {
						update_post_meta( $post_id, '_car_horsepower', $details['_car_horsepower'] );
					}
					if( $validation['car-seats_show'] ) {
						update_post_meta( $post_id, '_car_seats', $details['_car_seats'] );
					}
					if( $validation['first-name_show'] ) {
						update_post_meta( $post_id, '_seller_first_name', $details['_seller_first_name'] );
					}
					if( $validation['last-name_show'] ) {
						update_post_meta( $post_id, '_seller_last_name', $details['_seller_last_name'] );
					}
					if( $validation['seller-phone_show'] ) {
						update_post_meta( $post_id, '_seller_phone', $details['_seller_phone'] );
					}
					if( $validation['seller-company_show'] ) {
						update_post_meta( $post_id, '_seller_company', $details['_seller_company'] );
					}
					if( $validation['seller-country_show'] ) {
						update_post_meta( $post_id, '_seller_country', $details['_seller_country'] );
					}
					if( $validation['seller-state_show'] ) {
						update_post_meta( $post_id, '_seller_state', $details['_seller_state'] );
					}
					if( $validation['seller-town_show'] ) {
						update_post_meta( $post_id, '_seller_town', $details['_seller_town'] );
					}
					
					update_post_meta( $post_id, '_car_images', $details['_car_images'] );
					update_post_meta( $post_id, '_seller_email', $details['_seller_email'] );
					
					// Lets update user post limit by -1
					$user_meta = unserialize( get_user_meta( get_current_user_id(), '_jp_cars_membership', true ) );
					
					// Set post limit - 1
					$post_limit = $user_meta['classifieds_limit'];
					
					if( $post_limit !== 'unlimited' ) {
						
						$post_limit = $post_limit - 1;
					
						// Format meta value with new post limit number
						$meta_value = array(
							'id'						=> $user_meta['id'],
							'title'						=> $user_meta['title'],
							'type'						=> $user_meta['type'],
							'price'						=> $user_meta['price'],
							'purchase_date'				=> $user_meta['purchase_date'],
							'expire_date'				=> $user_meta['expire_date'],
							'classifieds_limit'			=> $post_limit,
							'classifieds_expire'		=> $user_meta['classifieds_expire'],
							'classifieds_image_limit'	=> $user_meta['classifieds_image_limit']
						);
					
						// Update user meta with new post limit
						update_user_meta( get_current_user_id(), '_jp_cars_membership', serialize( $meta_value ) );
					}
					
					echo '<div class="alert alert-success">';
						printf( '<strong>%s</strong> %s %s <a href="%s">%s</a>', __( 'Thank you! ', 'jprocars' ), __( 'Your classified submission is successful.', 'jprocars' ), __( 'Check', 'jprocars' ), esc_url( add_query_arg( 'jp', 'my-classifieds', get_permalink( $general_settings['cc_page_id'] ) ) ), __( 'My Classifieds', 'jprocars' ) );
					echo '</div>';
					
					// Let's send user "successful classified submition" email with custom message
					$email_settings	= unserialize( get_option( '_jp_cars_email_templates' ) );
					$admin_email 	= get_option( 'admin_email' );
					
					$to 		= $details['_seller_email'];
					$subject 	= $email_settings['classified_submited_subject'];
					$message	= $email_settings['classified_submited_message'];
					$headers[]	= 'FROM: ' . get_bloginfo('name') . '<' . $admin_email . '>';
					
					// Replace user tags with current user details
					$subject = str_replace( '{first_name}', $details['_seller_first_name'], $subject );
					$subject = str_replace( '{last_name}', $details['_seller_last_name'], $subject );
					$subject = str_replace( '{email}', $details['_seller_email'], $subject );
					$subject = str_replace( '{classified_ID}', $post_id, $subject );
					$subject = str_replace( '{classified_title}', $details['_car_title'], $subject );
					$subject = str_replace( '{classified_description}', $details['_car_description'], $subject );
					$subject = str_replace( '{classified_url}', esc_url( get_permalink( $post_id ) ), $subject );
					
					$message = str_replace( '{first_name}', $details['_seller_first_name'], $message );
					$message = str_replace( '{last_name}', $details['_seller_last_name'], $message );
					$message = str_replace( '{email}', $details['_seller_email'], $message );
					$message = str_replace( '{classified_ID}', $post_id, $message );
					$message = str_replace( '{classified_title}', $details['_car_title'], $message );
					$message = str_replace( '{classified_description}', $details['_car_description'], $message );
					$message = str_replace( '{classified_url}', esc_url( get_permalink( $post_id ) ), $message );
					
					wp_mail( $to, $subject, $message, $headers );
				}
			}
		######################################################
		# If security check fails output error message
		######################################################
		elseif( isset( $_POST['submit-car'] ) && !wp_verify_nonce( $retrived_nonce, 'add_classified' ) && $class == 'error' ):
			echo '<div class="alert alert-danger">';
				printf( '<strong>%s:</strong> %s', __( 'Error', 'jprocars' ), __( 'Failed security check!', 'jprocars' ) );
			echo '</div>';
		endif;
	?>
	
	<?php if( isset( $_POST['submit-car'] ) || isset( $_POST['add-new-classified'] ) || isset( $_GET['jp'] ) && $_GET['jp'] == 'add-new-classified' ): // Show add classified options only if insert post is not completed ?>

	<!-- Car Make && Model jQuery -->
	<script> 
	jQuery(document).ready(function($){
		<?php if( jpro_get_car_makes() ): foreach( jpro_get_car_makes() as $make ): ?>
			<?php $termchildren = get_term_children( $make->term_id, 'car-model' ); ?>
			var <?php echo str_replace( '-', '_', $make->slug ); ?> = [
				<?php foreach( $termchildren as $child ): ?>
					<?php $model = get_term_by( 'id', $child, 'car-model' ); ?>
					{display: "<?php echo $model->name; ?>", value: "<?php echo $model->term_id; ?>" },
				<?php endforeach; ?>
			];
		<?php endforeach; endif;?>
		
		//If parent option is changed
		$('#jpro_cars_make').change(function() {
				var parent = $(this).val(); //get option value from parent 
				
				switch(parent){ //using switch compare selected option and populate child
						
					  <?php if( jpro_get_car_makes() ): foreach( jpro_get_car_makes() as $make ): ?>
						case '<?php echo $make->term_id; ?>':
						  list(<?php echo str_replace( '-', '_', $make->slug ); ?>);
						  break;
					  <?php endforeach; endif; ?>
					  
					default: //default child option is blank
						$("#car_model").html('<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>');  
						break;
				   }
		});

		//function to populate child select box
		function list(array_list)
		{
			jQuery("#car_model").html(""); //reset child options
			jQuery(array_list).each(function (i) { //populate child options 
				jQuery("#car_model").append("<option value=\""+array_list[i].value+"\">"+array_list[i].display+"</option>");
			});
		}
	});
	</script><!-- / Car Make && Model jQuery -->
	
	<!-- Car Submit Form -->
	<div id="add-classified-wrapper" class="row">
	<form <?php if( $general_settings['cc_page_id'] ){ echo 'action="'. esc_url( get_permalink( $general_settings['cc_page_id'] ) ) .'"';} ?> id="add_classified" method="post">
		
		<input name="add-new-classified" type="hidden">
		
		<?php echo wp_nonce_field('add_classified'); ?>
		
		<!-- Car Details -->
		<div id="jpro-step-1" class="jpro-panel">
			
			<div class="jpro-panel-heading">
				<span class="jpro-panel-title"><?php _e( 'Car Details', 'jprocars' ); ?></span>
			</div>
			
			<div class="jpro-panel-body">
				<div class="jpro-form-horizontal">
					
					<!-- Car Make -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Make', 'jprocars' ); ?> <span class="required">*</span></label>
						<div class="col-lg-9">
							<select name="car-make" id="jpro_cars_make" class="jpro-form-control <?php if($error && $error['_car_make']) echo 'error'; ?>">
								<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
								<?php if( jpro_get_car_makes() ): foreach( jpro_get_car_makes() as $make ): ?>
								<option value="<?php echo $make->term_id; ?>"><?php echo $make->name; ?></option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div><!-- / Car Make -->
					
					<!-- Car Model -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Model', 'jprocars' ); ?> <span class="required">*</span></label>
						<div class="col-lg-9">
							<select name="car-model" id="car_model" class="jpro-form-control <?php if($error && $error['_car_model']) echo 'error'; ?>">
								<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
							</select>
						</div>
					</div><!-- / Car Model -->
					
					<?php if( $validation['car-version_show'] ): ?>
					<!-- Car Version -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Version', 'jprocars' ); ?> <?php if( $validation['car-version_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<input name="car-version" type="text" placeholder="eg: 1.6 hdi" value="<?php echo $details['_car_version']; ?>" class="jpro-form-control <?php if( $validation['car-version_req'] && $error && $error['_car_version'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / Car Version -->
					<?php endif; ?>
					
					<?php if( $validation['car-year_show'] ): ?>
					<!-- Car Year -->
					<div class="jpro-form-group">
						
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Year', 'jprocars' ); ?> <?php if( $validation['car-year_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="car-year" class="jpro-form-control <?php if( $validation['car-year_req'] && $error && $error['_car_year'] ): ?>error<?php endif; ?>">
								
								<?php JP_Cars::dropdown_years( $details['_car_year'] ); ?>
								
							</select>
						</div>
					</div><!-- / Car Year -->
					<?php endif; ?>
					
					<?php if( $validation['car-mileage_show'] ): ?>
					<!-- Car Mileage -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Mileage', 'jprocars' ); ?> <?php if( $validation['car-mileage_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<input name="car-mileage" type="text" placeholder="eg: 100000" value="<?php echo $details['_car_mileage']; ?>" class="jpro-form-control <?php if( $validation['car-mileage_req'] && $error && $error['_car_mileage'] ): ?>error<?php endif; ?>">
							<span class="errmileage"></span>
						</div>
					</div><!-- / Car Mileage -->
					<?php endif; ?>
					
					<?php if( $validation['car-fuel_show'] ): ?>
					<!-- Car Fuel -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Fuel', 'jprocars' ); ?> <?php if( $validation['car-fuel_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="car-fuel" class="jpro-form-control <?php if( $validation['car-fuel_req'] && $error && $error['_car_fuel'] ): ?>error<?php endif; ?>">
								<option value="diesel" <?php selected( 'diesel', $details['_car_fuel'], true ); ?>><?php _e( 'Diesel', 'jprocars' ); ?></option>
								<option value="electric" <?php selected( 'electric', $details['_car_fuel'], true ); ?>><?php _e( 'Electric', 'jprocars' ); ?></option>
								<option value="gasoline" <?php selected( 'gasoline', $details['_car_fuel'], true ); ?>><?php _e( 'Gasoline', 'jprocars' ); ?></option>
								<option value="hybrid" <?php selected( 'hybrid', $details['_car_fuel'], true ); ?>><?php _e( 'Hybrid', 'jprocars' ); ?></option>
							</select>
						</div>
					</div><!-- / Car Fuel -->
					<?php endif; ?>
					
					<?php if( $validation['car-engine_show'] ): ?>
					<!-- Car Engine -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Engine cm3', 'jprocars' ); ?> <?php if( $validation['car-engine_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<input name="car-engine" type="text" placeholder="eg: 1900" value="<?php echo $details['_car_engine']; ?>" class="jpro-form-control <?php if( $validation['car-engine_req'] && $error && $error['_car_engine'] ): ?>error<?php endif; ?>">
							<span class="errengine"></span>
						</div>
					</div><!-- / Car Engine -->
					<?php endif; ?>
					
					<?php if( $validation['car-horsepower_show'] ): ?>
					<!-- Car Horsepower -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Horsepower hp', 'jprocars' ); ?> <?php if( $validation['car-horsepower_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<input name="car-horsepower" type="text" placeholder="eg: 200" value="<?php echo $details['_car_horsepower']; ?>" class="jpro-form-control <?php if( $validation['car-horsepower_req'] && $error && $error['_car_horsepower'] ): ?>error<?php endif; ?>">
							<span class="errhorsepower"></span>
						</div>
					</div><!-- / Car Horsepower -->
					<?php endif; ?>
					
					<?php if( $validation['car-transmission_show'] ): ?>
					<!-- Car Transmission -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Transmission', 'jprocars' ); ?> <?php if( $validation['car-transmission_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="car-transmission" class="jpro-form-control <?php if( $validation['car-transmission_req'] && $error && $error['_car_transmission'] ): ?>error<?php endif; ?>">
								<option value="automatic" <?php selected( 'automatic', $details['_car_transmission'], true ); ?>><?php _e( 'Automatic', 'jprocars' ); ?></option>
								<option value="manual" <?php selected( 'manual', $details['_car_transmission'], true ); ?>><?php _e( 'Manual', 'jprocars' ); ?></option>
								<option value="semi-automatic" <?php selected( 'semi-automatic', $details['_car_transmission'], true ); ?>><?php _e( 'Semi-Automatic', 'jprocars' ); ?></option>
							</select>
						</div>
					</div><!-- / Car Transmission -->
					<?php endif; ?>
					
					<?php if( $validation['car-drive_show'] ): ?>
					<!-- Car Drive -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Drive', 'jprocars' ); ?> <?php if( $validation['car-drive_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="car-drive" class="jpro-form-control <?php if( $validation['car-drive_req'] && $error && $error['_car_drive'] ): ?>error<?php endif; ?>">
								<option value="left" <?php selected( 'left', $details['_car_drive'], true ); ?>><?php _e( 'Left', 'jprocars' ); ?></option>
								<option value="right" <?php selected( 'right', $details['_car_drive'], true ); ?>><?php _e( 'Right', 'jprocars' ); ?></option>
							</select>
						</div>
					</div><!-- / Car Drive -->
					<?php endif; ?>
					
					<?php if( $validation['car-doors_show'] ): ?>
					<!-- Car Doors -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Doors', 'jprocars' ); ?> <?php if( $validation['car-doors_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="car-doors" class="jpro-form-control <?php if( $validation['car-doors_req'] && $error && $error['_car_doors'] ): ?>error<?php endif; ?>">
								<?php foreach( range( 2, 5 ) as $doors ): ?>
								<option value="<?php echo $doors; ?>" <?php selected( $details['_car_doors'], $doors, true ); ?>><?php echo $doors; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div><!-- / Car Doors -->
					<?php endif; ?>
					
					<?php if( $validation['car-seats_show'] ): ?>
					<!-- Car Seats -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Seats', 'jprocars' ); ?> <?php if( $validation['car-seats_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<input name="car-seats" type="text" placeholder="eg: 5" value="<?php echo $details['_car_seats']; ?>" class="jpro-form-control <?php if( $validation['car-seats_req'] && $error && $error['_car_seats'] ): ?>error<?php endif; ?>">
							<span class="errseats"></span>
						</div>
					</div><!-- / Car Seats -->
					<?php endif; ?>
					
					<?php if( $validation['car-color_show'] ): ?>
					<!-- Car Color -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Car Color', 'jprocars' ); ?> <?php if( $validation['car-color_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<input name="car-color" type="text" placeholder="eg: yellow" value="<?php echo $details['_car_color']; ?>" class="jpro-form-control <?php if( $validation['car-color_req'] && $error && $error['_car_color'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / Car Color -->
					<?php endif; ?>
					
					<?php if( $validation['car-condition_show'] ): ?>
					<!-- Car Condition -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Condition', 'jprocars' ); ?> <?php if( $validation['car-condition_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="car-condition" class="jpro-form-control <?php if( $validation['car-condition_req'] && $error && $error['_car_condition'] ): ?>error<?php endif; ?>">
								<option value="new" <?php selected( 'new', $details['_car_condition'], true ); ?>><?php _e( 'New', 'jprocars' ); ?></option>
								<option value="used" <?php selected( 'used', $details['_car_condition'], true ); ?>><?php _e( 'Used', 'jprocars' ); ?></option>
							</select>
						</div>
					</div><!-- / Car Condition -->
					<?php endif; ?>
					
					<?php if( $validation['car-vin_show'] ): ?>
					<!-- VIN -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'VIN', 'jprocars' ); ?> <?php if( $validation['car-vin_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<input name="car-vin" type="text" placeholder="eg: JH4TB2H26CC000000" value="<?php echo $details['_car_vin']; ?>" class="jpro-form-control <?php if( $validation['car-vin_req'] && $error && $error['_car_vin'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / VIN -->
					<?php endif; ?>
					
					<?php if( $validation['car-price_show'] ): ?>
					<!-- Car Price -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Price', 'jprocars' ); ?> <?php if( $validation['car-price_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<input name="car-price" type="text" placeholder="eg: 10000" value="<?php echo $details['_car_price']; ?>" class="jpro-form-control <?php if( $validation['car-price_req'] && $error && $error['_car_price'] ): ?>error<?php endif; ?>">
							<span class="errprice"></span>
						</div>
					</div><!-- / Car Price -->
					<?php endif; ?>
					
					<?php if( $validation['car-price-type_show'] ): ?>
					<!-- Car Price Type -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Price Type', 'jprocars' ); ?> <?php if( $validation['car-price-type_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="car-price-type" class="jpro-form-control <?php if( $validation['car-price-type_req'] && $error && $error['_car_price_type'] ): ?>error<?php endif; ?>">
								<option value="fixed" <?php selected( 'fixed', $details['_car_price_type'], true ); ?>><?php _e( 'Fixed', 'jprocars' ); ?></option>
								<option value="negotiable" <?php selected( 'negotiable', $details['_car_price_type'], true ); ?>><?php _e( 'Negotiable', 'jprocars' ); ?></option>
							</select>
						</div>
					</div><!-- Car Price Type -->
					<?php endif; ?>
					
					<?php if( $validation['car-warranty_show'] ): ?>
					<!-- Car Warranty -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Warranty', 'jprocars' ); ?> <?php if( $validation['car-warranty_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="car-warranty" class="jpro-form-control <?php if( $validation['car-warranty_req'] && $error && $error['_car_warranty'] ): ?>error<?php endif; ?>">
								<option value="no" <?php selected( 'no', $details['_car_warranty'], true ); ?>><?php _e( 'No', 'jprocars' ); ?></option>
								<option value="yes" <?php selected( 'yes', $details['_car_warranty'], true ); ?>><?php _e( 'Yes', 'jprocars' ); ?></option>
							</select>
						</div>
					</div><!-- Car Warranty -->
					<?php endif; ?>
					
					<?php if( $validation['car-currency_show'] ): ?>
					<!-- Currency -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"><?php _e( 'Currency', 'jprocars' ); ?> <?php if( $validation['car-currency_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-9">
							<select name="currency" class="jpro-form-control <?php if( $validation['car-currency_req'] && $error && $error['_car_currency'] ): ?>error<?php endif; ?>">
								<?php if( $currencies ): foreach( $currencies as $currency ): ?>
								
									<option value="<?php echo $currency['iso']; ?>" <?php selected( $details['_car_currency'], $currency['iso'], true ); ?>><?php echo $currency['iso']; ?></option>
								
								<?php endforeach; else: ?>
								
									<option value="EUR" <?php selected( $details['_car_currency'], 'EUR', true ); ?>><?php echo 'EUR'; ?></option>
									<option value="USD" <?php selected( $details['_car_currency'], 'USD', true ); ?>><?php echo 'USD'; ?></option>
								
								<?php endif; ?>
							</select>
						</div>
					</div><!-- / Currency -->
					<?php endif; ?>
					
					<!-- Next -->
					<div class="jpro-form-group">
						<label class="col-lg-3 jpro-control-label"></label>
						<div class="col-lg-9">
							<button id="jpro-goto-step-2" class="btn btn-warning btn-large" type="button" value="Next Step"><?php _e( 'Next Step', 'jprocars' ); ?> <i class="fa fa-angle-double-right"></i></button>
						</div>
					</div><!-- / Next -->
				
				</div>
			</div>
			
		</div><!-- / Car Details -->
		
		<!-- Car Equipment -->
		<div id="jpro-step-2" class="jpro-panel">
			<div class="jpro-panel-heading">
				<span class="jpro-panel-title"><?php _e( 'Car Equipment', 'jprocars' ); ?></span>
			</div>
			<div class="jpro-panel-body">
				
				<?php $equipments = get_terms('car-equipment', 'orderby=count&hide_empty=0' ); ?>

				<?php foreach( $equipments as $equipment ): ?>
				<div class="jpro-form-group col-md-4">
					<?php if( isset( $_POST['car-equipment'] ) && is_array( $_POST['car-equipment'] ) && in_array( $equipment->term_id, $_POST['car-equipment'] ) ): ?>
						<input class="jpro-checkbox" name="car-equipment[]" type="checkbox" value="<?php echo $equipment->term_id; ?>" checked="checked">
						<label><span></span><?php echo ucfirst($equipment->name); ?></label>
					<?php else: ?>
						<input class="jpro-checkbox" name="car-equipment[]" type="checkbox" value="<?php echo $equipment->term_id; ?>">
						<label><span></span><?php echo ucfirst($equipment->name); ?></label>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
				
				<!-- Next -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"></label>
					<div class="col-lg-9">
						<button id="jpro-back-step-1" class="btn btn-warning btn-large" type="button" value="Back Step"><i class="fa fa-angle-double-left"></i> <?php _e( 'Back Step', 'jprocars' ); ?></button>
						<button id="jpro-goto-step-3" class="btn btn-warning btn-large" type="button" value="Next Step"><?php _e( 'Next Step', 'jprocars' ); ?> <i class="fa fa-angle-double-right"></i></button>
					</div>
				</div><!-- / Next -->

			</div>
		</div><!-- / Car Equipment -->
		
		<!-- Car Title & Description -->
		<div id="jpro-step-3" class="jpro-panel">
			<div class="jpro-panel-heading">
				<span class="jpro-panel-title"><?php _e( 'Car Title & Description', 'jprocars' ); ?></span>
			</div>
			<div class="jpro-panel-body">
				<div class="jpro-form-horizontal">
					
					<!-- Title -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'Title', 'jprocars' ); ?> <span class="required">*</span></label>
						<div class="col-lg-10">
							<input name="car-title" type="text" placeholder="eg: Audi A4 Used" value="<?php echo esc_attr( $details['_car_title'] ); ?>" class="jpro-form-control <?php if($error && $error['_car_title']) echo 'error'; ?>">
						</div>
					</div><!-- / Title -->
					
					<!-- Description -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'Description', 'jprocars' ); ?> <span class="required">*</span></label>
						<div class="col-lg-10">
							<?php if( $error && $error['_car_description'] ): ?>
							<style>
								#wp-car-description-wrap { border-color: red !important; }
							</style>
							<?php endif; ?>
							<?php wp_editor( $details['_car_description'], 'car-description', array( true, false ) ); ?>
						</div>
					</div><!-- / Description -->
					
					<!-- Next -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"></label>
						<div class="col-lg-10">
							<button id="jpro-back-step-2" class="btn btn-warning btn-large" type="button" value="Back Step"><i class="fa fa-angle-double-left"></i> <?php _e( 'Back Step', 'jprocars' ); ?></button>
							<button id="jpro-goto-step-4" class="btn btn-warning btn-large" type="button" value="Next Step"><?php _e( 'Next Step', 'jprocars' ); ?> <i class="fa fa-angle-double-right"></i></button>
						</div>
					</div><!-- / Next -->
					
				</div>
			</div>
		</div><!-- Car Title & Description -->
		
		<!-- Car Images -->
		<div id="jpro-step-4" class="jpro-panel">
			<div class="jpro-panel-heading">
				<span class="jpro-panel-title"><?php _e( 'Car Images', 'jprocars' ); ?></span>
			</div>
			<div class="jpro-panel-body">
				
				<!-- Upload Images -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Upload images', 'jprocars' ); ?> <span class="required">*</span></label>
					<div class="col-lg-9">
						<span><?php _e( 'Recommended image sizes: 800x600', 'jprocars' ); ?></span>
						<?php $id = "car-images"; $svalue = ""; $multiple = true; $width = 800; $height = 600; ?>
						<div class="images-wrapper <?php if($error && $error['_car_images']) echo 'error'; ?> clearfix">
						<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; ?>" />  
						<div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">  
						<input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="button" />
						<span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
						
						<?php if ($width && $height): ?>
								<span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
								<span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
						<?php endif; ?>
						
						<div class="filelist"></div>
						
						</div>  
						<div class="plupload-thumbs <?php if ($multiple): ?>plupload-thumbs-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-thumbs">  
						</div>  
						<div class="clear"></div>
					</div>
					</div>
				</div><!-- / Upload Images -->
				
				<!-- Next -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"></label>
					<div class="col-lg-9">
						<button id="jpro-back-step-3" class="btn btn-warning btn-large" type="button" value="Back Step"><i class="fa fa-angle-double-left"></i> <?php _e( 'Back Step', 'jprocars' ); ?></button>
						<button id="jpro-goto-step-5" class="btn btn-warning btn-large" type="button" value="Next Step"><?php _e( 'Next Step', 'jprocars' ); ?> <i class="fa fa-angle-double-right"></i></button>
					</div>
				</div><!-- / Next -->
				
			</div>
		</div><!-- / Car Images -->
		
		<!-- Seller Details -->
		<div id="jpro-step-5" class="jpro-panel">
			<div class="jpro-panel-heading">
				<span class="jpro-panel-title"><?php _e( 'Seller Details', 'jprocars' ); ?></span>
			</div>
			<div class="jpro-panel-body">
				<div class="jpro-form-horizontal">
					<?php $user_info = get_userdata( get_current_user_id() ); ?>
					
					<?php if( $validation['first-name_show'] ): ?>
					<!-- First Name -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'First Name', 'jprocars' ); ?> <?php if( $validation['first-name_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-10">
							<input name="seller-first-name" type="text" placeholder="eg: John" value="<?php if( $user_info->first_name ){echo $user_info->first_name;} ?>" class="jpro-form-control <?php if( $validation['first-name_req'] && $error && $error['_seller_first_name'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / First Name -->
					<?php endif; ?>
					
					<?php if( $validation['last-name_show'] ): ?>
					<!-- Last Name -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'Last Name', 'jprocars' ); ?> <?php if( $validation['last-name_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-10">
							<input name="seller-last-name" type="text" placeholder="eg: Doe" value="<?php if( $user_info->last_name ){echo $user_info->last_name;} ?>" class="jpro-form-control <?php if( $validation['last-name_req'] && $error && $error['_seller_last_name'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / Last Name -->
					<?php endif; ?>
					
					<?php if( $validation['seller-company_show'] ): ?>
					<!-- Company -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'Company', 'jprocars' ); ?> <?php if( $validation['seller-company_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-10">
							<input name="seller-company" type="text" placeholder="eg: General Motors" value="<?php echo $details['_seller_company']; ?>" class="jpro-form-control <?php if( $validation['seller-company_req'] && $error && $error['_seller_company'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / Company -->
					<?php endif; ?>
					
					<!-- Email -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'Email', 'jprocars' ); ?> <span class="required">*</span></label>
						<div class="col-lg-10">
							<input name="seller-email" type="text" placeholder="eg: example@gmail.com" value="<?php echo $details['_seller_email']; ?>" class="jpro-form-control <?php if($error && $error['_seller_email']) echo 'error'; ?>">
						</div>
					</div><!-- / Email -->
					
					<?php if( $validation['seller-phone_show'] ): ?>
					<!-- Phone -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'Phone', 'jprocars' ); ?> <?php if( $validation['seller-phone_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-10">
							<input name="seller-phone" type="text" placeholder="eg: +38160656545" value="<?php echo $details['_seller_phone']; ?>" class="jpro-form-control <?php if( $validation['seller-phone_req'] && $error && $error['_seller_phone'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / Phone -->
					<?php endif; ?>
					
					<?php if( $validation['seller-country_show'] ): ?>
					<!-- Country -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'Country', 'jprocars' ); ?> <?php if( $validation['seller-country_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-10">
							<select name="seller-country" class="jpro-form-control <?php if( $validation['seller-country_req'] && $error && $error['_seller_country'] ): ?>error<?php endif; ?>">
								<?php if( $details ): ?>
									<?php $JP_Country->option_output( $details['_seller_country'] ); ?>
								<?php else: ?>
									<?php $JP_Country->option_output(); ?>
								<?php endif; ?>
							</select>
						</div>
					</div><!-- / Country -->
					<?php endif; ?>
					
					<?php if( $validation['seller-state_show'] ): ?>
					<!-- State -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'State', 'jprocars' ); ?> <?php if( $validation['seller-state_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-10">
							<input name="seller-state" type="text" placeholder="eg: Texas" value="<?php echo $details['_seller_state']; ?>" class="jpro-form-control <?php if( $validation['seller-state_req'] && $error && $error['_seller_state'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / State -->
					<?php endif; ?>
					
					<?php if( $validation['seller-town_show'] ): ?>
					<!-- Town -->
					<div class="jpro-form-group">
						<label class="col-lg-2 jpro-control-label"><?php _e( 'Town', 'jprocars' ); ?> <?php if( $validation['seller-town_req'] ): ?><span class="required">*</span><?php endif; ?></label>
						<div class="col-lg-10">
							<input name="seller-town" type="text" value="<?php echo $details['_seller_town']; ?>" placeholder="eg: Rome" class="jpro-form-control <?php if( $validation['seller-town_req'] && $error && $error['_seller_town'] ): ?>error<?php endif; ?>">
						</div>
					</div><!-- / Town -->
					<?php endif; ?>
					
				<!-- Next -->
				<div class="jpro-form-group">
					<label class="col-lg-2 jpro-control-label"></label>
					<div class="col-lg-10">
						<button id="jpro-back-step-4" class="btn btn-warning btn-large" type="button" value="Back Step"><i class="fa fa-angle-double-left"></i> <?php _e( 'Back Step', 'jprocars' ); ?></button>
						<button name="submit-car" class="btn btn-success btn-large" type="submit" value="Finish"><?php _e( 'Finish', 'jprocars' ); ?> <i class="fa fa-check"></i></button>
					</div>
				</div><!-- / Next -->
				
				</div>
			</div>
		</div><!-- / Seller Details -->
		
		<!-- jQuery Steps -->
		<script>
		jQuery( document ).ready(function(){
			jQuery("#jpro-step-2, #jpro-step-3, #jpro-step-4, #jpro-step-5").hide();
			
			jQuery("#jpro-goto-step-2").click(function() {
				var $step_1 = jQuery("#jpro-goto-step-2").val();
				
				if($step_1 == 'Next Step'){
					jQuery("#jpro-step-2").show("slow");
					jQuery("#jpro-step-1").slideUp();
				}
			});
			
			jQuery("#jpro-goto-step-3").click(function() {
				var $step_2 = jQuery("#jpro-goto-step-3").val();
				
				if($step_2  == 'Next Step'){
					jQuery("#jpro-step-3").show("slow");
					jQuery("#jpro-step-2").slideUp();
				}
			});
			
			jQuery("#jpro-goto-step-4").click(function() {
				var $step_3 = jQuery("#jpro-goto-step-4").val();
				
				if($step_3  == 'Next Step'){
					jQuery("#jpro-step-4").show("slow");
					jQuery("#jpro-step-3").slideUp();
				}
			});
			
			jQuery("#jpro-goto-step-5").click(function() {
				var $step_3 = jQuery("#jpro-goto-step-5").val();
				
				if($step_3  == 'Next Step'){
					jQuery("#jpro-step-5").show("slow");
					jQuery("#jpro-step-4").slideUp();
				}
			});
			
			jQuery("#jpro-back-step-1").click(function() {
				var $step_2 = jQuery("#jpro-back-step-1").val();
				
				if($step_2  == 'Back Step'){
					jQuery("#jpro-step-2").slideUp();
					jQuery("#jpro-step-1").show("slow");
				}
			});
			
			jQuery("#jpro-back-step-2").click(function() {
				var $step_3 = jQuery("#jpro-back-step-3").val();
				
				if($step_3  == 'Back Step'){
					jQuery("#jpro-step-3").slideUp();
					jQuery("#jpro-step-2").show("slow");
				}
			});
			
			jQuery("#jpro-back-step-3").click(function() {
				var $step_4 = jQuery("#jpro-back-step-4").val();
				
				if($step_4  == 'Back Step'){
					jQuery("#jpro-step-4").slideUp();
					jQuery("#jpro-step-3").show("slow");
				}
			});
			
			jQuery("#jpro-back-step-4").click(function() {
				var $step_5 = jQuery("#jpro-back-step-4").val();
				
				if($step_5  == 'Back Step'){
					jQuery("#jpro-step-5").slideUp();
					jQuery("#jpro-step-4").show("slow");
				}
			});
		});
		</script><!-- / jQuery Steps -->
	</form>
	</div>
	
	<?php endif; // End show add classified options only if insert post is not completed  ?>
	
	<?php endif; ?>