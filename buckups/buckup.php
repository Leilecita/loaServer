<?php
include __DIR__ . '/../config/config.php';
require __DIR__ . '/../libs/dbhelper.php';


global $DBCONFIG;

// $this->db->connect('pdo', 'mysql', $DBCONFIG['HOST'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'],$DBCONFIG['DATABASE'],$DBCONFIG['PORT']);


$dbhost = $DBCONFIG['HOST'];
$dbname = $DBCONFIG['DATABASE'];
$dbuser = "root";
$dbpass = $DBCONFIG['PASSWORD'];

$backup_file = "/Users/leila/buckups/" .$dbname. "-" .date("Y-m-d-H-i-s"). ".sql";;

// comandos a ejecutar
$command = "/Applications/XAMPP/bin/mysqldump --opt -h $dbhost -u $dbuser -p$dbpass $dbname | gzip > $backup_file";

system($command,$output);
echo $output;

