<?php
include'set_session.php';
include'setup.php';
require_once'FastJSON.class.php';
session_start();
//echo "<pre>";
//www.tritonetech.com/php_uploads/porsche/webservice/group_reading_data.php?student_id=&module_id=

$module_id=$_REQUEST['module_id'];
$student_id=$_REQUEST['student_id'];
//$a="SELECT * FROM jos_porsche_group where $student_id IN (student_id) and task='group_read' and module_id='$module_id'";
//echo $a;
$query=$_DB->Query("SELECT * FROM jos_porsche_group where $student_id IN (student_id) and task='group_read' and module_id='$module_id'");
$group_content_id=$_DB->GetResultValue($query,0,'id');
//$module_id=$_REQUEST['id'];
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$dataDB['Result']['Data'][]['Status']="user has not completed group reading";
	//$dataDB['Result']['Data'][]['student_id']";
	echo FastJSON::encode($dataDB);
	exit;
}
else
{
/*no. of questions posted by student*/
$query=$_DB->Query("SELECT COUNT(question) as count_of_questions from jos_porsche_group_reading_task where student_id='$student_id' and group_content_id='$group_content_id'");
$count=$_DB->GetResultNumber($query);
//$dataDB['Result']['Data'][]['count_of_questions']=$_DB->GetResultValue($query,0,'count_of_questions');

/*no. of answers answered by student*/
$query=$_DB->Query("SELECT filename,id,q_s_id from jos_porsche_group_reading_task where q_s_id='$student_id' and filename<>'' and group_content_id='$group_content_id'");
$count=$_DB->GetResultNumber($query);
//$dataDB['Result']['Data'][]['count_of_answers']=$_DB->GetResultNumber($query);

/*$query=$_DB->Query("SELECT count( student_id ) , student_id
FROM jos_porsche_group_reading_task
GROUP BY student_id");*/

/*no. of correct responses*/
$query=$_DB->Query("SELECT count(score) as  correct_response ,q_s_id from jos_porsche_group_reading_task where q_s_id='$student_id' and score>'0' and group_content_id='$group_content_id'");
$count=$_DB->GetResultNumber($query);
//$dataDB['Result']['Data'][]['correct_responses']=$_DB->GetResultValue($query,0,'correct_response');



/*-------------------------------------------------------------Remediation-------------------------------------------------------------------------*/
/*no. of wrong responses*/
$query=$_DB->Query("SELECT count(score) as  wrong_response ,q_s_id from jos_porsche_group_reading_task where q_s_id='$student_id' and score='0' and group_content_id='$group_content_id'");
$count=$_DB->GetResultNumber($query);
//$dataDB['Result']['Data'][]['wrong_responses']=$_DB->GetResultValue($query,0,'wrong_response');


/*no. of unanswered questions*/
$query=$_DB->Query("SELECT COUNT(filename) as unanswered_questions from jos_porsche_group_reading_task where q_s_id='$student_id' and filename='' and group_content_id='$group_content_id'");
$count=$_DB->GetResultNumber($query);
//$dataDB['Result']['Data'][]['unanswered_questions']=$_DB->GetResultValue($query,0,'unanswered_questions');




/*$query=$_DB->Query("SELECT filename,id,q_s_id from jos_porsche_group_reading_task where q_s_id='$student_id'");
$count=$_DB->GetResultNumber($query);
for($i=0;$i<$count;$i++)
	{
	$result=$_DB->GetResultValue($query,$i,'score');
	switch($result){
	case'NULL':
	$dataDB['Result']['Data'][$i]['Status']="user has not answered";
	break;
	case:'0':
	$dataDB['Result']['Data'][$i]['Status']="user has not answered correctly";
	break;
	case:'>0':
	$dataDB['Result']['Data'][$i]['count_of_answers']=$_DB->GetResultNumber($query);
	break;
	}

	}*/
echo FastJSON::encode($dataDB);
}
?>
