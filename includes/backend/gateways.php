<!-- Payment Gateways Settings -->
<?php 
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

$Settings = new JP_Settings();

if( isset( $_POST['gateway'] ) || isset( $_POST['edit'] ) ) {
	$options = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_gateway_'.esc_attr($_POST['gateway']), true );
}

$options['sandbox'] = isset( $options['sandbox'] ) ? $options['sandbox'] : '';
?>

<!-- Cooming Soon
<script>
jQuery(document).ready(function() {
    jQuery("#sandbox").find('select').change(function() {
        var $val = jQuery(this).val();

        if($val === '1'){
            jQuery('#sandbox_api_user').show();
			jQuery('#sandbox_api_pass').show();
        }else{
            jQuery('#sandbox_api_user').hide();
			jQuery('#sandbox_api_pass').hide();
        }
    });
	
	<?php if( isset( $_POST['edit'] ) && $options['sandbox'] == '1' ): ?>
	jQuery('#sandbox_api_user').show();
	jQuery('#sandbox_api_pass').show();
	<?php else: ?>
	jQuery('#sandbox_api_user').hide();
	jQuery('#sandbox_api_pass').hide();
	<?php endif; ?>
});
</script>
-->

<?php 
	if( isset( $_POST['action'] ) && $_POST['action'] == 'update' ) { // Add new || Edit gateway
		
		if( $_POST['sandbox'] == '1' ) {
			$sandbox = 1;
		}else{
			$sandbox = 0;
		}
		
		$args = array(
			'title'			=> esc_attr($_POST['title']),
			'gateway'		=> esc_attr($_POST['gateway']),
			'email'			=> esc_attr($_POST['email']),
			//'api_username'	=> esc_attr($_POST['api_username']),
			//'api_password'	=> esc_attr($_POST['api_password']),
			'sandbox'		=> esc_attr($sandbox),
			//'sandbox_user'	=> esc_attr($_POST['sandbox_user']),
			//'sandbox_pass'	=> esc_attr($_POST['sandbox_pass'])
		);
		$Settings->update( 'WP_OPTIONS', '_jp_cars_gateway_'.esc_attr(strtolower($_POST['gateway'])), serialize( $args ), false );
	}
	
	/**
	 * Delete Gateways
	 */
	if( isset( $_POST['delete'] ) && isset( $_POST['gateway'] ) ) {
		$Settings->delete( 'WP_OPTIONS', '_jp_cars_gateway_'.esc_attr($_POST['gateway']) );
	}

	if( $tab == 'payment_gateways' && $action == 'add_new' && get_option( '_jp_cars_gateway_paypal') && !isset( $_POST['submit'] ) ): // If both gateways added show notice message ?>

	<div id="setting-error-settings_updated" class="error settings-error">
		<p><strong><?php _e( 'You have already added all available payment gateways!', 'jprocars' ); ?></strong></p>
	</div>

<?php else: // Else if there is available gateway for adding show add form ?>

<?php 
############################################################################################
# ADD || EDIT PAYMENT GATEWAY
############################################################################################
if( $tab == 'payment_gateways' && isset( $action ) && $action == 'add_new' || isset( $_POST['action'] ) &&  $_POST['action'] !== 'update' || isset( $_POST['edit'] ) ): ?>
<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'New Payment Gateway', 'jprocars' ); ?></span>
	</div>
	<div class="jpro-panel-body">
		<form method="post" class="jpro-form-horizontal" role="form">
		
			<input type="hidden" name="action" value="update">
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Title', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="title" type="text" placeholder="eg: PayPal" value="<?php if( !empty( $options['title'] ) ) echo $options['title']; ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Select gateway', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="gateway" class="jpro-form-control">
						<option name="paypal"><?php _e( 'PayPal', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'E-mail', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="email" type="text" placeholder="eg: john@gmail.com" value="<?php if( !empty( $options['email'] ) ) echo $options['email']; ?>" class="jpro-form-control">
				</div>
			</div>
			
			<!-- Cooming Soon
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php //_e( 'API username', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="api_username" type="text" value="<?php //if($options['api_username']){echo $options['api_username'];} ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php //_e( 'API password', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="api_password" type="text" value="<?php //if($options['api_password']){echo $options['api_password'];} ?>" class="jpro-form-control">
				</div>
			</div>
			-->
			<div id="sandbox" class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Sandbox', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="sandbox" class="jpro-form-control">
						<option value="0" <?php selected( '0', $options['sandbox'], true ); ?>><?php _e( 'disable', 'jprocars' ); ?></option>
						<option value="1" <?php selected( '1', $options['sandbox'], true ); ?>><?php _e( 'enable', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			<!-- Cooming Soon
			<div id="sandbox_api_user" class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php //_e( 'Sandbox API user', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="sandbox_user" type="text" value="<?php //if($options['sandbox_user']){echo $options['sandbox_user'];} ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div id="sandbox_api_pass" class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php //_e( 'Sandbox API pass', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="sandbox_pass" type="text" value="<?php //if($options['sandbox_pass']){echo $options['sandbox_pass'];} ?>" class="jpro-form-control">
				</div>
			</div>
			-->
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label"></label>
				<div class="col-lg-9">
					<?php submit_button(); ?>
				</div>
			</div>
			
		</form>
	</div>
</div>

<?php else: 
############################################################################################
# PAYMENT GATEWAYS MAIN PAGE
############################################################################################
$options = $Settings->getSettings( 'JP_GATEWAYS', '_jp_cars_gateway_', true );
?>

<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'Payment Gateways', 'jprocars' ); ?></span>
		<a style="top:2px;" href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=payment_gateways&action=add_new" class="add-new-h2">
			<?php _e( 'Add New', 'jprocars' ); ?>
		</a>
	</div>
	<div class="jpro-panel-body">
		<table class="jpro-table">
			<thead>
				<tr class="jpro-primary">
					<th><?php _e( 'Title', 'jprocars' ); ?></th>
					<th><?php _e( 'Gateway', 'jprocars' ); ?></th>
					<th><?php _e( 'E-mail', 'jprocars' ); ?></th>
					<!-- Cooming Soon
					<th><?php //_e( 'API username', 'jprocars' ); ?></th>
					<th><?php //_e( 'API password', 'jprocars' ); ?></th>
					-->
					<th><?php _e( 'Sandbox Mode', 'jprocars' ); ?></th>
					<!-- Coomin Soon
					<th><?php //_e( 'Sandbox API user', 'jprocars' ); ?></th>
					<th><?php //_e( 'Sandbox API pass', 'jprocars' ); ?></th>
					-->
					<th><?php _e( 'Action', 'jprocars' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if( $options ) { ?>
				<?php foreach( $options as $key => $value ): ?>
				<form method="post" class="jpro-form-horizontal" role="form">
					<input name="gateway" type="hidden" value="<?php echo esc_attr($value['gateway']); ?>">
					<?php //$len = strlen($value['api_password']); $str = $value['api_password']; ?>
					<tr>
						<td><?php echo $value['title']; ?></td>
						<td><?php echo $value['gateway']; ?></td>
						<td><?php echo $value['email']; ?></td>
						<!-- Cooming Soon 
						<td><?php //echo $value['api_username']; ?></td>
						<td><?php //if( !empty( $value['api_password'] ) ){echo substr($str, 0,1). str_repeat('*',$len - 2) . substr($str, $len - 1 ,1);} ?></td>
						-->
						<td><?php if( $value['sandbox'] == '1' ){ echo '<span style="color:red;">enabled</span>'; }else{ echo '<span style="color:green;">disabled</span>'; } ?></td>
						<!-- Cooming Soon
						<td><?php //echo $value['sandbox_user']; ?></td>
						<td><?php //echo $value['sandbox_pass']; ?></td>
						-->
						<td>
							<input name="edit" type="submit" value="<?php _e( 'Edit', 'jprocars' ); ?>" class="add-new-h2">
							<input name="delete" type="submit" value="<?php _e( 'Delete', 'jprocars' ); ?>" class="add-new-h2">
						</td>
					</tr>
				</form>
				<?php endforeach; ?>
				<?php }else{ ?>
				<tr>
					<td colspan="9"><?php _e( 'Add new payment gateway.', 'jprocars' ); ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- / Payment Gateways Settings -->