<?hh 

$currentDir = dirname(__FILE__); 
require_once $currentDir.'/includes/xhp/init.php';
require_once $currentDir.'/DateRetriever/retriever.php';

$bsPad = "body {\npadding-top: 60px;\n}";
$html5Mark = "<!DOCTYPE html>";
$html = 
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title>Coding Calendar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>

    <link href="includes/bootstrap/css/bootstrap.css" rel="stylesheet"/>
    <style>
	{$bsPad}
    </style>
	<link href="includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet"/>

  	<script src="includes/bootstrap/js/html5shiv.js"></script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="">Coding Calendar</a>
        </div>
      </div>
    </div>
    <div class="container">

      <h1>Bootstrap starter template</h1>
      <p>Use this document as a way to quick start any new project.<br/> All you get is this message and a barebones HTML document.</p>

    </div> 

	<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
  	<script src="includes/bootstrap/js/html5shiv.js"></script>
    <script src="includes/bootstrap/js/bootstrap.js"></script>

  </body>
</html>;
echo $html5Mark;
echo $html;
