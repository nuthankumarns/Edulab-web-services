<?php
include'set_session.php';
include "setup.php";
//include "config.php";
$uid = $_SESSION['UID'];
$times_requested = $_REQUEST['times_requested'];

$rtime=date('y-m-d H:i:s');






$query=$_DB->Query("select a.user_id,a.name from jos_porsche_student as a  where user_id='$uid'");
$student_id=$_DB->GetResultValue($query,0,'user_id');
$student_name=$_DB->GetResultValue($query,0,'name');

//echo $student_id."name";



	if($student_id!='' && $times_requested!='')
	{
	$query=$_DB->Execute("update jos_porsche_student set times_requested=times_requested+1 ,rtime='$rtime' where user_id='$student_id'");

	$m=mysql_affected_rows();
	$dataDB['Result']['Data'][0]=array('Status'=>"Help updated",'user_id'=>"$student_id");
	echo json_encode($dataDB);		

	

	}else
	{
	$dataDB['Result']['Data'][0]=array('Status'=>"Invalid data");
	echo json_encode($dataDB);
	}






?>
