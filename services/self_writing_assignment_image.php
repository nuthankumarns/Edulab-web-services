<?php
include'set_xml_session.php';
include'setup.php';
$qid=$_REQUEST['qid'];
header("content-type:application/xml");
//www.tritonetech.com/php_uploads/porsche/webservice/self_writing_assignment_image.php?qid=
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$query=$_DB->Query("SELECT written_image,word
FROM jos_porsche_self_writing_task
WHERE self_content_id = '$qid'");
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$output.="<Status>"."user has not answered"."</Status>";
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$filename=($_DB->GetResultValue($query,$i,'written_image'))!=('')?($_DB->GetResultValue($query,$i,'written_image')):"user has not uploaded image";
	$output.="<Data><qid>".$qid."</qid><word>".$_DB->GetResultValue($query,$i,'word')."</word><filename>".$filename."</filename></Data>";
	}
}
echo $output."</Result>";
