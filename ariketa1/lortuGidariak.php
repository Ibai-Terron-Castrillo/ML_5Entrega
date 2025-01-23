<?php

require_once("db.php");

if ($_GET["akzioa"] == "lortuGidariak") {

    $conn = konexioaSortu();

    $sql = "SELECT * FROM podioa order by Postua";
    $result = $conn->query($sql);
    $podioa = [];

    if ($result->num_rows > 0) {
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            $podioa[$counter] = ["Postua" => $row["Postua"], "Dortsala" => $row["Dortsala"], "Izena" => $row["Izena"]];
            $counter++;
        }
        
        $podioa["kopurua"] = $counter;
        echo json_encode($podioa);
        die;

    } else {
        $podioa["kopurua"] = 0;
        echo json_encode($podioa);
        die;
    }

}
