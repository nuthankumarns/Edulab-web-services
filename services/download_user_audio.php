<?php
include'set_session.php';
include 'setup.php';
require 'FastJSON.class.php';

$uid=$_SESSION['UID'];
//www.tritonetech.com/php_uploads/porsche/webservice/download_user_audio.php?qid=
$qid=$_REQUEST['qid'];
$content_id=$_REQUEST['content_id'];
$query=$_DB->Query("select * from jos_porsche_group_reading_task where id='$qid'");
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$dataDB['Result']['Data'][0]=array('Status'=>"user has not answered");
}
else
{
$result=$_DB->GetResultValue($query,0,'filename');
switch($result){
case'':
$dataDB['Result']['Data'][0]['filename']='user has not answered';
//$dataDB['Result']['Data'][0]['qid']=$_DB->GetResultValue($query,0,'id');
break;
default:
$dataDB['Result']['Data'][0]['filename']=$_DB->GetResultValue($query,0,'filename');
$dataDB['Result']['Data'][0]['qid']=$_DB->GetResultValue($query,0,'id');
break;
}
}
echo FastJSON::encode($dataDB);
?>
