<?php
include'set_session.php';
include'setup.php';
require_once'FastJSON.class.php';
//date_default_timezone_set('Asia/Calcutta');
//www.tritonetech.com/php_uploads/porsche/webservice/add_task.php?task=&id=

//echo $duration;

$id=$_REQUEST['id'];
if($id!='')
{
	
	$query=$_DB->Execute("UPDATE jos_porsche_class_module SET $_REQUEST[task]='1' WHERE id='$id'");
	
	
}

	
	//$_DB->Execute("UPDATE jos_porsche_student_module set module_id='$module_id' 
	$dataDB['Result']['Data'][0]['Status']=($query)?("assign success"):"assign failure";
echo FastJSON::encode($dataDB);
?>
