<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Staff Info</title>
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
            //Initialize variables
            $displayName = $displayID = $displayEmail = $displayGender = $displaySchool = ""; 
            //Get DB Connection
            $database = "staff_db";
            $conn = connectSQL(true, $database);

            //Checks if delete/update button has been clicked
            if(!isset($_POST["deleteBtn"]) AND !isset($_POST["updateBtn"])){
                if(isset($_GET["staffID"])){
                    $displayID = $_GET["staffID"];

                    //SQL query to retrieve staff info from DB
                    $sql = "SELECT email, `name` AS staffName, gender, school FROM staff_table WHERE staff_id = '$displayID'";
    
                    //Execute the query
                    $result = mysqli_query($conn, $sql);
    
                    //Assign the staff info retrieved to the display variables
                    if(mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_assoc($result);
                        $displayName = $row["staffName"];
                        $displayEmail = $row["email"];
                        $displayGender = $row["gender"];
                        $displaySchool = $row["school"];
                    };
                };
            }else{
                $staffID = $_POST["staffID"];

                if(isset($_POST["deleteBtn"])){
                    //SQL queries to delete staff record from tables
                    $sql1 = "DELETE FROM staff_table WHERE staff_id = '$staffID'";
                    $sql2 = "DELETE FROM staff_kpi_table WHERE staff_id = '$staffID'";
                    $sql3 = "DELETE FROM account_table WHERE staff_id = '$staffID'";

                    //Execute queries
                    mysqli_query($conn, $sql1);
                    mysqli_query($conn, $sql2);
                    mysqli_query($conn, $sql3);

                    //Close the connection
                    mysqli_close($conn);

                    //Redirect user to DeleteConfirm.php
                    header("Location:DeleteConfirm.php");

                }elseif(isset($_POST["updateBtn"])){
                    //Redirect user to UpdateEmployee.php passing staffID over
                    header("Location:UpdateEmployee.php?staffID=$staffID");
                };
            };
            //Close the connection
            mysqli_close($conn);
        };

    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Staff Profile</p></div>
            <br>
            <div class="staffPfp">
                <div class="staffPfpBG">
                    <?php if($displayGender == "Male") :?>
                        <img src="../images/avatar_male.jpg" alt="../images/imgError.png">
                    <?php else : ?>
                        <img src="../images/avatar_female.jpg" alt="../images/imgError.png">
                    <?php endif ?> 
                </div>
            </div>
            <br>
            <div class="wrap">
                <span class="field">Name: </span>
                <span><?= $displayName?></span>
            </div>
            <br>
            <div class="wrap">
                <span class="field">StaffID: </span>
                <span><?= $displayID?></span>
            </div>
            <br>
            <div class="wrap">
                <span class="field">Email: </span>
                <span><?= $displayEmail?></span>
            </div>
            <br>
            <div class="wrap">
                <span class="field">Gender: </span>
                <span><?= $displayGender?></span>
            </div>
            <br>
            <div class="wrap">
                <span class="field">School: </span>
                <span><?= $displaySchool?></span>
            </div>
            <form action="" method="post">
                <div class="button">
                    <input type="submit" value="Update" name="updateBtn">
                    <input type="submit" value="Delete" name="deleteBtn">
                </div>
                <input type="hidden" value=<?=$displayID?> name="staffID">
            </form>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>