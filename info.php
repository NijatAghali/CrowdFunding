<?php
        include("connectDB.inc.php");
		session_start();

        // Performing insert query execution
		$sql = "SELECT projects_investors.investmentFund, projects_investors.investmentDate FROM projects_investors, projects WHERE projects.idProject = ".$_GET['idProject']." AND projects_investors.idProject = projects.idProject AND projects_investors.idUser = ".$_SESSION['idUser']." ";
		$sql2 = "SELECT projects_investors.idUser, projects_investors.idProject, projects.projectEndDate, projects.projectDescription from projects_investors, projects WHERE projects.idProject = ".$_GET['idProject']." AND projects_investors.idProject = projects.idProject";
		$sql3 = "SELECT projects.projectName, projects.requestedFund,  SUM(projects_investors.investmentFund) as invested FROM projects_investors, 
		projects WHERE projects.idProject = ".$_GET['idProject']." AND projects_investors.idProject = projects.idProject";

		$result = $mysqli->query($sql);
		$result2 = $mysqli->query($sql2);
		$result3 = $mysqli->query($sql3);

		$res = $result->fetch_assoc();		
		$res2 = $result2->fetch_assoc();		
		$res3 = $result3->fetch_assoc();		
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Info</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="./css/graph.css">
	<link rel="stylesheet" href="./css/info.css">
</head>
<body>

<?php
	$requested = $res3['requestedFund'];
	$invested = $res3['invested'];
	$remaining = $requested - $invested;
	$graph = 100 - 100 * ($invested / $requested );

?>
	<div class="background">
		<div class="container">
			<div class="screen">
			<div class="screen-header">
				<div class="screen-header-left">
				<div class="screen-header-button close"></div>
				<div class="screen-header-button maximize"></div>
				<div class="screen-header-button minimize"></div>
				</div>
				<div class="screen-header-right">
				<div class="screen-header-ellipsis"></div>
				<div class="screen-header-ellipsis"></div>
				<div class="screen-header-ellipsis"></div>
				</div> 
			</div>
			<div class="screen-body">
				<div class="screen-body-item left">
					<div class="app-title">
						<span><?php echo $res3['projectName']?></span>
					</div>
					<div class="description"><?php echo $res2['projectDescription']; ?></div>
					</div>
					
					<div class="screen-body-item">
						<div class="app-form">
							<div class="app-results-group">
								<h3 class="projectName">Total Requested : <?php echo $requested ?> </h3>
								<h3 class="projectName">Invested Fund : <?php echo $invested ?> </h3>
								<h3 class="projectName">Remaining Money : <?php echo $remaining ?> </h3>
								<hr class="line">
								<h3 class="projectName">Amount you invested :  <?php echo $res['investmentFund'] ?> </h3>
								<h3 class="projectName">You invested in : <br> <?php echo $res['investmentDate'] ?> </h3>
								<?php
									echo "<div class='out' id='invest'><div class='in'  style='margin-right: $graph%'></div></div>";
								?>
							</div>
						</div>
					</div>
				</div>
					<div class="link"><a class="app-home" href="index.php">Back to Homepage</a></div>
			</div>
			</div>
			</div>
		</div>
	</div>
</body>
</html>