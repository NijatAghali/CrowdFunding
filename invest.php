<?php
        include("connectDB.inc.php");
		session_start();

        // Performing insert query execution
		$sql2 = "SELECT projects_investors.idUser, projects_investors.idProject from projects_investors, projects WHERE projects.idProject = ".$_GET['idProject']." AND projects_investors.idProject = projects.idProject";
		$sql3 = "SELECT projects.projectName, projects.requestedFund, projects.projectEndDate, SUM(projects_investors.investmentFund) as invested FROM projects_investors, 
		projects WHERE projects.idProject = ".$_GET['idProject']." AND projects_investors.idProject = projects.idProject";

		$result2 = $mysqli->query($sql2);
		$result3 = $mysqli->query($sql3);

		$res2 = $result2->fetch_assoc();		
		$res3 = $result3->fetch_assoc();		
		?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Invest</title>
	<link rel="stylesheet" href="./css/inves.css">
	<link rel="stylesheet" href="./css/graph.css">
</head>
<body>
		<?php
			while($res2 = $result2->fetch_assoc()){
				
				if($_SESSION['idUser']==($res2['idUser']) AND $_GET['idProject'] == $res2['idProject'])
				{
					echo "<h1 class='info'>You have already invested to this project.</h1>";
					session_abort();
					include("info.php");
					
					// header( "refresh:5;index.php" );
					// echo "<h2>You will be redirected to Homepage in 5 seconds. Please choose the project that you haven't invested yet</h2>	";
					die();	
				}}
				
			$res2 = $result2->fetch_assoc();
			$requested = $res3['requestedFund'];
			$invested = $res3['invested'];
			$remaining = $requested - $invested;
			$graph = 100 - 100 * ($invested / $requested );

			if($remaining == 0){
				header( "refresh:5;index.php" );
					
				echo "<h1>Investment for this project is Full!</h1>";
				echo "<h2>You will be redirected to Homepage in 5 seconds. Please choose the project that you haven't invested yet</h2>	";
				die();	
			}	
			else{
				$date = $res3['projectEndDate'];
				$now = date("Y-m-d");

				if($date<$now){
					header( "refresh:5;index.php" );
					
					echo "<h1>Deadline for this Project had Expired!</h1>";
					echo "<h2>You will be redirected to Homepage in 5 seconds. Please choose the project that deadline hasn't expired yet</h2>	";
				die();	
				}
					if(isset($_POST['investmentFund'])){
						if(isset($_POST['date'])){
							$inputDate = $_POST['date'];
							
							 if($now<$date){
								if($date>$inputDate){
									$investmentFund	 = $_POST['investmentFund'];
									if($investmentFund>0){
										if($investmentFund <= $remaining){
											$idUser = $_SESSION['idUser'];
											$idProject = $_GET['idProject'];
				
											$query = "INSERT INTO projects_investors (idUser, idProject, investmentFund, investmentDate) VALUES ($idUser, $idProject, $investmentFund, '$inputDate')";
											$mysqliResult = $mysqli->query($query);
											header("location:index.php");
										}else echo "<div class='error'>Invested money cannot be higher than requiered</div>";		
									}else echo "<div class='error'>Invested money cannot be less than or eqaul to zero</div>";
								}else echo "<div class='error'>You cannot invest after the Deadline </div>";
							 }else echo "<div class='error'>Project deadline had expired</div>";
						}else echo "<div class='error'></div>";
					}
				}
			//}
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
            	<span class="projectName"><?php echo $res3['projectName']?></span>
        	</div>
          	<a href="./index.php" class="app-home">Back To Homepage</a>
			</div>
			<div class="screen-body-item">
				<div class="app-form">
					<div class="app-form-group">
					<h3 class="projectName">Invested Fund : <?php echo $invested ?> </h3>
					<h3 class="projectName">Remaining Money : <?php echo $remaining ?> </h3>
				</div>
				<form action="" method="POST">
					<div class="app-form-group">
						<input type="number" name="investmentFund" class="app-form-control" placeholder="Invest Money" requiered>
					</div>
					<div class="app-form-group">
						<input type="date" name="date" class="app-form-control" requiered>
					<?php
						echo "<div class='out' id='invest'><div class='in' style='margin-right: $graph%'></div></div>";
					?>
					</div>
					</div>
					<div class="app-form-group buttons">
						<a href="./index.php" class="app-form-button" id="cancel">CANCEL</a>
						<input type="submit" class="app-form-button" name="SUBMIT" value="SUBMIT">
				</form>
			</div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
</body>
</html>