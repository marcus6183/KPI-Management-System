<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPI Report</title>
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
            //Get DB Connection
            $database = "staff_db";
            $conn = connectSQL(true, $database);

            //Retrieve all KPI's from kpi_table
            $sqlKPI = "SELECT kpi_num, `description` AS kpiDesc FROM kpi_table";

            //Execute the SQL queries
            $resultKPI = mysqli_query($conn, $sqlKPI);
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>KPI Overview</p></div>
            <div class="reportKPI">
                <table class="table">
                    <tr class="rowHeading">
                        <th class="col1">KPI List</th>
                        <th class="col2">List Of Staff</th>
                        <th class="col2">Total Staff</th>
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
                                echo '<td>'.$totalRows.'</td>';
                            };
                        };
                        //Close the connection
                        mysqli_close($conn);
                    ?>	
                </table>
            </div>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>