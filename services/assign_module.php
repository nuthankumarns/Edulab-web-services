<?php
include'set_session.php';
include'setup.php';
require_once'FastJSON.class.php';
//www.tritonetech.com/php_uploads/porsche/webservice/assign_task.php?id=&module_id=&task=&subject_id=&module_order=&class_id=

//$student_id=$_REQUEST['student_id'];
$id=$_REQUEST['id'];
$subject_id=$_REQUEST['subject_id'];
$module_id=$_REQUEST['module_id'];
$task=$_REQUEST['task'];
$module_order=$_REQUEST['module_order'];
$class_id=$_REQUEST['class_id'];
if(($id==('0'|| '')) || ($module_id==('0'|| '')) || ($subject_id==('0'|| '')) || ($module_order==('0'|| '')))
{
$dataDB['Result']['Data'][0]['Status']="missing_parameters";
echo FastJSON::encode($dataDB);
exit;
}


$query=$_DB->Query("SELECT a. * , b.name as student_name,b.school_id, b.level_id, b.class_id, b.id , c.student_id, c.group_id
FROM jos_porsche_classes AS a
LEFT JOIN jos_porsche_student AS b ON ( a.id = b.class_id
AND a.school_id = b.school_id
AND a.level_id = b.level_id )
LEFT JOIN jos_porsche_group_members AS c ON b.id = c.student_id
WHERE a.id = '$class_id'");

$count=$_DB->GetResultNumber($query);
if($count==0)
{
$dataDB['Result']['Data'][0]['Status']="No students";
}
else
{

	for($i=0;$i<$count;$i++)
	{
	$student_id[$i]=$_DB->GetResultValue($query,$i,'student_id');
$group_id[$i]=$_DB->GetResultValue($query,$i,'group_id');
	
//var_dump($group_id);
		if($group_id=='')
		{
		$dataDB['Result']['Data'][0]['Status']="Please assign group ability";
		echo FastJSON::encode($dataDB);
		exit;
		}
	}


	for($i=0;$i<count($student_id);$i++)
	{//echo $student_id."<br/>";
$group_id[$i]=$_DB->GetResultValue($query,$i,'group_id');
	/*$a="INSERT INTO jos_porsche_student_module( student_id, module_id, group_id, self_read )
VALUES (
'$student_id[$i]', '$module_id', '$group_id[$i]', '$task'
) ON DUPLICATE
KEY UPDATE task = '$task' ";*/
$_DB->Execute("INSERT INTO jos_porsche_student_module(student_id,module_id,group_id) VALUES('$student_id[$i]','$module_id','$group_id[$i]')");
//var_dump($query);
/*echo $a;
	$_DB->Execute("INSERT INTO jos_porsche_student_module( student_id, module_id, group_id, task )
VALUES (
'$student_id[$i]', '$module_id', '$group_id[$i]', '$task'
) ON DUPLICATE
KEY UPDATE task = '$task' ");*/
	
	
	}
	$query=$_DB->Execute("UPDATE jos_porsche_class_module SET subject_id='$subject_id', module_id='$module_id',  module_order='$module_order' where id='$id'");
	
	//$_DB->Execute("UPDATE jos_porsche_student_module set module_id='$module_id' 
	$dataDB['Result']['Data'][0]['Status']=($query)?("assign success"):"assign failure";
}
//$query?true:($dataDB['Result']['Data'][0]['Status']="task assign success"),($dataDB['Result']['Data'][0]['Status']="task assign failure");

echo FastJSON::encode($dataDB);

?>
