<?php
include'set_session.php';
/*session_start();
include 'db.php';
//http://www.tritonetech.com/php_uploads/porsche/webservice/view_subjects.php?subject_id=
//$id='$_SESSION['UID'];
if(isset($_SESSION['UID']))
{
$uid=$_SESSION['UID'];*/
include "setup.php";
$subject_id=$_REQUEST['subject_id'];
$query=$_DB->Query("select * from jos_porsche_subject where id='$subject_id'");
$count=$_DB->GetResultNumber($query);
if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No subjects");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
	for($i=0; $i < $count; $i++)
        {
            $dataDB['Result']['Data'][$i]['subject_id'] = $_DB->GetResultValue($query, $i,"id");
		$dataDB['Result']['Data'][$i]['name'] = $_DB->GetResultValue($query, $i,"name");
			$dataDB['Result']['Data'][$i]['description'] = $_DB->GetResultValue($query, $i,"description");
	}
	echo json_encode($dataDB);
	}
/*}
else
{
$dataDB['Result']['Data'][]=array('Status'=>"Session Expired-Please login again");
echo json_encode($dataDB);
}*/
?>

