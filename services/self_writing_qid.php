<?php
include'set_session.php';
include'setup.php';

require_once'FastJSON.class.php';
//www.tritonetech.com/php_uploads/porsche/webservice/self_writing_qid.php?self_content_id=&ordering=
$ordering=$_REQUEST['ordering'];
$self_content_id=$_REQUEST['self_content_id'];
if($ordering=='' || $self_content_id=='')
{
$dataDB['Result']['Data'][0]['Status']="parameters missing";
echo FastJSON::encode($dataDB);
exit;
}
$query=$_DB->Query("SELECT id,guided_count,self_count,unaided_count from jos_porsche_self_writing_task where self_content_id='$self_content_id' and ordering='$ordering'");
if($query)
{
$dataDB['Result']['Data'][0]['qid']=$_DB->GetResultValue($query,0,'id');
$dataDB['Result']['Data'][0]['guided_count']=$_DB->GetResultValue($query,0,'guided_count');
$dataDB['Result']['Data'][0]['self_count']=$_DB->GetResultValue($query,0,'self_count');
$dataDB['Result']['Data'][0]['unaided_count']=$_DB->GetResultValue($query,0,'unaided_count');
}
else
{
$dataDB['Result']['Data'][0]['Status']="qid does not exist";

}
echo FastJSON::encode($dataDB);


?>
