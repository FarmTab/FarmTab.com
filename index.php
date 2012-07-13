<?
include('header.php'); 

$url = "pages/" . $_GET['page'] . ".php";
if(!file_exists($url)) $url = "pages/home.php";
include($url);

include('footer.php');
?>