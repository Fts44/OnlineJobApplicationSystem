function myFunction(id)
{
  var x = document.getElementById(id);

  if (x.type === "password") {
    x.type = "text";
  } 
  else {
    x.type = "password";
  }
}

function loadSelect(Region, regCode, Province, provCode, City, cityCode, Barangay, brgyCode){
	set_region(Region,regCode,Province,City,Barangay);
	set_province(Province, regCode, provCode,City,Barangay);
	set_city(City, provCode, cityCode, Barangay);
	set_barangay(Barangay, cityCode, brgyCode);
}
function set_region(Region, regCode, Province, City, Brgy){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","populateAddress.php?region=1&regCode="+regCode,false);
	xmlhttp.send(null);
	document.getElementById(Province).innerHTML = "<select><option value='0'>SELECT PROVINCE</option></select>";
	document.getElementById(City).innerHTML = "<select><option value='0'>SELECT CITY</option></select>";
	document.getElementById(Brgy).innerHTML = "<select><option value='0'>SELECT BARANGAY</option></select>";		
	document.getElementById(Region).innerHTML=xmlhttp.responseText;
}
function set_province(Province, regCode, provCode, City, Brgy){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","populateAddress.php?province=1&provCode="+provCode+"&regCode="+regCode,false);
	xmlhttp.send(null);
	document.getElementById(City).innerHTML = "<select><option value='0'>SELECT CITY</option></select>";
	document.getElementById(Brgy).innerHTML = "<select><option value='0'>SELECT BARANGAY</option></select>";			
	document.getElementById(Province).innerHTML=xmlhttp.responseText;
}
function set_city(City, provCode, cityCode, Brgy){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","populateAddress.php?city=1&cityCode="+cityCode+"&provCode="+provCode,false);
	xmlhttp.send(null);
	document.getElementById(Brgy).innerHTML = "<select><option value='0'>SELECT BARANGAY</option></select>";		
	document.getElementById(City).innerHTML=xmlhttp.responseText;
}
function set_barangay(Brgy, cityCode, brgyCode){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","populateAddress.php?brgy=1&brgyCode="+brgyCode+"&cityCode="+cityCode,false);
	xmlhttp.send(null);		
	document.getElementById(Brgy).innerHTML=xmlhttp.responseText;
}
function populate_JobPosting(page){
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET","populateJobPosting.php?page="+page,false);
	xmlhttp.send(null);		
	document.getElementById('grid').innerHTML=xmlhttp.responseText;
}
function refreshPage(){
    window.location.reload();
} 