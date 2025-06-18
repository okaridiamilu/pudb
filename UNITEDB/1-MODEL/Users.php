<?php
class User {
    private int $id;
    private string $username;
    private string $password;
    private string $created_at;

    // HYDRATEUR
    public function hydrate(array $data): void {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // GETTERS
    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getCreated_at(): string {
        return $this->created_at;
    }

    // SETTERS
    public function setId(int $id): void {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    public function setUsername(string $username): void {
        if (!empty($username)) {
            $this->username = $username;
        }
    }

    public function setPassword(string $password): void {
        if (!empty($password)) {
            $this->password = $password;
        }
    }

    public function setCreated_at(string $created_at): void {
        $this->created_at = $created_at;
    }
}
