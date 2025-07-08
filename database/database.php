<?php

$host = "localhost";
$dbname = "mentehofdb";
$username = "root";
$password = "05092024";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;