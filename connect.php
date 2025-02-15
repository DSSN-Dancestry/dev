<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connections = array();

function getDbConnection()
{
    if (
        $_SERVER['HTTP_HOST'] == 'stark.cse.buffalo.edu' || $_SERVER['HTTP_HOST'] == 'webdev.cse.buffalo.edu'
    ) {
        $servername = "stark.cse.buffalo.edu";
        $username = "cl";
        $password = "DclSQLwebsiteLineage";
        $dbname = "choreographiclineage_db";
        $port = "3306";
    } elseif ($_SERVER['HTTP_HOST'] == 'lannister.cse.buffalo.edu') {
        $servername = "lannister.cse.buffalo.edu";
        $username = "cl";
        $password = "DclSQLwebsiteLineage";
        $dbname = "choreographiclineage_db";
        $port = "3306";
    } else {
        $servername = 'host.docker.internal';
        $username = 'root';
        $password = '';
        $port = "3306";    // Charul changed it to 3306 for MYSQL workbench connection [originally it was 3307]
        $dbname = 'choreographiclineage_db'; 
    }
    error_log("Connecting to  " . $dbname . " as user " . $username, 0);
    $conn = null;
    global $connections;
    // Create connection
    try {
        //echo("trying connection");
        $conn = new PDO("mysql:host=" . $servername . ":" . $port . ";dbname=" . $dbname, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        array_push($connections, $conn);
        return $conn;
    } catch (Exception $e) {
        echo "connection error " . $servername . $dbname . $username . $password;
        error_log("Error Connecting to  " . $dbname . " as user " . $username . " " . $e, 0);
    }
}

function closeConnections()
{
    global $connections;
    foreach ($connections as $conn) {
        $conn = null;
    }
    $connections = array();
}
