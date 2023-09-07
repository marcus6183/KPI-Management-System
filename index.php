<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        //Include the functions.php file so that it can access the functions defined inside
        include("functions/functions.php");

        //Get connection to localhost MySQL server
        $database = "staff_db";
        //Without connecting to the DB (So error won't occur when database doesn't exists)
        $conn = connectSQL(false, $database);

        //SQL script to create database (if not exists)
        $sql = "CREATE DATABASE IF NOT EXISTS $database";
        if(!mysqli_query($conn, $sql)){
            //Use die if sql failed to execute
            die("Unable to create database! Reason: ".mysqli_error($conn));
        };

        //Select DB
        mysqli_select_db($conn, $database);

        //Creates the tables (if not exists)
        //staff_table
        $sqlStaff = 
            "CREATE TABLE IF NOT EXISTS staff_table (
                id INT(4) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                staff_id VARCHAR(6) NOT NULL,
                email VARCHAR(50) NOT NULL,
                `name` VARCHAR(50) NOT NULL,
                gender VARCHAR(6) NOT NULL,
                school VARCHAR(5) NOT NULL
            );";
        //KPI Table
        $sqlKPI = 
            "CREATE TABLE IF NOT EXISTS kpi_table (
                id INT(4) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                kpi_num INT NOT NULL,
                `description` VARCHAR(150) NOT NULL
            );";
        //Staff_KPI Table
        $sqlStaffKPI = 
            "CREATE TABLE IF NOT EXISTS staff_kpi_table (
                id INT(4) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                staff_id VARCHAR(6) NOT NULL,
                kpi_num INT NOT NULL,
                `status` VARCHAR(12) NOT NULL
            );";
        //Account Table
        $sqlAcc =
            "CREATE TABLE IF NOT EXISTS account_table (
                id INT(4) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                staff_id VARCHAR(6) NOT NULL,
                `name` VARCHAR(50) NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `type` VARCHAR(5) NOT NULL,
                email VARCHAR(50) NOT NULL
            );";

        //For extension task (availability)
        $sqlAval =
            "CREATE TABLE IF NOT EXISTS aval_table (
                id INT(1) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                `type` VARCHAR(20) NOT NULL,
                `status` INT(1) NOT NULL
            );";
        
        //Insert default status
        $sqlAval1 = "INSERT INTO aval_table VALUES (1, 'availability', 1) ON DUPLICATE KEY UPDATE `type` = 'availability'";

        //Create admin account (If not exist/password unhashed)
        $hashPW = hash('sha256', "admin");

        $sqlAdmin = "INSERT INTO account_table VALUES (1, 'SS001', 'admin', '$hashPW', 'admin', 'admin@swinburne.edu.my') ON DUPLICATE KEY UPDATE `password` = '$hashPW'";

        //Create sample staff in staff_table
        $sqlSample1 = "INSERT INTO staff_table VALUES (1, 'SS001', 'admin@swinburne.edu.my', 'admin', 'Male', 'SFS') ON DUPLICATE KEY UPDATE gender = 'Male'";

        //Execute the queries
        mysqli_query($conn, $sqlStaff);
        mysqli_query($conn, $sqlKPI);
        mysqli_query($conn, $sqlAcc);
        mysqli_query($conn, $sqlStaffKPI);
        mysqli_query($conn, $sqlAval);
        mysqli_query($conn, $sqlAval1);
        mysqli_query($conn, $sqlAdmin);
        mysqli_query($conn, $sqlSample1);

        //Close the connection
        mysqli_close($conn);
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>KPI Assignment System</p></div>
            <div class="indexContent">
                <div class="gridContainer">
                    <div class="photo">
                        <img src="./images/Marcus.jpg" alt="./images/imgError.png">
                    </div>
                    <div class="myDetails">
                        <div>
                            <div class="wrap">
                                <span class="field">Name: </span>
                                <p>Marcus Wong En Hao</p>
                            </div>
                            <div class="wrap">
                                <span class="field">Student ID: </span>
                                <p>102762658</p>
                            </div>
                            <div class="wrap">
                                <span class="field">Email: </span>
                                <a href="mailto:102762658@students.swinburne.edu.my">102762658@students.swinburne.edu.my</a>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="longtext">I declare that this assignment is my individual work. I have not work collaboratively nor have I copied from any other student's work or from any other source. I have not engaged another party to complete this assignment. I am aware of the University’s policy with regards to plagiarism. I have not allowed, and will not allow, anyone to copy my work with the intention of passing it off as his or her own work.</p>
            </div>
        </div>
        <div class="container" id="footer">
            <div class="footer">
                <ul class="footer-link">
                    <li>
                        <a href="login.php">Login</a>
                    </li>
                    <li>
                        <a href="about.php">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>