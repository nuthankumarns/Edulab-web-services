<?php
include'set_session.php';
include'setup.php';
require_once'FastJSON.class.php';

$student_id=$_REQUEST['student_id'];
$module_id=$_REQUEST['module_id'];
$task=$_REQUEST['task'];
$time_taken=$_REQUEST['time_taken'];
//www.tritonetech.com/php_uploads/porsche/webservice/alert_task_time.php?time
if($student_id=='' || $module_id=='' ||$task=="" || $time_taken=='')
{$dataDB['Result']['Data'][0]['Status']="missing_parameters";
echo FastJSON::encode($dataDB);
exit;
}
$query=$_DB->Execute("UPDATE jos_porsche_student_module SET time_taken =  '$time_taken'  WHERE student_id = '$student_id' AND module_id = '$module_id' and task='$task' ");
$dataDB['Result']['Data'][0]['Status']=($query)?("time track success"):"time track failure";
echo FastJSON::encode($dataDB);
?>
