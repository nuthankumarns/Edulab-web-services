<?php
include'set_session.php';
include "setup.php";
/*session_start();
include 'db.php';
//http://www.tritonetech.com/php_uploads/porsche/webservice/view_classes.php?level_id=&school_id=
//$id='$_SESSION['UID'];
if(isset($_SESSION['UID']))
{
$uid=$_SESSION['UID'];*/
$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];

$query=$_DB->Query("Select * from jos_porsche_classes where level_id='$level_id' and school_id='$school_id'");
$count = $_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No classes");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0; $i < $count; $i++)
        {
            $dataDB['Result']['Data'][$i]['class_id'] = $_DB->GetResultValue($query, $i, "id");
		$dataDB['Result']['Data'][$i]['class_name'] = $_DB->GetResultValue($query, $i, "name");
			$dataDB['Result']['Data'][$i]['number_of_students'] = $_DB->GetResultValue($query, $i, "students");

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
