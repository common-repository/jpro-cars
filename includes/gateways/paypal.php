<?php 
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

$Settings	= new JP_Settings();
$options	= $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_gateway_paypal', true );
$user_info 	= get_userdata( get_current_user_id() );
$invoice	= uniqid();

if( $options['sandbox'] == '1' ){
	$action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
}else{
	$action_url = 'https://www.paypal.com/cgi-bin/webscr';
}
?>
	
<script>
jQuery(document).ready(function($){
 $("#submit").click();
});
</script>

<!-- Col-MD-12 -->
<div class="col-md-12">
	
	<h4><?php _e( 'Redirecting to PayPal...', 'jprocars' ); ?></h4>
	
	<form id="paypal" action="<?php echo $action_url; ?>" method="post">

		<!-- Identify your business so that you can collect the payments. -->
		<input type="hidden" name="business" value="<?php echo $options['email']; ?>">

		<!-- Specify a Buy Now button. -->
		<input type="hidden" name="cmd" value="_xclick">
		
		<!-- Item Invoice -->
		<input type="hidden" name="invoice" value="<?php echo $details['id'].'_'.$invoice; ?>">
		
		<!-- Item Name -->
		<input type="hidden" name="item_name" value="<?php echo $details['title']; ?>">

		<!-- Amount -->
		<input type="hidden" name="amount" value="<?php echo $details['price']; ?>">

		<!-- Currency -->
		<input type="hidden" name="currency_code" value="<?php echo $details['currency']; ?>">
		
		<!-- Return URL -->
		<input name="return" type="hidden" value="<?php esc_url( bloginfo('url') ); ?>/?jp-api=jp_gateway_paypal">
		
		<!-- Cancel URL -->
		<input name="cancel_return" type="hidden" value="<?php esc_url( bloginfo('url') ); ?>/?jp-api=jp_gateway_paypal">
		
		<!-- Validate Payment -->
		<input name="notify_url" type="hidden" value="<?php esc_url( bloginfo('url') ); ?>/?jp-api=jp_gateway_paypal">
		
		<!-- Custom Security Hash -->
		<input name="custom" type="hidden" value="<?php echo sha1( $invoice . $user_info->ID ); ?>">
		
		<!-- Submit Button -->
		<input id="submit" type="submit" name="submit" alt="<?php _e( 'PayPal - The safer, easier way to pay online', 'jprocars' ); ?>" style="display:none;">
		
	</form>
</div><!-- / Col-MD-12 -->