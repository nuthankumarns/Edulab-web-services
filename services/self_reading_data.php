<?php
//include 'set_session.php';
include 'setup.php';
include 'FastJSON.class.php';
//www.tritonetech.com/php_uploads/porsche/webservice/self_reading_data.php?student_id=&module_id=
$student_id=$_REQUEST['student_id'];
$module_id=$_REQUEST['module_id'];


$query=$_DB->Query("SELECT a.student_id, a.id, a.task, b.id, b.self_content_id, b.playback_count, b.record_count, b.total_time, b.time_taken
FROM jos_porsche_self AS a
JOIN jos_porsche_self_reading_task AS b ON a.id = b.self_content_id
WHERE a.student_id = '$student_id'
AND task = 'self_read'
AND module_id = '$module_id'");
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$dataDB['Result']['Data'][0]['Status']="No data";
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$dataDB['Result']['Data'][$i]['playback_count']=$_DB->GetResultValue($query,$i,'playback_count');
	$dataDB['Result']['Data'][$i]['record_count']=$_DB->GetResultValue($query,$i,'record_count');
	$dataDB['Result']['Data'][$i]['total_time']=$_DB->GetResultValue($query,$i,'total_time');
			$time_taken=explode(' ',$_DB->GetResultValue($query,$i,'time_taken'));
		if($_DB->GetResultValue($query,$i,'time_taken')=='')
		{
		$dataDB['Result']['Data'][$i]['time_of_each_recording'][1]="user has not recorded";
		break;
		}
		unset($time_taken[0]);
		//print_r($time_taken);
		for($j=1;$j<=count($time_taken);$j++)
		{
		$dataDB['Result']['Data'][$i]['time_of_each_recording'][$j]=$time_taken[$j];
		}	
		

	}
}
echo FastJSON::encode($dataDB);


?>
