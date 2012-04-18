<?php

include'set_session.php';
include'setup.php';
//http://www.tritonetech.com/php_uploads/porsche/webservice/help_view.php


$uid=$_SESSION['UID'];


$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
$class_id=$_REQUEST['class_id'];
if($uid=='' )
{
$dataDB['Result']['Data'][]=array('Status'=>"Parameters Missing");
	echo json_encode($dataDB);
	exit;
}

$query=$_DB->Query("select id,name,times_requested,rtime from jos_porsche_student where school_id in (select school_id from jos_porsche_schools_admin where admin_id='$uid') and times_requested >0 and rtime>0 ORDER BY rtime DESC");

//echo $uid."hh";
	$count = $_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No students");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0; $i < $count; $i++)
        	{
            	$dataDB['Result']['Data'][$i]['student_id'] = $_DB->GetResultValue($query, $i,"id");
		$dataDB['Result']['Data'][$i]['name'] = $_DB->GetResultValue($query, $i,"name");
		$dataDB['Result']['Data'][$i]['times_requested'] = $_DB->GetResultValue($query, $i,"times_requested");
		$dataDB['Result']['Data'][$i]['rtime'] = $_DB->GetResultValue($query, $i,"rtime");

		}
	echo json_encode($dataDB);
	}


?>
