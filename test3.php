<?php

$host="62.162.101.2"; //replace with database hostname 
$username="valeri"; //replace with database username 
$password="april9"; //replace with database password 
$db_name="sakila"; //replace with database name
 
$con=mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_set_charset("UTF8", $con);
mysql_select_db("$db_name")or die("cannot select DB");
$sql = "select * from skladiste"; 
$result = mysql_query($sql);
$json = array();
 
if(mysql_num_rows($result)){
while($row=mysql_fetch_assoc($result)){
$json['sifra'][]=$row;
}
}
mysql_close($con);

echo json_encode($json, JSON_UNESCAPED_UNICODE );


?> 
