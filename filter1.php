
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

    <title>Records Filters</title>
    <style>
          .floating-menu {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background-color: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .floating-menu a {
            display: block;
            margin: 5px 0;
            padding: 10px;
            background-color: #071C34;
            color: white;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }

        .floating-menu ab {
            display: block;
            margin: 5px 0;
            padding: 10px;
            background-color: white;
            color: #071C34;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }

        .floating-menu a:hover {
            background-color: white;
            color:#071C34;
            font-weight: bold;
        }

        
        
        .tooltip {
            position: relative;
            display: inline-block;
        }
        
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 300px;
            background-color: #f9f9f9;
            color: #000;
            text-align: left;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 5px;
            position: absolute;
            z-index: 2;
            top: 100%;
            left: 50%;
            margin-left: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        
        .tooltip:hover .tooltiptext {
            visibility: visible;
        }
    </style>
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
                <li><a href="admin4.php">School</a></li>
                <li><a href="admin5.php">Work Experience</a></li>
                <li><a href="admin6.php">Reference</a></li>
            </ul>
        </div>
    <br>
    <h1>Records Filters</h1>
    <form method="POST" action="">
        <fieldset class="tooltip">
            <legend>Filter by Region and Age</legend>
            <div class="tooltiptext">Display the profile of the applicants who live in the NCR Region who are 30 years old and above, ordered by their Applicant ID.</div>
            <input type="checkbox" name="region_ncr" value="NCR"> Lives in NCR<br>
            <input type="checkbox" name="age_30_plus" value="30"> Age 30 and above<br>
        </fieldset>
        
        <fieldset class="tooltip">
            <legend>Filter by Desired Position and Employment Type</legend>
            <div class="tooltiptext">Display applicant ID, available working date, and desired salary of applicants who applied for a full-time Software Developer position, ordered by their working date availability.</div>
            <input type="checkbox" name="position_software_dev" value="Software Developer"> Software Developer<br>
            <input type="checkbox" name="employment_full_time" value="FT"> Full Time<br>
        </fieldset>
        
        <fieldset class="tooltip">
            <legend>Filter by Reason for Leaving</legend>
            <div class="tooltiptext">Display the applicant ID, employer, previously employed position, employed date, and the reason for leaving for those applicants who left because of career or relocation-related reasons, sorted by the most recent employed date.</div>
            <input type="checkbox" name="reason_career" value="Career"> Career-related reason<br>
            <input type="checkbox" name="reason_relocation" value="Relocation"> Relocation<br>
        </fieldset>
        
        <fieldset class="tooltip">
            <legend>Filter by Major</legend>
            <div class="tooltiptext">Display the records of applicants who took any computer major in their bachelor's degree, ordered by the applicant ID.</div>
            <input type="checkbox" name="major_computer" value="Computer"> Computer major<br>
        </fieldset>

        <input type="submit" name="filter" value="Apply Filters">
    </form>

    <?php
    if (isset($_POST['filter'])) {
        // Database connection parameters
        $db_servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $db_name = "project";
        
        // Create connection
        $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Initialize SQL queries
        $queries = [];

        // 1. Filter by Region and Age
        if (isset($_POST['region_ncr']) && isset($_POST['age_30_plus'])) {
            $queries[] = "SELECT * FROM applicant_profile WHERE region = 'NCR' AND Age >= 30 ORDER BY applicantID";
        }

        // 2. Filter by Desired Position and Employment Type
        if (isset($_POST['position_software_dev']) && isset($_POST['employment_full_time'])) {
            $queries[] = "SELECT applicantID, availWDate, desiredSalary FROM application_information WHERE desiredPosition = 'Software Developer' AND desiredEmployment = 'FT' ORDER BY availWDate";
        }

        // 3. Filter by Reason for Leaving
        if (isset($_POST['reason_career']) || isset($_POST['reason_relocation'])) {
            $reasons = [];
            if (isset($_POST['reason_career'])) {
                $reasons[] = "reasonforLeaving LIKE 'Career%'";
            }
            if (isset($_POST['reason_relocation'])) {
                $reasons[] = "reasonforLeaving = 'Relocation'";
            }
            $reason_filter = implode(" OR ", $reasons);
            $queries[] = "SELECT applicantID, employer, employedPosition, dateEmployed, reasonforLeaving FROM work_experience WHERE $reason_filter ORDER BY dateEmployed DESC";
        }

        // 4. Filter by Major
        if (isset($_POST['major_computer'])) {
            $queries[] = "SELECT * FROM educational_background WHERE major LIKE '%Computer%' AND degree LIKE 'Bachelor%' ORDER BY applicantID";
        }

        // Execute and display results
        foreach ($queries as $query) {
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                echo "<table border='1'><tr>";
                while ($field_info = $result->fetch_field()) {
                    echo "<th>{$field_info->name}</th>";
                }
                echo "</tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>$cell</td>";
                    }
                    echo "</tr>";
                }
                echo "</table><br>";
            } else {
                echo "No results found for query: $query<br>";
            }
        }
        // Close connection
        $conn->close();
    }
    ?>
     <div class="floating-menu">
        <ab href="filter1.php">Simple</ab>
        <a href="moderate_filter.php">Moderate</a>
        <a href="difficult_filter.php">Difficult</a>
    </div>
</body>
</html>
