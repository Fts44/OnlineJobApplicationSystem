<?php 
  include 'methods.php';
  $con = new connection();

  $Fname = "";
  $Mname = "";
  $Lname = "";
  $Email = "";
  $Password = "";
  $CPassword = "";
  $User_type = "";
  if(isset($_POST["btnSignup"])){
    $Fname = $_POST['Fname'];
    $Mname = $_POST['Mname'];
    $Lname = $_POST['Lname'];
    $Email = $_POST['Email'];
    $User_type = $_POST['UserType'];
    $Password = $_POST['Password'];
    $CPassword = $_POST['CPassword'];
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
    <link rel="stylesheet" type="text/css" href="../css/idx.css">
    
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
        <form id="formSignup" method="POST">
          <div class="form-row">
            <label>First Name:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Fname" required="on"
            value="<?php if($Fname != ''){ echo $Fname; } ?>">
          </div>
          <div class="form-row">
            <label>Middle Name:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Mname" autocomplete="off" required="on"
            value="<?php if($Mname != ''){ echo $Mname; } ?>">
          </div>
          <div class="form-row">
            <label>Last Name:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Lname" autocomplete="off" required="on"
            value="<?php if($Lname != ''){ echo $Lname; } ?>">
          </div>
          <div class="form-row">
            <label>Email:</label>
          </div>
          <div class="form-row">
            <input type="Text" name="Email" autocomplete="off" required="on"
            value="<?php if($Email != ''){ echo $Email; } ?>">
          </div>
          <div class="form-row">
            <label>Password:</label>
          </div>
          <div class="form-row">
            <input type="Text" id="Password" name="Password" autocomplete="off" required="on"
            value="<?php if($Password != ''){ echo $Password; } ?>">
          </div>
          <div class="form-row">
            <input type="Text" id="CPassword" name="CPassword" autocomplete="off" required="on"
            value="<?php if($CPassword != ''){ echo $CPassword; } ?>">
          </div>
          <div class="form-row">
            <label>User Type:</label>
          </div>
          <div class="form-row">
            <select name="UserType">
              <option <?php if($User_type == 'Applicant'){ echo "selected";} ?> >Applicant</option>
              <option <?php if($User_type == 'Employer'){ echo "selected";} ?> >Employer</option>
            </select>
          </div>
        </form>
        <div>
          <button class="btn btn-dark" name="btnSignup" form="formSignup">Sign Up</button>
          <a href="index.php"><button class="btn btn-dark" name="btnBack">Cancel</button></a>
        </div>  
      </section>
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">  
    </script>
    <script src='../javascript/sweetalert.min.js'></script>
  </body>
</html>
<?php
  if(isset($_POST["btnSignup"])){ 
    if($Password!=$CPassword){
        echo "
          <script>
          swal({
            text: 'Password Not Match!',
            icon: 'warning',
            button: 'Okay',
            });
          </script>
      ";
    }
    else{
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
          swal({
            text: 'Account Created!',
            icon: 'success',
            button: 'Okay',
            });
          </script>
        ";
        header("Location: index.php");
      }
      else{
        echo "
          <script>
          swal({
            text: 'Email is Already Taken!',
            icon: 'warning',
            button: 'Okay',
            });
          </script>
        ";
      }
    }
  }
?>