<?php
class JP_Settings
{
	/**
	 * Update | Save | Create - update_option
	 * ======================================
	 * $table:
	 * WP_OPTIONS
	 * JP_CURRENCIES
	 *
	 * @since 0.2
	 */
	public function update( $table, $key, $value, $post_id = false, $array = false ) {
		switch( $table ):
			
			/**
			 * Update single wp_options
			 * ========================
			 * @since 0.2
			 */
			case $table == 'WP_OPTIONS' && $array == false:
				 if( update_option( $key, $value ) )
					echo $this->success();
			break;
			
			/**
			 * Update array wp_options
			 * =======================
			 * @since 0.2
			 */
			case $table == 'WP_OPTIONS' && $array == true:
				foreach( $value as $arname => $aroption ) {
					update_option( $key, $arname, $aroption );
				}
				echo $this->success();
			break;
			
			/**
			 * Update _jp_cars_currencies
			 * ==========================
			 * @since 0.4
			 */
			case $table == 'JP_CURRENCIES':
				 if( update_option( $key, $value ) )
					echo $this->success();
			break;
		
		endswitch;
	}
	/**
	 * Delete Settings From Database
	 *
	 * $table:
	 * WP_OPTIONS
	 * WP_POSTMETA
	 * WP_USERMETA
	 *
	 * @since 0.2
	 */
	public function delete( $table, $key, $post_id = false, $user_id = false, $meta_key = false, $meta_value = false ) {
		switch( $table ):
			
			/**
			 * Delete from WP_OPTIONS
			 * ======================
			 * @since 0.2
			 */
			case $table == 'WP_OPTIONS':
				 if( delete_option( $key ) )
					echo $this->success();
			break;
		
		endswitch;
	}
	/**
	 * Get option from database
	 *
	 * WP_OPTIONS
	 * WP_POSTMETA
	 * WP_USERMETA
	 * JP_GATEWAYS
	 * JP_MEMBERSHIP
	 *
	 * @since 0.2
	 * @return string || array
	 */
	public function getSettings( $table, $key, $unserialize = false, $user_id = false ) {
		$settings = false; // default settings status, once settings is updated return true
		
		switch( $table ):
		
			case $table == 'WP_OPTIONS' && $unserialize == false:
				 
				 if( get_option( $key ) )
					 $settings = get_option( $key );
				 
			break;
			
			case $table == 'WP_OPTIONS' && $unserialize == true:
			
				 if( get_option( $key ) )
					 $settings = unserialize( get_option( $key ) );
					
			break;
			
			case $table == 'WP_USERMETA' && $unserialize == true && $user_id !== false:
				 if( get_user_meta( $user_id, $key ) )
					 $settings = unserialize( get_user_meta( $user_id, $key, true ) );
			break;
			
			/**
			 * MEMBERSHIPS || GATEWAYS
			 */
			case $table == 'JP_MEMBERSHIP' || $table == 'JP_GATEWAYS' && $unserialize == true:
				 
				 $all_options = wp_load_alloptions();
				 $options = array();
				 
				 foreach( $all_options as $name => $value ) {
					if( stristr( $name, $key ) ) {
						$options[$name] = $value;
						$settings[] = maybe_unserialize( unserialize( $options[$name] ) );
					}
				 }
				 
			break;
			
			/**
			 * GET CURRENT USER MEMBERSHIP PACKAGE
			 */
			case $table == 'WP_USERMETA' && $unserialize == true:
				 $settings = unserialize( get_user_meta( get_current_user_id(), $key, true ) );
			break;
		
		endswitch;

		return $settings;
	}
	/**
	 * If operation successful, show message
	 *
	 * @since 0.2
	 */
	public function success( $type = null ) {
		if( $type == 'update' ) {
			$message = 'Settings updated!';
		} else {
			$message = 'Settings saved!';
		}
		return	'
			<div id="setting-error-settings_updated" class="updated settings-error">
				<p><strong>'. __( $message, 'jprocars' ) .'</strong></p>
			</div>
			<meta http-equiv="refresh" content="0" url="'.$_SERVER['PHP_SELF'].'">
				';
	}
	/**
	 * If operation failed, show message
	 *
	 * @since 0.2
	 */
	public function failed() {
		return	'
			<div id="setting-error-settings_updated" class="error settings-error">
				<p><strong>'. __( 'Operation failed.', 'jprocars' ) .'</strong></p>
			</div>
			<meta http-equiv="refresh" content="0" url="'.$_SERVER['PHP_SELF'].'">
				';
	}
}
?>