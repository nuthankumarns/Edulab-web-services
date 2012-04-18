<?php
include'set_session.php';
/* track audio play count self reading*/
include 'setup.php';

//www.tritonetech.com/php_uploads/webservice/audio_play_count.php
$uid=$_SESSION['UID'];
//$uid='104';
$query=$_DB->Query("select a.user_id,a.id,b.student_id,c.id as cid,c.student_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id left join jos_porsche_self as c on b.student_id=c.student_id where user_id='$uid'");
		$id=$_DB->GetResultValue($query,0,'cid');
//$query=$_DB->Execute("update jos_porsche_student_module SET audio_req=audio_req+1 where student_id='$id'");
if($query)
{
$query=$_DB->Query("select * from jos_porsche_self_reading_task where self_content_id='$id'");
$count=$_DB->GetResultValue($query,0,'playback_count');
$dataDB['Result']['Data'][]=array('audio play count'=>"$count");
echo json_encode($dataDB);
exit;
}
else
{
$dataDB['Result']['Data'][]=array("Status"=>'track failed');
echo json_encode($dataDB);
exit;
}
?>
