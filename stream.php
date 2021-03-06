
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8' />
	<title>Using getUserMedia with HTML5 <video> and <canvas></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<style>
		body {
			margin-top:2%;
			text-align:center;
			color:#fff;
			background-color:#122836;
		}
		video {
			position:absolute;
			visibility:hidden;
		}
		canvas { border:8px solid #fff;}
		button {
			border:none;
			display:block;
			padding:0.5em 1em;
			margin:1% auto 0;
			cursor:pointer;
			color:#fff;
			background-color:#9f361b;
		}
		button:active { background-color:#e44d26; }
	</style>
</head>
<body>
	<h1>Using getUserMedia with HTML5 &lt;video&gt; and &lt;canvas&gt;</h1>
	<video id='v'></video>
	<canvas id='c'></canvas>
	<button id='grey'>Toggle Greyness</button>
  <script src="socket.io.js"></script>
  <script src="socket.io-stream.js"></script>
  <script>
  (function() {

	window.addEventListener('DOMContentLoaded', function() {
    var socket = io.connect('localhost:3000');
		var isStreaming = false,
			v = document.getElementById('v'),
			c = document.getElementById('c'),
			grey = document.getElementById('grey');
			con = c.getContext('2d');
			w = 600,
			h = 420,
			greyscale = false;

		// Cross browser
		navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
		if (navigator.getUserMedia) {
			// Request access to video only
			navigator.getUserMedia(
				{
					video:true,
					audio:false
				},
				function(stream) {

					// Cross browser checks
					var url = window.URL || window.webkitURL;
        			v.src = url ? url.createObjectURL(stream) : stream;
        			// Set the video to play
        			v.play();
				},
				function(error) {
					alert('Something went wrong. (error code ' + error.code + ')');
					return;
				}
			);
		}
		else {
			alert('Sorry, the browser you are using doesn\'t support getUserMedia');
			return;
		}

		// Wait until the video stream can play
		v.addEventListener('canplay', function(e) {
		    if (!isStreaming) {
		    	// videoWidth isn't always set correctly in all browsers
		    	if (v.videoWidth > 0) h = v.videoHeight / (v.videoWidth / w);
				c.setAttribute('width', w);
				c.setAttribute('height', h);
				// Reverse the canvas image
				con.translate(w, 0);
  				con.scale(-1, 1);
		      	isStreaming = true;
		    }
		}, false);

		// Wait for the video to start to play
		v.addEventListener('play', function() {
			// Every 33 milliseconds copy the video image to the canvas
			setInterval(function() {
				if (v.paused || v.ended) return;
				con.fillRect(0, 0, w, h);
				con.drawImage(v, 0, 0, w, h);
				if (greyscale) goingGrey();
        socket.emit('video-send', c.toDataURL());
			}, 33);
		}, false);

		var goingGrey = function() {
			var imageData = con.getImageData(0, 0, w, h);
			var data = imageData.data;
			for (var i = 0; i < data.length; i += 4) {
				var bright = 0.34 * data[i] + 0.5 * data[i + 1] + 0.16 * data[i + 2];
          		data[i] = bright;
          		data[i + 1] = bright;
          		data[i + 2] = bright;
			}
			con.putImageData(imageData, 0, 0);
		}

		// When the grey button is clicked, toggle the greyness indicator
		grey.addEventListener('click', function() {	greyscale = !greyscale; }, false);

	})
})();</script>
</body>
</html>
