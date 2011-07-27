<?php
require_once('facebook/fbmain.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Parabox Game</title>
<meta charset="utf-8" />
<link href="style/parabox.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://jqueryui.com/themes/base/jquery.ui.all.css">
<script type="text/javascript" src="js/excanvas.compiled.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/sprites.js"></script>
<script>
var animation;
var lastMousePos = 0;
var isLoaded = false; // Our flag
var Keys = {
		arrowLeft: 37,
		arrowUp: 38,
		arrowRight: 39,
		arrowDown: 40,
		space: 32,
		escape: 27,
		x: 88
	};

//http://stackoverflow.com/questions/3075577/convert-mysql-datetime-stamp-into-javascripts-date-format
Date.createFromMysql = function(mysql_string)
{ 
   if(typeof mysql_string === 'string')
   {
      var t = mysql_string.split(/[- :]/);

      //when t[3], t[4] and t[5] are missing they defaults to zero
      return new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);          
   }

   return null;   
}

/**
 * You first need to create a formatting function to pad numbers to two digits…
 **/
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}

/**
 * …and then create the method to output the date string as desired.
 * Some people hate using prototypes this way, but if you are going
 * to apply this to more than one Date object, having it as a prototype
 * makes sense.
 **/
Date.prototype.toMysqlFormat = function() {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + " " + twoDigits(this.getUTCHours()) + ":" + twoDigits(this.getUTCMinutes()) + ":" + twoDigits(this.getUTCSeconds());
};

$(document).ready(function() {
	
	var canvas = document.getElementById("paraboxCanvas");
	if (typeof canvas.getContext == "undefined"){
		// Try using exCanvas for Internet Explorer
		try {
			G_vmlCanvasManager.initElement(canvas);
		}
		catch (error) {
			alert("Sorry, your browser doesn't support CANVAS");
			return;
		}
	}

    animation = new Animation(canvas);
    animation.loadLevel(1);
    animation.draw();
	$(window).keydown(function(e) {
		if (e.keyCode == Keys.arrowRight) {
			animation.moveRight();
		} else if (e.keyCode == Keys.arrowLeft) {
			animation.moveLeft();
		} else if (e.keyCode == Keys.space) {
			animation.start();
			e.preventDefault();
		} else if (e.ctrlKey && e.keyCode == Keys.x) {
			var answer = animation.showDialog(Status.exit);
			if (answer){
				animation.stop();
			}
		}
	});
	$(window).keyup(function(e) {
	});

	/*$('#paraboxCanvas').mousemove(function(event) {
		if (event.clientX < lastMousePos)
			animation.moveLeft();
		else if (event.clientX > lastMousePos)
			animation.moveRight();
		lastMousePos = event.clientX;
	});*/

	$('#paraboxCanvas').click(function(event) {
		if (!animation.animationRunning)
			animation.start();
		lastMousePos = event.clientX;
	});
	
});

function transferScore(level, score, successFunc) {
	var name = "Parabox";
	var hrefTitle = 'I have just reached level ' + String(level) + ' and scored ' + String(score)
	var hrefLink = 'http://parabox.keensocial.com';
	var userPrompt = 'Beat me if you can!';
	var desc = "It's exciting!";
	FB.ui({ method : 'feed', 
    		name: name,
        	message: userPrompt,
        	link   : hrefLink,
	        caption: hrefTitle,
    	    description: desc
      	  },
      		function(uiResponse) {
        		if (uiResponse && uiResponse.post_id) {
        			FB.api('/me', function(info) {
          	        	addScoreToDb(info.id, level, score);
             	        successFunc();
          	        });
          		} else {
              		alert('Post was not published.');
          		}
      		}
	  );
}

function addScoreToDb(userName, level, score) {
	var date = new Date();
	var url = "index.php?action=user&addScore&userName=" + userName + "&level=" + level + "&score=" + score + "&time=" + date.toMysqlFormat();
	$.get(url);
}

function streamPublish(name, desc, hrefTitle, hrefLink, userPrompt){        
    FB.ui({ method : 'feed', 
        	name: name,
            message: userPrompt,
            link   :  hrefLink,
            caption:  hrefTitle,
            description: desc
          },
          function(response) {
              if (response && response.post_id) {
                  var date = new Date();
                  var url = "index.php?action=user&addScore&userName=" + $user + "&level=" + level + "&score=" + score + "&time=" + date.toMysqlFormat();
                  $.get(url);
              } else {
                  alert('Post was not published.');
              }
          }
   	   );
}

</script>
</head>
<body>
<div style="position: relative; float:none;" >
<div style="float: left; width: 140px; margin-right:20px;" >
<div>
<div style="float: left;">Score:</div>
<div style="float: right;" id="score"></div>
<div style="clear: both"></div>
</div>
<div>
<div style="float: left;">Balls:</div>
<div style="float: right;" id="balls"></div>
<div style="clear: both"></div>
</div>
<div>
<div style="float: left;">Level:</div>
<div style="float: right;" id="level"></div>
<div style="clear: both"></div>
</div>
<div style="height: 200px;">
</div>
<div>
<div style="float: left;">Start:</div>
<div style="float: right;" id="level">Space</div>
<div style="clear: both"></div>
</div>
<div>
<div style="float: left;">Exit:</div>
<div style="float: right;" id="level">Ctrl + x</div>
<div style="clear: both"></div>
</div>
<div>
<div style="float: left;">Move:</div>
<div style="clear: both"></div>
</div>
<div>
<div style="float: right;" id="level">Right Arrow</div>
<div style="clear: both"></div>
</div>
<div>
<div style="float: right;" id="level">Left Arrow</div>
<div style="clear: both"></div>
</div>

</div>

<div id="paraboxDiv">
<canvas class="paraboxCanvas" id="paraboxCanvas" width="480px" height="400px" style="z-index: 0;">
<!-- Fallback code goes here -->
</canvas>
</div>
</div>

<audio id ="pingpong">
<source src="audio/pingpong.wav" ></source>
</audio>

<div id="fb-root"></div>
    <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
     <script type="text/javascript">
       FB.init({
         appId  : '<?=$fbconfig['appid']?>',
         status : true, // check login status
         cookie : true, // enable cookies to allow the server to access the session
         xfbml  : true  // parse XFBML
       });
       
     </script>
</body>
</html>
