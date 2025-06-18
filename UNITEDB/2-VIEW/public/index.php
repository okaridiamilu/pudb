<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PUDB</title>
</head>
<body>
    <?php
    session_start();
    require_once __DIR__ . '/../../5-CONFIGURATOR/db.php';
    require_once __DIR__ . '/../../3-CONTROLER/UsersManager.php';
    require_once __DIR__ . '/../../3-CONTROLER/TeamManager.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    $userId = $_SESSION['user']['id'] ?? null;

    if ($userId) {
        $userManager = new UserManager($pdo);
        $teamManager = new TeamManager($pdo);

        $teamManager->deleteAllByUser($userId); // à ajouter dans TeamManager si pas encore fait
        $userManager->delete($userId);
        session_destroy();

        header("Location: index.php?deleted=1");
        exit;
    }
}

if (isset($_GET['deleted'])) {
    $message = "Votre compte a bien été supprimé.";
}
    ?>

    <header>
        <h1> Pokemon Unite Data Base</h1>
        <div class="user-session">
            <?php if (isset($_SESSION['user']['id'])): ?>
                <span>Bienvenue, <?= htmlspecialchars($_SESSION['user']['username']) ?> !</span>
                <a href="logout.php"><button>Déconnexion</button></a>
                <a href="account.php"><button>modifier</button></a>
                <form method="POST" onsubmit="return confirmDelete();" style="display:inline;">
                    <button type="submit" name="delete_account" style="background-color: red; color: white;">Supprimer mon compte</button>
                </form>
            </br></br></br></br></br></br>
                <button id="theme-toggle">Changer de mode</button>
            <?php else: ?>
                <a href="login.php"><button>Connexion</button></a>
                <a href="register.php"><button>Inscription</button></a>
            </br></br></br></br></br></br>
                <button id="theme-toggle">Changer de mode</button>
            <?php endif; ?>
        </div>
    </header>
    <?php if ($message): ?>
        <p style="color: green; font-weight: bold; text-align: center;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <div id="entrance">
        <img src="../../4-DATAS/IMAGES/pokemon-unite-darkrai-00.jpg" alt="MAP UNITE">
        <p id=initialTexte> Bienvenue sur PUDB Pokémon Unite Data Base ! </br></br>
Explorez une base de données complète et interactive dédiée à Pokémon Unite. Consultez les caractéristiques détaillées de chaque Pokémon jouable, filtrez-les selon leur rôle, style ou capacités, et créez vos propres compositions d'équipe grâce à notre outil de team maker. </br></br>
Le site met à votre disposition : Un Pokédex dynamique avec filtres avancés et recherche ainsi qu'un système d’inscription pour sauvegarder vos équipes préférées.</p>
    </div>
    <div id="core">
        <div class="UDBaccess">
            <img src="../../4-DATAS/IMAGES/pokemon-unite-dragon-carnival-header.jpg" alt="image de pokemons">
            <a href="../Pokemons/pokemonsPage.php"><button>Pokemons Data Base</button></a>
        </div>
        <div class="UDBaccess">
            <img src="../../4-DATAS/IMAGES/17216679430023.png" alt="image d'équipe">
            <a href="../game/teamPage.php"><button>Team comp maker</button></a>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>