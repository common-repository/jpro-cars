<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue Scripts in Footer
 * =========================
 * @since 0.5
 */
add_filter( 'wp_footer', 'jp_footer_scripts' );
function jp_footer_scripts() {
	global $post;
	
	// Enqueue script only if "jpro_cars" shortcode used
	if( has_shortcode( $post->post_content, 'jpro_cars' ) ) {
		echo "
		<!-- jQuery List && Grid Style -->
		<script>
		jQuery('document').ready(function() {
			jQuery('#cars-list-grid-style').click(function(e) {
				e.preventDefault();
				if(jQuery('#change-layout').hasClass('cars-list')) 
				{
					jQuery('#change-layout').removeClass('cars-list');
					jQuery('#change-layout').addClass('cars-grid');
					
					jQuery('#cars-list-grid-style i').removeClass('fa-th');
					jQuery('#cars-list-grid-style i').addClass('fa-list');
				}else if(jQuery('#change-layout').hasClass('cars-grid'))
				{
					jQuery('#change-layout').removeClass('cars-grid');
					jQuery('#change-layout').addClass('cars-list');
					
					jQuery('#cars-list-grid-style i').removeClass('fa-list');
					jQuery('#cars-list-grid-style i').addClass('fa-th');
				}
			});
			jQuery('#cars-list-grid-style i').addClass('fa-th');
		});
		</script><!-- / jQuery List && Grid Style -->
		";
	}
}

/**
 * Apply FREE membership package
 *
 * @since 0.1
 * @return boolean
 */
function jpro_apply_membership_package( $details ) {
	// Free Package
	if( isset( $details ) && $details['type'] == 'free' ){
		
		if( update_user_meta( get_current_user_id(), '_jp_cars_membership', serialize( $details ) ) ) 
			return true;
	}
}
/**
 * Apply PAID membership package
 *
 * @since 0.1
 * @return void
 */
add_action( 'init', 'jpro_apply_paid_membership_package' );
function jpro_apply_paid_membership_package() {
	global $wpdb;
	
	$Settings	= new JP_Settings();
	$settings	= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
	$user_info 	= get_userdata( get_current_user_id() );

	###########################################################################
	# PAYPAL PAYMENTS PROCCESSING
	###########################################################################
	if( isset( $_REQUEST['jp-api'] ) && $_REQUEST['jp-api'] == 'jp_gateway_paypal' ) {

		$payer_email	= esc_attr( $_REQUEST['payer_email'] );
		$payment_status = esc_attr( strtolower($_REQUEST['payment_status']) );
		$currency		= esc_attr( $_REQUEST['mc_currency'] );
		
		$invoice = esc_attr( $_REQUEST['invoice'] );
		$invoice = explode( '_', $invoice ); // Lets separate package ID from Invoice ID
		
		// Invoice[0] holding membership package ID
		$membership_package_ID = $invoice[0]; // Package ID
		
		// Invoice[1] holding invoice ID
		$invoice = $invoice[1]; // Invoice ID
		
		// Collect incoming hash
		$incoming_hash 	= esc_attr( $_REQUEST['custom'] );
		
		// Calculate our hash
		$hash = sha1( $invoice . $user_info->ID );
		
		// Match our hash with incoming hash, if hash's match lets finish proccess
		if( $hash == $incoming_hash && $payment_status == 'completed' || $hash == $incoming_hash && $payment_status == 'pending' ) {
			
			// Lets get purchased membership package details from database
			$membership_settings = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_membership_'.$membership_package_ID, true );
			
			// Organize membership package details for post meta
			$package = array(
				'id'						=> esc_attr($membership_settings['id']),
				'title'						=> esc_attr($membership_settings['membership_title']),
				'type'						=> esc_attr($membership_settings['membership_type']),
				'price'						=> esc_attr($membership_settings['membership_price']),
				'currency'					=> esc_attr($membership_settings['membership_currency']),
				'purchase_date'				=> esc_attr(date('Y-m-d')),
				'expire_date'				=> jpro_expiration_calculator('membership', $membership_settings['membership_expiration']),
				'classifieds_limit'			=> esc_attr($membership_settings['membership_classifieds_limit']),
				'classifieds_expire'		=> esc_attr($membership_settings['membership_classifieds_expiration']),
				'classifieds_image_limit'	=> esc_attr($membership_settings['membership_image_upload_limit'])
			);

			// Lets apply membership package to user
			update_user_meta( get_current_user_id(), '_jp_cars_membership', serialize( $package ) );
			
			// Lets add transaction history to database
			$transaction_data = array(
				'id'			=> '',
				'user_id'		=> get_current_user_id(),
				'username'		=> $user_info->user_login,
				'user_email'	=> $payer_email,
				'package_id'	=> $package['id'],
				'package_title'	=> $package['title'],
				'gateway'		=> 'PayPal',
				'amount'		=> $package['price'],
				'currency'		=> $currency,
				'date'			=> $package['purchase_date'],
				'status'		=> $payment_status
			);
			
			$table_name = $wpdb->prefix . "jp_cars_transactions"; 
			$wpdb->insert( $table_name, $transaction_data );
			
			// Email user about successfull submition
			$admin_email	= get_option( 'admin_email' );
			$current_user	= wp_get_current_user(); // Get current user info
			$email_settings	= unserialize( get_option( '_jp_cars_email_templates' ) ); // Get email templates
			
			$to 		= $current_user->user_email;
			$subject 	= $email_settings['classified_submited_subject'];
			$message	= $email_settings['classified_submited_message'];
			$headers[]	= 'FROM: ' . get_bloginfo('name') . '<' . $admin_email . '>';
			
			if( empty( $user_info->first_name ) ) {
				$user_info->first_name = __( 'NoName', 'jprocars' );
			}
			
			if( empty( $user_info->last_name ) ) {
				$user_info->last_name = __( 'NoLastName', 'jprocars' );
			}
				
			$subject = str_replace( '{first_name}', $user_info->first_name, $subject );
			$subject = str_replace( '{last_name}', $user_info->last_name, $subject );
			$subject = str_replace( '{email}', $payer_email, $subject );
			$subject = str_replace( '{package_title}', $package['title'], $subject );
			$subject = str_replace( '{package_purchased_date}', $package['purchase_date'], $subject );
			$subject = str_replace( '{package_expire_date}', $package['expire_date'], $subject );
			$subject = str_replace( '{package_classifieds_posting_limit}', $package['classifieds_limit'], $subject );
			$subject = str_replace( '{package_classifieds_image_limit}', $package['classifieds_image_limit'], $subject );
			$subject = str_replace( '{package_url}', esc_url( get_permalink( $settings['cc_page_id'] ).'?jp=my-membership' ), $subject );
			
			$message = str_replace( '{first_name}', $user_info->first_name, $message );
			$message = str_replace( '{last_name}', $user_info->last_name, $message );
			$message = str_replace( '{email}', $payer_email, $message );
			$message = str_replace( '{package_title}', $package['title'], $message );
			$message = str_replace( '{package_purchased_date}', $package['purchase_date'], $message );
			$message = str_replace( '{package_expire_date}', $package['expire_date'], $message );
			$message = str_replace( '{package_classifieds_posting_limit}', $package['classifieds_limit'], $message );
			$message = str_replace( '{package_classifieds_image_limit}', $package['classifieds_image_limit'], $message );
			$message = str_replace( '{package_url}', esc_url( get_permalink( $settings['cc_page_id'] ).'?jp=my-membership' ), $message );
			
			wp_mail( $to, $subject, $message, $headers );
			
			// Redirect to successful payment page
			if( $settings['payment_success'] ) {
				wp_redirect( esc_url( get_permalink( $settings['payment_success'] ) ) );
			}else{
				wp_redirect( get_bloginfo('url') );
			}
			exit;
		}else{
			// Failed - Pending payment redirection
			if( $settings['payment_failed'] ) {
				wp_redirect( esc_url( get_permalink( $settings['payment_failed'] ) ) );
			}else{
				wp_redirect( esc_url( get_bloginfo('url') ) );
			}
			exit;
		}
	}
	else
	###########################################################################
	# SKRILL PAYMENTS PROCCESSING
	###########################################################################
	if( isset( $_GET['jp-api'] ) && $_GET['jp-api'] == 'jp_gateway_skrill' ) {

	}
}
/**
 * Disable Commenting on "payment success" && "payment failed" && "car classfieds" pages.
 *
 * @since 0.2
 * @return string
 */
add_filter( 'comments_open', 'jpro_disable_comments', 10, 2 );
function jpro_disable_comments( $open, $post_id ) {
	$Settings		= new JP_Settings();
	$settings		= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
	
	if ( is_page( $settings['payment_failed'] ) or is_page( $settings['payment_success'] ) or is_page( $settings['cc_page_id'] ) )
		$open = false;

	return $open;
}
/**
 * Get Post Meta by ID
 * Used ONLY in frontend, edit_classified.php file.
 *
 * @since 0.1
 * @return string
 */
function jp_post_meta( $key ) {
	$post_id 	= esc_attr( $_GET['id'] );
	$post_meta 	= get_post_meta( $post_id, $key, true );
	
	return $post_meta;
}
/**
 * Get Current Theme Details
 *
 * @since 0.2
 * @return string
 */
function jp_theme( $args ) {
	$theme = wp_get_theme();
	return $theme->get( $args );
}