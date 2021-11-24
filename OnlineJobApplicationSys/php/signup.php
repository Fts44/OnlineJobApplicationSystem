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
    
    <title>Sign Up</title>
  </head>
  <style type="text/css">
    .container{
     	margin-top: 17px; 
     	margin-bottom: 5px;"
    }
    button{
     	width: 120px;
     	display: inline-flex;
    }
    header{
    	height: 70px;
    }
    label{
    	margin-bottom: -3px;
    }
  </style>
  <body>
    <div class="container">
      <header>
        Sign Up
      </header>
      <section>
        <form id="formSignup" method="POST" onsubmit="invalidPass()">
          <div class="form-row">
            <label>First Name:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Fname" placeholder="First Name" autocomplete="off" required="on">
          </div>
          <div class="form-row">
            <label>Middle Name:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Mname" placeholder="Middle Name" autocomplete="off" required="on">
          </div>
          <div class="form-row">
            <label>Last Name:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Lname" placeholder="Last Name" autocomplete="off" required="on">
          </div>
          <div class="form-row">
            <label>Email:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Email" placeholder="Email" autocomplete="off" required="on">
          </div>
          <div class="form-row">
            <label>Password:</label>
          </div>
          <div class="form-row">
            <input type="Text" id="Password" name="Password" placeholder="Password" autocomplete="off" required="on">
          </div>
          <div class="form-row">
            <input type="Text" id="CPassword" name="CPassword" placeholder="Confirm Password" autocomplete="off" required="on">
          </div>
          <div class="form-row">
            <label>User Type:</label>
          </div>
          <div class="form-row">
            <select name="UserType">
              <option>Applicant</option>
              <option>Employer</option>
            </select>
          </div>
        </form>
        <div>
          <button class="btn btn-dark" name="btnSignup" form="formSignup">Sign Up</button>
          <a href="index.php"><button class="btn btn-dark" name="btnBack">Cancel</button></a>
        </div>  
      </section>
    </div>
    <style type="text/css">
      .swal-modal {
        background-color: #E4E9F7;
        color: white;
      }
      .swal-text {
        color: black;
        font-size: 25px;
        align-self: center;
      }
      .swal-button {
        background-color: rgb(41, 43, 44);
        color: white;
        width: 90px;
      }
    </style>
    <script type="text/javascript">
      function invalidPass(){
        swal({
          text: "Password Not Match!",
          button: "Okay",
          });   
    </script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">  
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  </body>
</html>
<?php 
  include 'methods.php';
  $con = new connection();

  if(isset($_POST["btnSignup"])){
    $Password = $_POST['Password'];
    $CPassword = $_POST['CPassword'];
    if($Password!=$CPassword){
        echo "
          <script>
          swal({
            text: 'Password Not Match!',
            button: 'Okay',
            });
          </script>
      ";
    }
    else{
      $Fname = $_POST['Fname'];
      $Mname = $_POST['Mname'];
      $Lname = $_POST['Lname'];
      $Email = $_POST['Email'];
      $User_type = $_POST['UserType'];
      $sql = 
      "INSERT INTO `user_info`
      (`first_name`, 
      `middle_name`, 
      `last_name`,
      `email`, 
      `password`, 
      `user_type` 
      ) 
      VALUES 
      ('$Fname',
      '$Mname',
      '$Lname',
      '$Email',
      '$Password',
      '$User_type')";
      $result = $con->queries($sql);
      if($result){
        echo "
          <script>
            alert('Account Created!');
          </script>
        ";
      }
      else{
        echo "
          <script>
            alert('Email is Taken!');
          </script>
        ";
      }
    }
  }
?>