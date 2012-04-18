<?php
include'set_xml_session.php';
include'setup.php';
$qid=$_REQUEST['qid'];
header("content-type:application/xml");
//www.tritonetech.com/php_uploads/porsche/webservice/self_reading_assignment_audio.php?qid=
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$query=$_DB->Query("SELECT filename FROM jos_porsche_self_reading_task WHERE self_content_id = '$qid'");
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$output.="<Status>"."user has not answered"."</Status>";
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$filename=($_DB->GetResultValue($query,$i,'filename'))!=('')?($_DB->GetResultValue($query,$i,'filename')):"user has not uploaded audio";
	$output.="<Data><qid>".$qid."</qid><filename>".$filename."</filename></Data>";
	}
}
echo $output."</Result>";
?>
