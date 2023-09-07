<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage KPI (Staff)</title>
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
            $staffID = $_SESSION["staffID"];
            //Get DB Connection
            $database = "staff_db";
            $conn = connectSQL(true, $database);

            //Retrieve availability status
            $sqlAvalStatus = "SELECT `status` AS avalStatus FROM aval_table WHERE id = 1";

            //Retrieve Staff Info
            $sqlStaffInfo = "SELECT `name` AS staffName, email, gender FROM staff_table WHERE staff_id = '$staffID'";

            //Retrieve Staff KPI
            $sqlStaffKPI = "SELECT id, kpi_num, `status` AS kpiStatus FROM staff_kpi_table WHERE staff_id = '$staffID'";

            //Execute the query
            $resultStatus = mysqli_query($conn, $sqlAvalStatus);
            $resultInfo = mysqli_query($conn, $sqlStaffInfo);
            $resultStaffKPI = mysqli_query($conn, $sqlStaffKPI);

            $rowStatus = mysqli_fetch_assoc($resultStatus);
            $status = $rowStatus["avalStatus"];

            //Assign the staff info retrieved to the display variables
            if(mysqli_num_rows($resultInfo) > 0){
                $row = mysqli_fetch_assoc($resultInfo);
                $displayName = $row["staffName"];
                $displayEmail = $row["email"];
                $displayGender = $row["gender"];
            };

            if(isset($_POST["removeBtn"])){
                $id = $_POST["id"];
                //SQL statement to delete row from Staff KPI Table
                $sqlDelete = "DELETE FROM staff_kpi_table WHERE id = '$id'";

                //Execute the query
                mysqli_query($conn, $sqlDelete);

                //Reload the page to show the latest Staff KPI List
                header("Location:staffManageKPI.php?staffID=$staffID");
            };

            if(isset($_POST["addBtn"])){
                //Redirects the user to AssignStaffKPI.php
                header("Location:../AssignStaffKPI.php?staffID=$staffID");
            };
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Update Key Performance Indicator</p></div>
            <br>
            <div class="staffUpper">
                <div class="staffInner">
                    <div class="img">
                        <?php if($displayGender == "Male") :?>
                            <img src="../images/avatar_male.jpg" alt="../images/imgError.png">
                        <?php else : ?>
                            <img src="../images/avatar_female.jpg" alt="../images/imgError.png">
                        <?php endif ?>
                    </div>
                    <div class="subtitle"><p><?= $displayName?></p></div>
                    <div class="centerText"><p><?= $staffID?></p></div>
                    <div class="centerText"><i><?= $displayEmail?></i></div>
                </div>
            </div>
            <div class="staffLower">
                <table class="table">  		
                    <tr class="rowHeading">
                        <th class="col2">KPI List</th>	
                        <th class="col1">Approval Status</th>
                        <th class="col2">Remove</th>		
                    </tr>   
                    <?php
                        while ($row = mysqli_fetch_assoc($resultStaffKPI)){ 
                            echo '<tr><td>'.$row['kpi_num'].'</td>';
                            if(strcmp($row["kpiStatus"], "Pending") == 0){
                                echo '<td>Pending</td>';
                            }else{
                                echo '<td>Approved</td>';
                            };
                            
                            echo '<td>
                                    <form method="POST" action="">
                                        <div class="tableButton">
                                            <input name="id" type="hidden" value='.$row["id"].'>';
                                            if($status == 1){
                                                if(strcmp($row["kpiStatus"], "Approved") == 0){
                                                    echo '<input type="submit" name="removeBtn" value="Remove" disabled>';
                                                }else{
                                                    echo '<input type="submit" name="removeBtn" value="Remove">';
                                                }; 
                                            }else{
                                                echo '<input type="submit" name="removeBtn" value="Remove" disabled>';
                                            };
                                        echo '</div>
                                    </form>
                                </td>';
                            echo '</tr>';
                        };		
                    ?>
                    <tr>
                        <td></td>
                        <td>
                            <form method="POST" action="">
                                <div class="tableButton">
                                    <input type="submit" name="addBtn" value="Add KPI" <?php echo ($status == 0) ? 'disabled' : '';?>>
                                </div>
                            </form>
                        </td>
                        <td></td>
                    </tr>			  			  				 
                </table>
            </div>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>