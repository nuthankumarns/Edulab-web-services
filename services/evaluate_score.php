<?php
include'set_session.php';
include 'setup.php';
require 'FastJSON.class.php';
$uid=$_SESSION['UID'];

//www.tritonetech.com/php_uploads/porsche/webservice/evaluate_score.php?qid=&score=group_content_id=
$score=$_REQUEST['score'];
$qid=$_REQUEST['qid'];
if($score=='' || $qid=='')
{
$dataDB['Result']['Data'][]=array('Status'=>"parameters missing");
echo FastJSON::encode($dataDB);
exit;
}
$time=time();
$query=$_DB->Execute("update jos_porsche_group_reading_task set score='$score',eval_time='$time' where id='$qid'");
if($query==true)
{
$query=$_DB->Query("SELECT a.id , a.group_content_id, a.q_s_id, b.id, b.module_id, b.task
FROM jos_porsche_group_reading_task AS a
LEFT JOIN jos_porsche_group b ON a.group_content_id = b.id
WHERE a.id = '$qid'
AND b.task = 'group_read'");
$student_id=$_DB->GetResultValue($query,0,'q_s_id');
$module_id=$_DB->GetResultValue($query,0,'module_id');
//$_DB->Execute("UPDATE jos_porsche_student_module SET group_reading =case when group_reading IS NULL THEN $score ELSE group_reading +'$score' END WHERE student_id = '$student_id' AND module_id = '$module_id' ");

$_DB->Execute("UPDATE jos_porsche_student_module SET group_reading =group_reading+'$score' WHERE student_id = '$student_id' AND module_id = '$module_id' ");
$dataDB['Result']['Data'][0]=array('Status'=>"successful in assigning score");
}
else
{
$dataDB['Result']['Data'][0]=array('Status'=>"not assigned");
}
echo FastJSON::encode($dataDB);
?>
