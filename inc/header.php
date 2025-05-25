<?php
require_once "connect.php";
session_start();
$login_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $userRegex = '/^[A-Za-z0-9]{1,}$/';
        $passRegex = '/^[A-Za-z\d@$!%*?&]{1,}$/';

        if (preg_match($userRegex, $username) && preg_match($passRegex, $password)) {
            $username_escaped = mysqli_real_escape_string($id_conexiune, $username);
            $sql = "SELECT password FROM admin WHERE username='$username_escaped'";
            $result = mysqli_query($id_conexiune, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $hash = $row['password'];

                if (md5($password) === $hash) {
                    $_SESSION['logat'] = true;
                    $_SESSION['user'] = $username;
                    header("Location: private/admin.php");
                    exit();
                }
            }
        }
    }

    $login_error = "Nume sau parola incorecta";
}
?>
<header>
    <nav>
        <a href="index.php" class="button-a">Home</a>
        <a href="servicii.php" class="button-a">Servicii</a>
        <a href="medici.php" class="button-a">Medici</a>
        <a href="contact.php" class="button-a">Contact</a>
        <a onClick="openLogPopup()" class="button-a">Login</a>
    </nav>
    <div id="loginPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeLogPopup()">&times;</span>
            <h2>Login</h2>
            <?php
            if (!empty($login_error)) {
                echo '<div class="error-message">' . htmlspecialchars($login_error) . '</div>';
            }
            ?>
            <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input id="username" name="username" type="text" placeholder="Nume"
                    value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                <input id="password" name="password" type="password" placeholder="Parola" required>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
    <?php if (!empty($login_error)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                openLogPopup();
            });
        </script>
    <?php endif; ?>
</header>