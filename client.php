
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
    var
      v = document.getElementById('v'),
      c = document.getElementById('c');
    var ctx = c.getContext("2d");

	window.addEventListener('DOMContentLoaded', function() {
    var socket = io.connect('localhost:3000');
    socket.on('video-receive', function(data){
      var image = new Image();
      image.onload = function() {
        c.width = image.width;
        c.height = image.height;
          ctx.drawImage(image, 0, 0);
      };
      image.src = data;
    });
	});
})();</script>
</body>
</html>
