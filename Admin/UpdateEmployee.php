<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Staff Profile</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        //Include the functions.php file so that it can access the functions defined inside
        include("../functions/functions.php");

        session_start();
        if($_SESSION["status"] == 0 OR $_SESSION["type"] != "admin"){
            //Redirects the user back to the login page if not logged in/is not an admin account
            header("Location:../login.php");
        }else{
            $updateClicked = false;
            //Initialize the variables
            $errorName = $errorEmail = $recordUpdatedAlert = "";

            //Get DB Connection
            $database = "staff_db";
            $conn = connectSQL(true, $database);

            if(!isset($_POST["updateStaffBtn"])){
                //On load
                //Retrieve the Staff ID throught GETs
                $staffID = $_GET["staffID"];

                //SQL query to retrieve staff info from DB
                $sql = "SELECT email, `name` AS staffName, gender, school FROM staff_table WHERE staff_id = '$staffID'";
        
                //Execute the query
                $result = mysqli_query($conn, $sql);

                //Assign the staff info retrieved to the value variables
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
                    $valName = $row["staffName"];
                    $valEmail = $row["email"];
                    $valGender = $row["gender"];
                    $valSchool = $row["school"];
                };
            }else{
                $validatePassAll = true;
                $updateClicked = true;

                //Retrieve xss safe inputs
                $staffID = $_POST["staffID"];
                $updateGender = $_POST["editGender"];
                $updateSchool = $_POST["editSchool"];
                $prevEmail = $_POST["prevEmail"];

                //Sanitize inputs
                $updateFname = trimInput($_POST["editFname"]);
                $updateEmail = trimInput($_POST["editEmail"]);

                //Validation of updated inputs
                //Validate Name
                $validateName = validateName($updateFname);
                if(!$validateName[1]){
                    $errorName = $validateName[0];
                    $validatePassAll = false;
                };

                //Validate Email
                $validateEmail = validateEmail($updateEmail);
                if(!$validateEmail[1]){
                    $errorEmail = $validateEmail[0];
                    $validatePassAll = false;
                }else{
                    //Check staff's previous email
                    //SQL query to fetch user's current email address
                    $sql = "SELECT email FROM staff_table WHERE staff_id = '$staffID'";

                    //Execute the query
                    $result = mysqli_query($conn, $sql);

                    if(mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_assoc($result);
                        //Compare the email input with the user's previous email
                        if(strcmp($updateEmail, $row["email"]) != 0){
                            //Checks for availability if different
                            $validateExists = checkExistsEmail($updateEmail, $conn);
                            if(!$validateExists[1]){
                                $errorEmail = $validateExists[0];
                                $validatePassAll = false;
                            };
                        };
                    };
                };

                //Updates the records in respective tables if all inputs have passed the validation
                if($validatePassAll){
                    //Extract account login name from updated email
                    $loginName = strtok($updateEmail, "@");

                    //SQL query to update values in staff_table
                    $sqlStaff = "UPDATE staff_table SET email = '$updateEmail', `name` = '$updateFname', gender = '$updateGender', school = '$updateSchool' WHERE staff_id = '$staffID';";
                    //SQL query to update values in account_table
                    $sqlAcc = "UPDATE account_table SET `name` = '$loginName', email = '$updateEmail' WHERE staff_id = '$staffID';";

                    //Execute the INSERT statements
                    mysqli_query($conn, $sqlStaff);
                    mysqli_query($conn, $sqlAcc);

                    //Close the connection
                    mysqli_close($conn);

                    //Redirects user to DisplayStaffInfo.php on successful updates
                    header("Location:DisplayStaffInfo.php?staffID=$staffID");
                };
            };
            //Close the connection
            mysqli_close($conn);
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Update Staff Profile</p></div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <p><span class="errorText">* required field</span></p>
                <br>
                <div class="wrap">
                    <span class="field">Full Name: </span>
                    <span class="errorText">*<?= $errorName?></span>
                </div>
                <input type="text" name="editFname" value="<?php if(!$updateClicked){echo $valName;}else{if(!$validatePassAll){echo isset($_POST["editFname"]) ? $_POST["editFname"] : '';}}?>">
                <br><br>
                <div class="wrap">
                    <span class="field">Staff ID: </span>
                </div>
                <input type="text" name="staffID" value="<?php echo $staffID?>" readonly>
                <br><br>
                <div class="wrap">
                    <span class="field">Email: </span>
                    <span class="errorText">*<?= $errorEmail?></span>
                </div>
                <input type="text" name="editEmail" value="<?php if(!$updateClicked){echo $valEmail;}else{if(!$validatePassAll){echo isset($_POST["editEmail"]) ? $_POST["editEmail"] : '';}}?>">
                <?php if(!$updateClicked) :?>
                    <input type="hidden" name="prevEmail" value="<?php echo $valEmail?>">
                <?php endif ?>
                
                <br><br>
                <div class="wrap">
                    <?php 
                        //For select fields
                        if(!$updateClicked){
                            $gender = $valGender;
                            $school = $valSchool;
                        }else{
                            $gender = $updateGender;
                            $school = $updateSchool;
                        }
                    ?>
                    <span class="field">Gender: </span>
                    <div class="custom-select">
                        <select name="editGender">
                            <option value="Male" <?php echo ($gender == "Male") ? "selected" : '';?>>Male</option>
                            <option value="Female" <?php echo ($gender == "Female") ? "selected" : '';?>>Female</option>
                        </select>
                    </div>
                    <br><br>
                    <span class="field">School/Faculty: </span>
                    <div class="custom-select">
                        <select name="editSchool">
                            <option value="SFS" <?php echo ($school == "SFS") ? "selected" : '';?>>SFS</option>
                            <option value="FBDA" <?php echo ($school == "FBDA") ? "selected" : '';?>>FBDA</option>
                            <option value="FECS" <?php echo ($school == "FECS") ? "selected" : '';?>>FECS</option>
                        </select>
                    </div>
                </div>
                <br><br>
                <div class="button">
                    <input type="submit" value="Update Staff" name="updateStaffBtn">
                </div>
                <div class="alert">
                    <p><?= $recordUpdatedAlert?></p>
                </div>
            </form>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>