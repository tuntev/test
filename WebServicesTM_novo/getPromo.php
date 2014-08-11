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

$sql = "select pr.vazi_od, pr.vazi_do, pr.broj, pr.artikal, pr.norm_cena, pr.promo_cena, p.art_naziv, p.opis, p.slika, p.sifra
FROM promo pr
INNER JOIN
produkti p
ON
p.sifra=pr.artikal WHERE 1=1";

if(isset($_GET['pLow'])&&!empty($_GET['pLow']))
{
	$pLow = $_GET['pLow'];
	$sql.=" AND pr.promo_cena >= '" . mysql_real_escape_string($pLow) . "'";
}

if(isset($_GET['pHigh'])&&!empty($_GET['pHigh']))
{
	$pHigh = $_GET['pHigh'];
	$sql.=" AND pr.promo_cena <= '" . mysql_real_escape_string($pHigh) . "'";
}


if(isset($_GET['artKeyword'])&&!empty($_GET['artKeyword']))
{
	$artKeyword = $_GET['artKeyword'];
	$sql.=" AND p.art_naziv LIKE '%" . mysql_real_escape_string($artKeyword) . "%'";
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
		
		$post["vazi_od"]  = $row["vazi_od"];
        $post["vazi_do"] = $row["vazi_do"];
        $post["broj"]    = $row["broj"];
        $post["norm_cena"]  = $row["norm_cena"];
		$post["promo_cena"]  = $row["promo_cena"];
		
		$post["art_naziv"] = $row["art_naziv"];
		$post["opis"] = $row["opis"];
		$post["slika"] = $row["slika"];
        
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
