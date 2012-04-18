<?php
include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
$class_id=$_REQUEST['class_id'];
$module_id=$_REQUEST['module_id'];
$ability=$_REQUEST['ability'];
//www.tritonetech.com/php_uploads/porsche/webservice/reading_assessment.php?school_id=&level_id=&class_id=&module_id=&ability=
/*most selected word for reading request*/
$query=$_DB->Query("SELECT d.word, sum( d.reading_request ) AS sum_of_reading_request
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_reading_task AS c ON b.id = c.self_content_id
JOIN jos_porsche_self_reading_track_word AS d ON c.id = d.self_reading_task_id
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.ability = '$ability'
AND b.task = 'self_read'
AND b.module_id = '$module_id'
GROUP BY d.word
ORDER BY reading_request DESC");
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$output.="<Status>"."No words exist"."</Status>";
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$output.="<word>".$_DB->GetResultValue($query,$i,'word')."</word><sum_of_reading_request>".$_DB->GetResultValue($query,$i,'sum_of_reading_request')."</sum_of_reading_request>";
	}
}
echo $output."</Result>";
?>
