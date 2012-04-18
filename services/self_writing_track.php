<?php
include'set_xml_session.php';
include 'setup.php';

header("Content-Type: application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$uid=$_SESSION['UID'];
//var_dump($uid);
//$uid='104';
$word=$_REQUEST['word'];
//$guided_count=$_REQUEST['guided_count'];
//www.tritonetech.com/php_uploads/porsche/webservice/self_writing_track.php?word=&self_content_id=&self_time=&error=&ordering=

$self_content_id=$_REQUEST['self_content_id'];
$self_time=$_REQUEST['self_time'];
$error=$_REQUEST['error'];
$ordering=$_REQUEST['ordering'];
if($word=='' || $self_content_id=='' || $ordering=='')
{
$output.="<Status>"."parameters missing"."</Status></Result>";
echo $output;
exit;

}
//$query=$_DB->Query("select a.id,a.user_id,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
//$student_id=$_DB->GetResultValue($query,0,'student_id');
//$a="update jos_porsche_self_writing_task SET self_count=guided_count+1, self_time=ADDTIME(self_time,'11:11:33'), self_error=self_error+3 where word='木' and self_content_id='1';
//echo $a;
$qry=$_DB->Query("SELECT id from jos_porsche_self_writing_task where (self_content_id='$self_content_id' and word='$word' and ordering='$ordering')");
$count=$_DB->GetResultNumber($qry);
//echo $count;
switch($count){
case'1':
$query=$_DB->Execute("update jos_porsche_self_writing_task SET self_count=self_count+1, self_time=ADDTIME(self_time,'$self_time'), self_error=self_error+$error where word='$word' and self_content_id='$self_content_id' and ordering='$ordering'");
//$_DB->Execute("update jos_porsche_student_module SET meaning_req=meaning_req+1 where student_id='$id'");
	if($query==false)
	{
	$output.="<Status>"."self writing count track failed"."</Status></Result>";
	}
	else
	{
	$query=$_DB->Query("select self_count from jos_porsche_self_writing_task where word='$word' and self_content_id='$self_content_id' and ordering='$ordering'");
	$count=$_DB->GetResultValue($query,0,'self_count');
	$output.="<Status>"."tracking self writing count successful"."</Status><Count>".$count."</Count></Result>";
	}
break;
case'0':
//$query=$_DB->Execute("INSERT INTO jos_porsche_self_writing_task values('','$self_content_id','$word','','1','$self_time','$error','','','','')");
	//if($query==false)
	//{
	$output.="<Status>"."play guided writing first"."</Status></Result>";
	/*}
	else
	{
	//$query=$_DB->Query("select self_count from jos_porsche_self_writing_task where word='$word' and self_content_id='$self_content_id'");
	//$count=$_DB->GetResultValue($query,0,'self_count');
	$output.="<Status>"."tracking self writing count successful"."</Status><Count>"."1"."</Count></Result>";
	}*/
break;
}

echo $output;
?>
