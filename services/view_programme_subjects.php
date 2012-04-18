<?php
include'set_session.php';
include'setup.php';
//www.tritonetech.com/php_uploads/porsche/webservice/view_programme_subjects.php?programme_id=
$programme_id=$_REQUEST['programme_id'];
$query=$_DB->Query("select * from jos_porsche_programme where id='$programme_id'");
$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No subjects");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0;$i<$count;$i++)
		{	
		$subjects=$_DB->GetResultValue($query,$i,'subjects');
		}
		$result=explode(',',$subjects);
			for($j=0;$j<count($result);$j++)
			{
			$query=$_DB->Query("select * from jos_porsche_subject where id='$result[$j]'");
			$count=$_DB->GetResultNumber($query);

				if($count==0)
				{
				$dataDB['Result']['Data'][]=array('Status'=>"No subjects");
				echo json_encode($dataDB);
				exit;
				}
				else
				{
					//for($k=0;$k<=$count;$k++)
					//{
				$dataDB['Result']['Data'][$j]['subject_id']=$_DB->GetResultValue($query,0,"id");
					$dataDB['Result']['Data'][$j]['subject_name']=$_DB->GetResultValue($query,0,"name");
						//$dataDB['Result']['Data'][$j]['description']=$_DB->GetResultValue($query,0,"description");
					//}
				}
			}

echo json_encode($dataDB);
}

		
?>
