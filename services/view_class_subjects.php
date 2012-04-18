<?php
include'set_session.php';
include "setup.php";
require_once'FastJSON.class.php';
//www.tritonetech.com/php_uploads/porsche_webservice/view_class_subjects.php?class_id=
$class_id=$_REQUEST['class_id'];
$query=$_DB->Query("SELECT a . * , b . * , c . *
FROM jos_porsche_level AS a
LEFT JOIN jos_porsche_classes AS b ON ( a.id = b.level_id
AND a.school_id = b.school_id )
LEFT JOIN jos_porsche_programme AS c ON a.programme = c.id
WHERE b.id = '$class_id'");

$count = $_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][0]=array('Status'=>"No programme assigned");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0; $i < $count; $i++)
        {
		//$dataDB['Result']['Status'][$i]['programme_name']=$_DB->GetResultValue($query,$i,'name');
			$programme=explode(',',$_DB->GetResultValue($query,$i,'subjects'));
			for($j=0;$j<count($programme);$j++)
			{
				
            			$dataDB['Result']['Data'][$j]['subject_id']=$programme[$j];
				$query=$_DB->Query("select * from jos_porsche_subject where id='$programme[$j]'");
				$dataDB['Result']['Data'][$j]['subject_name']=$_DB->GetResultValue($query,0,'name');
			}
		//$dataDB['Result']['Status'][$i]['subject_id']=$_DB->GetResultValue($query,$i,'subjects');

        }
	echo FastJSON::encode($dataDB);
	}
?>
