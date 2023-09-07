<!DOCTYPE html>
<html>
	<head>
		<?php include_once ("processKPI.php"); ?>
		<title>Add KPI</title>
		<link rel="stylesheet" href="../style.css">
	</head>
	<body>
		<div class="outerContainer">
			<div class="container">
				<div class="title"><p>Add KPI</p></div>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<p><span class="errorText">* required field</span></p>
					<br>
					<div class="wrap">
						<span class="field">KPI No.: </span>
						<span class="errorText">*<?= $errorNum?></span>
					</div>
					<input type="text" name="addKPI" value="<?php if(!$validatePassAll){echo isset($_POST["addKPI"]) ? $_POST["addKPI"] : '';} ?>">
					<br><br>
					<div class="wrap">
						<span class="field">Description: </span>
						<span class="errorText">*<?= $errorDesc?></span>
					</div>
					<textarea name="addKPIDesc" rows="3"><?php if(!$validatePassAll){echo isset($_POST["addKPIDesc"]) ? $_POST["addKPIDesc"] : '';} ?></textarea>
					<br><br>
					<div class="button">
						<input type="submit" value="Add KPI" name="addKPIBtn">
					</div>
					<div class="alert">
						<p><?= $result?></p>
					</div>
				</form>
			</div>
			<?php include("../functions/footer.php")?>
		</div>
	</body>
</html>