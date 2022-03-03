<?php
$path_fastq=preg_replace('/\s+/', '',$_POST["path_fastq"]);
$sample=preg_replace('/\s+/', '',$_POST['sample']);
$arrlength=count($sample);
$task=$_POST["task"];

if (empty($comments)){
        $comments = "No comments.";
}


if ($task == "expr_gen"){

	for($x=0;$x<$arrlength;$x++){
		$cmd="sudo bash /var/www/html/script/cellranger_expr_generate.sh ".$path_fastq." ".$sample[$x]." &";
		echo "$cmd <br><br>";
	#	shell_exec($cmd);
		passthru($cmd,$returnvalue);
		if ($returnvalue != 0){
        		echo "<br/><br/>";
        		echo $sample[$x]." folder does not exist at ".$path_fastq."<br><br>";
        		exit();
		}else{
        		echo "<br/><br/>";
			echo "Results were generated successfully for ".$sample[$x]."<br><br>";
		}
	}
}elseif ($task == "summary"){

	$summary_cmd="sudo Rscript /var/www/html/script/cellranger_results_summary.R ".$path_fastq;

	for($x=0;$x<$arrlength;$x++){
		$summary_cmd = $summary_cmd." ".$sample[$x];
	}
	echo $summary_cmd."<br><br>";
	passthru($summary_cmd,$returnvalue1);
                if ($returnvalue1 != 0){
                        echo "<br/><br/>";
                        echo "fail to generate the final summary report for <br><br>";
                        exit();
                }else{
                        echo "<br/><br/>";
                        echo "final summary report is generated successfully<br><br>";
                }
	


}elseif ($task == "both"){
	for($x=0;$x<$arrlength;$x++){
		$cmd="sudo bash /var/www/html/script/cellranger_expr_generate.sh ".$path_fastq." ".$sample[$x]." &";
		echo "$cmd <br><br>";
	#	shell_exec($cmd);
		passthru($cmd,$returnvalue);
		if ($returnvalue != 0){
        		echo "<br/><br/>";
        		echo $sample[$x]." folder does not exist at ".$path_fastq."<br><br>";
        		exit();
		}else{
        		echo "<br/><br/>";
			echo "Results were generated successfully for ".$sample[$x]."<br><br>";
		}
	}
	
	$summary_cmd="sudo Rscript /var/www/html/script/cellranger_results_summary.R ".$path_fastq;

	for($x=0;$x<$arrlength;$x++){
		$summary_cmd = $summary_cmd." ".$sample[$x];
	}
	echo $summary_cmd."<br><br>";
	passthru($summary_cmd,$returnvalue1);
                if ($returnvalue1 != 0){
                        echo "<br/><br/>";
                        echo "fail to generate the final summary report for <br><br>";
                        exit();
                }else{
                        echo "<br/><br/>";
                        echo "final summary report is generated successfully<br><br>";
                }


}






echo "<br>";
echo "Samples: ";
foreach($sample as $value){
	echo "$value, ";
}
echo "<br>";
echo "Path to the files: $path_fastq<br><br><br>";
echo "<br>";

