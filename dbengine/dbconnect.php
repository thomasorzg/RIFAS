<?php

$conn = mysqli_connect('localhost','root','tommyo24','boletos');
if(!$conn) {
	echo "database failed to open...";
}
mysqli_set_charset($conn, 'utf8');
?>