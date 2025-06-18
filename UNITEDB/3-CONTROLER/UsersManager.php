<?php
require_once __DIR__ . '/../5-CONFIGURATOR/db.php';
require_once __DIR__ . '/../1-MODEL/Users.php';

class UserManager {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function add(User $user): bool {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([
            $user->getUsername(),
            $user->getPassword()
        ]);
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM users");
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new User();
            $user->hydrate($row);
            $users[] = $user;
        }
        return $users;
    }

    public function getByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $user = new User();
            $user->hydrate($data);
            return $user;
        }
        return null;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    // Récupérer un utilisateur par ID (utile pour la page de modification)
public function getById(int $id): ?User {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $user = new User();
        $user->hydrate($data);
        return $user;
    }
    return null;
}

// Mettre à jour un utilisateur (username et password)
public function update(User $user): bool {
    $stmt = $this->db->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    return $stmt->execute([
        $user->getUsername(),
        password_hash($user->getPassword(), PASSWORD_DEFAULT),
        $user->getId()
    ]);
}

}
