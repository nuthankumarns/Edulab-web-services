<?php
//include'set_xml_session.php';
include'setup.php';
header("content-Type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
$class_id=$_REQUEST['class_id'];
$module_id=$_REQUEST['module_id'];
$ability=$_REQUEST['ability'];
//www.tritonetech.com/php_uploads/porsche/webservice/writing_assessment.php?school_id=&level_id=&class_id=&module_id=&ability=
$query=$_DB->Query("SELECT c.word, sum( c.self_error ) as sum_of_errors
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.module_id = '$module_id'
AND b.ability = '$ability'
AND b.task = 'self_write'
AND c.self_error > '0'
GROUP BY (
c.word
)
ORDER BY sum( c.self_error ) DESC
LIMIT 0 , 10 ");
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$output.="<Status>"."No words exist"."</Status>";
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$output.="<word>".$_DB->GetResultValue($query,$i,'word')."</word><sum_of_errors>".$_DB->GetResultValue($query,$i,'sum_of_errors')."</sum_of_errors>";
	}
}
echo $output."</Result>";
?>
