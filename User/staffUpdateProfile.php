<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php
        //Include the functions.php file so that it can access the functions defined inside
        include ("../functions/functions.php");

        session_start();
        if($_SESSION["status"] == 0 OR $_SESSION["type"] != "user"){
            //Redirects the user back to the login page if not logged in/is not an admin account
            header("Location:../login.php");
        }else{
            //Initialize display variables
            $errorPW = $errorConf = $passwordChangedAlert = "";
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $staffID = $_SESSION["staffID"];
                $validatePassAll = true;
                $validateNewPW = false;

                //Get DB Connection
                $database = "staff_db";
                $conn = connectSQL(true, $database);

                //Sanitize inputs
                $newPass = $_POST["newPass"];
                $confPass = $_POST["confPass"];

                //Validate new password
                //Check empty
                if(checkEmpty($newPass) == false){
                    $errorPW = "Please enter a new password";
                    $validatePassAll = false;
                }else{
                    //Check if password has at least 6 characters
                    if(strlen($newPass) < 6){
                        $errorPW = "Password must have at least 6 characters";
                        $validatePassAll = false;
                    }else{
                        //Checks if the new password is the same as the current
                        //SQL statement to retrieve user password
                        $sql = "SELECT `password` AS pw FROM account_table WHERE staff_id = '$staffID'";

                        //Execute SQL query
                        $result = mysqli_query($conn, $sql);

                        //Get result
                        $row = mysqli_fetch_assoc($result);
                        $currPW = $row["pw"];

                        //Compare with new password (hashed)
                        $hashPW = hash('sha256', $newPass);
                        if(strcmp($currPW, $hashPW) == 0){
                            $errorPW = "Please use a different password";
                            $validatePassAll = false;
                        }else{
                            //Set to true to trigger input retention
                            $validateNewPW = true;
                        }
                    };
                };

                //Validate confirm password (Only when the first has been verified)
                if($validateNewPW){
                    //Check empty
                    if(checkEmpty($confPass) == false){
                        $errorConf = "Please confirm your password";
                        $validatePassAll = false;
                    }else{
                        //Checks if both passwords match
                        if(strcmp($newPass, $confPass) != 0){
                            $errorConf = "Passwords do not match";
                            $validatePassAll = false;
                        };
                    };
                };

                if($validatePassAll){
                    //SQL statement to update the user password in the account_table
                    $sql = "UPDATE account_table SET `password` = '$hashPW' WHERE staff_id = '$staffID'";

                    //Execute the query
                    mysqli_query($conn, $sql);

                    //Display password changed message
                    $passwordChangedAlert = "Password has been changed. Please log in again with your new password";

                    //Destroys the current session to force the user to log in again
                    session_unset();
                    session_destroy();
                };

                //Close the connection
                mysqli_close($conn);
            };
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Reset Password</p></div>
            <div class="subtitle"><p>Personal Information</p></div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="wrap">
                    <span class="field">New Password: </span>
                    <span class="errorText"><?= $errorPW?></span>
                </div>
                <input type="password" name="newPass" value="<?php if(!$validatePassAll && $validateNewPW){echo isset($_POST["newPass"]) ? $_POST["newPass"] : '';}?>">
                <div class="wrap">
                    <span class="field">Confirm Password: </span>
                    <span class="errorText"><?= $errorConf?></span>
                </div>
                <input type="password" name="confPass">
                <div class="button">
                    <input type="submit" value="Reset Password" name="resetBtn">
                </div>
                <div class="alert">
                    <p><?= $passwordChangedAlert?></p>
                </div>
            </form>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>