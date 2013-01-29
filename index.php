<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Checker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Checker</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <h1>Checker: Keep your web apps up-to-date</h1>

        <table class="table table-striped table-bordered table-hover table-condensed" style="width:500px">

        <thead>
            <tr>
                <th>Apps</th>
                <th>Version</th>
                <th>Latest</th>
            </tr>
        </thead>
        
        <tbody id="tbl_content">
        </tbody>

        </table>

    <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>

    </div> <!-- /container -->

    <script src="js/jquery.js"></script>

    <script type="text/javascript">
        
    $(document).ready(function(){
    
    });    
        
    $("#loader").ajaxStart(function(){
        $(this).show();
    });
    $("#loader").ajaxStop(function(){
        $(this).hide();
    });     
    
    
    <?php 
    include('config.php');
    foreach ($servers as $key => $value) {
    ?>
    
    $.ajax({
        type: "GET",
        cache: false,
        dataType: "text",
        url: "call_probe.php?server=<?php echo $value ?>",
        success: function (data) {
        
            json_version = JSON.parse(data);

            $.each(json_version, function(key, value){
                $.each(json_version[key], function(app, version){
                    if(version["local"] == version["remote"]) {
                        var label = "label-success";
                    }
                    else if(version["local"] == 0 || version["remote"] == 0) {
                        var label = "";
                    }
                    else {
                        var label = "label-warning";
                    }
                   
                    $("#tbl_content").append("<tr>"+
                    "<td>"+app+"</td>"+
                    "<td><span class=\"label "+label+"\">"+version["local"]+"</span></td>"+
                    "<td>"+version["remote"]+"</td>/tr>");
                });
            });
            
        },
        error: function (XMLHttpRequest, textStatus, errorThrows) {
            console.log("error");
        }
    });

    <?php } ?>

    </script>

  </body>
</html>
