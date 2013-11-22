<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="da" lang="da" dir="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="no-cache, no-store" http-equiv="cache-control" />
    <meta content="no-cache" http-equiv="pragma" />
    <meta content="-1" http-equiv="expires" />
    <link rel="stylesheet" type="text/css" href="https://reader.pubhub.dk/scripts/reader-1.3.4/css">
    <script type="text/javascript" src="https://reader.pubhub.dk/scripts/reader-1.3.4/js"></script>
    <script type="text/javascript">
      var reader;
        function InitReader() {
          reader = new Reader ({
            elementId: 'reader-container',
            orderId: '<?php print $vars['order_number']; ?>',
            sessionKeyUrl: window.location.protocol + '//' + window.location.host + '/<?php print $vars['session_url']; ?>',
            notesEnabled: false
          });
          reader.Display();
      }
    </script>
    <title>eReolen Stream - <?php print $vars['title'] ?></title>
  </head>
  <body onload="javascript:InitReader();">
    <div id="reader-container"></div>
  </body>
</html>
