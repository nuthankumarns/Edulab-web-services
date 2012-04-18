<?php








// Instanciation of inherited class
include'../setup.php';


		$school_id=$_REQUEST['school_id'];
		$level_id=$_REQUEST['level_id'];
		$class_id=$_REQUEST['class_id'];
		$module_id=$_REQUEST['module_id'];
		$ability=$_REQUEST['ability'];
		$task=$_REQUEST['task'];

		$query1=$_DB->Query("SELECT A.name from jos_porsche_schools A where id='$school_id'");
		$school_name=$_DB->GetResultValue($query1,0,'name');

		//echo $school_name."hi";
		/*$pdf->Cell(0,5,'School Name  : '.$school_name,0,1);
		$pdf->Cell(0,5,'Level Name   : '.$level_id,0,1);
		$pdf->Cell(0,5,'Class Name   : '.$class_id,0,1);
		$pdf->Cell(0,5,'Module Name  : '.$module_id,0,1);
		$pdf->Cell(0,5,'Ability Group: '.$ability,0,1);*/



		switch($task){
		case'0':
		
		/*order by users completed in shortest time */
	//	$pdf->Cell(0,8,'Users completed in shortest time: ',0,1);

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
		break;
		case'1':
		/* order by users complete in longest time */
	//	$pdf->Cell(0,8,'Users complete in longest time: ',0,1);

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
	//	$pdf->Cell(0,8,'Users average highest first: ',0,1);

	}
//echo "<pre>";
$count=$_DB->GetResultNumber($query);
$data=array();


for($i=0;$i<$count;$i++)
{	
	$rowLabels[$i]=$_DB->GetResultValue($query,$i,'word');
for($j=0;$j<1;$j++)
	{
	$data[$i][$j]=$_DB->GetResultValue($query,$i,'sum_of_errors');
	//unset($data[$j]);
	}
	
}
//print_r($data);

require_once('fpdf.php');

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
$reportName=$school_name;
$reportNameYPos = 0;
//$reportNameYPos = 160;
$logoXPos = 50;
$logoYPos = 108;
$logoWidth = 110;
//$columnLabels = array( "Q1", "Q2", "Q3", "Q4" );
//$columnLabels = array( "Q1", "Q2");
$columnLabels = array("No of Errors");
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
$pdf = new FPDF( 'P', 'mm', 'A4' );

$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->AddPage();

// Logo
//$pdf->Image( $logoFile, $logoXPos, $logoYPos, $logoWidth );

// Report Name
$pdf->SetFont( 'Arial', 'B', 24 );
$pdf->Ln( $reportNameYPos );
$pdf->Cell( 0, 15, $reportName, 0, 0, 'C' );
$pdf->SetDrawColor( $tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2] );
$pdf->Ln( 15 );

// Create the table header row
$pdf->SetFont( 'Arial', 'B', 15 );

// "PRODUCT" cell
$pdf->SetTextColor( $tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2] );
$pdf->SetFillColor( $tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2] );
$pdf->Cell( 46, 12, " Student name", 1, 0, 'L', true );

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
