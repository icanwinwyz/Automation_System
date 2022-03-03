<?php
$sequencing_id=preg_replace('/\s+/', '',$_POST["run_ID"]);
$pi_last=preg_replace('/\s+/', '',$_POST['PI_Last']);
$pi_first=preg_replace('/\s+/', '',$_POST['PI_First']);
$project_id=preg_replace('/\s+/', '',$_POST['ProjectID']);
$seq_type=$_POST['Sequencing_Type'];
$org=$_POST['Organism'];
$fastq_ge=$_POST['fastq_ge'];
$arrlength=count($project_id);
$comments=$_POST["comments"];
if (empty($comments)){
        $comments = "No comments.";
}



for($x=0;$x<$arrlength;$x++){
	$text = $project_id[$x]." ".$project_id[$x]."_".$pi_last[$x]."_".$pi_first[$x]."_".$seq_type[$x]."_".$org[$x]."\n";
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
echo "Organism: ";
foreach($org as $value){
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
        echo '<span style="color:#F00;text-align:center;">ERROR: Please select a file before clicking submit button.</span>';
        shell_exec("sudo rm /var/www/html/upload/tmp/*");
	exit();
    }
else
if(!$fileSize > 16777215)//if file is > 16MB (Max size of MEDIUMBLOB)
    {
        echo '<span style="color:#F00;text-align:center;">ERROR: Your file was larger than 16 Megabytes.</span>';
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
        unlink($fileTmpLoc);//remove the uploaded file from the PHP folder
        exit();
    }
else
if(!preg_match("/\.(csv)$/i", $fileName))//this codition allows only the type of files listed to be uploaded
    {
        echo '<span style="color:#F00;text-align:center;">ERROR: Your file was not csv.</span>';
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
        unlink($fileTmpLoc);//remove the uploaded file from the PHP temp folder
        exit();
    }
else
if($fileErrorMsg == 1)//if file uploaded error key = 1 ie is true
    {
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
        echo '<span style="color:#F00;text-align:center;">ERROR: An error occured while processing the file. Please try again.</span>';
        exit();
     }
else
if(preg_match("/[\(\)\s]+/", $fileName))
    {
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
	echo '<span style="color:#F00;text-align:center;">ERROR: No space or parenthesis allowed in name of sample sheet. Please rename the sample sheet into "SampleSheet.csv" as example.<br><br></span>';
	exit();
}

$moveResult = move_uploaded_file($fileTmpLoc, $target_path);

if($moveResult != true)
    {
        echo '<span style="color:#F00;text-align:center;">ERROR: File not uploaded. Please Try again.</span>';
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
        unlink($fileTmpLoc);//remove the uploaded file from the PHP temp folder
	exit();
    }
    else
    {
        echo "The Sample Sheet was successfully uploaded to the server.<br><br>";
}


if($seq_type[0] == "scRNA_10X"){

	$return_value=shell_exec("sudo perl /var/www/html/script/samplesheet_format_verify.pl $target_path /var/www/html/upload/tmp/$filename NextSeq_10x");

}elseif($seq_type[0] == "miRNA" or $seq_type[0] == "Total_RNA" or $seq_type[0] == "ATAC" or $seq_type[0] == "mRNA" or $seq_type[0] == "scRNA"){

	$return_value=shell_exec("sudo perl /var/www/html/script/samplesheet_format_verify.pl $target_path /var/www/html/upload/tmp/$filename NextSeq_regular");

}


#echo "$return_value<br><br>";

if($return_value == 1){
    echo '<span style="color:#F00;text-align:center;">ERROR: The number of projects input doesnt equal to the number of projects in Sample Sheet! Please double check or check whether the project ID you input is exactly same as in Sample Sheet! Or please use the original Sample Sheet as input.<br><br></span>';
    echo '<span style="color:#F00;text-align:center;">If you have tried many times but still fail to make it work, please email yizhou.wang@cshs.org for technical suppot. <br><br></span>';
    shell_exec("sudo rm /var/www/html/upload/tmp/*");
    exit();
}
else
if($return_value == 2){
    echo '<span style="color:#F00;text-align:center;">ERROR: Invalid characters in samplename. Only alphanumeric, -, or _ characters are allowed.</span>';
    shell_exec("sudo rm /var/www/html/upload/tmp/*");
    exit(); 
}
else
if($return_value == 3){
    echo '<span style="color:#F00;text-align:center;">ERROR1: Invalid characters in samplename. Only alphanumeric, -, or _ characters are allowed.<br></span>';
    echo '<span style="color:#F00;text-align:center;">ERROR2: The number of projects input doesnt equal to the number of projects in Sample Sheet! Please double check!<br><br></span>';
    shell_exec("sudo rm /var/www/html/upload/tmp/*");
    exit();
}
if($return_value == 0)
{
    echo "Good! The number of projects input matches the sample sheet and sample names satisfy the requirement!<br><br>";
}

shell_exec("mv /var/www/html/upload/tmp/SampleSheet_modified.csv /var/www/html/upload/tmp/$sample_sheet");

$cmd="sudo bash /var/www/html/script/mv_NovaSeq.sh $sample_sheet $sequencing_id NextSeq";
passthru($cmd,$returnvalue);

if ($returnvalue != 0){
	echo "<br/><br/>";
	echo '<span style="color:#F00;text-align:center;">Failed to save sample sheet in sequencing run folder and Sample_Sheet folder!<br></span>';
	shell_exec("sudo rm /var/www/html/upload/tmp/*");
	exit();
}else{
	echo "<br/><br/>";
        #$to="yizhou.wang@cshs.org";
        $to="genomics@cshs.org";
         $subject="NextSeq $sequencing_id Submission Summary";
      	 $a="Sequencing ID:         $sequencing_id\r\n\n";
 	 $b="Project ID:            ".implode(", ",$project_id)."\r\n\n";
         $c="PI Last name:               ".implode(", ", $pi_last)."\r\n\n";
         $i="PI First name:               ".implode(", ", $pi_first)."\r\n\n";
	 $d="Sequencing type:       ".implode(", ",$seq_type)."\r\n\n";
         $e="Organism:              ".implode(", ",$org)."\r\n\n";
	 $f="Comments:               $comments\r\n\n";
         $h="Fastq Generation:          $fastq_ge\r\n\n";
	 $myfile=fopen("/var/www/html/upload/tmp/samples_tmp_report.txt","r") or die("Unable to open file!");
	 $g=fread($myfile,filesize("/var/www/html/upload/tmp/samples_tmp_report.txt"));
	 $text="Fastq generation has been started for sequencing $sequencing_id!\r\n\n";
         $message=$text.$a.$b.$c.$i.$d.$e.$f.$h.$g;
	 $headers = "From: root";
         mail($to,$subject,$message,$headers);
         echo "Mail sent.<br/><br/>";
	 shell_exec("sudo rm /var/www/html/upload/tmp/*");

}

#echo "$seq_type[0]<br><br>";
if($fastq_ge == "Yes"){

      if($seq_type[0] == "scRNA_10X"){
             $log_name=$sequencing_id."_tmp.log";
             echo "the sequencing type is $seq_type[0], CellRanger pipeline starts for fastq generation!<br/>";
             shell_exec("sudo bash /home/genomics/bin/cellranger_NovaSeq.sh NextSeq $sequencing_id >> /var/www/html/log/$log_name &");
#       echo ("sudo bash /home/genomics/bin/cellranger.sh $folder_name $sequencing_id");
        }elseif($seq_type[0] == "miRNA" or $seq_type[0] == "Total_RNA" or $seq_type[0] == "mRNA" or $seq_type[0] == "scRNA" or $seq_type[0] == "ATAC"){
                $log_name=$sequencing_id."_tmp.log";
                echo "the sequencing type is ".implode(", ",$seq_type)." bcl2fastq pipeline starts for fastq generation!<br/>";
                shell_exec("sudo bash /home/genomics/bin/bcl2fastq_NovaSeq.sh NextSeq $sequencing_id  >> /var/www/html/log/$log_name &");
        }else{
	echo '<span style="color:#F00;text-align:center;">Demultiplexing only works for 10X scRNA, Total RNA, mRNA, scRNA and Sequencing Only. For others, you can only upload the sample sheet. Please double check the sequencing type.<br></span>';
	}
}



?>


