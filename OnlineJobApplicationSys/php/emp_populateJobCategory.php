<?php
	include 'methods.php';
	$con = new connection();

	$job_category = $_GET['category'];

    $sql_jpCategory = "SELECT * FROM `job_category";
	$result_jpCategory = $con->queries($sql_jpCategory);
                  
    while($row_jpCategory = mysqli_fetch_assoc($result_jpCategory)){
	    if($job_category == $row_jpCategory['category_name']){
	    	echo 
	    	"<option selected
				value='".$row_jpCategory['category_name']."'>"
				.$row_jpCategory['category_name']."
			</option>";
	    }
	    else{
	    	echo 
	    	"<option 
				value='".$row_jpCategory['category_name']."'>"
				.$row_jpCategory['category_name']."
			</option>";
	    }	
    }

?>