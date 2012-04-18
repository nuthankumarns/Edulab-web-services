<?php
include'set_session.php';
include'setup.php';
require_once'FastJSON.class.php';
$uid=$_SESSION['UID'];
//http://www.tritonetech.com/php_uploads/porsche/webservice/self_reading_count_check.php?self_content_id=

$self_content_id=$_REQUEST['self_content_id'];
$query=$_DB->Query("SELECT a.id, b.id as qid, b.self_content_id, b.playback_count
FROM jos_porsche_self AS a
LEFT JOIN jos_porsche_self_reading_task AS b ON a.id = b.self_content_id
WHERE b.self_content_id = '$self_content_id' ORDER BY qid ASC");
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$dataDB['Result']['Data'][0]=array('Status'=>"not started");

}
else
{
$dataDB['Result']['Data'][0]['playback_count']=$_DB->GetResultValue($query,0,'playback_count');
$dataDB['Result']['Data'][0]['qid']=$_DB->GetResultValue($query,0,'qid');
}
echo FastJSON::encode($dataDB);

?>
