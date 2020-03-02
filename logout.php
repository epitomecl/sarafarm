<?php 
session_start();
$_SESSION["USERID"] = "";
header("location:index.php");
?>