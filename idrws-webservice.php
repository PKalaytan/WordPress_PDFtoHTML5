<?php
/*
Plugin Name: PDF to HTML5 Converter
Plugin URI: https://github.com/IDRSolutions/WordPress_PDFtoHMTL5
Description: Convert PDF files to HTML5
Author: Simon Lissack
Company: IDR Solutions
Version: 1.0
*/
require_once 'idrws-convert.php';
require_once 'idrws-page-handler.php';
require_once 'idrws-menu-page.php';

define('IDRWS_PLUGIN_URL',plugin_dir_url(__FILE__));
// Get dir and make it use platform dependent seperators
define('IDRWS_PLUGIN_DIR',preg_replace("[\\/]", DIRECTORY_SEPARATOR, plugin_dir_path(__FILE__)));
define('IDRWS_ADMIN_SUFFIX', 'pdf-to-html');
$idrws_is_converting=false;

add_action('admin_enqueue_scripts', 'idrws_setup_scripts');
add_action('admin_menu', 'idrws_converter_menu');

function idrws_converter_menu(){
	add_menu_page( 'PDF to HTML5 options', 'PDF to HTML5', 'manage_options', IDRWS_ADMIN_SUFFIX, 'idrws_init', 'dashicons-media-code' );
}

function idrws_init(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	idrws_handle_post_data();
	idrws_load_content();

	idrws_setup_css();

	idrws_write_vars();
}

function idrws_setup_scripts($hook_suffix){
	// Stop scripts being loaded in other sections of the admin panel
	if($hook_suffix == 'toplevel_page_'.IDRWS_ADMIN_SUFFIX){
		wp_enqueue_script("jquery");
		wp_enqueue_script("idrws-admin-page-js", plugins_url( 'idrws-admin-page.js' , __FILE__ ), array('jquery'), "1.0", false);
	}
}

function idrws_setup_css(){
	wp_enqueue_style("idr-webservice-css", plugins_url( 'idrws-style.css' , __FILE__ ));
}


function idrws_get_pdf_files(){
	$args = array(
		'post_mime_type'=>'application/pdf'
		,'post_type'=>'attachment'
		);

	$posts_array = get_posts($args);
	return $posts_array;
}

function idrws_get_pdf_file($id){
	$posts_array = get_post($id);
	return $posts_array;
}

function idrws_handle_post_data(){
	// Check a pdf was passed via post, otherwise checks for file upload
	if(isset($_POST['idrws_selected_pdf'])){
		$idrws_is_converting=true;
		$filename = idrws_start_conversion();
	}else if(isset($_FILES['idrws_upload_pdf'])){
		$id = idrws_handle_upload();
		$filename = idrws_get_pdf_file($id)->post_title;
	}
	return $filename;
}

function idrws_start_conversion(){
	$file_id = $_POST["idrws_selected_pdf"];
	$filename = idrws_get_pdf_file($file_id)->post_title;

	idrws_convert_file($file_id, $filename, $is_content);

	return $filename;
}

function idrws_handle_upload(){
	$pdf = $_FILES['idrws_upload_pdf'];
	echo "uploading " . $pdf;
	$uploaded=media_handle_upload('idrws_upload_pdf', 0);
	if(is_wp_error($uploaded)){
		echo $uploaded->get_error_message();
	}
}

/*
 * Write out variables used by javascript
 */
function idrws_write_vars(){
?>
	<script type='text/javascript'> 
		var outputLoc = '<?php echo IDRWS_PLUGIN_URL ?>output/';
		var isConverting = '<?php echo $idrws_is_converting ?>';
	</script>
<?php
}
?>