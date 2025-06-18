<?php
session_start();
require_once __DIR__ . '/../../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../../1-MODEL/Team.php';
require_once __DIR__ . '/../../1-MODEL/Pokemon.php';
require_once __DIR__ . '/../../3-CONTROLER/TeamManager.php';
require_once __DIR__ . '/../../3-CONTROLER/PokemonManager.php';

if (!isset($_SESSION['user'])) {
    header('Location: /../../UNITEDB/2-VIEW/public/login.php');
    exit;
}

$teamManager = new TeamManager($pdo);
$pokemonManager = new PokemonManager($pdo);

// Traitement du formulaire
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pokemon_ids'])) {
    $team = new Team();
    $team->setUserId($_SESSION['user']['id']);
    $team->setName($_POST['team_name'] ?? null);
    $pokemonIds = $_POST['pokemon_ids'];
    $team->setPokemon1Id($pokemonIds[0] ?? null);
    $team->setPokemon2Id($pokemonIds[1] ?? null);
    $team->setPokemon3Id($pokemonIds[2] ?? null);
    $team->setPokemon4Id($pokemonIds[3] ?? null);
    $team->setPokemon5Id($pokemonIds[4] ?? null);

    if ($teamManager->add($team)) {
        $message = "Équipe enregistrée !";
    } else {
        $message = "Erreur lors de l'enregistrement.";
    }
}

if (isset($_GET['delete'])) {
    $teamManager->delete((int) $_GET['delete'], $_SESSION['user']['id']);
    header("Location: teamPage.php");
    exit;
}

$teams = $teamManager->getAllByUser($_SESSION['user']['id']);
$pokemons = $pokemonManager->getAll();

$manager = new PokemonManager($pdo);
$filters = [
    'style' => $_GET['style'] ?? '',
    'dmgType' => $_GET['dmgType'] ?? '',
    'role' => $_GET['role'] ?? '',
    'search' => $_GET['search'] ?? '',
    'sort' => $_GET['sort'] ?? 'asc'
];

$boolFields = [
    'healAllies', 'croudControle', 'selfShield', 'allieShield',
    'buff', 'debuff', 'hpScale', 'execute'
];
foreach ($boolFields as $field) {
    $filters[$field] = isset($_GET[$field]);
}

$pokemons = $manager->getFiltered($filters);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>PUDB Pokémons</title>
  <link rel="stylesheet" href="css_team.css" />
</head>
<body>
  <header>
    <a href="../public/index.php"><button>Page Principale</button></a>
    <h1>Pokémon Unite DB</h1>
    <button id="theme-toggle">Changer de mode</button>
<!-- outil de trie -->

    <form method="GET" id="filters">
      <select name="style">
        <option value="">Tous styles</option>
        <option <?= $filters['style'] === 'Novice' ? 'selected' : '' ?>>Novice</option>
        <option <?= $filters['style'] === 'Intermediate' ? 'selected' : '' ?>>Intermédiaire</option>
        <option <?= $filters['style'] === 'Expert' ? 'selected' : '' ?>>Expert</option>
      </select>

      <select name="dmgType">
        <option value="">Tous types</option>
        <option <?= $filters['dmgType'] === 'Melee' ? 'selected' : '' ?>>Melee</option>
        <option <?= $filters['dmgType'] === 'Ranged' ? 'selected' : '' ?>>Ranged</option>
      </select>

      <select name="role">
        <option value="">Tous rôles</option>
        <option <?= $filters['role'] === 'All-Rounder' ? 'selected' : '' ?>>All-Rounder</option>
        <option <?= $filters['role'] === 'Attacker' ? 'selected' : '' ?>>Attacker</option>
        <option <?= $filters['role'] === 'Speedster' ? 'selected' : '' ?>>Speedster</option>
        <option <?= $filters['role'] === 'Defender' ? 'selected' : '' ?>>Defender</option>
        <option <?= $filters['role'] === 'Supporter' ? 'selected' : '' ?>>Supporter</option>
        <option <?= $filters['role'] === 'Tank' ? 'selected' : '' ?>>Tank</option>
      </select>

      <input type="text" name="search" placeholder="Rechercher un nom" value="<?= htmlspecialchars($filters['search']) ?>" />

      <fieldset class="filter-bools">
        <legend>Capacités</legend>
        <?php foreach ($boolFields as $key): ?>
          <label>
            <input type="checkbox" name="<?= $key ?>" <?= $filters[$key] ? 'checked' : '' ?> />
            <?= $key ?>
          </label>
        <?php endforeach; ?>
      </fieldset>

      <button type="submit">Filtrer</button>
      <button type="submit" name="sort" value="<?= $filters['sort'] === 'asc' ? 'desc' : 'asc' ?>">
        Trier <?= $filters['sort'] === 'asc' ? 'Z → A' : 'A → Z' ?>
      </button>
    </form>
  </header>

<body>
    <header><h1>Team Maker – Pokémon Unite</h1></header>
<!-- creation des div pokemons -->

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
    <div id="collected">
        <label for="team_name">Nom de l'équipe :</label>
        <input type="text" name="team_name" id="team_name" required>

            <legend>Sélectionnez 5 Pokémon :</legend>
    <div id="collected">  
      <?php foreach ($pokemons as $p): ?>
        <div class="post-container">
          <img src="<?= htmlspecialchars($p->getImage()) ?>" alt="<?= htmlspecialchars($p->getName()) ?>" />
          <div class="text-container">
            <h2><?= htmlspecialchars($p->getName()) ?></h2>
            <h3><?= htmlspecialchars($p->getRole()) ?></h3>
            <p><?= htmlspecialchars($p->getStyle()) ?> <br> <?= htmlspecialchars($p->getDmgType()) ?></p>
            <label>
                    <input type="checkbox" name="pokemon_ids[]" value="<?= $p->getId() ?>" 
                        <?= isset($_POST['pokemon_ids']) && in_array($p->getId(), $_POST['pokemon_ids']) ? 'checked' : '' ?>
                        <?= count($_POST['pokemon_ids'] ?? []) >= 5 ? 'disabled' : '' ?>
                    >
                    <?= htmlspecialchars("ajouter a l'équipe") ?>
                </label><br>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
        <button type="submit">Enregistrer l'équipe</button>
      </div>
    </form>
    <hr>

    <h2>Mes équipes</h2>
    <!-- affichage des equipes -->

    <ul>
        <?php foreach ($teams as $team): ?>
            <li>
                <strong><?= htmlspecialchars($team->getName()) ?></strong> :
                <?= implode(', ', array_map(function ($id) use ($pokemons) {
                  foreach ($pokemons as $p) {
                      if ($p->getId() == $id) return $p->getName();
                  }
                  return "Inconnu";
              }, $team->getPokemonIds())) ?>
                <a href="?delete=<?= $team->getId() ?>" onclick="return confirm('Supprimer cette équipe ?')">🗑️</a>
                <a href="edit_team.php?id=<?= $team->getId() ?>"><button>Modifier</button></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <script src="script.js"></script>
</body>
</html>
