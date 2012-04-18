<?php
include'set_session.php';
include'setup.php';
//if(isset($_SESSION['UID']))
//{
$uid=$_SESSION['UID'];
//www.tritonetech.com/php_uploads/porsche/webservice/user_group_details.php?task=
$task=$_REQUEST['task'];
$module_id=$_REQUEST['module_id'];
if($task=='')
{
$dataDB['Result']['Data'][0]=array('Status'=>"parameters missing");
	echo json_encode($dataDB);
	exit;
}

/*$query=$_DB->Query("SELECT a.id, a.name, a.ability, a.user_id, b.student_id AS c, b.group_reading, b.group_writing, b.module_id, c.id, c.module_name
FROM jos_porsche_student AS a
LEFT JOIN jos_porsche_class_module AS d ON d.class_id = a.class_id
LEFT JOIN jos_porsche_module AS c ON d.module_id = c.id
LEFT JOIN jos_porsche_student_module AS b ON a.id = b.student_id
AND d.module_id = b.module_id
WHERE user_id = '$uid'");*/
$query=$_DB->Query("SELECT a.id, a.name, a.ability, b.group_reading, b.group_writing, c.module_name
FROM jos_porsche_student AS a
LEFT JOIN jos_porsche_student_module AS b ON a.id = b.student_id
AND b.module_id = '$module_id'
LEFT JOIN jos_porsche_module AS c ON b.module_id = c.id
WHERE a.user_id = '$uid'");

$count=$_DB->GetResultNumber($query);
if($count==0)
	{
	$dataDB['Result']['Data'][0]=array('Status'=>"No data");
	echo json_encode($dataDB);
	exit;
	}
$dataDB['Result']['Data'][0]['id']=$_DB->GetResultValue($query,0,'id');
	$dataDB['Result']['Data'][0]['name']=$_DB->GetResultValue($query,0,'name');
		$dataDB['Result']['Data'][0]['group_ability']=$_DB->GetResultValue($query,0,'ability');
switch($task){
case'group_read':
$dataDB['Result']['Data'][0]['reading_score']=$_DB->GetResultValue($query,0,'group_reading');
break;
case'group_write':
$dataDB['Result']['Data'][0]['writing_score']=$_DB->GetResultValue($query,0,'group_writing');
break;
}
$dataDB['Result']['Data'][0]['progress']=$_DB->GetResultValue($query,0,'module_name');
//($_DB->GetResultValue($query, 0, "module_name"))!=('0'||'')?($_DB->GetResultValue($query,0,"module_name")):"not assigned";
echo json_encode($dataDB);
/*}
else
{
$dataDB['Result']['Data']=array('Status'=>"No data");
	echo json_encode($dataDB);
	exit;
}
*/

?>
