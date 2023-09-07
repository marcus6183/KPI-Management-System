<?php
    //Function that checks if the input is empty
    function checkEmpty($inputVar){
        if(empty($inputVar)){
            //Returns false if the input is empty
            return false;
        }else{
            //Returns true if the input is not empty
            return true;
        };
    };

    //Function that cleans the input data
    function trimInput($inputVar){
        //Removes whitespaces from start and end
        $inputVar = trim($inputVar);
        //Removes backslashes
        $inputVar = stripslashes($inputVar);
        //Converts specialcharacters/predefined characters into HTML entities
        $inputVar = htmlspecialchars($inputVar);
        //Returns the trimmed input data
        return $inputVar;
    };

    //Function that validates name
    function validateName($name){
        //Initialize the array
        $validateArray = array("", true);
        //Check Empty
        if(checkEmpty($name) == false){
            $validateArray[0] = "Name is required";
            $validateArray[1] = false;
            return $validateArray;
        }else{
            //Checks for string length using strlen() function
            //The string is trimmed before checking so that accidental whitespace input are not counted
            if(strlen($name) > 20){
                $validateArray[0] = "Name must not exceed 20 characters";
                $validateArray[1] = false;
                return $validateArray;
            }else{
                //Validates name using preg_match
                //The Regex here ensures that the input can only have alphas and whitespaces
                if(!preg_match("/^[a-zA-Z ]*$/", $name)){
                    $validateArray[0] = "Only alphas and spaces are allowed";
                    $validateArray[1] = false;
                    return $validateArray;
                };
            };
        };
        return $validateArray;
    };

    //Function that validates staff ID
    function validateStaffID($staffID, $conn){
        //Initialize the array
        $validateArray = array("", true);
        //Check Empty
        if(checkEmpty($staffID) == false){
            $validateArray[0] = "Staff ID is required";
            $validateArray[1] = false;
            return $validateArray;
        }else{
            //Check SS and numbers behind using preg_match
            //The Regex here ensures that the input starts with SS along with 3-4 numbers only
            if(!preg_match("/^SS[0-9]{3,4}$/", $staffID)){
                $validateArray[0] = "Staff ID must start with SS and 3-4 numbers";
                $validateArray[1] = false;
                return $validateArray;
            }else{
                //SQL query to search for StaffID
                $sql = "SELECT staff_id FROM staff_table WHERE staff_id = '$staffID';";

                //Execute the query
                $result = mysqli_query($conn, $sql);

                //Checks the result
                if(mysqli_num_rows($result) != 0){
                    //If the result is not 0, means that the ID is already in use
                    $validateArray[0] = "Staff ID already exists, please enter another one";
                    $validateArray[1] = false;
                    return $validateArray;
                };
            };
        };
        return $validateArray;
    };

    //Function that validates email
    function validateEmail($email){
        //Initialize the array
        $validateArray = array("", true);
        //Check Empty
        if(checkEmpty($email) == false){
            $validateArray[0] = "Email is required";
            $validateArray[1] = false;
            return $validateArray;
        }else{
            //Validate email format using FILTER_VALIDATE_EMAIL
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $validateArray[0] = "Please enter a valid email";
                $validateArray[1] = false;
                return $validateArray;
            }else{
                //Checks the domain of the email address
                //Extracts the substring of the email input after the "@" and compares its value
                $domain = substr($email, strpos($email, "@")+1);
                if($domain != "swinburne.edu.my"){
                    $validateArray[0] = "Domain must be of swinburne.edu.my";
                    $validateArray[1] = false;
                    return $validateArray;
                };
            };
        };
        return $validateArray;
    };

    //Function that checks whether email exists
    function checkExistsEmail($email, $conn){
        //Initialize the array
        $validateArray = array("", true);
        //SQL query to search for existing email addresses
        $sql = "SELECT * FROM staff_table WHERE email = '$email'";

        //Execute the query
        $result = mysqli_query($conn, $sql);

        //Checks the result
        if(mysqli_num_rows($result) != 0){
            //If the result is not 0, means that the email is already in use
            $validateArray[0] = "Email is already registered with an account, please enter another one";
            $validateArray[1] = false;
            return $validateArray;
        };
        return $validateArray;
    };

    //Function that retrieves the connection to the MySQL server
    function connectSQL($setDB, $DB){
        $servername = "localhost";
        $username = "root";
        $password = "";
        
        //Create connection
        if($setDB){
            //Connect with DB
            $conn = mysqli_connect($servername, $username, $password, $DB);
        }else{
            //Connect without DB
            $conn = mysqli_connect($servername, $username, $password);
        };
        
        //Check connection
        if(!$conn){
            die("Connection failed! Reason: ".mysqli_connect_error());
        };
        return $conn;
    };

    //Function that updates the kpi_table
    function editKPI($editKPI, $editDesc, $id){
        //Get DB Connection
        $database = "staff_db";
        $conn = connectSQL(true, $database);

        //SQL statement to update the KPI in the KPI table
        $sql = "UPDATE kpi_table SET kpi_num='$editKPI', `description`='$editDesc' WHERE id='$id'";
        
        //Execute the query
        mysqli_query($conn, $sql);

        //Close the connection		
        mysqli_close($conn);
    };

    //Function that retrieves KPI info from the kpi_table
    function retrieveKPI($id){
        //Get DB Connection
        $database = "staff_db";
        $conn = connectSQL(true, $database);

        //SQL statement to retrieve KPI tuple by KPI ID
        $sql = "SELECT * FROM kpi_table WHERE id='$id'";
        
        //Execute the SQL statement
        if(mysqli_query($conn, $sql)){
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);  				
        };

        //Close the connection
        mysqli_close($conn);

        return $row;
    };

    //Function that adds new a new record into the kpi_table
    function addKPI($num,$desc){
        $result="";
    
        //Get DB Connection
        $database = "staff_db";
        $conn = connectSQL(true, $database);
    
        //Check connection
        if($conn === false){
          $result="ERROR: Could not connect.";
          die("ERROR: Could not connect. " . mysqli_connect_error());
        };
    
        //SQL statement that ensures that there is no existing KPI number before inserting into the table
        $sql = "INSERT INTO kpi_table (kpi_num, `description`) SELECT * FROM (SELECT '$num', '$desc') AS tmp WHERE NOT EXISTS(SELECT kpi_num FROM kpi_table WHERE kpi_num = '$num') LIMIT 1";  
      
        //Execute the INSERT statement
        mysqli_query($conn, $sql);  
    
        if(mysqli_affected_rows($conn)>0){
          $result="Record inserted successfully.";
        }else{
          $result="KPI number already exists. Please use a different number";
        };
        return $result;
      };
?>