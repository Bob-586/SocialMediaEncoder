<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-touch-fullscreen" content="yes" />
<style type="text/css">
  html { width:100%; height: 100% }
  body { width:100%; height: 100%; margin: 0px; padding: 0px }
  div#qrdiv {width:200px; height:200px}
</style>

<script type="text/javascript" src="./dist/qrenc3.min.js"></script>
</head>
<body onload="setupqr()">
    
<div id="qrdiv" style="background:lightgray; position:absolute; width:360px; height:360px">
    Javascript QR Encoder, Copyright 2010, tz@execpc.com, released under GPLv3.
    [Right click on QR, then Save as Image, to download it.] <a href="index.html">Encode new message</a>
  <form name="qrinp">
    
    <label for="ecc">ECC Level (1-4)</label>
    <input type="numeric" name="ECC" id="ecc" value="1" size="1">
    <label for="go">Make the QR Image</label>
    <input type="button" value="Go" id="go" onclick="doqr()"/>
    <br>
    CE:<textarea name="qrinput" size=2953><?= $_POST['enc'] ?></textarea><br>
  </form>
    <canvas id="qrcanv">No Canvas Support?</canvas>
</div>
           
</body>
</html>
