<?php
include'set_session.php';
include'setup.php';
include'FastJSON.class.php';
$uid=$_SESSION['UID'];
//echo $uid;

//www.tritonetech.com/php_uploads/porsche/webservice/student_switch_task.php

/*$query=$_DB->Query("SELECT a.id, a.user_id, a.class_id, b.class_id, b.module_id,b.task, c.module_name, c.module_order
FROM jos_porsche_student AS a
LEFT JOIN jos_porsche_class_module AS b ON a.class_id = b.class_id
LEFT JOIN jos_porsche_module AS c ON b.module_id = c.id
WHERE a.user_id = '$uid'");*/
$query=$_DB->Query("select b.student_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student_id=$_DB->GetResultValue($query,0,'student_id');

$query=$_DB->Query("SELECT d.id as module_id,d.module_name,d.module_order,c.task,b.group_write
FROM jos_porsche_student AS a
LEFT JOIN jos_porsche_class_module AS b ON a.class_id = b.class_id
LEFT JOIN jos_porsche_student_module AS c ON ( a.id = c.student_id
AND b.module_id = c.module_id )
LEFT JOIN jos_porsche_module AS d ON b.module_id=d.id
WHERE a.id = '$student_id'");



$count=$_DB->GetResultNumber($query);
if($count>0)
{
$dataDB['Result']['Data'][0]['module_id']=$_DB->GetResultValue($query,0,'module_id');
$dataDB['Result']['Data'][0]['module_order']=$_DB->GetResultValue($query,0,'module_order');
$dataDB['Result']['Data'][0]['module_name']=$_DB->GetResultValue($query,0,'module_name');
	$task=$_DB->GetResultValue($query,'0','task');
	$group_write_status=$_DB->GetResultValue($query,0,'group_write');
	
	if($task=='self_complete' && $group_write_status=='1')
	{
	
	$dataDB['Result']['Data'][0]['task']="group_write";
	$module_id=$_DB->GetResultValue($query,0,'module_id');
	$_DB->Execute("UPDATE jos_porsche_student_module SET task='group_write' WHERE student_id='$student_id' AND '$module_id'");
	}
	else
	{
	$dataDB['Result']['Data'][0]['task']=$task;
	}
}
else
{

$dataDB['Result']['Data'][0]['Status']="task not assigned";
}
echo FastJSON::encode($dataDB);

?>
