<?php
include 'util.php';
my_session_start();

my_session_destroy();
$location = "index.php";
//header("Location: index.php");
echo("<script>location.href='$location'</script>");
