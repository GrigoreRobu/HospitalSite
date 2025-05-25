<?php
session_start();
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
require_once "../inc/config.php";
require_once "../inc/connect.php";

if (!isset($_SESSION['logat']) || $_SESSION['logat'] !== true) {
    die("Acces interzis. Trebuie sa fii autentificat ca admin.");
}

function validSpecializare($nume)
{
    $error = [];
    $nume = trim($nume);

    if (empty($nume)) {
        $error[] = "Numele specializarii este obligatoriu.";
    } elseif (strlen($nume) < 2 || strlen($nume) > 50) {
        $error[] = "Numele specializarii trebuie sa aiba intre 2 si 50 de caractere.";
    }

    return $error;
}

function validServiciu($nume, $descriere, $pret, $id_specializari)
{
    $error = [];
    $nume = trim($nume);
    $descriere = trim($descriere);
    $pret = floatval($pret);
    $id_specializari = intval($id_specializari);

    if (empty($nume)) {
        $error[] = "Numele serviciului este obligatoriu.";
    } elseif (strlen($nume) < 2 || strlen($nume) > 50) {
        $error[] = "Numele serviciului trebuie sa aiba intre 2 si 50 de caractere.";
    }

    if (empty($descriere)) {
        $error[] = "Descrierea serviciului este obligatorie.";
    } elseif (strlen($descriere) < 5 || strlen($descriere) > 255) {
        $error[] = "Descrierea trebuie sa aiba intre 5 si 255 de caractere.";
    }

    if ($pret <= 0 || $pret > 10000) {
        $error[] = "Pretul trebuie sa fie un numar pozitiv si mai mic de 10000.";
    }

    if ($id_specializari <= 0) {
        $error[] = "Selectati o specializare valida.";
    }

    return $error;
}

function validMedic($nume, $prenume, $descriere, $id_specializari)
{
    $error = [];
    $nume = trim($nume);
    $prenume = trim($prenume);
    $descriere = trim($descriere);
    $id_specializari = intval($id_specializari);

    if (empty($nume)) {
        $error[] = "Numele medicului este obligatoriu.";
    } elseif (strlen($nume) < 2 || strlen($nume) > 50) {
        $error[] = "Numele medicului trebuie sa aiba intre 2 si 50 de caractere.";
    }

    if (empty($prenume)) {
        $error[] = "Prenumele medicului este obligatoriu.";
    } elseif (strlen($prenume) < 2 || strlen($prenume) > 50) {
        $error[] = "Prenumele medicului trebuie sa aiba intre 2 si 50 de caractere.";
    }

    if (empty($descriere)) {
        $error[] = "Descrierea medicului este obligatorie.";
    } elseif (strlen($descriere) < 5 || strlen($descriere) > 1000) {
        $error[] = "Descrierea trebuie sa aiba intre 5 si 1000 de caractere.";
    }

    if ($id_specializari <= 0) {
        $error[] = "Selectati o specializare valida.";
    }

    return $error;
}

$error = [];
$formSubmitted = false;

function errorMessage($error)
{
    if (!empty($error)) {
        echo '<div class="error-message">';
        foreach ($error as $err) {
            echo '<p>' . htmlspecialchars($err) . '</p>';
        }
        echo '</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_specializare'])) {
        $nume = trim($_POST['nume_specializare']);
        $error = validSpecializare($nume);
        $formSubmitted = 'specializare';

        if (empty($error)) {
            $nume_escaped = mysqli_real_escape_string($id_conexiune, $nume);
            $sql = sprintf("INSERT INTO specializari (nume) VALUES ('%s')", $nume_escaped);
            mysqli_query($id_conexiune, $sql) or die("Eroare: " . mysqli_error($id_conexiune));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_POST['add_serviciu'])) {
        $nume = trim($_POST['nume']);
        $descriere = trim($_POST['descriere']);
        $pret = floatval($_POST['pret']);
        $id_specializari = intval($_POST['id_specializari']);

        $error = validServiciu($nume, $descriere, $pret, $id_specializari);
        $formSubmitted = 'serviciu';

        if (empty($error)) {
            $nume_escaped = mysqli_real_escape_string($id_conexiune, $nume);
            $descriere_escaped = mysqli_real_escape_string($id_conexiune, $descriere);
            $sql = sprintf(
                "INSERT INTO servicii (nume, descriere, pret, id_specializari) VALUES ('%s', '%s', %f, %d)",
                $nume_escaped,
                $descriere_escaped,
                $pret,
                $id_specializari
            );
            mysqli_query($id_conexiune, $sql) or die("Eroare: " . mysqli_error($id_conexiune));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_POST['edit_serviciu'])) {
        $id_servicii = intval($_POST['id_servicii']);
        $nume = trim($_POST['nume']);
        $descriere = trim($_POST['descriere']);
        $pret = floatval($_POST['pret']);

        $getSpecSql = sprintf("SELECT id_specializari FROM servicii WHERE id_servicii=%d", $id_servicii);
        $specResult = mysqli_query($id_conexiune, $getSpecSql);
        $id_specializari = 0;
        if ($row = mysqli_fetch_assoc($specResult)) {
            $id_specializari = $row['id_specializari'];
        }

        $error = validServiciu($nume, $descriere, $pret, $id_specializari);
        $formSubmitted = 'edit_serviciu_' . $id_servicii;

        if (empty($error) && $id_servicii) {
            $nume_escaped = mysqli_real_escape_string($id_conexiune, $nume);
            $descriere_escaped = mysqli_real_escape_string($id_conexiune, $descriere);
            $sql = sprintf(
                "UPDATE servicii SET nume='%s', descriere='%s', pret=%f WHERE id_servicii=%d",
                $nume_escaped,
                $descriere_escaped,
                $pret,
                $id_servicii
            );
            mysqli_query($id_conexiune, $sql) or die("Eroare: " . mysqli_error($id_conexiune));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_POST['delete_serviciu'])) {
        $id_servicii = intval($_POST['id_servicii']);
        if ($id_servicii) {
            $sql = sprintf("DELETE FROM servicii WHERE id_servicii=%d", $id_servicii);
            mysqli_query($id_conexiune, $sql) or die("Eroare: " . mysqli_error($id_conexiune));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_POST['delete_specializare'])) {
        $id_specializari = intval($_POST['id_specializari']);
        if ($id_specializari) {
            $sql = sprintf("DELETE FROM servicii WHERE id_specializari=%d", $id_specializari);
            mysqli_query($id_conexiune, $sql) or die("Eroare: " . mysqli_error($id_conexiune));

            $sql2 = sprintf("DELETE FROM specializari WHERE id_specializari=%d", $id_specializari);
            mysqli_query($id_conexiune, $sql2) or die("Eroare: " . mysqli_error($id_conexiune));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_POST['add_medic'])) {
        $nume = trim($_POST['nume_medic']);
        $prenume = trim($_POST['prenume_medic']);
        $id_specializari = intval($_POST['id_specializari']);
        $descriere = trim($_POST['descriere_medic']);

        $error = validMedic($nume, $prenume, $descriere, $id_specializari);
        $formSubmitted = 'medic';

        if (empty($error)) {
            $nume_escaped = mysqli_real_escape_string($id_conexiune, $nume);
            $prenume_escaped = mysqli_real_escape_string($id_conexiune, $prenume);
            $descriere_escaped = mysqli_real_escape_string($id_conexiune, $descriere);
            $sql = sprintf(
                "INSERT INTO medici (nume, prenume, id_specializari, descriere) VALUES ('%s', '%s', %d, '%s')",
                $nume_escaped,
                $prenume_escaped,
                $id_specializari,
                $descriere_escaped
            );
            mysqli_query($id_conexiune, $sql) or die("Eroare: " . mysqli_error($id_conexiune));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_POST['edit_medic'])) {
        $id_medic = intval($_POST['id_medic']);
        $nume = trim($_POST['nume']);
        $prenume = trim($_POST['prenume']);
        $id_specializari = intval($_POST['id_specializari']);
        $descriere = trim($_POST['descriere']);

        $error = validMedic($nume, $prenume, $descriere, $id_specializari);
        $formSubmitted = 'edit_medic_' . $id_medic;

        if (empty($error) && $id_medic) {
            $nume_escaped = mysqli_real_escape_string($id_conexiune, $nume);
            $prenume_escaped = mysqli_real_escape_string($id_conexiune, $prenume);
            $descriere_escaped = mysqli_real_escape_string($id_conexiune, $descriere);
            $sql = sprintf(
                "UPDATE medici SET nume='%s', prenume='%s', id_specializari=%d, descriere='%s' WHERE id_medic=%d",
                $nume_escaped,
                $prenume_escaped,
                $id_specializari,
                $descriere_escaped,
                $id_medic
            );
            mysqli_query($id_conexiune, $sql) or die("Eroare: " . mysqli_error($id_conexiune));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    if (isset($_POST['delete_medic'])) {
        $id_medic = intval($_POST['id_medic']);
        if ($id_medic) {
            $sql = sprintf("DELETE FROM medici WHERE id_medic=%d", $id_medic);
            mysqli_query($id_conexiune, $sql) or die("Eroare: " . mysqli_error($id_conexiune));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
}

$specializari = [];
$res = mysqli_query($id_conexiune, "SELECT * FROM specializari");
while ($row = mysqli_fetch_assoc($res)) {
    $specializari[$row['id_specializari']] = $row['nume'];
}
?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Administrare - Clinica MedGrig</title>
</head>

<body>

    <h1>Administrare</h1>
    <a href="?logout=1" id="log-out" class="add-form" style="float:right; ">Logout</a>

    <h3>Adauga specializare noua</h3>
    <?php if ($formSubmitted === 'specializare') {
        errorMessage($error);
    } ?>
    <form method="post" class="add-form" name="add_specializare_form">
        <label>Nume specializare: <input type="text" name="nume_specializare" required></label>
        <button type="submit" name="add_specializare">Adauga specializare</button>
    </form>

    <hr>

    <h3>Adauga serviciu nou</h3>
    <?php if ($formSubmitted === 'serviciu') {
        errorMessage($error);
    } ?>
    <form method="post" class="add-form" name="add_serviciu_form">
        <label>Specializare:
            <select name="id_specializari" required>
                <option value="">Alege specializarea</option>
                <?php foreach ($specializari as $id_specializari => $nume): ?>
                    <option value="<?= $id_specializari ?>"><?= htmlspecialchars($nume) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Nume serviciu: <input type="text" name="nume" required></label>
        <label>Descriere: <input type="text" name="descriere" required></label>
        <label>Pret: <input type="number" name="pret" step="0.01" required></label>
        <button type="submit" name="add_serviciu">Adauga</button>
    </form>

    <h2>Lista serviciilor</h2>
    <?php
    foreach ($specializari as $spec_id => $spec_nume) {
        echo "<h3>" . htmlspecialchars($spec_nume) . "</h3>";
        echo "<form method='post'>
            <input type='hidden' name='id_specializari' value='" . intval($spec_id) . "'> 
            <button type='submit' name='delete_specializare' onclick=\"return confirm('Esti sigur? Vei sterge specializarea si toate serviciile asociate.')\">Sterge specializarea</button>
          </form>";
        $servicii = mysqli_query($id_conexiune, "SELECT * FROM servicii WHERE id_specializari=" . intval($spec_id));
        if (mysqli_num_rows($servicii) > 0) {
            echo "<table class='admin-table'>";
            echo "<tr><th>Nume</th><th>Descriere</th><th>Pret</th><th>Actiuni</th></tr>";
            while ($serv = mysqli_fetch_assoc($servicii)) {
                $serv_id = intval($serv['id_servicii']);

                if ($formSubmitted === 'edit_serviciu_' . $serv_id) {
                    echo "<tr><td colspan='4'>";
                    errorMessage($error);
                    echo "</td></tr>";
                }

                echo "<tr>
                <form method='post' class='edit-serviciu-form'>
                    <td><input type='text' name='nume' value='" . htmlspecialchars($serv['nume']) . "' required></td>
                    <td><input type='text' name='descriere' value='" . htmlspecialchars($serv['descriere']) . "' required></td>
                    <td><input type='number' name='pret' value='" . htmlspecialchars($serv['pret']) . "' step='0.01' required></td>
                    <td>
                        <input type='hidden' name='id_servicii' value='" . $serv_id . "'>
                        <button type='submit' name='edit_serviciu'>Salveaza</button>
                        <button type='submit' name='delete_serviciu' onclick=\"return confirm('Esti sigur ca vrei sa-l stergi?')\">Sterge</button>
                    </td>
                </form>
            </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Niciun serviciu pentru aceasta specializare.</p>";
        }
    }
    ?>

    <h2>Adauga medic nou</h2>
    <?php if ($formSubmitted === 'medic') {
        errorMessage($error);
    } ?>
    <form method="post" class="add-form" name="add_medic_form">
        <label>Nume: <input type="text" name="nume_medic" required></label>
        <label>Prenume: <input type="text" name="prenume_medic" required></label>
        <label>Specializare:
            <select name="id_specializari" required>
                <option value="">Alege specializarea</option>
                <?php foreach ($specializari as $id_specializari => $nume): ?>
                    <option value="<?= $id_specializari ?>"><?= htmlspecialchars($nume) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Descriere: <input type="text" name="descriere_medic" required> </label>
        <button type="submit" name="add_medic">Adauga medic</button>
    </form>

    <h2>Lista medicilor</h2>
    <?php
    $medici = mysqli_query($id_conexiune, "SELECT m.*, s.nume as specializare 
                                      FROM medici m 
                                      LEFT JOIN specializari s ON m.id_specializari = s.id_specializari");
    if (mysqli_num_rows($medici) > 0) {
        echo "<table class='admin-table'>";
        echo "<tr><th>Nume</th><th>Prenume</th><th>Specializare</th><th>Descriere</th><th>Actiuni</th></tr>";
        while ($medic = mysqli_fetch_assoc($medici)) {
            $medic_id = intval($medic['id_medic']);

            if ($formSubmitted === 'edit_medic_' . $medic_id) {
                echo "<tr><td colspan='5'>";
                errorMessage($error);
                echo "</td></tr>";
            }

            echo "<tr>
            <form method='post' class='edit-medic-form'>
                <td><input type='text' name='nume' value='" . htmlspecialchars($medic['nume']) . "' required></td>
                <td><input type='text' name='prenume' value='" . htmlspecialchars($medic['prenume']) . "' required></td>
                <td><select name='id_specializari' required>";
            foreach ($specializari as $id_specializari => $nume) {
                echo "<option value='$id_specializari'" .
                    ($id_specializari == $medic['id_specializari'] ? " selected" : "") . ">" .
                    htmlspecialchars($nume) . "</option>";
            }
            echo "</select></td>
                <td><textarea name='descriere'>" . htmlspecialchars($medic['descriere']) . "</textarea></td>
                <td>
                    <input type='hidden' name='id_medic' value='" . $medic_id . "'>
                    <button type='submit' name='edit_medic'>Salveaza</button>
                    <button type='submit' name='delete_medic' onclick=\"return confirm('Sigur stergi acest medic?')\">Sterge</button>
                </td>
            </form>
        </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nu exista medici in baza de date.</p>";
    }
    ?>
</body>

</html>