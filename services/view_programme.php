<?php
include'set_session.php';
//www.tritonetech.com/php_uploads/porsche/webservice/view_programme.php?school_id=&level_id=
include "setup.php";
$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
//echo $school_id;
//echo $level_id;
$query=$_DB->Query("select * from jos_porsche_level where school_id='$school_id' and id='$level_id'");
//$a="select * from jos_porsche_level where school_id='$school_id' and id='$level_id'";
//echo $a;
$count = $_DB->GetResultNumber($query);
//echo $count;
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No programme");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0;$i<$count;$i++)
		{
			$programme[$i]=$_DB->GetResultValue($query,$i,"programme");
			}
	}

//print_r($programme);
		for($j=0;$j<count($programme);$j++)
		{
$query=$_DB->Query("select * from jos_porsche_programme where id='$programme[$j]'");
	$count=$_DB->GetResultNumber($query);
			if($count==0)
			{
			$dataDB['Result']['Data'][]=array('Status'=>"No programme");
			echo json_encode($dataDB);
			exit;
			}
			else
			{
			$dataDB['Result']['Data'][$j]['programme_id']=$_DB->GetResultValue($query,$j,"id");
				$dataDB['Result']['Data'][$j]['programme_name']=$_DB->GetResultValue($query,$j,"name");
			
			}
		}
echo json_encode($dataDB);
	

?>
