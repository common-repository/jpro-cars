<?php 
	$Settings = new JP_Settings();
	$options  = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_validation', true );
	
	// Fix undefined index notice
	$options['car-version_req']			= isset( $options['car-version_req'] ) ? $options['car-version_req'] : '';
	$options['car-year_req']			= isset( $options['car-year_req'] ) ? $options['car-year_req'] : '';
	$options['car-mileage_req']			= isset( $options['car-mileage_req'] ) ? $options['car-mileage_req'] : '';
	$options['car-fuel_req']			= isset( $options['car-fuel_req'] ) ? $options['car-fuel_req'] : '';
	$options['car-engine_req']			= isset( $options['car-engine_req'] ) ? $options['car-engine_req'] : '';
	$options['car-horsepower_req']		= isset( $options['car-horsepower_req'] ) ? $options['car-horsepower_req'] : '';
	$options['car-transmission_req']	= isset( $options['car-transmission_req'] ) ? $options['car-transmission_req'] : '';
	$options['car-drive_req']			= isset( $options['car-drive_req'] ) ? $options['car-drive_req'] : '';
	$options['car-doors_req']			= isset( $options['car-doors_req'] ) ? $options['car-doors_req'] : '';
	$options['car-seats_req']			= isset( $options['car-seats_req'] ) ? $options['car-seats_req'] : '';
	$options['car-color_req']			= isset( $options['car-color_req'] ) ? $options['car-color_req'] : '';
	$options['car-condition_req']		= isset( $options['car-condition_req'] ) ? $options['car-condition_req'] : '';
	$options['car-vin_req']				= isset( $options['car-vin_req'] ) ? $options['car-vin_req'] : '';
	$options['car-price_req']			= isset( $options['car-price_req'] ) ? $options['car-price_req'] : '';
	$options['car-price-type_req']		= isset( $options['car-price-type_req'] ) ? $options['car-price-type_req'] : '';
	$options['car-warranty_req']		= isset( $options['car-warranty_req'] ) ? $options['car-warranty_req'] : '';
	$options['car-currency_req']		= isset( $options['car-currency_req'] ) ? $options['car-currency_req'] : '';
	$options['first-name_req']			= isset( $options['first-name_req'] ) ? $options['first-name_req'] : '';
	$options['last-name_req']			= isset( $options['last-name_req'] ) ? $options['last-name_req'] : '';
	$options['seller-company_req']		= isset( $options['seller-company_req'] ) ? $options['seller-company_req'] : '';
	$options['seller-phone_req']		= isset( $options['seller-phone_req'] ) ? $options['seller-phone_req'] : '';
	$options['seller-country_req']		= isset( $options['seller-country_req'] ) ? $options['seller-country_req'] : '';
	$options['seller-state_req']		= isset( $options['seller-state_req'] ) ? $options['seller-state_req'] : '';
	$options['seller-town_req']			= isset( $options['seller-town_req'] ) ? $options['seller-town_req'] : '';
	
	$options['car-version_show']		= isset( $options['car-version_show'] ) ? $options['car-version_show'] : '';
	$options['car-year_show']			= isset( $options['car-year_show'] ) ? $options['car-year_show'] : '';
	$options['car-mileage_show']		= isset( $options['car-mileage_show'] ) ? $options['car-mileage_show'] : '';
	$options['car-fuel_show']			= isset( $options['car-fuel_show'] ) ? $options['car-fuel_show'] : '';
	$options['car-engine_show']			= isset( $options['car-engine_show'] ) ? $options['car-engine_show'] : '';
	$options['car-horsepower_show']		= isset( $options['car-horsepower_show'] ) ? $options['car-horsepower_show'] : '';
	$options['car-transmission_show']	= isset( $options['car-transmission_show'] ) ? $options['car-transmission_show'] : '';
	$options['car-drive_show']			= isset( $options['car-drive_show'] ) ? $options['car-drive_show'] : '';
	$options['car-doors_show']			= isset( $options['car-doors_show'] ) ? $options['car-doors_show'] : '';
	$options['car-seats_show']			= isset( $options['car-seats_show'] ) ? $options['car-seats_show'] : '';
	$options['car-color_show']			= isset( $options['car-color_show'] ) ? $options['car-color_show'] : '';
	$options['car-condition_show']		= isset( $options['car-condition_show'] ) ? $options['car-condition_show'] : '';
	$options['car-vin_show']			= isset( $options['car-vin_show'] ) ? $options['car-vin_show'] : '';
	$options['car-price_show']			= isset( $options['car-price_show'] ) ? $options['car-price_show'] : '';
	$options['car-price-type_show']		= isset( $options['car-price-type_show'] ) ? $options['car-price-type_show'] : '';
	$options['car-warranty_show']		= isset( $options['car-warranty_show'] ) ? $options['car-warranty_show'] : '';
	$options['car-currency_show']		= isset( $options['car-currency_show'] ) ? $options['car-currency_show'] : '';
	$options['first-name_show']			= isset( $options['first-name_show'] ) ? $options['first-name_show'] : '';
	$options['last-name_show']			= isset( $options['last-name_show'] ) ? $options['last-name_show'] : '';
	$options['seller-company_show']		= isset( $options['seller-company_show'] ) ? $options['seller-company_show'] : '';
	$options['seller-phone_show']		= isset( $options['seller-phone_show'] ) ? $options['seller-phone_show'] : '';
	$options['seller-country_show']		= isset( $options['seller-country_show'] ) ? $options['seller-country_show'] : '';
	$options['seller-state_show']		= isset( $options['seller-state_show'] ) ? $options['seller-state_show'] : '';
	$options['seller-town_show']		= isset( $options['seller-town_show'] ) ? $options['seller-town_show'] : '';
	
	// If fired save button
	if( isset( $_POST['save'] ) ) {
		foreach( $_POST as $key => $value ) {
			if( $key !== 'save' ) {
				$args[$key] = $value;
			}
		}
		$Settings->update( 'WP_OPTIONS', '_jp_cars_validation', serialize( $args ) );
	}
?>

<!-- Validation Settings -->
<div class="jpro-panel validation">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'Validation Settings', 'jprocars' ); ?></span>
	</div>
	<div class="jpro-panel-body">
		<form method="post" class="jpro-form-horizontal" role="form">
		
			<table class="jpro-table">
				<thead>
					<tr class="jpro-primary">
						<th><?php _e( 'Input Field', 'jprocars' ); ?> <i class="fa fa-question-circle" title="Car Fields" data-toggle="tooltip" data-placement="top"></i></th>
						<th><?php _e( 'Required', 'jprocars' ); ?> <i class="fa fa-question-circle" title="Check this if you want this field to be marked as required when users adding new cars." data-toggle="tooltip" data-placement="top"></i></th>
						<th><?php _e( 'Show', 'jprocars' ); ?> <i class="fa fa-question-circle" title="Check this if you want to show this field to be visible on cars page & add / edit car frontpage." data-toggle="tooltip" data-placement="top"></i></th>
					</tr>
				</thead>
				<tbody>
					<form method="post">
					<tr>
						<td><?php _e( 'Car Make', 'jprocars' ); ?></td>
						<td><input name="car-make_req" type="checkbox" checked disabled></td>
						<td><input name="car-make_show" type="checkbox" checked disabled></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Model', 'jprocars' ); ?></td>
						<td><input name="car-model_req" type="checkbox" checked disabled></td>
						<td><input name="car-model_show" type="checkbox" checked disabled></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Version', 'jprocars' ); ?></td>
						<td><input name="car-version_req" type="checkbox" <?php checked( 'on', $options['car-version_req'], true ); ?>></td>
						<td><input name="car-version_show" type="checkbox" <?php checked( 'on', $options['car-version_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Year', 'jprocars' ); ?></td>
						<td><input name="car-year_req" type="checkbox" <?php checked( 'on', $options['car-year_req'], true ); ?>></td>
						<td><input name="car-year_show" type="checkbox" <?php checked( 'on', $options['car-year_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Mileage', 'jprocars' ); ?></td>
						<td><input name="car-mileage_req" type="checkbox" <?php checked( 'on', $options['car-mileage_req'], true ); ?>></td>
						<td><input name="car-mileage_show" type="checkbox" <?php checked( 'on', $options['car-mileage_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Fuel', 'jprocars' ); ?></td>
						<td><input name="car-fuel_req" type="checkbox" <?php checked( 'on', $options['car-fuel_req'], true ); ?>></td>
						<td><input name="car-fuel_show" type="checkbox" <?php checked( 'on', $options['car-fuel_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Engine', 'jprocars' ); ?></td>
						<td><input name="car-engine_req" type="checkbox" <?php checked( 'on', $options['car-engine_req'], true ); ?>></td>
						<td><input name="car-engine_show" type="checkbox" <?php checked( 'on', $options['car-engine_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Horsepower', 'jprocars' ); ?></td>
						<td><input name="car-horsepower_req" type="checkbox" <?php checked( 'on', $options['car-horsepower_req'], true ); ?>></td>
						<td><input name="car-horsepower_show" type="checkbox" <?php checked( 'on', $options['car-horsepower_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Transmission', 'jprocars' ); ?></td>
						<td><input name="car-transmission_req" type="checkbox" <?php checked( 'on', $options['car-transmission_req'], true ); ?>></td>
						<td><input name="car-transmission_show" type="checkbox" <?php checked( 'on', $options['car-transmission_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Drive', 'jprocars' ); ?></td>
						<td><input name="car-drive_req" type="checkbox" <?php checked( 'on', $options['car-drive_req'], true ); ?>></td>
						<td><input name="car-drive_show" type="checkbox" <?php checked( 'on', $options['car-drive_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Doors', 'jprocars' ); ?></td>
						<td><input name="car-doors_req" type="checkbox" <?php checked( 'on', $options['car-doors_req'], true ); ?>></td>
						<td><input name="car-doors_show" type="checkbox" <?php checked( 'on', $options['car-doors_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Seats', 'jprocars' ); ?></td>
						<td><input name="car-seats_req" type="checkbox" <?php checked( 'on', $options['car-seats_req'], true ); ?>></td>
						<td><input name="car-seats_show" type="checkbox" <?php checked( 'on', $options['car-seats_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Color', 'jprocars' ); ?></td>
						<td><input name="car-color_req" type="checkbox" <?php checked( 'on', $options['car-color_req'], true ); ?>></td>
						<td><input name="car-color_show" type="checkbox" <?php checked( 'on', $options['car-color_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Condition', 'jprocars' ); ?></td>
						<td><input name="car-condition_req" type="checkbox" <?php checked( 'on', $options['car-condition_req'], true ); ?>></td>
						<td><input name="car-condition_show" type="checkbox" <?php checked( 'on', $options['car-condition_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car VIN', 'jprocars' ); ?></td>
						<td><input name="car-vin_req" type="checkbox" <?php checked( 'on', $options['car-vin_req'], true ); ?>></td>
						<td><input name="car-vin_show" type="checkbox" <?php checked( 'on', $options['car-vin_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Price', 'jprocars' ); ?></td>
						<td><input name="car-price_req" type="checkbox" <?php checked( 'on', $options['car-price_req'], true ); ?>></td>
						<td><input name="car-price_show" type="checkbox" <?php checked( 'on', $options['car-price_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Price Type', 'jprocars' ); ?></td>
						<td><input name="car-price-type_req" type="checkbox" <?php checked( 'on', $options['car-price-type_req'], true ); ?>></td>
						<td><input name="car-price-type_show" type="checkbox" <?php checked( 'on', $options['car-price-type_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Warranty', 'jprocars' ); ?></td>
						<td><input name="car-warranty_req" type="checkbox" <?php checked( 'on', $options['car-warranty_req'], true ); ?>></td>
						<td><input name="car-warranty_show" type="checkbox" <?php checked( 'on', $options['car-warranty_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Car Currency', 'jprocars' ); ?></td>
						<td><input name="car-currency_req" type="checkbox" <?php checked( 'on', $options['car-currency_req'], true ); ?>></td>
						<td><input name="car-currency_show" type="checkbox" <?php checked( 'on', $options['car-currency_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Seller First Name', 'jprocars' ); ?></td>
						<td><input name="first-name_req" type="checkbox" <?php checked( 'on', $options['first-name_req'], true ); ?>></td>
						<td><input name="first-name_show" type="checkbox" <?php checked( 'on', $options['first-name_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Seller Last Name', 'jprocars' ); ?></td>
						<td><input name="last-name_req" type="checkbox" <?php checked( 'on', $options['last-name_req'], true ); ?>></td>
						<td><input name="last-name_show" type="checkbox" <?php checked( 'on', $options['last-name_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Seller Company', 'jprocars' ); ?></td>
						<td><input name="seller-company_req" type="checkbox" <?php checked( 'on', $options['seller-company_req'], true ); ?>></td>
						<td><input name="seller-company_show" type="checkbox" <?php checked( 'on', $options['seller-company_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Seller Email', 'jprocars' ); ?></td>
						<td><input name="none" type="checkbox" checked disabled></td>
						<td><input name="none" type="checkbox" checked disabled></td>
					</tr>
					<tr>
						<td><?php _e( 'Seller Phone', 'jprocars' ); ?></td>
						<td><input name="seller-phone_req" type="checkbox" <?php checked( 'on', $options['seller-phone_req'], true ); ?>></td>
						<td><input name="seller-phone_show" type="checkbox" <?php checked( 'on', $options['seller-phone_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Seller Country', 'jprocars' ); ?></td>
						<td><input name="seller-country_req" type="checkbox" <?php checked( 'on', $options['seller-country_req'], true ); ?>></td>
						<td><input name="seller-country_show" type="checkbox" <?php checked( 'on', $options['seller-country_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Seller State', 'jprocars' ); ?></td>
						<td><input name="seller-state_req" type="checkbox" <?php checked( 'on', $options['seller-state_req'], true ); ?>></td>
						<td><input name="seller-state_show" type="checkbox" <?php checked( 'on', $options['seller-state_show'], true ); ?>></td>
					</tr>
					<tr>
						<td><?php _e( 'Seller Town', 'jprocars' ); ?></td>
						<td><input name="seller-town_req" type="checkbox" <?php checked( 'on', $options['seller-town_req'], true ); ?>></td>
						<td><input name="seller-town_show" type="checkbox" <?php checked( 'on', $options['seller-town_show'], true ); ?>></td>
					</tr>
					<tr>
						<td colspan="3"><input name="save" type="submit" class="add-new-h2" value="<?php _e( 'Save', 'jprocars' ); ?>"></td>
					</tr>
					</form>
				</tbody>
			</table>
		
		</form>
	</div>
</div><!-- / Validation Settings -->

<script>
jQuery( document ).ready(function($) {
	$('[data-toggle="tooltip"]').tooltip();
});
</script>