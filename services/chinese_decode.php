<?php
include'setup.php';
//$a=$_REQUEST['china'];
$a['data']='我到附近的巴刹去买菜';
echo json_encode($a);
$b=json_encode($a['data']);
echo json_decode($b);


?>