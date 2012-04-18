<?php
include'set_xml_session.php';
include 'setup.php';
include 'FastJSON.class.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";

$uid=$_SESSION['UID'];
//www.tritonetech.com/php_uploads/porsche/webservice/post_writing_task.php?ids=&content_id=&group_content_id=
$array=stripslashes($_REQUEST['ids']);
$group_content_id=$_REQUEST['group_content_id'];
$content_id=$_REQUEST['content_id'];
if($content_id=='' || $array=='' || $group_content_id=='')
{
$output.="<Status>"."missing parameters"."</Status></Result>";
echo $output;
exit;
}
$a=json_decode($array,true);
$query=$_DB->Query("select a.id,a.user_id,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student_id=$_DB->GetResultValue($query,0,'student_id');
$group_id=$_DB->GetResultValue($query,0,'group_id');
$time=time();
for($i=0;$i<count($a);$i++)
{
$_DB->Execute("INSERT INTO jos_porsche_group_writing_task(group_content_id,student_id,q_s_id,content_id,posted_time) VALUES('$group_content_id','$student_id','$a[$i]','$content_id','$time')");
}
$d=mysql_insert_id();
if($d=='')
{
$output.="<Status>"."error in posting task"."</Status></Result>";

}
else
{
$output.="<Status>"."posted question"."</Status></Result>";

}
echo $output;
?>
