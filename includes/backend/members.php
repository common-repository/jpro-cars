<?php 
	$Settings = new JP_Settings();
	$packages = $Settings->getSettings( 'JP_MEMBERSHIP', '_jp_cars_membership_', true );
	
	$user_id = isset( $_POST['user_id'] ) ? $_POST['user_id'] : '';
	
	// Save membership
	if( isset( $_POST['save_membership'] ) && ! empty( $_POST['user_id'] ) ) {
		$package = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_membership_'.esc_attr( $_POST['package_id'] ), true );
		
		$args = array(
			'id' 						=> esc_attr( $package['id'] ),
			'title'						=> sanitize_text_field( $package['membership_title'] ),
			'type'						=> sanitize_text_field( $package['membership_type'] ),
			'purchase_date'				=> esc_attr( date('Y-m-d') ),
			'expire_date'				=> jpro_expiration_calculator('membership', $package['membership_expiration']),
			'classifieds_limit'			=> esc_attr( $package['membership_classifieds_limit'] ),
			'classifieds_expire'		=> esc_attr( $package['membership_classifieds_expiration'] ),
			'classifieds_image_limit'	=> esc_attr( $package['membership_image_upload_limit'] )
		);
		
		if( add_user_meta( esc_attr( $user_id ), '_jp_cars_membership', serialize( $args ), true ) ):
			echo $Settings->success();
		else:
			echo $Settings->failed();
		endif;
	}
	else // Remove user membership
	if( isset( $_POST['remove_membership'] ) && ! empty( $user_id ) ) {
		if( ! delete_user_meta( $user_id, '_jp_cars_membership' ) ) {
			echo $Settings->failed();
		} else {
			echo $Settings->success();
		}
	}
	else // Update user membership
	if( isset( $_POST['update_membership'] ) && ! empty( $user_id ) ) {
		
		// Get current user membership package
		$user_meta = unserialize(get_user_meta( $user_id, '_jp_cars_membership', true ));
		
		// Remove package options we will update
		unset( $user_meta['purchase_date'] );
		unset( $user_meta['expire_date'] );
		unset( $user_meta['classifieds_limit'] );
		unset( $user_meta['classifieds_expire'] );
		unset( $user_meta['classifieds_image_limit'] );
		
		$_POST['classifieds_limit'] 		= $_POST['classifieds_limit'] == '0' ? 'unlimited' : $_POST['classifieds_limit'];
		$_POST['classifieds_expire'] 		= $_POST['classifieds_expire'] == '0' ? 'never' : $_POST['classifieds_expire'];
		$_POST['classifieds_image_limit']	= $_POST['classifieds_image_limit'] == '0' ? 'unlimited' : $_POST['classifieds_image_limit'];
		
		// Add new update options to array
		$args = array(
			'purchase_date'				=> esc_html( $_POST['purchase_date'] ),
			'expire_date'				=> esc_html( $_POST['expire_date'] ),
			'classifieds_limit'			=> esc_html( $_POST['classifieds_limit'] ),
			'classifieds_expire'		=> esc_html( $_POST['classifieds_expire'] ),
			'classifieds_image_limit'	=> esc_html( $_POST['classifieds_image_limit'] )
		);
		
		// Merge options
		$args_merge = array_merge( $user_meta, $args );
		
		if( update_user_meta( $user_id, '_jp_cars_membership', serialize( $args_merge ) ) ):
			echo $Settings->success('update');
		else:
			echo $Settings->failed();
		endif;
	}
?>

<div class="wrap">
	<form method="post">
	<h2><?php _e( 'Members', 'jprocars' ); ?> <input name="add_membership" type="submit" class="add-new-h2" value="<?php _e( 'Add Membership to User', 'jprocars' ); ?>" style="cursor:pointer;"></h2>
	</form>
	
	<!-------------------------------------------- | SHOW USERS MEMBERSHIP | -------------------------------------------->
	
	<?php if( ! isset( $_POST['add_membership'] ) && ! isset( $_POST['edit_membership'] ) ): // SHOW ALL USERS WITH JPRO CARS MEMBERSHIP ?>
	<div class="jpro-panel">
		<div class="jpro-panel-heading">
			<span class="jpro-panel-title"><?php _e( 'All Members with JPro Cars Membership', 'jprocars' ); ?></span>
		</div>
		<div class="jpro-panel-body">
			<table class="jpro-table">
				<thead>
					<tr class="jpro-primary">
						<th><?php _e( 'User ID', 'jprocars' ); ?></th>
						<th><?php _e( 'Username', 'jprocars' ); ?></th>
						<th><?php _e( 'Membership', 'jprocars' ); ?></th>
						<th><?php _e( 'Membership Purchase', 'jprocars' ); ?></strong></th>
						<th><?php _e( 'Membership Expire', 'jprocars' ); ?></th>
						<th><?php _e( 'Classifieds Limit', 'jprocars' ); ?></th>
						<th><?php _e( 'Classifieds Image Limit', 'jprocars' ); ?></th>
						<th><?php _e( 'Classifieds Expire', 'jprocars' ); ?></th>
						<th><?php _e( 'Action', 'jprocars' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $users = get_users( array( 'meta_key' => '_jp_cars_membership' ) ); ?>
					<?php if( $users ): foreach( $users as $user ): $membership = unserialize( get_user_meta( $user->ID, '_jp_cars_membership', true ) ); ?>
					<form method="post">
					<input name="user_id" type="hidden" value="<?php echo esc_html( $user->ID ); ?>">
					<tr>
						<td><?php echo esc_html( $user->ID ); ?></td>
						<td><?php echo esc_html( $user->user_nicename ); ?></td>
						<td><?php echo esc_html( $membership['title'] ); ?></td>
						<td><?php echo esc_html( $membership['purchase_date'] ); ?></td>
						<td><?php printf( '%s', esc_html( $membership['expire_date'] ) ); ?>
						</td>
						<td><?php echo esc_html( $membership['classifieds_limit'] ).' post/s'; ?></td>
						<td><?php echo esc_html( $membership['classifieds_image_limit'] ).' image/s'; ?></td>
						<td><?php 
							if( $membership['classifieds_expire'] == 'never' ):
								printf( '%s', esc_html( $membership['classifieds_expire'] ) );
							else:
								printf( '%s %s %s', __( 'after', 'jprocars' ), esc_html( $membership['classifieds_expire'] ), __( 'day/s', 'jprocars' ) );
							endif; ?>
						</td>
						<td>
							<input name="edit_membership" type="submit" value="<?php _e( 'Edit', 'jprocars' ); ?>" class="add-new-h2" style="top:0px;cursor:pointer;">
							<input name="remove_membership" type="submit" value="<?php _e( 'Remove Membership', 'jprocars' ); ?>" class="add-new-h2" style="top:0px;cursor:pointer;">
						</td>
					</tr>
					</form>
					<?php endforeach; else: ?>
					<tr>
						<td colspan="9"><?php _e( 'No users with JPro Cars membership!', 'jprocars' ); ?></td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php endif; ?>
	
	<!-------------------------------------------- | EDIT USER MEMBERSHIP | -------------------------------------------->
	
	<?php if( isset( $_POST['edit_membership'] ) && ! empty( $user_id ) ): // EDIT USER MEMBERSHIP PACKAGE ?>
	<?php $membership = unserialize( get_user_meta( $user_id, '_jp_cars_membership', true ) ); ?>
	<div class="jpro-panel">
		<div class="jpro-panel-heading">
			<span><?php _e( 'Edit Membership Package', 'jprocars' ); ?></span>
		</div>
		<div class="jpro-panel-body">
			<form method="post" class="jpro-form-horizontal" role="form">
			<input name="user_id" type="hidden" value="<?php echo $user_id; ?>">
				<div class="jpro-form-group">
					<label class="col-lg-2 jpro-control-label"><?php _e( 'Package title', 'jprocars' ); ?></label>
					<div class="col-lg-9">
						<input name="membership_title" type="text" value="<?php echo esc_html( $membership['title'] ); ?>" class="jpro-form-control" disabled="disabled">
					</div>
				</div>
				<div class="jpro-form-group">
					<label class="col-lg-2 jpro-control-label"><?php _e( 'Package purchase date', 'jprocars' ); ?></label>
					<div class="col-lg-9">
						<input name="purchase_date" type="text" value="<?php echo esc_html( $membership['purchase_date'] ); ?>" class="jpro-form-control">
						<p class="description">
							<?php _e('Date must be formated only in next order: <strong>Year-Month-Day</strong>, if any other order added, the membership package won\'t work properly.', 'jprocars' ); ?>
						</p>
					</div>
				</div>
				<div class="jpro-form-group">
					<label class="col-lg-2 jpro-control-label"><?php _e( 'Package expiration', 'jprocars' ); ?></label>
					<div class="col-lg-9">
						<input name="expire_date" type="text" value="<?php echo esc_html( $membership['expire_date'] ); ?>" class="jpro-form-control">
						<p class="description">
							<?php _e('Date must be formated only in next order: <strong>Year-Month-Day</strong> or <strong>never</strong>, if any other order added, the membership package won\'t work properly.', 'jprocars' ); ?>
						</p>
					</div>
				</div>
				<div class="jpro-form-group">
					<label class="col-lg-2 jpro-control-label"><?php _e( 'Classifieds posting limit', 'jprocars' ); ?></label>
					<div class="col-lg-9">
						<select name="classifieds_limit" class="jpro-form-control">
							<?php foreach( range(0,200) as $posts ): ?>
								<option value="<?php echo $posts; ?>" <?php selected( $membership['classifieds_limit'], $posts, true ); ?>>
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
					<label class="col-lg-2 jpro-control-label"><?php _e( 'Classifieds image upload limit', 'jprocars' ); ?></label>
					<div class="col-lg-9">
						<select name="classifieds_image_limit" class="jpro-form-control">
							<?php foreach( range(0,10) as $images ): ?>
								<option value="<?php echo $images; ?>" <?php selected( $membership['classifieds_image_limit'], $images, true ); ?>>
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
						<select name="classifieds_expire" class="jpro-form-control">
							<?php foreach( range(0,60) as $months ): ?>
								<option value="<?php echo $months; ?>" <?php selected( $membership['classifieds_expire'], $months, true ); ?>>
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
						<input name="update_membership" type="submit" class="add-new-h2" value="<?php _e( 'Update Membership', 'jprocars' ); ?>">
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php endif; ?>
	
	<!-------------------------------------------- | ADD USER MEMBERSHIP | -------------------------------------------->
	
	<?php if( isset( $_POST['add_membership'] ) ): // ADD MEMBERSHIP PACKAGE TO USER ?>
	<div class="jpro-panel">
		<div class="jpro-panel-heading">
			<span><?php _e( 'Add Membership to User', 'jprocars' ); ?></span>
		</div>
		<div class="jpro-panel-body">
			<?php $users = get_users(); ?>
			<form method="post" class="jpro-form-horizontal" role="form">
				<div class="jpro-form-group">
					<label class="col-lg-2 jpro-control-label"><?php _e( 'Select user', 'jprocars' ); ?></label>
					<div class="col-lg-9">
						<select name="user_id" class="jpro-form-control">
							<?php foreach( $users as $user ): ?>
								<?php if( ! get_user_meta( $user->ID, '_jp_cars_membership', true ) ) { ?>
									<option value="<?php echo $user->ID; ?>"><?php echo esc_html( $user->user_nicename ); ?></option>
								<?php } ?>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="jpro-form-group">
					<label class="col-lg-2 jpro-control-label"><?php _e( 'Select membership', 'jprocars' ); ?></label>
					<div class="col-lg-9">
						<select name="package_id" class="jpro-form-control">
							<?php if( $packages ): foreach( $packages as $package ): ?>
							<option value="<?php echo $package['id']; ?>"><?php echo esc_html( $package['membership_title'] ); ?></option>
							<?php endforeach; else: ?>
							<option value=""><?php _e( 'No membership packages!', 'jprocars' ); ?> Go to Settings->Membership Packages and create package first!</option>
							<?php endif; ?>
						</select>
					</div>
				</div>
				<?php if( ! empty( $packages ) ): ?>
				<div class="jpro-form-group">
					<label class="col-lg-2 jpro-control-label"><input name="save_membership" type="submit" class="add-new-h2" value="<?php _e( 'Add Membership', 'jprocars' ); ?>" style="cursor:pointer;"></label>
				</div>
				<?php endif; ?>
			</form>
		</div>
	</div>
	<?php endif; ?>
	
</div><!-- .wrap -->