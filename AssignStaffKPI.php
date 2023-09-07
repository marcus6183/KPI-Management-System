<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Staff KPI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        //Include the functions.php file so that it can access the functions defined inside
        include ("functions/functions.php");

        session_start();
        if($_SESSION["status"] == 0){
            //Redirects the user back to the login page if not logged in/is not an admin account
            header("Location:login.php");
        }else{
            //Get DB Connection
            $database = "staff_db";
            $conn = connectSQL(true, $database);

            //Retrieve availability status
            $sqlAvalStatus = "SELECT `status` AS avalStatus FROM aval_table WHERE id = 1";

            //Execute the query
            $resultStatus = mysqli_query($conn, $sqlAvalStatus);

            $rowStatus = mysqli_fetch_assoc($resultStatus);
            $status = $rowStatus["avalStatus"];

            if($_SESSION["type"] == "user" && $status == 0){
                header("Location:staffManageKPI.php");
            }else{
                if(isset($_GET["staffID"])){
                    //Initialize variables
                    $staffID = $_GET["staffID"];
                    $errorMsg = "";
                    
                    if(isset($_GET["error"])){
                        $errorMsg = "Please select an option";
                    };
    
                    //SQL statement to retrieve the staffName
                    $sqlStaff = "SELECT `name` AS staffName FROM staff_table WHERE staff_id = '$staffID'";
    
                    //SQL statement to retrieve all KPI List
                    $sqlKPI = "SELECT id, kpi_num, `description` AS kpiDesc FROM kpi_table";
    
                    //Execute the queries
                    $resultStaff = mysqli_query($conn, $sqlStaff);
                    $resultKPI = mysqli_query($conn, $sqlKPI);
    
                    $rowStaff = mysqli_fetch_assoc($resultStaff);
                    $staffName = $rowStaff["staffName"];
    
                    if(isset($_POST["assignBtn"])){
                        //Checks if the user has selected one of the KPI options
                        $selectedKPI = $_POST["selectKPI"];
                        $staffID = $_POST["staffID"];
                        if(strcmp($selectedKPI, "default") != 0){
                            //SQL statement to insert record to staff_kpi_table
                            $sqlInsert = "INSERT INTO staff_kpi_table (staff_id, kpi_num, `status`) VALUES ('$staffID', '$selectedKPI', 'Pending');";
    
                            //Execute the SQL query
                            mysqli_query($conn, $sqlInsert);
                            
                            if($_SESSION["type"] == "admin"){
                                //Redirects user back to UpdateStaffKPI.php
                                header("Location:Admin/UpdateStaffKPI.php?staffID=$staffID");
                            }else{
                                //Redirects user back to UpdateStaffKPI.php
                                header("Location:User/staffManageKPI.php");
                            };
                        }else{
                            //Loads the page again
                            header("Location:AssignStaffKPI.php?staffID=$staffID&error=true");
                        };
                    };
                };
            };
        };
    ?>

    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Assign KPI</p></div>
            <div class="subtitle"><p>To: <?=$staffName?></p></div>
            <form action="" method="POST">
                <div class="pfp">
                    <div class="custom-select">
                        <select name="selectKPI">
                            <option value="default">Choose one</option>
                            <?php
                                if(mysqli_num_rows($resultKPI) > 0){
                                    while($rowKPI = mysqli_fetch_assoc($resultKPI)){
                                        $kpiNum = $rowKPI["kpi_num"];
                                        $combinedKPI = $rowKPI["kpi_num"]." - ".$rowKPI["kpiDesc"];
                                        echo "<option value='$kpiNum'>$combinedKPI</option>";
                                    };
                                };
                            ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="button">
                    <input type="submit" value="Assign KPI" name="assignBtn">
                    <input type="hidden" value="<?=$staffID?>" name="staffID">
                </div>
                <div class="alert">
                    <p><?= $errorMsg?></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>