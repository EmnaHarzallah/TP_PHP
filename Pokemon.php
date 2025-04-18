<?php

class AttackPokemon {
    private int $attackMinimal;
    private int $attackMaximal;
    private int $specialAttack;
    private int $probabilitySpecialAttack;

    function __construct(int $attackMinimal, int $attackMaximal, int $specialAttack, int $probabilitySpecialAttack) {
        $this->attackMinimal = $attackMinimal;
        $this->attackMaximal = $attackMaximal;
        $this->specialAttack = $specialAttack;
        $this->probabilitySpecialAttack = $probabilitySpecialAttack;
    }

    function getSpecialAttack(): int { return $this->specialAttack; }
    function getProbabilitySpecialAttack(): int { return $this->probabilitySpecialAttack; }
    function getAttackMinimal(): int { return $this->attackMinimal; }
    function getAttackMaximal(): int { return $this->attackMaximal; }
}

class Pokemon {
    protected string $name;
    protected string $url;
    protected int $hp;
    protected string $type = "Normal";
    protected AttackPokemon $attackPokemon;

    function __construct(string $name, string $url, int $hp, string $type, AttackPokemon $attackPokemon) {
        $this->name = $name;
        $this->url = $url;
        $this->hp = $hp;
        $this->type = $type;
        $this->attackPokemon = $attackPokemon;
    }

    function getName(): string { return $this->name; }
    function getURL(): string { return $this->url; }
    function getHP(): int { return $this->hp; }
    function getType(): string { return $this->type; }
    function setHP(int $hp) { $this->hp = max(0, $hp); }
    function displayImage() { echo "<img src='{$this->url}' alt='{$this->name}' width='100'>"; }

    // Base attack method for Normal-type Pokémon (1x damage to all)
    function attack(Pokemon $defender): int {
        $damage = rand($this->attackPokemon->getAttackMinimal(), $this->attackPokemon->getAttackMaximal());
        if (rand(0, 100) <= $this->attackPokemon->getProbabilitySpecialAttack()) {
            $damage *= $this->attackPokemon->getSpecialAttack();
        }
        $defender->setHP($defender->getHP() - $damage);
        return $damage;
    }
}

class PokemonFeu extends Pokemon {
    function __construct(string $name, string $url, int $hp, string $type , AttackPokemon $attackPokemon) {
        parent::__construct($name, $url, $hp, "Feu", $attackPokemon);
    }

    
    function attack(Pokemon $defender): int {
        // Calcul des dégâts de base
        $damage = rand($this->attackPokemon->getAttackMinimal(), $this->attackPokemon->getAttackMaximal());
        echo "Base damage: $damage\n";
    
        // Application de l'attaque spéciale si la probabilité est respectée
        if (rand(0, 100) <= $this->attackPokemon->getProbabilitySpecialAttack()) {
            $damage *= $this->attackPokemon->getSpecialAttack();
            echo "Special attack applied! New damage: $damage\n";
        }
    
        // Calcul du multiplicateur de type
        $typeMultiplier = 1.0;
        if ($defender->getType() === "Feu") {
            $typeMultiplier = 2.0; // Super efficace contre le type Feu
        } elseif ($defender->getType() === "Eau" || $defender->getType() === "Plante") {
            $typeMultiplier = 0.5; // Pas très efficace contre Eau ou Plante
        }
    
        // Application du multiplicateur de type
        $damage = (int)($damage * $typeMultiplier);
        echo "Damage after type multiplier ($typeMultiplier): $damage\n";
    
        // Mise à jour des HP du défenseur
        $newHP = $defender->getHP() - $damage;
        $defender->setHP($newHP);
        echo "Defender HP after attack: " . $defender->getHP() . "\n";
    
        return $damage;
    }
}

class PokemonEau extends Pokemon {
    function __construct(string $name, string $url, int $hp, string $type , AttackPokemon $attackPokemon) {
        parent::__construct($name, $url, $hp, "Eau", $attackPokemon);
    }

    // Water-type attack: 2x vs Fire, 0.5x vs Water/Grass, 1x vs Normal
    function attack(Pokemon $defender): int {
        $damage = rand($this->attackPokemon->getAttackMinimal(), $this->attackPokemon->getAttackMaximal());
        echo "Base damage: $damage\n";
    
        if (rand(0, 100) <= $this->attackPokemon->getProbabilitySpecialAttack()) {
            $damage *= $this->attackPokemon->getSpecialAttack();
            echo "Special attack applied! New damage: $damage\n";
        }
    
        $typeMultiplier = 1.0;
        if ($defender->getType() === "Eau") {
            $typeMultiplier = 2.0; // Super effective against Water
        } elseif ($defender->getType() === "Plante" || $defender->getType() === "Feu") {
            $typeMultiplier = 0.5; // Not very effective against Grass or Fire
        }
    
        $damage = (int)($damage * $typeMultiplier);
        echo "Damage after type multiplier ($typeMultiplier): $damage\n";
    
        $defender->setHP($defender->getHP() - $damage);
        echo "Defender HP after attack: " . $defender->getHP() . "\n";
    
        return $damage;
    }
}

class PokemonPlante extends Pokemon {
    function __construct(string $name, string $url, int $hp, string $type , AttackPokemon $attackPokemon) {
        parent::__construct($name, $url, $hp, "Plante", $attackPokemon);
    }

    // Grass-type attack: 2x vs Water, 0.5x vs Grass/Fire, 1x vs Normal
    function attack(Pokemon $defender): int {
        $damage = rand($this->attackPokemon->getAttackMinimal(), $this->attackPokemon->getAttackMaximal());
        if (rand(0, 100) <= $this->attackPokemon->getProbabilitySpecialAttack()) {
            $damage *= $this->attackPokemon->getSpecialAttack();
        }

        $typeMultiplier = 1.0;
        if ($defender->getType() === "Eau") {
            $typeMultiplier = 2.0; // Super effective against Water
        } elseif ($defender->getType() === "Plante" || $defender->getType() === "Feu") {
            $typeMultiplier = 0.5; // Not very effective against Grass or Fire
        }

        $damage = (int)($damage * $typeMultiplier);
        $defender->setHP($defender->getHP() - $damage);
        return $damage;
    }
}
?>