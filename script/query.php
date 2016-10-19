<?php
function exeQuery($q){
	include(dirname(__FILE__).'/../config/config.php');
	//include_once(dirname(__FILE__).'/errorHandler.php');
	$result = $mysqli->query($q);
	//echo $q;
	$row=$mysqli->affected_rows;
	
	$count = 0;
    $num = $result->num_rows;

	try{
	if($result!=null){
		//if($num>1){
			$rows = array();
				while ($count< $num ){
				array_push($rows,$result->fetch_array(MYSQLI_BOTH));
				$count=$count+1;
				}
				$row=$rows;
		//}else{
		//	$row = $result->fetch_array(MYSQLI_BOTH);
		//}

	}else{
		//echo $mysqli->error;
		//echo "\n".$q."\n";
		throw new Exception($mysqli->error);
	}
	}catch(Exception $e){
		exception_handler("",$e->getMessage(),"","");
	}
	return $row;
}

function exeScalarQuery($q){
	include(dirname(__FILE__).'/../config/config.php');
	//include(dirname(__FILE__).'/errorHandler.php');
	$result = $mysqli->query($q);
	//echo $q;
	$row=$mysqli->affected_rows;
	
	$count = 0;
    $num = $result->num_rows;

	try{
	if($result!=null){
		
			$row = $result->fetch_array(MYSQLI_BOTH);
		

	}else{
		//echo $mysqli->error;
		//echo "\n".$q."\n";
		throw new Exception($mysqli->error);
	}
	}catch(Exception $e){
		exception_handler("",$e->getMessage(),"","");
	}
	return $row;
}

function exeMaintQuery($q){
	include(dirname(__FILE__).'/../config/config.php');
	//include(dirname(__FILE__).'/errorHandler.php');
	try{
	$result = $mysqli->query($q);
	
	$row=$mysqli->affected_rows;
	

	}catch(Exception $e){
		exception_handler("",$e->getMessage(),"","");
	}
	return $row;
}

?>