<?php
if (empty($_GET['html']) || $_GET['html']!="n" ) {
?>
<!DOCTYPE html>
<html>
<?php
}
?>
<?php
require_once('script/errorHandler.php');
include(dirname(__FILE__).'/script/logger.php');
include(dirname(__FILE__).'/script/message.php');
//Handle Form Post request - start

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//~ foreach ($_POST as $key => $value)
//~ { 
   //~ if (!is_array($value))
   //~ { 
	
      //~ echo "key: ".$key." = ".$value."</br>";
   //~ } 
  
//~ } 
$formid=stripslashes($_POST['form_id']);
$query="select * from s_page_container where container_id='".$formid."'";
$result=exeQuery($query);
if($result['post_action']!=""){
	// if in SQL_MODE then action taken by SQL Querys.
	if(strpos($result['post_action'],'@SQL_SAVE_MODE')==false){
	echo $result['post_action'];
		//eval($result['post_action']);
	}else{
		exeQuery2(processInput($result['post_action']));
		//echo  processInput($result['post_action']);
	}
}
}


//Handle Form Post request - end

//verify page name
echo newID();
$p_out="";
if (empty($_GET['page_name']) ) {
		$p_out.= 'Refer to non-existing page';
		echo getMessageString($p_out,"E");
		$GLOBALS['logger']->logDebug("page.php  >>".$p_out);
		exit;
	}
$name= urldecode($_GET['page_name']);	
$query = 'select * from s_page where page_name="'.$name.'"';

$result=exeQuery($query);

if (!isset($result) ) {
		$p_out.= 'Refer to non-existing page, page not found "'.$_GET['page_name'].'"';
		echo getMessageString($p_out,"E");
		$GLOBALS['logger']->logDebug("page.php  >>".$p_out);
		exit;
	}
if($result['page_title']!=""){
	$p_out.= "<title>".$result['page_title']."</title>\n";
	
}


$p_out.= "<link rel='stylesheet' type='text/css' href='style/".$result['page_css']."' />\n";
$p_out.= "<script type='text/javascript' src='script/ajax.js'>\n";
$p_out.= "</script>\n";
$p_out.= "<script type='text/javascript' >\n";
$p_out.=loadScript($result['page_id']);
$p_out.= "</script>\n";
$p_out.= "<body style='font-family:Arial, Helvetica, sans-serif;height:100%'>\n";
if (empty($_GET['html']) || $_GET['html']!="n" ) {
	echo $p_out;
}

//get Page Container
/*
*Content Type:
* LIST - display list of data in table form
* GRID - A form type display
* TAB - Multiple container in a page
*/
require('content/container/container.php');

if (empty($_GET['html']) || $_GET['html']!="n" ) {
	$p_out="</body>\n</html>";
	echo $p_out;
}


$p_out.=  "<body style='font-family:Arial, Helvetica, sans-serif;height:100%'>";
$query = 'select * from s_page_container where page_id="'.$result['page_id'].'" order by container_index';	
require('config/config.php');
	$result = $mysqli->query($query);
	//$row = $result->fetch_array(MYSQLI_BOTH);
//$result=$row;
if($result){
try{
$count = 0;
$num = $result->num_rows;
//$row = $result->fetch_array(MYSQLI_BOTH);
	while ($count< $num ){
		$row = $result->fetch_array(MYSQLI_BOTH); 
		$height="";
		if($row['type']=='tab'){
			$height="height='100%'";
		}
$p_out.=  "<table border='0' cellspacing='6'  width='100%' ".$height."  id='".$row['container_name']."'>\n";

if($row['title']!=""){
$p_out.=  "<tr><td class='c_title' id='".$row['container_name']."_title' height=10px><table class='inner_title'><tr><td style='width:90%'>".processInput($row['title'])."</td><td style='text-align:right;' ><a href='javascript:void(0);' id='".$row['container_name']."_control' onclick='contentExpand(\"".$row['container_name']."\")' style='color:#D8D8D8;' alt='up'>&#x25B4</a></td></tr></table></td></tr>\n";
}
$p_out.=  "<tr ><td height='100%' id='".$row['container_name']."_content'>\n";
$p_out.= displayContent($row['type'],$row['container_id'],$row['query'],$row['script_type'],$row['templete_script'],$row['container_name']);
$p_out.=  "</td></tr>\n";
$p_out.=  "<tr ><td width='100%' id='".$row['container_name']."_button'>\n";
$p_out.= displayButton($row['container_id'],$row['container_name']);
$p_out.=  "</td></tr>\n";
$p_out.=  "</table>\n";		

		$count=$count+1;
	}
	
//final output

echo $p_out;
 

}catch(Exception $e){
	throw new Exception("Test 2");
}
	

}
function displayContent($type,$id,$query,$script_type,$templete,$container_name){
$p_out="";
switch ($type)
{
case "table":

  $p_out.=displayTable($id,$query,$script_type,$templete);
  break;
case "form":
  
  $p_out.=displayForm($id,$query,$script_type,$templete,$container_name);
  break;
case "tab":

  $p_out.=displayTab($id,$query,$script_type,$templete);
  break;
default:
  $p_out.= "<h3>No Content<h3>\n";
}
return $p_out;
}

function displayTable($id,$query,$script_type,$templete){
	$p_out="";
	require('../config/config.php');
	$p_out.= "<table class='table_list' width='100%'   cellspacing='0' cellpadding='0' style='border-collapse:collapse;' >\n";
	$query=processInput($query);
	if($script_type == "php"){//execute php script
		$result=null;
		eval($query);
		$header = getHeader($id,1);
		$p_out.=getHeader($id,0);
		if($result!=null){
		$count = 0;
		$num = sizeof($result);
		while ($count< $num ){
			$row = $result[$count]; 
			
			$p_out.=getDetail($row,$header,$count,$id);
			$count=$count+1;
		}
		}
		
	}else{// query table data
	$result=null;
	if($query<>""){
	
	$result = $mysqli->query($query);
	}
	 $header = getHeader($id,1);
	 $p_out.=getHeader($id,0);
	if($result!=null){
$count = 0;
$num = $result->num_rows;
//$row = $result->fetch_array(MYSQLI_BOTH);
	while ($count< $num ){
		$row = $result->fetch_array(MYSQLI_BOTH); 
		$p_out.=getDetail($row,$header,$count,$id);
		$count=$count+1;
	}
}

}
$p_out.= "</table>\n";//echo $lol;
return $p_out;
}

function getDetail($detail,$header,$count,$id){
	$p_out="";
		$p_out.= "<tr class='t_row_".($count%2)."'>\n";
		if($header!=null&&$detail!=null){
		for($i=0;$i<sizeof($header);$i++){
			$p_out.= "<td class='t_col'>";
			$p_out.= dataProcess($detail,$id,$header[$i]);
			$p_out.= "</td>\n";
		}
		}
		$p_out.= "</tr>\n";
		return $p_out;
}

function getHeader($id,$x){
$return="";
$p_out="";
require('../config/config.php');
$query="select * from s_page_container_item where container_id ='".$id."'  order by column_index;";
	$result = $mysqli->query($query);
	$p_out.= "<tr class=t_header>\n";
	if($result){
$count = 0;
$num = $result->num_rows;
//$row = $result->fetch_array(MYSQLI_BOTH);
	while ($count< $num ){
		$row = $result->fetch_array(MYSQLI_BOTH); 
		$return[$count] = $row['item_name'];
		$p_out.= "<td class=t_header_col id='".$row['item_name']."'>".$row['item_title']."</td>\n";
		
		$count=$count+1;
	}
}
$p_out.= "</tr>\n";

if($x==1){
return $return;
}else{
return $p_out;
}
}

function dataProcess($val,$id,$col){
$p_out="";
$return="";
require('../config/config.php');
$query="select script from s_page_container_item where container_id ='".$id."' and item_name='".$col."'  order by column_index;";
	$result = $mysqli->query($query);
	if($result){
	$row = $result->fetch_array(MYSQLI_BOTH); 
		if($row['script']!=null){
			eval(processInput($row['script']));
			return $p_out;
		}else{
			return processInput($val[$col]);
		}
	}
		
	
	
	
}

function displayForm($id,$query,$script_type,$templete,$container_name){
	$p_out="";
	require('../config/config.php');
	$query=processInput($query);
	$p_out.= "<form action='".$_SERVER['REQUEST_URI']."' method='post' name='frm".$container_name."' id='frm".$container_name."' >\n";
	$p_out.= "<input type='hidden' name='form_id'  value='".$id."'/>\n";
	$p_out.= "<table  width='100%'  cellspacing='0' cellpadding='0' >\n<tr>";
	
	if($script_type == "php"){//execute php script
			$mode="";
			if(!empty($_GET['UMode'])){
				$mode=$_GET['UMode'];
			}
			if(strtoupper($mode)!="ADD"){
				eval($query);
			}
		
		$item=getItemTitle($id);
		$count = 0;
		$num = sizeof($item);
		$p_out.= "<tr class='t_row_field'>\n";
		while ($count< $num ){
		
		$row = $result[$count]; 
	
		$p_out.=getItemDetail($row,$item,$count);
		$count=$count+1;
		
	}
	$p_out.= "</tr>\n";
	}else{
	$result=null;
	$mode="";
			if(!empty($_GET['UMode'])){
				$mode=strtoupper($_GET['UMode']);
				
			}
			if(strtoupper($mode)!="ADD"){
				$result = $mysqli->query($query);
			}
	//echo strtoupper($mode);
	$item=getItemTitle($id);
	$count = 0;
$num=1;
if($result!=null){
$num = $result->num_rows;
if($num==0){
$num=1;
}
}
//$row = $result->fetch_array(MYSQLI_BOTH);
echo "<tr class='t_row_field'>\n";
	while ($count< $num ){
		$row =null;
		if($result!=null){
		$row = $result->fetch_array(MYSQLI_BOTH); 
		}
		$p_out.=getItemDetail($row,$item,$count,$templete);
		
		
		$count=$count+1;
		
	}
	$p_out.= "</tr>\n";
	}
	$p_out.= "</table>\n";
	$p_out.= "</form>\n";
	
	return $p_out;
}


function getItemTitle($id){
require('../config/config.php');
$return = '';
$query="select * from s_page_container_item where container_id ='".$id."' order by row_index,column_index";
	$result = $mysqli->query($query);
	//echo "<tr class=t_header>\n";
	if($result){
$count = 0;
$num = $result->num_rows;
//$row = $result->fetch_array(MYSQLI_BOTH);
	while ($count< $num ){
		$row = $result->fetch_array(MYSQLI_BOTH); 
		$return[$count] = $row;
		//echo "<td class=t_header_col id='".$row['item_name']."'>".$row['item_title']."</td>\n";
		
		$count=$count+1;
	}
}
//echo "</tr>\n";
return $return;
}
function getItemDetail($detail,$header,$count,$templete){
$p_out="";
require('../config/config.php');

			//$detail=null;
		if($templete==null){
			$p_out=defaultTemplate($detail,$header,$count);
		}else{
		
		//process templete
		$query="select * from s_php_script where script_id='".$templete."'";
		$result = $mysqli->query($query);	
		if($result){
			$row = $result->fetch_array(MYSQLI_BOTH); 
			eval($row['script']);
		}
		}
			
			
	return $p_out;	
		
}

function processItem($value,$data){
	
	$value=htmlentities($value, ENT_QUOTES);
	switch ($data['item_type'])
	{
	case "text":
		$width=(($data['width']!=null)?"size='".$data['width']."'":"");
		return "<input type='text' id='".$data['item_name']."' name='".$data['item_name']."' value='".$value."' ".$width."/>";
	break;
	
	case "note":
		$width=(($data['width']!=null)?"cols='".$data['width']."'":"");
		$height=(($data['height']!=null)?"rows='".$data['height']."'":"");
		return "<textarea  id='".$data['item_name']."' name='".$data['item_name']."'  ".$width." ".$height.">".$value."</textarea>";
	break;
	
	case "dropdown":
		return dropdown($value,$data);
	break;
	
	default:
		$width=(($data['width']!=null)?"size='".$data['width']."'":"");
		return "<input type='text' id='".$data['item_name']."' name='".$data['item_name']."' value='".$value."' ".$width."/>";
	break;
	}
}

function dropdown($value,$data){
require('../config/config.php');
$query=$data['script'];
$result = $mysqli->query($query);	
		$width=(($data['width']!=null)?"width='".$data['width']."'":"");
		$height=(($data['height']!=null)?"size='".$data['height']."'":"");
		$return="<select  id='".$data['item_name']."' name='".$data['item_name']."'  ".$width." ".$height."  >\n";
		
		if($result){
		$count = 0;
		$num = $result->num_rows;
		while ($count< $num ){
		$selected="";
			$row = $result->fetch_array(MYSQLI_BOTH); 
			if($row[0]==$value){
				$selected="selected";
			}
			$return=$return."<option value='".$row[0]."' ".$selected.">".$row[1]."</option>";
			//$return=$return.$query;
			$count=$count+1;
		}
		}
		$return=$return."</select>\n";
	return $return;
}

function displayTab($id,$query,$script_type,$templete){
	$p_out="";
	require('../config/config.php');
	$query=processInput($query);
	$p_out.= "<table  width='100%'  cellspacing='0' cellpadding='0' style='border-collapse:collapse;' height='100%' >\n";
	$item=getTabTitle($id);
	$count = 0;
	$num = sizeof($item);
	$p_out.= "<tr class='t_row_field' id='tab_header'>\n";
	
	while ($count< $num ){
		//$row = $result->fetch_array(MYSQLI_BOTH); 
		//$return[$count] = $row;
		if($item[$count]['selected']==1){
			$selected='_selected';
		}else{
			$selected='';
		}
		$p_out.= "<td class=t_header_tab".$selected." id='".$item[$count]['item_name']."' onclick='switchTab(this)'>".$item[$count]['item_title']."</td>\n";
		
		$count=$count+1;
	}
	$p_out.= "<td class=t_header_tab_blank id='tab_blank'>&nbsp;</td>\n";
	$p_out.= "</tr>\n";
	
	//content display
	$p_out.= "<tr class='t_row_field' id='tab_content'>\n";
	$count = 0;
	while ($count< $num ){
		//$row = $result->fetch_array(MYSQLI_BOTH); 
		//$return[$count] = $row;
		if($item[$count]['selected']==1){
			$selected='_selected';
		}else{
			$selected='';
		}
		$p_out.= "<td class=t_content_tab".$selected." colspan=".($num+1)." id='content_".$item[$count]['item_name']."'><iframe class=display_tab".$selected." id='frame_".$item[$count]['item_name']."' src='".processInput($item[$count]['href'])."' frameborder=0 width=100% height=100% ></iframe></td>\n";
		
		$count=$count+1;
	}
	
	$p_out.= "</tr>\n";
	$p_out.= "</table>\n";
	return $p_out;
}
function getTabTitle($id){
require('../config/config.php');
$query="select * from s_page_container_item where container_id ='".$id."' order by column_index";
$result = $mysqli->query($query);	
	//echo "<tr class=t_header>\n";
	if($result){
$count = 0;
$num = $result->num_rows;
//$row = $result->fetch_array(MYSQLI_BOTH);
	while ($count< $num ){
		$row = $result->fetch_array(MYSQLI_BOTH); 
		$return[$count] = $row;
		//echo "<td class=t_header_col id='".$row['item_name']."'>".$row['item_title']."</td>\n";
		
		$count=$count+1;
	}
}
//echo "</tr>\n";
return $return;
}

function displayButton($container,$name){
//page button area
$p_out="";
require('../config/config.php');
$query = 'select * from s_page_container_button where container_id="'.$container.'" order by button_index';
$result = $mysqli->query($query);
	//echo $query;
if($result){


$count = 0;
$num = $result->num_rows;
if($num>0){
	//echo "<tr class=button_row>\n";
	$p_out.= "<div id='button_".$name."' class='button_row'  title='".$name."'>\n";
	$p_out.= "<span class=button_blank>&nbsp;</span>\n";
}
while ($count< $num ){
$row = $result->fetch_array(MYSQLI_BOTH); 
//echo getScriptName($row['button_script']);
$script=(strlen($row['parameter'])>0)?getScriptName($row['button_script'])."(".processInput($row['parameter']).")":getScriptName($row['button_script'])."()";
//echo $script;
	$p_out.= "<span class=button_item>\n";
	$p_out.= "<a id='".$row['button_name']."' href='javascript:void(0);' onclick=\"".$script."\">".$row['button_title']."</a>";
	$p_out.= "</span>\n";
	$count=$count+1;
	}
	if($num>0){
	$p_out.= "</span>\n";
	$p_out.= "</div>\n";
	}
}
return $p_out;
}

function getScriptName($id){
require('../config/config.php');
$query = 'select * from s_javascript where script_id="'.$id.'" ;';
$result = $mysqli->query($query);

if($result){
	//$count = 0;
	//$num = $result->num_rows;
	//while ($count< $num ){
	$row = $result->fetch_array(MYSQLI_BOTH); 
	return $row['script_name'];
	//$count=$count+1;
	//}
}
}
function processInput($inp){

$result=$inp;
$data=load2Array($result);
$size=sizeof($data);

for($i=0;$i<$size;$i++){
	$type=substr($data[$i],2,1);
	
	if($type=="P"){
		$result=str_replace($data[$i],getURL(substr($data[$i],3,strlen($data[$i])-5)),$result);
		//echo $result;
	}
}


return $result;
}

function load2Array($inp){
$inp=" ".$inp;
$sIndex=-2;
$eIndex=0;
$count=0;
$result="";
$arr=null;

$sIndex=strpos($inp,"<!",1);
$eIndex=strpos($inp,"!>",$sIndex);

while($sIndex!=false){

$result=substr($inp,$sIndex,($eIndex+2)-$sIndex);
//echo $result;
//echo $result." s ".$sIndex." e ".$eIndex;
$arr[$count]=$result;
//echo $arr[$count]."<br/>";
$count=$count+1;
$sIndex=strpos($inp,"<!",$sIndex+2);
$eIndex=strpos($inp,"!>",$sIndex);

}
//echo "&nbsp;";
return $arr;
}

function getURL($name){
$result="";
	if (!empty($_GET[$name]) ) {
		$result= urldecode($_GET[$name]);
	}
	return $result;
}
function exeQuery($q){
	require('config/config.php');
	$result = $mysqli->query($q);
	$row=$mysqli->affected_rows;
	
	$count = 0;
    $num = $result->num_rows;

	try{
	if($result!=null){
		if($num>1){
			$rows = array();
				while ($count< $num ){
				array_push($rows,$result->fetch_array(MYSQLI_BOTH));
				$count=$count+1;
				}
				$row=$rows;
		}else{
			$row = $result->fetch_array(MYSQLI_BOTH);
		}

	}else{
		//echo $mysqli->error;
		//echo "\n".$q."\n";
		throw new Exception($mysqli->error);
	}
	}catch(Exception $e){
		handleError("",$e->getMessage(),"","");
	}
	return $row;
}
function exeQuery2($q){
	require('config/config.php');
	if($mysqli->multi_query($q)){
	$result = $mysqli->store_result();
	//echo $q;
	}else{
		echo $mysqli->error;
	}
	//return  $mysqli->affected_rows;
}

function newID(){
require('config/config.php');
$query = 'select uuid()';
$result = $mysqli->query($query);
	if($result){
		$row = $result->fetch_array(MYSQLI_BOTH);
	}else{
		$result = $mysqli->error;
	}

return $row[0];	
}
function loadScript($id){
$p_out="";
require('config/config.php');
$query = 'select * from s_javascript a,s_page_javascript b where a.javascript_id=b.javascript_id and b.page_id="'.$id.'"';
//echo $query;
$result = $mysqli->query($query);
$return="";
if($result){
$count = 0;
$num = $result->num_rows;

while ($count< $num ){
		$row = $result->fetch_array(MYSQLI_BOTH); 
		$p_out.= $row['script']."\n\n";
	
		
		$count=$count+1;
	}
}
return $p_out;
}
function defaultTemplate($detail,$header,$count){
	$p_out="";
	$twoCol=false;
			$twoCol2=false;
			$Col2=false;
			$valTwo="";
			$first="";
			//has 2 column design
			for($i=0;$i<sizeof($header);$i++){
				if(($header[$i]['column_index'])>=1000){
					$twoCol=true;
				}
			}
			for($i=0;$i<sizeof($header);$i++){
				if(($header[$i]['column_index'])<1000||($header[$i]['column_index'])>0){
					$twoCol2=true;
				}
			}
			$p_out.= "<td><table class='col1' width='100%'  cellspacing='0' cellpadding='0' style='border-collapse:collapse;border:1px #D8D8D8 inset;' border=1><tr>\n";
			for($i=0;$i<sizeof($header);$i++){
			
			$value="";
			if($header[$i]['column_index']<1000){
			if($detail[$header[$i]['item_name']]!=null){
				$value=$detail[$header[$i]['item_name']];
			}
			
			if(!$Col2){
			$p_out.= $first."</tr><tr class='t_row_field'><td class='t_col_title' width='120px' >".$header[$i]['item_title']."</td><td class='t_col_field'  $valTwo>".processItem($value,$header[$i]);
			}else{
			$Col2=false;
			}
			if(sizeof($header)>($i+1)){
				if($header[$i+1]['column_index']>0&&$header[$i+1]['column_index']<1000 &&$header[$i]['row_index']==$header[$i+1]['row_index']){
				if($detail[$header[$i+1]['item_name']]!=null){
					$value=$detail[$header[$i+1]['item_name']];
				}
				$p_out.= "<span class='t_col_title' width='120px' >".$header[$i+1]['item_title']."</span><span class='t_col_field' >".processItem($value,$header[$i+1])."</span>\n";				
				$Col2=true;
				}
			}
			
			}
			$first="</td>\n";
			}
			if(substr($p_out,strlen($p_out)-5,5)!="</td>"){
				$p_out.= "</td>\n";
			}
			$p_out.= "</tr></table></td>";
	return $p_out;
}
?>
