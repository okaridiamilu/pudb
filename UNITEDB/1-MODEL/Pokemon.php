<?php
class Pokemon {
    private $id;
    private $name;
    private $style;
    private $dmgType;
    private $role;
    private $healAllies;
    private $croudControle;
    private $selfShield;
    private $allieShield;
    private $buff;
    private $debuff;
    private $hpScale;
    private $execute;
    private $image;

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getStyle() { return $this->style; }
    public function getDmgType() { return $this->dmgType; }
    public function getRole() { return $this->role; }
    public function getHealAllies() { return $this->healAllies; }
    public function getCroudControle() { return $this->croudControle; }
    public function getSelfShield() { return $this->selfShield; }
    public function getAllieShield() { return $this->allieShield; }
    public function getBuff() { return $this->buff; }
    public function getDebuff() { return $this->debuff; }
    public function getHpScale() { return $this->hpScale; }
    public function getExecute() { return $this->execute; }
    public function getImage() { return $this->image; }

    // Setters
    public function setId($id) { $this->id = $id; return $this; }
    public function setName($name) { $this->name = $name; return $this; }
    public function setStyle($style) { $this->style = $style; return $this; }
    public function setDmgType($dmgType) { $this->dmgType = $dmgType; return $this; }
    public function setRole($role) { $this->role = $role; return $this; }
    public function setHealAllies($value) { $this->healAllies = $value; return $this; }
    public function setCroudControle($value) { $this->croudControle = $value; return $this; }
    public function setSelfShield($value) { $this->selfShield = $value; return $this; }
    public function setAllieShield($value) { $this->allieShield = $value; return $this; }
    public function setBuff($value) { $this->buff = $value; return $this; }
    public function setDebuff($value) { $this->debuff = $value; return $this; }
    public function setHpScale($value) { $this->hpScale = $value; return $this; }
    public function setExecute($value) { $this->execute = $value; return $this; }
    public function setImage($image) { $this->image = $image; return $this; }

    // Hydratation
    public function hydrate(array $data): void {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
?>
