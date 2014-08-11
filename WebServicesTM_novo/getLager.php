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
$sql = "select DISTINCT kg.klasa, kg.grupa, l.artikal, l.skladiste, s.naziv, s.adresa, s.telefon, s.rab_vreme, s.sifra, 
		p.art_naziv, p.klasa, p.brand, p.grupa, p.opis, p.pcena, p.slika, p.sifra as 'artSifra',
		kg.klasa_mak, kg.klasa_ang, kg.grupa_mak, kg.grupa_ang
		
		FROM lager l
		INNER JOIN
		skladiste s
		ON
		s.sifra = l.skladiste
		INNER JOIN
		produkti p
		ON
		p.sifra = l.artikal
		INNER JOIN
		klasa_grupa kg
		ON
		p.klasa = kg.klasa AND p.grupa = kg.grupa
		WHERE 1=1
		";

if(isset($_GET['sifra'])&&!empty($_GET['sifra']))
{
	$sifra = $_GET['sifra'];
	$sql.=" AND s.sifra = '" . mysql_real_escape_string($sifra) . "'";
}		

if(isset($_GET['artSifra'])&&!empty($_GET['artSifra']))
{
	$artSifra = $_GET['artSifra'];
	$sql.=" AND p.sifra = '" . mysql_real_escape_string($artSifra) . "'";
}		

if(isset($_GET['klasa'])&&!empty($_GET['klasa']))
{
	$klasa = $_GET['klasa'];
	$sql.=" AND p.klasa = '" . mysql_real_escape_string($klasa) . "'";
}		

if(isset($_GET['grupa'])&&!empty($_GET['grupa']))
{
	$grupa = $_GET['grupa'];
	$sql.=" AND p.grupa = '" . mysql_real_escape_string($grupa) . "'";
}		

if(isset($_GET['brand'])&&!empty($_GET['brand']))
{
	$brand = $_GET['brand'];
	$sql.=" AND p.brand = '" . mysql_real_escape_string($brand) . "'";
}

if(isset($_GET['bKeyword'])&&!empty($_GET['bKeyword']))
{
	$keyword = $_GET['bKeyword'];
	$sql.=" AND p.brand LIKE '%" . mysql_real_escape_string($keyword) . "%'";
}

if(isset($_GET['gKeyword'])&&!empty($_GET['gKeyword']))
{
	$keyword = $_GET['gKeyword'];
	$sql.=" AND p.grupa LIKE '%" . mysql_real_escape_string($keyword) . "%'";
}

if(isset($_GET['artKeyword'])&&!empty($_GET['artKeyword']))
{
	$artKeyword = $_GET['artKeyword'];
	$sql.=" AND p.art_naziv LIKE '%" . mysql_real_escape_string($artKeyword) . "%'";
}

if(isset($_GET['pLow'])&&!empty($_GET['pLow']))
{
	$pLow = $_GET['pLow'];
	$sql.=" AND p.pcena >= '" . mysql_real_escape_string($pLow) . "'";
}

if(isset($_GET['pHigh'])&&!empty($_GET['pHigh']))
{
	$pHigh = $_GET['pHigh'];
	$sql.=" AND p.pcena <= '" . mysql_real_escape_string($pHigh) . "'";
}

$sql.=" ORDER BY p.pcena ASC";		
//echo $sql; die;
$rows = mysql_query($sql);

if (mysql_num_rows($rows)) {
    $response["success"] = 1;
    $response["message"] = "Post Available!";
    $response["posts"]   = array();
    
    while($row=mysql_fetch_assoc($rows)){
        $post             = array();
		// tuka treba kolonite od tabelata sto ke gi citas da se stavat vo niza
		// samo imeto vo zagradite smeni go
		
		$post["sifra"]  = $row["sifra"];
		$post["naziv"]  = $row["naziv"];
        $post["adresa"] = $row["adresa"];
		$post["telefon"] = $row["telefon"];
		$post["rab_vreme"] = $row["rab_vreme"];
		$post["art_naziv"] = $row["art_naziv"];
		$post["opis"] = $row["opis"];
		$post["klasa"] = $row["klasa"];
		$post["klasa_mak"] = $row["klasa_mak"];
		$post["klasa_ang"] = $row["klasa_ang"];
		$post["grupa"] = $row["grupa"];
		$post["grupa_mak"] = $row["grupa_mak"];
		$post["grupa_ang"] = $row["grupa_ang"];
		$post["brand"] = $row["brand"];
		$post["pcena"] = $row["pcena"];
		$post["slika"] = $row["slika"];
        $post["artSifra"] = $row["artSifra"];
        
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
