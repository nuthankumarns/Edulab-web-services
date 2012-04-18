<?php
include'set_xml_session.php';
include 'setup.php';
include 'upload.class.php';

//header("content-type:application/xml");
$output="<?xml version='1.0' encoding='UTF-8'?><Result><Status>";
$uid=$_SESSION['UID'];
//www.tritonetech.com/php_uploads/porsche/webservice/upload_group_writing_image.php

$up_obj = new UploadAudioFile();
$data=$up_obj->upload_file("./group_writing_image/");
$filename=$_FILES['userfile']['name'];
$qid=basename($filename,".jpeg");
//$self_reading_task_id=basename($filename,".wav");
//var_dump($self_content_id);
switch($data){
case "true":
$query=$_DB->Query("select * from jos_porsche_group_writing_task where image='$filename'");
$count=$_DB->GetResultNumber($query);
if($count!=0)
	{
	$status="updated image successfully";
	}
	else
	{
	$time=time();
	$query=$_DB->Execute("UPDATE jos_porsche_group_writing_task SET image='$filename',ans_time='$time' where id='$qid'");
		if($query==true)
		{
		$status="image upload successful";
		}
		else
		{
		$status="play the content first";
		}	
	}
break;
case "false":
$status="please select image file";
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
