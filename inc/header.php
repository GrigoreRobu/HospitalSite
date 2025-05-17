<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <?php include_once("inc/config.php"); ?>
        <?php include_once("inc/connect.php"); ?>
        <div id="logo">
            <h1>Clinica MedGrig pagina Home</h1>
        </div>
        <nav>
            <a href="index.php" class="button-a">Home</a>
            <a href="servicii.php" class="button-a">Servicii</a>
            <a href="galerie.php" class="button-a">Galerie</a>
            <a href="contact.php" class="button-a">Contact</a>
            <a onClick="openLogPopup()" class="button-a">Login</a>
            <a onClick="openInregPopup()" class="button-a">Inregistrare</a>
        </nav>
        <div id="loginPopup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closeLogPopup()">&times;</span>
                <h2>Login</h2>
                <form id="loginForm" method="POST">
                    <input id="loginUsername" name="username1" type="text" placeholder="Nume">
                    <input id="loginPass" name="password1" type="password" placeholder="Parola">
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
        <div id="inregPopup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closeInregPopup()">&times;</span>
                <h2>Creare Cont</h2>
                <form id="inregForm" action="creareCont.php" method="POST">
                    <input id="inregUsername" name="username2" type="text" placeholder="Nume" required>
                    <input id="inregPass" name="password2" type="password" placeholder="Parola" required>
                    <input type="submit" value="Creaza">
                </form>
            </div>
        </div>
    </header>
</body>

</html>