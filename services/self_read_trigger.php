<?php
include'set_session.php';
include'setup.php';
include'FastJSON.class.php';
//www.tritonetech.com/php_uploads/porsche/webservice/self_read_trigger.php?self_content_id=
/*-------------------------content_id---------------- differentiation*/
$self_content_id=$_REQUEST['self_content_id'];
if($self_content_id=='')
{
$dataDB['Result']['Data'][0]['Status']=("missing parameters");
echo FastJSON::encode($dataDB);
exit;
}
$qry=$_DB->Query("SELECT id FROM jos_porsche_self_reading_task where self_content_id='$self_content_id'");
$count=$_DB->GetResultNumber($qry);
switch($count){
	case'0':
	$query=$_DB->Execute("INSERT INTO jos_porsche_self_reading_task(self_content_id) VALUES('$self_content_id')");
//("INSERT INTO jos_porsche_self_reading_task(self_content_id)values('$self_content_id') ON DUPLICATE KEY UPDATE self_content_id='$self_content_id'");
		if($query)
		{
		$qid=mysql_insert_id();
		$dataDB['Result']['Data'][0]['Status']="self_read_started";
		$dataDB['Result']['Data'][0]['qid']=(string)$qid;
		}
		else
		{
		$dataDB['Result']['Data'][0]['Status']=("task failure");
		}
	break;
	case'1':
	$dataDB['Result']['Data'][0]['Status']=("self_read_started");
	$dataDB['Result']['Data'][0]['qid']=$_DB->GetResultValue($qry,0,'id');
	break;
}
	echo FastJSON::encode($dataDB);
?>
