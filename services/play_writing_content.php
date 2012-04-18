<?php
include'set_xml_session.php';
include 'setup.php';
header("content-type:application/xml");
//www.tritonetech.com/php_uploads/porsche/webservice/play_writing_content.php?qid=&word=
$output="<?xml version='1.0' encoding='utf-8'?><Result>";

$qid=$_REQUEST['qid'];
$word=$_REQUEST['word'];
if($qid=='' || $word=='')
{
$output.="<Status>"."parameters missing"."</Status></Result>";
echo $output;
exit;
}
/*----------------------------------------------------track content playback count-----------------------------------------------------------*/
//$query=$_DB->Query("SELECT a.id,a.qid,b.track_word_id,b.word,b.id as track_id from jos_porsche_group_writing_track as a left join jos_porsche_group_writing_track_word as b on a.id=b.track_word_id where a.qid='$qid' and b.word='$word'");
$query=$_DB->Query("SELECT id as track_id
FROM jos_porsche_group_writing_track_word
WHERE track_word_id = '$qid' and word='$word'");
$count=$_DB->GetResultNumber($query);
switch($count){
case'0':
//$_DB->Execute("INSERT INTO jos_porsche_group_writing_track (qid)values('$qid')");
//$track_word_id=mysql_insert_id();

$_DB->Execute("INSERT INTO jos_porsche_group_writing_track_word (track_word_id,word,playback_count) VALUES('$track_word_id','$word','1')");
$track_id=mysql_insert_id();
$output.="<word>".$word."</word><count>".'1'."</count><track_id>".$track_id."</track_id></Result>";
break;
case'1':
$track_id=$_DB->GetResultValue($query,0,'track_id');
$_DB->Execute("UPDATE jos_porsche_group_writing_track_word SET playback_count=playback_count+1 where id='$track_id'");
$query=$_DB->Query("SELECT id,playback_count from jos_porsche_group_writing_track_word where id='$track_id'");
$cnt=$_DB->GetResultValue($query,0,'playback_count');
$output.="<word>".$word."</word><count>".$cnt."</count><track_id>".$track_id."</track_id></Result>";
break;
default:
$output.="<Status>"."track failed"."</Status>";
break;
}
echo $output;
?>

