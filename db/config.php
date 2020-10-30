<?php
// database connection file
ob_start();

try {
    $conn = new PDO("mysql:dbname=sampledatabase;host=localhost:3308","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>