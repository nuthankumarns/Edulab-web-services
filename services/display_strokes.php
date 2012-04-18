<?php
include'set_session.php';
$uid=$_SESSION["UID"];
//echo $uid;
include'setup.php';
$word=$_REQUEST['word'];
//echo $word;
//echo"<pre>";
//www.tritonetech.com/php_uploads/porsche/webservice/display_strokes.php?word=

//var_dump($query);
	
//var_dump($id);
//echo $id;


$query=$_DB->Query("select a.word_id,a.stroke_num,b.id,b.words from jos_porsche_stroke as a left join jos_porsche_words as b on b.id=a.word_id where words='$word'");

	$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No strokes");
	echo json_encode($dataDB);
	exit;
	}
	else
	{

	$word_id=$_DB->GetResultValue($query,0,'id');

//echo $count;
for($i=0;$i<$count;$i++)
		{
	$word_id=$_DB->GetResultValue($query,$i,'id');
	$stroke[]=$_DB->GetResultValue($query,$i,'stroke_num');}
	$b=array_unique($stroke);
sort($b);
//$b=array_reverse($b);
	//print_r($b);
	$c=implode(' ',$b);
	//print_r($c);
	$d=explode(' ',$c);//echo count($d);
	//print_r($d);echo"<br/>";
$m = 1;
$n = 0;

// loop
for ($m, $n; $n < count($d); $m++, $n++)
{
$e[$m] = $d[$n];
}
//print_r($e);

		for($j=1;$j<=count($e);$j++)
		{//echo $j;
		$query=$_DB->Query("select * from jos_porsche_stroke where stroke_num='$e[$j]' and word_id='$word_id' order by id asc");
		//$a="select * from jos_porsche_stroke where stroke_num='$d[$j]' and word_id='$word_id'";
		//echo $a;

		//$dataDB['Result']['Data'][$j][][]=$e[$j];
			$count=$_DB->GetResultNumber($query);
			for($k=0;$k<$count;$k++)
			{
			$dataDB['Result']['Data'][$j][$k]['startx']=$_DB->GetResultValue($query,$k,'startx');
				$dataDB['Result']['Data'][$j][$k]['starty']=$_DB->GetResultValue($query,$k,'starty');
					$dataDB['Result']['Data'][$j][$k]['endx']=$_DB->GetResultValue($query,$k,'endx');
						$dataDB['Result']['Data'][$j][$k]['endy']=$_DB->GetResultValue($query,$k,'endy');
							$dataDB['Result']['Data'][$j][$k]['delta']=$_DB->GetResultValue($query,$k,'delta');
			}
		}
/*	for($i=0;$i<$count;$i++)
		{
	$word_id=$_DB->GetResultValue($query,$i,'id');
	$stroke[]=$_DB->GetResultValue($query,$i,'stroke_num');}
	$b=array_unique($stroke);
	//print_r($b);
	$c=implode(' ',$b);
	//print_r($c);
	$d=explode(' ',$c);
	//print_r($d);
		for($j=0;$j<count($d);$j++)
		{
		$query=$_DB->Query("select * from jos_porsche_stroke where stroke_num='$d[$j]' and word_id='$word_id'");
		//$a="select * from jos_porsche_stroke where stroke_num='$d[$j]'";
		//echo $a;
			$count=$_DB->GetResultNumber($query);
			for($k=0;$k<$count;$k++)
			{
			$dataDB['Result']['Data'][$j][$k]['startx']=$_DB->GetResultValue($query,$k,'startx');
				$dataDB['Result']['Data'][$j][$k]['starty']=$_DB->GetResultValue($query,$k,'starty');
					$dataDB['Result']['Data'][$j][$k]['endx']=$_DB->GetResultValue($query,$k,'endx');
						$dataDB['Result']['Data'][$j][$k]['endy']=$_DB->GetResultValue($query,$k,'endy');
							$dataDB['Result']['Data'][$j][$k]['delta']=$_DB->GetResultValue($query,$k,'delta');
			}
		}
//$ad=array_push($ad,1);*/
echo json_encode($dataDB);
}
//print_r($da);
/*
		for($j=1;$j<=count($d);$j++)
		{//startx 	starty 	endx 	endy 	delta 	stroke_num 	word_id
		//$dataDB['Result']['Data'][$i]['id']=$_DB->GetResultValue($query,$i,'c');
			//for($k=0;$k<$count;$i++)
		//{
		$dataDB['Result']['Data'][$j]['startx']=$_DB->GetResultValue($query,$j,'startx');
			$dataDB['Result']['Data'][$j]['starty']=$_DB->GetResultValue($query,$j,'starty');
				$dataDB['Result']['Data'][$j]['endx']=$_DB->GetResultValue($query,$j,'endx');
					$dataDB['Result']['Data'][$j]['endy']=$_DB->GetResultValue($query,$j,'endy');
						$dataDB['Result']['Data'][$j]['delta']=$_DB->GetResultValue($query,$j,'delta');
					//$dataDB['Result']['Data'][$j][$i]['stroke_num']=$_DB->GetResultValue($query,$i,'stroke_num');
			//echo $str;
				//if($str!=$d){break;}
		//}
			
		}
	
print_r($dataDB);
//echo json_encode($dataDB);
	//}
//echo count($stroke);
//var_dump($stroke);*/
/*for($k=0;$k<$count;$k++)
{$array[]=$_DB->GetResultValue($query,$k,'stroke_num');

//$b=array_unique($array);
//var_dump($b);
	/*	for($j=1;$j<=count($array);$j++)
			{
			for($i=0;$i<count($array[$j]);$i++)
				{
				$array[$j][$i][startx]=$_DB->GetResultValue($query,$j,'startx');
				$array[$j][$i][starty]=$_DB->GetResultValue($query,$j,'starty');
				$array[$j][$i][endx]=$_DB->GetResultValue($query,$j,'endx');
				$array[$j][$i][endy]=$_DB->GetResultValue($query,$j,'endy');
				$array[$j][$i][delta]=$_DB->GetResultValue($query,$j,'delta');
				}
			}*/
	//}
/*print_r($array);
$b=array_unique($array);
print_r($b);
$c=implode(' ',$b);
print_r($c);
$d=explode(' ',$c);
print_r($d);
for($j=0;$j<=$count;$j++)
		{//startx 	starty 	endx 	endy 	delta 	stroke_num 	word_id
		//$dataDB['Result']['Data'][$i]['id']=$_DB->GetResultValue($query,$i,'c');
			for($i=1;$i<count($d);$i++)
			{
			$dataDB['Result']['Data'][$j][$i]['startx']=$_DB->GetResultValue($query,$i,'startx');
				$dataDB['Result']['Data'][$j][$i]['starty']=$_DB->GetResultValue($query,$i,'starty');
					$dataDB['Result']['Data'][$j][$i]['endx']=$_DB->GetResultValue($query,$i,'endx');
						$dataDB['Result']['Data'][$j][$i]['endy']=$_DB->GetResultValue($query,$i,'endy');
						//$dataDB['Result']['Data'][$i][$j]['stroke_num']=$_DB->GetResultValue($query,$j,'stroke_num');
							$dataDB['Result']['Data'][$i][$j]['delta']=GetResultValue($query,$i,'delta');
							$stroke=$_DB->GetResultValue($query,$i,'stroke_num');
				if($stroke!=$i)
				{break;}
			}
		}
print_r($dataDB);*/

?>
