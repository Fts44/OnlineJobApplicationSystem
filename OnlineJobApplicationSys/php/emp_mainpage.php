<?php
  include 'methods.php';
  $con = new connection();
  $func = new methods();

  session_start();
  $Email = $_SESSION["Email"];

  //retrieve user info
  $sql = "SELECT * FROM `user_info` WHERE `email`='$Email'";
  $result = $con->queries($sql);
  $row = mysqli_fetch_assoc($result);
  $company_name = $row['company_name'];
  $first_name = $row['first_name'];
  $middle_name = $row['middle_name'];
  $last_name = $row['last_name'];
  $Current_password = $row['password'];
  $birthdate = $row['birthdate'];
  $regCode = $func->isNULL($row["regCode"]);
  $provCode = $func->isNULL($row["provCode"]);
  $cityCode = $func->isNULL($row["cityCode"]);
  $brgyCode = $func->isNULL($row["brgyCode"]);
  //change acc details 
  if(isset($_POST["btnSaveAccDetails"])){
    $first_name = "'".$_POST["fname"]."'";
    $middle_name = "'".$_POST["mname"]."'";
    $last_name = "'".$_POST["lname"]."'";
    $birthdate = "'".$_POST["bdate"]."'";
    $regCode = $func->isNULL($_POST["regCode"]);
    $provCode = $func->isNULL($_POST["provCode"]);
    $cityCode = $func->isNULL($_POST["cityCode"]);
    $brgyCode = $func->isNULL($_POST["brgyCode"]);
    $company_name = $func->isNULL($_POST["cname"]);
    if($company_name != 'NULL'){
    	$company_name = "'".$company_name."'";
    }
    $sql = 
    "UPDATE `user_info` SET 
    `first_name`= $first_name,
    `middle_name`= $middle_name,
    `last_name`= $last_name,
    `birthdate`= $birthdate,
    `regCode`= $regCode,
    `provCode`= $provCode,
    `cityCode`= $cityCode,
    `brgyCode`= $brgyCode,
    `company_name`= $company_name
    WHERE `email`='$Email'";
    $con->queries($sql);
    header('Location: emp_mainpage.php');
  }
  //change password
  if(isset($_POST['btnSaveAccPassword'])){
    $Password = $_POST['oldpass'];
    $npassword = $_POST['npassword'];
    $npassword1 = $_POST['npassword1'];
    if($npassword1!=$npassword){
      echo 
      "
      <script>
        alert('New Password Not Match!');
      </script>
      ";
    }
    else if($Password!=$Current_password){
      echo 
      "
      <script>
        alert('Old Password Not Match!');
      </script>
      ";
    }
    else{
      $sql = "UPDATE `user_info` SET `password`='$npassword' WHERE `email`='$Email'";
      $con->queries($sql);
      header('Location: emp_mainpage.php');
    }
  }
  //insert new job post
  if(isset($_POST['btnInsertJP'])){
  	$category_name = $_POST["category_name"];
    $title = $_POST["title"];
    $regCode = $_POST["jpRegion"];
    $provCode = $_POST["jpProvince"];
    $cityCode = $_POST["jpCity"];
    $brgyCode = $_POST["jpBarangay"];
    $description = $_POST["description"];
    $qualification = $_POST["qualification"];
    $salary = $_POST["salary"];
    $status = "Open";

    $sql = 
    "INSERT INTO `job_post`
    (`category_name`,`title`, 
    `regCode`, `provCode`, 
    `cityCode`, `brgyCode`, 
    `description`, `qualification`, 
    `salary`, `employer_email`, 
    `status`) 
    VALUES 
    ('$category_name','$title',
    '$regCode','$provCode',
    '$cityCode','$brgyCode',
    '$description','$qualification',
    '$salary','$Email',
    '$status')";
    $con->queries($sql);
  }
  //default sql for job posting 
  $sqldefault = 
  "SELECT rr.regDesc, 
  CONCAT(UPPER(SUBSTRING(rp.provDesc,1,1)),LOWER(SUBSTRING(rp.provDesc,2))) AS provDesc,
  CONCAT(UPPER(SUBSTRING(rc.citymunDesc,1,1)),LOWER(SUBSTRING(rc.citymunDesc,2))) AS citymunDesc,
  CONCAT(UPPER(SUBSTRING(rb.brgyDesc,1,1)),LOWER(SUBSTRING(rb.brgyDesc,2))) AS brgyDesc,
  jp.*
  FROM job_post jp
  INNER JOIN refregion rr
  ON rr.regCode = jp.regCode
  INNER JOIN refprovince rp
  ON rp.provCode = jp.provCode
  INNER JOIN refcitymun rc
  ON rc.citymunCode = jp.cityCode
  INNER JOIN refbrgy rb
  ON rb.brgyCode = jp.brgyCode";

  $_SESSION["sql"] = $sqldefault." ORDER BY jp.date_post DESC";

   //page
  $num_per_page=12;
  if(isset($_GET["page"]))
  {
    $page=$_GET["page"];
  }
  else
  {
    $page=1;
  }
  $start_from=($page-1)*$num_per_page;

  if(isset($_POST["btnFilterJP"])){
  	//check if empty or 0, if empty set the value to is not null
    $title = $func->notNULL($_POST['title'], "jp.title");
 	  $fRegion = $func->notNULL($_POST['fRegion'], "rr.regCode");
    $fProvince =  $func->notNULL($_POST['fProvince'], "rp.provCode ");
    $fCity =  $func->notNULL($_POST['fCity'], "rc.citymunCode");
    $fBarangay =  $func->notNULL($_POST['fBarangay'], "rb.brgyCode");

    if($_POST['minSalary'] == ""){
    	$minSalary =  "(SELECT MIN(salary) from job_post)";
    }
    else{
    	$minSalary = $_POST['minSalary'];
    }

    if($_POST['maxSalary'] == ""){
    	$maxSalary =  "(SELECT MAX(salary) from job_post)";
    }
    else{
    	$maxSalary = $_POST['maxSalary'];
    }
    $salary = "jp.salary BETWEEN ".$minSalary." and ".$maxSalary;
    $page = 1;
    $sql = $sqldefault.
    " WHERE ".$title.
    " and ".$fRegion.
    " and ".$fProvince.
    " and ".$fCity.
    " and ".$fBarangay.
    " and ".$salary.
    " ORDER BY jp.date_post DESC";
    //update session sql
    $_SESSION["sql"] = $sql;
  }
  //set the session sql to default
  if(isset($_POST["default"])){
    $_SESSION["sql"] = $sqldefault;
    $page = 1;
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../images/LogoCL.png">
    <link rel="stylesheet" href="../css/mainpage.css">
    <!-- Bootstrap Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
<body onload="loadAll(); populate_empJobPosting(<?php echo $page; ?>)">
  <script type="text/javascript">
    function loadAll(){
      loadSelect('sRegion', <?php echo "'".$regCode."'"; ?>, 'sProvince', <?php echo "'".$provCode."'"; ?>, 
                 'sCity',   <?php echo "'".$cityCode."'"; ?>, 'sBarangay', <?php echo "'".$brgyCode."'"; ?>);
      loadSelect('jpRegion', '0', 'jpProvince', '0', 'jpCity',   '0', 'jpBarangay', '0')
      loadSelect('fRegion', '0', 'fProvince', '0', 'fCity',   '0', 'fBarangay', '0');
    }
  </script>
  <div class="sidebar close">
    <div class="logo-details">
      <i class='bx bx-menu black'></i>
      <span class="logo_name"><u>CoffeLaberss</u></span>
    </div>
    <ul class="nav-links">
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bxs-dashboard'></i>
            <span class="link_name">Dashboard</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a href="#">Sub Menu 1</a></li>
          <li><a href="#">Sub Menu 2</a></li>
        </ul>
        <!--Add sub menu (Start looking jobs near you, )-->
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bxs-chalkboard'></i>
            <span class="link_name">Post</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a href="emp_mainpage.php">View All</a></li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#CreateJP">Add New</a></li>
          <li><a href="emp_viewyourJP.php">Edit Your Post</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bxs-envelope' ></i>
            <span class="link_name">Mail</span>
          </a>
        </div>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bxs-user'></i>
            <span class="link_name">Account</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#AccPassword">Change Password</a></li>
          <li><a href="#" data-bs-toggle="modal" data-bs-target="#AccDetails">Employer Details</a></li>
          <li><a href="index.php">Logout</a></li>
        </ul>
      </li>
</ul>
  </div>
  <section class="home-section" style="height: 775px;">
    <div class="home-content">
      <a data-bs-toggle="modal" data-bs-target="#FilterJP">
        <button class="btn btn-secondary"><i class='bx bx-filter-alt'></i> Filter Option</button>
      </a>
      &nbsp;
      <form method="POST">
        <button class="btn btn-secondary" type="submit" name="default"><i class='bx bx-reset'></i> Default</button>
      </form>
    </div>
    <div class='grid' id='grid'>
    </div>
    <?php
      
    ?>
  </section>
  <script src="../javascript/mainpage.js" type="text/javascript"></script>
  <script src="../javascript/function.js" type="text/javascript"></script>
  <!--Bootstrap-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
<!--Modal for the account details-->
<div class="modal fade" id="AccDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="AccDetails" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="">
        <h5 class="modal-title">Profile Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method='POST' id='frmAccDetails'>
          <div class="container-fluid">
          	<div class="row">
              <div class="col-lg-4"> 
                Co. Name:
              </div>
              <div class="col-lg-8"> 
                <input type='text' name='cname' value='<?php echo $company_name;?>' autocomplete="off">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4"> 
                First Name:
              </div>
              <div class="col-lg-8"> 
                <input type='text' name='fname' value='<?php echo $first_name;?>' autocomplete="off">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4"> 
                Middle Name:
              </div>
              <div class="col-lg-8"> 
                <input type='text' name='mname' value='<?php echo $middle_name;?>' autocomplete="off">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4"> 
                Last Name:
              </div>
              <div class="col-lg-8"> 
                <input type='text' name='lname' value='<?php echo $last_name;?>' autocomplete="off">
              </div> 
            </div>
            <div class="row">
              <div class="col-lg-4"> 
                Birthdate:
              </div>
              <div class="col-lg-8"> 
                <input type='date' name='bdate' value='<?php echo $birthdate;?>' autocomplete="off">
              </div> 
            </div>
            <div class="row">
              <div class="col-lg-4"> 
                Region:
              </div>
              <div class="col-lg-8"> 
                <select 
                  id="sRegion" 
                  onchange="set_province('sProvince', document.getElementById('sRegion').value, '0','sCity','sBarangay');" 
                  name="regCode"
                >
                </select>
              </div> 
            </div>
            <div class="row">
              <div class="col-lg-4"> 
                Province:
              </div>
              <div class="col-lg-8"> 
                <select 
                  id="sProvince" 
                  onchange="set_city('sCity', document.getElementById('sProvince').value, '0','sBarangay')"
                  name="provCode"
                >
                </select>
              </div> 
            </div>
            <div class="row">
              <div class="col-lg-4"> 
                City:
              </div>
              <div class="col-lg-8"> 
                <select 
                  id="sCity" 
                  onchange="set_barangay('sBarangay', document.getElementById('sCity').value, '0')" 
                  name="cityCode"
                >
                </select>
              </div> 
            </div>
            <div class="row">
              <div class="col-lg-4"> 
                Barangay:
              </div>
              <div class="col-lg-8"> 
                <select id="sBarangay" name="brgyCode">
                </select>
              </div> 
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="loadAll()">Close</button>
        <button type="submit" class="btn btn-secondary" name="btnSaveAccDetails" form="frmAccDetails">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<!--Modal for the password details-->
<div class="modal fade" id="AccPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="AccPassword" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method='POST' id='frmAccPassword'>
          <div class="container-fluid">

            <div class="row">
              <div class="col-lg-12">
                New Password:
              </div>
              <div class="col-lg-12">
                <input type='password' name='npassword' id='Password' autocomplete="off">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Retype New Password:
              </div>
              <div class="col-lg-12">
                <input type='password' name='npassword1' id='Password1' autocomplete="off">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Enter Current Password:
              </div>
              <div class="col-lg-12">
                <input type='password' name='oldpass' id='oldpass' autocomplete="off">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                 <input type='checkbox' style='min-width: 25px;' 
                onclick="myFunction('Password');myFunction('Password1');myFunction('oldpass')">
                Show Password
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" name="btnSaveAccPassword" form="frmAccPassword">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<!--Modal for the create job post-->
<div class="modal fade" id="CreateJP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="CreateJP" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="">
        <h5 class="modal-title">Create Job Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method='POST' id='frmCreatJP'>
          <div class="container-fluid">
          	<div class="row">
              <div class="col-lg-12">
                Job Category:
              </div>
              <div class="col-lg-12">
                <select id='jpCategory' name="category_name">
                	<option value="0">Select Job Category</option>
                	<?php
                	$sql_jpCategory = "SELECT * FROM `job_category";
                	$result_jpCategory = $con->queries($sql_jpCategory);
                	
                	while($row_jpCategory = mysqli_fetch_assoc($result_jpCategory)){
                		echo "<option value='".$row_jpCategory['category_name']."'>"
                		.$row_jpCategory['category_name'].
                		"</option>";
                	}
                	?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Job Title
              </div>
              <div class="col-lg-12">
                <input type='text' name="title">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Region:
              </div>
              <div class="col-lg-12">
                <select 
                  id="jpRegion" 
                  onchange="set_province('jpProvince', document.getElementById('jpRegion').value, '0','jpCity','jpBarangay');"
                  name="jpRegion" 
                >
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Province:
              </div>
              <div class="col-lg-12">
                <select 
                  id="jpProvince" 
                  onchange="set_city('jpCity', document.getElementById('jpProvince').value, '0','jpBarangay');"
                  name="jpProvince" 
                >
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                City:
              </div>
              <div class="col-lg-12">
                <select 
                  id="jpCity" 
                  onchange="set_barangay('jpBarangay', document.getElementById('jpCity').value, '0');"
                  name="jpCity" 
                >
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Barangay:
              </div>
              <div class="col-lg-12">
                <select 
                  id="jpBarangay"
                  name="jpBarangay" 
                >
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Description:
              </div>
              <div class="col-lg-12">
                <textarea name="description"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Qualification:
              </div>
              <div class="col-lg-12">
                <textarea name="qualification"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Salary:
              </div>
              <div class="col-lg-12">
                <input type='text' name="salary" autocomplete="off">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" name="btnInsertJP" form="frmCreatJP">Add</button>
      </div>
    </div>
  </div>
</div>
<!--Modal for the filter job post-->
<div class="modal fade" id="FilterJP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="FilterJP" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filter Option</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method='POST' id='frmFilterJP'>
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                Job Title
              </div>
              <div class="col-lg-12">
                <input type='text' name="title">
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Region:
              </div>
              <div class="col-lg-12">
                <select 
                  id="fRegion" 
                  onchange="set_province('fProvince', document.getElementById('fRegion').value, '0','fCity','fBarangay');"
                  name="fRegion" 
                >
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Province:
              </div>
              <div class="col-lg-12">
                <select 
                  id="fProvince" 
                  onchange="set_city('fCity', document.getElementById('fProvince').value, '0','fBarangay');"
                  name="fProvince" 
                >
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                City:
              </div>
              <div class="col-lg-12">
                <select 
                  id="fCity" 
                  onchange="set_barangay('fBarangay', document.getElementById('fCity').value, '0');"
                  name="fCity" 
                >
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Barangay:
              </div>
              <div class="col-lg-12">
                <select 
                  id="fBarangay"
                  name="fBarangay" 
                >
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                Salary:
              </div>
              <div class="col-lg-12">
                <center>
                  <table>
                    <tr>
                      <td><input name="minSalary" type="number" placeholder="Min"></td>
                      <td>-</td>
                      <td><input name="maxSalary" type="number" placeholder="Max"></td>
                    </tr>
                  </table>
                </center>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" name="btnFilterJP" form="frmFilterJP">Search</button>
      </div>
    </div>
  </div>
</div>
<!--Modal for the view job post-->
<div class="modal fade" id="viewJP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="FilterJP" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Job Post Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method='POST' id='frmFilterJP'>
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <h6>Job Title:</h6>
              </div>
              <div class="col-lg-12">
                <div id="jtitle"></div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-lg-12">
                <h6>Address:</h6>
              </div>
              <div class="col-lg-12">
                <label id="jregion"></label>
              </div>
              <div><label id="jbrgy"></label>, <label id="jcity"></label>, <label id="jprov"></label></div>
            </div>
            <br>
            <div class="row">
              <div class="col-lg-12">
                <h6>Job Description:</h6>
              </div>
              <div class="col-lg-12">
                <div id="jdesc" style="text-align: justify;"></div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-lg-12">
                <h6>Job Qualification:</h6>
              </div>
              <div class="col-lg-12">
                <div id="jquali" style="text-align: justify;"></div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-lg-12">
                <h6>Salary:</h6>
              </div>
              <div class="col-lg-12">
                <div id="jsalary"></div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" name="" form="">Apply</button>
      </div>
    </div>
  </div>
</div>
</html>