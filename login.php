<?php
$username = $_POST['username'];
$userpass = $_POST['userpass'];

//$username = "tunte";
//$userpass = "1a1dc91c907325c69271ddf0c944bc72";

if (selectFromDB() == $userpass)
	echo "Accept";
else
	echo "Denied";
function selectFromDB(){
	global $username;	
	
	$host="localhost"; //replace with database hostname 
	$user="root"; //replace with database username 
	$password=""; //replace with database password 
	$db_name="test"; //replace with database name
	$db_table="tutorial";

	$con=mysql_connect("$host", "$user", "$password")or die("cannot connect"); 
	mysql_select_db("$db_name")or die("cannot select DB");
	$sql = "select userpass from tutorial WHERE username='".$username."'"; 
	$result = mysql_query($sql);
	//$json = array();
	
	if(mysql_num_rows($result)){
		return mysql_result($result,0);
	}
	else
		return "0";
	mysql_free_result($result);
	mysql_close($con);
}
//echo json_encode($json); 
?>