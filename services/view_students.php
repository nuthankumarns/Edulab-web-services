<?php
include'set_session.php';
/*session_start();
include 'db.php';
//http://www.tritonetech.com/php_uploads/porsche/webservice/view_students.php?class_id=
//$id='$_SESSION['UID'];
if(isset($_SESSION['UID']))
{
$uid=$_SESSION['UID'];*/
$class_id=$_REQUEST['class_id'];
include "setup.php";
$query=$_DB->Query("select id,school_id,name from jos_porsche_student where class_id='$class_id' ORDER BY name ASC");
$count = $_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No students");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0; $i < $count; $i++)
        {
            $dataDB['Result']['Data'][$i]['student_id'] = $_DB->GetResultValue($query, $i,"id");
		$dataDB['Result']['Data'][$i]['school_id'] = $_DB->GetResultValue($query, $i,"school_id");
			$dataDB['Result']['Data'][$i]['name'] = $_DB->GetResultValue($query, $i,"name");
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
