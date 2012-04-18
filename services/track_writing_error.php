<?php
include'set_xml_session.php';
include 'setup.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";

//include 'FastJSON.class.php';
//www.tritonetech.com/php_uploads/porsche/webservice/track_writing_error.php?track_id=&time=&error=
/*-----------------------------------------------------group writing track---------------------------------------------*/
$track_id=$_REQUEST['track_id'];
$time=$_REQUEST['time'];
$error=$_REQUEST['error'];

if($track_id=='' || $time=='' || $error=='')
{
$output.="<Status>"."missing parameters"."</Status></Result>";
echo $output;
exit;
}

$query=$_DB->Execute("UPDATE jos_porsche_group_writing_track_word SET error='$error',time='$time' where id='$track_id'");
if($query==true)
{
$output.="<Status>"."error tracking successful"."</Status></Result>";

}
else
{
$output.="<Status>"."error tracking failed"."</Status></Result>";

}

echo $output;
?>
