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
?>