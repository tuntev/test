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
$sql = "select MAX(PCENA) as pMax, MIN(PCENA)as pMin from produkti WHERE 1=1"; 

if(isset($_GET['klasa'])&&!empty($_GET['klasa']))
{
	$klasa = $_GET['klasa'];
	$sql.=" AND KLASA = '" . mysql_real_escape_string($klasa) . "'";
}

if(isset($_GET['sifra'])&&!empty($_GET['sifra']))
{
	$sifra = $_GET['sifra'];
	$sql.=" AND sifra = '" . mysql_real_escape_string($sifra) . "'";
}

if(isset($_GET['pLow'])&&!empty($_GET['pLow']))
{
	$pLow = $_GET['pLow'];
	$sql.=" AND PCENA >= '" . mysql_real_escape_string($pLow) . "'";
}

if(isset($_GET['pHigh'])&&!empty($_GET['pHigh']))
{
	$pHigh = $_GET['pHigh'];
	$sql.=" AND PCENA <= '" . mysql_real_escape_string($pHigh) . "'";
}

if(isset($_GET['grupa'])&&!empty($_GET['grupa']))
{
	$grupa = $_GET['grupa'];
	$sql.=" AND GRUPA = '" . mysql_real_escape_string($grupa) . "'";
}

if(isset($_GET['gKeyword'])&&!empty($_GET['gKeyword']))
{
	$keyword = $_GET['gKeyword'];
	$sql.=" AND GRUPA LIKE '%" . mysql_real_escape_string($keyword) . "%'";
}

if(isset($_GET['artKeyword'])&&!empty($_GET['artKeyword']))
{
	$artKeyword = $_GET['artKeyword'];
	$sql.=" AND ART_NAZIV LIKE '%" . mysql_real_escape_string($artKeyword) . "%'";
}

if(isset($_GET['bKeyword'])&&!empty($_GET['bKeyword']))
{
	$keyword = $_GET['bKeyword'];
	$sql.=" AND BRAND LIKE '%" . mysql_real_escape_string($keyword) . "%'";
}

if(isset($_GET['brand'])&&!empty($_GET['brand']))
{
	$brand = $_GET['brand'];
	$sql.=" AND BRAND = '" . mysql_real_escape_string($brand) . "'";
}

if(isset($_GET['sifra'])&&!empty($_GET['sifra']))
{
	$sifra = $_GET['sifra'];
	$sql.=" AND sifra = '" . mysql_real_escape_string($sifra) . "'";
}


$rows = mysql_query($sql);

if (mysql_num_rows($rows)) {
    $response["success"] = 1;
    $response["message"] = "Post Available!";
    $response["posts"]   = array();
    
    while($row=mysql_fetch_assoc($rows)){
        $post             = array();
		// tuka treba kolonite od tabelata sto ke gi citas da se stavat vo niza
		// samo imeto vo zagradite smeni go
		
		$post["pMin"]  = $row["pMin"];
        $post["pMax"] = $row["pMax"];
        
        
        //update our repsonse JSON data
        array_push($response["posts"], $post);
    }
    
    // echoing JSON response
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    
    
} else {
    $response["success"] = 0;
    $response["message"] = "No Post Available!";
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}

?>
