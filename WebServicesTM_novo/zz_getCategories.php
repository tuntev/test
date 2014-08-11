<?php
error_reporting(E_ERROR);
$host="62.162.101.2"; //replace with database hostname 
$username="valeri"; //replace with database username 
$password="2868"; //replace with database password 
$db_name="sakila"; //replace with database name
 
function errorConnecting(){
	$response["success"] = 0;
    $response["message"] = "Cannot connect to database!";
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}

function errorSelectingDatabase(){
	$response["success"] = 0;
    $response["message"] = "Cannot select database!";
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}

$con=mysql_connect("$host", "$username", "$password") or errorConnecting(); 
mysql_set_charset("UTF8", $con);
mysql_select_db("$db_name")or errorSelectingDatabase();
$sql = "select DISTINCT klasa, grupa, klasa_mak, klasa_ang, grupa_mak, grupa_ang, Kategorija_mk, Kategorija_ang, kateg from klasa_grupa WHERE 1=1"; 

if(isset($_GET['kateg'])&&!empty($_GET['kateg']))
{
	$kateg = $_GET['kateg'];
	$sql.=" AND kateg = '" . mysql_real_escape_string($kateg) . "'";
}

if(isset($_GET['klasa_mak'])&&!empty($_GET['klasa_mak']))
{
	$klasa_mak = $_GET['klasa_mak'];
	$sql.=" AND klasa_mak = '" . mysql_real_escape_string($klasa_mak) . "'";
}

if(isset($_GET['klasa_ang'])&&!empty($_GET['klasa_ang']))
{
	$klasa_ang = $_GET['klasa_ang'];
	$sql.=" AND klasa_ang = '" . mysql_real_escape_string($klasa_ang) . "'";
}

if(isset($_GET['grupa_mak'])&&!empty($_GET['grupa_mak']))
{
	$grupa_mak = $_GET['grupa_mak'];
	$sql.=" AND grupa_mak = '" . mysql_real_escape_string($grupa_mak) . "'";
}

if(isset($_GET['grupa_ang'])&&!empty($_GET['grupa_ang']))
{
	$grupa_ang = $_GET['grupa_ang'];
	$sql.=" AND grupa_ang = '" . mysql_real_escape_string($grupa_ang) . "'";
}

if(isset($_GET['klasa'])&&!empty($_GET['klasa']))
{
	$klasa = $_GET['klasa'];
	$sql.=" AND klasa = '" . mysql_real_escape_string($klasa) . "'";
}

if(isset($_GET['grupa'])&&!empty($_GET['grupa']))
{
	$grupa = $_GET['grupa'];
	$sql.=" AND grupa = '" . mysql_real_escape_string($grupa) . "'";
}

if(isset($_GET['kategorija_mk'])&&!empty($_GET['kategorija_mk']))
{
	$kategorija_mk = $_GET['kategorija_mk'];
	$sql.=" AND kategorija_mk = '" . mysql_real_escape_string($kategorija_mk) . "'";
}

if(isset($_GET['kategorija_ang'])&&!empty($_GET['kategorija_ang']))
{
	$kategorija_ang = $_GET['kategorija_ang'];
	$sql.=" AND kategorija_ang = '" . mysql_real_escape_string($kategorija_ang) . "'";
}


//echo $sql;die;
$rows = mysql_query($sql);

if (mysql_num_rows($rows)) {
    $response["success"] = 1;
    $response["message"] = "Post Available!";
    $response["posts"]   = array();
    
    while($row=mysql_fetch_assoc($rows)){
        $post             = array();
		// tuka treba kolonite od tabelata sto ke gi citas da se stavat vo niza
		// samo imeto vo zagradite smeni go
		
		$post["klasa"]  = $row["klasa"];
        $post["grupa"] = $row["grupa"];
        $post["klasa_mak"]    = $row["klasa_mak"];
        $post["klasa_ang"]  = $row["klasa_ang"];
        $post["grupa_mak"]  = $row["grupa_mak"];
		$post["grupa_ang"]  = $row["grupa_ang"];
		$post["kateg"]  = $row["kateg"];
		$post["Kategorija_mk"]  = $row["Kategorija_mk"];
		$post["Kategorija_ang"]  = $row["Kategorija_ang"];
        
        //update our repsonse JSON data
        array_push($response["posts"], $post);
    }
    
    // echoing JSON response
    echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    
    
} else {
    $response["success"] = 0;
    $response["message"] = "No Post Available!";
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}

?>
