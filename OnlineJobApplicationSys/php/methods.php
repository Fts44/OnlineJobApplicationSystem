<?php

	class connection
	{
		public $con;
		function __construct()
		{
			$this->con = mysqli_connect("localhost","root","","OnlineJobApplicationSys");
			if(!$this->con){
				die("Connection failed: " . mysqli_connect_error());
			}
		}
		public function queries($sql){
			$result = mysqli_query($this->con,$sql);
			return $result;
		}
	}
	
	class methods extends connection{
		
		function regionExplode($regionDesc){
			$a = explode('(', $regionDesc);
            $b = explode(')', $a[1]);
            return $b[0];
		}

		function isNULL($value){
			if(empty($value) || $value == 0){
		      return 'NULL';
		    }
		    else{
		      return $value;
		    }
		}

		function notNULL($var, $varname){
			if($var == "" || $var == "0"){
				return $varname." IS NOT NULL";
			}
			else{
				return $varname." LIKE '%".$var."%'";
			}
		}
	}
			
?>