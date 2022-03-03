<?php

$sequencing_id=trim($_POST["run_ID"]);
$project_id=trim($_POST["project_id"]);
$piname_last=$_POST["Lastname"];
$piname_first=$_POST["Firstname"];
$piname=trim($piname_last)."_".trim($piname_first);
$seq_type=$_POST["sequencing_type"];
$organism = $_POST["org"];
$replace = $_POST["replace"];
if($organism == "Others"){
	$organism=trim($_POST["org_other"]);
}
$fastq_gener=$_POST["fastq_ge"];

$sample_sheet=$sequencing_id."_".$project_id."_".$piname."_"."$seq_type".".csv";
$folder_name=$project_id."_".$piname."_"."$seq_type";
echo "Sequencing ID:         <strong> $sequencing_id</strong><br/>";
echo "Project ID:         <strong> $project_id</strong><br/>";
echo "PI name:         <strong>$piname</strong><br/>";
echo "Sequencing type:         <strong>$seq_type</strong><br/>";
echo "Organism:      <strong>$organism</strong><br/>";
echo "Fastq generation:      <strong>$fastq_gener</strong><br/>";
echo "<br/><br/>";

$fileName = $_FILES["uploaded_file"]["name"];//the files name takes from the HTML form
$fileTmpLoc = $_FILES["uploaded_file"]["tmp_name"];//file in the PHP tmp folder
$fileType = $_FILES["uploaded_file"]["type"];//the type of file
$fileSize = $_FILES["uploaded_file"]["size"];//
$fileErrorMsg = $_FILES["uploaded_file"]["error"];//0 for false and 1 for true
#$filesave="/var/www/html/upload/final/"

$target_path = "/var/www/html/upload/final/" . basename( $_FILES["uploaded_file"]["name"]);
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
            if(!preg_match("/\.(csv)$/i", $fileName))//this codition allows only the type of files listed to be uploaded
            {
                echo "ERROR: Your file was not csv";
                unlink($fileTmpLoc);//remove the uploaded file from the PHP temp folder
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
	echo "Sample sheet is renamed in: <br/>";
	echo "<strong>$sample_sheet</strong><br/><br/>";
        //Display to the page so you see what is happening 
#        echo "The file named <strong>$fileName</strong> uploaded successfully.<br/><br/>";
 #       echo "It is <strong>$fileSize</strong> bytes.<br/><br/>";
  #      echo "It is a <strong>$fileType</strong> type of file.<br/><br/>";
   #     echo "The Error Message output for this upload is: $fileErrorMsg";
    }

if ($replace == "Yes"){
	shell_exec("sudo perl /var/www/html/script/edit.pl -f $target_path -s $sequencing_id -p $project_id -n $piname -t $seq_type -o $organism -re");
	$cmd_delete="sudo rm /mnt/genomics-archive/NextSeq500_RawData/SampleSheets/$sequencing_id*";
#	echo "$cmd_delete<br/>";
	passthru($cmd_delete,$returnvalue_replace1);
	if($returnvalue_replace1 !=0){
		echo "Failed to delete old SampleSheet in /home/genomics/genomics-archive/NextSeq500_RawData/SampleSheets/ or no SampleSheet for $sequencing_id found, please check!";
	}else{
		echo "Succeeded in replacing SampleSheet of $sequencing_id in /home/genomics/genomics-archive/NextSeq500_RawData/SampleSheets/ and record of $sequencing_id in Sequencing_org.txt";
	}
}else{
	passthru("sudo perl /var/www/html/script/edit.pl -f $target_path -s $sequencing_id -p $project_id -n $piname -t $seq_type -o $organism",$returnvalue_replace2);
	if($returnvalue_replace2 !=0){
		echo "Failed to add record of $sequencing_id in Sequencing_org.txt,please check!";
	}else{
		echo "Succeeded in adding record of $sequencing_id in Sequencing_org.txt!";
	}
}

$cmd="sudo bash /var/www/html/script/mv.sh $sample_sheet $sequencing_id";

shell_exec("mv $target_path /var/www/html/upload/final/$sample_sheet");
passthru($cmd,$returnvalue);

if ($returnvalue != 0){
echo "<br/><br/>";
echo "Failed to save sample sheet in sequencing run folder and Sample_Sheet folder!<br/>";
}else{
echo "<br/><br/>";
echo "Sample sheet was succefully saved in sequencing run folder and Sample_Sheet folder!<br/>";
echo "<br/><br/>";
$to="yizhou.wang@cshs.org";
#$to="genomics@cshs.org";
$subject="$sequencing_id Summary";
$a="Sequencing ID:         $sequencing_id\r\n\n";
$b="Project ID:          $project_id\r\n\n";
$c="PI name:         $piname\r\n\n";
$d="Sequencing type:         $seq_type\r\n\n";
$e="Organism:      $organism\r\n\n";
if($fastq_gener == "Yes"){
	$f= "Fastq generation:   $fastq_gener and fastq files are saved at:\r\n ~/genomics/data/Temp/Sequence_Temp/$folder_name\r\n\n";
}else{
	$f= "Fastq generation:   $fastq_gener\r\n\n";
}
$text="Sequencing $sequencing_id is ready for processing!\r\n\n";
$message=$text.$a.$b.$c.$d.$e.$f;

$headers = "From: root";
mail($to,$subject,$message,$headers);
echo "Mail sent.<br/><br/>";
}

if($fastq_gener == "Yes"){

	if($seq_type == "scRNA_10X"){
		$log_name=$folder_name.$sequencing_id."_tmp.log";
		echo "the sequencing type is $seq_type, CellRanger pipeline would be started for fastq generation!<br/>";
		shell_exec("sudo bash /home/genomics/bin/cellranger.sh $folder_name $sequencing_id >> /var/www/html/log/$log_name &");
#	echo ("sudo bash /home/genomics/bin/cellranger.sh $folder_name $sequencing_id");
	}elseif($seq_type == "total_RNA" or $seq_type == "mRNA" or $seq_type == "scRNA"){
		echo "the sequencing type is $seq_type, bcl2fastq pipeline would be started for fastq generation!<br/>";
		shell_exec("sudo bash /home/genomics/bin/bcl2fastq.sh $sequencing_id $folder_name >> /var/www/html/log/$log_name &");	
	}
}






?>



