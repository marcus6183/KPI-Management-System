<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Manage Key Performance Indicator</title>
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
				$result = $deleteMsg = "";

				//Get DB Connection
				$database = "staff_db";
				$conn = connectSQL(true, $database);

				//SQL statement to retrieve the KPI List
				$sql = "SELECT * FROM kpi_table ORDER BY kpi_num";
				//Execute the SQL query
				$result = mysqli_query($conn, $sql);
			
				if(isset($_POST["deleteKPI"])){
					$id=$_POST['id'];
					$kpiNum = $_POST["kpiNum"];
					//SQL statements that delete rows from kpi_table and staff_kpi_table
					$sqlKPI = "DELETE FROM kpi_table WHERE id='$id'";
					$sqlStaffKPI = "DELETE FROM staff_kpi_table WHERE kpi_num = '$kpiNum'";
					mysqli_query($conn, $sqlKPI);
					mysqli_query($conn, $sqlStaffKPI);
					//Loads the page again to show the latest of KPI List (after deletion)
					header("Location:manageKPIForm.php");
				};

				if(isset($_POST["editKPI"])){
					$id=$_POST['id'];
					//Redirects the user to UpdateKPI.php
					header("Location:UpdateKPI.php?id=$id");
				};

				//Close the connection
				mysqli_close($conn);
			};
			
		?>

		<div class="outerContainer">
			<div class="container">
				<div class="title"><p>Manage Key Performance Indicator</p></div>
				<div class="tableContainer">
					<table class="table">  		
						<tr class="rowHeading">
							<th class="col1">KPI List</th>	
							<th class="col2">Click to edit KPI</th>
							<th class="col2">Click to delete KPI</th>		
						</tr>
						<?php
							while ($row = mysqli_fetch_assoc($result)){ 
								echo '<tr><td>'.$row['kpi_num'] ." - ".$row['description'] .'</td>';
								echo '
									<td>
										<form method="POST" action="">
											<div class="tableButton">
												<input name="id" type="hidden" value='.$row["id"].'>   
												<input type="submit" name="editKPI" value="Edit KPI">
											</div>
										</form>
									</td>
									';
								echo '
										<td>
											<form method="POST" action="">
												<div class="tableButton">
													<input name="id" type="hidden" value='.$row["id"].'> 
													<input name="kpiNum" type="hidden" value='.$row["kpi_num"].'> 
													<input type="submit" name="deleteKPI" value="Delete KPI">
												</div>
											</form>
										</td>
									</tr>
									';
							}						
						?>					  			  				 
					</table>
				</div>
			</div>
			<?php include("../functions/footer.php")?>
		</div>
	</body>
</html>