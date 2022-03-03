<?php
$path_fastq=preg_replace('/\s+/', '',$_POST["path_fastq"]);
$sample=preg_replace('/\s+/', '',$_POST['sample']);
$fastq_folder=preg_replace('/\s+/', '',$_POST['fastq_folder']);
$org=$_POST['Organism'];
$server=$_POST['server'];
$comments=$_POST["comments"];
$arrlength=count($sample);


if (empty($comments)){
        $comments = "No comments.";
}

if($server == "titan"){

	for($x=0;$x<$arrlength;$x++){
		$cmd="sudo bash /var/www/html/script/cellranger_generate.sh ".$sample[$x]." ".$fastq_folder[$x]." ".$org[$x]." ".$path_fastq." ".$server ;
		echo "$cmd <br><br>";
		shell_exec($cmd);
	}


}elseif($server == "csclprd1"){
	
	for($x=0;$x<$arrlength;$x++){
		$cmd="sudo bash /var/www/html/script/cellranger_generate.sh ".$sample[$x]." ".$fastq_folder[$x]." ".$org[$x]." ".$path_fastq." ".$server;
		echo "$cmd <br><br>";
		shell_exec($cmd);
	}
}

echo "<br>";
echo "Samples: ";
foreach($sample as $value){
	echo "$value, ";
}
echo "<br>";
echo "Fastq folder :";
foreach($fastq_folder as $value){
	echo "$value, ";
}
echo "<br>";
echo "Organism: ";
foreach($org as $value){
	echo "$value, ";
}
echo "<br>";
echo "Path to the files: $path_fastq<br><br><br>";
echo "<br>";
echo "The server is : $server<br><br><br>";

