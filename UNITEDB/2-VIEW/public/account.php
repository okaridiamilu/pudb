<?php
session_start();
require_once __DIR__ . '/../../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../../3-CONTROLER/UsersManager.php';
require_once __DIR__ . '/../../1-MODEL/Users.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$manager = new UserManager($pdo);
$currentUser = $manager->getById($_SESSION['user']['id']);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = $_POST['username'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    if (!empty($newUsername) && !empty($newPassword)) {
        $currentUser->setUsername($newUsername);
        $currentUser->setPassword($newPassword);  // Hashé dans le manager
        if ($manager->update($currentUser)) {
            $_SESSION['user']['username'] = $newUsername;
            $message = "Modifications enregistrées.";
        } else {
            $message = "Erreur lors de la mise à jour.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<h2>Modifier mon compte</h2>
<?php if ($message): ?><p><?= htmlspecialchars($message) ?></p><?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Nouveau nom" value="<?= htmlspecialchars($currentUser->getUsername()) ?>"><br>
    <input type="password" name="password" placeholder="Nouveau mot de passe"><br>
    <button type="submit">Mettre à jour</button>
</form>
<a href="index.php">← Retour</a>
