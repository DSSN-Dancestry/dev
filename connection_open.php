<?php
    $mysqli_hostname = "lannister.cse.buffalo.edu";
    $mysqli_user = "cl";
    $mysqli_password = "DclSQLwebsiteLineage";
    $mysqli_database = "choreographiclineage_db";
    $mysqli_port="3306";

 $dbc = mysqli_connect($mysqli_hostname.":".$mysqli_port, $mysqli_user, $mysqli_password, $mysqli_database)
        or die('Error connecting to MySQL server.');
