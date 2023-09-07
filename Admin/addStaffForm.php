<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff Form</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        //Include the functions.php file so that it can access the functions defined inside
        include ("../functions/functions.php");

        session_start();
        if($_SESSION["status"] == 0 OR $_SESSION["type"] != "admin"){
            //Redirects the user back to the login page if not logged in/is not an admin account
            header("Location:../login.php");
        }else{
            //Initialize the variables
            //Input variables
            $fname = $staffID = $email = $gender = $school = "";
            //Error messages and alerts
            $errorName = $errorStaffID = $errorEmail = $recordSavedAlert = "";
            //Variable that keeps track of the validation status
            $validatePassAll = false;

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $validatePassAll = true;

                //Assigning values to the variables that doesn't require validation
                $gender = $_POST["addGender"];
                $school = $_POST["addSchool"];

                //Sanitize inputs
                $fname = trimInput($_POST["addFname"]);
                $staffID = trimInput($_POST["addStaffID"]);
                $email = trimInput($_POST["addEmail"]);

                //Get DB Connection
                $database = "staff_db";
                $conn = connectSQL(true, $database);

                //Validate Name
                //Calls validateName() which returns an array (errorMsg, bool)
                $validateName = validateName($fname);
                if(!$validateName[1]){
                    $errorName = $validateName[0];
                    $validatePassAll = false;
                };

                //Validate Staff ID
                //Calls validateStaffID() which returns an array (errorMsg, bool)
                $validateStaffID = validateStaffID($staffID, $conn);
                if(!$validateStaffID[1]){
                    $errorStaffID = $validateStaffID[0];
                    $validatePassAll = false;
                };

                //Validate Email
                //Calls validateEmail() which returns an array (errorMsg, bool)
                $validateEmail = validateEmail($email);
                if(!$validateEmail[1]){
                    $errorEmail = $validateEmail[0];
                    $validatePassAll = false;
                }else{
                    //Check exists
                    //Calls checkExistsEmail() which returns an array (errorMsg, bool)
                    $validateExists = checkExistsEmail($email, $conn);
                    if(!$validateExists[1]){
                        $errorEmail = $validateExists[0];
                        $validatePassAll = false;
                    };
                };

                //Inserts the record into respective tables if all inputs have passed the validation
                if($validatePassAll){
                    //Extract account login name from email
                    $loginName = strtok($email, "@");
                    //Hash the default password
                    $defaultPW = hash('sha256', "password123");
                    $defaultType = "user";

                    //SQL query to insert values into staff_table
                    $sqlStaff = "INSERT INTO staff_table (staff_id, email, `name`, gender, school) VALUES ('$staffID', '$email', '$fname', '$gender', '$school');";
                    //SQL query to insert values into account_table
                    $sqlAcc = "INSERT INTO account_table (staff_id, `name`, `password`, `type`, email) VALUES ('$staffID', '$loginName', '$defaultPW', '$defaultType', '$email');";

                    //Execute the INSERT statements
                    mysqli_query($conn, $sqlStaff);
                    mysqli_query($conn, $sqlAcc);

                    //Inform user that the record has been saved
                    $recordSavedAlert = "Record is Saved";
                };

                //Close the connection
                mysqli_close($conn);
            };
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Add Staff Profile</p></div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <p><span class="errorText">* required field</span></p>
                <br>
                <div class="wrap">
                    <span class="field">Full Name: </span>
                    <span class="errorText">*<?= $errorName?></span>
                </div>
                <input type="text" name="addFname" value="<?php if(!$validatePassAll){echo isset($_POST["addFname"]) ? $_POST["addFname"] : '';} ?>">
                <br><br>
                <div class="wrap">
                    <span class="field">Staff ID: </span>
                    <span class="errorText">*<?= $errorStaffID?></span>
                </div>
                <input type="text" name="addStaffID" value="<?php if(!$validatePassAll){echo isset($_POST["addStaffID"]) ? $_POST["addStaffID"] : '';} ?>">
                <br><br>
                <div class="wrap">
                    <span class="field">Email: </span>
                    <span class="errorText">*<?= $errorEmail?></span>
                </div>
                <input type="text" name="addEmail" value="<?php if(!$validatePassAll){echo isset($_POST["addEmail"]) ? $_POST["addEmail"] : '';} ?>">
                <br><br>
                <div class="wrap">
                    <span class="field">Gender: </span>
                    <div class="custom-select">
                        <select name="addGender">
                            <option value="Male" <?php if(!$validatePassAll){if(isset($_POST["addGender"])){echo ($_POST["addGender"] == "Male") ? "selected" : '';}}?>>Male</option>
                            <option value="Female" <?php if(!$validatePassAll){if(isset($_POST["addGender"])){echo ($_POST["addGender"] == "Female") ? "selected" : '';}}?>>Female</option>
                        </select>
                    </div>
                    <br><br>
                    <span class="field">School/Faculty: </span>
                    <div class="custom-select">
                        <select name="addSchool">
                            <option value="SFS" <?php if(!$validatePassAll){if(isset($_POST["addSchool"])){echo ($_POST["addSchool"] == "SFS") ? "selected" : '';}}?>>SFS</option>
                            <option value="FBDA" <?php if(!$validatePassAll){if(isset($_POST["addSchool"])){echo ($_POST["addSchool"] == "FBDA") ? "selected" : '';}}?>>FBDA</option>
                            <option value="FECS" <?php if(!$validatePassAll){if(isset($_POST["addSchool"])){echo ($_POST["addSchool"] == "FECS") ? "selected" : '';}}?>>FECS</option>
                        </select>
                    </div>
                </div>
                <br><br>
                <div class="button">
                    <input type="submit" value="Add Staff" name="addStaffBtn">
                </div>
                <div class="alert">
                    <p><?= $recordSavedAlert?></p>
                </div>
            </form>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>