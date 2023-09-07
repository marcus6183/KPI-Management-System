<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Staff KPI</title>
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
            if(isset($_GET["staffID"])){
                $staffID = $_GET["staffID"];

                //Get DB Connection
                $database = "staff_db";
                $conn = connectSQL(true, $database);

                //SQL statements
                //Retrieve Staff Info
                $sqlStaffInfo = "SELECT `name` AS staffName, email, gender FROM staff_table WHERE staff_id = '$staffID'";

                //Retrieve Staff KPI
                $sqlStaffKPI = "SELECT id, kpi_num, `status` AS kpiStatus FROM staff_kpi_table WHERE staff_id = '$staffID' ORDER BY kpi_num";

                //Retrieve all KPI's from kpi_table
                $sqlKPI = "SELECT kpi_num, `description` AS kpiDesc FROM kpi_table ORDER BY kpi_num";

                //Execute the SQL queries
                $resultInfo = mysqli_query($conn, $sqlStaffInfo);
                $resultStaffKPI = mysqli_query($conn, $sqlStaffKPI);
                $resultKPI = mysqli_query($conn, $sqlKPI);

                //Assign the staff info retrieved to the display variables
                if(mysqli_num_rows($resultInfo) > 0){
                    $row = mysqli_fetch_assoc($resultInfo);
                    $displayName = $row["staffName"];
                    $displayEmail = $row["email"];
                    $displayGender = $row["gender"];
                };

                if(isset($_POST["approveBtn"])){
                    $id = $_POST["id"];
                    $staffID = $_POST["staffID"];
                    //SQL statement to update the Staff KPI Table
                    $sqlUpdate = "UPDATE staff_kpi_table SET `status` = 'Approved' WHERE id = '$id'";

                    //Execute the query
                    mysqli_query($conn, $sqlUpdate);

                    //Reload the page to show the latest Staff KPI List
                    header("Location:UpdateStaffKPI.php?staffID=$staffID");
                };

                if(isset($_POST["removeBtn"])){
                    $id = $_POST["id"];
                    $staffID = $_POST["staffID"];
                    //SQL statement to delete row from Staff KPI Table
                    $sqlDelete = "DELETE FROM staff_kpi_table WHERE id = '$id'";

                    //Execute the query
                    mysqli_query($conn, $sqlDelete);

                    //Reload the page to show the latest Staff KPI List
                    header("Location:UpdateStaffKPI.php?staffID=$staffID");
                };

                if(isset($_POST["assignBtn"])){
                    $staffID = $_POST["staffID"];

                    //Redirects the user to AssignStaffKPI.php
                    header("Location:../AssignStaffKPI.php?staffID=$staffID");
                };

            }else{
                //Redirects user back to the search staff form if StaffID is not set
                header("Location:SearchStaffForm.php?usage=kpi");
            };
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Update Key Performance Indicator</p></div>
            <div class="upperSplit">
                <div class="staffProfile">
                    <div class="profileInner">
                        <div class="pfp">
                            <?php if($displayGender == "Male") :?>
                                <img src="../images/avatar_male.jpg" alt="../images/imgError.png">
                            <?php else : ?>
                                <img src="../images/avatar_female.jpg" alt="../images/imgError.png">
                            <?php endif ?> 
                        </div>
                        <div class="details">
                            <p class="staffName"><?= $displayName?></p>
                            <p><?= $staffID?></p>
                            <i><?= $displayEmail?></i>
                        </div>
                    </div>
                </div>
                <div class="staffKPI">
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
                                    echo '
									<td>
										<form method="POST" action="">
											<div class="tableButton">
												<input name="id" type="hidden" value='.$row["id"].'>   
												<input name="staffID" type="hidden" value='.$staffID.'>   
												<input type="submit" name="approveBtn" value="Approve">
											</div>
										</form>
									</td>
									';
                                }else{
                                    echo '<td>Approved</td>';
                                };
								
								echo '
										<td>
											<form method="POST" action="">
												<div class="tableButton">
													<input name="id" type="hidden" value='.$row["id"].'>
                                                    <input name="staffID" type="hidden" value='.$staffID.'>    
													<input type="submit" name="removeBtn" value="Remove">
												</div>
											</form>
										</td>
									</tr>
									';
							};		
						?>
                        <tr>
                            <td></td>
                            <td>
                                <form method="POST" action="">
                                    <div class="tableButton">
                                        <input type="hidden" name="staffID" value="<?= $staffID?>">
                                        <input type="submit" name="assignBtn" value="Assign KPI">
                                    </div>
                                </form>
                            </td>
                            <td></td>
                        </tr>			  			  				 
					</table>
                </div>
            </div>
            <div class="lowerSplit">
                <div class="subtitle"><p>KPI Overview</p></div>
                <div class="overviewKPI">    
                    <table class="table">  		
						<tr class="rowHeading">
							<th class="col3">KPI List</th>	
							<th class="col3">List Of Staff</th>	
						</tr>   
						<?php
                            //Loops through the KPI List to query for staffs that are assigned to that KPI
                            if(mysqli_num_rows($resultKPI) > 0){
                                while($row = mysqli_fetch_assoc($resultKPI)){
                                    $kpiNum = $row['kpi_num'];
                                    $kpiDesc = $row['kpiDesc'];
                                    //SQL statements to retrieve staffID of staffs that are assigned to the respective KPI
                                    $sql = 
                                        "SELECT s.`name` AS staffName
                                        FROM staff_table s
                                        INNER JOIN (SELECT staff_id FROM staff_kpi_table WHERE `status` = 'Approved' AND kpi_num = '$kpiNum') q
                                        ON s.staff_id = q.staff_id
                                        ;";

                                    //Execute query
                                    $result = mysqli_query($conn, $sql);
                                    
                                    echo '<tr><td>'.$kpiNum." - ".$kpiDesc.'</td>';
                                    echo '<td>';
                                    $rowCount = 1;
                                    $totalRows = mysqli_num_rows($result);
                                    while($row1 = mysqli_fetch_assoc($result)){
                                        $staffName = $row1["staffName"];
                                        if($rowCount < $totalRows){
                                            echo $staffName.", ";
                                            $rowCount += 1;
                                        }else{
                                            echo $staffName;
                                        };
                                    };
                                    echo '</td>';
                                };  
                            };		
						?>			  				 
					</table>
                </div>
            </div>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>