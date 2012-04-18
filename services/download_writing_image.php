<?php
include'set_session.php';
include 'setup.php';
require 'FastJSON.class.php';

$uid=$_SESSION['UID'];
//www.tritonetech.com/php_uploads/porsche/webservice/download_writing_image.php?qid=
$qid=$_REQUEST['qid'];
$content_id=$_REQUEST['content_id'];
$query=$_DB->Query("select * from jos_porsche_group_writing_task where id='$qid' AND image!=''");
//$a="select * from jos_porsche_group_writing_track where qid='$qid'";
//echo $a;
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$dataDB['Result']['Data'][0]=array('Status'=>"user has not answered");
}
else
{
$result=$_DB->GetResultValue($query,0,'image');
switch($result){
case'':
$dataDB['Result']['Data'][0]['Status']='user has not answered';
//$dataDB['Result']['Data'][0]['qid']=$_DB->GetResultValue($query,0,'id');
break;
default:
$dataDB['Result']['Data'][0]['filename']=$_DB->GetResultValue($query,0,'image');
$dataDB['Result']['Data'][0]['qid']=$_DB->GetResultValue($query,0,'id');
break;
}
}
echo FastJSON::encode($dataDB);
?>
