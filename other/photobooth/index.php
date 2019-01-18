<?php

require_once('config.inc.php');

?>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Photobooth</title>
	<link rel="stylesheet" href="<?php echo $config['baseUrl']; ?>/resources/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo $config['baseUrl']; ?>/resources/css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $config['baseUrl']; ?>/resources/css/neon.css" />
    <link rel="stylesheet" href="<?php echo $config['baseUrl']; ?>/resources/css/main.css" />
    <link rel="stylesheet" href="<?php echo $config['baseUrl']; ?>/resources/css/fonts.css" />



</head>
<body class="">
<!--
<div class="container-full">
    <div class="row">
        <div class="col-lg-12 webcam"></div>
    </div>
</div>
-->
<div id="mainDiv" class="container-full">
        <div id="messages" class="efx-0-neon centerLogo"><p><a href="#"><img src="../../resources/img/logo_ge.png"/></a></p></div>
</div>

<div class="loaderInner">
    <div class="spinner" style="display: none;">
        <i class="fa fa-cog fa-spin"></i>
    </div>

    <div id="counter" style="opacity: 1; transform: matrix(1, 0, 0, 1, 0, 0);"></div>
    <div class="loading">Something went wrong. Please try it again<a class="btn" href="/photobooth1/">Reload Page</a></div>
</div>


<!-- Jquery library -->
	<script type="text/javascript" src="<?php echo $config['baseUrl']; ?>/resources/js/jquery.js"></script>
    <!-- Popper js -->
	<script type="text/javascript" src="<?php echo $config['baseUrl']; ?>/resources/js/popper.js"></script>
    <!-- Bootstrap js -->
    <script type="text/javascript" src="<?php echo $config['baseUrl']; ?>/resources/js/bootstrap.js"></script>
	<!-- Lang Translations -->
	<script type="text/javascript" src="<?php echo $config['baseUrl']; ?>/lang/<?php echo $config['language']; ?>.js"></script>
    <!-- First, include the Webcam.js JavaScript Library -->
    <script type="text/javascript" src="<?php echo $config['baseUrl']; ?>/resources/js/webcam.min.js"></script>
    <!-- The neon text effect -->
    <script type="text/javascript" src="<?php echo $config['baseUrl']; ?>/resources/js/neon_text.js"></script>
    <!-- Fit div to text -->
    <script type="text/javascript" src="<?php echo $config['baseUrl']; ?>/resources/js/jquery.textfill.js"></script>

    <!-- Configure a few settings and attach camera -->
    <script language="JavaScript">
        Webcam.set({
            width: <?php echo $config['webcamWidth']; ?>,
            height: <?php echo $config['webcamHeight']; ?>,
            image_format: 'jpeg',
            jpeg_quality: <?php echo $config['webcamQuality']; ?>,
            constraints: {
                width: { exact: <?php echo $config['webcamWidth']; ?> },
                height: { exact: <?php echo $config['webcamHeight']; ?> }
            }
        });


		// idle timeout
		var idleTime = -1;
		var messagesTimer = false;
		$(document).ready(function () {

		    // Center the logo for the start
            var newPos = getCenterPositionOnScreen();
            $("#messages").css({top: newPos[0], left: newPos[1], position:'absolute'});


		    //Increment the idle time counter every minute.
		    setInterval(showRandomMessage,  <?php echo $config['minTimeBetweenMessages']; ?>*1000);

		    //Zero the idle timer on mouse movement.
		    $(this).click(function (e) {
                takePhoto();
		    });
	//	    $(this).keypress(function (e) {
	//	        idleTime = -1;
	//	       	clearScreen();
	//	        messagesTimer = false;
	//	    });
		});

		// Start the 3 2 1
		function takePhoto()
        {
            // Clear messages from screen
            clearScreen();
            // Disable timer
            messagesTimer = false;
            $("#mainDiv").hide();

        }
        // Show random messages on screen
		function showRandomMessage()
		{
			(function loop() {
				if (messagesTimer == true)
				{
					var max = <?php echo $config['maxTimeBetweenMessages']; ?>;
					var min = <?php echo $config['minTimeBetweenMessages']; ?>;
					var rand = (Math.random() * (max - min) + min) * 1000;
			    	setTimeout(function() {
			            	displayMessageFromBackend();
			        	    loop();  
			    	}, rand);
			    }
			}());
		}

		function log(message)
		{
			console.log(message);
		}

		function clearScreen() 
		{
			log('Clear screen');
			$("#messages").fadeOut(<?php echo $config['fadeOutText']; ?>*1000).html("");
		}

		function displayMessageFromBackend()
		{
			var jqxhr = $.getJSON( "messages.php", function(data) {
			  console.log( "grabMessageFromBackend: " + data.message);
			  drawMessage(data.message);
			})
		  	.done(function() {
				console.log( "second success" );
			})
			.fail(function() {
			    console.log( "Error getting message from backend." );
			})
			.always(function() {
				console.log( "complete" );
			});
		}

		function drawMessage(message)
		{
		    // First draw the text on the div
		    $("#messages").fadeOut(<?php echo $config['fadeOutText']; ?> * 1000 ).hide().html('<p><a href="#">' +  message + '</a></p>');

		    // Set random neon effect
            var randomNeon =  Math.floor(Math.random() *3) ;
            // Remove all effects of css
            $("#messages").removeClass('efx-0-neon').removeClass('efx-1-neon').removeClass('efx-2-neon');
            // Apply the cute neon effect
            $("#messages").addClass('efx-' + randomNeon + '-neon');

            // Grab a random rotation and rotate (done here so the new div position is updated)
            var rotation = getRandomRotationOnScreen();
            // Rotate the div
            $("#messages").css({'transform' : 'rotate('+ rotation +'deg)'});

		    // Now that de div is with dimensions calculated we can grab the new random position
            var newPos = getRandomPositionOnScreen();

            // Grab a random text color
            var color0 = getRandomHexColor();
            var color1 = getRandomHexColor();
            var color2 = getRandomHexColor();

            // Set new position of div, rotate, fit it to the div and fade it in
            $("#messages").css({top: newPos[0], left: newPos[1], position:'absolute'}).textfill({maxFontPixels: 10}).neonText({
                textColor: color0,
                neonHighlight:'white',
                neonHighlightColor:color1,
                neonHighlightHover:color2,
                }
            ).fadeIn(<?php echo $config['fadeInText']; ?>);

        }

        function getRandomHexColor()
        {
            var color = Math.floor(Math.random()*16777215).toString(16);
            log("Random color: " + color);
            return color;
        }

		function getRandomRotationOnScreen()
        {
            // Calculate a rotation value, 70 is the max rotation allowed
            var rotation = Math.floor(Math.random() *<?php echo $config['maxTextRotation']; ?>)

            var flipResult = Math.random();
            if(flipResult <= 0.5)
            {
                rotation = rotation * -1;
            }
            log("Rotation is: " + rotation);
            return rotation;
        }

		function getRandomPositionOnScreen(){
    
		    // Get viewport dimensions
            var wh = $(document).height();
		    var ww = $(document).width();

		    // Div dimensions
            var dh = $("#messages").height();
            var dw = $("#messages").width();


            // Get a random position where div fits inside screen.
            var nh = Math.floor(Math.random() * (wh - dh));
		    var nw = Math.floor(Math.random() * (ww - dw));


		    log("Random text position height: " + nh + ", width: " + nw + ", div height is: " + dh + ", div width is: " + dw + ", screen height is: " + wh + ", screen width is: " + ww );
		    return [nh,nw];
		}


        function getCenterPositionOnScreen(){

            // Get viewport dimensions
            var wh = $(document).height()/2;
            var ww = $(document).width()/2;

            // Div dimensions
            var dh = $("#messages").height()/2;
            var dw = $("#messages").width()/2;


            // Get a random position where div fits inside screen.
            var nh = Math.floor((wh - dh));
            var nw = Math.floor((ww - dw));


            log("Random text position height: " + nh + ", width: " + nw + ", div height is: " + dh + ", div width is: " + dw + ", screen height is: " + wh + ", screen width is: " + ww );
            return [nh,nw];
        }


	</script>
</body>
</html>
