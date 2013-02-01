<html>
  <head>
    <!--<script src="http://reader.pubhub.dk/scripts/reader-1.0.9/reader.js" type="text/javascript"></script>-->
    <script type="text/javascript" src="http://reader.pubhub.dk/scripts/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="/<?php print drupal_get_path('module', 'ebog_stream') . '/js/ebog_stream.reader.js'; ?>"></script>
    <script type="text/javascript">
      var reader = new Reader ({
        elementId: 'elib-reader',
        orderId: <?php print '\'' . $vars['order_number'] . '\''; ?>,
        sessionKeyUrl: '/' + <?php print '\'' . $vars['session_url'] . '\''; ?>
      });

      reader.Display();
    </script>
  </head>
  <style type="text/css">
    p
    {
        margin-top: 0px;
    }

    #reader-container
    {
        position:absolute;
        top:0;
        left:0;
        right:0;
        bottom:0;
    }

    #reader-header
    {
        background: #CCCCCC;
        height: 50px;
        left: 0;
        min-width:350px;
        position: absolute;
        right: 0;
        text-align: center;
        top: 0;
        z-index:2;
    }

    #reader-navigation-top
    {
        height: 50px;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        z-index:1;
    }

    #reader-content-container
    {
        background: #FFF;
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 50px;
    }

    #reader-content
    {
        height:100%;
        left:0;
        position:absolute;
        top:0;
        width:100%;
    }

    #reader-navigation-left
    {
        height:100%;
        left:0;
        position:absolute;
        top:0;
        width:100px;
    }

    #reader-navigation-right
    {
        height:100%;
        position:absolute;
        right:0;
        top:0;
        vertical-align:middle;
        width:100px;
    }

    #reader-button-previouspage
    {
        margin-left:42px;
        margin-top:-15px;
        position:absolute;
        top:50%;
        visibility:hidden;
    }

    #reader-button-nextpage
    {
        margin-left:42px;
        margin-top:-15px;
        position:absolute;
        top:50%;
    }

    #reader-book
    {
        color:#000000;
        /*font-size:400%;*/
        height:100%;
        left:100px;
        padding: 10px;
        position:absolute;
        right:100px;
        top:0;
    }

    #reader-bookmark, .reader-bookmark-rightadjusted, .reader-bookmark-centeradjusted
    {
        position:absolute;
        top:0;
        visibility:hidden;
    }

    #reader-bookmark-mobile
    {
        visibility:hidden;
        position:absolute;
        top:0px;
        /*left:37px;*/
        right:37px;
        height:50px;
        background:#FFE840;
    }

    #reader-note-mobile
    {
        visibility:hidden;
        position:absolute;
        top:0px;
        left:37px;
        /*right:37px;*/
        height:50px;
        background:#F21B1B;
    }

    .reader-bookmark-centeradjusted
    {
        margin-left:23px;
    }

    .reader-bookmark-mobile-centeradjusted
    {
        margin-left:0;
        left:37px;
    }

    .reader-bookmark-rightadjusted
    {
        margin-left:34px;
    }

    .reader-bookmark-mobile-rightadjusted
    {
        left:50%;
    }

    #reader-note, .reader-note-leftadjusted, .reader-note-centeradjusted
    {
        position:absolute;
        top:0;
        visibility:hidden;
    }

    .reader-note-centeradjusted
    {
        margin-left:23px;
    }

    .reader-note-mobile-centeradjusted
    {
        margin-left:0;
        right:37px;
    }

    .reader-note-leftadjusted
    {
        margin-left:16px;
    }

    .reader-note-mobile-leftadjusted
    {
        right: 50%;
    }

    #reader-modal-window
    {
        background:#CCCCCC;
        border:solid 2px #CCCCCC;
        color:#000000;
        display:none;
        height:380px;
        left:50%;
        margin-left:-150px;
        position:absolute;
        right:200px;
        /*top:10%;*/
        width:300px;
        z-index:100;
    }

    #reader-modal-window-header
    {
        background:#373232;
        color:#FFF;
        font-weight:bold;
        height:28px;
        left:0;
        padding-left:10px;
        padding-top:7px;
        position:absolute;
        width:290px;
    }

    #reader-modal-window-button-close
    {
        margin-right:10px;
        margin-top:2px;
        position:absolute;
        right:0;
    }

    #reader-modal-window-content
    {
        height:344px;
        overflow:auto;
        position:absolute;
        top:36px;
        width:300px;
    }

    #reader-modal-window-content a, #reader-modal-window-content a:hover
    {
        color:#000;
        text-decoration:none;
    }

    #reader-modal-window-header-title, #reader-modal-window-content
    {
        font-family:Helvetica,Arial,sans-serif;
        font-size:18px;
    }

    #spinner
    {
        left:50%;
        position:absolute;
        top:50%;
    }
  </style>
  <body>
    <div id="elib-reader"></div>
  </body>
</html>
