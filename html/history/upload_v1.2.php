<html>
<body>
<?php
$sequencing_id = $project_id = $Lastname = $Firstname = $org = $org_other= $piname = $seq_type = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $sequencing_id = test_input($_POST["run_ID"]);
     $project_id = test_input($_POST["project_id"]);
     $Lastname = test_input($_POST["Lastname"]);
     $Firstname = test_input($_POST["Firstname"]);
     $org = test_input($_POST["org"]);
     $org_other = test_input($_POST["org_other"]);
     $piname=test_input($_POST["Lastname"])."_".test_input($_POST["Firstname"]);
     $seq_type=test_input($_POST["sequencing_type"]);
}

function test_input($data) {
   $data = trim($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data"> 	
	Sequencing Run ID: <input type="text" name="run_ID" value="<?php echo $sequencing_id; ?>"/><img src="/pic/1506480352523.gif" align="right" width="700" height="550"><br><br>
	Project ID: <input type="text" name="project_id" value="<?php echo $project_id;?>" /><br><br>
	PI name:   Last name: <input type="text" name="Lastname" value="<?php echo $Lastname; ?>"/>  First name: <input type="text" name="Firstname" value="<?php echo $Firstname; ?>"><br><br>
	Sequencing Type:
<select name="sequencing_type">
	<option value="scRNA_10X" selected <?php if ($_POST["sequencing_type"]=="scRNA_10X") {echo "selected='selected'"; } ?>>10X scRNA<br>
   	<option value="V(D)J_RNA_10X" <?php if ($_POST["sequencing_type"]=="V(D)J_RNA_10X") {echo "selected='selected'"; } ?>>10X V(D)JRNA<br>
        <option value="total_RNA" <?php if ($_POST["sequencing_type"]=="total_RNA") {echo "selected='selected'"; } ?>>total RNA<br>
        <option value="mRNA" <?php if ($_POST["sequencing_type"]=="mRNA") {echo "selected='selected'"; } ?>>mRNA<br>
        <option value="scRNA" <?php if ($_POST["sequencing_type"]=="scRNA") {echo "selected='selected'"; } ?>>scRNA<br>
        <option value="miRNA" <?php if ($_POST["sequencing_type"]=="miRNA") {echo "selected='selected'"; } ?>>miRNA<br>
        <option value="sequencing_only" <?php if ($_POST["sequencing_type"]=="sequencing_only") {echo "selected='selected'"; } ?>>Sequencing Only<br>
</select>
	<br><br>
	Organism:<br>	      
        <input type="radio" name="org" <?php if (isset($org) && $org=="Human") echo "checked";?> value="Human">Human<br>
        <input type="radio" name="org" <?php if (isset($org) && $org=="Mouse") echo "checked";?> value="Mouse">Mouse<br>
        <input type="radio" name="org" <?php if (isset($org) && $org=="Others") echo "checked";?> value="Others">Others <input type="text" name="org_other" value="<?php echo $org_other; ?>"/><br><br>
	<input type="submit" value="Verify">
	<h3>Your Input</h3>
	<span class="error">*(please verify the samplesheet carefully before the submission!!)</span>
<style>	
.selection {
margin: 5px;
padding: 15px;
width: 400px;
height: 100px;
border: 1px solid black;
}
.error {color: #FF0000;}
</style>
	<div class="selection">
	<?php
	echo "Sequencing ID:  <strong>$sequencing_id </strong>";
	echo "<br>";
	echo "Project ID:  <strong>$project_id</strong>";
	echo "<br>";
	echo "PI name: <strong>$piname</strong>";
	echo "<br>";
	echo "Sequencing Type:  <strong>$seq_type</strong>";
	echo "<br>";
	if($org == "Others"){
	echo "Organism: <strong>$org_other</strong>";
	echo "<br>";
	}else{
	echo "Organism: <strong>$org</strong>";
        echo "<br>";
	}
	?>
	</div>
 
	<br><br>
	Fastq Genertion: <input type="radio" name="fastq_ge" value="Yes">Yes <input type="radio" name="fastq_ge" value="No" checked />No<br><br> 
	Sample Sheet(.csv):<br/>
	replace?  
	<input type="radio" name="replace" value="Yes" />Yes
	<input type="radio" name="replace" value="No" checked/>NO<br/>
	<input name="uploaded_file" type="file"/><br/>
	<br><br>
    	<button type="submit" formaction="file_uploader.php">Submit</button>
    </form>
  
</body>
</html>

