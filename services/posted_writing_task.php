<?php
include'set_xml_session.php';
include 'setup.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";

//require 'FastJSON.class.php';
$uid=$_SESSION['UID'];
//echo $uid;
//include 'get_student_id.php';
$id=$_REQUEST['student_id'];
$group_content_id=$_REQUEST['group_content_id'];
if($id=='')
{
$output.="<Status>"."missing parameters"."</Status></Result>";
echo $output;
exit;
}
//require 'fastJSON.class.php';
//www.tritonetech.com/php_uploads/porsche/webservice/posted_writing_task.php?student_id=&group_content_id=
$query=$_DB->Query("select a.id,a.user_id,a.ability,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student_id=$_DB->GetResultValue($query,0,'student_id');
$ability=$_DB->GetResultValue($query,0,'ability');
$query=$_DB->Query("SELECT a.id AS qid, a.content_id, b.content, b.id, a.score,a.posted_time
FROM jos_porsche_group_writing_task AS a
LEFT JOIN jos_porsche_".$ability."_lesson AS b ON a.content_id = b.id
WHERE a.student_id = '$student_id'
AND a.q_s_id = '$id'
AND a.group_content_id = '$group_content_id'
AND a.score IS NULL
ORDER BY a.posted_time DESC");
/*$query=$_DB->Query("SELECT a.id as qid,a.content_id , b.content,b.id
FROM jos_porsche_group_writing_task AS a
LEFT JOIN jos_porsche_".$ability."_lesson AS b ON a.content_id = b.id
WHERE a.student_id = '$student_id'
AND q_s_id = '$id' and group_content_id='$group_content_id'");*/

//echo $a;
/*query to view the posted questions to logged in user on clicking the user profile in his group*/

/*$a="SELECT a . * , b.content, b.id
FROM jos_porsche_group_reading_task AS a
LEFT JOIN jos_porsche_".$ability."_lesson AS b ON a.content_id = b.id
WHERE a.student_id = '$id'
AND q_s_id = '$student_id'";
echo $a;*/
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$output.="<Status>"."no posted questions"."</Status>";
//echo FastJSON::encode($dataDB);
//exit;
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$content=$_DB->GetResultValue($query,$i,'content');
	//$id=$_DB->GetResultValue($query,$i,'content_id');
	$qid=$_DB->GetResultValue($query,$i,'qid');
	$output.="<Data><content>".$content."</content><qid>".$qid."</qid></Data>";
	}

}
/*--------------------------------------goes next for evaluate_writing_score.php---------------------------------------------------------------*/
echo $output."</Result>";
?>
