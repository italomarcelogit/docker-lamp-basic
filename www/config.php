<?php
$servername = "db";
$username = "user";
$password = "test";
$database = "sistema";

try {        
    $conn = new PDO("mysql:host=$servername;dbname=".$database, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $msg = "Status: Connected successfully";
}
catch(PDOException $e) {
     $msg = "status: Connection failed: " . $e->getMessage();
}

?>