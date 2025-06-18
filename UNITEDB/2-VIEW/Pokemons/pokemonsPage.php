<?php
require_once __DIR__ . '/../../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../../3-CONTROLER/PokemonManager.php';

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
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header>
    <a href="../public/index.php"><button>Page Principale</button></a>
    <h1>Pokémon Unite DB</h1>
    <button id="theme-toggle">Changer de mode</button>

    <form method="GET" id="filters">
      <select name="style">
        <option value="">Tous styles</option>
        <option <?= $filters['style'] === 'Novice' ? 'selected' : '' ?>>Novice</option>
        <option value<?= $filters['style'] === 'Intermediate' ? 'selected' : '' ?>>Intermédiaire</option>
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

  <main>
    <div id="collected">
      <?php foreach ($pokemons as $p): ?>
        <div class="post-container">
    <img src="<?= htmlspecialchars($p->getImage()) ?>" alt="<?= htmlspecialchars($p->getName()) ?>" />
    <div class="text-container">
      <h2><?= htmlspecialchars($p->getName()) ?></h2>
      <h3><?= htmlspecialchars($p->getRole()) ?></h3>
      <p><?= htmlspecialchars($p->getStyle()) ?> • <?= htmlspecialchars($p->getDmgType()) ?></p>

      <?php
        // Liste des champs booléens
        $boolFields = [
          'healAllies' => 'Soigne les alliés',
          'croudControle' => 'Contrôle de foule',
          'selfShield' => 'Bouclier personnel',
          'allieShield' => 'Bouclier allié',
          'buff' => 'Buff',
          'debuff' => 'Debuff',
          'hpScale' => 'Scale sur PV',
          'execute' => 'Exécution'
        ];

        // Collecter les capacités actives
        $activeCaps = [];
        foreach ($boolFields as $field => $label) {
          // Appeler le getter dynamique, exemple : getHealAllies()
          $getter = 'get' . ucfirst($field);
          if (method_exists($p, $getter) && $p->$getter()) {
            $activeCaps[] = $label;
          }
        }
      ?>

      <?php if (count($activeCaps) > 0): ?>
        <ul class="pokemon-capacities">
          <?php foreach ($activeCaps as $cap): ?>
            <li><?= htmlspecialchars($cap) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
      <?php endforeach; ?>
    </div>
    <script src="script.js"></script>
  </main>
</body>
</html>
