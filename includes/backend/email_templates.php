<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

$Settings = new JP_Settings();
$options = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_email_templates', true );

if( isset( $_POST['action'] ) && $_POST['action'] == 'save' && wp_verify_nonce( $_REQUEST['_wpnonce'], 'email_template_save' ) ) {
	
	$args = array(
		'membership_purchase_subject' => esc_attr( $_POST['membership_purchase_subject'] ),
		'membership_purchase_message' => $_POST['membership_purchase_message'],
		'classified_submited_subject' => esc_attr( $_POST['classified_submited_subject'] ),
		'classified_submited_message' => $_POST['classified_submited_message']
	);
	
	$Settings->update( 'WP_OPTIONS', '_jp_cars_email_templates', serialize( $args ), false );
}
else
if( isset( $_POST['action'] ) && $_POST['action'] == 'save' && !wp_verify_nonce( $_REQUEST['_wpnonce'], 'email_template_save' ) ) {
	echo '<div id="setting-error-settings_updated" class="error settings-error">' .
			'<p><strong>'. __( 'Failed security check!', 'jprocars' ) .'</strong></p>' .
		 '</div>';
}
?>

<style>
textarea { height:200px; }
</style>

<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'Email Templates', 'jprocars' ); ?></span>
	</div>
	<div class="jpro-panel-body">
		<form method="post" class="jpro-form-horizontal" role="form">
			<input type="hidden" name="action" value="save">
			<?php echo wp_nonce_field('email_template_save'); ?>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9">
					<h3 style="color:green;"><?php _e( 'Membership package purchase successful - Template', 'jprocars' ); ?></h3>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9">
					<span style="color: orange;"><b><?php echo __( 'You can use next tags which will be replaced with individual user info', 'jprocars' ).':<br> {first_name}, {last_name}, {email}, {package_title}, {package_purchased_date}, {package_expire_date}, {package_classifieds_posting_limit}, {package_classifieds_image_limit}, {package_url}'; ?></b></span>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"><?php _e( 'Subject', 'jprocars' ); ?></label>
				<div class="col-lg-9">
					<input name="membership_purchase_subject" type="text" placeholder="<?php _e( 'Email subject', 'jprocars' ); ?>" value="<?php echo $options['membership_purchase_subject']; ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"><?php _e( 'Message', 'jprocars' ); ?></label>
				<div class="col-lg-9">
					<?php wp_editor( $options['membership_purchase_message'], 'membership_purchase_message', array( true, false ) ); ?>
				</div>
			</div>
		
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9">
					<h3 style="color:green;"><?php _e( 'Classified submitted successfuly - Template', 'jprocars' ); ?></h3>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9">
					<span style="color: orange;"><b><?php echo __( 'You can use next tags which will be replaced with individual user info', 'jprocars' ).':<br> {first_name}, {last_name}, {email}, {classified_ID}, {classified_title}, {classified_description}, {classified_url}'; ?></b></span>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"><?php _e( 'Subject', 'jprocars' ); ?></label>
				<div class="col-lg-9">
					<input name="classified_submited_subject" type="text" placeholder="<?php _e( 'Email subject', 'jprocars' ); ?>" value="<?php echo $options['classified_submited_subject']; ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"><?php _e( 'Message', 'jprocars' ); ?></label>
				<div class="col-lg-9">
					<?php wp_editor( $options['classified_submited_message'], 'classified_submited_message', array( true, false ) ); ?>
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