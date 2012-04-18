<?php
include'set_xml_session.php';
include'setup.php';
$uid=$_SESSION["UID"];
header("content-type:application/xml");
//www.tritonetech.com/php_uploads/porsche/webservice/display_audio.php?word=
$output="<?xml version='1.0' encoding='utf-8'?><Result>";

$word=stripslashes($_REQUEST['word']);
/*if($word=='')
{
$output.="<Status>"."missing parameters"."</Status>";
echo $output."</Result>";
exit;
}*/

$query=$_DB->Query("select audio from jos_porsche_words where words='$word'");
$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$output.="<Status>"."no audio exist in the database"."</Status>";
	}
	else
	{
	$result=$_DB->GetResultValue($query,0,'audio');
	
		if($result=='1')
		{
		$file=$word.'.wav';
		$output.="<Data>".$file."</Data>";
		}
		else
		{
		$output.="<Status>"."please upload audio"."</Status>";
		}
		
	}


echo $output."</Result>";


?>
