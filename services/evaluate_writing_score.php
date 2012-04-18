<?php
include'set_session.php';
include 'setup.php';
require 'FastJSON.class.php';
$uid=$_SESSION['UID'];

//www.tritonetech.com/php_uploads/porsche/webservice/evaluate_writing_score.php?qid=&score=&group_content_id=
$score=$_REQUEST['score'];
$qid=$_REQUEST['qid'];


if($score=='' || $qid=='')
{
$dataDB['Result']['Data'][]=array('Status'=>"parameters missing");
echo FastJSON::encode($dataDB);
exit;
}
$time=time();
$query=$_DB->Execute("update jos_porsche_group_writing_task set score='$score',eval_time='$time' where id='$qid'");

if($query==true)
{
/*$query=$_DB->Query("SELECT a.qid, b.id, b.q_s_id, b.group_content_id, c.module_id, c.id, c.task
FROM jos_porsche_group_writing_track AS a
LEFT JOIN jos_porsche_group_writing_task AS b ON a.qid = b.id
LEFT JOIN jos_porsche_group AS c ON b.group_content_id = c.id
WHERE a.qid = '$qid'
AND c.task = 'group_write'");*/
$query=$_DB->Query("SELECT a.id , a.group_content_id, a.q_s_id, b.id, b.module_id, b.task
FROM jos_porsche_group_writing_task AS a
LEFT JOIN jos_porsche_group b ON a.group_content_id = b.id
WHERE a.id = '$qid'
AND b.task = 'group_write'");
$student_id=$_DB->GetResultValue($query,0,'q_s_id');
$module_id=$_DB->GetResultValue($query,0,'module_id');
//$_DB->Execute("UPDATE jos_porsche_student_module SET group_writing =case when group_writing IS NULL THEN $score ELSE group_writing +'$score' END WHERE student_id = '$student_id' AND module_id = '$module_id' ");
$_DB->Execute("UPDATE jos_porsche_student_module SET group_writing =group_writing+'$score' WHERE student_id = '$student_id' AND module_id = '$module_id' ");
$dataDB['Result']['Data'][0]=array('Status'=>"successful in assigning score");
}
else
{
$dataDB['Result']['Data'][0]=array('Status'=>"not assigned");
}
echo FastJSON::encode($dataDB);
?>
