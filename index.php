<html>
  <head>
  <?php
    if( $_GET["t"]){
      $time = $_GET["t"];
      $imgContent = 'http://andreus.valec.net/stuff/clicker/image.php?t='.$time;
      $titleContent = 'I made it in '. $time/1000 .' s! How about you?';
    }else{
      $imgContent = 'http://andreus.valec.net/stuff/clicker/sources/default.png';
      $titleContent = 'How fast are your reflexes?';
    }
  ?>
    <meta property="og:type"               content="game" />
    <meta property="og:title"              content="<?php echo $titleContent ?>" />
    <meta property="og:description"        content="Just click on circles and collect'em all!!" />
    <meta property="og:image"              content="<?php echo $imgContent ?>" />
    <meta property="og:image:width"        content="1200" />
    <meta property="og:image:height"        content="630" />
  </head>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Roboto', sans-serif;
        }
    </style>
    <script src="https://code.createjs.com/easeljs-0.8.2.min.js"></script>
    <script src="https://code.createjs.com/tweenjs-0.6.2.min.js"></script>
    <script>

        var circleR = 30;
        var circleCount = 20;
        var startTime;
        var textTimer = new createjs.Text("00.000 s", "100px Roboto", randomColor());
        var running = false;

        function getRandom(min, max) {
            return Math.random() * (max - min) + min;
        }

        function getX(object) {
            var genMin = -500;
            var genMax = 500;
            var min = object.x * -1;
            var max = document.body.clientWidth - object.x;

            var ran = getRandom(Math.max(genMin, min), Math.min(genMax, max));

            var result = object.x + ran;
            return result;
        }

        function getY(object) {
            var genMin = -500;
            var genMax = 500;
            var min = object.y * -1;
            var max = document.body.clientHeight - object.y;

            var ran = getRandom(Math.max(genMin, min), Math.min(genMax, max));

            var result = object.y + ran;
            return result;
        }

        function randomColor() {
            var r = Math.round(getRandom(0, 200));
            var g = Math.round(getRandom(0, 200));
            var b = Math.round(getRandom(0, 200));
            var a = 0.7;

            var result = "rgba(" + r + "," + g + "," + b + "," + a + ")"

            return result;
        }

        function init() {
            canvas = document.getElementById("demoCanvas");
            canvas.width = document.body.clientWidth; //document.width is obsolete
            canvas.height = document.body.clientHeight; //document.height is obsolete

            stage = new createjs.Stage("demoCanvas");
            createjs.Touch.enable(stage);

            createjs.Ticker.setFPS(60);
            createjs.Ticker.addEventListener("tick", tack);

             startCircle = new createjs.Shape();
             startText = new createjs.Text("Start", "100px Roboto", randomColor());
             strokeText = new createjs.Text("Start", "100px Roboto", "rgb(255,255,255)");


            stage.addChild(startCircle);
            stage.addChild(startText);
            stage.addChild(strokeText);

            startCircle.graphics.beginFill(randomColor()).drawCircle(0, 0, 200);
            strokeText.outline = 2;

           
            startCircle.x = canvas.width / 2;
            startCircle.y = canvas.height / 2;
            console.log("width: "+ canvas.width);
            console.log("height: "+canvas.height);
            
            startCircle.on("mousedown", function() {
                stage.removeChild(startCircle);
                stage.removeChild(startText);
                stage.removeChild(strokeText);
                createjs.Tween.removeAllTweens();
                createCircles();
            });


            stage.update();
            startTween(startCircle, startText, strokeText);
            //debugger;
        }

        function createTimer() {
            textTimer.x = canvas.width - textTimer.getBounds().width - 50;
            stage.addChild(textTimer);
        }

        function tack() {
            if (running) {
                var currentTime = (new Date()).getTime() - startTime;
                textTimer.text = (currentTime / 1000).toFixed(3) + " s";
                textTimer.time = ((currentTime / 1000).toFixed(3))*1000;
            }else{
	      startText.x = strokeText.x = startCircle.x - startText.getBounds().width / 2 ;
	      startText.y = strokeText.y = startCircle.y - startText.getBounds().height / 2 ;
            }
            stage.update();
        }

        function createCircles() {
            generateCircles(circleCount, clicked, tween);
            startTime = (new Date()).getTime();
            createTimer();
            running = true;
        }

        function startTween(startCircle, startText, strokeText) {
            var xx = getX(startCircle);
            var yy = getY(startCircle);
            var sx = xx - startText.getBounds().width / 2 - 15;
            var sy = yy - startText.getBounds().height / 2 - 25;
            var time = 10000;
            createjs.Tween.get(startCircle, {override: true}).to({x: xx, y: yy}, time, createjs.Ease.getPowInOut(1)).call(startTween, [startCircle, startText, strokeText]);
        }

        function tween(circle) {
            createjs.Tween.get(circle, {override: true}).wait(1).to({x: getX(circle), y: getY(circle)}, 1000, createjs.Ease.cubicInOut).call(tween, [circle]);
        }
        
        function bgTween(circle){
            createjs.Tween.get(circle, {override: true}).to({x: getX(circle), y: getY(circle), alpha:0.15}, getRandom(2000, 6000), createjs.Ease.quadInOut).call(bgTween, [circle]);
        }
        
        function shareTween(circle){
	    createjs.Tween.get(circle, {override: true}).to({x: getX(circle), y: getY(circle), alpha:1}, getRandom(4000, 8000), createjs.Ease.quadInOut).call(shareTween, [circle]);
        }

        function clicked(circle) {
            stage.removeChild(circle);

            if (stage.children.length == 1) {
                finished();
            }
        }

        function finished() {
            running = false;
            
            createjs.Tween.get(textTimer, {override: true}).to({x: canvas.width / 2 - textTimer.getBounds().width / 2, y: canvas.height / 3 - textTimer.getBounds().height / 2}, 500, createjs.Ease.getPowInOut(1));
            createBgCircles();
            createFBCircle();
            createRestartCircle();
        }
        
        function share(){ 
	  FB.ui({
	    method: 'share',
	    href: 'http://andreus.valec.net/stuff/clicker?t='+textTimer.time+'&nocache='+new Date().getTime(),
	  }, function(response){});
        }
        
        function createFBCircle(){
	  var fbCircle = new createjs.Bitmap("fb.png");
	  fbCircle.x = canvas.width/2-100;
	  fbCircle.y = canvas.height/2;
	  fbCircle.alpha = 0;
	  fbCircle.on("mousedown", function(event) {
	    share();
	  });
	  stage.swapChildrenAt(0, stage.children.length - 1);
	  stage.addChildAt(fbCircle, stage.children.length - 2);
	  shareTween(fbCircle);
        }
        
        function createRestartCircle(){
	  var restartCircle = new createjs.Bitmap("restart.png");
	  restartCircle.x = canvas.width/2+100;
	  restartCircle.y = canvas.height/2;
	  restartCircle.alpha = 0;
	  restartCircle.on("mousedown", function(event) {
	    location.reload();
	  });
	  stage.addChildAt(restartCircle, stage.children.length - 2);
	  shareTween(restartCircle);
        }
        
        function createBgCircles(){
	  generateCircles(5, null, bgTween, bgTopCircle);
	  generateCircles(5, null, bgTween, bgBottomCircle);
	  generateCircles(5, null, bgTween, bgLeftCircle);
	  generateCircles(5, null, bgTween, bgRightCircle);
        }
        
        function bgTopCircle(circle){
	  circle.y = 0;
	  circle.alpha = 0;
        }
        function bgBottomCircle(circle){
	  circle.y = canvas.height;
	  circle.alpha = 0;
        }
        function bgLeftCircle(circle){
	  circle.x = 0
	  circle.alpha = 0;
	}
	function bgRightCircle(circle){
	  circle.x = canvas.width;
	  circle.alpha = 0;
	}
        
        function generateCircles(count, clickCallback, tweenCallback, circleCallback){
	  for (var i = 0; i < count; i++) {
                var color = randomColor();
                var circle = new createjs.Shape();
                circle.graphics.beginFill(color).drawCircle(0, 0, circleR);
                circle.x = canvas.width / 2;
                circle.y = canvas.height / 2;
                circle.num = i;
                if(circleCallback){
		  circleCallback(circle);
                }
                if(clickCallback){
		  circle.on("mousedown", function(event) {
                    clickCallback(event.target)
		  });
                }

                stage.addChild(circle);
                if(tweenCallback){
		  tweenCallback(circle);
                }
            }
        }


    </script>
    
    <body onload="init();" style="display: table-cell;">
    
    
    <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1036493763071256',
      xfbml      : true,
      version    : 'v2.7'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
    
    
        <canvas id="demoCanvas"></canvas>
    </body>
</html>