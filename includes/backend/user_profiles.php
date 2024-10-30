<?php 
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add Car Classifieds Membership Fields in User Profiles
 *
 * @since 0.1
 */
add_action('show_user_profile', 'jpro_membership_profile_fields');
add_action('edit_user_profile', 'jpro_membership_profile_fields');
function jpro_membership_profile_fields( $user ) {
	
	$Settings	= new JP_Settings();
	$membership	= $Settings->getSettings( 'WP_USERMETA', '_jp_cars_membership', true, $user->ID );
	
	if( $membership ): ?>
	<table class="form-table">
	<h3><?php _e( 'Car Classifieds', 'jprocars' ); ?></h3>
		<tbody>
		  <tr>
			<th><label for="membershiptitle"><?php _e( 'Membership:', 'jprocars' ); ?></label></th>
			<td><input type="text" value="<?php echo esc_attr( $membership['title'] ); ?>" disabled="disabled" /></td>
		  </tr>
		  <tr>
			<th><label for="membershiptype"><?php _e( 'Membership type:', 'jprocars' ); ?></th>
			<td><input type="text" value="<?php echo esc_attr( ucfirst( $membership['type'] ) ); ?>" disabled="disabled" /></td>
		  </tr>
		  <tr>
			<th><label for="membershippurchasedate"><?php _e( 'Membership purchase date:', 'jprocars' ); ?></th>
			<td><input type="text" value="<?php echo esc_attr( $membership['purchase_date'] ); ?>" disabled="disabled" /></td>
		  </tr>
		  <tr>
			<th><label for="membershipexpirationdate"><?php _e( 'Membership expiration date:', 'jprocars' ); ?></th>
			<td><input type="text" value="<?php echo esc_attr( $membership['expire_date'] ); ?>" disabled="disabled" /></td>
		  </tr>
		  <tr>
			<th><label for="classifiedspostlimit"><?php _e( 'Classifieds post limit:', 'jprocars' ); ?></th>
			<td><input type="text" value="<?php echo esc_attr( $membership['classifieds_limit'] ); ?> <?php _e( 'posts', 'jprocars' ); ?>" disabled="disabled" /></td>
		  </tr>
		  <tr>
			<th><label for="classifiedsexpiration"><?php _e( 'Classifieds expiration after:', 'jprocars' ); ?></th>
			<td><input type="text" value="<?php echo esc_attr( $membership['classifieds_expire'] ); ?> <?php _e( 'days', 'jprocars' ); ?>" disabled="disabled" /></td>
		  </tr>
		</tbody>
	</table>
	<?php endif; ?>
    <?php
}
?>