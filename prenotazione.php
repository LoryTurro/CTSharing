<?php
session_start();

require "action.php";

if (!isset($_SESSION["logged"]))
    header("location: login.php");

if (first_register_socio($_SESSION["username"]) == "registrazione")
    header("location: register_socio.php");

?>