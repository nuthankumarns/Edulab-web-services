<?php
include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");
//www.tritonetech.com/php_uploads/porsche/webservice/display_meaning.php?word=&task=&qid=
$output="<?xml version='1.0' encoding='utf-8'?><Result>";

$word=stripslashes($_REQUEST['word']);
$task=$_REQUEST['task'];
//task=self_reading
$qid=$_REQUEST['qid'];
$qury=$_DB->Query("select meaning from jos_porsche_words where words='$word'");
$count=$_DB->GetResultNumber($qury);
	if($count==0)
	{
	
	$output.="<Status>"."no meaning exist in the database"."</Status>";
	}
	else
	{
	$qry=$_DB->Query("SELECT self_reading_task_id FROM jos_porsche_self_reading_track_word where self_reading_task_id='$qid' and word='$word'");
	$cnt=$_DB->GetResultNumber($qry);
//echo $cnt;
		switch($cnt)
		{
		case'0':
		$query=$_DB->Execute("INSERT INTO jos_porsche_self_reading_track_word values('','$qid','$word','1')");
			if($query==true)
			{
			$status="reading request tracking successful";
			}
			else
			{
			$status="reading request tracking failed";		
			}
		break;
		case'1':
		$qid=$_DB->GetResultValue($qry,0,'self_reading_task_id'); 
		$query=$_DB->Execute("UPDATE jos_porsche_self_reading_track_word SET reading_request=reading_request+1 where self_reading_task_id='$qid' and word='$word'");
			if($query==true)
			{
			$status="reading request tracking successful";
			}
			else
			{
			$status="reading request tracking failed";
			}
;
		break;
		}
	

	$result=$_DB->GetResultValue($qury,0,'meaning');
$query=$_DB->Query("SELECT reading_request FROM jos_porsche_self_reading_track_word where self_reading_task_id='$qid' and word='$word'");
//var_dump($query);
	$cnt=$_DB->GetResultValue($query,0,'reading_request');
	$output.="<Data>".$result."</Data><Status>".$status."</Status><Count>".$cnt."</Count>";
	}

echo $output."</Result>";
?>
