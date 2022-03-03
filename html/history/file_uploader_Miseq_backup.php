<?php

$sequencing_id=preg_replace('/\s+/', '',$_POST["run_ID"]);
$piname_last=preg_replace('/\s+/', '',$_POST["Lastname"]);
$piname_first=preg_replace('/\s+/', '',$_POST["Firstname"]);
$piname=$piname_last."_".$piname_first;
$project_id = preg_replace('/\s+/', '',$_POST["project_id"]);
$seq_type=preg_replace('/\s+/', '',$_POST["sequencing_type"]);
$seq_num=$_POST["seq_num"];
$comments=$_POST["comments"];
if (empty($comments)){
	$comments = "No comments.";
}
$sample_sheet=$sequencing_id."_".$project_id."_".$piname."_"."$seq_type".".csv";
echo "<br/><br/>";

$fastq_gener=$_POST["fastq_ge"];


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
  //  else
  #  {
#	echo "Sample sheet is renamed in: <br/>";
#	echo "<strong>$sample_sheet</strong><br/><br/>";
        //Display to the page so you see what is happening 
#        echo "The file named <strong>$fileName</strong> uploaded successfully.<br/><br/>";
 #       echo "It is <strong>$fileSize</strong> bytes.<br/><br/>";
  #      echo "It is a <strong>$fileType</strong> type of file.<br/><br/>";
   #     echo "The Error Message output for this upload is: $fileErrorMsg";
 #   }


#echo "$target_path<br/>";
#echo "$fileName<br/>";

if($fastq_gener == "Yes"){
	if ($seq_num == "single"){
		$comments_file_name = "comments"."_$sequencing_id".".txt";
		shell_exec("echo $comments > /var/www/html/upload/final/$comments_file_name");
		shell_exec("mv $target_path /var/www/html/upload/final/$sample_sheet");
		$cmd="sudo bash /var/www/html/script/mv_Miseq_upload.sh $sample_sheet $sequencing_id $seq_num";
		passthru($cmd,$returnvalue);

		if ($returnvalue != 0){
		echo "<br/><br/>";
		echo "Failed to upload sample sheet in sequencing run $sequencing_id folder and Sample Sheet back up folder!<br/>";
		}else{
		echo "Sequencing ID:         <strong> $sequencing_id</strong><br/>";
		echo "Project ID:         <strong> $project_id</strong><br/>";
		echo "PI name:         <strong>$piname</strong><br/>";
		echo "Sequencing type:         <strong>$seq_type</strong><br/>";
		echo "Comments:       <strong>$comments<strong><br/>";
		echo "<br/><br/>";
		echo "<br/><br/>";
		echo "Sample sheet was renamed in $sample_sheet and succefully uploaded to sequencing run $sequencing_id folder and Sample Sheet back up folder!<br/>";
		echo "<br/><br/>";
		#$to="yizhou.wang@cshs.org";
		$to="genomics@cshs.org";
		$subject="$sequencing_id Summary";
		$a="Sequencing ID:         $sequencing_id\r\n\n";
		$b="Project ID:      $project_id\r\n\n";
		$c="PI name:         $piname\r\n\n";
		$d="Sequencing type:         $seq_type\r\n\n";
		$e="Comments:     $comments\r\n\n";
		$text="Fastq generation has been started for sequencing $sequencing_id!\r\n\n";
		$message=$text.$a.$b.$c.$d.$e;
#	$message=$text.$a;

		$headers = "From: root";
		mail($to,$subject,$message,$headers);
		echo "Mail sent.<br/><br/>";
		}
	
		$log_name=$sequencing_id."_tmp.log";
		shell_exec("sudo bash /home/genomics/bin/bcl2fastq_Miseq.sh $sequencing_id $project_id $piname $seq_type $seq_num >> /var/www/html/log/$log_name &");
	}elseif ($seq_num == "multiple"){
		$comments_file_name = "comments"."_$sequencing_id".".txt";
		shell_exec("echo $comments > /var/www/html/upload/final/$comments_file_name");
		shell_exec("mv $target_path /var/www/html/upload/final/SampleSheet.csv");
		$cmd="sudo bash /var/www/html/script/mv_Miseq_upload.sh SampleSheet.csv $sequencing_id $seq_num";
		passthru($cmd,$returnvalue);

		if ($returnvalue != 0){
		echo "<br/><br/>";
		echo "Failed to upload sample sheet in sequencing run $sequencing_id folder!<br/>";
		}else{
		$project_id = "Multiple";
		$piname = "Multipe";
		$seq_type = "Multiple";
		echo "Sequencing ID:         <strong> $sequencing_id</strong><br/>";
		echo "Project ID:         <strong>$project_id</strong><br/>";
		echo "PI name:         <strong>$piname</strong><br/>";
		echo "Sequencing type:         <strong>$seq_type</strong><br/>";
		echo "Comments:       <strong>$comments<strong><br/>";
		echo "<br/><br/>";
		echo "<br/><br/>";
		echo "Sample sheet was succefully uploaded to sequencing run $sequencing_id folder!<br/>";
		echo "<br/><br/>";
		#$to="yizhou.wang@cshs.org";
		$to="genomics@cshs.org";
		$subject="$sequencing_id Summary";
		$a="Sequencing ID:         $sequencing_id\r\n\n";
		$b="Project ID:     $project_id\r\n\n";
		$c="PI name:         $piname\r\n\n";
		$d="Sequencing type:         $seq_type\r\n\n";
		$e="Comments:     $comments\r\n\n";
		$text="Fastq generation has been started for sequencing $sequencing_id!\r\n\n";
		$message=$text.$a.$b.$c.$d.$e;
#	$message=$text.$a;

		$headers = "From: root";
		mail($to,$subject,$message,$headers);
		echo "Mail sent.<br/><br/>";
		}
	
		$log_name=$sequencing_id."_tmp.log";
		shell_exec("sudo bash /home/genomics/bin/bcl2fastq_Miseq.sh $sequencing_id $project_id $piname $seq_type $seq_num >> /var/www/html/log/$log_name &");
	}
}else{		
		shell_exec("mv $target_path /var/www/html/upload/final/$sample_sheet");
		$cmd="sudo bash /var/www/html/script/mv_Miseq_upload.sh $sample_sheet $sequencing_id upload_only";
		passthru($cmd,$returnvalue);

		if ($returnvalue != 0){
		echo "<br/><br/>";
		echo "Failed to upload sample sheet in Sample Sheet back up folder!<br/>";
		}else{
		echo "Sequencing ID:         <strong> $sequencing_id</strong><br/>";
		echo "Project ID:         <strong>$project_id</strong><br/>";
		echo "PI name:         <strong>$piname</strong><br/>";
		echo "Sequencing type:         <strong>$seq_type</strong><br/>";
		echo "Comments:       <strong>$comments<strong><br/>";
		echo "<br/><br/>";
		echo "Sample sheet was renamed in $sample_sheet and succefully uploaded to Sample Sheet back up folder folder!<br/>";
		echo "<br/><br/>";
		}
	}
	
