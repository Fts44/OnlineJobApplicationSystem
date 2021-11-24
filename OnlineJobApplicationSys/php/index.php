<?php
  session_start();
  include 'methods.php';
  $con = new connection();

  if(isset($_POST['btnLogin'])){
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $sql = "SELECT * FROM `user_info` WHERE email='$email' and password='$password'";
    $result = $con->queries($sql);
    if(mysqli_num_rows($result)>0){    
      $_SESSION["Email"] = $email;
    	header('Location: mainpage.php');
    }
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/LogoCL.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <title>Login</title>
  </head>
  <body>

    <div class="container">
      <header>
        Login
      </header>
      <section>
        <form id="formLogin" method="POST">
          <div class="form-row">
            <label>Email:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Email" placeholder="Email" autocomplete="off" required="on">
          </div>
          <br>
          <div class="form-row">
            <label>Password:</label>
          </div>
          <div class="form-row">
            <input type="Password" name="Password" placeholder="Password" autocomplete="off" id="Password"  required="on">
          </div>
        </form>
          <div class="form-row">
            <input type="checkbox" class="chckbx" onclick="myFunction('Password')">
            <label>Show Password</label>
          </div>
          <div class="form-row">
            <button class="btn btn-dark" name="btnLogin" form="formLogin">Login</button>
          </div>
          <div class="form-row">
            <a href="#">Forgot Password?</a>
          </div>
          <div class="form-row">
            Don't have an account yet?<br>
            <a href="signup.php">Sign Up</a>
          </div>
      </section>
    </div>
    <script src="../javascript/function.js" type="text/javascript"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>