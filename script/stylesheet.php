<?php
use RedBeanPHP\R;

if(isset($stylesheet_type)){
	
	$styleSheets = R::find('uistylesheet',' style_type = ? ', 
                array( $stylesheet_type )
               );
			   
	if($styleSheets){
		foreach($styleSheets as $styleSheet){
				echo "<link href=\"$styleSheet->path\" rel=\"stylesheet\">\n";
			}
	}	
}
?>