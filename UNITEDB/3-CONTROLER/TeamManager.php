<?php
require_once __DIR__ . '/../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../1-MODEL/Team.php';

class TeamManager {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function add(Team $team): bool {
        $stmt = $this->db->prepare("
            INSERT INTO teams (name, user_id, pokemon1_id, pokemon2_id, pokemon3_id, pokemon4_id, pokemon5_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $team->getName(),
            $team->getUserId(),
            $team->getPokemon1Id(),
            $team->getPokemon2Id(),
            $team->getPokemon3Id(),
            $team->getPokemon4Id(),
            $team->getPokemon5Id()
        ]);
    }

    public function getAllByUser(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM teams WHERE user_id = ?");
        $stmt->execute([$userId]);
        $teams = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $team = new Team();
            $team->hydrate($row);
            $teams[] = $team;
        }

        return $teams;
    }

    public function getById(int $id): ?Team {
        $stmt = $this->db->prepare("SELECT * FROM teams WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $team = new Team();
            $team->hydrate($data);
            return $team;
        }

        return null;
    }

    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM teams WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }

    public function update(Team $team): bool {
    $stmt = $this->db->prepare("
        UPDATE teams SET 
            name = ?, 
            pokemon1_id = ?, 
            pokemon2_id = ?, 
            pokemon3_id = ?, 
            pokemon4_id = ?, 
            pokemon5_id = ? 
        WHERE id = ?
    ");
    return $stmt->execute([
        $team->getName(),
        $team->getPokemon1Id(),
        $team->getPokemon2Id(),
        $team->getPokemon3Id(),
        $team->getPokemon4Id(),
        $team->getPokemon5Id(),
        $team->getId()
    ]);
}
public function deleteAllByUser(int $userId): bool {
    $stmt = $this->db->prepare("DELETE FROM teams WHERE user_id = ?");
    return $stmt->execute([$userId]);
}

}
?>
