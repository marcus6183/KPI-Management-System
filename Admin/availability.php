<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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
            $settingsChangedAlert = "";
            //Get DB Connection
            $database = "staff_db";
            $conn = connectSQL(true, $database);

            //Get current availability status
            $sql = "SELECT `status` AS avalStatus FROM aval_table WHERE id = 1";

            //Execute the query
            $result = mysqli_query($conn, $sql);
            //Get query results
            $row = mysqli_fetch_assoc($result);
            $status = $row["avalStatus"];

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $checked = $_POST["checkAval"];
                if($checked == "checked"){
                    $isChecked = 1;
                }else{
                    //SQL statement that updates the availability table
                    $isChecked = 0;
                };

                //SQL statement that updates the availability table
                $sql = "UPDATE aval_table SET `status` = '$isChecked' WHERE id = 1";

                //Execute the query
                mysqli_query($conn, $sql);

                //Reload the page
                header("Location:availability.php");
            };

            //Close the connection
            mysqli_close($conn);
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Settings</p></div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="center">
                    <div class="wrap">
                        <span class="field">Availability: </span>
                        <label class="switch">
                            <input type="checkbox" name="checkAval" value="checked" <?php echo ($status == 1) ? 'checked' : '';?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" value="Apply Changes" name="applyBtn">
                </div>
            </form>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>