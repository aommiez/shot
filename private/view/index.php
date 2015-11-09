<?php
use Main\Helper;
$this->import('/layout/header');
 ?>

<div class="container">
  <div id="results"></div>
  <div id="my_camera" style='margin-top:12px;margin-bottom:12px;'></div>
	<form>
		<div id="pre_take_buttons">
      <input type="button" class="btn btn-primary btn-large" value="Take Snapshot" onClick="preview_snapshot()"/>
		</div>
		<div id="post_take_buttons" style="display:none">
      <input type="button" class="btn btn-inverse btn-large" value="Take Another" onClick="cancel_preview()"/>
      <input type="button" class="btn btn-success btn-large" value="Save Photo" onClick="save_photo()" />
		</div>
	</form>
</div>
<!-- Configure a few settings and attach camera -->
	<script language="JavaScript">
		Webcam.set({
			width: 1280,
			height: 720,
			image_format: 'jpeg',
			jpeg_quality: 100
		});
		Webcam.attach( '#my_camera' );
	</script>
<!-- Code to handle taking the snapshot and displaying it locally -->
	<script language="JavaScript">
		function preview_snapshot() {
			// freeze camera so user can preview pic
			Webcam.freeze();

			// swap button sets
			document.getElementById('pre_take_buttons').style.display = 'none';
			document.getElementById('post_take_buttons').style.display = '';
		}

		function cancel_preview() {
			// cancel preview freeze and return to live camera feed
			Webcam.unfreeze();

			// swap buttons back
			document.getElementById('pre_take_buttons').style.display = '';
			document.getElementById('post_take_buttons').style.display = 'none';
		}

		function save_photo() {
			// actually snap photo (from preview freeze) and display it
			Webcam.snap( function(data_uri) {
        /*
				// display results in page
				document.getElementById('results').innerHTML =
					'<h2>Here is your image:</h2>' +
					'<img src="'+data_uri+'"/>';
        */

        Webcam.upload( data_uri, 'savephoto', function(code, text) {
            // Upload complete!
            // 'code' will be the HTTP response code from the server, e.g. 200
            // 'text' will be the raw response content
            //alert('save');
        } );

				// swap buttons back
				document.getElementById('pre_take_buttons').style.display = '';
				document.getElementById('post_take_buttons').style.display = 'none';
			} );

		}
	</script>
