<?php

session_start();

if (!isset($_SESSION["usuari"])) {

    header("Location: login.php");

    exit();

}
?>

<!DOCTYPE html>

<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Pàgina privada</title>
    <meta http-equiv="refresh" content="1;url=../index.php">
    <link href="../css/login.css" rel="stylesheet">

</head>

<body>

    <h1>Has entrat correctament</h1>

</body>

</html>