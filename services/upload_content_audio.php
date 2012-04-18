<?php
include'set_xml_session.php';
include 'setup.php';
include("upload.class.php");
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result><Status>";
$ability=$_REQUEST['ability'];
$up_obj = new UploadAudioFile();
$data=$up_obj->upload_file("./content_audio/");
$filename=$_FILES['userfile']['name'];
$file=rtrim($filename,'.wav');
$content_id=ltrim($file,$ability);

switch($data){
case "true":

//$query=$_DB->Query("select * from jos_porsche_$ where words='$word'");
//$count=$_DB->GetResultNumber($query);
	/*if($count==0)
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
	{*/
	$query=$_DB->Execute("update jos_porsche_".$ability."_lesson set filename='$filename',time=now() where id='$content_id'");
$a="update jos_porsche_".$ability."_lesson set filename='$filename' where id='$content_id'";
//echo $a;
		if($query==true)
		{
		$status="audio file added";
		}
		else
		{
		$status="audio upload failed";
		}
	//}


	
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

