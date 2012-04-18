<?php
include'set_session.php';
include 'setup.php';
//$uid=$_REQUEST['UID'];

$student_id=$_REQUEST['student_id'];
$task=$_REQUEST['task'];
//$group_id=$_REQUEST['group_id'];
if($student_id=='')
{
$dataDB['Result']['Data']=array('Status'=>"missing parameters");
	echo json_encode($dataDB);
	exit;
}
//www.tritonetech.com/php_uploads/porsche/webservice/view_group_score.php?student_id=&task=
//$query=$_DB->Query("select a.name,a.id,b.student_id,b.group_reading,b.group_writing,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where b.student_id='$student_id'");
$query=$_DB->Query("select a.name,a.id,b.student_id,b.group_reading,b.group_writing,b.group_id from jos_porsche_student as a left join 
jos_porsche_class_module AS c ON a.class_id=c.class_id LEFT JOIN
jos_porsche_student_module as b on a.id=b.student_id AND c.module_id=b.module_id where b.student_id='$student_id'");
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$dataDB['Result']['Data']=array('Status'=>"no students");
	echo json_encode($dataDB);
	exit;


}
else
{
switch($task){
case'group_read':
$dataDB['Result']['Data']['group_reading_score']=$_DB->GetResultValue($query,0,'group_reading');
break;
case'group_write':
$dataDB['Result']['Data']['group_writing_score']=$_DB->GetResultValue($query,0,'group_writing');
break;
}
$dataDB['Result']['Data']['name']=$_DB->GetResultValue($query,0,'name');
}
echo json_encode($dataDB);
?>
