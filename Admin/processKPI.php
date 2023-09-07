<?php
  //Include the functions.php file so that it can access the functions defined inside
  include("../functions/functions.php");

  session_start();
  if($_SESSION["status"] == 0 OR $_SESSION["type"] != "admin"){
      //Redirects the user back to the login page if not logged in/is not an admin account
      header("Location:../login.php");
  }else{
    $kpiNum = $kpiDesc = "";
    $errorNum = $errorDesc = "";
    $result = "";
  
    if(isset($_POST["addKPIBtn"])){
      $validatePassAll = true;
      
      if ($_POST["addKPIBtn"]=="Add KPI") {
        //Validate KPI
        if (empty($_POST["addKPI"])){
          $validatePassAll = false;
          $errorNum = "KPI number is required";
        }else{
          $kpiNum = trimInput($_POST["addKPI"]);
          if (!is_numeric($kpiNum)){
            $validatePassAll = false;
            $errorNum = "Invalid input. Please enter a number.";
          };
        };
  
        //Validate Description
        if (empty($_POST["addKPIDesc"])){
          $validatePassAll = false;
          $errorDesc = "KPI description is required";
        }else{
          $kpiDesc = trimInput($_POST["addKPIDesc"]);    
        };
  
        if($validatePassAll){
          $result = addKPI($kpiNum, $kpiDesc);
        };
      };
    };
  };
?>