<?php
include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$uid=$_SESSION['UID'];
//http://www.tritonetech.com/php_uploads/porsche/webservice/self_writing_count_check.php?self_content_id=

require_once'FastJSON.class.php';
$self_content_id=$_REQUEST['self_content_id'];
$word=$_REQUEST['word'];
$ordering=$_REQUEST['ordering'];
if($self_content_id=='')
{
$output.="<Status>"."missing parameters"."</Status></Result>";
echo $output;
exit;
}
$query=$_DB->Query("SELECT a.id, b.id AS qid,b.word,b.ordering, b.self_content_id, b.guided_count, b.self_count, b.self_error, b.unaided_count, b.unaided_error,b.written_image
FROM jos_porsche_self AS a
LEFT JOIN jos_porsche_self_writing_task AS b ON a.id = b.self_content_id
WHERE b.self_content_id = '$self_content_id' ORDER BY ordering DESC LIMIT 1");
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$output.="<Status>"."not started"."</Status>";
}
else
{//word 	guided_count 	self_count 	self_time 	self_error 	unaided_count 	unaided_time 	unaided_error 	written_image image of the word
$output.="<qid>".$_DB->GetResultValue($query,0,'qid')."</qid>
<word>".$_DB->GetResultValue($query,0,'word')."</word>
<ordering>".$_DB->GetResultValue($query,0,'ordering')."</ordering>
<guided_count>".$_DB->GetResultValue($query,0,'guided_count')."</guided_count>
<self_count>".$_DB->GetResultValue($query,0,'self_count')."</self_count>
<unaided_count>".$_DB->GetResultValue($query,0,'unaided_count')."</unaided_count>";
}
echo $output."</Result>";

?>
