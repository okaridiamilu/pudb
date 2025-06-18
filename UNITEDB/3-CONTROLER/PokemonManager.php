<?php
require_once __DIR__ . '/../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../1-MODEL/Pokemon.php';

class PokemonManager {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAll(): array {
    $stmt = $this->db->query("
        SELECT p.*, 
        s.label AS style_label, 
        d.label AS dmg_label, 
        r.label AS role_label
        FROM pokemon p
        JOIN styles s ON p.style = s.id
        JOIN damage_types d ON p.dmgType = d.id
        JOIN roles r ON p.role = r.id
    ");

    $results = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['style'] = $row['style_label'];
        $row['dmgType'] = $row['dmg_label'];
        $row['role'] = $row['role_label'];

        $pokemon = new Pokemon();
        $pokemon->hydrate($row);
        $results[] = $pokemon;
    }

    return $results;
}


    public function getById(int $id): ?Pokemon {
        $stmt = $this->db->prepare("SELECT * FROM pokemon WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $pokemon = new Pokemon();
            $pokemon->hydrate($data);
            return $pokemon;
        }
        return null;
    }

    public function getFiltered(array $filters): array {
    $sql = "SELECT p.*, s.label AS style, d.label AS dmgType, r.label AS role
            FROM pokemon p
            LEFT JOIN styles s ON p.style = s.id
            LEFT JOIN damage_types d ON p.dmgType = d.id
            LEFT JOIN roles r ON p.role = r.id
            WHERE 1=1";

    $params = [];

    // Filtres textuels
    if (!empty($filters['style'])) {
        $sql .= " AND s.label = ?";
        $params[] = $filters['style'];
    }

    if (!empty($filters['dmgType'])) {
        $sql .= " AND d.label = ?";
        $params[] = $filters['dmgType'];
    }

    if (!empty($filters['role'])) {
        $sql .= " AND r.label = ?";
        $params[] = $filters['role'];
    }

    if (!empty($filters['search'])) {
        $sql .= " AND p.name LIKE ?";
        $params[] = '%' . $filters['search'] . '%';
    }

    // Filtres booléens : grouper avec OR
    $boolFields = ['healAllies','croudControle','selfShield','allieShield','buff','debuff','hpScale','execute'];
    $boolConditions = [];
    foreach ($boolFields as $boolField) {
        if (!empty($filters[$boolField])) {
            $boolConditions[] = "p.$boolField = 1";
        }
    }
    if (!empty($boolConditions)) {
        $sql .= " AND (" . implode(' OR ', $boolConditions) . ")";
    }

    // Tri
    $sql .= " ORDER BY p.name " . ($filters['sort'] === 'desc' ? 'DESC' : 'ASC');

    // DEBUG (à désactiver en prod)
    // error_log("SQL: $sql");
    // error_log("Params: " . print_r($params, true));

    // Exécution
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    $results = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pokemon = new Pokemon();
        $pokemon->hydrate($row);
        $results[] = $pokemon;
    }
    return $results;
}



    // Tu pourras ajouter add(), update(), delete() ici si nécessaire
}
?>
