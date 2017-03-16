<?
session_start();
header("Content-Type:text/plain;Charset=TIS-620");

$path = '../';   

include($path.'include/config.inc.php');
include($path.'include/class_db.php');
include($path.'include/class_display.php');
include($path.'include/class_application.php');

$CLASS['db']=new db();
$CLASS['db']->connect();
$CLASS['disp']=new display();
$CLASS['app']=new application();

$db=$CLASS['db'];
$disp=$CLASS['disp'];
$app=$CLASS['app'];

//$q=strtolower($_GET["term"]);
$q=$_GET["term"];
$q=iconv('UTF-8','TIS-620',$q);
if ($q!=""){
	//$wh = " AND ".$_GET["fKey"]." LIKE '%$q%' ";
	$wh = " AND (place_id LIKE '%$q%' OR place_name LIKE '%$q%') ";
}
$fShow = $_GET["fShow"];
if(count($fShow)>0){
	foreach($fShow as $f_name){
		$all_f_name .= ",".$f_name;
	}
}
/*
$sql="
	SELECT TOP 100  ".$_GET["fKey"].$all_f_name."
	FROM thai_province
	WHERE 1=1 $wh
";
*/

 $sql="
	SELECT TOP 100  place_id
				,place_name
	FROM place
	WHERE place_status <>0
				$wh
";

//echo $sql;

$exc=$db->query($sql);
$rows=$db->num_rows($exc);
$resultArray=array();
$json="[" ;
while($rs=$db->fetch_array($exc)){
	$json_all_show="";
	if(count($fShow)>0){
		$json_all_show .= ',"fShow":[';
		foreach($fShow as $f_index => $f_name){
			$json_all_show .= "\"".trim(htmlspecialchars($disp->removeControlChar($rs[$f_name],true)." ",ENT_QUOTES,false))."\",";
		}
		$json_all_show = substr($json_all_show,0,-1).']';
	} 
	$arrCol=array();
	$json .= '{
					"value":"'.trim($disp->removeControlChar(str_replace('?','',$rs[$_GET["fKey"]]),true)).'",
					"fKey":"'.trim($disp->removeControlChar(str_replace('?','',$rs[$_GET["fKey"]]),true)).'"
					'.$json_all_show.'
				  }';
	$j++;
	if($j<$rows) $json .= ","; 
}
$json.="]" ;
print $json;
$db->close_db();
?>