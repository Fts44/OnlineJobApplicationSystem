<?php
	
	$status = $_GET['status'];

	if($status == 'Open'){
		echo "<option selected value='Open'>Open</option>";
		echo "<option value='Close'>Close</option>";
	}
	else{
		echo "<option value='Open'>Open</option>";
		echo "<option selected value='Close'>Close</option>";
	}
	
?>