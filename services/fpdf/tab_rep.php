<?php
require('fpdf.php');


//error_reporting(E_ALL);
//ini_set('display_errors','On');
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


//load data
function LoadData($file)
{
	// Read file lines
	$lines = $file;
	$data = array();
	foreach($lines as $line)
		$data[] = explode(';',trim($line));
	return $data;
}

function BasicTable($header, $data)
{
	// Header
	foreach($header as $col)
		$this->Cell(40,7,$col,1);
	$this->Ln();
	// Data
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(40,6,$col,1);
		$this->Ln();
	}
}

}


// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',10);

//$data = $pdf->LoadData('countries.txt');

$header = array('Name', 'Time Taken');
$pdf->AddPage();

$pdf->BasicTable($header,$data);



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
		$task=$_REQUEST['task'];

		$query1=$_DB->Query("SELECT A.name from jos_porsche_schools A where id='$school_id'");
		$school_name=$_DB->GetResultValue($query1,0,'name');

		//echo $school_name."hi";
		$pdf->Cell(0,5,'School Name  : '.$school_name,0,1);
		$pdf->Cell(0,5,'Level Name   : '.$level_id,0,1);
		$pdf->Cell(0,5,'Class Name   : '.$class_id,0,1);
		$pdf->Cell(0,5,'Module Name  : '.$module_id,0,1);
		$pdf->Cell(0,5,'Ability Group: '.$ability,0,1);



		switch($task){
		case'0':
		
		/*order by users completed in shortest time */
		$pdf->Cell(0,8,'Users completed in shortest time: ',0,1);
		
		$query=$_DB->Query("SELECT a.name,c.self_content_id, SEC_TO_TIME( sum( TIME_TO_SEC( c.self_time + c.unaided_time ) ) ) AS time_taken
		FROM jos_porsche_student AS a
		JOIN jos_porsche_self AS b ON a.id = b.student_id
		JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
		WHERE a.school_id = '$school_id'
		AND a.level_id = '$level_id'
		AND a.class_id = '$class_id'
		AND b.module_id = '$module_id'
		AND b.ability = '$ability'
		AND b.task = 'self_write'
		GROUP BY self_content_id
		ORDER BY time_taken  ASC");
		break;
		case'1':
		/* order by users complete in longest time */
		$pdf->Cell(0,8,'Users complete in longest time: ',0,1);

		$query=$_DB->Query("SELECT a.name,c.self_content_id, SEC_TO_TIME( sum( TIME_TO_SEC( c.self_time + c.unaided_time ) ) ) AS time_taken
		FROM jos_porsche_student AS a
		JOIN jos_porsche_self AS b ON a.id = b.student_id
		JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
		WHERE a.school_id = '$school_id'
		AND a.level_id = '$level_id'
		AND a.class_id = '$class_id'
		AND b.module_id = '$module_id'
		AND b.ability = '$ability'
		AND b.task = 'self_write'
		GROUP BY self_content_id
		ORDER BY  time_taken  DESC");
		break;
		case'2':
		/*order by users average highest first*/
		$pdf->Cell(0,8,'Users average highest first: ',0,1);

		$query=$_DB->Query("SELECT a.name, c.self_content_id, avg( c.self_count ) AS average , count( self_count ) AS number_of_words
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
		c.self_content_id
		)
		ORDER BY avg( c.self_count ) DESC");
		break;
		case'3':
		/*order by users average lowest first*/

		$pdf->Cell(0,8,'Users average Lowest first: ',0,1);

		$query=$_DB->Query("SELECT a.name, c.self_content_id, avg( c.self_count ) AS average , count( self_count ) AS number_of_words
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
		c.self_content_id
		)
		ORDER BY avg( c.self_count ) ASC");
		break;
		default:
		$dataDB['Result']['Data'][0]['Status']="parameters missing";
		break;
		}

		//var_dump($query);
		$count=$_DB->GetResultNumber($query);
		//echo $count;
		if($count==0)
		{
		$dataDB['Result']['Data'][0]['Status']="No users exist";
		}
		else
		{
			for($i=0;$i<$count;$i++)
			{
				$pdf->Cell(0,10,'Student Name: '.$_DB->GetResultValue($query,$i,'name'),0,1);

				$data = $pdf->LoadData($_DB->GetResultValue($query,$i,'name'));
				
				switch($task){
				case'0':
				case'1':
				$pdf->Cell(300,10,'Time Taken: '.$_DB->GetResultValue($query,$i,'time_taken'),0,1);
				
				break;
				case'2':
				case'3':
				$pdf->Cell(100,10,'Average:'.$_DB->GetResultValue($query,$i,'average'),0,1);

				$pdf->Cell(100,10,'No of Words: '.$_DB->GetResultValue($query,$i,'number_of_words'),0,1);
				
				break;
				}

			}

		}





$pdf->Output('Reports','D'); 

























		
		    
		
?>
