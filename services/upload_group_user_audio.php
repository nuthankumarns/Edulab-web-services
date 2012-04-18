<?php
include'set_xml_session.php';
include("setup.php");
include("upload.class.php");
$uid=$_SESSION['UID'];
//$uid='107';
header("content-type:application/xml");
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">";
//echo"<pre>";
$output="<?xml version='1.0' encoding='utf-8'?><Result><Status>";

//www.tritonetech.com/php_uploads/porsche/webservice/upload_group_user_audio.php
$up_obj = new UploadAudioFile();
$data=$up_obj->upload_file("./group_user_audio/");
$filename=$_FILES['userfile']['name'];
$qid=rtrim($filename,'.wav');
//echo $qid;
/*$word=strrev($word);
//echo $word."<br/>";
$content_id=trim(strstr($word, '_'),'_');
$content_id=strrev($content_id);*/
//echo $content_id;
switch($data){
case "true":

/*$query=$_DB->Query("select a.id,a.user_id,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student=$_DB->GetResultValue($query,0,'student_id');
$query=$_DB->Query("select id from jos_porsche_group_reading_task where q_s_id='$student_id' and content_id='$content_id'");
$qid=$_DB->GetResultValue($query,0,'id');*/
//$query=$_DB->Query("select * from jos_porsche_group_reading_task where id='$qid'");
//$count=$_DB->GetResultValue($query,0,'filename');
//print_r($count);
	//switch($count){
	//case'NULL':
	$time=time();
	$query=$_DB->Execute("UPDATE jos_porsche_group_reading_task set filename='$filename',ans_time='$time' where id='$qid'");
//var_dump($query);
		if($query==true)
		{
		$status="audio upload successful";
		$output.=$status."</Status></Result>";
		echo $output;
	//echo "success";
		//exit;
		}
		else
		{
		$status="audio upload failed";
		$output.=$status."</Status></Result>";
		echo $output;
		}
	
break;
case "false":
$status="please select audio file";
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
break;
default:
$status="please choose file";
	$output.=$status."</Status></Result>";
	echo $output;
	//exit;
}
?>
