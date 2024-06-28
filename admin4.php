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

// SQL query to select data from the table
$sql = "SELECT * FROM school_details";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/admin1.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
    <title>7th Agency</title>
</head>
<body>
    <div class="applicant-container">
        <div class="header">
            
        <ul>
        <img class="logo" src="photo&icons/Transparent Logo.png" alt="Logo">
            <li><a href="index.php">Home</a></li>
            <li><a href="admin1.php">AP Profile</a></li>
            <li><a href="admin2.php">AP Information</a></li>
            <li><a href="admin3.php">Educational</a></li>
            <li><ab href="admin4.php">School</ab></li>
            <li><a href="admin5.php">Work Experience</a></li>
            <li><a href="admin6.php">Reference</a></li>
        </ul>

        </div>
        
    <div class="container">
        <h1>Admin Dashboard</h1>
        <h2>School Details</h2>
         <div class="table-container">
            <table>
             <tr>
                <th>School ID</th>
                <th>School Name</th>
                <th>Location</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <form method='POST' action=''>
                                <td>".$row["schoolID"]."<input type='hidden' name='schoolID' value='".$row["schoolID"]."'></td>
                                <td>".$row["schoolName"]."<input type='hidden' name='schoolName' value='".$row["schoolName"]."'></td>
                                <td>".$row["location"]."<input type='hidden' name='location' value='".$row["location"]."'></td>
                            </td>
                            </form>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='16'>No results found</td></tr>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>