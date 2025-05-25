<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" href="img/favico.png">
    <title>Servicii - Clinica MedGrig</title>
</head>

<body>
    <div id="logo">
        <h1>Clinica MedGrig - Servicii</h1>
    </div>
    <div id="container">
        <?php include 'inc/header.php'; ?>
        <div id="content">
            <?php
            require_once "inc/connect.php";

            $specializari_sql = sprintf("SELECT * FROM specializari ORDER BY nume");
            $specializari_result = mysqli_query($id_conexiune, $specializari_sql) or
                die("Eroare: " . mysqli_error($id_conexiune));

            if ($specializari_result && mysqli_num_rows($specializari_result) > 0) {
                while ($spec = mysqli_fetch_assoc($specializari_result)) {
                    echo "<div class='specializare'>";
                    echo "<h2>" . htmlspecialchars($spec['nume']) . "</h2>";

                    $servicii_sql = sprintf(
                        "SELECT * FROM servicii WHERE id_specializari = %d ORDER BY nume",
                        intval($spec['id_specializari'])
                    );
                    $servicii_result = mysqli_query($id_conexiune, $servicii_sql) or
                        die("Eroare: " . mysqli_error($id_conexiune));

                    if ($servicii_result && mysqli_num_rows($servicii_result) > 0) {
                        echo "<table class='servicii-table'>
                              <tr>
                                <th class='table-nume'>Serviciu</th>
                                <th>Descriere</th>
                                <th class='table-pret'>Pret</th>
                              </tr>";
                        while ($serv = mysqli_fetch_assoc($servicii_result)) {
                            echo "<tr class='serviciu-row'>";
                            echo "<td class='serviciu-name'><strong>" . htmlspecialchars($serv['nume']) . "</strong></td>";
                            echo "<td class='serviciu-desc'>" . htmlspecialchars($serv['descriere']) . "</td>";
                            echo "<td class='serviciu-pret'>" . htmlspecialchars($serv['pret']) . " lei</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Nici un serviciu medical pentru aceasta specializare.</p>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>Nu exista specializari disponibile.</p>";
            }
            ?>
        </div>
        <?php include 'inc/footer.php'; ?>
    </div>
    <script src="index.js"></script>
</body>

</html>