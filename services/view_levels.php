<?php
include'set_session.php';
/*session_start();
//http://www.tritonetech.com/php_uploads/porsche/webservice/view_levels.php?school_id=
include 'db.php';
//$id='$_SESSION['UID'];
if(isset($_SESSION['UID']))
{
$uid=$_SESSION['UID'];*/
include "setup.php";
$school_id=$_REQUEST['school_id'];
//if($school_id=='')
//{


//}
$query=$_DB->Query("SELECT * from jos_porsche_level WHERE school_id='$school_id' ORDER BY id ASC");
$count = $_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No levels");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0; $i < $count; $i++)
        {
            $dataDB['Result']['Data'][$i]['level_id'] = $_DB->GetResultValue($query, $i,"id");
		$dataDB['Result']['Data'][$i]['level_name'] = $_DB->GetResultValue($query, $i, "name");
			$dataDB['Result']['Data'][$i]['programme'] = $_DB->GetResultValue($query, $i, "programme");
				

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
