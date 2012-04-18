<?php
require_once "session.class.php";
$oSession = new Session();
header("Content-Type: application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
include'setup.php';
//www.tritonetech.com/php_uploads/porsche/webservice/check_words.php?module_id=&sentence=&LA=&MA=&HA=&Ltime=&Mtime=&Htime=&task=
//task=group_read/group_write/self_read/self_write
$module_id=$_REQUEST['module_id'];
$word=stripslashes($_REQUEST['sentence']);
//foreach($word as $key=>$word)
$LA=$_REQUEST['LA'];
$MA=$_REQUEST['MA'];
$HA=$_REQUEST['HA'];
$Ltime=$_REQUEST['Ltime'];
$Mtime=$_REQUEST['Mtime'];
$Htime=$_REQUEST['Htime'];
$group_self=$_REQUEST['group_self'];
$task=$_REQUEST['task'];
//echo $word;
//charset=gb2312

//echo $word;
//echo $module_id;
//echo $word;
//echo $LA;
//echo $MA;
//echo $HA;
if($word=='' || $LA=='' || $MA=='' || $HA==''|| $module_id=='' || $Ltime=='' || $Mtime=='' || $Htime=='' || $task='')
{
$dataDB="missing parameters";
$output.="<Status>".$dataDB."</Status></Result>";
echo $output;
//echo json_encode($dataDB);
exit;
}

preg_match_all('/./u', $word, $wordss);

$words=array_pop($wordss);
	$query=$_DB->Query("select id from jos_porsche_module where id='$module_id'");
	$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$output.="<Status>"."No module!!! please contact administrator"."</Status></Result>";
	echo $output;
	exit;
	}
//print_r($words);
/*------------------start of code to check word/meaning/audio exists or not in the database------------*/
for($j=0;$j<count($words);$j++)
{
//$a="select * from jos_porsche_words where words='$words[$j]'";
	if($words[$j]==' ')
	{
	continue;
	}
	
 $query=$_DB->Query("select * from jos_porsche_words where words='$words[$j]'");
	$count = $_DB->GetResultNumber($query);
		if($count==1)
		{
		 $worddb = $_DB->GetResultValue($query, 0, "words");
		$audio= $_DB->GetResultValue($query, 0, "audio");
		$meaning=$_DB->GetResultValue($query, 0, "meaning");
			if($worddb=='')
			{
			$Data = $words[$j];
			$Status = ("word does not exist");
			break;
			}
			
			if($meaning=='')
			{
			$Data  = $words[$j];
			$Status = "meaning does not exist";
			break;
			}
			if($audio=='')
			{
			$Data = $words[$j];
			$Status= "audio does not exist";
			break;
			}
		}
		else
		{
		$Data  = $words[$j];
		$Status = "word or audio or meaning does not exist";
		break;
		}
	
}
/*------------------end of code to check word/meaning/audio exists or not in the database------------*/
if($Data!='' && $Status!='')
{

$output.="<Data>".$Data."</Data>"."<Status>".$Status."</Status>"."</Result>";
echo $output;
}
else
{
	/*$query=$_DB->Query("select id from jos_porsche_lesson where module_id='$module_id'");
	$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$output.="<Status>"."No content!!! please contact administrator"."</Status></Result>";
	echo $output;
	exit;
	}
	else
	{*/
		$query=$_DB->Execute("insert into jos_porsche_lesson values('','$module_id','$word','$LA','$Ltime','$MA','$Mtime','$HA','$Htime','$task')");
		//$a=mysql_insert_id();
			//$query=$_DB->Query("select content_id from jos_porsche_module where id='$module_id'");
			//$con=$_DB->GetResultValue($query,0,"content_id");
				//$query=$_DB->Execute("update jos_porsche_module set content_id='$con,$a', where id='$module_id'");
				if($query==true)
				{
				$output.="<Status>"."Content uploaded successfully"."</Status></Result>";
				echo $output;
				exit;
				}
	//}
		//$cont(',',$con);
//update tablename SET fieldname=TRIM(BOTH ',' from (select replace(CONCAT(',',fieldname,','), ',76,', ',67,'))) WHERE FIND_IN_SET('76',fieldname)>0

}

	//$query=$_DB->Execute("update jos_porsche_lesson set ('','$module_name','$school_id','$level_id','$subject_id','$a','$LA','$MA','$HA','$group','$self')");

	/*if($query==true)
		{
	$m=mysql_insert_id();
		$dataDB['Result']['Data'][]=array('Status'=>"All words exist in the database and content upload successfully",'content_id'=>"$a");
		echo json_encode($dataDB);
		exit;
		}	*/
//}

?>
