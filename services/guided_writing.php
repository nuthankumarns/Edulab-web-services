<?php
include'set_xml_session.php';
include 'setup.php';

header("Content-Type: application/xml");
//$oSession = new Session();


$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$uid=$_SESSION['UID'];
//var_dump($uid);
//$uid='104';
$word=$_REQUEST['word'];
//$guided_count=$_REQUEST['guided_count'];
//www.tritonetech.com/php_uploads/porsche/webservice/guided_writing.php?word=&self_content_id=&ordering=
/*---------------------------------------------------------stroke request count----------------------------------*/
$self_content_id=$_REQUEST['self_content_id'];
$ordering=$_REQUEST['ordering'];
if($word=='' || $self_content_id=='' || $ordering=='')
{
$output.="<Status>"."parameters missing"."</Status></Result>";
echo $output;
exit;

}
//$query=$_DB->Query("select a.id,a.user_id,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
//$student_id=$_DB->GetResultValue($query,0,'student_id');
//$a="update jos_porsche_self_writing_task set guided_count='guided_count+1' where self_content_id='$self_content_id' and word='$word'";
//echo $a;
$qry=$_DB->Query("SELECT id from jos_porsche_self_writing_task where self_content_id='$self_content_id' and word='$word' and ordering='$ordering'");
//$a="SELECT id from jos_porsche_self_writing_task where self_content_id='$self_content_id' and word='$word' and ordering='$ordering'";
//echo $a;
$count=$_DB->GetResultNumber($qry);
//echo $count;
switch($count){
case'1':
$qid=$_DB->GetResultValue($qry,0,'id');
//echo $qid;
//$a="update jos_porsche_self_writing_task SET guided_count=guided_count+1 where id='$qid'";
//echo $a;
$query=$_DB->Execute("update jos_porsche_self_writing_task SET guided_count=guided_count+1 where id='$qid'");
//var_dump($query);
//$_DB->Execute("update jos_porsche_student_module SET meaning_req=meaning_req+1 where student_id='$id'");
	if($query==true)
	{
$query=$_DB->Query("select id,guided_count from jos_porsche_self_writing_task where word='$word' and self_content_id='$self_content_id' and ordering='$ordering'");
	//var_dump($query);
	$count=$_DB->GetResultValue($query,0,'guided_count');
	$qid=$_DB->GetResultValue($query,0,'id');
	$output.="<Status>"."tracking guided writing count successful"."</Status><Count>".$count."</Count><qid>".$qid."</qid></Result>";
	
	}
	else
	{
	$output.="<Status>"."guided writing count track failed"."</Status></Result>";
	}
break;
case'0':
$query=$_DB->Execute("INSERT INTO jos_porsche_self_writing_task values('','$self_content_id','$ordering','$word','1','','','','','','','')");
	$qid=mysql_insert_id();
	if($query==true)
	{
	$query=$_DB->Query("select guided_count from jos_porsche_self_writing_task where word='$word' and self_content_id='$self_content_id' and ordering='$ordering'");
	//var_dump($query);
	$count=$_DB->GetResultValue($query,0,'guided_count');
	$output.="<Status>"."tracking guided writing count successful"."</Status><Count>".$count."</Count><qid>".$qid."</qid></Result>";
	}
	else
	{
	$output.="<Status>"."guided writing count track failed"."</Status></Result>";
	}

}



echo $output;
?>
