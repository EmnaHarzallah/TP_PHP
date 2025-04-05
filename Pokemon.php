<?php

class AttackPokemon { 
    private int $attackMinimal ;
    private int $attackMaximal ; 
    private int $specialAttack ; 
    private int $probabilitySpecialAttack  ;
    function __construct(int $attackMinimal, int $attackMaximal, int $specialAttack, int $probabilitySpecialAttack) {
        $this->attackMinimal = $attackMinimal;
        $this->attackMaximal = $attackMaximal;
        $this->specialAttack = $specialAttack;
        $this->probabilitySpecialAttack = $probabilitySpecialAttack;
    }

    function getSpecialAttack(): int {
        return $this->specialAttack;
    }

    function getProbabilitySpecialAttack(): int {
        return $this->probabilitySpecialAttack;
    }

    function getAttackMinimal(): int {
        return $this->attackMinimal;
    }

    function getAttackMaximal(): int {
        return $this->attackMaximal;
    }
}


    class Pokemon { 
        protected string $name ; 
        protected string $url ; 
        protected int $hp ; 
        protected string $type="Normal" ;
        protected AttackPokemon $attackPokemon ;

        function __construct(string $name, string $url, int $hp, string $type ,AttackPokemon $attackPokemon) {
            $this->name = $name;
            $this->url = $url;
            $this->type = $type;
            $this->hp = $hp;
            $this->attackPokemon = new AttackPokemon($attackPokemon->getAttackMinimal(), $attackPokemon->getAttackMaximal(), $attackPokemon->getSpecialAttack(), $attackPokemon->getProbabilitySpecialAttack());
        }
        function getName(): string {
            return $this->name;
        }
        function getURL(): string {
            return $this->url;
        }
        function getHP(): int {
            return $this->hp;
        }
        function getAttackPokemon(): AttackPokemon {
            return $this->attackPokemon;
        }
        function setName(string $name){
            $this->name=$name ; 
        }
        function setURL(string $url){ 
            $this->url=$url ; 
        }
        function setHP(int $hp){ 
            $this->hp=$hp ; 
        }
        function setAttackPokemon(AttackPokemon $attackPokemon){ 
            $this->attackPokemon=$attackPokemon ; }

        function attack(Pokemon $p){

            $attackpoints = rand($this->attackPokemon->getAttackMinimal(), $this->attackPokemon->getAttackMaximal());
            $chance = rand(0, 100); 
            if ($chance <= $this->attackPokemon->getProbabilitySpecialAttack()) {
                $attackpoints *= $this->attackPokemon->getSpecialAttack(); 
                echo $this->name . " effectue une attaque spéciale\n"; 
            }
            $p->setHP($p->getHP() - $attackpoints);
            echo $this->name . " attaque " . $p->getName() . "\n";
            echo $p->getName() . " a maintenant " . $p->getHP() . " HP\n";
                
        
            p.setHP(p.getHP()-$attackpoints) ;
            echo($this->name + "attaque" + $p.getName() );
            echo($p.getName() + " a maintenant " + $p.getHP() + "HP ") ;
            echo ("Je suis " + $this->name + " avec " + $this->hp + " HP et je fais des attaques entre " + $this->attackPokemon->getAttackMinimal() . 
            " et " + $this->attackPokemon->getAttackMaximal() + " points de dégâts\n" + $this->hp + "HP et je fais des attaques entre " +
             $this->attackPokemon.getAttackMinimal() + " et " + $this->attackPokemon.getAttackMaximal() + " points de dégats") ;
        }
    }

    class PokemonFeu extends Pokemon{ 
        function __construct(string $name, string $url, int $hp, string $type , AttackPokemon $attackPokemon) {
            parent::__construct($name, $url, $hp, "Feu", $attackPokemon);
        }
        
        function whoAmI(){ 
            echo("Je suis un pokemon de type feu") ; 
            parent::whoAmI() ; 
        }
    }
    
    class PokemonEau extends Pokemon{ 
        function __construct(string $name, string $url, int $hp, string $type , AttackPokemon $attackPokemon) {
            parent::__construct($name, $url, $hp , "Eau" , $attackPokemon);
        }
    
        function attack(Pokemon $p){
            parent::attack($p); 
            if ($p instanceof PokemonPlante) { 
                echo $this->name . " attaque " . $p->getName() . " avec une attaque spéciale\n"; 
                $p->setHP($p->getHP() - (int)($p->getHP() / 2)); 
                echo $p->getName() . " a maintenant " . $p->getHP() . " HP\n";
            }
        
                $p.setHP(p.getHP()-p.getHP()/2) ; 
                echo(p.getName() + " a maintenant " + p.getHP() + "HP ") ;
            
        }}

    class PokemonPlante extends Pokemon{ 
        function __construct(string $name, string $url, int $hp, string $type ,AttackPokemon $attackPokemon) {
            parent::__construct($name, $url, $hp, "Plante",  $attackPokemon);
        }
        function whoAmI(){ 
            echo("Je suis un pokemon de type plante") ; 
            parent::whoAmI() ; 
        }
    }

?>