<?php
/*
Author: Simon Lissack
Company: IDR Solutions
Version: 1.0
*/

function idrws_convert_file($file_id, $filename, $is_content){
    $client = new SoapClient('http://cloud.idrsolutions.com:8080/HTML_Page_Extraction/IDRConversionService?wsdl');


    $file=file_get_contents(wp_get_attachment_url($file_id));

    $details=idrws_fetch_user_details();

    $outputdir = IDRWS_PLUGIN_DIR . "output/" . $filename. "/";
	if (!file_exists($outputdir)) {
	    mkdir($outputdir, 0777, true);
	}

    $style_params = idrws_setup_params();

    $conversion_params = array("email" => $details['email'],
                            "password" =>$details['password'],
                            "fileName"=>$filename,
                            "dataByteArray"=>$file,
                            "conversionType"=>"html5",
                            "conversionParams"=>$style_params,
                            "xmlParamsByteArray"=>null,
                            "isDebugMode"=>false);

    try{
        $output = (array)($client->convert($conversion_params));
    } catch (Exception $e){
        echo $e->getMessage() . "<br/>";
        return;
    }

    idrws_extract_files($outputdir, $filename, $output);
}

function idrws_fetch_user_details(){
    // if(isset($_POST["idrws-email"]) && $_POST["idrws-password"]){
        // $username=filter_var($_POST["idrws-email"], FILTER_SANITIZE_EMAIL);
        // $password=$_POST["idrws-password"];
    // }else{
        $username="wordpress";
        $password="wordpress";
    // }

    return array("email"=>$username,"password"=>$password);
}

function idrws_setup_params(){
    $params = array();

    $params[0] = "org.jpedal.pdf2html.viewMode";
    $params[1] = $_POST["viewMode"];

    return $params;

}

function idrws_extract_files($outputdir, $filename, $output){
    WP_Filesystem();

    if(is_dir($outputdir.$filename)){
        idrws_recursive_delete($outputdir.$filename);
    }

    file_put_contents($outputdir.$filename.".zip", $output);
    $result=unzip_file($outputdir.$filename.".zip", $outputdir);
}

function idrws_recursive_delete($root_dir){
    $root_dir.=DIRECTORY_SEPARATOR;
    $dir = scandir($root_dir);
    foreach ($dir as $entry) {
        // Ignore current and parent dirs
        if ($entry != "." && $entry != "..") {
            $entry = $root_dir.$entry;
            if(is_dir($entry)){
                idrws_recursive_delete($entry);
                rmdir($root_dir);
            }else if (is_file($entry)){
                unlink($entry);
            }
        }
    }
}

?>