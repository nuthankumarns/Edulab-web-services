<?php
include'set_xml_session.php';
include 'setup.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$uid=$_SESSION['UID'];
$id=$_REQUEST['student_id'];
$group_content_id=$_REQUEST['group_content_id'];
/*if($id=='' || $group_content_id=='')
{
$output.="<Status>"."missing parameters"."</Status></Result>";
echo $output;
exit;
}*/
//www.tritonetech.com/php_uploads/porsche/webservice/posted_question.php?student_id=&group_content_id=
$query1=$_DB->Query("select a.id,a.user_id,a.ability,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student_id=$_DB->GetResultValue($query1,0,'student_id');
$ability=$_DB->GetResultValue($query1,0,'ability');
$query=$_DB->Query("SELECT a.id as qid,a.question ,b.content
FROM jos_porsche_group_reading_task AS a
LEFT JOIN jos_porsche_".$ability."_lesson AS b ON a.content_id = b.id
WHERE a.student_id = '$student_id'
AND q_s_id = '$id' and group_content_id='$group_content_id' and a.score='0' ORDER by a.posted_time DESC");
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$output.="<Status>"."No posted questions"."</Status></Result>";
echo $output;
}
else
{
for($i=0;$i<$count;$i++)
{
$output.="<Data><question>".$_DB->GetResultValue($query,$i,'question')."</question>
<content>".$_DB->GetResultValue($query,$i,'content')."</content>
<qid>".$_DB->GetResultValue($query,$i,'qid')."</qid></Data>";
}
echo $output."</Result>";
}

?>