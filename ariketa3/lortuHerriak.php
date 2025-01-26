<?php

require_once("db.php"); // 'db.php' fitxategia inportatzen du

if ($_GET["akzioa"] == "lortuHerriak") { // 'akzioa' parametroaren balioa 'lortuHerriak' definitzen du
    $eskualdea = $_GET["eskualdea"]; // 'eskualdea' parametroaren balioa hartzen du
    $conn = konexioaSortu(); // Datu-basearekin konexioa sortzen du

    $sql = "SELECT herria FROM eskualdeak where eskualdea = '$eskualdea'"; // Hautatutako eskualdearen herriak lortzeko SQL kontsulta
    $result = $conn->query($sql); //
    $herriak = []; // Herriak gordetzeko array hutsa 

    if ($result->num_rows > 0) { 
        $counter = 0; // Herrien zenbaketa hasten du
        while ($row = $result->fetch_assoc()) { 
            $herriak[$counter] = ["herria" => $row["herria"]]; // Lerroko "herria" balioa array-an gorde
            $counter++; // Zenbatzaileari 1 gehitzen dio
        }
        
        $herriak["kopurua"] = $counter; // Herrien kopurua array-an gehitzen du
        echo json_encode($herriak); // JSON formatuan herrien array-a bueltatzen du
        die; 

    } else { 
        $herriak["kopurua"] = 0; // Herrien kopurua 0 jartzen du
        echo json_encode($herriak); // JSON formatuan datu hutsak bueltatzen ditu.
        die; // Prozesua amaitzen du.
    }

}

