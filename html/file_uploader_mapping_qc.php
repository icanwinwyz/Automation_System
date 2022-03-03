<?php

$path=$_POST["path"];
$organism=$_POST["org"];
$seq_type=$_POST["sequencing_type"];
$project_name=preg_replace('/\s+/', '',$_POST["project_name"]);
$qc=$_POST["qc"];
$email=$_POST["email"];

echo "Path to Fastq files:         <strong> $path</strong><br/>";
echo "Organism:         <strong>$organism</strong><br/>";
echo "Sequencing type:         <strong>$seq_type</strong><br/>";
echo "project_name:      <strong>$project_name</strong><br/>";
echo "Perform QC: <strong>$qc</strong><br/> ";
echo "<br/><br/>";
$log_name=$project_name."_".$organism."_tmp.log";
shell_exec("sudo perl /home/genomics/bin/titan_mapping.pl -f $path -e $email -t $seq_type -o $organism -p $project_name -qc >> /var/www/html/log/$log_name &");
#shell_exec("sudo perl /home/genomics/bin/titan_mapping.pl -f $path -e $email -t $seq_type -o $organism -p $project_name -qc > $path/test.out.txt 2>&1 &");



?>



