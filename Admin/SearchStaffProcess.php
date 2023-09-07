<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Staff Process</title>
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
            //This page serves as search staff process page for staff INFO and staff KPI
            //Variable to check for usage type
            $usage = $_GET["usage"];

            $redirPage = "";

            //Check usage type
            if(strcmp($usage, "profile") == 0){
                $redirPage = "DisplayStaffInfo.php";
            }else{
                $redirPage = "UpdateStaffKPI.php";
            };

            if(isset($_GET["searchStaffName"])){
                $searchName = trimInput($_GET["searchStaffName"]);

                //Get DB Connection
                $database = "staff_db";
                $conn = connectSQL(true, $database);

                //SQL query to search for staff by name input from the user
                $sql = "SELECT `name` AS staffName, staff_id FROM staff_table WHERE `name` LIKE '%$searchName%'";

                //Execute the query
                $result = mysqli_query($conn, $sql);

                //Get the number of results
                $resultsCount = mysqli_num_rows($result);
            };
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Staff Information</p></div>
            <br>
            <div class="wrap">
                <span class="field">Search Results:</span>
                <p class="greyText"><?= $resultsCount?> results found</p>
            </div>
            <ul class="resultsList">
                <?php
                    //Loops through the results to display the staff name as hyperlink to DisplayStaffInfo.php as well as including their staffID in the URL, passing it to the next page for further processing
                    while($row = mysqli_fetch_assoc($result)){
                        $matchedName = $row["staffName"];
                        $matchedSID = $row["staff_id"];
                        echo "<li><a href='$redirPage?staffID=$matchedSID'>$matchedName</a></li>";
                    };

                    //Close the connection
                    mysqli_close($conn)
                ?>
            </ul>
        </div>
        <?php include("../functions/footer.php")?>
    </div>
</body>
</html>