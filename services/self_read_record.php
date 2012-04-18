<?php
include'set_session.php';
include'setup.php';

require_once 'FastJSON.class.php';
$uid=$_SESSION['UID'];
$qid=$_REQUEST['qid'];
$time=$_REQUEST['time'];
//$count=$_REQUEST['count'];
//echo $task;
//echo $count;
//www.tritonetech.com/php_uploads/porsche/webservice/self_read_record.php?qid=&time=
if($qid=='' || $time=='')
{
$dataDB['Result']['Data'][0]['Status']="missing paramaters";
echo FastJSON::encode($dataDB);
//echo json_encode($dataDB);
exit;
}
//var_dump($task);

$qry=$_DB->Query("SELECT id,record_count  FROM jos_porsche_self_reading_task where id='$qid'");
$cnt=$_DB->GetResultNumber($qry);
;
	$query=$_DB->Execute("UPDATE jos_porsche_self_reading_task SET record_count=record_count+1, total_time=ADDTIME(total_time,'$time'),time_taken = CONCAT_WS( ' ', time_taken, '$time' ) where id='$qid'");

		if($query==true)
		{
		$qry=$_DB->Query("SELECT record_count FROM jos_porsche_self_reading_task where id='$qid'");
		$dataDB['Result']['Data'][0]['Status']="record tracking successful";
		$dataDB['Result']['Data'][0]['record_count']=$_DB->GetResultValue($qry,0,'record_count');
		}
		else
		{
		$dataDB['Result']['Data'][]['Status']="record tracking failed";
		}
	
/*UPDATE yourTable SET bankmoney = CASE WHEN bankmoney >= 50000 THEN bankmoney*.02 ELSE bankmoney+1000 END*/
/*UPDATE table SET field = CONCAT_WS(' ', field, 'value ') WHERE id = ID;*/

//UPDATE table SET field = CONCAT( field, ' this is appended' ) WHERE id = 7;

echo FastJSON::encode($dataDB);
?>
