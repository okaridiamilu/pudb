<?php
session_start();
require_once __DIR__ . '/../../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../../1-MODEL/Team.php';
require_once __DIR__ . '/../../1-MODEL/Pokemon.php';
require_once __DIR__ . '/../../3-CONTROLER/TeamManager.php';
require_once __DIR__ . '/../../3-CONTROLER/PokemonManager.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user'])) {
    header('Location: ../public/login.php');
    exit;
}

$teamManager = new TeamManager($pdo);
$pokemonManager = new PokemonManager($pdo);

$teamId = $_GET['id'] ?? null;
$team = $teamId ? $teamManager->getById((int)$teamId) : null;

if (!$team || $team->getUserId() !== $_SESSION['user']['id']) {
    echo "Équipe introuvable ou non autorisée.";
    exit;
}

$message = '';
$selectedIds = $team->getPokemonIds();
$allPokemons = $pokemonManager->getAll();

// --- Traitement du POST (modification de l'équipe)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['team_name'] ?? '';
    $ids = $_POST['pokemon_ids'] ?? [];

    if (count($ids) !== 5) {
        $message = "Vous devez sélectionner exactement 5 Pokémon.";
    } else {
        $team->setName($name);
        $team->setPokemon1Id($ids[0]);
        $team->setPokemon2Id($ids[1]);
        $team->setPokemon3Id($ids[2]);
        $team->setPokemon4Id($ids[3]);
        $team->setPokemon5Id($ids[4]);

        if ($teamManager->update($team)) {
            header("Location: teamPage.php");
            exit;
        } else {
            $message = "Erreur lors de la mise à jour.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Modifier équipe</title>
  <link rel="stylesheet" href="css_team.css" />
</head>
<body>
  <header>
    <a href="teamPage.php"><button>← Retour</button></a>
    <h1>Modifier l'équipe : <?= htmlspecialchars($team->getName()) ?></h1>
  </header>

  <?php if ($message): ?>
    <p style="color:red"><?= htmlspecialchars($message) ?></p>
  <?php endif; ?>

  <form method="POST">
    <label for="team_name">Nom de l'équipe :</label>
    <input type="text" name="team_name" value="<?= htmlspecialchars($team->getName()) ?>" required><br><br>

    <p>Sélectionnez 5 Pokémon :</p>
    <div id="collected">
      <?php foreach ($allPokemons as $poke): ?>
        <div class="post-container">
          <img src="<?= $poke->getImage() ?>" alt="<?= $poke->getName() ?>">
          <div class="text-container">
            <h2><?= htmlspecialchars($poke->getName()) ?></h2>
            <h3><?= htmlspecialchars($poke->getRole()) ?></h3>
            <p><?= htmlspecialchars($poke->getStyle()) ?> <br> <?= htmlspecialchars($poke->getDmgType()) ?></p>

            <label>
              <input type="checkbox" name="pokemon_ids[]" value="<?= $poke->getId() ?>"
                <?= in_array($poke->getId(), $selectedIds) ? 'checked' : '' ?>
              >
              Ajouter
            </label>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <br>
    <button type="submit">Mettre à jour l'équipe</button>
  </form>
  <script src="script.js"></script>
</body>
</html>
