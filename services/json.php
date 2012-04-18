<?php // 4 or greater

// creates dedicated JSON output
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
include'setup.php';
$id=$_REQUEST['id'];
    // uses your favourite JSON parser
    require 'FastJSON.class.php';
 /* class MyClass {
 			//var	$param = "somevalue";
 			function MyClass($somevar) {
 				$this->somevar = $somevar;
 			}
			
 		}*/
//$instance = new MyClass("example");
//$dataDB['Result']['Data']=array("nuthan"=>$status);
$query=$_DB->Query("select * from jos_porsche_words limit 5");
$count=$_DB->GetResultNumber($query);
for($i=0;$i<$count;$i++)
{
$dataDB['Result']['Data'][$i]['id']=$_DB->GetResultValue($query,$i,'id');
$dataDB['Result']['Data'][$i]['words']=$_DB->GetResultValue($query,$i,'words');
$dataDB['Result']['Data'][$i]['meaning']=$_DB->GetResultValue($query,$i,'meaning');
}
 		echo FastJSON::encode($dataDB);

    // show JSON object
   // bytesonResponse(FastJSON::encode($result));

?> 