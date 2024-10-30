<?php
if( ! defined( 'ABSPATH' ) ) exit;
/*---------------------------------------------------------------------
 * JPRO CARS - SINGLE CAR PAGE TEMPLATE | Rewritten @since v0.7
 *--------------------------------------------------------------------*/
if( ! class_exists( 'JPROCUSTOM_SINGLE_POST' ) ) {
	
	class JPROCUSTOM_SINGLE_POST {
		
		function __construct() {
			
			// Show single car page
			add_filter( 'the_content', array( $this, 'render' ) );
			
		}
		
		function render( $content ) {
			global $post;
			
			if( $post->post_type == 'car-classifieds' && is_singular( 'car-classifieds' ) ) {
				
				ob_start();
				
				$content  = $this->output();
				$content  = ob_get_contents();
				
				ob_end_clean();
				
				return $content;
			} else {
				return $content;
			}
			
		}
		
		function output() { 
			global $post, $JP_Cars, $JP_Country; 
			
			$Settings = new JP_Settings();
			$validate = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_validation', true ); // Get validation settings
			$validate = jpro::validation( $validate ); ?>
			
			<!-- Single Car Classifieds -->
			<div id="car-classifieds-single" class="car-classifieds page clearfix">
				
				<!-- Left Side -->
				<div class="col-md-12">
					
						<?php
						$images = $JP_Cars->get_meta('_car_images');
							
						/**
						 * Lets check if single or multiple images in meta value
						 * If multiple images, choose only first one for thumbnail
						 */
						if( preg_match( "/,/", $images ) ) {
							$images = explode( ",", $images );
							$img	= $images;
							$image 	= $images[0];
						}else{
							$image = $images;
							$img = '';
						}
						
						$strip_title = strip_tags( get_the_title() );
						?>
						
						<!-- Main Image -->
						<div class="jprocars-main-image">
							<?php if( $JP_Cars->get_meta('_car_images') ): ?>
								<img src="<?php echo $image; ?>">
								
								<?php if( $validate['car-price_show'] && $JP_Cars->get_meta('_car_price') ): ?>
								<span class="jprocars-main-image-price">
									<?php echo $JP_Cars->get_price(); ?>
								</span>
								<?php endif; ?>
							<?php endif; ?>
							
							<div class="jpro-thumbs">
							<?php if( $img ): foreach( $img as $image ): ?>
								<a href="<?php echo $image; ?>" title="<?php echo $strip_title; ?>" class="col-lg-2 col-md-2 col-sm-2">
									<img src="<?php echo $image; ?>">
								</a>
							<?php endforeach; endif; ?>
							</div>
						</div><!-- / Main Image -->
						
						<h3><?php echo get_the_title(); ?></h3>
						
						<!-- Tabs Panel -->
						<div class="panel">
						
							<ul id="car-classifieds-tabs" class="nav nav-tabs nav-justified">
							
								<li class="active">
									<a href="jpro#car-details" data-toggle="tab"><?php _e( 'Car Details', 'jprocars' ); ?></a>
								</li>
								
								<li>
									<a href="jpro#car-equipment" data-toggle="tab"><?php _e( 'Car Equipment', 'jprocars' ); ?></a>
								</li>
								
								<li>
									<a href="jpro#car-description" data-toggle="tab"><?php _e( 'Car Description', 'jprocars' ); ?></a>
								</li>
								
								<li>
									<a href="jpro#contact-seller" data-toggle="tab"><?php _e( 'Contact Seller', 'jprocars' ); ?></a>
								</li>
							
							</ul>
							
							<div id="car-classifieds-tabs-content" class="tab-content">
							
								<!-- Car Details Tab -->
								<div id="car-details" class="tab-pane fade active in">
						
									<?php if( $JP_Cars->get_make() ): ?>
									<!-- Make -->
									<div class="cell">
										<div class="left"><?php _e( 'Make:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_make(); ?></div>
									</div><!-- / Make -->
									<?php endif; ?>
									
									<?php if( $JP_Cars->get_model() ): ?>
									<!-- Model -->
									<div class="cell">
										<div class="left"><?php _e( 'Model:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_model(); ?></div>
									</div><!-- / Model -->
									<?php endif; ?>

									<?php if( $validate['car-year_show'] && $JP_Cars->get_meta('_car_year') ): ?>
									<!-- Made Year -->
									<div class="cell">
										<div class="left"><?php _e( 'Made Year:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_car_year'); ?></div>
									</div><!-- / Made Year -->
									<?php endif; ?>
									
									<?php if( $validate['car-mileage_show'] && $JP_Cars->get_meta('_car_mileage') ): ?>
									<!-- Mileage -->
									<div class="cell">
										<div class="left"><?php _e( 'Mileage:', 'jprocars' ); ?></div>
										<div class="right"><?php echo number_format($JP_Cars->get_meta('_car_mileage')); ?></div>
									</div><!-- / Mileage -->
									<?php endif; ?>
									
									<?php if( $validate['car-vin_show'] && $JP_Cars->get_meta('_car_vin') ): ?>
									<!-- VIN -->
									<div class="cell">
										<div class="left"><?php _e( 'VIN:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_car_vin'); ?></div>
									</div><!-- / VIN -->
									<?php endif; ?>
									
									<?php if( $validate['car-version_show'] && $JP_Cars->get_meta('_car_version') ): ?>
									<!-- Version -->
									<div class="cell">
										<div class="left"><?php _e( 'Version:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_car_version'); ?></div>
									</div><!-- / Version -->
									<?php endif; ?>
									
									<?php if( $validate['car-fuel_show'] && $JP_Cars->get_meta('_car_fuel') ): ?>
									<!-- Fuel -->
									<div class="cell">
										<div class="left"><?php _e( 'Fuel:', 'jprocars' ); ?></div>
										<div class="right"><?php _e( $JP_Cars->get_meta('_car_fuel'), 'jprocars' ); ?></div>
									</div><!-- / Fuel -->
									<?php endif; ?>
									
									<?php if( $validate['car-engine_show'] && $JP_Cars->get_meta('_car_engine') ): ?>
									<!-- Engine -->
									<div class="cell">
										<div class="left"><?php _e( 'Engine (cm3):', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_car_engine'); ?></div>
									</div><!-- / Engine -->
									<?php endif; ?>
									
									<?php if( $validate['car-horsepower_show'] && $JP_Cars->get_meta('_car_horsepower') ): ?>
									<!-- Horsepower -->
									<div class="cell">
										<div class="left"><?php _e( 'Horsepower (hp):', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_car_horsepower'); ?></div>
									</div><!-- / Horsepower -->
									<?php endif; ?>
									
									<?php if( $validate['car-transmission_show'] && $JP_Cars->get_meta('_car_transmission') ) : ?>
									<!-- Transmission -->
									<div class="cell">
										<div class="left"><?php _e( 'Transmission:', 'jprocars' ); ?></div>
										<div class="right"><?php _e( $JP_Cars->get_meta('_car_transmission'), 'jprocars' ); ?></div>
									</div><!-- / Transmission -->
									<?php endif; ?>
									
									<?php if( $validate['car-doors_show'] && $JP_Cars->get_meta('_car_doors') ): ?>
									<!-- Doors -->
									<div class="cell">
										<div class="left"><?php _e( 'Doors:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_car_doors'); ?></div>
									</div><!-- / Doors -->
									<?php endif; ?>

									<?php if( $validate['car-condition_show'] && $JP_Cars->get_meta('_car_condition') ): ?>
									<!-- Condition -->
									<div class="cell">
										<div class="left"><?php _e( 'Condition:', 'jprocars' ); ?></div>
										<div class="right"><?php _e( $JP_Cars->get_meta('_car_condition'), 'jprocars' ); ?></div>
									</div><!-- / Condition -->
									<?php endif; ?>
									
									<?php if( $validate['car-drive_show'] && $JP_Cars->get_meta('_car_drive') ): ?>
									<!-- Drive -->
									<div class="cell">
										<div class="left"><?php _e( 'Drive:', 'jprocars' ); ?></div>
										<div class="right"><?php _e( $JP_Cars->get_meta('_car_drive').' drive', 'jprocars' ); ?></div>
									</div><!-- / Drive -->
									<?php endif; ?>
									
									<?php if( $validate['car-seats_show'] && $JP_Cars->get_meta('_car_seats') ): ?>
									<!-- Seats -->
									<div class="cell">
										<div class="left"><?php _e( 'Seats:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_car_seats'); ?></div>
									</div><!-- / Seats -->
									<?php endif; ?>
									
									<?php if( $validate['car-color_show'] && $JP_Cars->get_meta('_car_color') ): ?>
									<!-- Color -->
									<div class="cell">
										<div class="left"><?php _e( 'Color:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_car_color'); ?></div>
									</div><!-- / Color -->
									<?php endif; ?>
									
									<?php if( $validate['car-price_show'] && $JP_Cars->get_meta('_car_price') ): ?>
									<!-- Price -->
									<div class="cell">
										<div class="left"><?php _e( 'Price:', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_price(); ?></div>
									</div><!-- / Price -->
									<?php endif; ?>
									
									<?php if( $validate['car-price-type_show'] && $JP_Cars->get_meta('_car_price_type') ): ?>
									<!-- Price Type -->
									<div class="cell">
										<div class="left"><?php _e( 'Price Type:', 'jprocars' ); ?></div>
										<div class="right"><?php _e( $JP_Cars->get_meta('_car_price_type'), 'jprocars' ); ?></div>
									</div><!-- / Price Type -->
									<?php endif; ?>
									
									<?php if( $validate['car-warranty_show'] && $JP_Cars->get_meta('_car_warranty') ): ?>
									<!-- Warranty -->
									<div class="cell">
										<div class="left"><?php _e( 'Warranty:', 'jprocars' ); ?></div>
										<div class="right"><?php _e( $JP_Cars->get_meta('_car_warranty'), 'jprocars' ); ?></div>
									</div><!-- / Warranty -->
									<?php endif; ?>
									
								</div><!-- / Car Details Tab -->
								
								<!-- Car Equipment Tab -->
								<div id="car-equipment" class="tab-pane fade in">
									<?php $terms = wp_get_post_terms( $post->ID, 'car-equipment' ); ?>
									
									<?php foreach( $terms as $term ): ?>
									<div class="cell">
										<div class="left"><?php echo $term->name; ?></div>
										<div class="right">
											<input class="jpro-checkbox" type="checkbox" name="<?php echo $term->name; ?>" checked disabled>
											<label><span></span></label>
										</div>
									</div>
									<?php endforeach; ?>
								
								</div><!-- / Car Equipment Tab -->
								
								<!-- Car Description Tab -->
								<div id="car-description" class="tab-pane fade in">
									<div class="right">
										<?php echo str_replace("\r", "<br />", get_the_content('')); ?>
									</div>
								</div><!-- / Car Description Tab -->
								
								<!-- Contact Seller Tab -->
								<div id="contact-seller" class="tab-pane fade in">
									
									<?php if( $validate['first-name_show'] && $JP_Cars->get_meta('_seller_first_name') ): ?>
									<div class="cell">
										<div class="left"><?php _e( 'First Name', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_seller_first_name'); ?></div>
									</div>
									<?php endif; ?>
									
									<?php if( $validate['last-name_show'] && $JP_Cars->get_meta('_seller_last_name') ): ?>
									<div class="cell">
										<div class="left"><?php _e( 'Last Name', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_seller_last_name'); ?></div>
									</div>
									<?php endif; ?>
									
									<?php if( $JP_Cars->get_meta('_seller_email') ): ?>
									<div class="cell">
										<div class="left"><?php _e( 'E-mail', 'jprocars' ); ?></div>
										<div class="right">
											<a id="show-email"><?php _e( 'show email', 'jprocars' ); ?></a>
											<a id="hide-email"><?php echo $JP_Cars->get_meta('_seller_email'); ?></a>
										</div>
									</div>
									<?php endif; ?>
									
									<?php if( $validate['seller-phone_show'] && $JP_Cars->get_meta('_seller_phone') ): ?>
									<div class="cell">
										<div class="left"><?php _e( 'Phone', 'jprocars' ); ?></div>
										<div class="right">
											<a id="show-phone"><?php _e( 'show phone', 'jprocars' ); ?></a>
											<a id="hide-phone"><?php echo $JP_Cars->get_meta('_seller_phone'); ?></a>
										</div>
									</div>
									<?php endif; ?>
									
									<?php if( $validate['seller-company_show'] && $JP_Cars->get_meta('_seller_company') ): ?>
									<div class="cell">
										<div class="left"><?php _e( 'Company', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_seller_company'); ?></div>
									</div>
									<?php endif; ?>
									
									<?php if( $validate['seller-country_show'] && $JP_Cars->get_meta('_seller_country') ): ?>
									<div class="cell">
										<div class="left"><?php _e( 'Country', 'jprocars' ); ?></div>
										<div class="right"><?php $JP_Country->text_output( $JP_Cars->get_meta('_seller_country') ); ?></div>
									</div>
									<?php endif; ?>
									
									<?php if( $validate['seller-state_show'] && $JP_Cars->get_meta('_seller_state') ): ?>
									<div class="cell">
										<div class="left"><?php _e( 'State', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_seller_state'); ?></div>
									</div>
									<?php endif; ?>
									
									<?php if( $validate['seller-town_show'] && $JP_Cars->get_meta('_seller_town') ): ?>
									<div class="cell">
										<div class="left"><?php _e( 'Town', 'jprocars' ); ?></div>
										<div class="right"><?php echo $JP_Cars->get_meta('_seller_town'); ?></div>
									</div>
									<?php endif; ?>
									
									<script>
									jQuery('#show-email').click(function(){
										jQuery('#show-email').hide();
										jQuery('#hide-email').show();
									});
									jQuery('#hide-email').hide();
									
									jQuery('#show-phone').click(function(){
										jQuery('#show-phone').hide();
										jQuery('#hide-phone').show();
									});
									jQuery('#hide-phone').hide();
									</script>
								</div><!-- / Contact Seller Tab -->
							
							</div>
						
						</div><!-- / Tabs Panel -->
					
				</div>
				
				<script>
				jQuery(document).ready(function($) {
					$('.jpro-thumbs').magnificPopup({
						delegate: 'a',
						type: 'image',
						gallery: {enabled: true}
					});
				});
				</script>

			</div><!-- / Single Car Classifieds -->
			
		<?php }
		
	}
	new JPROCUSTOM_SINGLE_POST;
}