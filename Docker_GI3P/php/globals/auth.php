<?php

session_start();

if (!isset($_SESSION["usuari"])) {

    header("Location: globals/login.php");

    exit();

}

?>