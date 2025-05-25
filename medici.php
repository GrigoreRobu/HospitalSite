<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" href="img/favico.png">
    <title>Medici - Clinica MedGrig</title>
</head>

<body>
    <div id="logo">
        <h1>Clinica MedGrig - Medici</h1>
    </div>
    <div id="container">
        <?php include 'inc/header.php'; ?>
        <div id="content">
            <?php
            require_once "inc/connect.php";
            $medici_sql = sprintf("SELECT m.*, s.nume as specializare 
                           FROM medici m 
                           LEFT JOIN specializari s ON m.id_specializari = s.id_specializari
                           ORDER BY s.nume, m.nume");

            $medici_result = mysqli_query($id_conexiune, $medici_sql) or
                die("Eroare: " . mysqli_error($id_conexiune));

            if ($medici_result && mysqli_num_rows($medici_result) > 0) {
                $current_specializare = '';
                while ($medic = mysqli_fetch_assoc($medici_result)) {
                    if ($current_specializare != $medic['specializare']) {
                        if ($current_specializare != '')
                            echo "</table></div>";
                        $current_specializare = $medic['specializare'];
                        echo "<div class='specializare'>";
                        echo "<h2>" . htmlspecialchars($medic['specializare']) . "</h2>";
                        echo "<table class='medici-table'>
                              <tr>
                                <th class='table-nume'>Nume</th>
                                <th>Descriere</th>
                              </tr>";
                    }
                    echo "<tr class='medic-row'>";
                    echo "<td class='medic-name'>" . htmlspecialchars($medic['nume']) . " " .
                        htmlspecialchars($medic['prenume']) . "</td>";
                    echo "<td class='medic-desc'>" . nl2br(htmlspecialchars($medic['descriere'])) . "</td>";
                    echo "</tr>";
                }
                echo "</table></div>";
            } else {
                echo "<p>Nu există medici disponibili.</p>";
            }
            ?>
        </div>
        <?php include 'inc/footer.php'; ?>
    </div>
    <script src="index.js"></script>
</body>

</html>