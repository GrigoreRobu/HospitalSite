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
                <form action="login.php" method="POST">
                    <input type="text" name="nume" placeholder="Nume" required>
                    <input type="password" name="parola" placeholder="Parola" required>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
        <div id="inregPopup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closeInregPopup()">&times;</span>
                <h2>Creare Cont</h2>
                <form action="php/creareCont.php" method="POST">
                    <input type="text" pattern="^[A-Za-z0-9]{8,}$" title="Minim 8 caractere" placeholder="Nume"
                        required>
                    <input type="password"
                        pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^@$!%*?&])[A-Za-z\d^@$!%*?&]{8,}$"
                        title="Minim 8 caractere, 1 nr, 1 litera mare, 1 litera mica, 1 caracter special "
                        placeholder="Parola" required>
                    <input type="submit" value="Creaza">
                </form>
            </div>
        </div>
    </header>
</body>
<script src="index.js"></script>
</html>