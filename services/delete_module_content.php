<?php
include'setup.php';
//require_once'set_xml_session.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$content_id=$_REQUEST['content_id'];
$ability=$_REQUEST['ability'];
//www.edulabcloud.com/webservice/delete_module_content.php?content_id=&ability=
$query=$_DB->Execute("DELETE FROM jos_porsche_".$ability."_lesson WHERE id='$content_id'");
//$output.=($query)?"<Status>delete success</Status>":"<Status>delete failure</Status>";
//$count=$_DB->GetResultNumber($count);
echo is_resource($query);
if($query>0)
{
$output.="<Status>delete success</Status>";
}
else
{
$output.="<Status>delete failure</Status>";
}
echo $output."</Result>";
?>
