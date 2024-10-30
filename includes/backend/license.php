<?php 
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

$Settings = new JP_Settings();
$options = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_license', true );

############################################################
# SAVE DATA TO WP_OPTIONS TABLE
############################################################
if( isset( $_POST['submit'] ) && $_POST['action'] == 'save' && wp_verify_nonce( $_REQUEST['_wpnonce'], 'jp_license' ) ) {
	$args = array(
		'envato-username' 	=> sanitize_text_field( $_POST['envato-username'] ),
		'envato-api-key'	=> esc_attr( $_POST['envato-api-key'] ),
		'jprocars-license'	=> esc_attr( $_POST['jprocars-license'] )
	);
	$Settings->update( 'WP_OPTIONS', '_jp_cars_license', serialize( $args ), false );
}
?>

<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'Product License', 'jprocars' ); ?></span>
	</div>
	<div class="jpro-panel-body">
		<form method="post" class="jpro-form-horizontal" role="form">
			
			<input type="hidden" name="action" value="save">
			<?php echo wp_nonce_field('jp_license'); ?>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Envato username', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="envato-username" type="text" placeholder="eg: jerrypro" value="<?php echo $options['envato-username']; ?>" class="jpro-form-control">
					<p class="description">
						<?php _e( 'Your Envato username.', 'jprocars' ); ?>
					</p>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Envato API key', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="envato-api-key" type="text" placeholder="eg: bjg759fk-kvta-6584-94h6-75jg8vblatftq" value="<?php echo $options['envato-api-key']; ?>" class="jpro-form-control">
					<p class="description">
						<?php _e( 'You can find API key by visiting your Envato Account page, then clicking the My Settings tab. At the bottom of the page you\'ll find your account\'s API key. Need help?', 'jprocars' ); ?>
					</p>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'JPro Cars license', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="jprocars-license" type="text" placeholder="eg: bjg759fk-kvta-6584-94h6-75jg8vblatftq" value="<?php echo $options['jprocars-license']; ?>" class="jpro-form-control">
					<p class="description">
						<?php _e( 'Please enter your CodeCanyon JPro Cars license key, you can find your key by following the instructions on this page. License key looks similar to this: bjg759fk-kvta-6584-94h6-75jg8vblatftq.', 'jprocars' ); ?>
					</p>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9">
					<?php submit_button(); ?>
				</div>
			</div>
			
		</form>
	</div>
</div>