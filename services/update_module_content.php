<?php
include'setup.php';
//require_once'set_xml_session.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$content_id=$_REQUEST['content_id'];
$content=$_REQUEST['content'];
$ability=$_REQUEST['ability'];
//www.edulabcloud.com/webservice/update_module_content.php?content_id=&content=&ability=
preg_match_all('/./u', $content, $wordss);

$words=array_pop($wordss);
	/*$query=$_DB->Query("select id from jos_porsche_module where id='$module_id'");
	$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$output.="<Status>"."No module!!! please contact administrator"."</Status></Result>";
	echo $output;
	exit;
	}*/
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
if($Data!='' && $Status!='')
{
$output.="<Data>".$Data."</Data>"."<Status>".$Status."</Status>";
}
else
{
$query=$_DB->Execute("UPDATE jos_porsche_".$ability."_lesson SET content='$content' WHERE id='$content_id'");
//var_dump($query);
	if($query>0)
	{
	$output.="<Status>update success</Status>";
	}
	else
	{
	
	$output.="<Status>update failure</Status>";
}
}

echo $output."</Result>";
?>
