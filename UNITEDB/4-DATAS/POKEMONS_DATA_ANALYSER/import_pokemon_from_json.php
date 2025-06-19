<?php
require_once __DIR__ . '/../../5-CONFIGURATOR/db.php';

// Chargement du JSON
$json = file_get_contents(__DIR__ . 'pokemon_data.json');
$pokemonData = json_decode($json, true);

// Préparer les correspondances avec les ID de tables
function getIdFromTable(PDO $pdo, string $table, string $column, string $value): int {
    $stmt = $pdo->prepare("SELECT id FROM $table WHERE $column = ?");
    $stmt->execute([$value]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? (int)$result['id'] : 0;
}

// Insertion dans la table `pokemon`
foreach ($pokemonData as $poke) {
    $styleId = getIdFromTable($pdo, 'styles', 'label', $poke['style']);
    $dmgTypeId = getIdFromTable($pdo, 'damage_types', 'label', $poke['type-de-dmg']);
    $roleId = getIdFromTable($pdo, 'roles', 'label', $poke['role']);

    $stmt = $pdo->prepare("
        INSERT INTO pokemon 
        (name, style, dmgtype, role, healAllies, croudControle, selfShield, allieShield, buff, debuff, hpScale, execute, image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $poke['nom'],
        $styleId,
        $dmgTypeId,
        $roleId,
        (int)$poke['sa-capacité-a-pouvoir-heal-les-alliers'],
        (int)$poke['sa-capacité-a-croud-controle'],
        (int)$poke['sa-capacité-a-SE-shield'],
        (int)$poke['sa-capacité-a-shield-les-alliers'],
        (int)$poke['sa-capacité-a-buff-les-alliers'],
        (int)$poke['sa-capacité-a-debuff'],
        (int)$poke['Scale-HP'],
        (int)$poke['Execute'],
        $poke['image']
    ]);
}

echo "✅ Import terminé avec succès !";
