<?php
include_once(dirname(__FILE__).'/../../config/config.php');
use RedBeanPHP\R;

if (isset($_GET['tableID'])) {
    //echo $_GET['tableID'];

	echo json_encode(dataOutput($_GET['tableID'],$_GET));
}


function dataOutput($tableID,$request){
		//$data = array(array('1',"test","$tableID"),array('2',"test2","testing2"));
		$data= runQuery($tableID,$request);
		//return $data;
		$recordsCount = totalRecords($tableID);
		
		$recordsFiltered = filteredRecords($tableID,$request);
		//return $recordsCount;
		/*
		 * Output
		 */
		return array(
			"draw"            => isset ( $request['draw'] ) ?
				intval( $request['draw'] ) :
				0,
			"recordsTotal"    => $recordsCount,
			"recordsFiltered" => $recordsFiltered,
			"data"            => $data 
		);
}

function runQuery($tableID,$request){

	$tableQuery = "";
	$tableKey = "";
	
	$table = R::findOne('uitable',' table_name = ? ', 
                array( $tableID )
               );
	$tableQuery = $table->query;
	$tableKey = $table->id;
			   
	$TMP_TABLE = "TMP_TABLE";
	$orderBy = order ( $request, getColumns($tableKey),$TMP_TABLE) ;
	$where = filter( $request, getColumns($tableKey),$TMP_TABLE );
	$limit = limit($request);
	
	if(isset($tableQuery)){
		$tableQuery = str_replace_last(";","",$tableQuery);
		//order by
		$tableQuery = "SELECT * FROM ($tableQuery) $TMP_TABLE ";
		$tableQuery = $tableQuery.$where.$orderBy.$limit;

		$dataResult = array_values_recursive(R::getAll($tableQuery));
		return $dataResult;
	}
}

function array_values_recursive( $array ) {
    $array = array_values( $array );
    for ( $i = 0, $n = count( $array ); $i < $n; $i++ ) {
        $element = $array[$i];
        if ( is_array( $element ) ) {
            $array[$i] = array_values_recursive( $element );
        }
    }
    return $array;
}

function filteredRecords($tableID,$request){

	$tableQuery = "";
	$tableKey = "";
	$TMP_TABLE = "TMP_TABLE";

	$table = R::findOne('uitable',' table_name = ? ', 
                array( $tableID )
               );
	$tableQuery = $table->query;
	$tableKey = $table->id;
	
	if(isset($tableQuery)){
		
		//remove the ";" inside query to prevent sql syntax error
		$tableQuery = str_replace_last(";","",$tableQuery);
		$tableQuery = "select count(*) from ($tableQuery) a	";
		$where = filter( $request, getColumns($tableKey),$TMP_TABLE );
		$tableQuery = $tableQuery.$where;

		$row = R::getCell($tableQuery);
		return $row;
	}
}


function totalRecords($tableID){

	$tableQuery = "";

	$table = R::findOne('uitable',' table_name = ? ', 
                array( $tableID )
               );
	$tableQuery = $table->query;
	$tableKey = $table->id;
	
	if(isset($tableQuery)){
	
		//remove the ";" inside query to prevent sql syntax error
		$tableQuery = str_replace_last(";","",$tableQuery);
		$tableQuery = "select count(*) from ($tableQuery) a;";
		
		$row = R::getCell($tableQuery);
		return $row;
	}
	
}

function str_replace_last( $search , $replace , $str ) {
    if( ( $pos = strrpos( $str , $search ) ) !== false ) {
        $search_length  = strlen( $search );
        $str    = substr_replace( $str , $replace , $pos , $search_length );
    }
    return $str;
}

function order ( $request, $columns ,$prefix)
	{
		$order = '';

		if ( isset($request['order']) && count($request['order']) ) {
			$orderBy = array();
			//$dtColumns = self::pluck( $columns, 'dt' );

			for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];

				//$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';

					$orderBy[] = '`'.$column.'` '.$dir;
				}
			}

			$order = ' ORDER BY '.implode(', ', $orderBy);
		}

		return $order;
	}
	
function getColumns($tableID){

	$table = R::load('uitable',$tableID );
	$columns = $table->ownUicolumn;

	foreach ($columns as $column){
			$columnList[] = $column->column_name;
	}
	
	return $columnList;

	
}

function filter ( $request, $columns, $prefix )
	{
		$globalSearch = array();
		$columnSearch = array();
		//$dtColumns = self::pluck( $columns, 'dt' );

		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				//$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $i ];

				if ( $requestColumn['searchable'] == 'true' ) {
					$binding = '\'%'.$str.'%\'';
					$globalSearch[] = "`".$column."` LIKE ".$binding;
				}
			}
		}

		// Individual column filtering
		if ( isset( $request['columns'] ) ) {
			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				//$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $i ];

				$str = $requestColumn['search']['value'];

				if ( $requestColumn['searchable'] == 'true' &&
				 $str != '' ) {
					$binding = '\'%'.$str.'%\'';
					$columnSearch[] = "`".$column."` LIKE ".$binding;
				}
			}
		}

		// Combine the filters into a single string
		$where = '';

		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}

		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}

		if ( $where !== '' ) {
			$where = ' WHERE '.$where;
		}

		return $where;
	}

function limit ( $request )
	{
		$limit = '';

		if ( isset($request['start']) && $request['length'] != -1 ) {
			$limit = " LIMIT ".intval($request['start']).", ".intval($request['length']);
		}

		return $limit;
	}	

?>