<?php
if( ! defined( 'ABSPATH' ) ) 
	exit; // Exit if accessed directly

$Settings	= new JP_Settings();
$membership = $Settings->getSettings( 'WP_USERMETA', '_jp_cars_membership', true );
?>

<!-- My Membership -->
<div class="jpro-panel">
	<div class="jpro-panel-heading">
		<span class="jpro-panel-title"><?php _e( 'My Membership', 'jprocars' ); ?></span> 
		- 
		<span style="font-weight:normal;"><?php printf( '<a href="%s">%s</a>', esc_url(add_query_arg( 'jp', 'change-membership', get_permalink($settings['cc_page_id']) )), __( 'change membership', 'jprocars' ) ); ?></span>
	</div>
	<div class="jpro-panel-body">
		<div class="jpro-form-horizontal">
			
			<?php if( $membership ) { // Check if user have a membership package ?>
			
			<!-- Membership Title -->
			<div class="jpro-form-group">
				<label class="col-lg-4 jpro-control-label"><?php _e( 'Membership', 'jprocars' ); ?></label>
				<div class="col-lg-8"><input class="jpro-form-control" value="<?php echo $membership['title']; ?>" disabled></div>
			</div><!-- / Membership Title -->
			
			<!-- Classifieds Purchase Date -->
			<div class="jpro-form-group">
				<label class="col-lg-4 jpro-control-label"><?php _e( 'Purchase date', 'jprocars' ); ?></label>
				<div class="col-lg-8"><input class="jpro-form-control" value="<?php echo $membership['purchase_date']; ?>" disabled></div>
			</div><!-- / Classifieds Purchase Date -->
			
			<!-- Classifieds Expire Date -->
			<div class="jpro-form-group">
				<label class="col-lg-4 jpro-control-label"><?php _e( 'Expiry date', 'jprocars' ); ?></label>
				<div class="col-lg-8"><input class="jpro-form-control" value="<?php echo $membership['expire_date']; ?>" disabled></div>
			</div><!-- / Classifieds Expire Date -->
			
			<!-- Classifieds Posting Limit -->
			<div class="jpro-form-group">
				<label class="col-lg-4 jpro-control-label"><?php _e( 'Classifieds posts left', 'jprocars' ); ?></label>
				<div class="col-lg-8">
					<?php
					switch( $membership['classifieds_limit'] ):
						
						case( $membership['classifieds_limit'] == 'unlimited' ):
							$classifieds_limit = 'unlimited';
						break;
						
						default: $classifieds_limit = $membership['classifieds_limit'].' posts';
						
					endswitch;
					?>
					<input class="jpro-form-control" value="<?php echo $classifieds_limit; ?>" disabled>
				</div>
			</div><!-- / Classifieds Posting Limit -->
			
			<!-- Classifieds Image Limit -->
			<div class="jpro-form-group">
				<label class="col-lg-4 jpro-control-label"><?php _e( 'Classifieds images limit', 'jprocars' ); ?></label>
				<div class="col-lg-8">
					<?php
					if( !empty( $membership['classifieds_image_limit'] ) ) {
						switch( $membership['classifieds_image_limit'] ):
						
							case( $membership['classifieds_image_limit'] == 'unlimited' ):
								$classifieds_image_limit = 'unlimited';
							break;
						
							default: $classifieds_image_limit = $membership['classifieds_image_limit'].' images';
						
						endswitch;
					}
					?>
					<input class="jpro-form-control" value="<?php echo $classifieds_image_limit; ?>" disabled>
				</div>
			</div><!-- / Classifieds Image Limit -->
			
			<!-- Classifieds Expire -->
			<div class="jpro-form-group">
				<label class="col-lg-4 jpro-control-label"><?php _e( 'Classifieds expire', 'jprocars' ); ?></label>
				<div class="col-lg-8">
					<?php
						switch( $membership['classifieds_expire'] ):
							
							case( $membership['classifieds_expire'] == 'never' ):
								$classifieds_expire = 'never';
							break;
							
							default: $classifieds_expire = __( 'for ', 'jprocars' ).$membership['classifieds_expire'].__( ' days', 'jprocars' );
							
						endswitch;
					?>
					<input class="jpro-form-control" value="<?php echo $classifieds_expire; ?>" disabled>
				</div>
			</div><!-- / Classifieds Expire -->
			
			<?php }else{ // If user do not have any package, show proper message ?>
			
			<div class="jpro-form-group">
				<label class="col-lg-12"><?php _e( 'Sorry, you dont have membership package yet!', 'jprocars' ); ?></label>
			</div>
			
			<?php } ?>
			
		</div>
	</div>
</div><!-- / My Membership -->