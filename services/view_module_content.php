<?php
include'setup.php';
require_once'set_xml_session.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$module_id=$_REQUEST['module_id'];
$ability=$_REQUEST['ability'];
$task=$_REQUEST['task'];
//www.edulabcloud.com/webservice/view_module_content.php?module_id=&ability=&task=
$query=$_DB->Query("SELECT id,module_id,content FROM jos_porsche_".$ability."_lesson where module_id='$module_id' and task='$task'");
$count=$_DB->GetResultNumber($query);
if($count==0)
{
$output.="<Status>No content</Status>";
}
else
{




	for($i=0;$i<$count;$i++)
	{
	$output.="<Data><content>";

	$line[$i]=$_DB->GetResultValue($query,$i,"content");
//echo $i;
//print_r($line[$i]);echo"<br/>";
	preg_match_all('/./u', $line[$i], $array);
	$array=array_pop($array);
	//print_r($array);
		$k=0;
		while($k<count($array)){
		$b[]=array_slice($array,$k,100);
		$k+=100;
		}

				
			for($j=0;$j<count($b);$j++)
			{
			$con[$j]=implode('',$b[$j]);
			//echo $con[$i];
			$output.="<para>".$con[$j]."</para>";
			}
		$output.="</content><content_id>".$_DB->GetResultValue($query,$i,'id')."</content_id></Data>";
		unset($b);
	}
}

echo $output."</Result>";

?>
