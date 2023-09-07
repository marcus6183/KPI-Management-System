<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Confirm</title>
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
            if(isset($_POST["deleteAP"])){
                //Redirects user to SearchStaffForm.php
                header("Location:SearchStaffForm.php");
            }elseif(isset($_POST["home"])){
                //Redirects user to MainMenu.php
                header("Location:../MainMenu.php");
            };
        };
    ?>
    <div class="outerContainer">
        <div class="container">
            <div class="title"><p>Delete Staff Profile</p></div>
            <div class="deleteContainer">
                <span class="errorText">Record is deleted successfully</span>
            </div>
            <form action="" method="POST">
                <div class="button">
                    <input type="submit" value="Delete Another Profile" name="deleteAP">
                    <input type="submit" value="Home" name="home">
                </div>
            </form>
        </div>
    </div>
</body>
</html>