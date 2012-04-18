<?php
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
//echo "<pre>";

//print_r($data1);


		$school_id=$_REQUEST['school_id'];
		$level_id=$_REQUEST['level_id'];
		$class_id=$_REQUEST['class_id'];
		$module_id=$_REQUEST['module_id'];
		$ability=$_REQUEST['ability'];
		$task=$_REQUEST['task'];
//www.tritonetech.com/php_uploads/porsche/webservice/fpdf/group_writing_task_report.php?school_id=&level_id=&class_id=&module_id=&ability=
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
$pdf = new FPDF( 'P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );


$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
$pdf->SetFont( 'Arial', '', 17 );
$pdf->Cell( 0, 15, 'Group Writing Task Reports', 0, 0, 'C' );
$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
$pdf->SetFont( 'Arial', '', 20 );
//$pdf->Write( 10, "2009 Was A Good Year" );
$pdf->Ln( 16 );
$pdf->SetFont( 'Arial', '', 12 );
$query=$_DB->Query("SELECT a.id ,a.name,  c.id AS group_content_id
FROM jos_porsche_student AS a
LEFT JOIN jos_porsche_group_members AS b ON a.id = b.student_id
LEFT JOIN jos_porsche_group AS c ON b.group_id = c.group_id AND task='group_write'
AND c.module_id = '$module_id'
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND a.ability = '$ability'");

$count=$_DB->GetResultNumber($query);
$group_content_id=$_DB->GetResultValue($query,0,'group_content_id');
//echo $count;
//echo $group_content_id;
for($i=0;$i<$count;$i++)
{
$rowLabels[$i]=$_DB->GetResultValue($query,$i,'name');
$students[$i]=$_DB->GetResultValue($query,$i,'id');

//echo $students[$i]."<br/>";

	//for($j=0;$j<count($student);$i++)
	//{
	/*no. of posted questions by me**/
	$q1=$_DB->Query("SELECT count( id ) AS posted_questions
		FROM jos_porsche_group_writing_task
		WHERE group_content_id = '$group_content_id'
		AND student_id = '$students[$i]'");
	
	//no. of posted questions to me
	$q2=$_DB->Query("SELECT count( id ) AS received_questions
		FROM jos_porsche_group_writing_task
		WHERE group_content_id = '$group_content_id'
		AND q_s_id = '$students[$i]'");
	
	/*no. of unanswered questions by me*/
	$q3=$_DB->Query("SELECT count(id) AS unanswered_questions
		FROM jos_porsche_group_writing_task 
		WHERE group_content_id = '$group_content_id'
		AND q_s_id = '$students[$i]' AND progress='0'");
	
	//no. of answered questions by me*/
	$q4=$_DB->Query("SELECT count(id) AS answered_questions
		FROM jos_porsche_group_writing_task 
		WHERE group_content_id = '$group_content_id'
		AND q_s_id = '$students[$i]' AND progress>0");
		
	//no. of correct responses by me
	$q5=$_DB->Query("SELECT group_writing 
		FROM jos_porsche_student_module
		WHERE student_id = '$students[$i]' AND module_id='$module_id'");

	/*$q5=$_DB->Query("SELECT count( id ) AS wrong_responses
		FROM jos_porsche_group_writing_task
		WHERE group_content_id = '$group_content_id'
		AND q_s_id = '$students[$i]' AND score>0");*/
	//for($j=0;$j<$count;$j++)
	//{
	$data[$i][0]=$_DB->GetResultValue($q1,0,'posted_questions');
	$data[$i][1]=$_DB->GetResultValue($q2,0,'received_questions');
	$data[$i][2]=$_DB->GetResultValue($q3,0,'unanswered_questions');
	$data[$i][3]=$_DB->GetResultValue($q4,0,'answered_questions');
	$data[$i][4]=$_DB->GetResultValue($q5,0,'group_writing');
	
	//}
}
//echo "<pre>";
//print_r($data);
//exit();
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
$first_row="Student Name";
$columnLabels = array("posted","received","unanswered","answered","cookies");
$pdf->SetFont( 'Arial', 'B', 10 );
$pdf->Ln( $reportNameYPos );
//$pdf->Cell( 0, 15, $reportName, 0, 0, 'C' );
$pdf->SetDrawColor( $tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2] );
//$pdf->Ln( 15 );

// Create the table header row
$pdf->SetFont( 'Arial', 'B', 10 );

// "PRODUCT" cell
$pdf->SetTextColor( $tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2] );
$pdf->SetFillColor( $tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2] );
$pdf->Cell( 46, 12, $first_row, 1, 0, 'L', true );

// Remaining header cells
$pdf->SetTextColor( $tableHeaderTopTextColour[0], $tableHeaderTopTextColour[1], $tableHeaderTopTextColour[2] );
$pdf->SetFillColor( $tableHeaderTopFillColour[0], $tableHeaderTopFillColour[1], $tableHeaderTopFillColour[2] );

for ( $i=0; $i<count($columnLabels); $i++ ) {
  $pdf->Cell( 30, 12, $columnLabels[$i], 1, 0, 'C', true );
}

$pdf->Ln( 12 );

// Create the table data rows

$fill = false;
$row = 0;

foreach ( $data as $dataRow ) {

  // Create the left header cell
  $pdf->SetFont( 'Arial', 'B', 10 );
  $pdf->SetTextColor( $tableHeaderLeftTextColour[0], $tableHeaderLeftTextColour[1], $tableHeaderLeftTextColour[2] );
  $pdf->SetFillColor( $tableHeaderLeftFillColour[0], $tableHeaderLeftFillColour[1], $tableHeaderLeftFillColour[2] );
  $pdf->Cell( 46, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );

  // Create the data cells
  $pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
  $pdf->SetFillColor( $tableRowFillColour[0], $tableRowFillColour[1], $tableRowFillColour[2] );
  $pdf->SetFont( 'Arial', '', 15 );

  for ( $i=0; $i<count($columnLabels); $i++ ) {
    $pdf->Cell( 30, 12, $dataRow[$i], 1, 0, 'C', $fill );
//	$pdf->Cell(36,12,'Time Taken: '.$_DB->GetResultValue($query,$i,'time_taken'),0,1);
  }

  $row++;
  $fill = !$fill;
  $pdf->Ln( 12 );
}

$pdf->Output('Reports','D');


/*SELECT count( id )
FROM jos_porsche_group_reading_task
WHERE group_content_id = '17'
AND student_id = '209' */

//no. of posted questions to me
/*SELECT count( id )
FROM jos_porsche_group_reading_task
WHERE group_content_id = '17'
AND q_s_id = '201'
*/

/*no. of unanswered questions by me*/
/*
SELECT count( id )
FROM jos_porsche_group_reading_task
WHERE group_content_id = '17'
AND q_s_id = '201' AND filename=''
*/

//no. of answered questions by me*/
/*
SELECT count( id )
FROM jos_porsche_group_reading_task
WHERE group_content_id = '17'
AND q_s_id = '201' AND filename<>''
*/

//no. of correct responses by me
/*
SELECT count( id )
FROM jos_porsche_group_reading_task
WHERE group_content_id = '17'
AND q_s_id = '201' AND score>0
*/

?>
