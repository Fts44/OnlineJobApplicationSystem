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
  $sql = $_SESSION["sql"];
  $sql = $sql." limit $start_from,$num_per_page";
  $sql1 = $_SESSION["sql"];
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
    echo "<button class='btn btn-secondary'>View</button>";
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
      echo "No Records Found!";
    }
      echo "<center style = 'display: block; width: 100%; margin-bottom: 10px;'>";
      echo "<br>";
      echo "<div class='footer'>";
      echo "<a href='mainpage.php?page=".$prev."'><button class='btn btn-secondary' style='margin-left: 10px;'>".
      "<i class='bx bx-skip-next bx-flip-horizontal' ></i>"."</button></a>";
      for($i=1;$i<=$total_pages;$i++)
      {
        echo "<a href='mainpage.php?page=".$i."'><button class='btn btn-secondary' style='margin-left: 10px;'>".$i."</button></a>";
      }
      echo "<a href='mainpage.php?page=".$next."'><button class='btn btn-secondary' style='margin-left: 10px;'>".
      "<i class='bx bx-skip-next'></i>".
      "</button></a>";
      echo "</div";
      echo "</center>";
?>