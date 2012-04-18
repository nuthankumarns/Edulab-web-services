<?php
include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result><Data>";
$student_id=$_REQUEST['student_id'];
$module_id=$_REQUEST['module_id'];
//$query=$_DB->Query("SELECT ability from jos_porsche_student where student_id='$student_id'");
//$ability=$_DB->GetResultValue($query,0,'ability');
//www.tritonetech.com/php_uploads/porsche/webservice/self_writing_data.php?student_id=&module_id=
//$query=$_DB->Query("SELECT a.id,b.module_id= from jos_porsche_self on where student_id='$student_id' and task='self_write'");
$query=$_DB->Query("SELECT a.id, a.student_id, a.module_id, a.content_id,b.id as word_id,b.self_content_id,b.word, b.ordering, b.guided_count, b.self_count, b.self_time, b.self_error, b.unaided_count, b.unaided_time
FROM jos_porsche_self AS a
JOIN jos_porsche_self_writing_task AS b ON a.id = b.self_content_id
WHERE a.student_id = '$student_id'
AND module_id = '$module_id'
AND task = 'self_write'
ORDER BY self_content_id");

//var_dump($query);
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$output.="<Status>"."user has not answered"."</Status>";
}
else
{
/*for($j=0;$i<$count;$j++)
		{
		$format[$j]=$_DB->GetResultValue($query,$j,'self_content_id');
		}
var_dump($format);
echo "<pre>";
print_r($format);
$key=array_count_values($format);
print_r($key);
$key_values=array_values($key);
print_r($key_values);
$uniq=array_unique($format);
print_r($uniq);
$uni=array_values(array_unique($format));
print_r($uni);*/


	//echo "<pre>";

			//for($j=0;$j<count($uniq);$j++)
			//{
			//echo "i:".$i;
			//echo "j:".$j;
			
			/*for($j=0;$j<$count;$j++)
			{$format[$j]=$_DB->GetResultValue($query,$j,'self_content_id');
			$output.="<Data$format[$j]>";
			}*/
				//$output.="<Data>";
				for($i=0;$i<$count;$i++)
				{
				$format[$i]=$_DB->GetResultValue($query,$i,'self_content_id');
					if($format[$i-1]!='' && $format[$i-1]!=$format[$i])
					{
					$output.="</Data><Data>";
					}
				
				//$output.="<Data$format[$j]>";
				
				$output.="<Statistics><id>".$_DB->GetResultValue($query,$i,'word_id')."</id>
				<word>".$_DB->GetResultValue($query,$i,'word')."</word>
				<guided_count>".$_DB->GetResultValue($query,$i,'guided_count')."</guided_count>
				<self_count>".$_DB->GetResultValue($query,$i,'self_count')."</self_count>
				<self_time>".$_DB->GetResultValue($query,$i,'self_time')."</self_time>
				<self_error>".$_DB->GetResultValue($query,$i,'self_error')."</self_error>
				<unaided_count>".$_DB->GetResultValue($query,$i,'unaided_count')."</unaided_count>
				<unaided_time>".$_DB->GetResultValue($query,$i,'unaided_time')."</unaided_time></Statistics>";
				
				//$output.="</Data$format[$j]>";
				}
			///$output.="</Data>";
			//$output.="</Data$format[$j]>";
			
			/*for($j=0;$j<$count;$j++)
			{$format[$j]=$_DB->GetResultValue($query,$j,'self_content_id');
			$output.="</Data$format[$j]>";
			}*/
	

//print_r($data);

}
echo $output."</Data></Result>";

?>
