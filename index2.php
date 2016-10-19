<?php

require_once('script/init.php');
require_once('config/config.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
$stylesheet_type='PAPER';
include(dirname(__FILE__).'/script/stylesheet.php');

$javascript_type='MAIN';
include(dirname(__FILE__).'/script/javascript.php');
?>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
} );
</script>
</head>
	<body>
			<nav class="navbar navbar-default">
			  <div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
				  <a class="navbar-brand" href="#">WebSiteName</a>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
					  <li class="active"><a href="#">Home</a></li>
					  <li><a href="#">Page 1</a></li>
					  <li><a href="#">Page 2</a></li> 
					  <li><a href="#">Page 3</a></li> 
					  <li class="dropdown">
						  <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">Paper <span class="caret"></span></a>
						  <ul class="dropdown-menu" aria-labelledby="download">
							<li><a href="http://jsfiddle.net/bootswatch/ndax7sh7/">Open Sandbox</a></li>
							<li class="divider"></li>
							<li><a href="./bootstrap.min.css">bootstrap.min.css</a></li>
							<li><a href="./bootstrap.css">bootstrap.css</a></li>
							<li class="divider"></li>
							<li><a href="./variables.less">variables.less</a></li>
							<li><a href="./bootswatch.less">bootswatch.less</a></li>
							<li class="divider"></li>
							<li><a href="./_variables.scss">_variables.scss</a></li>
							<li><a href="./_bootswatch.scss">_bootswatch.scss</a></li>
						  </ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
					  <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
					  <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
					</ul>
			  </div>
			  </div>
			</nav>
			
			
			
			 
		<div class="container container-table">

			<div class="col-lg-12">
         <?php
		$dataTableID='test_table';
		include(dirname(__FILE__).'/script/content/dataTable.php');
		?>
        </div>
		
		</div>

		<div class="panel-footer navbar-fixed-bottom">
		<?php //throw new Exception("myException test");//require('123');
			echo 'PHP version: ' . phpversion() . ' | RedBean PHP version: '.R::getVersion() .' | MySQL version: ' . R::getCell('SELECT VERSION() as mysql_version');
		?>
		</div>
	</body>
</html>