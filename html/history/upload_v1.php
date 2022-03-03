<html>
  <body>
  <form action="file_uploader.php" method="post" enctype="multipart/form-data">
	Sequencing Run ID: <input type="text" name="run_ID" /><br><br>
	Project ID: <input type="text" name="project_id" /><br><br>
	PI name: <input type="text" name="PI" /><br><br>
	Sequencing Type:<br>
	<input type="radio" name="sequencing_type" value="10X_scRNA">10X scRNA<br>
	<input type="radio" name="sequencing_type" value="10X_V(D)J_RNA">10X V(D)JRNA<br>
	<input type="radio" name="sequencing_type" value="total_RNA">total RNA<br>
	<input type="radio" name="sequencing_type" value="mRNA">mRNA<br>
	<input type="radio" name="sequencing_type" value="scRNA">scRNA<br>
	<input type="radio" name="sequencing_type" value="miRNA">miRNA<br>
	<br><br>
	Organism:<br>	      
	<input type="radio" name="org" value="Human">Human<br>
        <input type="radio" name="org" value="Mouse">Mouse<br>
        <input type="radio" name="org" value="Others">Others<br>
	<br><br>
	Sample Sheet(.csv):
    	<input name="uploaded_file" type="file"/><br/>
	<br><br>
    	<input type="submit" value="Submit"/>
    </form>
  
</body>
</html>

