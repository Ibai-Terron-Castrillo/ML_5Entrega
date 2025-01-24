<?php

require_once("db.php");

if (isset($_GET['dortsala']) && isset($_GET['postua'])) {
    $dortsala = intval($_GET['dortsala']);
    $postua_berria = intval($_GET['postua']);

    $conn = konexioaSortu();

    $sql_initial = "SELECT Dortsala, Postua FROM podioa";
    $result_initial = $conn->query($sql_initial);
    $initial_positions = [];
    while ($row = $result_initial->fetch_assoc()) {
        $initial_positions[$row['Dortsala']] = $row['Postua'];
    }

    // Dorsalaren arabera egungo postua lortu
    $sql = "SELECT Postua FROM podioa WHERE Dortsala = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $dortsala);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $current_position = $result->fetch_assoc()['Postua'];

        if ($current_position != $postua_berria) {
            $conn->begin_transaction();

            try {
                if ($current_position > $postua_berria) {
                    // Gidaria gora mugitu eta besteak beherantz mugitu
                    $sql_update = "UPDATE podioa SET Postua = Postua + 1 WHERE Postua >= ? AND Postua < ?";
                } else {
                    // Gidaria beherantz mugitu eta besteak gorantz mugitu
                    $sql_update = "UPDATE podioa SET Postua = Postua - 1 WHERE Postua <= ? AND Postua > ?";
                }
                
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ii", $postua_berria, $current_position);
                $stmt_update->execute();

                // Gidariaren postua eguneratu
                $sql_update_runner = "UPDATE podioa SET Postua = ? WHERE Dortsala = ?";
                $stmt_runner = $conn->prepare($sql_update_runner);
                $stmt_runner->bind_param("ii", $postua_berria, $dortsala);
                $stmt_runner->execute();

                $conn->commit();

                // Koloreen informazioa itzuli
                $sql_colors = "SELECT Postua, Dortsala, Izena FROM podioa ORDER BY Postua";
                $result_colors = $conn->query($sql_colors);

                $table = "<table id='tabla'>";
                $table .= "<tr><th class='border'>Postua</th><th class='border'>Dortsala</th><th class='border'>Izena</th></tr>";

                while ($row = $result_colors->fetch_assoc()) {
                    $color = "";
                    $dortsala_current = $row['Dortsala'];
                    $initial_postua = $initial_positions[$dortsala_current];
                    $final_postua = $row['Postua'];

                    if ($final_postua < $initial_postua) {
                        $color = "style='background-color: green;'";
                    } elseif ($final_postua > $initial_postua) {
                        $color = "style='background-color: red;'";
                    }

                    $table .= "<tr $color><td class='border'>{$row['Postua']}</td><td class='border'>{$row['Dortsala']}</td><td class='border'>{$row['Izena']}</td></tr>";
                }

                $table .= "</table>";
                echo $table;

            } catch (Exception $e) {
                $conn->rollback();
                echo json_encode(["success" => false, "error" => $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Postua berdina da dagoeneko."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Dortsala ez da aurkitu."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Parametroak ez dira baliodunak."]);
}
