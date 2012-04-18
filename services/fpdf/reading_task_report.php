<?php

//error_reporting(E_ALL);
//ini_set('display_errors','On');
/*class PDF extends FPDF
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
}*/






// Instanciation of inherited class
include'../setup.php';
//require_once('fpdf.php');
include'fpdf.php';

$textColour = array( 0, 0, 0 );
$headerColour = array( 100, 100, 100 );
$tableHeaderTopTextColour = array( 255, 255, 255 );
$tableHeaderTopFillColour = array( 125, 152, 179 );
$tableHeaderTopProductTextColour = array( 0, 0, 0 );
$tableHeaderTopProductFillColour = array( 143, 173, 204 );
$tableHeaderLeftTextColour = array( 99, 42, 57 );
$tableHeaderLeftFillColour = array( 184, 207, 229 );
$tableBorderColour = array( 50, 50, 50 );
$tableRowFillColour = array( 213, 170, 170 );
//$reportName = "2009 Widget Sales Report";
/*task:0 no. of recordings per user
task:1 users time taken in ascending
task:2 users time taken in descending*/
$reportNameYPos = 15;
//$reportNameYPos = 160;
$logoXPos = 50;
$logoYPos = 108;
$logoWidth = 110;
//$columnLabels = array( "Q1", "Q2", "Q3", "Q4" );
//$columnLabels = array( "Q1", "Q2");

//$rowLabels = array( "SupaWidget", "WonderWidget", "MegaWidget", "HyperWidget" );
$chartXPos = 20;
$chartYPos = 250;
$chartWidth = 160;
$chartHeight = 80;
$chartXLabel = "Product";
$chartYLabel = "2009 Sales";
$chartYStep = 20000;

$data1 = array(
          array( 9940, 10100, 9490, 11730 ),
          array( 19310, 21140, 20560, 22590 ),
          array( 25110, 26260, 25210, 28370 ),
          array( 27650, 24550, 30040, 31980 ),
        );

//print_r($data1);

	
		$school_id=$_REQUEST['school_id'];
		$level_id=$_REQUEST['level_id'];
		$class_id=$_REQUEST['class_id'];
		$module_id=$_REQUEST['module_id'];
		$ability=$_REQUEST['ability'];
		$task=$_REQUEST['task'];
$pdf = new FPDF( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );


$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
$pdf->SetFont( 'Arial', '', 17 );
$pdf->Cell( 0, 15, 'Reading task Reports', 0, 0, 'C' );
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->SetFont( 'Arial', '', 20 );
//$pdf->Write( 10, "2009 Was A Good Year" );
$pdf->Ln( 16 );
$pdf->SetFont( 'Arial', '', 12 );
/*$pdf->Write( 6, "Despite the economic downturn, WidgetCo had a strong year. Sales of the HyperWidget in particular exceeded expectations. The fourth quarter was generally the best performing; this was most likely due to our increased ad spend in Q3." );*/


		$query=$_DB->Query("SELECT a.name,a.description,a.zone,a.phone, b.name AS level_name , c.name AS class_name,d.module_name,e.name as subject_name
				FROM jos_porsche_schools AS a
				LEFT JOIN jos_porsche_level AS b ON a.id = b.school_id
				AND b.id = '$level_id'
				LEFT JOIN jos_porsche_classes AS c ON b.id = c.level_id
				AND c.id = '$class_id'
				LEFT JOIN jos_porsche_module AS d ON d.id='$module_id'
				LEFT JOIN jos_porsche_subject AS e ON d.subject_id=e.id
				WHERE a.id = '$school_id'");
		$school_name=$_DB->GetResultValue($query,0,'name');
		$description=$_DB->GetResultValue($query,0,'description');
		$zone=$_DB->GetResultValue($query,0,'zone');
		$phone=$_DB->GetResultValue($query,0,'phone');		
		$level_name=$_DB->GetResultValue($query,0,'level_name');
		$class_name=$_DB->GetResultValue($query,0,'class_name');
		$module_name=$_DB->GetResultValue($query,0,'module_name');
		$subject_name=$_DB->GetResultValue($query,0,'subject_name');
	


		switch($task){
		case'0':
		$pdf->Cell(0,8,'count of recordings per user: ',0,1);
		$query=$_DB->Query("SELECT a.name, sum( c.record_count ) AS record_count
		FROM jos_porsche_student AS a
		JOIN jos_porsche_self AS b ON a.id = b.student_id
		JOIN jos_porsche_self_reading_task AS c ON b.id = c.self_content_id
		WHERE a.school_id = '$school_id'
		AND a.level_id = '$level_id'
		AND a.class_id = '$class_id'
		AND b.ability = '$ability'
		AND b.task = 'self_read'
		AND b.module_id = '$module_id'
		GROUP BY c.self_content_id
		ORDER BY c.record_count ASC");
		break;
		case'1':
		$pdf->Cell(0,8,'Users duration in descending: ',0,1);
		$query=$_DB->Query("SELECT a.name, SEC_TO_TIME( sum( TIME_TO_SEC( c.total_time ) ) ) AS total_time, c.self_content_id
		FROM jos_porsche_student AS a
		JOIN jos_porsche_self AS b ON a.id = b.student_id
		JOIN jos_porsche_self_reading_task AS c ON b.id = c.self_content_id
		WHERE a.school_id = '$school_id'
		AND a.level_id = '$level_id'
		AND a.class_id = '$class_id'
		AND b.ability = '$ability'
		AND b.task = 'self_read'
		AND b.module_id = '$module_id'
		GROUP BY c.self_content_id
		ORDER BY c.total_time DESC");
		break;
		case'2':
		$pdf->Cell(0,8,'Users duration in ascending: ',0,1);
		$query=$_DB->Query("SELECT a.name, SEC_TO_TIME( sum( TIME_TO_SEC( c.total_time ) ) ) AS total_time, c.self_content_id
		FROM jos_porsche_student AS a
		JOIN jos_porsche_self AS b ON a.id = b.student_id
		JOIN jos_porsche_self_reading_task AS c ON b.id = c.self_content_id
		WHERE a.school_id = '$school_id'
		AND a.level_id = '$level_id'
		AND a.class_id = '$class_id'
		AND b.ability = '$ability'
		AND b.task = 'self_read'
		AND b.module_id = '$module_id'
		GROUP BY c.self_content_id
		ORDER BY c.total_time ASC");
		break;
		
		}

		//var_dump($query);
		$count=$_DB->GetResultNumber($query);
		//echo $count;
		if($count==0)
		{
		//$dataDB['Result']['Data'][0]['Status']="No users exist";
		}
		else
		{
			
		$count=$_DB->GetResultNumber($query);
		//$data=array();

				
				switch($task){
				case'0':
				
				$first_row="Student Name";
				$columnLabels = array("Record Count");
				for($i=0;$i<$count;$i++)
				{	
					$rowLabels[$i]=$_DB->GetResultValue($query,$i,'name');
				for($j=0;$j<=$i;$j++)
					{
					//$j count of rows static
					$data[$i][0]=$_DB->GetResultValue($query,$j,'record_count');
					//unset($data[$j]);
					}
	
				}
				
				break;
				case'1':
				case'2':
				$first_row="Student Name";
				$columnLabels = array("Duration");
				for($i=0;$i<$count;$i++)
				{	$rowLabels[$i]=$_DB->GetResultValue($query,$i,'name');
					//$j count of rows static
				for($j=0;$j<=$i;$j++)
					{
					//$j count of rows static
					$data[$i][0]=$_DB->GetResultValue($query,$j,'total_time');
					//unset($data[$j]);
					}
	
				}
			
				
				
				break;
				}

			

		}
if($data=='')
{$pdf->Cell(0,8,'NO DATA' ,0,1);
//print_r($data);
//exit;
$pdf->Output('Reports','D');
exit;
}
//exit;
$reportName=$school_name;
$pdf->Write(6, "School:".$reportName);
$pdf->Ln( 5 );
$pdf->Write( 6, "Description:".$description);
$pdf->Ln( 5 );
$pdf->Write( 6, "Zone:".$zone);
$pdf->Ln( 5 );
$pdf->Write( 6, "Phone:".$phone);
$pdf->Ln( 5 );
$pdf->Write( 6, "Ability:".$ability );
$pdf->Ln( 5 );
$pdf->Write( 6, "Level:".$level_name);
$pdf->Ln( 5 );
$pdf->Write( 6, "Class:".$class_name);
$pdf->Ln( 5 );
$pdf->Write( 6, "Subject:".$subject_name);
$pdf->Ln( 5 );
$pdf->Write( 6, "Module:".$module_name);

/*echo "<pre>";
print_r($data1);
print_r($data);*/
// Logo
//$pdf->Image( $logoFile, $logoXPos, $logoYPos, $logoWidth );

// Report Name
$pdf->SetFont( 'Arial', 'B', 24 );
$pdf->Ln( $reportNameYPos );
//$pdf->Cell( 0, 15, $reportName, 0, 0, 'C' );
$pdf->SetDrawColor( $tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2] );
//$pdf->Ln( 15 );

// Create the table header row
$pdf->SetFont( 'Arial', 'B', 15 );

// "PRODUCT" cell
$pdf->SetTextColor( $tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2] );
$pdf->SetFillColor( $tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2] );
$pdf->Cell( 46, 12, $first_row, 1, 0, 'L', true );

// Remaining header cells
$pdf->SetTextColor( $tableHeaderTopTextColour[0], $tableHeaderTopTextColour[1], $tableHeaderTopTextColour[2] );
$pdf->SetFillColor( $tableHeaderTopFillColour[0], $tableHeaderTopFillColour[1], $tableHeaderTopFillColour[2] );

for ( $i=0; $i<count($columnLabels); $i++ ) {
  $pdf->Cell( 36, 12, $columnLabels[$i], 1, 0, 'C', true );
}

$pdf->Ln( 12 );

// Create the table data rows

$fill = false;
$row = 0;

foreach ( $data as $dataRow ) {

  // Create the left header cell
  $pdf->SetFont( 'Arial', 'B', 15 );
  $pdf->SetTextColor( $tableHeaderLeftTextColour[0], $tableHeaderLeftTextColour[1], $tableHeaderLeftTextColour[2] );
  $pdf->SetFillColor( $tableHeaderLeftFillColour[0], $tableHeaderLeftFillColour[1], $tableHeaderLeftFillColour[2] );
  $pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );

  // Create the data cells
  $pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
  $pdf->SetFillColor( $tableRowFillColour[0], $tableRowFillColour[1], $tableRowFillColour[2] );
  $pdf->SetFont( 'Arial', '', 15 );

  for ( $i=0; $i<count($columnLabels); $i++ ) {
    $pdf->Cell( 36, 12, $dataRow[$i], 1, 0, 'C', $fill );
//	$pdf->Cell(36,12,'Time Taken: '.$_DB->GetResultValue($query,$i,'time_taken'),0,1);
  }

  $row++;
  $fill = !$fill;
  $pdf->Ln( 12 );
}

$pdf->Output('Reports','D'); 

?>
