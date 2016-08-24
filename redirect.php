 
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
  <script type="text/javascript">
    function redirect(){
      window.location = "http://andreus.valec.net/stuff/clicker"
    }
  </script>
  <body onload="redirect();">
  </body>
</html>