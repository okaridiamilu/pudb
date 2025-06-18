<?php
class Team {
    private int $id;
    private string $name;
    private int $user_id;
    private int $pokemon1_id;
    private int $pokemon2_id;
    private int $pokemon3_id;
    private int $pokemon4_id;
    private int $pokemon5_id;

    // --- Getters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getUserId(): int { return $this->user_id; }
    public function getPokemon1Id(): int { return $this->pokemon1_id; }
    public function getPokemon2Id(): int { return $this->pokemon2_id; }
    public function getPokemon3Id(): int { return $this->pokemon3_id; }
    public function getPokemon4Id(): int { return $this->pokemon4_id; }
    public function getPokemon5Id(): int { return $this->pokemon5_id; }

    // --- Setters
    public function setId(int $id): self { $this->id = $id; return $this; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function setUserId(int $user_id): self { $this->user_id = $user_id; return $this; }
    public function setPokemon1Id(int $id): self { $this->pokemon1_id = $id; return $this; }
    public function setPokemon2Id(int $id): self { $this->pokemon2_id = $id; return $this; }
    public function setPokemon3Id(int $id): self { $this->pokemon3_id = $id; return $this; }
    public function setPokemon4Id(int $id): self { $this->pokemon4_id = $id; return $this; }
    public function setPokemon5Id(int $id): self { $this->pokemon5_id = $id; return $this; }

    public function getPokemonIds(): array {
    return array_filter([
        $this->pokemon1_id,
        $this->pokemon2_id,
        $this->pokemon3_id,
        $this->pokemon4_id,
        $this->pokemon5_id
    ]);
}

    
    // --- Hydrate
    public function hydrate(array $data): void {
    foreach ($data as $key => $value) {
        $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        if (method_exists($this, $method) && $value !== null) {
            $this->$method($value);
        }
    }
}

}
?>
