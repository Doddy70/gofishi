<?php
// Pure PHP MySQL Dumper - Clean & Reliable
$env = file_get_contents('../.env');
preg_match('/DB_DATABASE=(.*)/', $env, $db);
preg_match('/DB_USERNAME=(.*)/', $env, $user);
preg_match('/DB_PASSWORD="(.*)"/', $env, $pass);
preg_match('/DB_HOST=(.*)/', $env, $host);

$database = trim($db[1]);
$username = trim($user[1]);
$password = trim($pass[1]);
$db_host = trim($host[1]);

$mysqli = new mysqli($db_host, $username, $password, $database);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="gofishi_database_FIX.sql"');

// Get all tables
$tables = array();
$result = $mysqli->query('SHOW TABLES');
while($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

$sql = "-- Gofishi Database Dump\n";
$sql .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n\n";
$sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

echo $sql;

foreach($tables as $table) {
    // Create table structure
    $result = $mysqli->query('SHOW CREATE TABLE '.$table);
    $row = $result->fetch_row();
    echo "\n\n".$row[1].";\n\n";
    
    // Get data
    $result = $mysqli->query('SELECT * FROM '.$table);
    $num_fields = $result->field_count;
    
    while($row = $result->fetch_row()) {
        echo 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++) {
            if (is_null($row[$j])) {
                echo 'NULL';
            } else {
                $content = str_replace("\n", "\\n", $mysqli->real_escape_string($row[$j]));
                echo '"'.$content.'"';
            }
            
            if ($j<($num_fields-1)) {
                echo ',';
            }
        }
        echo ");\n";
    }
}

echo "\n\nSET FOREIGN_KEY_CHECKS=1;";
$mysqli->close();
exit;
