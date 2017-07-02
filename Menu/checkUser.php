<?php

$userName = "";

if (isset($_SESSION['user']) && !empty($_SESSION['user'])){
    $userName = $_SESSION["user"];
} else {
    $userName = "general user";
}



?>