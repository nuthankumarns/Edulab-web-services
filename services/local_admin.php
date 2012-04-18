<?php
include'set_session.php';
include'setup.php';
//www.tritonetech.com/php_uploads/porsche/webservice/local_admin.php?uid=&gid=
$suid=$_SESSION['UID'];

$gid=$_REQUEST['gid'];
$uid=$_REQUEST['uid'];
if($gid=='' || $uid=='')
{
$dataDB['Result']['Data'][]=array('Status'=>"Parameters missing");
echo json_encode($dataDB);
exit;
}
switch($gid){
case '25':
if($suid!=$uid)
{
$dataDB['Result']['Data'][]=array('Status'=>"Invalid admin id");
echo json_encode($dataDB);
exit;
}
$query=$_DB->Query("select * from jos_porsche_schools");
$count = $_DB->GetResultNumber($query);

	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No school exist");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0; $i < $count; $i++)
		{
		$dataDB['Result']['Data'][$i]['school_id'] = $_DB->GetResultValue($query, $i, "id");
		$dataDB['Result']['Data'][$i]['school_name'] = $_DB->GetResultValue($query, $i, "name");
		}
	echo json_encode($dataDB);
	exit;

	}
	break;

case '31':
$query=$_DB->Query("select admin_id,school_id from jos_porsche_schools_admin");
$count = $_DB->GetResultNumber($query);
if($count==0)
{
$dataDB['Result']['Data'][]=array('Status'=>"No school exist");
	echo json_encode($dataDB);
	exit;
}
else
{
$query=$_DB->Query("select a.*,b.id,b.name from jos_porsche_schools_admin as a left join jos_porsche_schools as b on a.school_id=b.id where admin_id='$uid'");
$count = $_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No school exist");
	//echo json_encode($dataDB);
	}
	else
	{
		$dataDB['Result']['Data'][0]['school_id'] = $_DB->GetResultValue($query, 0, "id");
		$dataDB['Result']['Data'][0]['school_name'] = $_DB->GetResultValue($query, 0, "name");
	//echo json_encode($dataDB);
	}
/*	for($i=0;$i<$count;$i++)
	{
	$admin[$i] = $_DB->GetResultValue($query, $i, "admin_id");
		$schoolid[$i] = $_DB->GetResultValue($query, $i, "school_id");
	$admini=implode(',',$admin);

	$adminis=explode(',',$admini);

		foreach($adminis as $key=>$b)
		{
			if($b==$uid)
			{
			$query=$_DB->Query("select name,id from jos_porsche_schools where id='$schoolid[$i]'");
				$count = $_DB->GetResultNumber($query);
					if($count==0)
					{
					$dataDB['Result']['Data'][]=array('Status'=>"No school exist");
					echo json_encode($dataDB);
					exit;
					}
					else
					{
					$dataDB['Result']['Data']['school_id'] = $_DB->GetResultValue($query, 0, "id");
						$dataDB['Result']['Data']['school_name'] = $_DB->GetResultValue($query, 0, "name");
					}
			}
		}//break;
	/*
		$query=$_DB->Query("select a.name,a.id,b.school_id from jos_porsche_schools as a left join jos_porsche_schools_admin as b on a.id=b.school_id where school_id='$Schoolid[$i]'");
		$dataDB['Result']['Data']['school_id'] = $_DB->GetResultValue($query, 0, "school_id");
			$dataDB['Result']['Data']['school_name'] = $_DB->GetResultValue($query, 0, "name");*/
	
	//}
	echo json_encode($dataDB);
}
//print_r($admini);
}

/*$query=$_DB->Query("select a.*,b.id,b.name from jos_porsche_schools_admin as a left join jos_porsche_schools as b on a.school_id=b.id where admin_id='$uid'");
$count = $_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No school exist");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		$dataDB['Result']['Data']['school_id'] = $_DB->GetResultValue($query, 0, "id");
		$dataDB['Result']['Data']['school_name'] = $_DB->GetResultValue($query, 0, "name");
	echo json_encode($dataDB);
	exit;

	}
	break;
}*/
//ALTER TABLE `jos_porsche_stroke` DEFAULT CHARACTER SET big5 COLLATE big5_chinese_ci 
?>
