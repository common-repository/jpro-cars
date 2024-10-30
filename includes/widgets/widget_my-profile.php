<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * My Profile Widget
 *
 * @since 0.1
 */
class JP_Widget_My_Profile extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct( 'jp_widget_my_profile', __( 'JPro Cars: My Profile', 'jprocars' ), array( 'description' => __( 'Car classifieds user important links.' ), ) );
	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $post;
		
		$Settings = new JP_Settings();
		$settings = $Settings->getSettings( 'WP_OPTIONS', '_jp_cars_settings', true );
		
		if( is_page( $settings['cc_page_id'] ) || $post->post_type == 'car-classifieds' ) {
			echo $args['before_widget']; ?>
			
			<div class="jpro-panel">
				
				<?php if( empty( $args['before_title'] ) ): ?>
				<div class="jpro-panel-heading">
					<span class="jpro-panel-title">
					<?php endif; ?>
				
						<?php 	
						if ( ! empty( $instance['title'] ) ) {
							echo $args['before_title'].apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
						}
						?>
				
				<?php if( empty( $args['before_title'] ) ): ?>
					</span>
				</div>
				<?php endif; ?>
				
				<div class="jpro-panel-body">
					<form <?php if( $settings['cc_page_id'] ){ echo 'action="'. esc_url( get_permalink( $settings['cc_page_id'] ) ) .'"';} ?> method="post" class="jpro-form-horizontal" role="form">
						
						<?php if( is_user_logged_in() ): ?>
						
							<div class="jpro-form-group">
								<div class="col-lg-12">
									<ul>
										<li><?php printf( '<a href="%s">%s</a>', esc_url( get_permalink( $settings['cc_page_id'] ) ), __( 'All Classifieds', 'jprocars' ) ); ?></li>
										<li><?php printf( '<a href="%s">%s</a>', esc_url( add_query_arg( 'jp', 'my-classifieds', get_permalink( $settings['cc_page_id'] ) ) ), __( 'My Classifieds', 'jprocars' ) ); ?></li>
										<li><?php printf( '<a href="%s">%s</a>', esc_url( add_query_arg( 'jp', 'my-membership', get_permalink( $settings['cc_page_id'] ) ) ), __( 'My Membership', 'jprocars' ) ); ?></li>
										<li><?php printf( '<a href="%s">%s</a>', esc_url( add_query_arg( 'jp', 'my-transactions', get_permalink( $settings['cc_page_id'] ) ) ), __( 'My Transactions', 'jprocars' ) ); ?></li>
										<li><?php printf( '<a href="%s">%s</a>', esc_url( add_query_arg( 'jp', 'change-membership', get_permalink( $settings['cc_page_id'] ) ) ), __( 'Change Membership', 'jprocars' ) ); ?></li>
									</ul>
								</div>
							</div>
						
							<div class="jpro-form-group">
								<div class="col-lg-12">
									<button name="add-new-classified" type="submit" class="btn btn-warning btn-large"><i class="fa fa-plus"></i> <?php _e( 'Add Classified', 'jprocars' ); ?></button>
								</div>
							</div>
						
						<?php else: ?>
							<!-- If user not logged in -->
							<div class="jpro-form-group">
								<div class="col-lg-12">
									<?php _e( 'Please', 'jprocars' ); ?> 
									<a href="<?php echo esc_url( wp_login_url( get_permalink( $settings['cc_page_id'] ) ) ); ?>" title="<?php _e( 'Login', 'jprocars' ); ?>"><?php _e( 'login', 'jprocars' ); ?></a> 
									<?php _e( 'or', 'jprocars' ); ?> 
									<a href="<?php echo wp_registration_url(); ?>" title="<?php _e( 'Register', 'jprocars' ); ?>"><?php _e( 'register', 'jprocars' ); ?></a> 
									<?php _e( 'first.', 'jprocars' ); ?>
								</div>
							</div><!-- / If user not logged in -->
						<?php endif; ?>
						
					</form>
				</div>
			</div>

			<?php
			echo $args['after_widget'];
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'My Profile', 'jprocars' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}
/**
 * Register Widget
 *
 * @since 0.1
 */
function register_jp_my_profile_widget() {
    register_widget( 'JP_Widget_My_Profile' );
}
add_action( 'widgets_init', 'register_jp_my_profile_widget' );
?>