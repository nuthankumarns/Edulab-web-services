<?php
include'set_session.php';
include'setup.php';
//www.tritonetech.com/php_uploads/porsche/webservice/view_school_subjects.php?school_id=
$school_id=$_REQUEST['school_id'];
if($school_id=='')
{
$dataDB['Result']['Data'][]=array('Status'=>"Parameters missing");
echo json_encode($dataDB);
exit;
}
$query=$_DB->Query("select * from jos_porsche_subject where school_id='$school_id'");
$count = $_DB->GetResultNumber($query);
//echo $count;

	if($count==0)
	{
	$dataDB['Result']['Data'][]['Status']=("No subjects for this school");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
	for($i=0;$i<$count;$i++)
		{
		$dataDB['Result']['Data'][$i]['subject_id']=$_DB->GetResultValue($query,$i,"id");
		$dataDB['Result']['Data'][$i]['subject_name']=$_DB->GetResultValue($query,$i,"name");
		}
	echo json_encode($dataDB);
	}
?>
