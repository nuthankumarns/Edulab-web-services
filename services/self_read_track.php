<?php
include'set_session.php';
include'setup.php';

require_once 'FastJSON.class.php';
$uid=$_SESSION['UID'];
$qid=$_REQUEST['qid'];

//www.tritonetech.com/php_uploads/porsche/webservice/self_read_track.php?qid=
if($qid=='')
{
$dataDB['Result']['Data'][0]['Status']="missing paramaters";
echo FastJSON::encode($dataDB);
exit;
}

$qry=$_DB->Query("SELECT id,playback_count FROM jos_porsche_self_reading_task where id='$qid'");
$cnt=$_DB->GetResultNumber($qry);

	switch($cnt)
	{
	case'0':
	$dataDB['Result']['Data'][0]['Status']="not started";
	echo FastJSON::encode($dataDB);
	exit;
	break;
	case'1':
	$query=$_DB->Execute("UPDATE jos_porsche_self_reading_task SET playback_count=playback_count+1 where id='$qid'");	
	//e$qid=$_DB->GetResultValue($qry,0,'id');
		if($query)
		{
		$dataDB['Result']['Data'][0]['Status']="playback tracking successful";
		$dataDB['Result']['Data'][0]['qid']=$qid;
		}
		else
		{
		$dataDB['Result']['Data'][]['Status']="playback tracking failed";
		}
	break;
	}
$query=$_DB->Query("SELECT playback_count FROM jos_porsche_self_reading_task where id='$qid'");
$dataDB['Result']['Data'][0]['playback_count']=$_DB->GetResultValue($query,0,'playback_count');
echo FastJSON::encode($dataDB);
?>
