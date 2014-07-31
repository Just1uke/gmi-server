  <!doctype html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <!-- styles used on all pages -->
    <link href="/staticLIVE/css/style.css" rel="stylesheet" type="text/css" />

    <!-- styles needed by DataTables -->
    <link href="/staticLIVE/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    
    <!-- styles needed by jScrollPane -->
    <link type="text/css" href="/staticLIVE/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />

    <!-- JQuery -->
    <script type="text/javascript" src="/staticLIVE/js/jquery-1.6.4.min.js"></script>
    
     <!-- the mousewheel plugin - optional to provide mousewheel support -->
    <script type="text/javascript" src="/staticLIVE/js/jquery.mousewheel.js"></script>

    <!-- the DataTables script -->
    <script type="text/javascript" src="/staticLIVE/js//datatables/jquery.dataTables.min.js"></script>

    <!-- the jScrollPane script -->
    <script type="text/javascript" src="/staticLIVE/js/jquery.jscrollpane.min.js"></script>
    
    <!-- Globalize (https://github.com/jquery/globalize) -->
    <script type="text/javascript" src="/staticLIVE/js/globalize/globalize.js" charset="utf-8"></script>
    <script type="text/javascript" src="/staticLIVE/js/globalize/globalize.cultures.js" charset="utf-8"></script>
    
    <!-- Market specific JavaScript -->
    <script type="text/javascript" src="/staticLIVE/js/market.js"></script>
    
    <script type="text/javascript">
      $(document).ready(function() {
        if (typeof AnarchyOnline == "undefined") {
          AnarchyOnline = new Object();
          AnarchyOnline.AcceptLanguage = [];
          AnarchyOnline.IsInGame = false;
        }
        Globalize.culture(AnarchyOnline.AcceptLanguage);
      })
    </script>
    
    <link rel="alternate" media="print" href="item_view.html">
    <title>Deep Stats</title>

    <script type="text/javascript">
      
    </script>      
    </head>
  <body onkeyup="hotKeys(event)">
    <div id="menu">
          <div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
          <div class="url_tab"><a href="/marketLIVE/stats"><p class="pHeader">Statistics</p></a></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
          <div class="url_tab"><a href="/marketLIVE/mail"><p class="pHeader">Deliveries</p></a></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
          <div class="url_tab"><a href="/marketLIVE/search"><p class="pHeader">Search</p></a></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
          <div class="url_tab"><a href="/marketLIVE/log"><p class="pHeader">Log</p></a></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
          <div class="url_tab"><a href="/marketLIVE/my_orders"><p class="pHeader">My Orders</p></a></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_right.png" /></div>
          <div class="url_tab"><a href="/marketLIVE/inventory"><p class="pHeader">Inventory</p></a></div>
          <div class="picture_tab"><img src="/staticLIVE/images/tab_left.png" /></div>
    </div>
    <div id="page">
      <div class="marketHeader">
        <div class="icon">
          <img src="/staticLIVE/images/log.png"/>
        </div>
        <div class="pageName">
          <h1>Deep Statistics</h1>
        </div>
      </div>
      <div class="header">
        <h2>This should be fun</h2>
      </div>
      <div>

      </div>
    </div>
  </body>
</html>

