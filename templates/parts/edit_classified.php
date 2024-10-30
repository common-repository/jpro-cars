<?php 
	if( ! defined( 'ABSPATH' ) ) 
		exit; // Exit if accessed directly

	global $JP_Cars, $JP_Country;
	
	$Settings			= new JP_Settings();
	$general_settings	= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
	$validate			= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_validation', true );
	$currencies			= unserialize( get_option( '_jp_cars_currencies' ) );
	$validation 		= jpro::validation( $validate );

	$post_id = esc_attr( $_GET['id'] );  // Get post ID
	$post	 = get_post( $post_id ); // Load post ID
	$terms	 = wp_get_post_terms( $post_id, 'car-equipment' );
	
	$term_id = isset( $term_id ) ? $term_id : '';
	
	foreach( $terms as $term ) {
		$term_id[] = $term->term_id;
	}
	
	$error 			= isset( $error ) ? $error : '';
	$edit_finished 	= isset( $edit_finished ) ? $edit_finished : '';
	
	if( is_user_logged_in() && $post->post_author == get_current_user_id() ) { // Only post authors
		##########################################################
		# Sanitize all submited data & add it into array $details
		##########################################################
		$details = jpro::sanitize_post();
		
		$retrived_nonce = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '';
		
		if( isset( $_POST['jp-edit-car'] ) &&  wp_verify_nonce( $retrived_nonce, 'edit_classified' ) ):
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
				
				// Get car equipment array id's and format it for tax_input
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
					'ID'			=> $post_id,
					'post_title'	=> wp_strip_all_tags($details['_car_title']),
					'post_content'	=> $details['_car_description'],
					'post_status'	=> 'publish',
					'post_author'	=> get_current_user_id(),
					'post_type'		=> 'car-classifieds',
					'tax_input'		=> array( 'car-model' => $details['_car_make'].','.$details['_car_model'], 'car-equipment' => $equipment )
				);
				
				// Update post
				wp_update_post( $my_post );
				
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
					
					echo '<div class="alert alert-success">';
						printf( '<strong>%s</strong> %s %s <a href="%s">%s</a>', __( 'Thank you! ', 'jprocars' ), __( 'Car edited successfully.', 'jprocars' ), __( 'Check', 'jprocars' ), esc_url( add_query_arg( 'jp', 'my-classifieds', get_permalink( $general_settings['cc_page_id'] ) ) ), __( 'My Classifieds', 'jprocars' ) );
					echo '</div>';
					
					$edit_finished = true;
				}
			}
		######################################################
		# If security check fails output error message
		######################################################
		elseif( isset( $_POST['submit-car'] ) && !wp_verify_nonce( $retrived_nonce, 'edit_classified' ) && $class == 'error' ):
			echo '<div class="alert alert-danger">';
				printf( '<strong>%s:</strong> %s', __( 'Error', 'jprocars' ), __( 'Failed security check!', 'jprocars' ) );
			echo '</div>';
		endif;
	?>	

<?php if( ! $edit_finished ) { ?>
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
	<?php endforeach; endif; ?>

	//If parent option is changed
	$("#jpro_cars_make").change(function() {
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
<div id="edit-classified-wrapper" class="row">
<form id="edit_classified" method="post">

	<?php echo wp_nonce_field('edit_classified'); ?>
	
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
						<input name="car-version" type="text" placeholder="eg: 1.6 hdi" value="<?php echo jp_post_meta( '_car_version' ); ?>" class="jpro-form-control <?php if( $validation['car-version_req'] && $error && $error['_car_version'] ): ?>error<?php endif; ?>">
					</div>
				</div><!-- / Car Version -->
				<?php endif; ?>
				
				<?php if( $validation['car-year_show'] ): ?>
				<!-- Car Year -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Year', 'jprocars' ); ?> <?php if( $validation['car-year_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="car-year" class="jpro-form-control <?php if($validation['car-year_req'] && $error && $error['_car_year']) echo 'error'; ?>">
							
							<?php JP_Cars::dropdown_years( jp_post_meta('_car_year') ); ?>
						
						</select>
					</div>
				</div><!-- / Car Year -->
				<?php endif; ?>
				
				<?php if( $validation['car-mileage_show'] ): ?>
				<!-- Car Mileage -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Mileage', 'jprocars' ); ?> <?php if( $validation['car-mileage_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="car-mileage" type="text" placeholder="eg: 100000" value="<?php echo jp_post_meta( '_car_mileage' ); ?>" class="jpro-form-control <?php if($validation['car-mileage_req'] && $error && $error['_car_mileage']) echo 'error'; ?>">
						<span class="errmileage"></span>
					</div>
				</div><!-- / Car Mileage -->
				<?php endif; ?>
				
				<?php if( $validation['car-fuel_show'] ): ?>
				<!-- Car Fuel -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Fuel', 'jprocars' ); ?> <?php if( $validation['car-fuel_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="car-fuel" class="jpro-form-control <?php if($validation['car-fuel_req'] && $error && $error['_car_fuel']) echo 'error'; ?>">
							<option value="diesel" <?php selected( 'diesel', jp_post_meta( '_car_fuel' ), true ); ?>><?php _e( 'Diesel', 'jprocars' ); ?></option>
							<option value="electric" <?php selected( 'electric', jp_post_meta( '_car_fuel' ), true ); ?>><?php _e( 'Electric', 'jprocars' ); ?></option>
							<option value="gasoline" <?php selected( 'gasoline', jp_post_meta('_car_fuel'), true ); ?>><?php _e( 'Gasoline', 'jprocars' ); ?></option>
							<option value="hybrid" <?php selected( 'hybrid', jp_post_meta( '_car_fuel' ), true ); ?>><?php _e( 'Hybrid', 'jprocars' ); ?></option>
						</select>
					</div>
				</div><!-- / Car Fuel -->
				<?php endif; ?>
				
				<?php if( $validation['car-engine_show'] ): ?>
				<!-- Car Engine -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Engine cm3', 'jprocars' ); ?> <?php if( $validation['car-engine_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="car-engine" type="text" placeholder="eg: 1900" value="<?php echo jp_post_meta( '_car_engine' ); ?>" class="jpro-form-control <?php if($validation['car-engine_req'] && $error && $error['_car_engine']) echo 'error'; ?>">
						<span class="errengine"></span>
					</div>
				</div><!-- / Car Engine -->
				<?php endif; ?>
				
				<?php if( $validation['car-horsepower_show'] ): ?>
				<!-- Car Horsepower -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Horsepower hp', 'jprocars' ); ?> <?php if( $validation['car-horsepower_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="car-horsepower" type="text" placeholder="eg: 200" value="<?php echo jp_post_meta( '_car_horsepower' ); ?>" class="jpro-form-control <?php if($validation['car-horsepower_req'] && $error && $error['_car_horsepower']) echo 'error'; ?>">
						<span class="errhorsepower"></span>
					</div>
				</div><!-- / Car Horsepower -->
				<?php endif; ?>
				
				<?php if( $validation['car-transmission_show'] ): ?>
				<!-- Car Transmission -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Transmission', 'jprocars' ); ?> <?php if( $validation['car-transmission_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="car-transmission" class="jpro-form-control <?php if($validation['car-transmission_req'] && $error && $error['_car_transmission']) echo 'error'; ?>">
							<option value="automatic" <?php selected( 'automatic', jp_post_meta( '_car_transmission' ), true ); ?>><?php _e( 'Automatic', 'jprocars' ); ?></option>
							<option value="manual" <?php selected( 'manual', jp_post_meta( '_car_transmission' ), true ); ?>><?php _e( 'Manual', 'jprocars' ); ?></option>
							<option value="semi-automatic" <?php selected( 'semi-automatic', jp_post_meta( '_car_transmission' ), true ); ?>><?php _e( 'Semi-Automatic', 'jprocars' ); ?></option>
						</select>
					</div>
				</div><!-- / Car Transmission -->
				<?php endif; ?>
				
				<?php if( $validation['car-drive_show'] ): ?>
				<!-- Car Drive -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Drive', 'jprocars' ); ?> <?php if( $validation['car-drive_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="car-drive" class="jpro-form-control <?php if($validation['car-drive_req'] && $error && $error['_car_drive']) echo 'error'; ?>">
							<option value="left" <?php selected( 'left', jp_post_meta( '_car_drive' ), true ); ?>><?php _e( 'Left', 'jprocars' ); ?></option>
							<option value="right" <?php selected( 'right', jp_post_meta( '_car_drive' ), true ); ?>><?php _e( 'Right', 'jprocars' ); ?></option>
						</select>
					</div>
				</div><!-- / Car Drive -->
				<?php endif; ?>
				
				<?php if( $validation['car-doors_show'] ): ?>
				<!-- Car Doors -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Doors', 'jprocars' ); ?> <?php if( $validation['car-doors_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="car-doors" class="jpro-form-control <?php if($validation['car-doors_req'] && $error && $error['_car_doors']) echo 'error'; ?>">
							<?php foreach( range( 2, 5 ) as $doors ): ?>
							<option value="<?php echo $doors; ?>" <?php selected( jp_post_meta( '_car_doors' ), $doors, true ); ?>><?php echo $doors; ?></option>
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
						<input name="car-seats" type="text" placeholder="eg: 5" value="<?php echo jp_post_meta( '_car_seats' ); ?>" class="jpro-form-control <?php if($validation['car-seats_req'] && $error && $error['_car_seats']) echo 'error'; ?>">
						<span class="errseats"></span>
					</div>
				</div><!-- / Car Seats -->
				<?php endif; ?>
				
				<?php if( $validation['car-color_show'] ): ?>
				<!-- Car Color -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Car Color', 'jprocars' ); ?> <?php if( $validation['car-color_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="car-color" type="text" placeholder="eg: yellow" value="<?php echo jp_post_meta( '_car_color' ); ?>" class="jpro-form-control <?php if($validation['car-color_req'] && $error && $error['_car_color']) echo 'error'; ?>">
					</div>
				</div><!-- / Car Color -->
				<?php endif; ?>
				
				<?php if( $validation['car-condition_show'] ): ?>
				<!-- Car Condition -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Condition', 'jprocars' ); ?> <?php if( $validation['car-condition_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="car-condition" class="jpro-form-control <?php if($validation['car-condition_req'] && $error && $error['_car_condition']) echo 'error'; ?>">
							<option value="new" <?php selected( 'new', jp_post_meta( '_car_condition' ), true ); ?>><?php _e( 'New', 'jprocars' ); ?></option>
							<option value="used" <?php selected( 'used', jp_post_meta( '_car_condition' ), true ); ?>><?php _e( 'Used', 'jprocars' ); ?></option>
						</select>
					</div>
				</div><!-- / Car Condition -->
				<?php endif; ?>
				
				<?php if( $validation['car-vin_show'] ): ?>
				<!-- VIN -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'VIN', 'jprocars' ); ?> <?php if( $validation['car-vin_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="car-vin" type="text" placeholder="eg: JH4TB2H26CC000000" value="<?php echo jp_post_meta( '_car_vin' ); ?>" class="jpro-form-control <?php if($validation['car-vin_req'] && $error && $error['_car_vin']) echo 'error'; ?>">
					</div>
				</div><!-- / VIN -->
				<?php endif; ?>
				
				<?php if( $validation['car-price_show'] ): ?>
				<!-- Car Price -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Price', 'jprocars' ); ?> <?php if( $validation['car-price_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="car-price" type="text" placeholder="eg: 10000" value="<?php echo jp_post_meta( '_car_price' ); ?>" class="jpro-form-control <?php if($validation['car-price_req'] && $error && $error['_car_price']) echo 'error'; ?>">
						<span class="errprice"></span>
					</div>
				</div><!-- / Car Price -->
				<?php endif; ?>
				
				<?php if( $validation['car-price-type_show'] ): ?>
				<!-- Car Price Type -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Price Type', 'jprocars' ); ?> <?php if( $validation['car-price-type_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="car-price-type" class="jpro-form-control <?php if($validation['car-price-type_req'] && $error && $error['_car_price_type']) echo 'error'; ?>">
							<option value="fixed" <?php selected( 'fixed', jp_post_meta('_car_price_type'), true ); ?>><?php _e( 'Fixed', 'jprocars' ); ?></option>
							<option value="negotiable" <?php selected( 'negotiable', jp_post_meta('_car_price_type'), true ); ?>><?php _e( 'Negotiable', 'jprocars' ); ?></option>
						</select>
					</div>
				</div><!-- Car Price Type -->
				<?php endif; ?>
				
				<?php if( $validation['car-currency_show'] ): ?>
				<!-- Currency -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Currency', 'jprocars' ); ?> <?php if( $validation['car-currency_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="currency" class="jpro-form-control <?php if($validation['car-currency_req'] && $error && $error['_car_currency']) echo 'error'; ?>">
						<?php if( $currencies ): foreach( $currencies as $currency ): ?>
								
							<option value="<?php echo $currency['iso']; ?>" <?php selected( jp_post_meta('_car_currency'), $currency['iso'], true ); ?>><?php echo $currency['iso']; ?></option>
								
						<?php endforeach; else: ?>
								
							<option value="EUR" <?php selected( jp_post_meta('_car_currency'), 'EUR', true ); ?>><?php echo 'EUR'; ?></option>
							<option value="USD" <?php selected( jp_post_meta('_car_currency'), 'USD', true ); ?>><?php echo 'USD'; ?></option>
								
						<?php endif; ?>
						</select>
					</div>
				</div><!-- / Currency -->
				<?php endif; ?>
				
				<?php if( $validation['car-warranty_show'] ): ?>
				<!-- Car Warranty -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Warranty', 'jprocars' ); ?> <?php if( $validation['car-warranty_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="car-warranty" class="jpro-form-control <?php if($validation['car-warranty_req'] && $error && $error['_car_warranty']) echo 'error'; ?>">
							<option value="no" <?php selected( 'no', jp_post_meta('_car_warranty'), true ); ?>><?php _e( 'No', 'jprocars' ); ?></option>
							<option value="yes" <?php selected( 'yes', jp_post_meta('_car_warranty'), true ); ?>><?php _e( 'Yes', 'jprocars' ); ?></option>
						</select>
					</div>
				</div><!-- Car Warranty -->
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
				<?php if( is_array( $term_id ) && in_array( $equipment->term_id, $term_id ) ): ?>
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
						<input name="car-title" type="text" placeholder="eg: Audi A4 Used" value="<?php echo $post->post_title; ?>" class="jpro-form-control <?php if($error && $error['_car_title']) echo 'error'; ?>">
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
						<?php wp_editor( $post->post_content, 'car-description', array( true, false ) ); ?>
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
					<div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">  
					<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; echo jp_post_meta('_car_images'); ?>" />  
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
				
				<?php if( $validation['first-name_show'] ): ?>
				<!-- First Name -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'First Name', 'jprocars' ); ?> <?php if( $validation['first-name_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="seller-first-name" type="text" placeholder="eg: John" value="<?php echo jp_post_meta( '_seller_first_name' ) ?>" class="jpro-form-control <?php if($validation['first-name_req'] && $error && $error['_seller_first_name']) echo 'error'; ?>">
					</div>
				</div><!-- / First Name -->
				<?php endif; ?>
				
				<?php if( $validation['last-name_show'] ): ?>
				<!-- Last Name -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Last Name', 'jprocars' ); ?> <?php if( $validation['last-name_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="seller-last-name" type="text" placeholder="eg: Doe" value="<?php echo jp_post_meta( '_seller_last_name' ) ?>" class="jpro-form-control <?php if($validation['last-name_req'] && $error && $error['_seller_last_name']) echo 'error'; ?>">
					</div>
				</div><!-- / Last Name -->
				<?php endif; ?>
				
				<?php if( $validation['seller-company_show'] ): ?>
				<!-- Company -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Company', 'jprocars' ); ?> <?php if( $validation['seller-company_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="seller-company" type="text" placeholder="eg: General Motors" value="<?php echo jp_post_meta( '_seller_company' ) ?>" class="jpro-form-control <?php if($validation['seller-company_req'] && $error && $error['_seller_company']) echo 'error'; ?>">
					</div>
				</div><!-- / Company -->
				<?php endif; ?>
				
				<!-- Email -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Email', 'jprocars' ); ?> <span class="required">*</span></label>
					<div class="col-lg-9">
						<input name="seller-email" type="text" placeholder="eg: example@gmail.com" value="<?php echo jp_post_meta( '_seller_email' ) ?>" class="jpro-form-control <?php if($error && $error['_seller_email']) echo 'error'; ?>">
					</div>
				</div><!-- / Email -->
				
				<?php if( $validation['seller-phone_show'] ): ?>
				<!-- Phone -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Phone', 'jprocars' ); ?> <?php if( $validation['seller-phone_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="seller-phone" type="text" placeholder="eg: +38160656545" value="<?php echo jp_post_meta( '_seller_phone' ) ?>" class="jpro-form-control <?php if($validation['seller-phone_req'] && $error && $error['_seller_phone']) echo 'error'; ?>">
					</div>
				</div><!-- / Phone -->
				<?php endif; ?>
				
				<?php if( $validation['seller-country_show'] ): ?>
				<!-- Country -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Country', 'jprocars' ); ?> <?php if( $validation['seller-country_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<select name="seller-country" class="jpro-form-control <?php if( $validation['seller-country_req'] && $error && $error['_seller_country']) echo 'error'; ?>">
							<?php $JP_Country->option_output( jp_post_meta( '_seller_country' ) ); ?>
						</select>
					</div>
				</div><!-- / Country -->
				<?php endif; ?>
				
				<?php if( $validation['seller-state_show'] ): ?>
				<!-- State -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'State', 'jprocars' ); ?> <?php if( $validation['seller-state_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="seller-state" type="text" placeholder="eg: Texas" value="<?php echo jp_post_meta('_seller_state'); ?>" class="jpro-form-control <?php if($validation['seller-state_req'] && $error && $error['_seller_state']) echo 'error'; ?>">
					</div>
				</div><!-- / State -->
				<?php endif; ?>
				
				<?php if( $validation['seller-town_show'] ): ?>
				<!-- Town -->
				<div class="jpro-form-group">
					<label class="col-lg-3 jpro-control-label"><?php _e( 'Town', 'jprocars' ); ?> <?php if( $validation['seller-town_req'] ): ?><span class="required">*</span><?php endif; ?></label>
					<div class="col-lg-9">
						<input name="seller-town" type="text" value="<?php echo jp_post_meta( '_seller_town' ) ?>" placeholder="eg: Rome" class="jpro-form-control <?php if($validation['seller-town_req'] && $error && $error['_seller_town']) echo 'error'; ?>">
					</div>
				</div><!-- / Town -->
				<?php endif; ?>
				
			<!-- Next -->
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-10">
					<button id="jpro-back-step-4" class="btn btn-warning btn-large" type="button" value="Back Step"><i class="fa fa-angle-double-left"></i> <?php _e( 'Back Step', 'jprocars' ); ?></button>
					<button name="jp-edit-car" class="btn btn-success btn-large" type="submit" value="Finish"><?php _e( 'Finish', 'jprocars' ); ?> <i class="fa fa-check"></i></button>
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
<?php }
}else{
	_e( 'Did you try to edit a classified which doesn\'t belong to you ?', 'jprocars' );
} ?>