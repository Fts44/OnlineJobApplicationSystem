<?php	
	include 'methods.php';
	$con = new connection();
	$func = new methods();
	
	$region = $_GET['region'];
	$regCode = $_GET['regCode'];

	$province = $_GET['province'];
	$provCode = $_GET['provCode'];

	$city = $_GET['city'];
	$cityCode = $_GET['cityCode'];

	$brgy = $_GET['brgy'];
	$brgyCode = $_GET['brgyCode'];
     
    if($region!="" and $regCode!=""){
    	$sql = "SELECT * FROM `refregion`";
		$result = $con->queries($sql);
		echo "<option value='0'>SELECT REGION</option>";
		while($row = mysqli_fetch_assoc($result)){
			$a = explode('(', $row['regDesc']);
			$b = explode(')', $a[1]);
			if($regCode == $row['regCode']){
				echo "<option value=".$row['regCode']." selected>".$b[0]."</option>";
			}
			else{
				echo "<option value=".$row['regCode'].">".$b[0]."</option>";
			}
		}
    }

    if($province!="" and $provCode!="" and $regCode!=""){
    	$sql = "SELECT * FROM `refprovince` WHERE `regCode`=$regCode";
    	$result = $con->queries($sql);
    	echo "<option value='0'>SELECT PROVINCE</option>";
    	while($row = mysqli_fetch_assoc($result)){
    		if($provCode == $row['provCode']){
    			echo "<option value=".$row['provCode']." selected>".$row['provDesc']."</option>";
    		}
    		else{
    			echo "<option value=".$row['provCode'].">".$row['provDesc']."</option>";
    		}
    	}
    }

    if($city!="" and $cityCode!="" and $provCode!=""){
    	$sql = "SELECT * FROM `refcitymun` WHERE `provCode`=$provCode";
    	$result = $con->queries($sql);
    	echo "<option value='0'>SELECT CITY</option>";
    	while($row = mysqli_fetch_assoc($result)){
    		if($cityCode == $row['citymunCode']){
    			echo "<option value=".$row['citymunCode']." selected>".$row['citymunDesc']."</option>";
    		}
    		else{
    			echo "<option value=".$row['citymunCode'].">".$row['citymunDesc']."</option>";
    		}
    	}
    }

    if($brgy!="" and $brgyCode!="" and $cityCode!=""){
    	$sql = "SELECT `brgyCode`, UPPER(`brgyDesc`) AS `brgyDesc` FROM `refbrgy` WHERE `citymunCode`=$cityCode ORDER BY brgyDesc ASC";
    	$result = $con->queries($sql);
    	echo "<option value='0'>SELECT BARANGAY</option>";
    	while($row = mysqli_fetch_assoc($result)){
    		if($brgyCode == $row['brgyCode']){
    			echo "<option value=".$row['brgyCode']." selected>".$row['brgyDesc']."</option>";
    		}
    		else{
    			echo "<option value=".$row['brgyCode'].">".$row['brgyDesc']."</option>";
    		}
    	}
    }


?>