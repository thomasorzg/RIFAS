<?php
/*
// Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"], 1);
$active_group = 'default';
$query_builder = TRUE;
*/

// Connect to DB
$conn = mysqli_connect("us-cdbr-east-05.cleardb.net", "bd5451d44bcc7d", "180c444f", "heroku_caebebeebb91053");

if(!$conn) {
	echo "Database failed to open!";
}

mysqli_set_charset($conn, "utf8");

?>
