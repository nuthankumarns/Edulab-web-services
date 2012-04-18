<?php
//include'set_session.php';
include'setup.php';
require_once'FastJSON.class.php';
$class_id=$_REQUEST['class_id'];
//http://www.tritonetech.com/php_uploads/porsche/webservice/view_assigned_task.php?class_id=
$query=$_DB->Query("select a.*,b.module_name from jos_porsche_class_module AS a LEFT JOIN jos_porsche_module AS b ON a.module_id=b.id WHERE class_id='$class_id'");
$count=$_DB->GetResultNumber($query);

if($count==0)
{
$_DB->Execute("INSERT INTO jos_porsche_class_module(class_id) VALUES('$class_id')");
 $dataDB['Result']['Data'][0]=array('Status'=>"Not assigned",'id'=>mysql_insert_id());
	echo FastJSON::encode($dataDB);
	exit;

}
else
{
	for($i=0;$i<$count;$i++)
	{
	$dataDB['Result']['Data'][$i]['id']=($_DB->GetResultValue($query,$i,'id')!=('0'||''))?($_DB->GetResultValue($query,$i,'id')):"not assigned";
	$dataDB['Result']['Data'][$i]['subject_id']=($_DB->GetResultValue($query,$i,'subject_id')!=('0'||''))?($_DB->GetResultValue($query,$i,'subject_id')):"not assigned";
	$dataDB['Result']['Data'][$i]['module_id']=($_DB->GetResultValue($query,$i,'module_id')!=('0'||''))?($_DB->GetResultValue($query,$i,'module_id')):"not assigned";
	//$dataDB['Result']['Data'][$i]['task']=($_DB->GetResultValue($query,$i,'task')!=('0'||''))?($_DB->GetResultValue($query,$i,'task')):"not assigned";
	$dataDB['Result']['Data'][$i]['ordering']=($_DB->GetResultValue($query,$i,'module_order')!=('0'||''))?($_DB->GetResultValue($query,$i,'module_order')):"not assigned";
	$dataDB['Result']['Data'][$i]['module_name']=($_DB->GetResultValue($query,$i,'module_name')!=('0'||''))?($_DB->GetResultValue($query,$i,'module_name')):"not assigned";
	$dataDB['Result']['Data'][$i]['group_read']=($_DB->GetResultValue($query,$i,'group_read')!=(''))?($_DB->GetResultValue($query,$i,'group_read')):"not assigned";
	$dataDB['Result']['Data'][$i]['group_write']=($_DB->GetResultValue($query,$i,'group_write')!=(''))?($_DB->GetResultValue($query,$i,'group_write')):"not assigned";
	
	}
echo FastJSON::encode($dataDB);

}


?>

