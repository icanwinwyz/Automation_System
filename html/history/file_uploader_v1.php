<?php

$sequencing_id=$_POST["run_ID"];
$project_id=$_POST["project_id"];
$piname=$_POST["PI"];
$seq_type=$_POST["sequencing_type"];
$organism = $_POST["org"];
$sample_sheet=$sequencing_id."_".$project_id."_".$piname."_"."$seq_type".".csv";
$folder_name=$project_id."_".$piname."_"."$seq_type";
echo "Sequencing ID:         <strong> $sequencing_id</strong><br/>";
echo "Project ID:         <strong> $project_id</strong><br/>";
echo "PI name:         <strong>$piname</strong><br/>";
echo "Sequencing type:         <strong>$seq_type</strong><br/>";
echo "Organism:      <strong>$organism</strong><br/>";
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
	echo "<strong>$sample_sheet</strong><br/>";
        //Display to the page so you see what is happening 
#        echo "The file named <strong>$fileName</strong> uploaded successfully.<br/><br/>";
 #       echo "It is <strong>$fileSize</strong> bytes.<br/><br/>";
  #      echo "It is a <strong>$fileType</strong> type of file.<br/><br/>";
   #     echo "The Error Message output for this upload is: $fileErrorMsg";
    }
$cmd="sudo bash /var/www/html/script/mv.sh $sample_sheet $sequencing_id";

#echo "$cmd<br/>";

shell_exec("mv $target_path /var/www/html/upload/final/$sample_sheet");
#shell_exec("sudo bash test.sh");
passthru($cmd,$returnvalue);
#shell_exec($cmd);

if ($returnvalue != 0){
    //we have a problem!
    //add error code here
#echo $returnvalue;
echo "<br/><br/>";
echo "Failed to save sample sheet in sequencing run folder and Sample_Sheet folder!<br/>";
}else{
    //we are okay
    //redirect to some other page
#echo $returnvalue;
echo "<br/><br/>";
echo "Sample sheet was succefully saved in sequencing run folder and Sample_Sheet folder!<br/>";
}
#shell_exec("sudo bash /home/genomics/bin/bcl2fastq.sh $sequencing_id $folder_name >> /var/www/html/tmp.log &");




?>



