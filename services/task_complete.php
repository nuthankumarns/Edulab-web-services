<?php
include'set_session.php';
include 'setup.php';
require_once 'FastJSON.class.php';
//http://www.tritonetech.com/php_uploads/porsche/webservice/task_complete.php?module_id=5&task=
//session_start();
$uid=$_SESSION['UID'];
$task=$_REQUEST['task'];

$query=$_DB->Query("select b.student_id,c.group_read,c.group_write from jos_porsche_student as a left join jos_porsche_class_module AS c ON a.class_id=c.class_id LEFT JOIN jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student_id=$_DB->GetResultValue($query,0,'student_id');

switch($task){
case'self_read':
$group_read_status=$_DB->GetResultValue($query,0,'group_read');
	if($group_read_status=='1')
	{
	$query=$_DB->Execute("UPDATE jos_porsche_student_module SET task='group_read' WHERE student_id='$student_id' AND module_id='$_REQUEST[module_id]'");
	}
	else
	{
	$query=$_DB->Execute("UPDATE jos_porsche_student_module SET task='self_write' WHERE student_id='$student_id' AND module_id='$_REQUEST[module_id]'");
	}
break;
case'group_read':
$query=$_DB->Execute("UPDATE jos_porsche_student_module SET task='self_write' WHERE student_id='$student_id' AND module_id='$_REQUEST[module_id]'");
break;
case'self_write':
$group_write_status=$_DB->GetResultValue($query,0,'group_write');
if($group_write_status=='1')
	{
	$query=$_DB->Execute("UPDATE jos_porsche_student_module SET task='group_write' WHERE student_id='$student_id' AND module_id='$_REQUEST[module_id]'");
	}
	else
	{
	$query=$_DB->Execute("UPDATE jos_porsche_student_module SET task='self_complete' WHERE student_id='$student_id' AND module_id='$_REQUEST[module_id]'");
	}
//$query=$_DB->Execute("UPDATE jos_porsche_student_module SET task='group_write' WHERE student_id='$student_id' AND module_id='$_REQUEST[module_id]'");
break;
case'group_write':
$query=$_DB->Execute("UPDATE jos_porsche_student_module SET task='group_complete' WHERE student_id='$student_id' AND module_id='$_REQUEST[module_id]'");
break;
//case'complete':
//break;
}
//$query=$_DB->Execute("UPDATE jos_porsche_student_module SET task='$task' WHERE student_id='$student_id' and module_id='$task'");
//var_dump($query);
$count=$_DB->GetResultNumber($query);
//var_dump($count);
$dataDB['Result']['Data'][0]['Status']=(($count>0)?"task updated":"failure");
echo FastJSON::encode($dataDB);
?>


