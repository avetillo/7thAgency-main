<?php
// Database connection parameters
$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "project";
$conn = "";

// Create connection
try {
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo "<script type='text/javascript'>alert('Could not connect: " . $e->getMessage() . "');</script>";
    exit;
}

// Handle update operation
if (isset($_POST['update'])) {
    $applicantID = $_POST['applicantID'];
    $fullName = $_POST['fullName'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $region = $_POST['region'];
    $zip = $_POST['zip'];
    $homePhone = $_POST['homePhone'];
    $cellPhone = $_POST['cellPhone'];
    $emailAddress = $_POST['emailAddress'];
    $sssNumber = $_POST['sssNumber'];
    $birthDate = $_POST['birthDate'];
    $age = $_POST['age'];
    $citizenship = $_POST['citizenship'];
    $sex = $_POST['sex'];
    $maritalStatus = $_POST['maritalStatus'];

    // Prepare SQL statement with placeholders
    $sql = "UPDATE applicant_profile SET 
            fullName=?, 
            address=?, 
            city=?, 
            region=?, 
            zip=?, 
            homePhone=?, 
            cellPhone=?, 
            emailAddress=?, 
            sssNumber=?, 
            birthDate=?, 
            age=?, 
            citizenship=?, 
            sex=?, 
            maritalStatus=? 
            WHERE applicantID=?";

    // Prepare and bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssi", $fullName, $address, $city, $region, $zip, $homePhone, $cellPhone, $emailAddress, $sssNumber, $birthDate, $age, $citizenship, $sex, $maritalStatus, $applicantID);

    try {
        // Execute the update statement
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Record updated successfully');</script>";
        } else {
            throw new Exception("Error updating record: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "<script type='text/javascript'>alert('Invalid Input: " . $e->getMessage() . "');</script>";
    }

    // Close statement
    $stmt->close();
}

// Handle delete operation
$popup_message = '';

if (isset($_POST['delete'])) {
    $id = $_POST['applicantID'];

    // Validate that ID is provided and is an integer
    if (!empty($id) && filter_var($id, FILTER_VALIDATE_INT)) {
        // Check if the applicantID exists in applicant_profile table
        $check_sql = "SELECT COUNT(*) as count FROM applicant_profile WHERE applicantID=?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            // Begin transaction
            $conn->begin_transaction();

            try {
                // Delete from application_information table (child table)
                $sql1 = "DELETE FROM application_information WHERE applicantID=?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("i", $id);
                if ($stmt1->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from application_information: " . $conn->error);
                }
                $stmt1->close();

                // Delete from educational_background table (child table)
                $sql2 = "DELETE FROM educational_background WHERE applicantID=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("i", $id);
                if ($stmt2->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from educational_background: " . $conn->error);
                }
                $stmt2->close();

                // Delete from character_reference table (child table)
                $sql3 = "DELETE FROM character_reference WHERE applicantID=?";
                $stmt3 = $conn->prepare($sql3);
                $stmt3->bind_param("i", $id);
                if ($stmt3->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from character_reference: " . $conn->error);
                }
                $stmt3->close();

                // Delete from work_experience table (child table)
                $sql4 = "DELETE FROM work_experience WHERE applicantID=?";
                $stmt4 = $conn->prepare($sql4);
                $stmt4->bind_param("i", $id);
                if ($stmt4->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from work_experience: " . $conn->error);
                }
                $stmt4->close();

                // Delete from applicant_profile table (parent table)
                $sql5 = "DELETE FROM applicant_profile WHERE applicantID=?";
                $stmt5 = $conn->prepare($sql5);
                $stmt5->bind_param("i", $id);
                if ($stmt5->execute()) {
                    $popup_message .= "";
                } else {
                    throw new Exception("Error deleting from applicant_profile: " . $conn->error);
                }
                $stmt5->close();

                // Commit transaction if all deletions succeed
                $conn->commit();
                $popup_message = "All records deleted successfully. " . $popup_message;
            } catch (Exception $e) {
                $conn->rollback(); // Rollback transaction on error
                $popup_message = $e->getMessage();
            }
        } else {
            $popup_message = "Applicant ID does not exist.";
        }
    } else {
        $popup_message = "Invalid applicant ID.";
    }
}

// SQL query to select data from the table
$sql = "SELECT * FROM applicant_profile";
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

    <script type="text/javascript">
        function showAlert(message) {
            alert(message);
            window.location.href = 'admin1.php'; // Redirect to the same page
        }
    </script>
</head>
<body>
    <div class="applicant-container">
        <div class="header">
            <ul>
            <img class="logo" src="photo&icons/Transparent Logo.png" alt="Logo">
                <li><a href="index.php">Home</a></li>
                <li><ab href="admin1.php">AP Profile</ab></li>
                <li><a href="admin2.php">AP Information</a></li>
                <li><a href="admin3.php">Educational</a></li>
                <li><a href="admin4.php">School</a></li>
                <li><a href="admin5.php">Work Experience</a></li>
                <li><a href="admin6.php">Reference</a></li>
            </ul>
        </div>
        
        <div class="container">
            <h1>Admin Dashboard</h1>
            <h2>Applicant Profile</h2>

            <form method="POST" action="">
                <label for="applicantID">Applicant ID: </label>
                <input type="text" id="applicantID" name="applicantID" required>
                <input type="submit" name="delete" value="Delete">
            </form>

            <div class="table-container">
                <table>
                    <tr>
                        <th>Applicant ID</th>
                        <th>Full Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Region</th>
                        <th>Zip</th>
                        <th>Home Phone</th>
                        <th>Cell Phone</th>
                        <th>Email Address</th>
                        <th>SSS Number</th>
                        <th>Birthdate</th>
                        <th>Age</th>
                        <th>Citizenship</th>
                        <th>Sex</th>
                        <th>Marital Status</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    if ($popup_message != '') {
                        echo "<script type='text/javascript'>showAlert('$popup_message');</script>";
                    }

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <form method='POST' action=''>
                                        <td>".$row["applicantID"]."<input type='hidden' name='applicantID' value='".$row["applicantID"]."'></td>
                                        <td><input type='varchar(60)' name='fullName' value='".$row["fullName"]."'></td>
                                        <td><input type='varchar(80)' name='address' value='".$row["address"]."'></td>
                                        <td><input type='varchar(30)' name='city' value='".$row["city"]."'></td>
                                        <td><input type='varchar(20)' name='region' value='".$row["region"]."'></td>
                                        <td><input type='int(11)' name='zip' value='".$row["zip"]."'></td>
                                        <td><input type='char(20)' name='homePhone' value='".$row["homePhone"]."'></td>
                                        <td><input type='char(20)' name='cellPhone' value='".$row["cellPhone"]."'></td>
                                        <td><input type='varchar(60)' name='emailAddress' value='".$row["emailAddress"]."'></td>
                                        <td><input type='varchar(15)' name='sssNumber' value='".$row["sssNumber"]."'></td>
                                        <td><input type='date' name='birthDate' value='".$row["birthDate"]."'></td>
                                        <td><input type='int(3)' name='age' value='".$row["age"]."'></td>
                                        <td><input type='varchar(30)' name='citizenship' value='".$row["citizenship"]."'></td>
                                        <td><input type='char(1)' name='sex' value='".$row["sex"]."'></td>
                                        <td><input type='char(2)' name='maritalStatus' value='".$row["maritalStatus"]."'></td>
                                        <td>
                                            <input type='submit' name='update' value='Update'>
                                        </td>
                                    </form>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='16'>No results found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
