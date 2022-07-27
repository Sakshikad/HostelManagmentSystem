 <?php
include_once('../../config.php');


$sql = "SELECT reg_no,F_name,L_name,year,rooms_booked from registration where rooms_booked!='' ";
$result1 = $conn->query($sql);
$student_records = array();
while( $rows = mysqli_fetch_assoc($result1) ) {
$student_records[] = $rows;
}	
if(isset($_POST["export_data"])) {	
	$filename = "student_data ".date('Y-m-d') . ".xls";			
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");	
	$show_coloumn = false;
	if(!empty($student_records)) {
	  foreach($student_records as $record) {
		if(!$show_coloumn) {
		  // display field/column names in first row
		  echo implode("\t", array_keys($record)) . "\n";
		  $show_coloumn = true;
		}
		echo implode("\t", array_values($record)) . "\n";
	  }
	}
	exit;  
}?>