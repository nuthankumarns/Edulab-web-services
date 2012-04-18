<?php
include'set_session.php';
include'setup.php';
/*$idletime=60;//after 60 seconds the user gets logged out
$oSession->clean($idletime);
if (time()-$_SESSION['timestamp']>$idletime){
header("location:logout_api.php");
}else{
    $_SESSION['timestamp']=time();
}*/
require_once'FastJSON.class.php';
$student_id=$_REQUEST['student_id'];
$module_id=$_REQUEST['module_id'];
$task=$_REQUEST['task'];
//$time_taken=$_REQUEST['time_taken'];
//www.tritonetech.com/php_uploads/porsche/webservice/reset_alert_time.php?student_id=&module_id=&task=
if($student_id=='' || $module_id=='' ||$task=="")
{$dataDB['Result']['Data'][0]['Status']="missing_parameters";
echo FastJSON::encode($dataDB);
exit;
}
$query=$_DB->Execute("UPDATE jos_porsche_student_module SET time_taken = '00:00:00'  WHERE student_id = '$student_id' AND module_id = '$module_id' and task='$task' ");
$dataDB['Result']['Data'][0]['Status']=($query)?("time track reset"):"time not reset";
echo FastJSON::encode($dataDB);
?>
