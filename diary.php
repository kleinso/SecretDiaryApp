<?php
    
    session_start();


    if (array_key_exists('id', $_COOKIE) && $_COOKIE['id']) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists("id", $_SESSION) && $_SESSION['id']) {
        
        include('connection.php');
        
        $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        
        $row = mysqli_fetch_array(mysqli_query($link, $query));
        
        $diaryContent = $row['diary'];
      }
     else {
        
        header("Location: diary-login.php");
     }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
    <style>
         html { 
              background: url(background2.jpeg) no-repeat center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;
            }
          body {
              background: none;
          }
        
        #logout {
            float: right;
            
        }
        
        a:link {
            color: #449D44;
        }
        a:visited {
            color: #449D44;
        }
        a:hover {
            color: white;
            text-decoration: none;
        }
        a:active {
            color: white;
        }
        textarea {
            resize: none;
        }
        .container {
            margin-top: 20px;
        }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-full navbar-light bg-faded">
      <a class="navbar-brand" href="#">Secret Diary</a>
     
        <button id="logout" class="btn btn-outline-success" type="submit" name="logout"><a href="diary-login.php?logout=1">Logout</a></button>
      </nav>
 
    <div class="container">
        <form method="post">
            <div class="form-group">
            <textarea rows="30" class="form-control" id="diaryEntry" name="diaryEntry"><? echo $diaryContent; ?></textarea>
            </div>
        </form>
    </div>

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
      <script type="text/javascript">
        $('#diaryEntry').bind('input propertychange', function() {
            
            $.ajax({
                method: "POST",
                url: "updateDatabase.php",
                data: {content: $("#diaryEntry").val() }
            });
        });
      
      </script>
  </body>
</html>