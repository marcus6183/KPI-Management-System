<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Update KPI</title>
		<link rel="stylesheet" href="../style.css">
	</head>
	<body>

	<?php
		//Include the functions.php file so that it can access the functions defined inside
		include("../functions/functions.php");

		session_start();
        if($_SESSION["status"] == 0 OR $_SESSION["type"] != "admin"){
            //Redirects the user back to the login page if not logged in/is not an admin account
            header("Location:../login.php");
        }else{
			//Declare empty variables
			$editKPI = $editDesc = $id = "";
			$errorNum = $errorDesc = "";
			$result = "";

			$updateClicked = false;

			if(!isset($_POST["editKPIBtn"])){
				if(isset($_GET['id'])){
					$id = $_GET['id'];
					// retrieve KPI from the table
					$row = retrieveKPI($id);

					//Assign KPI info retrieved to the value variables
					$valKPI = $row['kpi_num'];
					$valDesc = $row['description'];
				};
			}else{
				$updateClicked = true;
				$validatePassAll = true;

				//Validate KPI number
				if(empty($_POST["editKPI"])){
					$validatePassAll = false;
					$errorNum = "KPI number is required";
				}else{
					$editKPI = trimInput($_POST["editKPI"]);
					if(!is_numeric($editKPI)){
						$validatePassAll = false;
						$errorNum = "Invalid input. Please enter a number.";
					};
				};

				//Validate Description
				if(empty($_POST["editDesc"])){
					$validatePassAll = false;
					$errorDesc = "KPI description is required";
				}else{
					$editDesc = trimInput($_POST["editDesc"]);    
				};

				if($validatePassAll){
					$id = $_POST['id'];
					editKPI($editKPI,$editDesc,$id);
					header("Location:manageKPIForm.php");
				};
			};
		};
	?>
	<div class="outerContainer">
		<div class="container">
			<div class="title"><p>Edit KPI</p></div>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="post" >
				<p><span class="errorText">* required field</span></p>
				<br>
				<div class="wrap">
					<span class="field">KPI No.: </span>
					<span class="errorText">*<?= $errorNum?></span>
				</div>
				<input type="text" name="editKPI" value="<?php if(!$updateClicked){echo $valKPI;}else{if(!$validatePassAll){echo isset($_POST["editKPI"]) ? $_POST["editKPI"] : '';}}?>">
				<br><br>
				<div class="wrap">
					<span class="field">Description: </span>
					<span class="errorText">*<?= $errorDesc?></span>
				</div>
				<textarea name="editDesc" rows="3"><?php if(!$updateClicked){echo $valDesc;}else{if(!$validatePassAll){echo isset($_POST["editDesc"]) ? $_POST["editDesc"] : '';}}?></textarea>
				<br><br>
				<div class="button">
					<input type="submit" value="Save Update" name="editKPIBtn">
					<input type="hidden" value="<?php echo $id?>" name="id">
				</div>
			</form>
		</div>
		<?php include("../functions/footer.php")?>
	</div>
</body>
</html>