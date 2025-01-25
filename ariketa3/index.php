<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Herria Aukeratu</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
</head>

<body>
    <?php
    require_once("db.php");
    $conn = konexioaSortu();
    ?>

    <form action="" method="GET">
        <label for="eskualdea">Eskualdea</label>
        <select name="eskualdea" id="eskualdea" onchange="herriakEguneratu()">
            <option value="">Hautatu eskualdea</option>
            <?php
            $kontsulta = "SELECT DISTINCT eskualdea FROM eskualdeak";
            $result = $conn->query($kontsulta);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row["eskualdea"]) . '">' . htmlspecialchars($row["eskualdea"]) . '</option>';
                }
            }
            ?>
        </select>

        <label for="herria">Herria</label>
        <select name="herria" id="herria">
            <option value="">Hautatu herria</option>
        </select>
    </form>

    <script>
        function herriakEguneratu() {
            const eskualdea = $("#eskualdea").val();
            $.ajax({
                url: "lortuHerriak.php",
                method: "GET",
                data: { akzioa: "lortuHerriak", eskualdea: eskualdea }
            })
                .done(function (bueltanDatorrenInformazioa) {
                    var info = JSON.parse(bueltanDatorrenInformazioa);
                    if (info.kopurua > 0) {
                        $("#herria").html("");
                        $("#herria").append("<option value=''>Hautatu herria</option>");
                        for (var i = 0; i < info.kopurua; i++) {
                            $("#herria").append("<option value='" + info[i].herria + "'>" + info[i].herria + "</option>");
                        }
                    } else {
                        $("#herria").html("");
                        $("#herria").append("<option value=''>Hautatu herria</option>");
                    }
                })
                .fail(function () {
                    alert("gaizki joan da");
                });
        }
    </script>
</body>

</html>
