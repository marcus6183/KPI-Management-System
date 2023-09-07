<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        //Include the functions.php file so that it can access the functions defined inside
        include("functions/functions.php");

        session_start();
        //Destroys session if set
        if (isset($_SESSION['email'])) { 
            session_unset();
            session_destroy(); 
        };

        //Initialize variables
        $loginName = $loginPW = "";
        $loginError = $errorLoginName = $errorLoginPW = "";

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //Varaible that keeps track of empty check
            $validateEmpty = true;

            //Check empty - login name
            if(checkEmpty($_POST["loginName"]) == false){
                $errorLoginName = "Login name is required";
                $validateEmpty = false;
            };
            //Check empty - login password
            if(checkEmpty($_POST["loginPW"]) == false){
                $errorLoginPW = "Password is required";
                $validateEmpty = false;
            };

            if($validateEmpty){
                //Sanitize inputs
                $loginName = trimInput($_POST["loginName"]);
                $loginPW = trimInput($_POST["loginPW"]);

                //Get DB connection
                $database = "staff_db";
                $conn = connectSQL(true, $database);

                //SQL query to retrieve user login data from DB
                $sql = "SELECT staff_id, `name`, `password`, `type`, email FROM account_table WHERE `name` = '$loginName'";

                //Execute the query
                $result = mysqli_query($conn, $sql);

                //Check query result
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);

                    //Hash the input password
                    $hashPW = hash('sha256', $loginPW);
                    //Compare input password with  password from DB
                    if(strcmp($hashPW, $row["password"]) == 0){
                        session_start();
                        //Declare session variables
                        $_SESSION["status"] = 1; //0 = not logged in; 1 = logged in
                        $_SESSION["type"] = $row["type"];
                        $_SESSION["email"] = $row["email"];
                        $_SESSION["staffID"] = $row["staff_id"];

                        //Redirect to main menu page
                        header("Location:MainMenu.php");
                    }else{
                        //Display error message
                        $loginError = "Invalid password";
                    };
                }else{
                    //Display error message
                    $loginError = "Invalid login name";
                };

                //Close the connection
                mysqli_close($conn);
            };
        };
    ?>

    <div class="outerContainer">
        <div class="container">
            <div class="loginSplit">
                <div class="split1">
                    <div class="title"><p>KPI Management System</p></div>
                    <div class="loginImage"><img src="./images/top_image.png" alt="./images/imgError.png"></div>
                </div>
                <div class="split2">
                    <div class="title"><p>Login</p></div>
                    <div class="split2Inner">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="wrap">
                                <span class="field">Login Name:</span>
                                <span class="errorText">*</span>
                            </div>
                            <input type="text" name="loginName">
                            <span class="errorText"><?= $errorLoginName?></span>
                            <div class="wrap">
                                <span class="field">Password:</span>
                                <span class="errorText">*</span>
                            </div>
                            <input type="password" name="loginPW">
                            <span class="errorText"><?= $errorLoginPW?></span>
                            <div class="button">
                                <input type="submit" value="Login" name="loginBtn">
                                <input type="reset" value="Clear">
                            </div>
                            <div class="alert">
                                <p><?= $loginError?></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>