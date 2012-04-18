<?php
include'set_session.php';
include'setup.php';
$uid=$_SESSION['UID'];
//www.tritonetech.com/php_uploads/porsche/webservice/group_chat.php

$query=$_DB->Query("select school_id, class_id, ability from jos_porsche_student where user_id='$uid'");
	$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][0]=array('Status'=>"No data");
	echo json_encode($dataDB);
	exit;
	}
	$school_id=$_DB->GetResultValue($query,0,'school_id');
	$class_id=$_DB->GetResultValue($query,0,'class_id');
	$ability=$_DB->GetResultValue($query,0,'ability');
	
$query=$_DB->Query("select a.id,a.user_id,a.name,b.user_id as log from jos_porsche_student AS a left join jos_porsche_sessions  AS b ON a.user_id=b.user_id WHERE a.school_id='$school_id' and a.class_id='$class_id' and a.ability='$ability' and a.user_id<>'$uid' ORDER BY name ASC");

	$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][0]=array('Status'=>"No data");
	echo json_encode($dataDB);
	exit;
	}
	for($i=0;$i<$count;$i++)
	{
	$dataDB['Result']['Data'][$i]['id']=$_DB->GetResultValue($query,$i,'id');
		$dataDB['Result']['Data'][$i]['name']=$_DB->GetResultValue($query,$i,'name');
			$dataDB['Result']['Data'][$i]['login_status']=($_DB->GetResultValue($query,$i,'log'))!=('')?"logged in":"not logged in";
	}
echo json_encode($dataDB);

?>
