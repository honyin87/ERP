<?php

	/*
	* CHY 2016/10/03
	* use RedBeanPHP 4.3.2
	* ORM for MySQL
	* 'xdispense' is to allow underscore in table naming conventions
	*
	*/
	require_once dirname(__FILE__).'/../vendor/autoload.php';
	use RedBeanPHP\R;

if (!class_exists('R')) {
	

	if(!R::testConnection()){
		
		R::setup( 'mysql:host=localhost;dbname=cms;charset=utf8','root', '1234' );
		R::ext('xdispense', function($type){
		 return R::getRedBean()->dispense($type);
		});
		
		if(isset($GLOBALS['debug']) && $GLOBALS['debug']){
			R::fancyDebug( TRUE );
		}
	}
}

//$mysqli = new mysqli('localhost','root','1234','cms');
//$link = mysql_connect('localhost', 'root', '1234');

//$GLOBALS['mysqli'] = $mysqli;
$GLOBALS['path'] = "/newCMS";



?>