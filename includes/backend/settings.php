<!-- General Settings -->
<?php 
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;
	
$Settings = new JP_Settings();
$options = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );

$_POST['mode'] 						= isset( $_POST['mode'] ) ? $_POST['mode'] : '';
$_POST['car_classifieds_page']		= isset( $_POST['car_classifieds_page'] ) ? $_POST['car_classifieds_page'] : '';
$_POST['cars_per_page']				= isset( $_POST['cars_per_page'] ) ? $_POST['cars_per_page'] : '';
$_POST['payment_success']			= isset( $_POST['payment_success'] ) ? $_POST['payment_success'] : '';
$_POST['payment_failed']			= isset( $_POST['payment_failed'] ) ? $_POST['payment_failed'] : '';

##############################################################
# SAVE GENERAL SETTINGS INTO DATABASE
##############################################################
if( isset( $_POST['action'] ) && $_POST['action'] == 'save' ):
	
	$args = array(
		'mode'						=> esc_attr($_POST['mode']),
		'cc_page_id'				=> esc_attr($_POST['car_classifieds_page']),
		'cars_per_page'				=> esc_attr($_POST['cars_per_page']),
		'payment_success' 			=> esc_attr($_POST['payment_success']),
		'payment_failed'			=> esc_attr($_POST['payment_failed'])
	);
	
	// Save General Settings
	$Settings->update( 'WP_OPTIONS', '_jp_cars_settings', serialize( $args ) );
	
endif; ?>
<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'General Settings', 'jprocars' ); ?></span>
	</div>
	<div class="jpro-panel-body">
		<form method="post" class="jpro-form-horizontal" role="form">
			<input type="hidden" name="action" value="save">
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9"><h3><?php _e( 'General Settings', 'jprocars' ); ?></h3></div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Mode', 'jprocars' ); ?>
					<i class="fa fa-question-circle" title="<?php _e( 'Select between car classifieds or car dealer mode.', 'jprocars' ); ?>" data-toggle="tooltip" data-placement="top"></i>
				</label>
				<div class="col-lg-9">
					<select name="mode" class="jpro-form-control">
						<option value="classifieds" <?php if($options['mode'] == 'classifieds'): ?>selected<?php endif; ?>><?php _e( 'Car Classifieds', 'jprocars' ); ?></option>
						<option value="dealer" <?php if($options['mode'] == 'dealer'): ?>selected<?php endif; ?>><?php _e( 'Car Dealer', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php if( $options['mode'] == 'dealer' ) {_e( 'Car Dealer Page', 'jprocars' );}else{_e( 'Car Classifieds Page', 'jprocars' );} ?>
					<i class="fa fa-question-circle" title="<?php _e( 'Choose page on which you are serving car classifieds service. If you don\'t set up this properly there could be problems with plugin operations.', 'jprocars' ); ?>" data-toggle="tooltip" data-placement="top"></i>
				</label>
				<div class="col-lg-9">
					<select name="car_classifieds_page" class="jpro-form-control">
						<?php foreach( get_pages() as $page ): ?>
							<option value="<?php echo $page->ID; ?>" <?php selected( $options['cc_page_id'], $page->ID, true ); ?>><?php echo $page->post_title; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Cars per page', 'jprocars' ); ?>
					<i class="fa fa-question-circle" title="<?php _e( 'Set how many posts will be shown per page.', 'jprocars' ); ?>" data-toggle="tooltip" data-placement="top"></i>
				</label>
				<div class="col-lg-9">
					<select name="cars_per_page" class="jpro-form-control">
						
						<?php JP_Cars::dropdown_years( $options['cars_per_page'], $from = 1, $to = 20 ); ?>
						
					</select>
				</div>
			</div>
			
			<?php if( $options['mode'] == 'classifieds' ): ?>
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9"><h3><?php _e( 'Payment Settings', 'jprocars' ); ?></h3></div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Payment successful page', 'jprocars' ); ?>
					<i class="fa fa-question-circle" title="<?php _e( 'Select page where users will be redirected after successful payment for membership package.', 'jprocars' ); ?>" data-toggle="tooltip" data-placement="top"></i>
				</label>
				<div class="col-lg-9">
					<select name="payment_success" class="jpro-form-control">
						<?php foreach( get_pages() as $page ): ?>
							<option value="<?php echo $page->ID; ?>" <?php selected( $options['payment_success'], $page->ID, true ); ?>><?php echo $page->post_title; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Payment failed', 'jprocars' ); ?>
					<i class="fa fa-question-circle" title="<?php _e( 'Select page where users will be redirected after unsuccessful payment for membership package.', 'jprocars' ); ?>" data-toggle="tooltip" data-placement="top"></i>
				</label>
				<div class="col-lg-9">
					<select name="payment_failed" class="jpro-form-control">
						<?php foreach( get_pages() as $page ): ?>
							<option value="<?php echo $page->ID; ?>" <?php selected( $options['payment_failed'], $page->ID, true ); ?>><?php echo $page->post_title; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
	
			<?php endif; ?>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9">
					<?php submit_button(); ?>
				</div>
			</div>
			
		</form>
	</div>
</div>
<script>
jQuery( document ).ready(function($) {
	$('[data-toggle="tooltip"]').tooltip();
});
</script>
<!-- / General Settings -->