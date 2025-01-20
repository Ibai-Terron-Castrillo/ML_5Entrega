<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Podioa</title>
    <style>
        #tabla{
            border: 2px solid black ;
            width: 250px;
        }
        .border{
            border: 2px solid black ;
            text-align: center;
        }
        button{
            margin: 10px;
        }
    </style>
</head>
<body>
    <?php

    require_once("db.php");
    
    $conn = konexioaSortu();
    
    ?>
    
    <button class="taulaReload">Taula birkargatu</button>
    <br>
    
    <?php
    
    
    $kontsulta = "SELECT * FROM podioa order by Postua";
    $result = $conn->query($kontsulta);
    
    if ($result->num_rows > 0) {
        echo "<table  class='zerrenda' id='tabla' >";
        echo "<tr>";
        echo "<th class='border'>Postua</th>";
        echo "<th class='border'>Dortsala</th>";
        echo "<th class='border'>Izena</th>";
        echo "</tr>";
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='border'>" . $row["Postua"] . "</td>";
            echo "<td class='border'>" . $row["Dortsala"] . "</td>";
            echo "<td class='border'>" . $row["Izena"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    
    } else {
        echo "Ez dago informaziorik";
    }
    $conn->close();
    
    ?>
    
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    
    <script>
        $(document).ready(function () {
    
            $(".taulaReload").on("click", function () {
                taulaBirkargatu();
            });
    
            setInterval(taulaBirkargatu, 60000);
    
        });
    
        function taulaBirkargatu() {
    
            $.ajax({
                url: "lortuGidariak.php",
                type: "GET",
                data: { akzioa: "lortuGidariak" }
            })
                .done(function (bueltanDatorrenInformazioa) {
                    
                    var info = JSON.parse(bueltanDatorrenInformazioa);
                    if (info.kopurua > 0) {
                        $(".zerrenda").html("");
                        $(".zerrenda").append("<tr>");
                        $(".zerrenda").append("<th class='border'>Postua</th>");
                        $(".zerrenda").append("<th class='border'>Dortsala</th>");
                        $(".zerrenda").append("<th class='border'>Izena</th>");
                        $(".zerrenda").append("</tr>");
                        for (var i = 0; i < info.kopurua; i++) {
                            $(".zerrenda").append("<tr>");
                            $(".zerrenda").append("<td class='border'>" + info[i].Postua + "</td>");
                            $(".zerrenda").append("<td class='border'>" + info[i].Dortsala + "</td>");
                            $(".zerrenda").append("<td class='border'>" + info[i].Izena + "</td>");
                            $(".zerrenda").append("</tr>");
                        }
                    } else {
                        alert("Ez da elementurik kargatu");
                    }
    
                })
                .fail(function () {
                    alert("gaizki joan da");
                })
                .always(function () {
                    // alert("aa");
                });
        }
    </script>
</body>
</html>