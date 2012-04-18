<?php
include'setup.php';
//www.tritonetech.com/php_uploads/porsche/webservice/view_chinese_text.php
//$query=$_DB->("SET NAMES 'utf8'");
//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
//$str = “&#20320;&#22909;”;
//$str="我";
//$str='nuthan';
/*function unichr($str) {
    return mb_convert_encoding('&#' . intval($str) . ';', 'UTF-8', 'HTML-ENTITIES');
}*/
//echo ord($str);
//echo htmlentities($str);
//$anyUTF8string="&#20320;";
//$anyUTF8string="nuthan";
/*function utf8tohtml($utf8, $encodeTags) {
    $result = '';
    for ($i = 0; $i < strlen($utf8); $i++) {
        $char = $utf8[$i];
        $ascii = ord($char);
        if ($ascii < 128) {
            // one-byte character
            $result .= ($encodeTags) ? htmlentities($char) : $char;
        } else if ($ascii < 192) {
            // non-utf8 character or not a start byte
        } else if ($ascii < 224) {
            // two-byte character
            $result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
            $i++;
        } else if ($ascii < 240) {
            // three-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $unicode = (15 & $ascii) * 4096 +
                       (63 & $ascii1) * 64 +
                       (63 & $ascii2);
            $result .= "&#$unicode;";
            $i += 2;
        } else if ($ascii < 248) {
            // four-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $ascii3 = ord($utf8[$i+3]);
            $unicode = (15 & $ascii) * 262144 +
                       (63 & $ascii1) * 4096 +
                       (63 & $ascii2) * 64 +
                       (63 & $ascii3);
            $result .= "&#$unicode;";
            $i += 3;
        }
    }
    return $result;
}
*/
//echo utf8tohtml($anyUTF8string, TRUE);
/*
function UTF_to_Unicode($input, $array=False) {

 $bit1  = pow(64, 0);
 $bit2  = pow(64, 1);
 $bit3  = pow(64, 2);
 $bit4  = pow(64, 3);
 $bit5  = pow(64, 4);
 $bit6  = pow(64, 5);
 
 $value = '';
 $val   = array();
 
 for($i=0; $i< strlen( $input ); $i++){
 
     $ints = ord ( $input[$i] );
    
     $z     = ord ( $input[$i] );
     $y     = ord ( $input[$i+1] ) - 128;
     $x     = ord ( $input[$i+2] ) - 128;
     $w     = ord ( $input[$i+3] ) - 128;
     $v     = ord ( $input[$i+4] ) - 128;
     $u     = ord ( $input[$i+5] ) - 128;

     if( $ints >= 0 && $ints <= 127 ){
        // 1 bit
        $value .= '&#'.($z * $bit1).';';
        $val[]  = $value;
     }
     if( $ints >= 192 && $ints <= 223 ){
        // 2 bit
        $value .= '&#'.(($z-192) * $bit2 + $y * $bit1).';';
        $val[]  = $value;
     }   
     if( $ints >= 224 && $ints <= 239 ){
        // 3 bit
        $value .= '&#'.(($z-224) * $bit3 + $y * $bit2 + $x * $bit1).';';
        $val[]  = $value;
     }    
     if( $ints >= 240 && $ints <= 247 ){
        // 4 bit
        $value .= '&#'.(($z-240) * $bit4 + $y * $bit3 +
$x * $bit2 + $w * $bit1).';';
        $val[]  = $value;       
     }    
     if( $ints >= 248 && $ints <= 251 ){
        // 5 bit
        $value .= '&#'.(($z-248) * $bit5 + $y * $bit4
+ $x * $bit3 + $w * $bit2 + $v * $bit1).';';
        $val[]  = $value;  
     }
     if( $ints == 252 && $ints == 253 ){
        // 6 bit
        $value .= '&#'.(($z-252) * $bit6 + $y * $bit5
+ $x * $bit4 + $w * $bit3 + $v * $bit2 + $u * $bit1).';';
        $val[]  = $value;
     }
     if( $ints == 254 || $ints == 255 ){
       echo 'Wrong Result!<br>';
     }
    
 }
 
 if( $array === False ){
    return $unicode = $value;
 }
 if($array === True ){
     $val     = str_replace('&#', '', $value);
     $val     = explode(';', $val);
     $len = count($val);
     unset($val[$len-1]);
    
     return $unicode = $val;
 }
 
}

 
function Unicode_to_UTF( $input, $array=TRUE){

     $utf = '';
    if(!is_array($input)){
       $input     = str_replace('&#', '', $input);
       $input     = explode(';', $input);
       $len = count($input);
       unset($input[$len-1]);
    }
    for($i=0; $i < count($input); $i++){
   
    if ( $input[$i] <128 ){
       $byte1 = $input[$i];
       $utf  .= chr($byte1);
    }
    if ( $input[$i] >=128 && $input[$i] <=2047 ){
   
       $byte1 = 192 + (int)($input[$i] / 64);
       $byte2 = 128 + ($input[$i] % 64);
       $utf  .= chr($byte1).chr($byte2);
    }
    if ( $input[$i] >=2048 && $input[$i] <=65535){
   
       $byte1 = 224 + (int)($input[$i] / 4096);
       $byte2 = 128 + ((int)($input[$i] / 64) % 64);
       $byte3 = 128 + ($input[$i] % 64);
      
       $utf  .= chr($byte1).chr($byte2).chr($byte3);
    }
    if ( $input[$i] >=65536 && $input[$i] <=2097151){
   
       $byte1 = 240 + (int)($input[$i] / 262144);
       $byte2 = 128 + ((int)($input[$i] / 4096) % 64);
       $byte3 = 128 + ((int)($input[$i] / 64) % 64);
       $byte4 = 128 + ($input[$i] % 64);
       $utf  .= chr($byte1).chr($byte2).chr($byte3).
chr($byte4);
    }
    if ( $input[$i] >=2097152 && $input[$i] <=67108863){
   
       $byte1 = 248 + (int)($input[$i] / 16777216);
       $byte2 = 128 + ((int)($input[$i] / 262144) % 64);
       $byte3 = 128 + ((int)($input[$i] / 4096) % 64);
       $byte4 = 128 + ((int)($input[$i] / 64) % 64);
       $byte5 = 128 + ($input[$i] % 64);
       $utf  .= chr($byte1).chr($byte2).chr($byte3).
chr($byte4).chr($byte5);
    }
    if ( $input[$i] >=67108864 && $input[$i] <=2147483647){
   
       $byte1 = 252 + ($input[$i] / 1073741824);
       $byte2 = 128 + (($input[$i] / 16777216) % 64);
       $byte3 = 128 + (($input[$i] / 262144) % 64);
       $byte4 = 128 + (($input[$i] / 4096) % 64);
       $byte5 = 128 + (($input[$i] / 64) % 64);
       $byte6 = 128 + ($input[$i] % 64);
       $utf  .= chr($byte1).chr($byte2).chr($byte3).
chr($byte4).chr($byte5).chr($byte6);
    }
   }
   return $utf;
}

//$string=ord($str);
/*
function ascii2entities($string){
    for($i=128;$i<=255;$i++){
        $entity = htmlentities(chr($i), ENT_QUOTES, 'GB2312');
        $temp = substr($entity, 0, 1);
        $temp .= substr($entity, -1, 1);
        if ($temp != '&;'){
            $string = str_replace(chr($i), '', $string);
        }
        else{
            $string = str_replace(chr($i), $entity, $string);
        }
    }
    return $string;
}
*/
//echo ascii2entities($str);
/*function tohtml($s){
         $tmp = "";
         for($i=0;$i<strlen($s);$i++){
                 $tmp .= "&#" . hexdec(bin2hex($s[$i])) . ";";
         }
         return htmlentities($tmp);
 }*/
//echo tohtml($s);
//echo htmlspecialchars($str, ENT_QUOTES, 'GB2312');
//echo mb_detect_encoding($str,'UTF-8', 'GB2312');
//echo htmlspecialchars($str);
//$str = "&#20320;&#22909;";
//echo mb_convert_encoding($str, "UTF-8", "HTML-ENTITIES");
//echo mb_convert_decoding($str, "GB2312", "HTML-ENTITIES");
//echo "&#20320;";
//echo mb_convert_encoding($str, ‘UTF-8′, ‘HTML-ENTITIES’);
$query=$_DB->Query("select * from jos_porsche_content where id='1'");

$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
	{
	$dataDB['Result']['Data'][]['Status']=("No words");
echo json_encode($dataDB);
exit;
}
else
{
//echo"<pre>";
//$dataDB['Result']['Data'][]['content']=$_DB->GetResultValue($query,0,"content");
$dataDB=$_DB->GetResultValue($query,0,"content");
print_r($dataDB);
//echo json_encode(mb_convert_decoding($dataDB, "UTF-8", "HTML-ENTITIES"));
//echo json_encode($dataDB);
//echo $dataDB;
}

//$query=$_DB->Execute("insert into jos_porsche_content values('','我们明天要去巴刹。','')");

?>