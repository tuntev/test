<?php

$user = "username";
$pass = "password";
$host = "myhost/myservicename";

$conn = oci_connect($user, $pass, $host);
$sql  = oci_parse($conn, "SELECT * FROM emp");

oci_execute($sql);

$rows = array();
while($r = oci_fetch_assoc($sql)) {
$rows[] = $r;
 }

if ($rows) {
    $response["success"] = 1;
    $response["message"] = "Post Available!";
    $response["posts"]   = array();
    
    foreach ($rows as $row) {
        $post             = array();
	
        //this line is new:
        $post["post_id"]  = $row["post_id"];

        $post["username"] = $row["username"];
        $post["title"]    = $row["title"];
        $post["message"]  = $row["message"];
        
        
        //update our repsonse JSON data
        array_push($response["posts"], $post);
    }
    
    // echoing JSON response
    echo json_encode($response);
    
    
} else {
    $response["success"] = 0;
    $response["message"] = "No Post Available!";
    die(json_encode($response));
} 
?>