<?php
include'set_xml_session.php';
include'setup.php';

header("Content-Type: application/xml");
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
$output="<?xml version='1.0' encoding='utf-8'?><Result><Status>";
//www.tritonetech.com/php_uploads/porsche/webservice/meaning.php?word=&meaning=
$meaning=stripslashes($_REQUEST['meaning']);
//var_dump($meaning);
//foreach($meaning as $key=>$meaning)

//var_dump($meaning);

//var_dump($meaning);
//$meaning=stripslashes(str_split($_REQUEST['meaning']));
$word=stripslashes($_REQUEST['word']);
//$word1=json_decode($word);
//var_dump($word1);
//foreach($word as $key=>$word)
//var_dump($word);
//echo $word;
//echo $meaning;
//$word=mb_convert_encoding($word, 'UTF-8', 'HTML-ENTITIES');
//$meaning=mb_convert_encoding($meaning, 'UTF-8', 'HTML-ENTITIES');
if($word=='' || $meaning=='')
{
$output.="missing parameters";
echo $output."</Status></Result>";
exit;
}
//$a="update jos_porsche_words set meaning='$meaning' where words='$word'";
//echo $a;
//$_DB->Query("SET character_set_client=utf8");
//$_DB->Query("SET character_set_connection=utf8");
//$_DB->Query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$query=$_DB->Query("select * from jos_porsche_words where words='$word'");
$count=$_DB->GetResultNumber($query);
if($count==0)
{
//$a="insert into jos_porsche_words values('','$word','','$meaning',NOW())";
//echo $a;
$query=$_DB->Execute("insert into jos_porsche_words values('','$word','','$meaning','NOW()')");
	if($query==true)
	{
	$output.="success in inserting meaning";
	//echo json_encode($dataDB);
	
	}
	else
	{
	$output.="cannot execute";
	//echo json_encode($dataDB);
	
	}
}
else
{
$query=$_DB->Execute("update jos_porsche_words set meaning='$meaning',time=NOW() where words='$word'");
	if($query==true)
	{
	$output.="success in updating meaning";
	//echo json_encode($dataDB);
	
	}
	else
	{
	$output.="cannot execute";
	//echo json_encode($dataDB);
	
	}


}
echo $output."</Status></Result>";
?>
