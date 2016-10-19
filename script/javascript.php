<?php
use RedBeanPHP\R;

if(isset($javascript_type)){
	
	$javascripts = R::find('uijavascript',' javascript_type = ? ', 
                array( $javascript_type )
               );
			   
	if($javascripts){
		foreach($javascripts as $javascript){
				echo "<script src=\"$javascript->path\"></script>\n";
			}
	}	

	
	/*$table = R::dispense('uitable');
    list($id,$path,$javascriptType) = R::dispense('uicolumn',3);
	
	$javascriptType->column_text = "Javascript Type";
	$javascriptType->column_name = "javascript_type";
	
	$path->column_text = "Path";
	$path->column_name = "path";
	
	$id->column_text = "ID";
	$id->column_name = "id";
    
    //replaces entire list
	$table->table_name = "test_table";
	$table->query = 'SELECT * FROM ui_javascript';
    $table->ownUicolumn= array($id,$path,$javascriptType);
     
    R::store($table);*/
}
?>