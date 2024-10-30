<?php 
if( ! defined( 'ABSPATH' ) ) exit;
/*---------------------------------------------------------------------
 * JPRO CARS - SHORTCODE | Rewritten @since v0.7
 *--------------------------------------------------------------------*/
if( ! class_exists( 'JPROCUSTOM_SHORTCODE_CARS' ) ) {
	
	class JPROCUSTOM_SHORTCODE_CARS {
		
		/**
		 * Class Constructor
		 *
		 * @rewritten
		 * @since 0.7
		 */
		function __construct() {
			
			add_shortcode( 'jpro_cars', array( $this, 'shortcode' ) );
			
		}
		
		/**
		 * Shortcode Initialization
		 *
		 * @rewritten
		 * @since 0.7
		 */
		function shortcode( $atts, $content ) {

			ob_start();
			
			$html  = do_shortcode( $content );
			$html .= self::render( $atts );
			$html  = ob_get_contents();
			
			ob_end_clean();
			
			return $html;
		}
		
		/**
		 * Shortcode Render
		 *
		 * @rewritten
		 * @since 0.7
		 */
		static private function render( $Query = false ) {
			global $post, $JP_Cars;
			
			$Settings = new JP_Settings(); 
			$settings = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
			
			$validate = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_validation', true ); // Get validation settings
			$validate = jpro::validation( $validate ); // Fix undefined index notice
			
			$loop = new WP_Query( $JP_Cars->Query_Args( $Query ) );

			// If delete car fired
			if( isset( $_GET['jp'] ) && $_GET['jp'] == 'delete-classified' && ! empty( $_GET['id'] ) ) {
				if( wp_delete_post( $_GET['id'] ) ) {
					echo
					'
					<div class="alert alert-success">
						<strong>'.__( 'Car deleted successfuly!', 'jprocars' ).'</strong>
					</div>
					';
				}
			}
			
			// Output message with number how many cars found
			if( isset( $_POST['search-cars'] ) ) {
				echo
				'
				<div class="alert alert-success">
					<strong>'.__( 'Found Cars', 'jprocars' ).':</strong> '. $loop->found_posts .'
				</div>
				';
			} ?>

			<div class="car-classifieds loop clearfix">
				
				<main class="col-md-12" role="main">
					
					<?php
					###################################################################################
					# IF USER IS LOGGED IN AND WANTS ADD NEW CLASSIFIED, LOAD add_classified.php
					###################################################################################
					if( 
						isset( $_POST['add-new-classified'] ) && $settings['mode'] == 'classifieds' 
						or 
						isset( $_GET['jp'] ) && $_GET['jp'] == 'add-new-classified' && $settings['mode'] == 'classifieds' 
					  ): 

						require_once( JPRO_TEMPLATES_DIR . 'parts/add_classified.php' );
						
					###################################################################################
					# ELSE IF USER WANTS TO EDIT OWN CLASSIFIED LOAD edit_classified.php 
					###################################################################################
					elseif( is_user_logged_in() && isset( $_GET['jp'] ) && $_GET['jp'] == 'edit-classified' && $settings['mode'] == 'classifieds' ):
					
						require_once ( JPRO_TEMPLATES_DIR . 'parts/edit_classified.php' );
					
					###################################################################################
					# ELSE IF USER WANTS TO SEE OWN MEMBERSHIP PAGE LOAD my_membership.php 
					###################################################################################
					elseif( is_user_logged_in() && isset( $_GET['jp'] ) && $_GET['jp'] == 'my-membership' && $settings['mode'] == 'classifieds' ):
					
						require_once( JPRO_TEMPLATES_DIR . 'parts/my_membership.php' );
					
					###################################################################################
					# ELSE IF USER WANTS TO SEE OWN TRANSACTIONS PAGE LOAD my_transactions.php 
					###################################################################################
					elseif( is_user_logged_in() && isset( $_GET['jp'] ) && $_GET['jp'] == 'my-transactions' && $settings['mode'] == 'classifieds' ):
					
						require_once( JPRO_TEMPLATES_DIR . 'parts/my_transactions.php' );
					
					###################################################################################
					# ELSE IF USER WANTS TO CHANGE MEMBERSHIP PLAN LOAD select_membership.php 
					###################################################################################
					elseif( is_user_logged_in() && isset( $_GET['jp'] ) && $_GET['jp'] == 'change-membership' && $settings['mode'] == 'classifieds' ):
					
						require_once( JPRO_TEMPLATES_DIR . 'parts/select_membership.php' );
					
					###################################################################################
					# ELSE SHOW CAR CLASSIFIEDS LOOP PAGE FOR ALL USERS
					###################################################################################
					else: ?>
					
					<!-- Cars Top Filter -->
					<div class="cars-top-filter clearfix">
							<div class="pull-left">
								<?php 
								
									// Order by date url
									$order_date = isset( $_GET['order-date'] ) ? esc_attr( $_GET['order-date'] ) : '';
									
									if( isset( $order_date ) && $order_date == 'asc' ) {
										$date = 'desc';
									}elseif( isset( $order_date ) && $order_date == 'desc' ) {
										$date = 'asc';
									}else{
										$date = 'asc';
									}
									
									// Order by price url
									$order_price = isset( $_GET['order-price'] ) ? esc_attr( $_GET['order-price'] ) : '';
									
									if( isset( $order_price ) && $order_price == 'asc' ) {
										$price = 'desc';
									}elseif( isset( $order_price ) && $order_price == 'desc' ) {
										$price = 'asc';
									}else{
										$price = 'asc';
									}
								?>
								
								<a href="<?php echo get_permalink(); ?>?order-date=<?php echo $date; ?>">
									<?php  _e( 'Date:', 'jprocars' ); ?> <i class="fa fa-sort-numeric-<?php echo $date; ?>"></i>
								</a>
								
							</div>
							<div class="pull-right">
								<a id="cars-list-grid-style" href="#">
									<i class="fa fa-2x"></i>
								</a>
							</div>
					</div><!-- / Cars Top Filter -->
					
					<div id="change-layout" class="cars-list">
						
						<?php if( $loop->have_posts() ): ?>
						
							<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
							
								<article id="post-<?php the_ID(); ?>">
									
									
									<?php if( $post->post_author == get_current_user_id() && isset( $_GET['jp'] ) && $_GET['jp'] == 'my-classifieds' ): // Works only when jp=my-classifieds fired ?>
										<div class="jpro-action-links col-md-12">
											<ul>
												<li><?php printf( '<a href="%s">%s</a>', esc_url( add_query_arg( array('jp' => 'edit-classified', 'id' => get_the_ID()), get_permalink( $settings['cc_page_id'] ) ) ), __( 'Edit Car', 'jprocars' ) ); ?></li>
												<li><?php printf( '<a href="%s">%s</a>', esc_url( add_query_arg( array('jp' => 'delete-classified', 'id' => get_the_ID()), get_permalink( $settings['cc_page_id'] ) ) ), __( 'Delete Car', 'jprocars' ) ); ?></li>
											</ul>
										</div>
									<?php endif; ?>
									
									<!-- Article Inner Wrapper -->
									<div class="article-inner-wrapper clearfix">
										
										<?php
											$images = $JP_Cars->get_meta('_car_images');
											
											/**
											 * Lets check if single or multiple images in meta value
											 * If multiple images, choose only first one for thumbnail
											 */
											if( preg_match( "/,/", $images ) ) {
												$images = explode( ",", $images );
												$image = $images[0];
											}else{
												$image = $images;
											}
											
											// Add SSL support
											if( is_ssl() ) {
												$image = str_replace( 'http://', 'https://', $image );
											}
											
											$strip_title = strip_tags( get_the_title() );
										?>
										
										<!-- Car Thumbnail -->
										<div class="car-thumbnail col-lg-4 col-md-4 col-sm-4">
											<?php if( $JP_Cars->get_meta('_car_images') ): ?>
												<a href="<?php the_permalink(); ?>" title="<?php echo $strip_title; ?>">
													<img src="<?php echo $image; ?>" alt="<?php echo $strip_title; ?>">
													
													<span class="price"><?php echo $JP_Cars->get_price(); ?></span>
													
												</a>
											<?php else: ?>
												<img class="no-image" src="<?php echo JPRO_CAR_URI .'assets/img/no_image.jpg'; ?>" alt="no-image">
											<?php endif; ?>
											
										</div><!-- / Car Thumbnail -->

										<!-- Car Details -->
										<div class="car-info col-lg-8 col-md-8 col-sm-8">
											
											<div class="title-price-container">
												<h6><a href="<?php the_permalink(); ?>" title="<?php echo $strip_title; ?>"><?php the_title(); ?></a></h6>
												
												<?php if( $validate['car-price_show'] ): ?>
												<span class="price">
													<?php echo $JP_Cars->get_price(); ?>
												</span>
												<?php endif; ?>
												
											</div>
											
											<ul>
												
												<?php if( $validate['car-fuel_show'] ): ?>
												<li>
												  <b><?php _e( 'Fuel:', 'jprocars' ); ?></b> 
												  
												  <?php if( $JP_Cars->get_meta('_car_fuel') ): ?>
													<span><?php _e( $JP_Cars->get_meta('_car_fuel'), 'jprocars' ); ?></span>
												  <?php endif; ?>
												</li>
												<?php endif; ?>
												
												<?php if( $validate['car-mileage_show'] ): ?>
												<li><!-- Mileage -->
												  <b><?php _e( 'Mileage:', 'jprocars' ); ?></b>
												  
												  <?php if( $JP_Cars->get_meta('_car_mileage') ): ?>
													<span><?php echo number_format($JP_Cars->get_meta('_car_mileage')); ?></span>
												  <?php endif; ?>
												</li>
												<?php endif; ?>
												
												<?php if( $validate['car-year_show'] ): ?>
												<li>
												  <b><?php _e( 'Year:', 'jprocars' ); ?></b> 
												  
												  <?php if( $JP_Cars->get_meta('_car_year') ): ?>
													<span><?php echo $JP_Cars->get_meta('_car_year'); ?></span>
												  <?php endif; ?>
												</li>
												<?php endif; ?>
												
												<?php if( $validate['seller-country_show'] ): ?>
												<li>
												  <b><?php _e( 'Location:', 'jprocars' ); ?></b>
												  <?php $country = new JP_Country(); ?>
												  
												  <?php if( $JP_Cars->get_meta('_seller_country') ): ?>
													<span><?php $country->text_output( $JP_Cars->get_meta('_seller_country') ); ?></span>
												  <?php endif; ?>
												</li>
												<?php endif; ?>
												
											</ul>
										</div><!-- / Car Details -->
									</div><!-- / Article Inner Wrapper -->
									
									<div class="aditional-info clearfix">
										<ul>
										<?php if( get_the_date() ): ?>
											<li><span><?php echo get_the_date(); ?></span></li>
										<?php endif; ?>
										
										<?php if( $validate['car-condition_show'] && $JP_Cars->get_meta('_car_condition') ): ?>
											
											<?php if( $JP_Cars->get_meta('_car_condition') == 'used' ): ?>
												<li><span><?php _e( 'Used', 'jprocars' ); ?></span></li>
											<?php else: ?>
												<li><span><?php _e( 'New', 'jprocars' ); ?></span></li>
											<?php endif; ?>
											
										<?php endif; ?>
										
										<?php if( $validate['car-drive_show'] && $JP_Cars->get_meta('_car_drive') ): ?>
										
											<?php if( $JP_Cars->get_meta('_car_drive') == 'left' ): ?>
												<li><span><?php _e( 'Left drive', 'jprocars' ); ?></span></li>
											<?php else: ?>
												<li><span><?php _e( 'Right drive', 'jprocars' ); ?></span></li>
											<?php endif; ?>
										
										<?php endif; ?>
										
										<?php if( $validate['car-engine_show'] && $JP_Cars->get_meta('_car_engine') ): ?>
										<li><span><?php echo $JP_Cars->get_meta('_car_engine'); ?> <?php _e( 'cm3', 'jprocars' ); ?></span></li>
										<?php endif; ?>
										
										<?php if( $validate['car-horsepower_show'] && $JP_Cars->get_meta('_car_horsepower') ): ?>
											<li><span><?php echo $JP_Cars->get_meta('_car_horsepower').' '.__( 'hp', 'jprocars' ); ?></span></li>
										<?php endif; ?>
										
										<?php if( $validate['car-doors_show'] && $JP_Cars->get_meta('_car_doors') ): ?>
											<li><span><?php echo $JP_Cars->get_meta('_car_doors').' '.__( 'doors', 'jprocars' ); ?></span></li>
										<?php endif; ?>
										
										</ul>
									</div>
										
								</article>
							<?php endwhile; ?>
							
						<?php else: ?>
						
							<p><?php echo __( 'Sorry but there are no cars for display at the moment !', 'jprocars' ); ?></p>
						
						<?php endif; ?>
						
					</div>
					
					<?php if( ! isset( $_POST['add-new-classified'] ) ) { ?>
					<!-- Pagination -->
					<div id="jpro-pagination" class="clearfix">
						<?php
						/**
						* Fix Paged on Static Homepage
						* ============================
						* @since 0.4
						*/
						$big = 999999999; // need an unlikely integer
						if( get_query_var('paged') ) { $paged = get_query_var('paged'); }
						elseif( get_query_var('page') ) { $paged = get_query_var('page'); }
						else { $paged = 1; }

						echo paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, $paged ),
							'total' => $loop->max_num_pages
						) );
						?>
					</div><!-- / Pagination -->
					<?php } ?>
					
					<?php endif; ?>
					
				</main>
			</div>
		<?php
		}
	}
	new JPROCUSTOM_SHORTCODE_CARS;
}