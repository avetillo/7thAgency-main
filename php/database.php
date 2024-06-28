<?php
// Database connection parameters
$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "project";
$conn = "";

// Create connection
try{
    $conn = mysqli_connect($db_servername, $db_username, $db_password, $db_name);
}
catch(mysqli_sql_exception){
    echo "Could not Connect!";
}
// Check connection
if ($conn){
    echo"You are connected!";
}
else{
    echo "Could not connect!";
}