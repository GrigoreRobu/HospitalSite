<?php
require_once "config.php";
$id_conexiune = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if (!$id_conexiune) {
    die('Eroare conectare la MySQL: ' . mysqli_connect_error());
}
mysqli_select_db($id_conexiune, DB_NAME) or die("Eroare la selectarea bazei de date " .
    mysqli_error($id_conexiune));
?>