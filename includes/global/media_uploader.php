<?php

// No direct access allowed.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

##############################################
# Ajax Media Uploader 
# Credits: krishnakantsharma.com
##############################################
add_action( 'wp_enqueue_scripts', 'jpro_enqueue_uploader_scripts' );
add_action( 'admin_enqueue_scripts', 'jpro_enqueue_uploader_scripts' );
function jpro_enqueue_uploader_scripts() {
	
	// Load photo upload scripts only if [page = add_classified] or [post_type = car-classifieds]
	if( 
        is_admin() || 
        isset( $_GET['jp'] ) && $_GET['jp'] == 'add-new-classified' || 
        isset( $_GET['jp'] ) && $_GET['jp'] == 'edit-classified' || 
        isset( $_POST['add-new-classified'] ) ) 
    {
		wp_enqueue_script( 'plupload-all' );
 
		wp_register_script( 'media-uploader', JPRO_CAR_URI . 'assets/js/media-uploader.js', array( 'jquery' ), uniqid() );
		wp_enqueue_script( 'media-uploader' );
 
		wp_register_style( 'media-uploader', JPRO_CAR_URI . 'assets/css/media-uploader.css' );
		wp_enqueue_style( 'media-uploader' );
	}
	
}

if( 
    isset( $_GET['jp'] ) && $_GET['jp'] == 'add-new-classified' || 
    isset( $_GET['jp'] ) && $_GET['jp'] == 'edit-classified' || 
    isset( $_POST['add-new-classified'] ) ) 
{
    add_action( 'wp_head', 'jpro_plupload_head' ); 
}

add_action( 'admin_head', 'jpro_plupload_head' ); 
function jpro_plupload_head() {
	// place js config array for plupload
    $plupload_init = array(
        'runtimes'              => 'html5,html4',
        'browse_button'         => 'plupload-browse-button', // will be adjusted per uploader
        'container'             => 'plupload-upload-ui', // will be adjusted per uploader
        'drop_element'          => 'drag-drop-area', // will be adjusted per uploader
        'file_data_name'        => 'async-upload', // will be adjusted per uploader
        'multiple_queues'       => true,
        'max_file_size'         => wp_max_upload_size() . 'b',
        'url'                   => admin_url( 'admin-ajax.php' ),
        'filters'               => array( array( 'title' => esc_attr__( 'Allowed Files', 'jpro-cars' ), 'extensions' => '*' ) ),
        'multipart'             => true,
        'urlstream_upload'      => true,
        'multi_selection'       => false, // will be added per uploader
         // additional post data to send to our ajax hook
        'multipart_params'      => array(
            '_ajax_nonce'       => "", // will be added per uploader
            'action'            => 'plupload_action', // the ajax action name
            'imgid'             => 0 // will be added per uploader
        )
    );
?>
<script type="text/javascript">  
    var jpro_cars_plupload_config=<?php echo json_encode( $plupload_init ); ?>;
</script>  
<?php }
/**
 * Setup ajax handler to handle asynchronous file upload.
 */
add_action( 'wp_ajax_plupload_action', 'jpro_plupload_action' );
function jpro_plupload_action() {
 
    // check ajax noonce
    $imgid = $_POST["imgid"];
    check_ajax_referer( $imgid . 'pluploadan' );
 
    // handle file upload
    $status = wp_handle_upload( $_FILES[$imgid . 'async-upload'], array( 'test_form' => true, 'action' => 'plupload_action' ) );
    
    // send the uploaded file url in response
	//print_r( $status );
	echo $status['url'];
	exit;
}
?>