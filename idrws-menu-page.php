<?php
/*
Author: Simon Lissack
Company: IDR Solutions
Version: 1.0
*/
	function idrws_load_content(){
?>
	<div id="idrws-wrapper">
		<div class='wrap'>
			<h1>PDF to HTML5</h1>
			<h3>Powered by <a href="http://www.idrsolutions.com/html_products">JPDF2HTML5</a></h3>
			<div id="idrws-left">
				<div id="idrws-files-container">
				<!-- <div id="idrws-files-wrapper"> -->
					<h2 class="idrws-option-header">Step 1: Upload a File</h2>
					<div id="idrws-file-list">
						<?php idrws_get_files(); ?>
						<div id="idrws-upload">
							<h3 style="margin: 0;">Upload a new File</h3>
							<form action='' method='post'  enctype="multipart/form-data">
								<input type='file' id='idrws_upload_pdf' name='idrws_upload_pdf' style="width:100%;"></input>
								<?php submit_button('Upload') ?>
							</form>
						</div>
					<!-- </div> -->
				</div>
			</div>
			<br/>
			<div id="idrws-converion">
				<form action='' method='post' autocomplete='on'>
					<div id="idrws-options-menu">
						<div id="idrws-conversion-container">
							<a name="convert"></a>
							<h2 class="idrws-option-header">Step 2: Convert</h2>

							<div><input type="text" name="idrws_selected_pdf_name" id="idrws_selected_pdf_name" readonly><input type="text" id="idrws_selected_pdf" name="idrws_selected_pdf" readonly></div>
							<div id="idrws-view-mode">
								<h3>View Mode</h3>
								<label><input type="radio" name="viewMode" value="multifile" checked>Individual Pages			</label><br/>
								<label><input type="radio" name="viewMode" value="pageturning">Page Turning 					</label><br/>
								<label><input type="radio" name="viewMode" value="singlefile">Single Document					</label><br/>
								<label><input type="radio" name="viewMode" value="multifile_splitspreads">Magazine Layout		</label><br/>
								<label><input type="radio" name="viewMode" value="singlefile_splitspreads">Continuous Magazine 	</label>
							</div>
						</div>
						<?php submit_button('Convert') ?>
					</div>
					<div id="idrws-account-container">
						<h2 class="idrws-option-header">Account details</h2>
						<h3>Trial account available only</h3>

						<!-- <label><input type="checkbox" id="idrws-istrial" name="istrial" checked>Trial?</label> -->

						<!-- <h2>Account Options</h2>
						Email:<br/>
							<input id="username" type="text" name="idrws-email"></br>
						Password:<br/>
							<input id="password" type="password" name="idrws-password"></br> -->
					</div>
				</form>
			</div>
			</div>
			<div id="idrws-right">
				<div id="idrws-preview-window">
					<h2 class="idrws-option-header">Step 3: Preview</h2>
					<div id='idrws_selected_shortcode'></div>
					<iframe id="idrws-preview" allowfullscreen>Your browser does not support IFrames - Preview unavailable</iframe>
				</div>
				<div id="idrws-about-us">
					<h2 class="idrws-option-header">About PDF to HTML5</h2>
					<br/>
					<a href="http://www.idrsolutions.com/"><img src="<?php echo IDRWS_PLUGIN_URL?>images/idr_transparent.png" style="width:150px;float:left"/></a>
					<div>Created by <a href="http://www.idrsolutions.com/">IDR Solutions</a><br/>
						Click here to see our <a href="http://www.idrsolutions.com/products/">other products</a>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	}

	// Create the select menu for the PDFs
	function idrws_get_files(){
		$posts_array = idrws_get_pdf_files();
		echo "<select name='idrws_selected_pdf' id='idrws_available_pdfs' size='10'>";
		foreach ($posts_array as $value) {
			echo "<option value='".$value->ID."' >" . $value->post_title;
		}
		echo "</select>";
	}

?>