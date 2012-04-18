<?php
include'set_xml_session.php';
include 'setup.php';
include 'upload.class.php';
require_once 'FastJSON.class.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='UTF-8'?><Result><Status>";
session_start();
$uid=$_SESSION['UID'];
//www.tritonetech.com/php_uploads/porsche/webservice/upload_self_reading_audio.php

$up_obj = new UploadAudioFile();
$data=$up_obj->upload_file("./self_reading_audio/");
$filename=$_FILES['userfile']['name'];
$qid=basename($filename,".wav");
//$self_reading_task_id=basename($filename,".wav");
//var_dump($self_content_id);
switch($data){
case "true":
$query=$_DB->Query("select * from jos_porsche_self_reading_task where filename='$filename'");
$_DB->Execute("UPDATE jos_porsche_self SET end_time = NOW( ) WHERE id = ( SELECT self_content_id
FROM jos_porsche_self_reading_task
WHERE id = '$qid' )"); 
//UPDATE jos_porsche_self AS a LEFT JOIN jos_porsche_self_reading_task AS b ON a.id = b.self_content_id SET a.end_time = 'NOW()' WHERE b.id = '13' 
if($query==true)
	{
	$status="updated audio successfully";
	}
	else
	{
	$query=$_DB->Execute("UPDATE jos_porsche_self_reading_task SET filename='$filename' where id='$qid'");
		if($query==true)
		{
		$status="audio upload successful";
		}
		else
		{
		$status="audio cannot be uploaded";
		}	
	}
break;
case "false":
$status="please select audio file";
break;
case "size":
$status="size exceeded";
break;
case "invalid":
$status="invalid filetype";
break;
default:
$status="please choose file";
}
$output.=$status."</Status></Result>";

echo $output;
?>
