<?php
include'set_xml_session.php';
include("setup.php");
include("upload.class.php");
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result><Status>";

$up_obj = new UploadAudioFile();
$data=$up_obj->upload_file("./audio/");
$filename=$_FILES['userfile']['name'];
$word=rtrim($filename,'.wav');

//wwww.tritonetech.com/php_uploads/porsche/webservice/upload_audio.php
switch($data){
case "true":

$query=$_DB->Query("select * from jos_porsche_words where words='$word'");
$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$query=$_DB->Execute("insert into jos_porsche_words values('','$word','1','','NOW()')");
		if($query==true)
		{
		$status="audio file added";
		$output.=$status."</Status></Result>";
		echo $output;
		}
		else
		{
		$status="audio file not added";
		$output.=$status."</Status></Result>";
		echo $output;

		}
	}
	else
	{
	$query=$_DB->Execute("update jos_porsche_words set audio='1',time=NOW() where words='$word'");
		if($query==true)
		{
		$status="audio file added";
		$output.=$status."</Status></Result>";
		echo $output;
		}
		else
		{
		$status="audio file not added";
		$output.=$status."</Status></Result>";
		echo $output;

		}
	}


	
break;
case "false":
$status="please select audio file";
	$output.=$status."</Status></Result>";
	echo $output;
break;
case "size":
$status="size exceeded";
	$output.=$status."</Status></Result>";
	echo $output;
break;
case "invalid":
$status="invalid filetype";
	$output.=$status."</Status></Result>";
	echo $output;
break;
default:
$status="please choose file";
	$output.=$status."</Status></Result>";
	echo $output;
}



?>

