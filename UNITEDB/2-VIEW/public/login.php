<link rel="stylesheet" href="style.css">
<?php
require_once __DIR__ . '/../../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../../3-CONTROLER/UsersManager.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager = new UserManager($pdo);

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = $manager->getByUsername($username);

    if ($user && password_verify($password, $user->getPassword())) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername()
            ];

        header('Location: index.php');
        exit;
    } else {
        $message = "Identifiants incorrects.";
    }
}
?>

<!-- Formulaire de connexion -->
<a href="index.php"><button>Page Principale</button></a>
<button id="theme-toggle">Changer de mode</button>
<h2>Connexion</h2>
<form action="login.php" method="POST">
    <input type="text" name="username" placeholder="Nom d'utilisateur"><br>
    <input type="password" name="password" placeholder="Mot de passe"><br>
    <button type="submit">Se connecter</button>
</form>
<?php if (!empty($message)) echo "<p>$message</p>"; ?>
<a href="register.php">Pas encore inscrit ?</a>
<script src="script.js"></script>