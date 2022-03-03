<html>
<body>
<form method="post" action="file_uploader_Miseq.php" enctype="multipart/form-data"> 	
	Sequencing Run ID: <input type="text" name="run_ID"/><img src="/pic/1506480352523.gif" align="right" width="700" height="550"><br><br>
	PI name:   Last name: <input type="text" name="Lastname" />  First name: <input type="text" name="Firstname"/><br><br>
	Sequencing Type:<br>
        <input type="radio" name="sequencing_type" value="16S">16S<br>
        <input type="radio" name="sequencing_type" value="ITS">ITS<br>
        <input type="radio" name="sequencing_type" value="both">Both<br>
        <input type="radio" name="sequencing_type" value="Others">Others<br>
	<br><br>
        Fastq Genertion: <input type="radio" name="fastq_ge" value="Yes">Yes <input type="radio" name="fastq_ge" value="No" checked />No<br><br>
	Sample Sheet(.csv):<br/>
	<input name="uploaded_file" type="file"/><br/>
	<br><br>
        <input type="submit" value="Submit"/>
	</form>
  
</body>
</html>

