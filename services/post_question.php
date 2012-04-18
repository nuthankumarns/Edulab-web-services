<?php
include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");


$uid=$_SESSION['UID'];
//echo $uid;
//$uid='104';


//www.tritonetech.com/php_uploads/porsche/webservice/post_question.php?ids=&question=&content_id=&group_content_id=
$output="<?xml version='1.0' encoding='utf-8'?><Result><Status>";
//$group_id=$_REQUEST['group_id'];
//$group_id=$_REQUEST['group_id'];
$question=stripslashes($_REQUEST['question']);
$array=stripslashes($_REQUEST['ids']);
$content_id=$_REQUEST['content_id'];
$group_content_id=$_REQUEST['group_content_id'];
$module_id=$_REQUEST['module_id'];
if($question=='' || $content_id=='' || $array=='' || $group_content_id=='')
{
$output.="missing parameters";
echo $output."</Status></Result>";
exit;
}
$a=json_decode($array,true);
//print_r($a);
//$ids=implode(',',$a);
$query=$_DB->Query("select a.id,a.user_id,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student_id=$_DB->GetResultValue($query,0,'student_id');
$group_id=$_DB->GetResultValue($query,0,'group_id');
for($i=0;$i<count($a);$i++)
	{
$time=time();
$_DB->Execute("insert into jos_porsche_group_reading_task(group_content_id,student_id,q_s_id,question,content_id,posted_time) values('$group_content_id','$student_id','$a[$i]','$question','$content_id','$time')");
}
$d=mysql_insert_id();
//echo $d;
if($d!='')
{
$output.="content uploaded successfully";
}
else
{
$output.="content not uploaded";
}
echo $output."</Status></Result>";
?>
