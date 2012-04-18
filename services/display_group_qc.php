<?php
include'set_xml_session.php';
include 'setup.php';
$uid=$_SESSION['UID'];
//$uid='107';

//header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
//www.tritonetech.com/php_uploads/porsche/webservice/display_group_qc.php?task=&group_content_id=
//task=group_read 
//task=group_write
$task=$_REQUEST['task'];
$group_content_id=$_REQUEST['group_content_id'];
if($task=='' || $group_content_id=='')
{
$output.="<Status>"."missing parameters"."</Status></Result>";
	echo $output;
	exit;

}


$query=$_DB->Query("select a.id,a.ability,a.user_id,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
//$group_id=$_DB->GetResultValue($query,0,'group_id');
$student_id=$_DB->GetResultValue($query,0,'student_id');
$ability=$_DB->GetResultValue($query,0,'ability');

switch($task)
{
case'group_read':
//$a="SELECT a.id,a.question,a.content_id,b.id,b.content from jos_porsche_group_reading_task as a left join jos_porsche_".$ability."_lesson as b on a.content_id=b.id where q_s_id='$student_id'";

//echo $a;
$query=$_DB->Query("SELECT a.id as qid,a.question, a.content_id,a.posted_time, b.id, b.content
FROM jos_porsche_group_reading_task AS a
LEFT JOIN jos_porsche_".$ability."_lesson AS b ON a.content_id = b.id
WHERE q_s_id = '$student_id' AND group_content_id='$group_content_id'  AND a.filename='' ORDER BY a.posted_time DESC");


/*$query=$_DB->Query("SELECT id,question
FROM jos_porsche_group_reading_task 

WHERE q_s_id = '$student_id'");*/
//print_r($query);
$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$output.="<Status>"."No posted questions"."</Status></Result>";
	echo $output;
	exit;

	}
	else
	{
	for($i=0;$i<$count;$i++)
		{
		//$student_id=$_DB->GetResultValue($query,$i,'student_id');
		$question=$_DB->GetResultValue($query,$i,'question');
		//$id=$_DB->GetResultValue($query,$i,'content_id');
		$content=$_DB->GetResultValue($query,$i,'content');
		$qid=$_DB->GetResultValue($query,$i,'qid');
		//$q_s_id=$_DB->GetResultValue($query,$i,'q_s_id');
//echo $question;
$output.="<Data><question>".$question."</question><qid>".$qid."</qid><content>".$content."</content></Data>";
		}
	//echo $question;
//$output.="<question>".$question."</question><content>".$content."</content>";	
	
	}
break;
case'group_write':
$query=$_DB->Query("SELECT a.id as qid,a.content_id,a.posted_time, b.id, b.content
FROM jos_porsche_group_writing_task AS a
LEFT JOIN jos_porsche_".$ability."_lesson AS b ON a.content_id = b.id
 where q_s_id = '$student_id' AND group_content_id='$group_content_id' AND image='' ORDER BY a.posted_time DESC");
/*$query=$_DB->Query("SELECT a.id as qid,a.content_id, b.id, b.content
FROM jos_porsche_group_writing_task AS a
LEFT JOIN jos_porsche_".$ability."_lesson AS b ON a.content_id = b.id
 where q_s_id = '$student_id' and module_id='$module_id'");*/

//echo $a;
//print_r($query);
$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$output.="<Status>"."No posted questions"."</Status></Result>";
	echo $output;
	exit;

	}
	else
	{
	for($i=0;$i<$count;$i++)
		{
		//$student_id=$_DB->GetResultValue($query,$i,'student_id');
		//$question=$_DB->GetResultValue($query,$i,'question');
		$id=$_DB->GetResultValue($query,$i,'content_id');
		$content=$_DB->GetResultValue($query,$i,'content');
		$qid=$_DB->GetResultValue($query,$i,'qid');
		//$q_s_id=$_DB->GetResultValue($query,$i,'q_s_id');
//echo $question;
$output.="<Data><qid>".$qid."</qid><content>".$content."</content></Data>";
		}
	//echo $question;
//$output.="<question>".$question."</question><content>".$content."</content>";	
	
	}
break;
}
echo $output."</Result>";




?>
