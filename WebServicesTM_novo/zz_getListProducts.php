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
$sql = "select DISTINCT GRUPA from produkti"; 


$rows = mysql_query($sql);

if (mysql_num_rows($rows)) {
    $response["success"] = 1;
    $response["message"] = "Post Available!";
    $response["posts"]   = array();
    
    while($row=mysql_fetch_assoc($rows)){
        $post             = array();
		// tuka treba kolonite od tabelata sto ke gi citas da se stavat vo niza
		// samo imeto vo zagradite smeni go

        $post["GRUPA"]  = $row["GRUPA"];
        
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
