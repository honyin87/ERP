<meta charset="utf-8" />
<!DOCTYPE html>
<html>
<head>
    <title>UTF-8 encoding test</title>
</head>
<body>
<?php

require_once('script/init.php');
require_once('config/config.php');
header('Content-type: text/html; charset=utf-8');

	
	/*$container = R::dispense('uicontainer');

	$table= R::load( 'uitable', 1 );
	$container->title="Javascript";
	$container->index=1;
	$container->ownUitable = $table;
	
     
    R::store($container);*/
		
	$langtext = R::dispense('langtext');
	$langtext->text = 'txt_welcome';
	
	$langtranslation = R::dispense('langtranslation');
	$langtranslation->language = 'en-US';
	$langtranslation->translation = 'welcome';
	
	$langtranslation2 = R::dispense('langtranslation');
	$langtranslation2->language = 'zh-CN';
	$langtranslation2->translation = '欢迎';
	
	$langtext->ownLangtranslation = array($langtranslation,$langtranslation2);
	//R::store($langtext);
	echo $langtranslation2->translation;
	//echo '欢迎';

?>
	</body>
</html>