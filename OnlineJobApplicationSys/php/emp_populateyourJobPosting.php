<?Php

  include 'methods.php';

  $con = new connection();
  $func = new methods();

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

  session_start();
  $sql = $_SESSION["sqlyjp"];
  $sql = $sql." limit $start_from,$num_per_page";
  $sql1 = $_SESSION["sqlyjp"];
  $result = $con->queries($sql);

  while($row = mysqli_fetch_assoc($result)){
    echo "<div class='grid-item'>";
    echo "<label style = 'font-weight: 600;'><i class='bx bxs-user' ></i> ".$row['title']."</label>";
    echo "<br><br>";
    echo "<i class='bx bxs-map'></i> ".$func->regionExplode($row['regDesc'])."<br>";
    echo "&emsp;&nbsp;".$row['provDesc']."<br>";
    echo "&emsp;&nbsp;".$row['citymunDesc']."<br>";
    echo "&emsp;&nbsp;".$row['brgyDesc']."<br>";
    echo "<br>";
    echo "<i class='bx bx-money'></i> Php ".number_format($row['salary']);
    echo "<br>";
    echo "<button class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#EditJP'
    onclick=\"
    passTexttoInput('".$row['id']."','ejpid');
    set_jobCategory('".$row['category_name']."','ejpCategory');
    passTexttoInput('".$row['title']."','etitle');
    set_region('ejpRegion','".$row['regCode']."','ejpProvince','ejpCity','ejpBarangay');
    set_province('ejpProvince','".$row['regCode']."','".$row['provCode']."','ejpCity','ejpBarangay');
    set_city('ejpCity', '".$row['provCode']."', '".$row['cityCode']."', 'ejpBarangay');
    set_barangay('ejpBarangay', '".$row['cityCode']."', '".$row['brgyCode']."');
    passText('".$row['description']."','edescription');
    passText('".$row['qualification']."','equalification');
    passTexttoInput('".$row['salary']."','esalary');
    set_jobstatus('".$row['status']."','estatus');
    \">Edit</button>";
    echo "</div>";
  }
    $result = $con->queries($sql1);
    $total_records=mysqli_num_rows($result);
    $total_pages=ceil($total_records/$num_per_page);
    $prev = $page - 1;
    $next = $page + 1;
    if($prev == 0){
      $prev = 1;
    }
    if($next > $total_pages){
      $next = $total_pages;
    }
    if($total_records == 0){
      echo "<h5>No Job Post Found!</h5>";
    }
    else{
      echo "<center style = 'display: block; width: 100%; margin-bottom: 10px;'>";
      echo "<br>";
      echo "<div class='footer'>";
      echo "<a href='emp_viewyourJP.php?page=".$prev."'><button class='btn btn-secondary' style='margin-left: 10px;'>".
      "<i class='bx bx-skip-next bx-flip-horizontal' ></i>"."</button></a>";
      for($i=1;$i<=$total_pages;$i++)
      {
        echo "<a href='emp_viewyourJP.php?page=".$i."'><button class='btn btn-secondary' style='margin-left: 10px;'>".$i."</button></a>";
      }
      echo "<a href='emp_viewyourJP.php?page=".$next."'><button class='btn btn-secondary' style='margin-left: 10px;'>".
      "<i class='bx bx-skip-next'></i>".
      "</button></a>";
      echo "</div";
      echo "</center>";
    }
?>