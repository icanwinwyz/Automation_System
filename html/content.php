<?php
$sequencing_id=preg_replace('/\s+/', '',$_POST["run_ID"]);
$pi_last=preg_replace('/\s+/', '',$_POST['PI_Last']);
$pi_first=preg_replace('/\s+/', '',$_POST['PI_First']);
$project_id=preg_replace('/\s+/', '',$_POST['ProjectID']);
$seq_type=$_POST['Sequencing_Type'];
#$org=$_POST['Organism'];
$fastq_ge=$_POST['fastq_ge'];
$arrlength=count($project_id);
$comments=$_POST["comments"];
if (empty($comments)){
        $comments = "No comments.";
}



for($x=0;$x<$arrlength;$x++){
	$text = $project_id[$x]." ".$project_id[$x]."_".$pi_last[$x]."_".$pi_first[$x]."_".$seq_type[$x]."\n";
	$filename = "tmp.txt";
	$fp = fopen("/var/www/html/upload/tmp/$filename", "a") or die("Couldn't open $filename");
	fwrite($fp,$text);
}

fclose($fp);

$all=$sequencing_id;

echo "Sequencing ID: $sequencing_id<br>";

echo "PI: ";
foreach($pi_last as $value){
	echo "$value, ";
	$all=$all."_".$value; ###this is used for changing sample sheet name.
}
#echo "$all<br><br>";
echo "<br>";
echo "Project_ID :";
foreach($project_id as $value){
	echo "$value, ";
}
echo "<br>";
echo "Sequencing Type: ";
foreach($seq_type as $value){
	echo "$value, ";
}
echo "<br>";
echo "Fastq generation: $fastq_ge<br><br><br>";

$sample_sheet=$all.".csv";


$fileName = $_FILES["uploaded_file"]["name"];//the files name takes from the HTML form
$fileTmpLoc = $_FILES["uploaded_file"]["tmp_name"];//file in the PHP tmp folder

$fileType = $_FILES["uploaded_file"]["type"];//the type of file
$fileSize = $_FILES["uploaded_file"]["size"];//
$fileErrorMsg = $_FILES["uploaded_file"]["error"];//0 for false and 1 for true
#$filesave="/var/www/html/upload/final/"

#$target_path = "/var/www/html/upload/tmp/" . basename( $_FILES["uploaded_file"]["name"]);
$target_path = "/var/www/html/upload/tmp/" . $fileName;


if(!$fileTmpLoc)//no file was chosen ie file = null
    {
        echo "ERROR: Please select a file before clicking submit button.";
        shell_exec("sudo rm /var/www/html/upload/tmp/*");
	exit();
    }
else
if(!$fileSize > 16777215)//if file is > 16MB (Max size of MEDIUMBLOB)
    {
        echo "ERROR: Your file was larger than 16 Megabytes";
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
        unlink($fileTmpLoc);//remove the uploaded file from the PHP folder
        exit();
    }
else
if(!preg_match("/\.(csv)$/i", $fileName))//this codition allows only the type of files listed to be uploaded
    {
        echo "ERROR: Your file was not csv";
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
        unlink($fileTmpLoc);//remove the uploaded file from the PHP temp folder
        exit();
    }
else
if($fileErrorMsg == 1)//if file uploaded error key = 1 ie is true
    {
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
        echo "ERROR: An error occured while processing the file. Please try again.";
        exit();
     }
else
if(preg_match("/\s+/", $fileName))
    {
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
	echo "ERROR: No space allowed in name of sample sheet. Please rename the sample sheet into \"SampleSheet.csv\" as example.";
	exit();
}

$moveResult = move_uploaded_file($fileTmpLoc, $target_path);

if($moveResult != true)
    {
        echo "ERROR: File not uploaded. Please Try again.";
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
        unlink($fileTmpLoc);//remove the uploaded file from the PHP temp folder
	exit();
    }
    else
    {
        echo "The Sample Sheet was successfully uploaded to the server.<br><br>";
}


$return_value=shell_exec("sudo perl /var/www/html/script/samplesheet_format_verify.pl $target_path /var/www/html/upload/tmp/$filename MiSeq");


#echo "$return_value<br><br>";

if($return_value == 1){
    echo "ERROR: The number of projects input doesn't equal to the number of projects in Sample Sheet! Please double check!<br><br>";
    shell_exec("sudo rm /var/www/html/upload/tmp/*");
    exit();
}
else
if($return_value == 2){
    echo "ERROR: Sample names should not include any sign like \"+\" in sample sheet, please correct it.";
    shell_exec("sudo rm /var/www/html/upload/tmp/*");
    exit(); 
}
else
if($return_value == 3){
    echo "ERROR1: Sample names should not include any sign like \"+\" in sample sheet, please correct it.<br>";
    echo "ERROR2: The number of projects input doesn't equal to the number of projects in Sample Sheet! Please double check!<br><br>";
    shell_exec("sudo rm /var/www/html/upload/tmp/*");
    exit();
}
if($return_value == 0)
{
    echo "Good! The number of projects input matches the sample sheet and sample names satisfy the requirement!<br><br>";
}

shell_exec("mv /var/www/html/upload/tmp/SampleSheet_modified.csv /var/www/html/upload/tmp/$sample_sheet");

$cmd="sudo bash /var/www/html/script/mv_NovaSeq.sh $sample_sheet $sequencing_id MiSeq";
passthru($cmd,$returnvalue);

if ($returnvalue != 0){
	echo "<br/><br/>";
	echo "Failed to save sample sheet in sequencing run folder and Sample_Sheet folder!<br/>";
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
	exit();
}else{
	echo "<br/><br/>";
         $to="yizhou.wang@cshs.org";
         #$to="genomics@cshs.org";
         $subject="(TEST, please ignore) NextSeq $sequencing_id Submission Summary";
      	 $a="Sequencing ID:         $sequencing_id\r\n\n";
 	 $b="Project ID:            ".implode(", ",$project_id)."\r\n\n";
         $c="PI name:               ".implode(", ", $pi_last)."\r\n\n";
         $d="Sequencing type:       ".implode(", ",$seq_type)."\r\n\n";
	 $f="Comments:               $comments\r\n\n";
         $myfile=fopen("/var/www/html/upload/tmp/samples_tmp_report.txt","r") or die("Unable to open file!");
	 $g=fread($myfile,filesize("/var/www/html/upload/tmp/samples_tmp_report.txt"));
	 $text="Fastq generation has been started for sequencing $sequencing_id!\r\n\n";
         $message=$text.$a.$b.$c.$d.$e.$f.$g;
	 $headers = "From: root";
         mail($to,$subject,$message,$headers);
         echo "Mail sent.<br/><br/>";
	 shell_exec("sudo rm /var/www/html/upload/tmp/*");

}

#echo "$seq_type[0]<br><br>";


if($fastq_ge == "Yes"){

                $log_name=$sequencing_id."_tmp.log";
                echo "Bcl2fastq pipeline starts for fastq generation!<br/>";
                shell_exec("sudo bash /home/genomics/bin/bcl2fastq_NovaSeq.sh MiSeq $sequencing_id  >> /var/www/html/log/$log_name &");
        
}





?>


