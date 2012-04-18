<?php
include'set_session.php';
include'setup.php';
include'FastJSON.class.php';

//www.tritonetech.com/php_uploads/porsche/webservice/push_notification.php?task=
$uid=$_SESSION['UID'];
//var_dump($uid);
$task=$_REQUEST['task'];
$student_id=$_SESSION['student_id'];
$group_content_id=$_SESSION['group_content_id'];
//$group_content_id='6';
if($group_content_id=='')
{
$dataDB['Result']['Data'][0]['Status']="user not in group activity";
echo FastJSON::encode($dataDB);
exit;

}
/*SELECT b.group_read,b.read_start,b.read_duration,b.group_write,b.write_start,b.write_duration
FROM jos_porsche_class_module AS a
LEFT JOIN jos_porsche_student AS b ON a.class_id = b.class_id
WHERE b.user_id = '$uid'*/
//var_dump($group_content_id);

switch($task){
case'group_read':
//$query=$_DB->Query("SELECT time FROM jos_porsche_group_reading_task where q_s_id='$student_id' AND group_content_id='$group_content_id' ORDER BY time DESC LIMIT 1");
$query1=$_DB->Query("SELECT MAX( posted_time ) AS posted_time
FROM jos_porsche_group_reading_task
WHERE q_s_id = '$student_id'
AND group_content_id = '$group_content_id' AND filename=''");

//assigned score to me
$query2=$_DB->Query("SELECT MAX( eval_time ) AS score_assigned
FROM jos_porsche_group_reading_task
WHERE q_s_id = '$student_id'
AND group_content_id = '$group_content_id'
AND score <>0 ");

/*$query3=$_DB->Query("SELECT MAX( ans_time ) AS answered
FROM jos_porsche_group_reading_task
WHERE student_id = '$student_id'
AND group_content_id = '$group_content_id'
AND filename=''");*/
$query3=$_DB->Query("SELECT ans_time FROM jos_porsche_group_reading_task where student_id='$student_id' AND group_content_id='$group_content_id' AND filename='' ORDER BY ans_time DESC LIMIT 1");
break;
case'group_write':
//$query=$_DB->Query("SELECT time FROM jos_porsche_group_writing_task where q_s_id='$student_id' AND group_content_id='$group_content_id' ORDER BY time DESC LIMIT 1");
$query1=$_DB->Query("SELECT MAX(posted_time) AS posted_time FROM jos_porsche_group_writing_task where q_s_id='$student_id' AND group_content_id='$group_content_id' AND image=''");
$query2=$_DB->Query("SELECT MAX(eval_time) AS score_assigned FROM jos_porsche_group_writing_task WHERE q_s_id='$student_id' AND group_content_id='$group_content_id' AND score IS NOT NULL");
$query3=$_DB->Query("SELECT MAX(ans_time) AS answered FROM jos_porsche_group_writing_task WHERE student_id='$student_id' AND group_content_id='$group_content_id' AND image=''");

break;
}

//posted question
/*SELECT MAX( time )
FROM jos_porsche_group_reading_task
WHERE q_s_id = '86'
AND group_content_id = '1'
*/
//assigned score to me
/*SELECT MAX( time )
FROM jos_porsche_group_reading_task
WHERE q_s_id = '86'
AND group_content_id = '1'
AND score <>0*/

/*SELECT MAX( time )
FROM jos_porsche_group_reading_task
WHERE student_id = '86'
AND group_content_id = '1'
AND filename<>''*/



//$count=$_DB->GetResultNumber($query);
//echo $count;

/*if($count==0)
{
$dataDB['Result']['Data'][0]['Status']="No posted questions";
}
else
{*/
	//for($i=0;$i<$count;$i++)
	//{
	//$count1=$_DB->GetResultNumber($query1);
	
	$posted_time=$_DB->GetResultValue($query1,0,'posted_time');
	//var_dump($query1);
	$dataDB['Result']['Data'][0]['posted_time']=(($posted_time==(''||'0'))?"no data":$posted_time);
	//$count2=$_DB->GetResultNumber($query2);
	//echo $count2;
	$score_assigned=$_DB->GetResultValue($query2,0,'score_assigned');
//var_dump($score_assigned);
	$dataDB['Result']['Data'][0]['score_assigned']=(($score_assigned==(''||'0'))?"no data":$score_assigned);
	//$count3=$_DB->GetResultNumber($query3);
	//echo $count3;
	$answered=$_DB->GetResultValue($query3,0,'answered');
//var_dump($answered);
	$dataDB['Result']['Data'][0]['answered']=(($answered==(''||'0'))?"no data":$answered);
	//}

//}
$group=$_DB->Query("SELECT a.$task,FROM_UNIXTIME(a.read_start,'%T') as read_time,a.read_duration as r_duration,FROM_UNIXTIME(a.write_start,'%T') as write_time,a.write_duration as w_duration
FROM jos_porsche_class_module AS a
LEFT JOIN jos_porsche_student AS b ON a.class_id = b.class_id
WHERE b.user_id = '$uid'");
$group_count=$_DB->GetResultNumber($group);
//echo $group_count;
if($group_count==1)
{
$group_status=$_DB->GetResultValue($group,0,$task);
//echo $group_status;
	if($group_status==1)
	{
	//$dataDB['Result']['Data'][0][$task]="assigned";
	switch($task){
	case'group_read':
	$dataDB['Result']['Data'][0]['start_time']=$_DB->GetResultValue($group,0,'read_time');
	$dataDB['Result']['Data'][0]['duration']=$_DB->GetResultValue($group,0,'r_duration');

	break;
	case'group_write':
	$dataDB['Result']['Data'][0]['start_time']=$_DB->GetResultValue($group,0,'write_time');
	$dataDB['Result']['Data'][0]['duration']=$_DB->GetResultValue($group,0,'w_duration');
	break;
	}
	}
	else
	{
	$dataDB['Result']['Data'][0]['Status']="not assigned";
	}
}
else
{
$dataDB['Result']['Data'][0]['Status']="No module";
}



echo FastJSON::encode($dataDB);



?>
