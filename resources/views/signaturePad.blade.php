<!doctype html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <title>Triton Security</title>
  <link rel="shortcut icon" href="{{asset('logo.png')}}" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <link rel="stylesheet" href="../css/signature-pad.css">
</head>
<body onselectstart="return false">

  <div id="signature-pad" class="signature-pad">
    <div class="signature-pad--body">
      <canvas></canvas>
    </div>
    <div class="signature-pad--footer">
      <div class="signature-pad--actions">
        <div class="column">
          <button type="button" class="button clear" data-action="clear">Törlés</button>
          <button type="button" class="button undo" data-action="undo">Visszavonás</button>

        </div>
        <!--<div class="column">
          <button type="button" class="button save">Mentés</button>
        </div>-->
      </div>
    </div>
  </div>
  <script src="../js/signature_pad.umd.js"></script>
  <script src="../js/app.js"></script>
</body>
</html>
