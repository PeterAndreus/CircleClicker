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
    <meta property="fb:app_id"        content="1036493763071256" />
  </head>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Roboto', sans-serif;
        }
        .hidden{
	  display:none;
        }
        .donateBtn{
          position: absolute;
	  top: 95%;
	  right: 0;
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
        
        function recalculateCanvasDimensions(){
            canvas.width = document.body.clientWidth; //document.width is obsolete
            canvas.height = document.body.clientHeight; //document.height is obsolete
        }

        function init() {
            canvas = document.getElementById("circleCanvas");
	    window.addEventListener('orientationchange', function(){
	      recalculateCanvasDimensions()}
	    );        
            recalculateCanvasDimensions();

            stage = new createjs.Stage("circleCanvas");
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
            
	    startText.x = strokeText.x = startCircle.x - startText.getBounds().width / 2 ;
	    startText.y = strokeText.y = startCircle.y - startText.getBounds().height / 2 ;
	      
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
            
            document.getElementById('donateForm').classList.remove('hidden');
        }
        
        function share(){ 
	  FB.ui({
	    method: 'share',
	    href: 'http://andreus.valec.net/stuff/clicker/redirect.php?t='+textTimer.time+'&nocache='+new Date().getTime(),
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
    
    <!-- FB SHARE -->
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
    
    <!-- FB SHARE -->
    
        <canvas id="circleCanvas"></canvas>
        
        
    <!-- DONATE -->
        <form id='donateForm' class="donateBtn hidden" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

    <!-- DONATE -->
    
    <!-- GOOGLE ANALYTICS -->
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-83064941-1', 'auto');
  ga('send', 'pageview');

    </script>

    <!-- GOOGLE ANALYTICS -->
    </body>
</html>