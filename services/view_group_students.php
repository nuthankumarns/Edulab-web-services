<?php
include'set_session.php';
include'setup.php';
//http://www.tritonetech.com/php_uploads/porsche/webservice/view_group_students.php?school_id=&level_id=&class_id=
$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
$class_id=$_REQUEST['class_id'];
if($school_id=='' || $level_id=='' || $class_id=='')
{
$dataDB['Result']['Data'][]=array('Status'=>"Parameters Missing");
	echo json_encode($dataDB);
	exit;
}

$query=$_DB->Query("select id,name from jos_porsche_student where school_id='$school_id' and level_id='$level_id' and class_id='$class_id' and ability='' ORDER BY name ASC");
	$count = $_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No students");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0; $i < $count; $i++)
        	{
            $dataDB['Result']['Data'][$i]['student_id'] = $_DB->GetResultValue($query, $i,"id");
		$dataDB['Result']['Data'][$i]['name'] = $_DB->GetResultValue($query, $i,"name");
		}
	echo json_encode($dataDB);
	}
?>
