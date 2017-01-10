<?php

session_start();
$error = "";


//Logout system - clears cookies
if (array_key_exists("logout", $_GET)) {
    
    unset($_SESSION);
    setcookie("id", "", time() - 60*60);
    $_COOKIE["id"] = "";
} 


//Login redirect to loggedInPage
else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
    //checks that both SESSION and COOKIE exist AND have a value. Having both checks makes sure it doesn't force loggedinpage redirect if already logged out.
    //if array key doesn't exist, won't look for SESSION or COOKIE id. This prevents error if not logged in.
    
    header("Location: diary.php");    
}

if (array_key_exists("submit", $_POST)) {
//Login and Signup system
    include("connection.php");
        
    if (!$_POST['email']) {
            
        $error .= "Email address is required.<br>";
            //error messages appended
    } 
    if (!$_POST['password']) {
            
        $error .= "Password is required.<br>";
            
    } 
    if ($error != "") {
        $error = "<p>There were error(s) in your form:</p>".$error;
        
    } 
    
    else {
    //SignUp System    
        if ($_POST['signUp'] == '1') {
            $query = "SELECT `id` FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
            // check if email already in database
            
            $result = mysqli_query($link, $query);
            
            if (mysqli_num_rows($result) > 0) {
                    
                $error = "That email address has already been taken.";
            } 
            
            else {

             $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."',' ".mysqli_real_escape_string($link, $_POST['password'])."')";

                if (!mysqli_query($link, $query)) {

                  $error = "<p>Could not sign up - please try again later.</p>";

                } 
                
                else {

                  $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id =".mysqli_insert_id($link)." LIMIT 1";
                    // updates password to be encrypted by md5
                    // mysqli_insert_id() gives the id of most recent array in database
                    
                  mysqli_query($link, $query);

                  $_SESSION['id'] = mysqli_insert_id($link);
                    //start session to keep logged in. Id linked to user Id
                    
                     if($_POST['stayLoggedIn'] == '1') {
                         //if box checked, start cookies
                         
                        setcookie("id", mysqli_insert_id($link), time() + 60*60*24);

                      }
                  header("Location: diary.php");
                    //direct to loggedInPage once signed up
                 } 
             } 
          }
        else { //Logging In System
                $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
            
                $result = mysqli_query($link, $query);
            
                $row = mysqli_fetch_array($result);
            
                if (isset($row)) {
                    //checks to see if $row exists at all, vs using "array_key_exists" which only checks for specific array.
                    
                    $hashedPassword = md5(md5($row['id']).$_POST['password']);
                    
                    if ($hashedPassword == $row['password']) {
                        
                        $_SESSION['id'] = $row['id'];
                        
                        if ($_POST['stayLoggedIn'] == '1') {
                            
                            setcookie("id", $row['id'], time() + 60*60*24);

                            }

                            header("Location: diary.php");
                                //redirect if email and password are correct
                        
                        }
                    else {
                        $error = "That email/password combination cannot be found.";
                     } //error for incorrect password
                    
                } 
            else {
                    $error = "That email/password combination cannot be found.";
             }  //error for incorrect email
                        
        }
    }
}

?>

<html>
    <body>

 <?php include('header.php'); ?>

    <div class="container">
      <div id="content-bg">   
        <h1>Secret Diary</h1>
        <lead>Store your thoughts permanently and securely.</lead>
        
        <form id="signUpForm" method="post">
              <p id="interested">Interested? Sign up now.</p>
              <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Your Email">
              </div>
              <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>
            <div id="error"><?php
                if ($error) {
                    echo '<div name="error" class="alert alert-danger" role="alert">'.$error.'</div>';
                }
            
            ?></div>
                 
          
              <div class="form-check">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="stayLoggedIn" value="1">
                  Stay logged in
                </label>
              </div>
            <input type="hidden" name="signUp" value="1">
              <button type="submit" class="btn btn-success" name="submit">Sign Up</button>
            <a href="#" class="toggleSignIn">Log In</a>
        </form>
          
        <form id="logInForm" method="post">
              <p id="interested">Log in using your username and password.</p>
              <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Your Email">
              </div>
              <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>
             <div id="error"><?php
                if ($error) {
                    echo '<div name="error" class="alert alert-danger" role="alert">'.$error.'</div>';
                }
            
            ?></div>
           
              <div class="form-check">
                <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" name="stayLoggedIn" value="1">
                  Stay logged in
                </label>
              </div>
            <input type="hidden" name="signUp" value="0">
              <button type="submit" class="btn btn-success" name="submit">Sign In</button>
            <div><a href="#" class="toggleSignIn">Sign Up</a>
                </div>
        </form>
        
        
          
        </div> 
     </div>
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script type="text/javascript">
      $(".toggleSignIn").click(function() {
            
              if ($("#logInForm").css("display") == "none") {
                  
                  $("#logInForm").toggle();
                  $("#signUpForm").toggle();
              } else {
                  $("#logInForm").toggle();
                  $("#signUpForm").toggle();
              }
          
          });
      
    </script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
  </body>
</html>

