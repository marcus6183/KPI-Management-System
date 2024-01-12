<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Staff Form</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <?php
    //Include the functions.php file so that it can access the functions defined inside
    include("../functions/functions.php");

    session_start();
    if ($_SESSION["status"] == 0 or $_SESSION["type"] != "admin") {
        //Redirects the user back to the login page if not logged in/is not an admin account
        header("Location:../login.php");
    } else {
        //This page serves as search staff page for staff INFO and staff KPI
        //Variable to check for usage type
        $usage = $_GET["usage"];

        //Variable that keeps track of the validation
        $validatePassAll = true;

        //Initialize variables that are to be printed in the html to prevent Warning from showing
        $errorSearchStaff = $pageTitle = "";

        //Check usage type
        if (strcmp($usage, "profile") == 0) {
            $pageTitle = "Search Staff Profile";
        } else {
            $pageTitle = "Update Staff KPI";
        };

        if (($_SERVER["REQUEST_METHOD"] == "GET") and isset($_GET["searchStaffButton"])) {
            //Sanitize the input
            $searchStaffName = trimInput($_GET["searchStaffName"]);

            //Vaidate the input (name)
            $validateName = validateName($searchStaffName);
            if (!$validateName[1]) {
                $errorSearchStaff = $validateName[0];
                $validatePassAll = false;
            };

            if ($validatePassAll) {
                //get the query string data using $_SERVER['QUERY_STRING'] and then append it to the end of the path of the next page
                $query = $_SERVER['QUERY_STRING'];
                //Redirect to the searchEmployeeProcess page using the header() function
                header("Location:SearchStaffProcess.php?$query");
                exit;
            };
        };
    };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title">
                <p><?= $pageTitle ?></p>
            </div>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <div class="wrap">
                    <span class="field">Staff Name:</span>
                    <span class="errorText"><?= $errorSearchStaff ?></span>
                </div>
                <input type="text" name="searchStaffName" value="<?php if (!$validatePassAll) {
                                                                        echo isset($_GET["searchStaffName"]) ? $_GET["searchStaffName"] : '';
                                                                    } ?>">
                <br><br>
                <div class="button">
                    <input type="submit" value="Search" name="searchStaffButton">
                    <input type="hidden" value="<?= $usage ?>" name="usage">
                </div>
            </form>
        </div>
        <?php include("../functions/footer.php") ?>
    </div>
</body>

</html>