<?php
include'set_session.php';
include'setup.php';
include'FastJSON.class.php';

$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
$class_id=$_REQUEST['class_id'];
$module_id=$_REQUEST['module_id'];
$ability=$_REQUEST['ability'];
$task=$_REQUEST['task'];
//www.tritonetech.com/php_uploads/porsche/webservice/data_reports_reading.php?school_id=&level_id=&class_id=&module_id=&ability=&task=
/*task:0 no. of recordings per user
task:1 users time taken in ascending
task:2 users time taken in descending*/

/*SELECT TIME_TO_SEC(TIMEDIFF(now(), `last_active`)) as timediff FROM `users` where TIME_TO_SEC(TIMEDIFF(now(), `last_active`)) < 300*/
/*recording per user*/
switch($task){
case'0':
$query=$_DB->Query("SELECT a.name, sum( c.record_count ) AS record_count
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_reading_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.ability = '$ability'
AND b.task = 'self_read'
AND b.module_id = '$module_id'
GROUP BY c.self_content_id
ORDER BY c.record_count ASC");
break;
case'1':
$query=$_DB->Query("SELECT a.name, SEC_TO_TIME( sum( TIME_TO_SEC( c.total_time ) ) ) AS total_time, c.self_content_id
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_reading_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.ability = '$ability'
AND b.task = 'self_read'
AND b.module_id = '$module_id'
GROUP BY c.self_content_id
ORDER BY c.total_time DESC");
break;
case'2':
$query=$_DB->Query("SELECT a.name, SEC_TO_TIME( sum( TIME_TO_SEC( c.total_time ) ) ) AS total_time, c.self_content_id
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_reading_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.ability = '$ability'
AND b.task = 'self_read'
AND b.module_id = '$module_id'
GROUP BY c.self_content_id
ORDER BY c.total_time ASC");
break;
default:
$dataDB['Result']['Data'][0]['Status']="parameters missing";
break;
}
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$dataDB['Result']['Data'][0]['Status']="No users exist";
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$dataDB['Result']['Data'][$i]['name']=$_DB->GetResultValue($query,$i,'name');
		switch($task){
		case'0':
		$dataDB['Result']['Data'][$i]['record_count']=$_DB->GetResultValue($query,$i,'record_count');
		break;
		case'1':
		case'2':
		$dataDB['Result']['Data'][$i]['total_time']=$_DB->GetResultValue($query,$i,'total_time');
		break;
		}

	}

}
echo FastJSON::encode($dataDB);

?>
