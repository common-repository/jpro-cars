<?php

// No direct access allowed.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Car Classifieds Meta Boxes
 *
 * $id
 * $title
 * $callback
 * $post_type
 * $context
 * $priority
 * $callback_args 
 *
 * @since 0.1
 */
function add_car_classifieds_meta_boxes() {
	add_meta_box( 
        'jpro_car_details', 
        esc_html__( 'Car Details', 'jpro-cars' ), 
        'jpro_car_details', 
        'car-classifieds', 
        'normal', 
        'high' 
    );
	add_meta_box( 
        'jpro_car_photos', 
        esc_html__( 'Car Photos', 'jpro-cars' ), 
        'jpro_car_photos', 
        'car-classifieds', 
        'side', 
        'high' 
    );
}

/**
 * Get Meta Value
 *
 * @since 0.1
 */
function jpro_get_meta( $key ) {
	return sanitize_text_field( 
        get_post_meta( get_the_ID(), $key, true ) 
    );
}

/**
 * Car Details MetaBox
 *
 * @since 0.1
 */
function jpro_car_details() {
	global $post;
    
	$currencies = unserialize( get_option( '_jp_cars_currencies' ) ); ?>
	
<input name="car-classifieds-meta" type="hidden" value="save">

<div class="jpro-panel">
	<div class="jpro-panel-body">
		<div class="jpro-form-horizontal">
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car version', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="car-version" type="text" placeholder="eg: 1.6 hdi" value="<?php echo jpro_get_meta( '_car_version' ); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car made year', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="car-year" class="jpro-form-control">
						<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
						<?php JP_Cars::dropdown_years( jpro_get_meta('_car_year') ); ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car transmission', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="car-transmission" class="jpro-form-control">
						<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
						<option value="automatic" <?php if(jpro_get_meta('_car_transmission')=='automatic') echo 'selected'; ?>><?php _e( 'Automatic', 'jprocars' ); ?></option>
						<option value="manual" <?php if(jpro_get_meta('_car_transmission')=='manual') echo 'selected'; ?>><?php _e( 'Manual', 'jprocars' ); ?></option>
						<option value="semi-automatic" <?php if(jpro_get_meta('_car_transmission')=='semi-automatic') echo 'selected'; ?>><?php _e( 'Semi-Automatic', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car doors', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="car-doors" class="jpro-form-control">
						<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
						<?php JP_Cars::dropdown_years( jpro_get_meta('_car_doors'), $from = 2, $to = 5 ); ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car fuel', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="car-fuel" class="jpro-form-control">
						<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
						<option value="diesel" <?php if(jpro_get_meta('_car_fuel')=='diesel') echo 'selected'; ?>><?php _e( 'Diesel', 'jprocars' ); ?></option>
						<option value="electric" <?php if(jpro_get_meta('_car_fuel')=='electric') echo 'selected'; ?>><?php _e( 'Electric', 'jprocars' ); ?></option>
						<option value="gasoline" <?php selected( 'gasoline', jpro_get_meta('_car_fuel'), true ); ?>><?php _e( 'Gasoline', 'jprocars' ); ?></option>
						<option value="hybrid" <?php if(jpro_get_meta('_car_fuel')=='hybrid') echo 'selected'; ?>><?php _e( 'Hybrid', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car condition', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="car-condition" class="jpro-form-control">
						<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
						<option value="new" <?php if(jpro_get_meta('_car_condition')=='new') echo 'selected'; ?>><?php _e( 'New', 'jprocars' ); ?></option>
						<option value="used" <?php if(jpro_get_meta('_car_condition')=='used') echo 'selected'; ?>><?php _e( 'Used', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car drive', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="car-drive" class="jpro-form-control">
						<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
						<option value="left" <?php if(jpro_get_meta('_car_drive')=='left') echo 'selected'; ?>><?php _e( 'Left', 'jprocars' ); ?></option>
						<option value="right" <?php if(jpro_get_meta('_car_drive')=='right') echo 'selected'; ?>><?php _e( 'Right', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car color', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="car-color" type="text" placeholder="eg: yellow" value="<?php echo jpro_get_meta('_car_color'); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car price', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="car-price" type="text" placeholder="eg: 10000" value="<?php echo jpro_get_meta('_car_price'); ?>" class="jpro-form-control">
					<span class="errprice"></span>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Price Type', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="car-price-type" class="jpro-form-control">
						<option value="fixed" <?php selected( 'fixed', jpro_get_meta('_car_price_type'), true ); ?>><?php _e( 'Fixed', 'jprocars' ); ?></option>
						<option value="negotiable" <?php selected( 'negotiable', jpro_get_meta('_car_price_type'), true ); ?>><?php _e( 'Negotiable', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Warranty', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="car-warranty" class="jpro-form-control">
						<option value="no" <?php selected( 'no', jpro_get_meta('_car_warranty'), true ); ?>><?php _e( 'No', 'jprocars' ); ?></option>
						<option value="yes" <?php selected( 'yes', jpro_get_meta('_car_warranty'), true ); ?>><?php _e( 'Yes', 'jprocars' ); ?></option>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Currency', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="currency" class="jpro-form-control">
					<?php if( $currencies ): foreach( $currencies as $currency ): ?>
					
						<option value="<?php echo $currency['iso']; ?>" <?php selected( jpro_get_meta('_car_currency'), $currency['iso'], true ); ?>><?php echo $currency['iso']; ?></option>
					
					<?php endforeach; else: ?>
						
						<option value="EUR" <?php selected( jpro_get_meta('_car_currency'), 'EUR', true ); ?>><?php echo 'EUR'; ?></option>
						<option value="USD" <?php selected( jpro_get_meta('_car_currency'), 'USD', true ); ?>><?php echo 'USD'; ?></option>
					
					<?php endif; ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car mileage', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="car-mileage" type="text" placeholder="eg: 100000" value="<?php echo jpro_get_meta('_car_mileage'); ?>" class="jpro-form-control">
					<span class="errmileage"></span>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car VIN', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="car-vin" type="text" placeholder="eg: 1VXBR12EXCP901213" value="<?php echo jpro_get_meta('_car_vin'); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car engine, cm3', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="car-engine" type="text" placeholder="eg: 1900" value="<?php echo jpro_get_meta('_car_engine'); ?>" class="jpro-form-control">
					<span class="errengine"></span>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car horsepower, hp', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="car-horsepower" type="text" placeholder="eg: 200" value="<?php echo jpro_get_meta('_car_horsepower'); ?>" class="jpro-form-control">
					<span class="errhorsepower"></span>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Car seats', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="car-seats" type="text" placeholder="eg: 5" value="<?php echo jpro_get_meta('_car_seats'); ?>" class="jpro-form-control">
					<span class="errseats"></span>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Seller first name', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="seller-first-name" type="text" placeholder="eg: John" value="<?php echo jpro_get_meta('_seller_first_name'); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Seller last name', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="seller-last-name" type="text" placeholder="eg: Doe" value="<?php echo jpro_get_meta('_seller_last_name'); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Seller email', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="seller-email" type="text" placeholder="eg: johndoe@gmail.com" value="<?php echo jpro_get_meta('_seller_email'); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Seller phone', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="seller-phone" type="text" placeholder="eg: +38160656545" value="<?php echo jpro_get_meta('_seller_phone'); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Seller company', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="seller-company" type="text" placeholder="eg: General Motors" value="<?php echo jpro_get_meta('_seller_company'); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Seller country', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<select name="seller-country" class="jpro-form-control">
						<option value=""><?php _e( '-- Please Select --', 'jprocars' ); ?></option>
						<?php $country = new JP_Country(); $country->option_output( jpro_get_meta('_seller_country') ); ?>
					</select>
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Seller state', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="seller-state" type="text" placeholder="eg: Texas" value="<?php echo jpro_get_meta('_seller_state'); ?>" class="jpro-form-control">
				</div>
			</div>
			
			<div class="jpro-form-group">
				<label class="col-lg-2 jpro-control-label">
					<?php _e( 'Seller town', 'jprocars' ); ?>
				</label>
				<div class="col-lg-9">
					<input name="seller-town" type="text" placeholder="eg: Atlanta" value="<?php echo jpro_get_meta('_seller_town'); ?>" class="jpro-form-control">
				</div>
			</div>
		</div>
	</div>
</div>
<?php } // End car details
/**
 * Car Photos MetaBox
 *
 * @since 0.1
 * @return upload box
 */
 function jpro_car_photos() {

	// adjust values here
	$id = "car-images"; // this will be the name of form field. Image url(s) will be submitted in $_POST using this key. So if $id == “img1” then $_POST[“img1”] will have all the image urls
	 
	$svalue = ""; // this will be initial value of the above form field. Image urls.
	 
	$multiple = true; // allow multiple files upload
	 
	$width = 800; // If you want to automatically resize all uploaded images then provide width here (in pixels)
	 
	$height = 600; // If you want to automatically resize all uploaded images then provide height here (in pixels) ?>
 
	<label><?php _e( 'Upload Car Images', 'jprocars' ); ?></label>  
	<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $svalue; echo jpro_get_meta('_car_images'); ?>" />  
	<div class="plupload-upload-uic hide-if-no-js <?php if ($multiple): ?>plupload-upload-uic-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-upload-ui">  
		<input id="<?php echo $id; ?>plupload-browse-button" type="button" value="<?php esc_attr_e('Select Files'); ?>" class="button" />
		<span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce($id . 'pluploadan'); ?>"></span>
		
		<?php if( $width && $height ): ?>
				<span class="plupload-resize"></span><span class="plupload-width" id="plupload-width<?php echo $width; ?>"></span>
				<span class="plupload-height" id="plupload-height<?php echo $height; ?>"></span>
		<?php endif; ?>
		
		<div class="filelist" style="width:100%; margin-top:15px;"></div>
	</div>  
	<div class="plupload-thumbs <?php if( $multiple ): ?>plupload-thumbs-multiple<?php endif; ?>" id="<?php echo $id; ?>plupload-thumbs">  
	</div>  
	<div class="clear"></div>
	<span><?php _e( 'Recommended image sizes: 800x600', 'jprocars' ); ?></span>
	
<?php } // End car photos
/**
 * Save Custom MetaBox fields
 *
 * @since 0.1
 * @return boolean
 */
function save_car_classifieds_meta_boxes( $post_id ) {
	
	// Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
	
	if( isset( $_POST['car-classifieds-meta'] ) && $_POST['car-classifieds-meta'] == 'save' ) {
		$options = array(
			'_car_version'			=> sanitize_text_field( $_POST['car-version'] ),
			'_car_year'				=> sanitize_text_field( $_POST['car-year'] ),
			'_car_transmission'		=> sanitize_text_field( $_POST['car-transmission'] ),
			'_car_doors'			=> sanitize_text_field( $_POST['car-doors'] ),
			'_car_fuel'				=> sanitize_text_field( $_POST['car-fuel'] ),
			'_car_condition'		=> sanitize_text_field( $_POST['car-condition'] ),
			'_car_drive'			=> sanitize_text_field( $_POST['car-drive'] ),
			'_car_color'			=> sanitize_text_field( $_POST['car-color'] ),
			'_car_price'			=> sanitize_text_field( $_POST['car-price'] ),
			'_car_price_type'		=> sanitize_text_field( $_POST['car-price-type'] ),
			'_car_warranty'			=> sanitize_text_field( $_POST['car-warranty'] ),
			'_car_currency'			=> sanitize_text_field( $_POST['currency'] ),
			'_car_mileage'			=> sanitize_text_field( $_POST['car-mileage'] ),
			'_car_vin'				=> sanitize_text_field( $_POST['car-vin'] ),
			'_car_engine'			=> sanitize_text_field( $_POST['car-engine'] ),
			'_car_horsepower'		=> sanitize_text_field( $_POST['car-horsepower'] ),
			'_car_seats'			=> sanitize_text_field( $_POST['car-seats'] ),
			'_car_images'			=> sanitize_text_field( $_POST['car-images'] ),
			'_seller_first_name'	=> sanitize_text_field( $_POST['seller-first-name'] ),
			'_seller_last_name'		=> sanitize_text_field( $_POST['seller-last-name'] ),
			'_seller_email'			=> sanitize_text_field( $_POST['seller-email'] ),
			'_seller_phone'			=> sanitize_text_field( $_POST['seller-phone'] ),
			'_seller_company'		=> sanitize_text_field( $_POST['seller-company'] ),
			'_seller_country'		=> sanitize_text_field( $_POST['seller-country'] ),
			'_seller_state'			=> sanitize_text_field( $_POST['seller-state'] ),
			'_seller_town'			=> sanitize_text_field( $_POST['seller-town'] )
		);
		
		foreach( $options as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'save_car_classifieds_meta_boxes' );

/**
 * Add Custom Menu URL Class
 * Used in "Appearance->Menus"
 *
 * @since 0.3
 */
class JP_Nav_Menu
{
	/**
	 * Class Constructor
	 *
	 * @since 0.3
	 */
	function __construct() {
        add_action( 'admin_head-nav-menus.php', array( $this, 'meta_boxes' ) );
    }
	
	/**
	 * Meta Boxes
	 *
	 * @since 0.3
	 */
	public function meta_boxes() {
		add_meta_box(
            'jpro_cars_links',
            __( 'JPro Cars Links', 'jprocars' ),
            array( $this, 'menu_links'),
            'nav-menus',
            'side',
            'low'
        );
	}
	
	/**
	 * Menu Links
	 *
	 * @since 0.3
	 */
	public function menu_links() { 
		$Settings = new JP_Settings();
		$settings = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
		
		if( empty( $settings['cc_page_id'] ) ) $settings['cc_page_id'] = 1;
	?>
		<div id="posttype-jprocars-link" class="posttypediv">
            <div id="tabs-panel-jprocars-link" class="tabs-panel tabs-panel-active">
                <ul id ="jprocars-link-checklist" class="categorychecklist form-no-clear">
                    <li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox" name="menu-item[-99999][menu-item-object-id]" value="-99999"> <?php _e( 'Add Classified', 'jprocars' ); ?>
                        </label>
                        <input type="hidden" class="menu-item-type" name="menu-item[-99999][menu-item-type]" value="custom">
                        <input type="hidden" class="menu-item-title" name="menu-item[-99999][menu-item-title]" value="<?php esc_attr_e( 'Add Classified', 'jprocars' ); ?>">
                        <input type="hidden" class="menu-item-url" name="menu-item[-99999][menu-item-url]" value="<?php echo esc_url(add_query_arg( 'jp', 'add-new-classified', get_permalink($settings['cc_page_id']) )); ?>">
                        <input type="hidden" class="menu-item-classes" name="menu-item[-99999][menu-item-classes]" value="jprocars-add-new-classified">
                    </li>
					<li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox" name="menu-item[-99998][menu-item-object-id]" value="-99998"> <?php _e( 'My Classifieds', 'jprocars' ); ?>
                        </label>
                        <input type="hidden" class="menu-item-type" name="menu-item[-99998][menu-item-type]" value="custom">
                        <input type="hidden" class="menu-item-title" name="menu-item[-99998][menu-item-title]" value="<?php esc_attr_e( 'My Classifieds', 'jprocars' ); ?>">
                        <input type="hidden" class="menu-item-url" name="menu-item[-99998][menu-item-url]" value="<?php echo esc_url(add_query_arg( 'jp', 'my-classifieds', get_permalink($settings['cc_page_id']) )); ?>">
                        <input type="hidden" class="menu-item-classes" name="menu-item[-99998][menu-item-classes]" value="jprocars-my-classifieds">
                    </li>
					<li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox" name="menu-item[-99997][menu-item-object-id]" value="-99997"> <?php _e( 'My Membership', 'jprocars' ); ?>
                        </label>
                        <input type="hidden" class="menu-item-type" name="menu-item[-99997][menu-item-type]" value="custom">
                        <input type="hidden" class="menu-item-title" name="menu-item[-99997][menu-item-title]" value="<?php esc_attr_e( 'My Membership', 'jprocars' ); ?>">
                        <input type="hidden" class="menu-item-url" name="menu-item[-99997][menu-item-url]" value="<?php echo esc_url(add_query_arg( 'jp', 'my-membership', get_permalink($settings['cc_page_id']) )); ?>">
                        <input type="hidden" class="menu-item-classes" name="menu-item[-99997][menu-item-classes]" value="jprocars-my-membership">
                    </li>
					<li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox" name="menu-item[-99996][menu-item-object-id]" value="-99996"> <?php _e( 'My Transactions', 'jprocars' ); ?>
                        </label>
                        <input type="hidden" class="menu-item-type" name="menu-item[-99996][menu-item-type]" value="custom">
                        <input type="hidden" class="menu-item-title" name="menu-item[-99996][menu-item-title]" value="<?php esc_attr_e( 'My Transactions', 'jprocars' ); ?>">
                        <input type="hidden" class="menu-item-url" name="menu-item[-99996][menu-item-url]" value="<?php echo esc_url(add_query_arg( 'jp', 'my-transactions', get_permalink($settings['cc_page_id']) )); ?>">
                        <input type="hidden" class="menu-item-classes" name="menu-item[-99996][menu-item-classes]" value="jprocars-my-transactions">
                    </li>
					<li>
                        <label class="menu-item-title">
                            <input type="checkbox" class="menu-item-checkbox" name="menu-item[-99995][menu-item-object-id]" value="-99995"> <?php _e( 'Change Membership', 'jprocars' ); ?>
                        </label>
                        <input type="hidden" class="menu-item-type" name="menu-item[-99995][menu-item-type]" value="custom">
                        <input type="hidden" class="menu-item-title" name="menu-item[-99995][menu-item-title]" value="<?php esc_attr_e( 'Change Membership', 'jprocars' ); ?>">
                        <input type="hidden" class="menu-item-url" name="menu-item[-99995][menu-item-url]" value="<?php echo esc_url(add_query_arg( 'jp', 'change-membership', get_permalink($settings['cc_page_id']) )); ?>">
                        <input type="hidden" class="menu-item-classes" name="menu-item[-99995][menu-item-classes]" value="jprocars-my-transactions">
                    </li>
                </ul>
            </div>
            <p class="button-controls">
                <span class="add-to-menu">
                    <input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-jprocars-link">
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
	<?php }
	
}
new JP_Nav_Menu;
?>