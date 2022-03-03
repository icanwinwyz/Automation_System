<html>
<body>
<?php
$sequencing_id = $seq_num= $project_id = $Lastname = $Firstname = $org = $org_other= $piname = $seq_type = $sequencing_type= "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $sequencing_id = test_input($_POST["run_ID"]);
     $project_id = test_input($_POST["project_id"]);
     $Lastname = test_input($_POST["Lastname"]);
     $Firstname = test_input($_POST["Firstname"]);
     $piname=test_input($_POST["Lastname"])."_".test_input($_POST["Firstname"]);
     $sequencing_type=test_input($_POST["sequencing_type"]);
     $seq_num=test_input($_POST["seq_num"]);
}

function test_input($data) {
   $data = trim($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">

<!--<form method="post" action="file_uploader_Miseq_test.php" enctype="multipart/form-data"> -->	
	Sequencing Run ID: <input type="text" name="run_ID" value="<?php echo $sequencing_id; ?>"/><img src="/pic/1506480352523.gif" align="right" width="700" height="550"><br><br> 
	Number of projects in this run? <input type="radio" name="seq_num" <?php if (isset($seq_num) && $seq_num=="single") echo "checked";?> value="single" checked />Single <input type="radio" name="seq_num" <?php if (isset($seq_num) && $seq_num=="multiple") echo "checked";?> value="multiple" />Multiple<br>
	<span class="warn">(If "single", please fill the information between (---), if "multiple", please ignore.)</span><br><br>
	-----------------------------------------------------------------------------------------<br><br>
	PI name:   Last name: <input type="text" name="Lastname" value="<?php echo $Lastname; ?>" />  First name: <input type="text" name="Firstname" value="<?php echo $Firstname; ?>"/><br><br>
	Project ID: <input type="text" name="project_id" value="<?php echo $project_id;?>" /><br><br>
	Sequencing Type (Primer USE):<br>
        <input type="radio" name="sequencing_type" <?php if (isset($sequencing_type) && $sequencing_type=="16S") echo "checked";?> value="16S">16S<br>
        <input type="radio" name="sequencing_type" <?php if (isset($sequencing_type) && $sequencing_type=="archaea") echo "checked";?> value="archaea">Archaea<br>
        <input type="radio" name="sequencing_type" <?php if (isset($sequencing_type) && $sequencing_type=="prokaryote") echo "checked";?> value="prokaryote">Prokaryote<br>
        <input type="radio" name="sequencing_type" <?php if (isset($sequencing_type) && $sequencing_type=="bacteria") echo "checked";?> value="bacteria">Bacteria<br>
        <input type="radio" name="sequencing_type" <?php if (isset($sequencing_type) && $sequencing_type=="both") echo "checked";?> value="both">Both(16S,ITS)<br>
	<br>
	<input type="submit" value="Concatenate"><br><br>
	Used for fill "Sample_Project" in Sample Sheet (optional)<br>
     <!--   <span class="error1"></span> -->
<style>
.selection {
margin: 5px;
padding: 15px;
width: 400px;
height: 10px;
border: 1px solid black;
}
.error1 {color: #FF0000;}
.warn {color:#FF0000;}
</style>
<div class="selection">
        <?php
	$array = array($project_id,$Lastname,$Firstname,$sequencing_type);
	echo join("_",$array);
	
        ?>
        </div>
    	<br>
	-----------------------------------------------------------------------------------------<br><br>
	Fastq Genertion: <input type="radio" name="fastq_ge" value="Yes">Yes <input type="radio" name="fastq_ge" value="No" checked />No<br><br>
	
	Comments:<br>
	<textarea cols="35" rows="6" name="comments">
	</textarea><br><br>

	Sample Sheet(.csv):<br/>
	<input name="uploaded_file" type="file"/><br/>
	<br><br>
<!--        <input type="submit" value="Submit"/> -->
	

	<button type="submit" formaction="file_uploader_Miseq.php">Submit</button>
	 <br><br><br><br><br><br>
	##################################################################<br>	
	</form>


<h3>Guidance</h3>
<p>1. Make sure the column "Sample Project" in sample sheet is formatted as "&lt;projectID&gt;_&lt;PIlastname&gt;_&lt;PIfirstname&gt;_&lt;sequencingtype&gt;";<p>
<p>&nbsp;&nbsp;&nbsp;&nbsp; - You can use "Concatenate" function to concatenate the information and copy and paste it to the column "Sample Project" in sample sheet;<p>
<p>2. If there is only one project in the sequencing run, just fill the information and click "submit".</p> 
<p>&nbsp;&nbsp;&nbsp;&nbsp; - One copy of samplesheet will be renamed into "SampleSheet.csv" and uploaded to sequencing run folder to initialize the Fastq generation (bcl2fastq);</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp; - One copy of samplesheet will be renamed into "&lt;sequencingID&gt;_&lt;projectID&gt;_&lt;PIname_&lt;sequencingtype&gt;.csv" and uploaded to sample sheets back up folder;</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp; - Two emails are expected: one is to inform that sample sheet has been uploaded successfully to the sequencing run folder and sample sheets back up folder; the other one is to inform that Fastq generation is done and the summary of sequencing.</p>
<p>3. If there are multiple projects in the sequencing run, please ignore the information between (----), just fill the "sequencin id", upload the sample sheet with correct format, select "fastq generation" as "Yes" and click "Submit".</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp; - Sample sheet will be renamed into "SampleSheet.csv" and only uploaded to the sequencing run folder to initialize the Fastq generation;</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp; - Please create the individual sample sheet for each project in the sequencing run and upload them one by one with "Fastq generation " set as "NO";</p>
<p>4. For the control sample, please name it as "Temp_Genomics_Core_Control" in the "Sample Project" column in the sample sheet.</p>

</body>
</html>

