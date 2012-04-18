<?php
include'set_xml_session.php';
include "setup.php";
header("Content-Type: application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result><Status>";
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";



//www.tritonetech.com/php_uploads/porsche/webservice/stroke.php?array=&word=
$a=stripslashes($_REQUEST['array']);
/*$a='{"1":[{"starty":"268.121324","endy":"274.000000","endx":"66.000000","startx":"64.000000","delta":"0.000000"},
{"starty":"268.000000","endy":"274.000000","endx":"66.000000","startx":"64.000000","delta":"0.158114"}],
"2":[{"starty":"268.000000","endy":"274.000000","endx":"66.000000","startx":"64.000000","delta":"0.000000"},
{"starty":"268.000000","endy":"274.000000","endx":"66.000000","startx":"64.000000","delta":"0.316228"}]}';*/

//print_r($array);
$b=$_REQUEST['word'];
//$b='口';

if($a=='' || $b=='')
{
$output.="missing parameters"."</Status></Result>";
	echo $output;
	exit;
}
$query=$_DB->Query("select id from jos_porsche_words where words='$b'");
$count=$_DB->GetResultNumber($query);
	if($count!=0)
	{
	$word_id=$_DB->GetResultValue($query,0,'id');
	$query=$_DB->Execute("delete from jos_porsche_stroke where word_id='$word_id'");

	if($query==true)
	{
	$array=(json_decode($a,true));
//print_r($array);
//echo $array;
//$array=array_reverse($array);
//print_r($array);
		for($j=1;$j<=count($array);$j++)
			{
			for($i=0;$i<count($array[$j]);$i++)
				{
				$startx=$array[$j][$i][startx];
				$starty=$array[$j][$i][starty];
				$endx=$array[$j][$i][endx];
				$endy=$array[$j][$i][endy];
				$delta=$array[$j][$i][delta];
				$query=$_DB->Execute("insert into jos_porsche_stroke values('','$startx','$starty','$endx','$endy','$delta','$j','$word_id')");
				}
			}

	$output.="word updated successfully";
echo $output."</Status></Result>";
	}
}
else
{
/*new stroke insertion*/


//print_r($array);

	$query=$_DB->Execute("insert into jos_porsche_words values('','$b','','','NOW()')");
	$c=mysql_insert_id();
		if($query==true)
		{
//echo "<pre>";
		//$array=(json_decode($a,true));
$array=(json_decode($a,true));
		for($j=1;$j<=count($array);$j++)
			{
			for($i=0;$i<count($array[$j]);$i++)
				{
				$startx=$array[$j][$i][startx];
				$starty=$array[$j][$i][starty];
				$endx=$array[$j][$i][endx];
				$endy=$array[$j][$i][endy];
				$delta=$array[$j][$i][delta];
				$query=$_DB->Execute("insert into jos_porsche_stroke values('','$startx','$starty','$endx','$endy','$delta','$j','$c')");
				}
			}
		$output.="word inserted successfully";
		//echo json_encode($dataDB);
		}
		else
		{
		$output.="word and co-ordinates not inserted";
		//echo json_encode($dataDB);
		//exit;
		}
	echo $output."</Status></Result>";
}
/*$d='{"1":[{"starty":"268.121324","endy":"274.000000","endx":"66.000000","startx":"64.000000","delta":"0.000000"},
{"starty":"268.000000","endy":"274.000000","endx":"66.000000","startx":"64.000000","delta":"0.158114"}],
"2":[{"starty":"268.000000","endy":"274.000000","endx":"66.000000","startx":"64.000000","delta":"0.000000"},
{"starty":"268.000000","endy":"274.000000","endx":"66.000000","startx":"64.000000","delta":"0.316228"}]}';

*/




?>
