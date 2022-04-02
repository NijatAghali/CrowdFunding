<?php
session_start();
include("connectDB.inc.php");
 
$email = $password = "";
$email_err = $password_err = $login_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username and password is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($email_err) && empty($password_err)){

        $sql = "SELECT idUser, email, password FROM users WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_email);
            $param_email = $email; 		       
            
            if($stmt->execute()){
                $stmt->store_result();
                
                // Check if email exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    $stmt->bind_result($idUser, $email, $password);
                    if($stmt->fetch()){
						if ($_POST['password'] === $password) {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["idUser"] = $idUser;
                            $_SESSION["email"] = $email;                            
                            
                            header("location: index.php");
                        } else{
                           echo "<h1>Invalid email or password.</h1>";}
                    }
                } else{
                    echo "<h1>Invalid email or password.</h1>";}
            } else{
                echo "Oops! Something went wrong. Please try again later.";}
            $stmt->close();
        }
    }
    $mysqli->close();
}
?>
 
 <!DOCTYPE html>
 <html lang="en">
	 
	 <head>
		 <meta charset="UTF-8">
		 <meta http-equiv="X-UA-Compatible" content="IE=edge">
		 <meta name="viewport" content="width=device-width, initial-scale=1.0">
		 <title>Log In</title>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="./css/log.css">
    </head>

<body>
	<?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }       
        ?>
        
		<div class="main">
            <p class="sign" align="center">CrowdFunding</p>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form1">
						<input class="un" type="email"  name="email" placeholder="Email" required <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
						<span class="invalid-feedback"><?php echo $email_err; ?></span>

                        <input type="password" name="password" placeholder="Password" class="pass" required <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
						<span class="invalid-feedback"><?php echo $password_err; ?></span>

						<input class="submit" type="submit" value="Login">
			</form>
		</div>

	</body>
</html>

