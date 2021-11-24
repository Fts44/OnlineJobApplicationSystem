<?php
	include 'methods.php';
	$con = new connection();
	$func = new methods();

	$sql = "SELECT * FROM `user_info` WHERE `email`='jc@gmail.com'";
	$result = $con->queries($sql);
	$row = mysqli_fetch_assoc($result);
	$regCode = $row['regCode'];
	$provCode = $row['provCode'];
	$cityCode = $row['cityCode'];
	$brgyCode = $row['brgyCode'];

	$num_per_page=6;


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
	echo $_SESSION["sql"];
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body onload="loadAll('sRegion', <?php echo "'".$regCode."'"; ?>, 
					'sProvince', <?php echo "'".$provCode."'"; ?>, 
					'sCity', <?php echo "'".$cityCode."'" ?>,
					'sBarangay',  <?php echo "'".$brgyCode."'" ?>)">
	<script type="text/javascript">
		function loadAll(Region, regCode, Province, provCode, City, cityCode, Barangay, brgyCode){
			set_region(Region,regCode,Province,City,Barangay);
			set_province(Province, regCode, provCode,City,Barangay);
			set_city(City, provCode, cityCode, Barangay);
			set_barangay(Barangay, cityCode, brgyCode);
		}
		function set_region(Region, regCode, Province, City, Brgy){
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","populateAddress.php?region=1&regCode="+regCode,false);
			xmlhttp.send(null);
			document.getElementById(Province).innerHTML = "<select><option selected>SELECT PROVINCE</option></select>";
			document.getElementById(City).innerHTML = "<select><option selected>SELECT CITY</option></select>";
			document.getElementById(Brgy).innerHTML = "<select><option selected>SELECT BARANGAY</option></select>";		
			document.getElementById(Region).innerHTML=xmlhttp.responseText;
		}
		function set_province(Province, regCode, provCode, City, Brgy){
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","populateAddress.php?province=1&provCode="+provCode+"&regCode="+regCode,false);
			xmlhttp.send(null);
			document.getElementById(City).innerHTML = "<select><option selected>SELECT CITY</option></select>";
			document.getElementById(Brgy).innerHTML = "<select><option selected>SELECT BARANGAY</option></select>";			
			document.getElementById(Province).innerHTML=xmlhttp.responseText;
		}
		function set_city(City, provCode, cityCode, Brgy){
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","populateAddress.php?city=1&cityCode="+cityCode+"&provCode="+provCode,false);
			xmlhttp.send(null);
			document.getElementById(Brgy).innerHTML = "<select><option selected>SELECT BARANGAY</option></select>";		
			document.getElementById(City).innerHTML=xmlhttp.responseText;
		}
		function set_barangay(Brgy, cityCode, brgyCode){
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","populateAddress.php?brgy=1&brgyCode="+brgyCode+"&cityCode="+cityCode,false);
			xmlhttp.send(null);		
			document.getElementById(Brgy).innerHTML=xmlhttp.responseText;
		}
	</script>
	<div class="container">
	<style type="text/css">
		select{
			width: 250px;
		}
	</style>
	<label>Region:</label><br>
	<select id="sRegion" onchange="set_province('sProvince', document.getElementById('sRegion').value, '0','sCity','sBarangay');">
	</select>
	<br>
	<label>Province:</label>
	<br>
	<select id="sProvince" onchange="set_city('sCity', document.getElementById('sProvince').value, '0','sBarangay')">
	</select>
	<br>
	<label>City/ Municipality:</label>
	<br>
	<select id="sCity" onchange="set_barangay('sBarangay', document.getElementById('sCity').value, '0')">
	</select>
	<br>
	<label>City/ Barangay:</label>
	<br>
	<select id="sBarangay">
	</select>
	<br><br>
	<form method="post">
		
	</form>
	<button onclick="loadAll('sRegion2', document.getElementById('sRegion').value,
							'sProvince2', document.getElementById('sProvince').value, 
							'sCity2', document.getElementById('sCity').value,
							'sBarangay2', document.getElementById('sBarangay').value);">Transfer</button>
	<br>
	<label>Region 2:</label>
	<br>
	<select id="sRegion2" onchange="set_province('sProvince2', document.getElementById('sRegion2').value, '0','sCity2','sBarangay2');">
	</select>
	<br>
	<label>Province 2:</label>
	<br>
	<select id="sProvince2" onchange="set_city('sCity2', document.getElementById('sProvince2').value, '0','sBarangay2')">
	</select>
	<br>
	<label>City/ Municipality 2:</label>
	<br>
	<select id="sCity2" onchange="set_barangay('sBarangay2', document.getElementById('sCity2').value, '0')">
	</select>
	<br>
	<label>Barangay 2:</label>
	<br>
	<select id="sBarangay2">
	</select>
	<br>
	<br>
	<br>
	<?php
		$sql = 
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
		ON rb.brgyCode = jp.brgyCode
		ORDER BY jp.date_post DESC
		limit $start_from,$num_per_page";

		$result = $con->queries($sql);
		echo "<table class='table table-striped' style='border: solid;'>";
		echo "<tr>";
		echo "<td>";
		echo "Region";
		echo "</td>";
		echo "<td>";
		echo "Province";
		echo "</td>";
		echo "<td>";
		echo "City";
		echo "</td>";
		echo "<td>";
		echo "Brgy";
		echo "</td>";
		echo "<td>";
		echo "Salary";
		echo "</td>";
		echo "</tr>";
		while($row = mysqli_fetch_assoc($result)){
			echo "<tr>";
		    echo "<td>".$func->regionExplode($row['regDesc'])."</td>";
		    echo "<td>".$row['provDesc']."</td>";
		    echo "<td>".$row['citymunDesc']."</td>";
		    echo "<td>".$row['brgyDesc']."</td>";
		    echo "<td> Php ".number_format($row['salary'])."</td>";
		    echo "</td>";
	  	}
	  	echo "</table>";

	  	$sql = "SELECT * FROM `job_post`";
	    $result = $con->queries($sql);
	    $total_records=mysqli_num_rows($result);
	    $total_pages=ceil($total_records/$num_per_page);
	    $prev = $page - 1;
	    $next = $page + 1;
	    if($next > $total_pages){
	    	$next = $total_pages;
	    }
	    if($prev == 0){
	    	$prev = 1;
	    }
	    echo "<a href='address.php?page=".$prev."'><button class='btn btn-dark' style='margin-left: 10px;'>"."Prev"."</button></a>";
	    for($i=1;$i<=$total_pages;$i++)
	    {
	        echo "<a href='address.php?page=".$i."'><button class='btn btn-dark' style='margin-left: 10px;'>".$i."</button></a>";
	    }
	    echo "<a href='address.php?page=".$next."'><button class='btn btn-dark' style='margin-left: 10px;'>"."next"."</button></a>";
	?>
	</div>
</body>
</html>