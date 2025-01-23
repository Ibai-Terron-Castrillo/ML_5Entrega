<?php

require_once("db.php");

$dortsala=$_GET ["dortsala"];
$postua=$_GET["postua"];

if ($_GET["eguneratu"] == "podioaEguneratu") {

    $conn = konexioaSortu();

    $sql = "UPDATE podioa set Postua = $postua WHERE dortsala=$dortsala";
    $result = $conn->query($sql);
    die;
}