<?php
//include'set_session.php';
include 'setup.php';
require 'FastJSON.class.php';

$uid=$_SESSION['UID'];
//www.edulabcloud.com/webservice/check_content_audio.php?ability=&content_id=
$ability=$_REQUEST['ability'];
$content_id=$_REQUEST['content_id'];
$query=$_DB->Query("select filename from jos_porsche_".$ability."_lesson where id='$content_id'");
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$dataDB['Result']['Data'][0]=array('Status'=>"No content");
}
else
{
$result=$_DB->GetResultValue($query,0,'filename');
switch($result){
case'':
$dataDB['Result']['Data'][0]['Status']='file not uploaded';
break;
default:
$dataDB['Result']['Data'][0]['filename']=$_DB->GetResultValue($query,0,'filename');
break;
}
}
echo FastJSON::encode($dataDB);
?>
