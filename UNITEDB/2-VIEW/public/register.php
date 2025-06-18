<link rel="stylesheet" href="style.css">
<?php
session_start();

require_once __DIR__ . '/../../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../../3-CONTROLER/UsersManager.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager = new UserManager($pdo);

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        if ($manager->getByUsername($username)) {
            $message = "Nom d'utilisateur déjà pris.";
        } else {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT)); // hash du mot de passe
            $manager->add($user);
            $message = "Inscription réussie. Connectez-vous !";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!-- Formulaire d'inscription -->
<a href="index.php"><button>Page Principale</button></a>
<button id="theme-toggle">Changer de mode</button>
<h2>Inscription</h2>
<form action="register.php" method="POST">
    <input type="text" name="username" placeholder="Nom d'utilisateur"><br>
    <input type="password" name="password" placeholder="Mot de passe"><br>
    <button type="submit">S'inscrire</button>
</form>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>
<a href="login.php">Déjà inscrit ? Se connecter</a>
<script src="script.js"></script>
