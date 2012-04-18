<?php
include'set_session.php';
include'setup.php';
require_once'FastJSON.class.php';
$old='500';
//$module_id=$_REQUEST['module_id'];
//$student_id=$_REQUEST['student_id'];
$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
$class_id=$_REQUEST['class_id'];
$ability=$_REQUEST['ability'];
$module_id=$_REQUEST['module_id'];
//www.tritonetech.com/php_uploads/porsche/webservice/attention_required.php?school_id=&level_id=&class_id=&ability=&module_id=

if($school_id=='' || $level_id=='' || $class_id=="" || $ability=='')
{$dataDB['Result']['Data'][0]['Status']="missing_parameters";
echo FastJSON::encode($dataDB);
exit;
}
$query=$_DB->Query("SELECT a.name, c.module_name, a.id as student_id,timediff( now( ) , FROM_UNIXTIME( e. access ) ) AS idle_time, e.user_id, f.task
FROM jos_porsche_student AS a 
JOIN jos_porsche_class_module AS b ON a.class_id = b.class_id
JOIN jos_porsche_module AS c ON b.module_id = c.id
LEFT JOIN jos_porsche_sessions AS e ON a.user_id = e.user_id
LEFT JOIN jos_porsche_student_module AS f ON (f.student_id=a.id AND f.module_id='$module_id')
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND a.ability = '$ability'
AND b.module_id = '$module_id'");

$count=$_DB->GetResultNumber($query);
if($count==0)
{
$dataDB['Result']['Data'][0]['Status']="No students";
echo FastJSON::encode($dataDB);
exit;
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$dataDB['Result']['Data'][$i]['name']=$_DB->GetResultValue($query,$i,'name');
	$dataDB['Result']['Data'][$i]['student_id']=$_DB->GetResultValue($query,$i,'student_id');
	$dataDB['Result']['Data'][$i]['idle_time']=($_DB->GetResultValue($query,$i,'idle_time'))!=('')?($_DB->GetResultValue($query,$i,'idle_time')):"not logged in";
	$dataDB['Result']['Data'][$i]['module_name']=$_DB->GetResultValue($query,$i,'module_name');
	$dataDB['Result']['Data'][$i]['task']=$_DB->GetResultValue($query,$i,'task');
	$dataDB['Result']['Data'][$i]['login_status']=($_DB->GetResultValue($query,$i,'user_id'))!=('')?"logged in":"not logged in";
}
echo FastJSON::encode($dataDB);
}

//var_dump($oSession->clean($max));
//print_r($oSession->users());
//$id='423';
//print_r($oSession->read($id));
?>
