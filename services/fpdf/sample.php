<?php
require('fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
   // $this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(30,10,'Reports',1,0,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',10);



		//include'../set_xml_session.php';
		include'../setup.php';
		/*header("content-Type:application/xml");
		$output="<?xml version='1.0' encoding='utf-8'?><Result>";

		*/
		
		$school_id=$_REQUEST['school_id'];
		$level_id=$_REQUEST['level_id'];
		$class_id=$_REQUEST['class_id'];
		$module_id=$_REQUEST['module_id'];
		$ability=$_REQUEST['ability'];

		$query1=$_DB->Query("SELECT A.name from jos_porsche_schools A where id='$school_id'");
		$school_name=$_DB->GetResultValue($query1,0,'name');

		//echo $school_name."hi";
		$pdf->Cell(0,5,'School Name  : '.$school_name,0,1);
		$pdf->Cell(0,5,'Level Name   : '.$level_id,0,1);
		$pdf->Cell(0,5,'Class Name   : '.$class_id,0,1);
		$pdf->Cell(0,5,'Module Name  : '.$module_id,0,1);
		$pdf->Cell(0,5,'Ability Group: '.$ability,0,1);

		

		//www.tritonetech.com/php_uploads/porsche/webservice/writing_assessment.php?school_id=&level_id=&class_id=&module_id=&ability=
		$query=$_DB->Query("SELECT c.word, sum( c.self_error ) as sum_of_errors
		FROM jos_porsche_student AS a
		JOIN jos_porsche_self AS b ON a.id = b.student_id
		JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
		WHERE a.school_id = '$school_id'
		AND a.level_id = '$level_id'
		AND a.class_id = '$class_id'
		AND b.module_id = '$module_id'
		AND b.ability = '$ability'
		AND b.task = 'self_write'
		AND c.self_error > '0'
		GROUP BY (
		c.word
		)
		ORDER BY sum( c.self_error ) DESC
		LIMIT 0 , 10 ");
		$count=$_DB->GetResultNumber($query);
		if($count==0)
		{ 
		echo "No values";
		//$pdf->Cell(0,10,'Edulab PDF Reports Line No ',0,1);
		//$pdf->Output('Reports','D'); 
                //$output.="<Status>"."No words exist"."</Status>";
		}
		else
		{
			for($i=0;$i<$count;$i++)
			{
			$pdf->Cell(0,10,'Edulab PDF Reports Line No '.$_DB->GetResultValue($query,$i,'word'),0,1);

//echo ;

                        $pdf->Cell(100,10,'Edulab PDF Reports Line No '.$_DB->GetResultValue($query,$i,'sum_of_errors'),0,1);

			$pdf->Output('Reports','D'); 


			//$output.="<word>".$_DB->GetResultValue($query,$i,'word')."</word><sum_of_errors>".$_DB->GetResultValue($query,$i,'sum_of_errors')."</sum_of_errors>";
			}
		}
		//echo $output."</Result>";















		
		    
		
?>
