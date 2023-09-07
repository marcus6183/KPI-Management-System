<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        //Include the functions.php file so that it can access the functions defined inside
        include ("functions/functions.php");

        session_start();
        if(!isset($_SESSION["email"]) OR $_SESSION["status"] == 0){
            //Redirects back to the login page if not logged in
            header("Location:login.php");
        }else{
            $userType = $_SESSION["type"];
            //Initialize variable
            $pageTitle = "";

            //Checks for user type
            if(strcmp($userType, "admin") == 0){
                $pageTitle = "KPI Assignment System";
            }else{
                //Retrieve staff name using their Staff ID
                $staffID = $_SESSION["staffID"];
                //Get DB Connection
                $database = "staff_db";
                $conn = connectSQL(true, $database);

                //SQL query to retrieve staff name
                $sql = "SELECT `name` AS staffName FROM staff_table WHERE staff_id = '$staffID'";

                //Execute the query
                $result = mysqli_query($conn, $sql);

                //Get query result
                $row = mysqli_fetch_assoc($result);
                $staffName = $row["staffName"];
                $pageTitle = "Welcome: ".$staffName;

                //Close the connection
                mysqli_close($conn);
            };
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p><?=$pageTitle?></p></div>
            <?php if($userType == "admin") :?>
                <div class="cardWrapper">
                    <div class="card">
                        <div class="cardTittle">
                            <p>Staff Module</p>
                        </div>
                        <div class="cardContent">
                            <ul class="cardList">
                                <li>
                                    <a href="Admin/addStaffForm.php">Add Staff Profile</a>
                                </li>
                                <li>
                                    <a href="Admin/SearchStaffForm.php?usage=profile">Manage Staff Profile</a>
                                </li>
                                <li>
                                    <a href="Admin/SearchStaffForm.php?usage=kpi">Update Staff KPI</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="cardTittle">
                            <p>KPI Module</p>
                        </div>
                        <div class="cardContent">
                            <ul class="cardList">
                                <li>
                                    <a href="Admin/addKPIForm.php">Add KPI</a>
                                </li>
                                <li>
                                    <a href="Admin/manageKPIForm.php">Manage KPI</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="cardTittle">
                            <p>Reporting Module</p>
                        </div>
                        <div class="cardContent">
                            <ul class="cardList">
                                <li>
                                    <a href="Admin/KPIReport.php">KPI Overview</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="cardTittle">
                            <p>Settings</p>
                        </div>
                        <div class="cardContent">
                            <ul class="cardList">
                                <li>
                                    <a href="Admin/availability.php">Availability</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php else :?>
                <div class="cardWrapper" style="justify-content: center;">
                    <div class="card">
                        <div class="cardTittle">
                            <p>Main Menu</p>
                        </div>
                        <div class="cardContent">
                            <ul class="cardList">
                                <li>
                                    <a href="User/staffManageKPI.php">Manage KPI</a>
                                </li>
                                <li>
                                    <a href="User/staffUpdateProfile.php">Update Profile</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif?>
        </div>
        <div class="container" id="footer">
            <div class="footer">
                <ul class="footer-link">
                    <li>
                        <a href="index.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>