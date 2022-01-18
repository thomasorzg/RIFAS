<?php

$conn = mysqli_connect('localhost','rifasma1_root','malverde0124','rifasma1_booking_db');
if(!$conn) {
	echo "database failed to open...";
}
mysqli_set_charset($conn, 'utf8');
?>