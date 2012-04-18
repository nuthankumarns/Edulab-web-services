<?php
include'set_session.php';
include'setup.php';
//www.tritonetech.com/php_uploads/porsche/webservice/view_modules.php?school_id=&level_id=&subject_id=
$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];

$subject_id=$_REQUEST['subject_id'];

/*SELECT t1.id, t1.module_name, t2.id, t2.module_name
FROM jos_porsche_module AS t1
INNER JOIN jos_porsche_module AS t2 ON t2.module_order =6
WHERE t1.school_id = '1'
AND t1.level_id = '1'
AND t1.subject_id = '1'
*/
//$a="select id,module_name from jos_porsche_lesson where school_id='$school_id' and level_id='$level_id' and programme_id='$programme_id' and subject_id='$subject_id' and $task='1'";
//echo $a;
$query=$_DB->Query("SELECT t1.id, t1.module_name as name, t1.module_order, t2.id as remediation_module_id, t2.module_name
FROM jos_porsche_module AS t1
INNER JOIN jos_porsche_module AS t2 ON t2.module_order =6 
AND t2.school_id='$school_id' 
AND t2.level_id='$level_id' 
AND t2.subject_id='$subject_id'
WHERE t1.school_id = '$school_id'
AND t1.level_id = '$level_id'
AND t1.subject_id = '$subject_id'
AND t1.module_order <6
UNION
SELECT t1.id, t1.module_name, t1.module_order, t2.id, t2.module_name
FROM jos_porsche_module AS t1
INNER JOIN jos_porsche_module AS t2 ON t2.module_order =12
AND t2.school_id='$school_id' 
AND t2.level_id='$level_id' 
AND t2.subject_id='$subject_id'
WHERE t1.school_id = '$school_id'
AND t1.level_id = '$level_id'
AND t1.subject_id = '$subject_id'
AND t1.module_order
BETWEEN 7
AND 12
LIMIT 0 , 10");


/*SELECT t1.id, t1.module_name AS name, t1.module_order, t2.id AS remediation_module_id, t2.module_name
FROM jos_porsche_module AS t1
INNER JOIN jos_porsche_module AS t2 ON t2.module_order =6
AND t2.school_id = '8'
AND t2.level_id = '31'
AND t2.subject_id = '1'
WHERE t1.module_order <6
AND t1.school_id = '8'
AND t1.level_id = '31'
AND t1.subject_id = '1'*/

$count=$_DB->GetResultNumber($query);
$cnt=$_DB->GetResultNumber($qry);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No Modules");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
	for($i=0;$i<$count;$i++)
		{
		
			
		$dataDB['Result']['Data'][$i]['ordering']=$_DB->GetResultValue($query,$i,'module_order');
			$dataDB['Result']['Data'][$i]['module_id']=$_DB->GetResultValue($query,$i,'id');
				$dataDB['Result']['Data'][$i]['module_name']=$_DB->GetResultValue($query,$i,'name');
				$dataDB['Result']['Data'][$i]['remediation_module_id']=$_DB->GetResultValue($query,$i,'remediation_module_id');
				
					
					

		}
//print_r($format);
echo json_encode($dataDB);
//print_r($dataDB);
/*-----------------------------------------------------------view_content_id.php
							task=self_read
								task=self_write
									task=group_read
											task=group_write-----------------------------------------------------*/
	}
?>
