<?php
include'set_session.php';
include'setup.php';
require_once'FastJSON.class.php';
//date_default_timezone_set('Asia/Calcutta');
//www.tritonetech.com/php_uploads/porsche/webservice/trigger_group_activity.php?id=&task=&start=&duration=
$now=time();
$duration=$_REQUEST['duration'];
//echo $duration;

$id=$_REQUEST['id'];
if($_REQUEST[duration]!='')
{
	switch($_REQUEST[task]){
	case'group_read':
	$query=$_DB->Execute("UPDATE jos_porsche_class_module SET read_start='$now',read_duration='$duration' where id='$id'");
	break;
	case'group_write':
	$query=$_DB->Execute("UPDATE jos_porsche_class_module SET write_start='$now',write_duration='$duration' where id='$id'");
	break;
	}
}
else
{
$query=$_DB->Execute("UPDATE jos_porsche_class_module SET $_REQUEST[task]='0' where id='$id'");
}

	
	//$_DB->Execute("UPDATE jos_porsche_student_module set module_id='$module_id' 
	$dataDB['Result']['Data'][0]['Status']=($query)?("assign success"):"assign failure";
echo FastJSON::encode($dataDB);
?>
