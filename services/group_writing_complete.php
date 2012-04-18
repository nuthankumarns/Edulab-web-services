<?php
include'set_session.php';
include 'setup.php';
include 'FastJSON.class.php';
//www.tritonetech.com/php_uploads/porsche/webservice/group_writine_complete.php?qid=
$qid=$_REQUEST['qid'];
$query=$_DB->Execute("UPDATE jos_porsche_group_writing_task SET progress='1' WHERE id='$qid'");
$dataDB['Result']['Data'][0]['Status']=($query)?("task track success"):"task track failure";
echo FastJSON::encode($dataDB);


?>
