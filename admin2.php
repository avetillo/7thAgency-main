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
    $applicationID = $_POST['applicationID'];
    $desiredPosition = $_POST['desiredPosition'];
    $availWDate = $_POST['availWDate'];
    $desiredSalary = $_POST['desiredSalary'];
    $desiredEmployment = $_POST['desiredEmployment'];
    $applicantID = $_POST['applicantID'];

      // Prepare SQL statement with placeholders
      $sql = "UPDATE application_information SET 
      desiredPosition=?, 
      availWDate=?, 
      desiredSalary=?, 
      desiredEmployment=? 
      WHERE applicationID=?";

       // Prepare and bind parameters to prevent SQL injection
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("ssdsi", $desiredPosition, $availWDate, $desiredSalary, $desiredEmployment, $applicationID);

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
$sql = "SELECT * FROM application_information";
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
            window.location.href = 'admin2.php'; // Redirect to the same page
        }
    </script>
</head>
<body>
    <div class="applicant-container">
        <div class="header">
            <ul>
                <img class="logo" src="photo&icons/Transparent Logo.png" alt="Logo">
                <li><a href="index.php">Home</a></li>
                <li><a href="admin1.php">AP Profile</a></li>
                <li><ab href="admin2.php">AP Information</ab></li>
                <li><a href="admin3.php">Educational</a></li>
                <li><a href="admin4.php">School</a></li>
                <li><a href="admin5.php">Work Experience</a></li>
                <li><a href="admin6.php">Reference</a></li>
            </ul>
        </div>
        
        <div class="container">
            <h1>Admin Dashboard</h1>
            <h2>Application Information</h2>

            <form method="POST" action="">
                <label for="applicantID">Applicant ID: </label>
                <input type="text" id="applicantID" name="applicantID" required>
                <input type="submit" name="delete" value="Delete">
            </form>

            <div class="table-container">
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Desired Position</th>
                        <th>Available Working Date</th>
                        <th>Desired Salary</th>
                        <th>Desired Employment</th>
                        <th>Applicant ID</th>
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
                                        <td>".$row["applicationID"]."<input type='hidden' name='applicationID' value='".$row["applicationID"]."'></td>
                                        <td><input type='text' name='desiredPosition' value='".$row["desiredPosition"]."'></td>
                                        <td><input type='date' name='availWDate' value='".$row["availWDate"]."'></td>
                                        <td><input type='number' name='desiredSalary' value='".$row["desiredSalary"]."'></td>
                                        <td><input type='text' name='desiredEmployment' value='".$row["desiredEmployment"]."'></td>
                                        <td>".$row["applicantID"]."<input type='hidden' name='applicantID' value='".$row["applicantID"]."'></td>
                                        <td><input type='submit' name='update' value='Update'></td>
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
