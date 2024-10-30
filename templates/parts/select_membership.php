<?php 
	$Settings			= new JP_Settings();
	$settings			= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true ); // General Settings
	$options			= $Settings->getSettings( 'JP_MEMBERSHIP', '_jp_cars_membership_', true ); // Membership Packages
	$gateways			= $Settings->getSettings( 'JP_GATEWAYS', '_jp_cars_gateway_', true ); // Payment Gateways
	$currencies			= unserialize( get_option( '_jp_cars_currencies' ) ); // Get Currencies
	
	// If user wants to change current membership package
	$current_membership = unserialize( get_user_meta( get_current_user_id(), '_jp_cars_membership', true ) ); // Get current user membership package
	
	/**
	 * If package selected let's proceed with payment
	 *
	 * @since 0.1
	 */
	if( isset( $_POST['checkout'] ) && ! empty( $_POST['id'] ) ):
	
	$details = array(
		'id'						=> esc_attr($_POST['id']),
		'title'						=> esc_attr($_POST['title']),
		'gateway'					=> esc_attr($_POST['gateway']),
		'type'						=> esc_attr($_POST['type']),
		'price'						=> esc_attr($_POST['price']),
		'currency'					=> esc_attr($_POST['currency']),
		'purchase_date'				=> esc_attr(date('Y-m-d')),
		'expire_date'				=> jpro_expiration_calculator('membership', $_POST['expiration']),
		'classifieds_limit'			=> esc_attr($_POST['classifieds_limit']),
		'classifieds_expire'		=> esc_attr($_POST['classifieds_expire']),
		'classifieds_image_limit'	=> esc_attr($_POST['classifieds_image_limit'])
	);
?>

<div class="col-md-12">

	<?php 
	#####################################################################
	# IF PAID PACKAGE SELECTED, LOAD SELECTED GATEWAY
	#####################################################################
	if( isset( $details ) && $details['type'] == 'paid' ):
		
		if( $details['gateway'] ) {
			require_once JPRO_CAR_DIR . 'includes/gateways/' . strtolower( $details['gateway'] ) . '.php';
		} else {
			_e( 'No gateways available!', 'jprocars' );
		}
	
	#####################################################################
	# ELSE IF FREE PACKAGE SELECTED, APPLY IT RIGHT AWAY
	#####################################################################
	elseif( isset( $details ) && $details['type'] == 'free' ): ?>
	
		<div class="alert alert-success alert-dismissable">
			
			<strong><?php _e( 'Thank you!', 'jprocars' ); ?></strong>
			<?php echo __( 'You have purchased membership package. Check out', 'jprocars' ).' <a href="' . get_permalink( $settings['cc_page_id'] ) . '?jp=my-membership">'.__( 'My Membership', 'jprocars' ).'</a>.'; ?>
			<?php jpro_apply_membership_package( $details ); // apply free membership package ?>
			
		</div>
	
	<?php endif; ?>

</div>

<?php else: ### Show Membership Packages ### ?>

<div id="jpro-select-membership" class="col-md-12">

	<?php if( isset( $message ) ): ?>
	<div class="alert alert-warning alert-dismissable">
		<strong><?php _e( 'Warning!', 'jprocustom' ); ?></strong>
		<?php if( $message ) echo $message; ?>
	</div>
	<?php endif; ?>

<?php if( $options ): foreach( $options as $option ): ?>

	<div class="jpro-panel">
		<div class="jpro-panel-heading">
			<span class="jpro-panel-title"><?php echo $option['membership_title']; ?> 
			<?php 
				// If paid membership show package price & currency symbol
				if( $option['membership_type'] !== 'free' ) { 
					
					echo $option['membership_price'];
				
					if( $currencies ): 
						foreach( $currencies as $currency ):
							if( $currency['iso'] == $option['membership_currency'] ) {
								echo $currency['symbol'];
							}
						endforeach; 
					endif;
				}
				// If user current package match to package
				if( $option['id'] == $current_membership['id'] ) {
					printf( '%s <span style="font-weight:normal;color:green;">%s</span>', ' - ', __( 'your current package' ) );
				}
			?>
			</span>
		</div>
		<div class="jpro-panel-body">
		
			<form method="post" class="jpro-form-horizontal" role="form">
						
				<input name="add-new-classified" type="hidden">
				<input name="id" type="hidden" value="<?php echo $option['id']; ?>">
				<input name="title" type="hidden" value="<?php echo $option['membership_title']; ?>">
				<input name="type" type="hidden" value="<?php echo $option['membership_type']; ?>">
				<input name="price" type="hidden" value="<?php echo $option['membership_price']; ?>">
				<input name="currency" type="hidden" value="<?php echo $option['membership_currency']; ?>">
				<input name="expiration" type="hidden" value="<?php echo $option['membership_expiration']; ?>">
				<input name="classifieds_limit" type="hidden" value="<?php echo $option['membership_classifieds_limit']; ?>">
				<input name="classifieds_expire" type="hidden" value="<?php echo $option['membership_classifieds_expiration']; ?>">
				<input name="classifieds_image_limit" type="hidden" value="<?php echo $option['membership_image_upload_limit']; ?>">
				
				<?php if( $option['membership_type'] == 'free' ): ?>
				<input name="gateway" type="hidden" value="<?php _e( 'free', 'jprocars' ); ?>">
				<?php endif; ?>
				
				<div class="jpro-form-group">
					<label class="col-lg-6 jpro-control-label"><?php _e( 'Classifieds posting limit', 'jprocars' ); ?></label>
					<div class="col-lg-6">
						<span class="jpro-form-control"><?php echo $option['membership_classifieds_limit'].' posts'; ?></span>
					</div>
				</div>
				
				<div class="jpro-form-group">
					<label class="col-lg-6 jpro-control-label"><?php _e( 'Classifieds expiration', 'jprocars' ); ?></label>
					<div class="col-lg-6">
						<span class="jpro-form-control">
							<?php
							if( $option['membership_classifieds_expiration'] !== 'never' ) {
								echo __( 'for ', 'jprocars' ).$option['membership_classifieds_expiration'].' days'; 
							}else{
								echo $option['membership_classifieds_expiration'];
							} ?>
						</span>
					</div>
				</div>
				
				<div class="jpro-form-group">
					<label class="col-lg-6 jpro-control-label"><?php _e( 'Membership package expiration', 'jprocars' ); ?></label>
					<div class="col-lg-6">
						<span class="jpro-form-control">
							<?php 
							if( $option['membership_expiration'] !== 'never' ) {
								echo __( 'after', 'jprocars' ).' '.$option['membership_expiration'].' months'; 
							}else{
								echo $option['membership_expiration'];
							} ?>
							
						</span>
					</div>
				</div>
				
				<div class="jpro-form-group">
					<label class="col-lg-6 jpro-control-label"><?php _e( 'Classifieds image upload limit', 'jprocars' ); ?></label>
					<div class="col-lg-6">
						<span class="jpro-form-control"><?php echo $option['membership_image_upload_limit'].__( ' images', 'jprocars' ); ?></span>
					</div>
				</div>
				<?php if( $option['membership_price'] !== 'free' ): // if paid package, show gateways ?>
				<div class="jpro-form-group">
					<label class="col-lg-6 jpro-control-label"><?php _e( 'Pay with', 'jprocars' ); ?></label>
					<div class="col-lg-6">
						<select name="gateway" class="jpro-form-control">
						<?php if( !empty( $gateways ) ): ?>
							<?php foreach( $gateways as $gateway ): ?>
							<option value="<?php echo $gateway['gateway']; ?>"><?php echo $gateway['title']; ?></option>
							<?php endforeach; ?>
						<?php else: ?>
							<option value="0"><?php _e( 'No gateways available', 'jprocars' ); ?></option>
						<?php endif; ?>
						</select>
					</div>
				</div>
				<?php endif; ?>	
						
				<div class="jpro-form-group">
					<label class="col-lg-6 jpro-control-label"></label>
					<div class="col-lg-6">
						<input type="submit" name="checkout" class="button" value="<?php _e( 'Checkout', 'jprocars' ); ?>" 
						<?php if( 
								! empty( $err_code ) && $err_code == 'classifieds_limit_exceeded' && $membership['type'] == 'free' && $option['membership_type'] == 'free' || 
								empty( $gateways ) && !empty( $options['membership_type'] ) && $options['membership_type'] == 'paid' || 
								!is_user_logged_in() ||
								$current_membership['type'] ==  $option['membership_type']
								) echo 'disabled="disabled"'; // Step 5 ?>>
					</div>
				</div>
		
			</form>
		</div>
	</div>
<?php endforeach; endif; ?>
</div>

<?php endif; ?>