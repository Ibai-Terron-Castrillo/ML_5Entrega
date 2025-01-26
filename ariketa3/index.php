<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" /> <
    <title>Herria Aukeratu</title> <!-- Web orriaren izenburua ezartzen du -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script> <!-- jQuery liburutegiaren azken bertsioa gehitzen du -->
</head>

<body>
    <?php
    require_once("db.php"); // 'db.php' fitxategia inportatzen du
    $conn = konexioaSortu(); // Datu-basearekin konexioa sortzen du
    ?>

    <form action="" method="GET"> <!-- GET metodoa erabiliz formularioa sortzen du -->
        <label for="eskualdea">Eskualdea</label> <!-- Eskualdea hautatzeko -->
        <select name="eskualdea" id="eskualdea" onchange="herriakEguneratu()"> <!-- Eskualdeak hautatzeko zerrenda, aldaketa gertatzen denean 'herriakEguneratu()' funtzioa exekutatzen da -->
            <option value="">Hautatu eskualdea</option> 
            <?php
            $kontsulta = "SELECT DISTINCT eskualdea FROM eskualdeak"; // Datu-basean eskualde bakarrak lortzeko SQL kontsulta prestatzen du
            $result = $conn->query($kontsulta); // SQL kontsulta exekutatzen du
            if ($result && $result->num_rows > 0) { // Emaitzak existitzen diren edo ez ikusten du
                while ($row = $result->fetch_assoc()) { // Datuak lerro bakoitza iteratzen ditu
                    echo '<option value="' . htmlspecialchars($row["eskualdea"]) . '">' . htmlspecialchars($row["eskualdea"]) . '</option>'; // Eskualde bakoitza hautapenerako gehitzen du
                }
            }
            ?>
        </select>

        <label for="herria">Herria</label> <!-- Herria hautatzeko etiketa -->
        <select name="herria" id="herria"> <!-- Herriak hautatzeko zerrenda -->
            <option value="">Hautatu herria</option> <!-- Lehenetsitako aukera, herria hautatzeko -->
        </select>
    </form>

    <script>
        function herriakEguneratu() { 
            const eskualdea = $("#eskualdea").val(); // Hautatutako eskualdea hartzen du
            $.ajax({
                url: "lortuHerriak.php", // AJAX eskaera bidaltzeko helbidea
                method: "GET", 
                data: { akzioa: "lortuHerriak", eskualdea: eskualdea } // Eskaerari bidalitako datuak
            })
                .done(function (bueltanDatorrenInformazioa) { 
                    var info = JSON.parse(bueltanDatorrenInformazioa); // Bueltan jasotako JSON informazioa objektu bihurtzen du
                    if (info.kopurua > 0) { 
                        $("#herria").html(""); // Herrien zerrenda garbitzen du
                        $("#herria").append("<option value=''>Hautatu herria</option>"); // Lehenetsitako aukera berriro gehitzen du
                        for (var i = 0; i < info.kopurua; i++) { 
                            $("#herria").append("<option value='" + info[i].herria + "'>" + info[i].herria + "</option>"); // Herri bakoitza zerrendan jartzen du
                        }
                    } else {
                        $("#herria").html(""); 
                        $("#herria").append("<option value=''>Hautatu herria</option>"); // Lehenetsitako aukera gehitzen du
                    }
                })
                .fail(function () { // AJAX eskaera errorea ematen badu
                    alert("gaizki joan da"); // Errore mezua erakusten du
                });
        }
    </script>
</body>

</html>

