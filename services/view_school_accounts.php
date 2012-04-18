<?php
include'set_session.php';
//www.tritonetech.com/php_uploads/porsche/webservice/view_school_accounts.php
include "setup.php";

$query=$_DB->Query("select * from jos_porsche_schools where id>='1'");

$count = $_DB->GetResultNumber($query);
if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No Records");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		for($i=0; $i < $count; $i++)
        {
            $dataDB['Result']['Data'][$i]['school_id'] = $_DB->GetResultValue($query, $i,"id");
		$dataDB['Result']['Data'][$i]['school_name'] = $_DB->GetResultValue($query, $i, "name");
			$dataDB['Result']['Data'][$i]['description'] = $_DB->GetResultValue($query, $i, "description");
				$dataDB['Result']['Data'][$i]['zone'] = $_DB->GetResultValue($query, $i, "zone");
					$dataDB['Result']['Data'][$i]['postal'] = $_DB->GetResultValue($query, $i, "postal");
						$dataDB['Result']['Data'][$i]['address'] = $_DB->GetResultValue($query, $i, "address");
							$dataDB['Result']['Data'][$i]['phone'] = $_DB->GetResultValue($query, $i, "phone");
				

        }
	
	echo json_encode($dataDB);
	}

	


?>

