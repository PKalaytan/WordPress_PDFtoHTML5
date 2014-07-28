/*
Author: Simon Lissack
Company: IDR Solutions
Version: 1.0
*/

// jQuery on WP uses compatability mode, meaning $ is unset, alias jQuery function back to j (avoiding $ in case of conflicts)
function j(val){
	return jQuery(val);
}

j(document).ready(function(){
	j("#idrws_available_pdfs").click(function(){
        j("#idrws_selected_pdf_name").val(j("#idrws_available_pdfs").find(":selected").text());
        console.log("set");
		idrws_set_preview();
	});
	j('#idrws-istrial').bind('change', function(){
		val = this.checked;
		idrws_toggle_login(val);
	});
	idrws_toggle_login(j('#idrws-istrial').val());

});

function idrws_set_preview(){
	var pdfName=j("#idrws_available_pdfs").find(":selected").text();
	var output=outputLoc+"/"+pdfName+"/"+pdfName+"/index.html";
	var pdfID=j("#idrws_available_pdfs").val();

	j("#idrws_selected_pdf").val(pdfID);
    idrws_iframe_load();

}

function idrws_iframe_load(){
	var pdfName=j("#idrws_available_pdfs").find(":selected").text();
    console.log(pdfName);
    if(pdfName === "" || pdfName === undefined){
        return;
    }

	try {
        var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        var url = outputLoc+pdfName+"/"+pdfName+"/index.html";
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4) {
                if(xmlhttp.status==200){
                    idrws_show_iframe(pdfName);
                }else if (xmlhttp.status==404){
                    idrws_show_convert(pdfName);
                }
                xmlhttp.onreadystatechange=null;
            }
        };

        xmlhttp.open("GET",url,false);
        xmlhttp.send();
    } catch (e) {
        console.log(e);
        idrws_show_convert(pdfName);
    }
}

function idrws_show_iframe(pdfName){
	j('#idrws-preview').show();
    j("#idrws-preview").attr('src', outputLoc+"/"+pdfName+"/"+pdfName+"/index.html");
	j('#idrws_selected_shortcode').show();
	j('#idrws_selected_shortcode').html("[pdf id='"+pdfName+"']");
}

function idrws_show_convert(pdfName){
	j('#idrws-preview').hide();
	j('#idrws_selected_shortcode').hide();
}

function idrws_toggle_login(isTrial){
	j('#username').attr('readonly', isTrial);
	j('#password').attr('readonly', isTrial);

}