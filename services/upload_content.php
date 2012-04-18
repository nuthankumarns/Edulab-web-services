<?php
include'set_xml_session.php';
include 'setup.php';
$uid=$_REQUEST['UID'];


header("Content-Type: application/xml");
$output		="<?xml version='1.0' encoding='utf-8'?><Result>";
$module_id	=$_REQUEST['module_id'];
$word		=stripslashes($_REQUEST['sentence']);
$time		=$_REQUEST['time'];
$ability	=$_REQUEST['ability'];
$task		=$_REQUEST['task'];
$benchmark	=$_REQUEST['benchmark'];
$help_value	=$_REQUEST['help_value'];


/******************task=self_read***********self_write***********group_read**********group_write*******************/
if($module_id=='' || $word=='' || $time=='' || $ability=='' || $task=='' || $help_value=='')
{
$dataDB="missing parameters";
$output.="<Status>".$dataDB."</Status></Result>";
echo $output;
//echo json_encode($dataDB);
exit;
}
//LA MA HA
//www.tritonetech.com/php_uploads/porsche/webservice/upload_content.php?module_id=&sentence=&time=&ability=&benchmark=&help_value=&task=
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
	$query=$_DB->Execute("INSERT INTO jos_porsche_".$ability."_lesson values('','$module_id','$word','','$benchmark','$time','$help_value','$task')");
	if($query==true)
				{
				$content_id=mysql_insert_id();
				$output.="<Status>"."Content uploaded successfully"."</Status><content_id>".$content_id."</content_id></Result>";
				echo $output;
				}
				else
				{
				$output.="<Status>"."Content not uploaded successfully"."</Status></Result>";
				echo $output;
				}


}
?>
