<?php
include'setup.php';
//header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
require_once'FastJSON.class.php';
$module_id=$_REQUEST['module_id'];
$student_id=$_REQUEST['student_id'];
$group_id=$_REQUEST['group_id'];
//www.tritonetech.com/php_uploads/porsche/webservice/group_writing_data.php?student_id=&module_id=
$query=$_DB->Query("SELECT a . *,a.id as task_id , b . *,b.id as track_id , c . *,c.id as word_id
FROM jos_porsche_group_writing_task AS a
LEFT JOIN jos_porsche_group_writing_track AS b ON a.id = b.qid
LEFT JOIN jos_porsche_group_writing_track_word AS c ON b.id = c.track_word_id
WHERE module_id = '$module_id'
AND student_id = '$student_id'");

$count=$_DB->GetResultNumber($query);
$dataDB['Result']['Data'][]['count_of_answers']=$_DB->GetResultNumber($query);
for($i=0;$i<$count;$i++)
{
//playback_count 	error 	time
$output.="<Data><word>".$_DB->GetResultValue($query,$i,'word')."</word>
<playback_count>".$_DB->GetResultValue($query,$i,'playback_count')."</playback_count>
<error>".$_DB->GetResultValue($query,$i,'error')."</error>
<time>".$_DB->GetResultValue($query,$i,'time')."</time></Data>";
}
//echo $output."</Result>";
//echo FastJSON::encode($dataDB);

?>
