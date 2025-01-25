<?php

require_once("db.php");

if ($_GET["akzioa"] == "lortuHerriak") {
    $eskualdea = $_GET["eskualdea"];
    $conn = konexioaSortu();

    $sql = "SELECT herria FROM eskualdeak where eskualdea = '$eskualdea'";
    $result = $conn->query($sql);
    $herriak = [];

    if ($result->num_rows > 0) {
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            $herriak[$counter] = ["herria" => $row["herria"]];
            $counter++;
        }
        
        $herriak["kopurua"] = $counter;
        echo json_encode($herriak);
        die;

    } else {
        $herriak["kopurua"] = 0;
        echo json_encode($herriak);
        die;
    }

}
