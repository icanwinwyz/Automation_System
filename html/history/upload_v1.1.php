<html>
  <body>
  <form action="file_uploader.php" method="post" enctype="multipart/form-data">
	Sequencing Run ID: <input type="text" name="run_ID" /><br><br>
	Project ID: <input type="text" name="project_id" /><br><br>
	PI name:   Last name: <input type="text" name="Lastname" />  First name: <input type="text" name="Firstname"><br><br>
	Sequencing Type:<br>
	<input type="radio" name="sequencing_type" value="scRNA_10X">10X scRNA<br>
	<input type="radio" name="sequencing_type" value="V(D)J_RNA_10X">10X V(D)JRNA<br>
	<input type="radio" name="sequencing_type" value="total_RNA">total RNA<br>
	<input type="radio" name="sequencing_type" value="mRNA">mRNA<br>
	<input type="radio" name="sequencing_type" value="scRNA">scRNA<br>
	<input type="radio" name="sequencing_type" value="miRNA">miRNA<br>
	<input type="radio" name="sequencing_type" value="sequencing_only">Sequencing Only<br>
	<br>
	Organism:<br>	      
	<input type="radio" name="org" value="Human">Human<br>
        <input type="radio" name="org" value="Mouse">Mouse<br>
        <input type="radio" name="org" value="Others">Others <input type="text" name="org_other" /><br>
	<br>
	Fastq Genertion: <input type="radio" name="fastq_ge" value="Yes">Yes <input type="radio" name="fastq_ge" value="No" checked />No<br><br> 
	Sample Sheet(.csv):<br/>
	replace?  
	<input type="radio" name="replace" value="Yes" />Yes
	<input type="radio" name="replace" value="No" checked/>NO<br/>
	<input name="uploaded_file" type="file"/><br/>
	<br><br>
    	<input type="submit" value="Submit"/>
    </form>
  
</body>
</html>

