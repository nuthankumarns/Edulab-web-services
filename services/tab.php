<?php
require('fpdf.php');
error_reporting(E_ALL);
ini_set('display_errors','On');
include'../setup.php';
		$school_id=$_REQUEST['school_id'];
		$level_id=$_REQUEST['level_id'];
		$class_id=$_REQUEST['class_id'];
		$module_id=$_REQUEST['module_id'];
		$ability=$_REQUEST['ability'];
		$task=$_REQUEST['task'];


$user="sopdb40";
$password="Tritone123";
$database="sopdb40";
mysql_connect("sopdb40.db.5236568.hostedresource.com","$user","$password") or die(mysql_error());
$link=mysql_select_db($database) or die( "Unable to select database");



//$data=$_DB->GetResultValue($query,$i,'name');

//echo $data;
class PDF extends FPDF
{
// Load data
function FetchData()
{
  $query="SELECT a.name,c.self_content_id, SEC_TO_TIME( sum( TIME_TO_SEC( c.self_time + c.unaided_time ) ) ) AS time_taken
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
		ORDER BY time_taken  ASC";

 $res=mysql_query($query);

$data.=mysql_fetch_array($res);
  

//echo $row[0]."hhhh";

// Read file lines
   // $lines = file($file);
    //$data = array();
    //foreach($row as $key=>$value)
 
        //$data[] = explode(';',trim($value));
    return $data;
}

// Simple table
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

$pdf = new PDF();
// Column headings
//$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
// Data loading
$data = $pdf->FetchData();
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$data);
$pdf->AddPage();


echo "hhh";

//$pdf->Output();
?>
