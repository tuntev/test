<?php
error_reporting(E_ERROR);
$host="ffmwpnew.db.10157209.hostedresource.com"; //replace with database hostname 
$username="ffmwpnew"; //replace with database username 
$password="Admin123!"; //replace with database password 
$db_name="ffmwpnew"; //replace with database name
 
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

$sql = "SELECT post_title, guid, post_content, post_date, post_name FROM wp_posts WHERE post_status = 'publish' AND post_type='post'"; 


//echo $sql;die;
$rows = mysql_query($sql);

if (mysql_num_rows($rows)) {
    $response["success"] = 1;
    $response["message"] = "Post Available!";
	$response["time"] = time();
    $response["posts"]   = array();
    
    while($row=mysql_fetch_assoc($rows)){
        $post             = array();
		$post["post_title"] = $row["post_title"];
		$post["guid"]  = $row["guid"];
        $post["post_content"]    = $row["post_content"];
		$post["post_date"]    = $row["post_date"];
		$post["post_name"]    = $row["post_name"];
        
        
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
