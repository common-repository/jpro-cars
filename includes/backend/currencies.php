<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

$Settings	= new JP_Settings;
$options	= unserialize( get_option( '_jp_cars_currencies' ) ); 
$edit		= isset( $_POST['edit-currency'] ) ? true : false;
	
	if( isset( $_POST['currency'] ) ) {
		$iso = esc_attr( $_POST['currency'] );
	}
	else
	if( isset( $_POST['iso'] ) ) {
		$iso = esc_attr( $_POST['iso'] );
	} else {
		$iso = '';
	}

$position	= isset( $_POST['edit-currency'] ) ? esc_attr( $_POST['position'] ) : '';
$currency 	= jpro_get_currencies( $iso );

if( isset( $_POST['action'] ) && $_POST['action'] == 'add' ) {
	
	// Store currency details into array
	$args[$iso] = array(
		'iso'		=> esc_attr($currency['iso']),
		'name'		=> esc_attr($currency['name']),
		'symbol'	=> esc_attr($currency['symbol']),
		'position'	=> esc_attr($_POST['position'])
	);
	
	// Check if $options is array or not, if not let's make it array
	if( !is_array( $options ) ) $options = array( $options );
	
	// Merge new currency with current ones
	$options = array_merge( $options, $args );
	
	// Let's add new currency
	$Settings->update( 'JP_CURRENCIES', '_jp_cars_currencies', serialize( $options ) );
}

else
if( isset( $_POST['action'] ) && $_POST['action'] == 'edit' ) {
	
	// Remove currency we edit
	unset($options[$iso]);
	
	// Let's add updated currency details into array
	$args[$iso] = array(
		'iso'		=> esc_attr($currency['iso']),
		'name'		=> esc_attr($currency['name']),
		'symbol'	=> esc_attr($currency['symbol']),
		'position'	=> esc_attr($_POST['position'])
	);
	
	// Merge Updated Currency with Currencies
	$options = array_merge( $options, $args );
	
	// Update options
	$Settings->update( 'JP_CURRENCIES', '_jp_cars_currencies', serialize( $options ) );
}

else
if( isset( $_POST['delete-currency'] ) ) {
	unset($options[$iso]);
	$Settings->update( 'JP_CURRENCIES', '_jp_cars_currencies', serialize( $options ) );
}

?>

<?php if( isset( $action ) && $action == 'add_new' && isset( $_GET['tab'] ) && $_GET['tab'] == 'currencies' || isset( $_POST['edit-currency'] ) ): // Add || Edit currency ?>

<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title">
		<?php if( $edit ): ?>
			<?php _e( 'Edit Currency', 'jprocars' ); ?>
		<?php else: ?>
			<?php _e( 'New Currency', 'jprocars' ); ?>
		<?php endif; ?>
		</span>
	</div>
	<div class="jpro-panel-body">
		<table class="jpro-table">
			<thead>
				<tr class="jpro-primary">
				
				</tr>
			</thead>
			<tbody>
				<form method="post" class="jpro-form-horizontal" role="form">
					
					<?php if( $edit ): ?>
					<input name="action" value="edit" type="hidden">
					<?php else: ?>
					<input name="action" value="add" type="hidden">
					<?php endif; ?>
					
					<div class="jpro-form-group">
						<label class="col-lg-1 jpro-control-label">
							<?php _e( 'Currency', 'jprocars' ); ?>
						</label>
						<div class="col-lg-10">
							<select name="currency" class="chosen-select" <?php if( $edit ) echo 'disabled="disabled"'; ?>>
							<?php foreach( jpro_get_currencies() as $currency ): ?>
								<option value="<?php echo $currency['iso']; ?>" <?php selected( $iso, $currency['iso'], true ); ?>><?php echo $currency['name'].' ('.$currency['symbol'].')'; ?></option>
							<?php endforeach; ?>
							</select>
							<?php if( $edit ): ?>
							<input name="currency" value="<?php echo $iso; ?>" type="hidden">
							<?php endif; ?>
						</div>
					</div>
					
					<div class="jpro-form-group">
						<label class="col-lg-1 jpro-control-label">
							<?php _e( 'Currency Position', 'jprocars' ); ?>
						</label>
						<div class="col-lg-10">
							<select name="position" class="chosen-select">
								<option value="left" <?php selected( 'left', $position, true ); ?>><?php echo __( 'Left', 'jprocars' ).' (&#8364;99.99)'; ?></option>
								<option value="right" <?php selected( 'right', $position, true ); ?>><?php echo __( 'Right', 'jprocars' ).' (99.99&#8364;)'; ?></option>
								<option value="left_space" <?php selected( 'left_space', $position, true ); ?>><?php echo __( 'Left with space', 'jprocars' ).' (&#8364; 99.99)'; ?></option>
								<option value="right_space" <?php selected( 'right_space', $position, true ); ?>><?php echo __( 'Right with space', 'jprocars' ).' (99.99 &#8364;)'; ?></option>
							</select>
						</div>
					</div>
					
					<div class="jpro-form-group">
						<label class="col-lg-1 jpro-control-label"></label>
						<div class="col-lg-10">
							<?php if( $edit ) submit_button( __( 'Edit Currency', 'jprocars' ) ); else submit_button( __( 'Add Currency', 'jprocars' ) ); ?>
						</div>
					</div>
					
				</form>
			</tbody>
		</table>
	</div>
</div>

<?php else: // List currencies ?>

<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'Currencies', 'jprocars' ); ?></span>
		<a style="top:2px;" href="<?php echo esc_url( admin_url() ); ?>edit.php?post_type=car-classifieds&page=classifieds-settings&tab=currencies&action=add_new" class="add-new-h2">
			<?php _e( 'Add New', 'jprocars' ); ?>
		</a>
	</div>
	<div class="jpro-panel-body">
		<table class="jpro-table">
			<thead>
				<tr class="jpro-primary">
					<th><?php _e( 'Name', 'jprocars' ); ?></th>
					<th><?php _e( 'ISO', 'jprocars' ); ?></th>
					<th><?php _e( 'Symbol', 'jprocars' ); ?></th>
					<th><?php _e( 'Symbol Position', 'jprocars' ); ?></th>
					<th><?php _e( 'Actions', 'jprocars' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if( $options ): foreach( $options as $currency ): ?>
				<tr>
					<form method="post" class="jpro-form-horizontal" role="form">
					<input name="iso" value="<?php echo $currency['iso']; ?>" type="hidden">
					<input name="position" value="<?php echo $currency['position']; ?>" type="hidden">
					<td><?php echo $currency['name']; ?></td>
					<td><?php echo $currency['iso']; ?></td>
					<td><?php echo $currency['symbol']; ?></td>
					<td>
					<select name="position" class="chosen-select" disabled>
						<option value="<?php echo $currency['position']; ?>">
						<?php switch( $currency['position'] ):
								case $currency['position'] == 'left':
									echo __( 'Left', 'jprocars' ).' ('.$currency['symbol'].'99.99)';
								break;
								
								case $currency['position'] == 'right':
									echo __( 'Right', 'jprocars' ).' (99.99'.$currency['symbol'].')';
								break;
								
								case $currency['position'] == 'left_space':
									echo __( 'Left with space', 'jprocars' ).' ('.$currency['symbol'].' 99.99)';
								break;
								
								case $currency['position'] == 'right_space':
									echo __( 'Right with space', 'jprocars' ).' (99.99 '.$currency['symbol'].')';
								break;
							endswitch; ?>
						</option>
					</select>
					</td>
					<td>
						<input name="edit-currency" type="submit" value="<?php _e( 'Edit', 'jprocars' ); ?>" class="add-new-h2">
						<input name="delete-currency" type="submit" value="<?php _e( 'Delete', 'jprocars' ); ?>" class="add-new-h2">
					</td>
					</form>
				</tr>
				<?php endforeach; else: ?>
				<tr>
					<td colspan="5"><?php _e( 'Please add currency.', 'jprocars' ); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<?php endif; ?>