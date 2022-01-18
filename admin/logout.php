<?php
session_start();
// >_<
unset($_SESSION['username']);
header("location: login.php");
?>