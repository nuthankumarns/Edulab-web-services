<?php
//include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result><Status>";
//www.tritonetech.com/php_uploads/porsche/webservice/self_evaluation.php?student_id=&module_id=&task=&score=
$student_id=$_REQUEST['student_id'];
$module_id=$_REQUEST['module_id'];
$score=$_REQUEST['score'];
$task=$_REQUEST['task'];

switch($task){
case'self_write':
//$query=$_DB->Execute("UPDATE jos_porsche_student_module SET self_writing =case when self_writing IS NULL THEN $score ELSE self_writing +'$score' END WHERE student_id = '$student_id' AND module_id = '$module_id'");
$query=$_DB->Execute("UPDATE jos_porsche_student_module SET self_writing ='$score' WHERE student_id = '$student_id' AND module_id = '$module_id'");
break;
case'self_read':
//$query=$_DB->Execute("UPDATE jos_porsche_student_module SET self_reading =case when self_reading IS NULL THEN $score ELSE self_reading +'$score' END WHERE student_id = '$student_id' AND module_id = '$module_id'");
$query=$_DB->Execute("UPDATE jos_porsche_student_module SET self_reading ='$score' WHERE student_id = '$student_id' AND module_id = '$module_id'");
break;
default:
$output.="parameters missing"."</Status>";
echo $output."</Result>";
exit;
}
$output.=($query)?("score assign success"):"score assign failure";
echo $output."</Status></Result>";
?>
