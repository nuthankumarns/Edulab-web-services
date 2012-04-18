<?php
include'set_xml_session.php';
include("setup.php");
include("upload.class.php");
header("content-type:application/xml");
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">";
//echo"<pre>";
$output="<?xml version='1.0' encoding='utf-8'?><Result><Status>";
//www.tritonetech.com/php_uploads/porsche/webservice/upload_self_writing_image.php

$up_obj = new UploadAudioFile();
$data=$up_obj->upload_file("./self_writing_image/");
//$filename=$_FILES['userfile']['name'];

$filename=basename($_FILES['userfile']['name']);

$qid=rtrim($filename,'.jpeg');
switch($data){
case "true":
$query=$_DB->Query("select * from jos_porsche_self_writing_task where written_image='$filename'");
$_DB->Execute("UPDATE jos_porsche_self SET end_time = NOW( ) WHERE id = ( SELECT self_content_id
FROM jos_porsche_self_writing_task
WHERE id = '$qid' )"); 
if($query==true)
	{
	$output.="image updated successfully";
	}
	else
	{
	$query=$_DB->Execute("UPDATE jos_porsche_self_writing_task SET written_image='$filename' where id='$qid'");
		if($query==true)
		{
		$output.="image upload successful";
		}
		else
		{
		$output.="image upload failure";
		}	
	}
echo $output."</Status></Result>";
break;
case "false":
$status="please select image file";
	$output.=$status."</Status></Result>";
	echo $output;
	//exit;
break;
case "size":
$status="size exceeded";
	$output.=$status."</Status></Result>";
	echo $output;
	//exit;
break;
case "invalid":
$status="invalid filetype";
	$output.=$status."</Status></Result>";
	echo $output;
	//exit;
break;
default:
$status="please choose file";
	$output.=$status."</Status></Result>";
	echo $output;
	//exit;
}
?>
