<?php
$path_fastq=preg_replace('/\s+/', '',$_POST["path_fastq"]);
$sample=preg_replace('/\s+/', '',$_POST['sample']);
#$sample_path=preg_replace('/\s+/', '',$_POST['sample_path']);
$arrlength=count($sample);
$reanalyze=$_POST['reanalyze'];
$server=$_POST['server'];
$run_id=$_POST['run_id'];

if (empty($comments)){
        $comments = "No comments.";
}

$a="";
if ($reanalyze == "Yes"){
	if ($server == "titan"){
		shell_exec("sudo bash /var/www/html/script/cellranger_aggr_header.sh $path_fastq $run_id");
		for($x=0;$x<$arrlength;$x++){
		passthru("sudo bash /var/www/html/script/cellranger_aggr_add.sh $path_fastq $sample[$x] $run_id");##this is to generate the aggregation csv file
		$a=$a." ".$sample[$x]."_barcode_filter.csv ";##this is to generate the list for barcodes of samples
		}
		shell_exec("sudo Rscript /var/www/html/script/aggre_reanalyze_barcodes.R $a $path_fastq $run_id");
		shell_exec("sudo bash /var/www/html/script/cellranger_aggre_run.sh $run_id Yes $path_fastq titan");
		echo "<br>";
		echo "Samples: ";
		foreach($sample as $value){
			echo "$value, ";
		}
		echo "<br>";
		echo "Run ID: $run_id";
		echo "<br>";
		echo "Path to the files: $path_fastq<br><br><br>";
		echo "<br>";
		}
	else
	if ($server == "csclprd1"){
		shell_exec("sudo bash /var/www/html/script/cellranger_aggr_header.sh $path_fastq $run_id");
		for($x=0;$x<$arrlength;$x++){
		passthru("sudo bash /var/www/html/script/cellranger_aggr_add.sh $path_fastq $sample[$x] $run_id");##this is to generate the aggregation csv file
		$a=$a." ".$sample[$x]."_barcode_filter.csv ";##this is to generate the list for barcodes of samples
		}
		shell_exec("sudo Rscript /var/www/html/script/aggre_reanalyze_barcodes.R $a $path_fastq $run_id");
		shell_exec("sudo bash /var/www/html/script/cellranger_aggre_run.sh $run_id Yes $path_fastq csclprd1");
		echo "<br>";
		echo "Samples: ";
		foreach($sample as $value){
			echo "$value, ";
		}
		echo "<br>";
		echo "Run ID: $run_id";
		echo "<br>";
		echo "Path to the files: $path_fastq<br><br><br>";
		echo "<br>";
	}
}
else
if ($reanalyze == "No"){
	if ($server == "titan"){
		shell_exec("sudo bash /var/www/html/script/cellranger_aggr_header.sh $path_fastq $run_id");
		for($x=0;$x<$arrlength;$x++){
		passthru("sudo bash /var/www/html/script/cellranger_aggr_add.sh $path_fastq $sample[$x] $run_id");##this is to generate the aggregation csv file
		$a=$a." ".$sample[$x]."_barcode_filter.csv ";##this is to generate the list for barcodes of samples
		}
		shell_exec("sudo Rscript /var/www/html/script/aggre_reanalyze_barcodes.R $a $path_fastq $run_id");
		shell_exec("sudo bash /var/www/html/script/cellranger_aggre_run.sh $run_id No $path_fastq titan");
		echo "<br>";
		echo "Samples: ";
		foreach($sample as $value){
			echo "$value, ";
		}
		echo "<br>";
		echo "Run ID: $run_id";
		echo "<br>";
		echo "Path to the files: $path_fastq<br><br><br>";
		echo "<br>";
	}
	else
	if ($server == "csclprd1"){
		shell_exec("sudo bash /var/www/html/script/cellranger_aggr_header.sh $path_fastq $run_id");
		for($x=0;$x<$arrlength;$x++){
		passthru("sudo bash /var/www/html/script/cellranger_aggr_add.sh $path_fastq $sample[$x] $run_id");##this is to generate the aggregation csv file
		$a=$a." ".$sample[$x]."_barcode_filter.csv ";##this is to generate the list for barcodes of samples
		}
		shell_exec("sudo Rscript /var/www/html/script/aggre_reanalyze_barcodes.R $a $path_fastq $run_id");
		shell_exec("sudo bash /var/www/html/script/cellranger_aggre_run.sh $run_id No $path_fastq csclprd1");
		echo "<br>";
		echo "Samples: ";
		foreach($sample as $value){
			echo "$value, ";
		}
		echo "<br>";
		echo "Run ID: $run_id";
		echo "<br>";
		echo "Path to the files: $path_fastq<br><br><br>";
		echo "<br>";
	}

}




?>
