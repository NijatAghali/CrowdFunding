<?php
include("connectDB.inc.php");

session_start();
error_reporting();
    $check="SELECT idUser FROM projects WHERE idProject=".$_GET['idProject']."";
    $row=$mysqli->query($check)->fetch_assoc();
    if($_SESSION['idUser']!=$row['idUser']){
      header("Location: index.php");
    }

    $query = "SELECT * FROM projects_investors, users WHERE projects_investors.idProject = ".$_GET['idProject']." AND users.idUser=projects_investors.idUser";
    $project = "SELECT * FROM projects, projects_investors where projects_investors.idProject = ".$_GET['idProject']."";
    $sql = "SELECT projects.projectName, projects.requestedFund,  SUM(projects_investors.investmentFund) as invested FROM projects_investors, 
		projects WHERE projects.idProject = ".$_GET['idProject']." AND projects_investors.idProject = projects.idProject";

    $result = $mysqli->query($query);
    $Prj = $mysqli->query($project);
    $result2 = $mysqli->query($sql);

    $res = $result->fetch_assoc();
    $projects = $Prj->fetch_assoc();
    $res2 = $result2->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>    
    <link rel="stylesheet" href="./css/graph.css">
    <link rel="stylesheet" href="./css/details.css">
</head>

<body> 
  <div class="bode">
    <div class="table-wrapper ">
        <table class="fl-table table-hover">  
          <thead>
            <tr>
              <th>Firstname</th>
              <th>Lastname</th>
              <th>Investment Fund</th>
              <th>Investment Date</th>
            </tr>
          </thead>

          <tbody>

            <?php
              $requested = $res2['requestedFund'];
              $invested = $res2['invested'];
              $remaining = $requested - $invested;
              $graph = 100 - 100 * ($invested / $requested );

              echo "<div class=\"header\"><h1>",$projects['projectName'],"</h1>";
              echo "<h6 class=\"head\", id=\"desc\">",$projects['projectDescription'],"</h6>";
              echo "<h6 class=\"head\">","Start Date - ",$projects['projectStartDate'],"</h6>";
              echo "<h6 class=\"head\">","End Date - ", $projects['projectEndDate'],"</h6>";
              echo "<h6 class=\"head\">","Total Requested - ", $requested,"</h6>";
              echo "<h6 class=\"head\">","Total Invested - ", $invested,"</h6>";
              echo "<h6 class=\"head\">","Remaining - ", $remaining,"</h6></div>";
              echo "<div class='out'><div class='in' style='margin-right: $graph%'><div class='invested'>$invested out of $requested</div></div></div>";

                while($res = $result->fetch_assoc()){
                echo 
                      "<tr>
                        <td>".$res['firstname']."</td>
                        <td>".$res['lastname']."</td>
                        <td>".$res['investmentFund']."</td>
                        <td>".$res['investmentDate']."</td>
                        <td></td>
                      </tr>";  
                    }      
                    ?>
            </tbody>
          </table>
      </div>
        <div class="link"><a class="BackHome" href="./index.php">Back to Homepage</a></div>
  </div>

</body>
</html>