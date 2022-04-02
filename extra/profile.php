<?php
include("../connectDB.inc.php");
session_start();
error_reporting();

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            
    } else {
        header("location:../login.php");}

    $query = "SELECT * from users WHERE users.idUser = ".$_SESSION['idUser']." ";
    $result = $mysqli->query($query);
    $res = $result->fetch_assoc(); 
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/graph.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">CrowdFunding</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="../index.php">Home</a>
                            </li>
                            </li>
                                <a class="nav-link active" href="profile.php">Profile(Extra)</a>
                            </li>
                                <a class="nav-link" href="projects.php">Projects(Extra)</a>
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
                        <a class="btn btn-primary" id="out" href="../logout.php" role="button">Logout</a>
                </div>
        </div>
    </nav>

    <div class="bode">
        <br><br><br><br>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?php echo $res['firstname'], " ",$res['lastname'] ;?></h4>
                <p class="card-text"><?php echo "Email - ", $res['email'];?></p>
            </div>

            <ul class="list-group list-group-flush">
                <?php                       
                    $query3 = "SELECT projects_investors.investmentFund, projects_investors.investmentDate,  projects.requestedFund, projects_investors.idProject, projects_investors.idUser, projects.projectName, projects.projectDescription FROM projects, projects_investors 
                    WHERE ".$_SESSION['idUser']."= projects_investors.idUser AND projects.idProject = projects_investors.idProject";
                    $result3 = $mysqli->query($query3);
                    $res3 = $result3->fetch_assoc();
                    
                    $query2 = "SELECT * FROM projects WHERE ".$_SESSION['idUser']."= projects.idUser";
                    $result2 = $mysqli->query($query2);
                    $res2 = $result2->fetch_assoc();                      

                    if($_SESSION['idUser']==$res2['idUser']){
                        ?>
                        <li class="list-group-item">
                            <?php
                            echo "<h2>Your Project</h2><h3>", $res2['projectName'],"<br>","</h3>";
                            echo "<h5>".$res2['projectDescription']."<br><br>","</h5>";
                            echo "<h4>Project Start Date - ".$res2['projectStartDate']."<br>","</h4>";
                            echo "<h4>Project End Date - ".$res2['projectEndDate']."<br>","</h4>";
                            echo "<h4>Requested Fund - ".$res2['requestedFund']."<br>","</h4>";

                            if($result3->num_rows>0){
                                $query4 = "SELECT projects.projectName, projects.requestedFund,SUM(projects_investors.investmentFund) as invested FROM projects_investors, 
                                projects WHERE projects.idProject = ".$res3['idProject']." AND projects_investors.idProject = projects.idProject";
                                $result4 = $mysqli->query($query4);
                                $res4 = $result4->fetch_assoc();  
                                $requested = $res4['requestedFund'];
                                $invested = $res4['invested'];
                                $remaining = $requested - $invested;
                                $graph = 100 - 100 * ($invested / $requested ); 
                            echo "<h4>Already Invested - ", $invested,"<br>","</h4>";
                            echo "<h4>Still Remaining - ", $remaining,"<br>","</h4>";
                            echo "<div class='out'><div class='in' style='margin-right: $graph%'><div class='invested'></div></div></div>";}

                            "</li>";
                        }
                        
                        while($res3 = $result3->fetch_assoc()){
                        if($_SESSION['idUser']==$res3['idUser']){
                            ?>
                            <li class="list-group-item">
                                <?php
                            echo "<h2>You invested:</h2>";
                            echo "<h3>", $res3['projectName'],"<br>","</h3>";
                            echo "<h5>".$res3['projectDescription']."<br><br></h5>";
                            echo "<h4>Your invest - ", $res3['investmentFund'],"</h4>";
                            echo "<h4>Your invest Date - ", $res3['investmentDate'],"<br>","</h4>";
                            echo "<h4>Requested Fund - ", $res3['requestedFund'],"<br>","</h4>";

                                $query4 = "SELECT projects.projectName, projects.requestedFund,SUM(projects_investors.investmentFund) as invested FROM projects_investors, 
                                projects WHERE projects.idProject = ".$res3['idProject']." AND projects_investors.idProject = projects.idProject";
                                $result4 = $mysqli->query($query4);
                                $res4 = $result4->fetch_assoc();  
                                $requested = $res4['requestedFund'];
                                $invested = $res4['invested'];
                                $remaining = $requested - $invested;
                                $graph = 100 - 100 * ($invested / $requested );       
                            echo "<h4>Already Invested - ", $invested,"<br>","</h4>"; 
                            echo "<h4>Still Remaining - ", $remaining,"<br>","</h4>";
                            echo "<div class='out'><div class='in' style='margin-right: $graph%'><div class='invested'></div></div></div>";

                            "</li>";
                        }          
                    }   
                    
                    ?>
            </ul>
        <div class="card-body" id="home">
            <a href="../index.php" class="card-link">Back to Homepage</a>
        </div>
    </div>   
</body>
</html>