<html>
<body>
<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>

<?php

$sequencing_id=preg_replace('/\s+/', '',$_POST["run_ID"]);
$date=preg_replace('/\s+/', '',$_POST["date"]);
$loading_con=$_POST["loading_con"];
$phix_con=$_POST["phix_con"];
$kit=$_POST["kit"];
$notes = $_POST["notes"];
$seq_type = $_POST["seq_type"];

//if(!empty($_POST['performer'])) {
  //  foreach($_POST['performer'] as $check) {
    //        $all_performer=join(",",$check);
   // }
//}

$all_performer=join(",",$_POST['check_list']);
//foreach($_POST['check_list'] as $check){
//	$all_performer=$all_performer.",".$check;
//}


#echo "Sequencing ID:         <strong> $sequencing_id</strong><br/>";
#echo "Date:         <strong> $date</strong><br/>";
#echo "Loading Concentration:         <strong>$loading_con</strong><br/>";
#echo "Phix Concentration:         <strong>$phix_con</strong><br/>";
#echo "Kit:      <strong>$kit</strong><br/>";
#echo "Notes:      <strong>$notes</strong><br/>";
#echo "Performer:  <strong>$all_performer</strong><br>";
#echo "Sequencing type: $seq_type<br>";
#echo "<br/><br/>";

$fileName = $_FILES["uploaded_file"]["name"];//the files name takes from the HTML form
$fileTmpLoc = $_FILES["uploaded_file"]["tmp_name"];//file in the PHP tmp folder
$fileType = $_FILES["uploaded_file"]["type"];//the type of file
$fileSize = $_FILES["uploaded_file"]["size"];//
$fileErrorMsg = $_FILES["uploaded_file"]["error"];//0 for false and 1 for true
#$filesave="/var/www/html/upload/final/"
$target_path = "/var/www/html/upload/illumina/" . basename( $_FILES["uploaded_file"]["name"]);
#echo "file name: $fileName </br> temp file location: $fileTmpLoc<br/> file type: $fileType<br/> file size: $fileSize<br/> file upload target: $target_path<br/> file error msg: $fileErrorMsg<br/>";

//START PHP Image Upload Error Handling---------------------------------------------------------------------------------------------------

    if(!$fileTmpLoc)//no file was chosen ie file = null
    {
        echo "ERROR: Please select a file before clicking submit button.";
	exit();
    }
    else
        if(!$fileSize > 16777215)//if file is > 16MB (Max size of MEDIUMBLOB)
        {
            echo "ERROR: Your file was larger than 16 Megabytes";

            unlink($fileTmpLoc);//remove the uploaded file from the PHP folder
            exit();
        }
            else
                if($fileErrorMsg == 1)//if file uploaded error key = 1 ie is true
                {
                    echo "ERROR: An error occured while processing the file. Please try again.";
                    exit();
                }


//END PHP Image Upload Error Handling---------------------------------------------------------------------------------------------------------------------

//Place it into your "uploads" folder using the move_uploaded_file() function
    $moveResult = move_uploaded_file($fileTmpLoc, $target_path);

    //Check to make sure the result is true before continuing
    if($moveResult != true)
    {
        echo "ERROR: File not uploaded. Please Try again.";
        unlink($fileTmpLoc);//remove the uploaded file from the PHP temp folder

    }
    else
    {
	echo "Succeed reading in the library stats table!";
#	echo "<strong>$target_path</strong><br/><br/>";
        //Display to the page so you see what is happening 
#        echo "The file named <strong>$fileName</strong> uploaded successfully.<br/><br/>";
 #       echo "It is <strong>$fileSize</strong> bytes.<br/><br/>";
  #      echo "It is a <strong>$fileType</strong> type of file.<br/><br/>";
   #     echo "The Error Message output for this upload is: $fileErrorMsg";
    }
$perl_result = array();
exec("sudo perl /var/www/html/script/extract.pl $target_path $seq_type",$perl_result );
#    $perl_result = shell_exec("sudo perl /var/www/html/script/extract.pl $target_path $seq_type" );
#$perl_result = implode("\n", $perl_result);  # 	

$q30=$perl_result[0];
$yield=$perl_result[1];
$align=$perl_result[2];
$density=$perl_result[3];
$cluster=$perl_result[4];
$reads_sum=$perl_result[5];
$reads_pf=$perl_result[6];


#echo "$date\t$sequencing_id\t$kit\t$density\t$cluster\t$reads_sum\t$reads_pf\t$q30\t$yield\t$phix_con\t$align\t$loading_con\t$all_performer\t$notes";
?>

<h4>Your Input</h4>
<table border="2">
<tr>
  <td>Date</td>
  <td>Run Name</td>
  <td>Kit</td>
  <td>Density (K/mm2)</td>
  <td>Cluster PF(%)</td>
  <td>Reads</td>
  <td>Reads PF</td>
  <td>% >Q30</td>
  <td>Yield(Gbp)</td>
  <td>PhiX Concentration(%)</td>
  <td>%Aligned</td>
  <td>Loading Concentration</td>
  <td>Performed by</td>
  <td>Notes:</td>
</tr>
<tr>
  <td><?php echo $date ?></td>
  <td><?php echo $sequencing_id ?></td>
  <td><?php echo $kit ?></td>
  <td><?php echo $density ?></td>
  <td><?php echo $cluster ?></td>
  <td><?php echo $reads_sum ?></td>
  <td><?php echo $reads_pf ?></td>
  <td><?php echo $q30 ?></td>
  <td><?php echo $yield ?></td>
  <td><?php echo $phix_con ?></td>
  <td><?php echo $align ?></td>
  <td><?php echo $loading_con ?></td>
  <td><?php echo $all_performer ?></td>  
  <td><?php echo $notes ?></td>  
</tr>
</table>

</body>
</html>

