<?php

$path=$_POST["path"];
$project_name=preg_replace('/\s+/', '',$_POST["project_name"]);

echo "Path to Raw QC files:         <strong> $path</strong><br/>";
echo "project_name:      <strong>$project_name</strong><br/>";
echo "<br/><br/>";
$log_name=$project_name."_FFPE_tmp.log";
shell_exec("sudo bash /var/www/html/script/FFPE_mRNA_QC.sh $path $project_name >> /var/www/html/log/$log_name & ");
#shell_exec("sudo perl /home/genomics/bin/titan_mapping.pl -f $path -e $email -t $seq_type -o $organism -p $project_name -qc > $path/test.out.txt 2>&1 &");



?>



