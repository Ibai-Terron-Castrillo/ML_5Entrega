<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Podioa</title>
    <style>
        #tabla {
            border: 2px solid black;
            width: 250px;
        }

        .border {
            border: 2px solid black;
            text-align: center;
        }

        button {
            margin: 10px;
        }

        .green {
            background-color: green;
        }

        .red {
            background-color: red;
        }
    </style>
</head>

<body>
    <?php
    require_once("db.php");
    $conn = konexioaSortu();
    ?>

    <form action="podioaEguneratu.php" method="GET" id="updateForm">
        <label for="dortsala">Dortsala</label>
        <input type="number" name="dortsala" id="dortsala" required>
        <label for="postua">Postu berria</label>
        <input type="number" name="postua" id="postua" required>
        <input type="submit" id="eguneratu" value="Postua Eguneratu">
    </form>
    <button class="taulaReload">Taula birkargatu</button>
    <br>

    <div id="taulaContainer">
        <?php
        $kontsulta = "SELECT * FROM podioa ORDER BY Postua";
        $result = $conn->query($kontsulta);

        if ($result->num_rows > 0) {
            echo "<table class='zerrenda' id='tabla'>";
            echo "<tr>";
            echo "<th class='border'>Postua</th>";
            echo "<th class='border'>Dortsala</th>";
            echo "<th class='border'>Izena</th>";
            echo "</tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='border'>" . $row["Postua"] . "</td>";
                echo "<td class='border'>" . $row["Dortsala"] . "</td>";
                echo "<td class='border'>" . $row["Izena"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Ez dago informaziorik.";
        }
        $conn->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script>
        $(document).ready(function () {
            $(".taulaReload").on("click", function () {
                taulaBirkargatu();
            });

            $("#updateForm").on("submit", function (e) {
                e.preventDefault();
                taulaEguneratu();
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

        function taulaEguneratu() {
            const dortsala = $("#dortsala").val();
            const postua = $("#postua").val();

            $.ajax({
                url: "podioaEguneratu.php",
                type: "GET",
                data: { dortsala: dortsala, postua: postua }
            })
                .done(function (data) {
                    $("#taulaContainer").html(data);
                })
                .fail(function () {
                    alert("Errorea postua eguneratzean.");
                });
        }
    </script>
</body>

</html>