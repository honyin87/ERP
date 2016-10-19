<?php
include_once(dirname(__FILE__).'/../../config/config.php');
use RedBeanPHP\R;
if(isset($dataTableID)){

	/*$query="select * from ui_table where table_id='$dataTableID';";
	$result=$mysqli->query($query);
	$table_id="";
	$table_key="";
	$table_query="";*/
	/*if($result){
	$count = 0;
	$num = $result->num_rows;
	while ($count< $num ){
			$row = $result->fetch_array(MYSQLI_BOTH); 
			$table_id = $row['table_id'];
			$table_key = $row['table_key'];
			$table_query = $row['table_query'];
			
			$count++;
		}
	}*/
			$table = R::findOne('uitable',' table_name = ? ', 
                array( $dataTableID )
               );


}
?>

		<table class="table table-hover" id="<?=$table->table_name?>">
			<thead>
              <tr>
<?php
	/*$query="select * from ui_table_column where table_key='$table_key'";
	$result=$mysqli->query($query);
	if($result){
	$count = 0;
	$num = $result->num_rows;
	while ($count< $num ){
			$row = $result->fetch_array(MYSQLI_BOTH); 
			$column = $row['column_text'];
			echo "				<th>$column</th>\n";
			$count++;
		}
	}*/

			   
	$columns = $table->ownUicolumn;
	//echo json_encode($table);
	foreach($columns as $column){//echo json_encode($column);
			echo "				<th>$column->column_text</th>\n";
		}
	
?>
              </tr>
            </thead>
			</table>
			<script>
			<?php 	echo "$('#$table->table_name').DataTable({\n" ;
					echo "\"autoWidth\": false,\n";
					//echo "\"scrollX\": true,\n";
					//echo "\"scrollCollapse\": true,\n";
					echo "\"processing\": true,\n";
					echo "\"serverSide\": true,\n";
					echo "\"ajax\": \"script/content/dataTableContent.php?tableID=$table->table_name\"\n";
					echo "});\n"; ?>
			</script>