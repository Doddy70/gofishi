<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=rental_perahu', 'rental_perahu', 'Warakajimbe80');
    echo "Connected via 127.0.0.1\n";
} catch(PDOException $e) { echo $e->getMessage() . "\n"; }

try {
    $pdo = new PDO('mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=rental_perahu', 'rental_perahu', 'Warakajimbe80');
    echo "Connected via MAMP socket\n";
} catch(PDOException $e) { echo $e->getMessage() . "\n"; }

try {
    $pdo = new PDO('mysql:unix_socket=/Users/doddykapisha/Library/Application Support/Herd/config/valet/mysql.sock;dbname=rental_perahu', 'root', '');
    echo "Connected via Herd socket root\n";
} catch(PDOException $e) { echo $e->getMessage() . "\n"; }
