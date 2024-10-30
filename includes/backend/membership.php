<!-- Membership Packages -->
<?php 
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

$Settings = new JP_Settings();

if( isset( $_POST['id'] ) ) {
	$options = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_membership_'.esc_attr( $_POST['id'] ), true );
}else{
	$options = $Settings->getSettings( 'JP_MEMBERSHIP', '_jp_cars_membership_', true );
}

// Variables used in edit membership area
$membership_title					= isset( $options['membership_title'] ) ? $options['membership_title'] : '';
$memebrship_type					= isset( $options['membership_type'] ) ? $options['membership_type'] : '';
$membership_price					= isset( $options['membership_price'] ) ? $options['membership_price'] : '';
$membership_currency				= isset( $options['membership_currency'] ) ? $options['membership_currency'] : '';
$membership_expiration				= isset( $options['membership_expiration'] ) ? $options['membership_expiration'] : '';
$membership_classifieds_limit		= isset( $options['membership_classifieds_limit'] ) ? $options['membership_classifieds_limit'] : '';
$membership_image_upload_limit		= isset( $options['membership_image_upload_limit'] ) ? $options['membership_image_upload_limit'] : '';
$membership_classifieds_expiration	= isset( $options['membership_classifieds_expiration'] ) ? $options['membership_classifieds_expiration'] : '';
	
?>

<script>
jQuery(document).ready(function() {
    jQuery("#membership_type").find('select').change(function() {
        var $val = jQuery(this).val();

        if($val === 'paid'){
            jQuery('#membership_price').show();
			jQuery('#membership_currency').show();
        }else{
            jQuery('#membership_price').hide();
			jQuery('#membership_currency').hide();
        }
    });
	
	<?php if( isset( $_POST['edit'] ) && $options['membership_type'] !== 'free' ): ?>
	jQuery('#membership_price').show();
	jQuery('#membership_currency').show();
	<?php else: ?>
	jQuery('#membership_price').hide();
	jQuery('#membership_currency').hide();
	<?php endif; ?>
});
</script>

	<?php
	// Create || Edit Membership Package
	if( isset( $_POST['action'] ) && $_POST['action'] == 'update' ):
	
		if( empty( $_POST['membership_price'] ) ) {
			$_POST['membership_price'] = 'free';
		}
		
		// If action = edit, get package id for editing
		if( isset( $_POST['id'] ) && !empty( $_POST['id'] ) ) {
			$ID = $_POST['id'];
		}else{
			$ID = uniqid(); // Generates unique ID
		}
		
		
		// Membership expiration, 0 = never
		if( $_POST['membership_expiration'] == '0' ) {
			$_POST['membership_expiration'] = 'never';
		}
		
		// Membership classifieds posting limit, 0 = unlimited
		if( $_POST['membership_classifieds_limit'] == '0' ) {
			$_POST['membership_classifieds_limit'] = 'unlimited';
		}
		
		// Membership classifieds expiration, 0 = never
		if( $_POST['membership_classifieds_expiration'] == '0' ) {
			$_POST['membership_classifieds_expiration'] = 'never';
		}
		
		// Membership classifieds image upload limit, 0 = unlimited
		if( $_POST['membership_image_upload_limit'] == '0' ) {
			$_POST['membership_image_upload_limit'] = 'unlimited';
		}
		
		// Membership details array
		$args = array(
			'id'								=> esc_attr($ID),
			'membership_title' 					=> esc_attr($_POST['membership_title']),
			'membership_type'					=> esc_attr($_POST['membership_type']),
			'membership_price'					=> esc_attr($_POST['membership_price']),
			'membership_currency'				=> esc_attr($_POST['membership_currency']),
			'membership_expiration'				=> esc_attr($_POST['membership_expiration']),
			'membership_classifieds_limit'		=> esc_attr($_POST['membership_classifieds_limit']),
			'membership_classifieds_expiration'	=> esc_attr($_POST['membership_classifieds_expiration']),
			'membership_image_upload_limit'		=> esc_attr($_POST['membership_image_upload_limit'])
		);
		
		// Save Package
		if( update_option( '_jp_cars_membership_'.esc_attr( $args['id'] ), serialize( $args ) ) ):
			echo $Settings->success('update');
		else:
			echo $Settings->failed();
		endif;
	endif;
	
	// Delete Membership Package
	if( isset( $_POST['delete'] ) && isset( $_POST['id'] ) ) {
		$Settings->delete( 'WP_OPTIONS', '_jp_cars_membership_'.esc_attr( $_POST['id'] ) );
	}
	?>

<?php if( $tab == 'membership' && isset( $action ) && $action == 'add_new' || isset( $_POST['action'] ) && $_POST['action'] !== 'update' || isset( $_POST['edit'] ) && !isset( $_POST['update'] ) ): ?>

<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span><?php _e( 'New Membership Package', 'jprocars' ); ?></span>
	</div>
	<div class="jpro-panel-body">
		<form method="post" class="jpro-form-horizontal" role="form">
		
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="id" value="<?php if(!empty($_POST['id'])){echo $_POST['id'];} ?>">
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Package title', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="membership_title" type="text" placeholder="<?php _e( 'eg: Gold Membership', 'jprocars'); ?>" value="<?php echo $membership_title; ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div id="membership_type" class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Package type', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="membership_type" class="jpro-form-control">
						<option value="free" <?php selected( 'free', $memebrship_type, true ); ?>><?php _e( 'Free', 'jprocars' ); ?></option>
						<option value="paid" <?php selected( 'paid', $memebrship_type, true ); ?>><?php _e( 'Paid', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div id="membership_price" class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Package price', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input type="text" name="membership_price" placeholder="eg:10" value="<?php echo $membership_price; ?>" class="jpro-form-control">
					<span class="errprice"></span>
				</div>
			</div>
			
			<div id="membership_currency" class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Package currency', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="membership_currency" class="jpro-form-control">
						<option value="EUR" <?php selected( 'EUR', $membership_currency, true ); ?>><?php _e( 'EUR', 'jprocars' ); ?></option>
						<option value="GBP" <?php selected( 'GBP', $membership_currency, true ); ?>><?php _e( 'GBP', 'jprocars' ); ?></option>
						<option value="JPY" <?php selected( 'JPY', $membership_currency, true ); ?>><?php _e( 'JPY', 'jprocars' ); ?></option>
						<option value="USD" <?php selected( 'USD', $membership_currency, true ); ?>><?php _e( 'USD', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Package expiration', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="membership_expiration" class="jpro-form-control">
						<?php foreach( range(0,36) as $months ): ?>
							<option value="<?php echo $months; ?>" <?php selected( $membership_expiration, $months, true ); ?>>
							<?php
								if( $months == '0' )
								{
									echo 'never';
								}
								else
								if( $months == '1' )
								{
									echo $months.' month';
								}
								else
								{
									echo $months.' months';
								}
							?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Classifieds posting limit', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="membership_classifieds_limit" class="jpro-form-control">
						<?php foreach( range(0,200) as $posts ): ?>
							<option value="<?php echo $posts; ?>" <?php selected( $membership_classifieds_limit, $posts, true ); ?>>
							<?php
								if( $posts == '0' )
								{
									echo 'unlimited';
								}
								else
								if( $posts == '1' )
								{
									echo $posts.' post';
								}
								else
								{
									echo $posts.' posts';
								}
							?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Classifieds image upload limit', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="membership_image_upload_limit" class="jpro-form-control">
						<?php foreach( range(0,10) as $images ): ?>
							<option value="<?php echo $images; ?>" <?php selected( $membership_image_upload_limit, $images, true ); ?>>
							<?php
								if( $images == '0' )
								{
									echo 'unlimited';
								}
								else
								if( $images == '1' )
								{
									echo $images.' image';
								}else
								{
									echo $images.' images';
								}
							?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Classifieds will expire after', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="membership_classifieds_expiration" class="jpro-form-control">
						<?php foreach( range(0,60) as $months ): ?>
							<option value="<?php echo $months; ?>" <?php selected( $membership_classifieds_expiration, $months, true ); ?>>
							<?php 
								if( $months == '0' ) 
								{ 
									echo 'never'; 
								}
								else
								if( $months == '1' ) 
								{
									echo $months.' day';
								}
								else
								{ 
									echo $months.' days';
								} 
							?>
							</option>
						<?php endforeach; ?>
					</select>
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

<?php else: ### Show Main Membership Page ### ?>
<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'Membership Packages', 'jprocars' ); ?></span>
		<a style="top:2px;" href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=membership&action=add_new" class="add-new-h2">
			<?php _e( 'Add New', 'jprocars' ); ?>
		</a>
	</div>
	<div class="jpro-panel-body">
		<table class="jpro-table">
			<thead>
				<tr class="jpro-primary">
					<th><?php _e( 'Package title', 'jprocars' ); ?></th>
					<th><?php _e( 'Package type', 'jprocars' ); ?></th>
					<th><?php _e( 'Package price', 'jprocars' ); ?></th>
					<th><?php _e( 'Package expire', 'jprocars' ); ?></th>
					<th><?php _e( 'Classifieds limit', 'jprocars' ); ?></th>
					<th><?php _e( 'Classifieds image limit', 'jprocars' ); ?></th>
					<th><?php _e( 'Classifieds expire', 'jprocars' ); ?></th>
					<th><?php _e( 'Action', 'jprocars' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php if( !isset( $_POST['id'] ) ) { if( !empty( $options ) ) { ?>
			<?php foreach( $options as $option ) { ?>
			<form method="post">
				<input type="hidden" name="id" value="<?php echo $option['id']; ?>">
				<tr>
					<td><?php echo $option['membership_title']; ?></td>
					<td><?php echo $option['membership_type']; ?></td>
					<td><?php
					switch( $option['membership_type'] ):
						case( $option['membership_type'] == 'free' ):
							_e( 'free', 'jprocars' );
						break;
						
						case( $option['membership_type'] == 'paid' ):
							$currency = jpro_get_currencies( $option['membership_currency'] );
							echo $option['membership_price'].$currency['symbol'];
						break;
					endswitch;
					?></td>
					<td><?php 
					switch( $option['membership_expiration'] ):
						case( $option['membership_expiration'] == 'never' ):
							_e( 'never', 'jprocars' );
						break;
						
						default: echo __( 'after', 'jprocars' ).' '.$option['membership_expiration'].' '.__( 'months', 'jprocars' );
					endswitch;
					?></td>
					<td><?php 
					switch( $option['membership_classifieds_limit'] ):
						case( $option['membership_classifieds_limit'] == 'unlimited' ):
							_e( 'unlimited', 'jprocars' );
						break;
						
						default: echo $option['membership_classifieds_limit'].' '.__( 'posts', 'jprocars' );
					endswitch;
					?></td>
					<td><?php
					switch( $option['membership_image_upload_limit'] ):
						case( $option['membership_image_upload_limit'] == 'unlimited' ):
							_e( 'unlimited', 'jprocars' );
						break;
						
						case( $option['membership_image_upload_limit'] == '1' ):
							echo $option['membership_image_upload_limit'].' '.__( 'image', 'jprocars' );
						break;
						
						default: echo $option['membership_image_upload_limit'].' '.__( 'images', 'jprocars' );
					endswitch;
					?></td>
					<td><?php 
					switch( $option['membership_classifieds_expiration'] ):
						case( $option['membership_classifieds_expiration'] == 'never' ):
							_e( 'never', 'jprocars' );
						break;
						
						case( $option['membership_classifieds_expiration'] == '1' ):
							echo __( 'after', 'jprocars' ).' '.$option['membership_classifieds_expiration'].' '.__( 'day', 'jprocars' );
						break;
						
						default: echo __( 'after', 'jprocars' ).' '.$option['membership_classifieds_expiration'].' '.__( 'days', 'jprocars' );
					endswitch;
					?></td>
					<td>
						<input name="edit" type="submit" value="<?php _e( 'Edit', 'jprocars' ); ?>" class="add-new-h2" style="top:0px;">
						<input name="delete" type="submit" value="<?php _e( 'Delete', 'jprocars' ); ?>" class="add-new-h2" style="top:0px;">
					</td>
				</tr>
			</form>
			<?php } }else{ ?>
			<tr>
				<td colspan="8">
					<?php _e( 'Add new membership package.', 'jprocars' ); ?>
				</td>
			</tr>
			<?php } } ?>
			</tbody>
		</table>
	</div>
</div>
<?php endif; ?>
<!-- / Membership Packages -->