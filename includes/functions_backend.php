<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Include Neccessary Backend Files
 * 
 * @since 0.1
 * @require_once
 */
require_once 'backend/meta_boxes.php';
require_once 'backend/user_profiles.php';
/**
 * Enqueue Scripts && Stylesheets to Backend
 *
 * @since 0.1
 */
add_action( 'admin_enqueue_scripts', 'jp_cars_enqueue_scripts' );
function jp_cars_enqueue_scripts() {
	
	$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
	$tab  = isset( $_REQUEST['tab'] )  ? sanitize_text_field( $_REQUEST['tab'] ) : '';
	
	// jQuery Bootstrap 3
	wp_register_script( 'bootstrap', JPRO_CAR_URI . 'assets/js/bootstrap.min.js', array(), '3.3.0', true ); // In footer
	wp_enqueue_script( 'bootstrap' );
	
	// JP Cars Backend Stylesheet
	wp_register_style( 'car-classifieds-backend', JPRO_CAR_URI . 'assets/css/car-classifieds-backend.css', array(), '1.0.0' );
	wp_enqueue_style( 'car-classifieds-backend' );
	
	// FontAwesome
	wp_register_style( 'font-awesome', JPRO_CAR_URI . 'assets/css/font-awesome.min.css', array(), '4.2.0' );
	wp_enqueue_style( 'font-awesome' );
	
	// Chosen CSS
	wp_register_style( 'chosen', JPRO_CAR_URI . 'assets/css/chosen.css', array(), '1.4.1' );
	wp_enqueue_style( 'chosen' );
	
	// Chosen JS
	wp_register_script( 'chosen', JPRO_CAR_URI . 'assets/js/chosen.jquery.min.js', array(), '1.4.1', true ); // In footer
	wp_enqueue_script( 'chosen' );
	
	// Backend
	wp_register_script( 'jpro-backend', JPRO_CAR_URI . 'assets/js/backend.js', array(), '1.0.0', true );
	wp_enqueue_script( 'jpro-backend' );
	
	// Validation
	wp_register_script( 'jprocars-validation', JPRO_CAR_URI . 'assets/js/validation.js' );
	wp_enqueue_script( 'jprocars-validation' );
	
	// Color Picker
	if( $page == 'classifieds-settings' &&  $tab == 'styling' ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' ); 
	}
}
/**
 * Add "Transactions" && "Settings" navigation under "car-classifieds" post_type
 *
 * @since 0.1
 */
add_action('admin_menu', 'car_classifieds_submenu_pages');
function car_classifieds_submenu_pages() {
	$Settings = new JP_Settings();
	$settings = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
	// If mode = classifieds
	if( $settings['mode'] == 'classifieds' ) {
		// Add Members Page
		add_submenu_page(
			'edit.php?post_type=car-classifieds',
			__( 'Members', 'jprocars' ),
			__( 'Members', 'jprocars' ),
			'manage_options',
			'members',
			'car_classifieds_members_page_callback'
		);
		// Add Payments History Page
		add_submenu_page(
			'edit.php?post_type=car-classifieds',
			__( 'Transactions', 'jprocars' ),
			__( 'Transactions', 'jprocars' ),
			'manage_options',
			'payments-history',
			'car_classifieds_transactions_history_page_callback'
		);
	}
	// Add Settings Page
	add_submenu_page( 
		'edit.php?post_type=car-classifieds', 
		__( 'Settings', 'jprocars' ),
		__( 'Settings', 'jprocars' ),
		'manage_options', 
		'classifieds-settings', 
		'car_classifieds_settings_page_callback' 
	);
	// Add Upgrade to Premium Page
	add_submenu_page(
		'edit.php?post_type=car-classifieds',
		'<span style="font-weight:bold; color:orange;">' . __( 'Upgrade', 'jprocars' ) . '</span>',
		'<span style="font-weight:bold; color:orange;">' . __( 'Upgrade', 'jprocars' ) . '</span>',
		'manage_options',
		'jprocars-upgrade',
		'jpro_cars_upgrade_callback'
	);
}
/**
 * Members Page Callback
 *
 * @since 0.6
 */
function car_classifieds_members_page_callback() {
	require_once 'backend/members.php';
}
/**
 * Transactions History Page Callback
 *
 * @since 0.1
 */
function car_classifieds_transactions_history_page_callback() {
	require_once 'backend/transactions_history.php';
}
/**
 * General Settings Page Callback
 *
 * @since 0.1
 */
function car_classifieds_settings_page_callback() { 
	$Settings	= new JP_Settings();
	$options	= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
	
	$page 		= isset( $_GET['page'] ) ? esc_attr($_GET['page']) : ''; 
	$tab  		= isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : '';
	$action		= isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : '';
	$updated	= isset( $updated ) ? $updated : '';
	
	if( isset( $_POST['action'] ) == 'update' ) {
		$updated = true;
	}
	
	$general_settings = '';
	$payment_settings = '';
	$membership_settings = '';
	$email_settings = '';
	$currencies_settings = '';
	$validation_settings = '';
	$license_settings = '';
	$styling_settings = '';
	
	if( $page == 'classifieds-setings' && $tab == 'general_settings' ) {
		$general_settings = true;
		$case = 'general_settings';
	}
	else
	if( $page == 'classifieds-settings' && $tab == 'payment_gateways' ) {
		$payment_settings = true;
		$case = 'payment_gateways';
	}
	else
	if( $page == 'classifieds-settings' && $tab == 'membership' ) {
		$membership_settings = true;
		$case = 'membership';
	}
	else
	if( $page == 'classifieds-settings' && $tab == 'email_templates' ) {
		$email_settings = true;
		$case = 'email_templates';
	}
	else
	if( $page == 'classifieds-settings' && $tab == 'currencies' ) {
		$currencies_settings = true;
		$case = 'currencies';
	}
	else
	if( $page == 'classifieds-settings' && $tab == 'validation' ) {
		$validation_settings = true;
		$case = 'validation';
	}
	else
	if( $page == 'classifieds-settings' && $tab == 'jp_license' ) {
		$license_settings = true;
		$case = 'license_settings';
	}
	else
	if( $page == 'classifieds-settings' && $tab == 'styling' ) {
		$styling_settings = true;
		$case = 'styling_settings';
	}else{
		$general_settings = true;
		$case = 'general_settings';
	}
?>
	<div class="wrap">
		<h3><?php _e( 'JPro Cars Settings', 'jprocars' ); ?></h3>

		<h3 class="nav-tab-wrapper">
			
			<a href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=general_settings" class="nav-tab <?php if($general_settings): ?>nav-tab-active<?php endif; ?>">
				<?php _e( 'General Settings', 'jprocars' ); ?>
			</a>
			
			<?php if( $options['mode'] == 'classifieds' ): ?>
			<a href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=membership" class="nav-tab <?php if($membership_settings): ?>nav-tab-active<?php endif; ?>">
				<?php _e( 'Membership Packages', 'jprocars' ); ?>
			</a>
			<?php endif; ?>
			
			<?php if( $options['mode'] == 'classifieds' ): ?>
			<a href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=payment_gateways" class="nav-tab <?php if($payment_settings): ?>nav-tab-active<?php endif; ?>">
				<?php _e( 'Payment Gateways', 'jprocars' ); ?>
			</a>
			<?php endif; ?>
			
			<a href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=currencies" class="nav-tab <?php if($currencies_settings): ?>nav-tab-active<?php endif; ?>">
				<?php _e( 'Currencies', 'jprocars' ); ?>
			</a>
			
			<a href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=validation" class="nav-tab <?php if($validation_settings): ?>nav-tab-active<?php endif; ?>">
				<?php _e( 'Validation', 'jprocars' ); ?>
			</a>
			
			<?php if( $options['mode'] == 'classifieds' ): ?>
			<a href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=email_templates" class="nav-tab <?php if($email_settings): ?>nav-tab-active<?php endif; ?>">
				<?php _e( 'Email Templates', 'jprocars' ); ?>
			</a>
			<?php endif; ?>
			
		</h3>
		
		<?php
		// Switch between tabs
		switch( $case ):
			
			case $case == 'general_settings':
				 require_once 'backend/settings.php';
			break;
			
			case $case == 'payment_gateways':
				 require_once 'backend/gateways.php';
			break;
			
			case $case == 'membership':
				 require_once 'backend/membership.php';
			break;
			
			case $case == 'currencies':
				 require_once 'backend/currencies.php';
			break;
			
			case $case == 'validation':
				 require_once 'backend/validation.php';
			break;
			
			case $case == 'email_templates':
				 require_once 'backend/email_templates.php';
			break;
			
			case $case == 'license_settings':
				 require_once 'backend/license.php';
			break;
			
			default: require_once 'backend/settings.php';
			
		endswitch; ?>
		
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$('.chosen-select').chosen();
		});
		</script>
	 
	</div>

<?php }

/**
 * Upgrade to Premium Callback
 *
 * @since 0.9
 */
function jpro_cars_upgrade_callback() {
	require_once 'backend/upgrade.php';
}