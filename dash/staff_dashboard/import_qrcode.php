<?php  

session_start();


include '../../config.php';

require 'vendor/autoload.php';

use phpOffice\PhpSpreadsheet\Spreadsheet;

use phpOffice\PhpSpreadsheet\Writer\Xlsx;


if(isset($_POST['import'])){
 $allowed_ext= ["xls","xlsx","csv"];
 $fileName=$_FILES['file']['name'];
 $checking=explode(".",$fileName);
    $file_ext=end( $checking);
    
    if(in_array($file_ext,$allowed_ext))
    {
        $targetPath=$_FILES['file']['tmp_name'];
        $spreadsheet=\PhpOffice\PhpSpreadsheet\IOFactory::load($targetPath);
        $data=$spreadsheet->getActiveSheet()->toArray();
        
        foreach($data as $row)
        {
            $reg_no=$row['0'];
            $url=$row['5'];
            $qrcode=$row['6'];
            
            $checkStudent="select * from registration where reg_no='$reg_no' ";
             $checkStudent_res=mysqli_query($conn,$checkStudent);
            
            if(mysqli_num_rows(  $checkStudent_res)>0)
            {
                //already exist 
                $up_query="update registration set url='$url',qrcode='$qrcode' where reg_no='$reg_no'";
                
                $up_res=mysqli_query($conn,$up_query);
                $msg=1;
                
            }
            else{
              //new record
                $in_query="insert into registration(url,qrcode) values('$url','$qrcode') where rooms_booked!='' ";
                $in_res=mysqli_query($conn,$in_query);
                  $msg=1;
            }
            
        }
        if(isset($msg))
        {
         $_SESSION['status']="File imported! ";
        header("Location:Attendance.php");
        
        }
        else
        {
        
         $_SESSION['status']="File importing failed";
        header("Location:Attendance.php");
        }
    }
    else
    {
        $_SESSION['status']="Invalid file";
        header("Location:Attendance.php");
        exit(0);
    
    }


}


?>