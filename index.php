<?php
include("connectDB.inc.php");
include("class.inc.php");

session_start();
if(isset($_SESSION['idUser']))

error_reporting();

$query = "SELECT * FROM projects, users WHERE projects.idUser=users.idUser";
$query2 = "SELECT projects.idUser , projects.idProject FROM projects";

$projects = array();
$result2 = $mysqli->query($query2);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $mysqliResult = $mysqli->query($query);
    while($var=$mysqliResult->fetch_assoc()){
        $projects[$var['idUser']]=new homepage($var['projectName'],$var['firstname'],$var['lastname'],$var['projectDescription'],$var['projectEndDate'],$var['requestedFund'],$var['idUser'],$var['idProject']);
    }
}
catch (Exception $e) { 
    echo "MySQLi Error Code: " . $e->getCode() . "<br />";
    echo "Exception Msg: " . $e->getMessage();
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="css/index.css">

</head>

<body>
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

    } else {
        header("location:login.php");
    }
    ?>

        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top ">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">CrowdFunding</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                    <div class="collapse navbar-collapse" id="navbarScroll">
                        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                            </li><a class="nav-link" href="extra/profile.php">Profile(Extra)</a>
                            </li><a class="nav-link" href="extra/projects.php">Projects(Extra)</a>
                            </li>
                            <li class="nav-item">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">See more</a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                        <li><a class="dropdown-item" href="#">Comments</a></li>
                                        <li><a class="dropdown-item" href="#">About</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#">Contact</a></li>
                                    </ul>
                            </li>
                        </ul>
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                        <a class="btn btn-primary" id="out" href="logout.php" role="button">Logout</a>
                    </div>
            </div>
        </nav>
        
    <br><br><br><br>   

    <?php
        $query3 = "SELECT users.firstname, users.lastname FROM users WHERE ".$_SESSION['idUser']."= users.idUser";
        $result3 = $mysqli->query($query3); 
        $res3 = $result3->fetch_assoc();
    ?>

    <h2>Welcome, <?php echo $res3['firstname'], " ", $res3['lastname'], " !"; ?></h2><br>
        <table>
        <tbody>
            <?php               
            $res2 = $result2->fetch_assoc();

            foreach ($projects as $keyProject => $valueProject) {
                $sql = "SELECT projects_investors.idUser, projects_investors.idProject from projects_investors, projects WHERE projects.idProject = $valueProject->prjId AND projects_investors.idProject = projects.idProject";
                $result3 = $mysqli->query($sql);
                $res3 = $result3->fetch_assoc();

                echo 
                 "<table>
                  <tr>";?>
                    <div class="tab">
                        <h3><?php echo $valueProject->prj_name, "<br>";?></h3>
                        <h5><?php echo "Project Owner : ", $valueProject->firstname, " ", $valueProject->lastname;?></h5>
                        <div class=""><?php echo $valueProject->prj_description;?></div> 
                        <div class=""><?php echo "Project End Date : ",$valueProject->prj_endDate;?></div> 
                        <p class="fund"><?php echo "Requested Fund : ",$valueProject->requestedFund;?></p> 
                      
                    <?php if($res2['idUser'] == $_SESSION['idUser']){
                        echo "<a class=\"detail\" href='details.php?idProject=$valueProject->prjId'> Details </a><br><br>";     
                    }
                    else{
                        if($_SESSION['idUser']==$res3['idUser']){
                            // AND $valueProject->prjId == $res3['idProject']
                            echo "<a class=\"info\" href='info.php?idProject=$valueProject->prjId'> See Info </a><br><br>";     
                        }
                        else{
                            echo "<a class=\"invest\" href='invest.php?idProject=$valueProject->prjId'> Invest </a><br><br>";     
                        }
                    }                   
                    "</div>";
                    "</tr>";
                    $res2 = $result2->fetch_assoc();
                }
                ?>
        </tbody>
    </table>
</body>
</html>