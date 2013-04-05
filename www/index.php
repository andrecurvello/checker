<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Checker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="components/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }

      h5.url {
        line-height: 0;
      }

    </style>
    <link href="components/bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
  </head>

  <body>

    <div id="checker" class="container-narrow">

    <div class="header">
      <h3 class="muted">Checker <small>- Keep your web apps up-to-date</small></h3>
    </div>

    <hr />

    <div class="server">
      <div class="label label-info"><h5 class="url span7"></h5></div>

      <table class="table table-hover table-condensed">

        <thead>
          <tr>
            <th>Apps</th>
            <th>Version</th>
            <th>Latest</th>
          </tr>
        </thead>

        <tbody class="tbl_content">
          <tr><td class="app"></td><td class="vs"></td><td class="latest"></td></tr>
        </tbody>

      </table>

      <hr />

    </div>

    <div id="loader" style="display:none"><img src="img/ajax-loader.gif"></div>

    </div> <!-- /container -->

    <script src="components/jquery/jquery.min.js"></script>
    <script src="components/transparency/dist/transparency.js"></script>
    <script src="js/display.js"></script>

  </body>
</html>
