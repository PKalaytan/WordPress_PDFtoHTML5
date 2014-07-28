<?php
/*
Author: Simon Lissack
Company: IDR Solutions
Version: 1.0
*/
	add_shortcode('PDF', 'idrws_pdf_shortcode');
	add_shortcode('pdf', 'idrws_pdf_shortcode');

	function idrws_pdf_shortcode($atts){
		$a = shortcode_atts(array('id'=>'-1'), $atts);
		// No ID value
		if(strcmp($a['id'], '-1') == 0){
			return "";
		}
		$pdf=$a['id'];
		$url=IDRWS_PLUGIN_URL."output/".$pdf."/".$pdf."/index.html";
		$iframe = "<iframe allowfullscreen class='idrws_pdf_short' src='".$url."' style='width:80%; height:800px;'></iframe>";
	
	
	
		return $iframe;
	}

	function idrws_upload_as_page($filename){
		$dir = IDRWS_PLUGIN_DIR."output/".$filename."/".$filename;
		$post  = array();
		$pages = scandir($dir);
		

		// Can and SHOULD be optimized
		foreach($pages as $page){
			if(preg_match("/.html$/", $page)){
				echo $page;
				$post[]=$page;
			}
		}
		foreach($post as $page){
			idrws_add_page($dir."/".$page, $filename);
		}
	}

	function idrws_add_page($page, $filename){
		$html = file_get_contents($page);
		$slug = preg_replace("/[ ]/", "_", $filename);
		$post = array(
			'post_content' 	=> $html,
			'post_name' 	=> $slug,
			'post_title'	=> $filename
			);
		$id = wp_insert_post($post, true);

		if(is_wp_error($id)){
			echo $id->get_error_message();
		}
	}
?>