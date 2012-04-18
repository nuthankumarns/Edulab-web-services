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

global $school_id,$level_id,$class_id,$module_id,$ability,$task,$link;
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

//echo $query."jjjj";

 $res=mysql_query($query);

  $count = mysql_num_rows($res);


$row1=mysql_fetch_array($res);
foreach ($row1 as $field1=>$value)
{ 
//echo $value."khooo<br/>";

//$data[]=$value;
$data[]= explode(';',trim($value));
}


//echo $count."cccohhhhhhh";
//$i=1;
//while($row1=mysql_fetch_array($res))
//{
 
//$row1[]=$row1[$i];
//echo $row1['0']."first value";
//foreach ($row1 as $field=>$value) 
//echo $value."khooo<br/>";
//$data[]= explode(';',trim($field));


//$i++;

//}



//$data=array(0=>array(a,b,c,d));
    return $data;
}

//

// Simple table
function BasicTable($header, $data)
{

//print_r($data);
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
foreach($data as $row)
		foreach($row as $col1)
            $this->Cell(40,6,$col1,1);
        $this->Ln();


    /*foreach($data as $row)
    {
	// print_r($row)."great";
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    } */
}



}

$pdf = new PDF();
// Column headings
$header = array('Country', 'Capital','Time Taken','fhfh');
// Data loading
$data = $pdf->FetchData();
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$data);
$pdf->AddPage();


//echo "hhh";

$pdf->Output();
?>
