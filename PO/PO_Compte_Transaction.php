<?php

global $cnx;
include("../include/connexion.inc.php");
include('../include/verifyconnexion.inc.php');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="po_compte.css">
    <title>JeFinance</title>
</head>
<body>
<?php
$onit = "Remise";
include("../include/User_po_navbar.inc.php"); // Navbar
?>
