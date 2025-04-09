<?php
require_once 'Pokemon.php';


$attack = new AttackPokemon(100, 420, 2, 20);
$pokemon1 = new PokemonFeu("Dracaufeu", "https://assets.pokemon.com/assets/cms2/img/pokedex/full/006.png", 200, "Feu" , $attack);

$attack2 = new AttackPokemon(100, 350, 4, 20);
$pokemon2 = new PokemonEau("DracauWater", "https://www.pokebip.com/membres/galeries/1698/1698797421018135400.png", 200, "Eau" ,  $attack2);

$battleLog = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rounds = isset($_POST['rounds']) ? (int)$_POST['rounds'] : 1;

    // Reinitialize HP for a fresh battle (optional, remove if you want persistent HP)
    $pokemon1->setHP(200);
    $pokemon2->setHP(200);

    for ($i = 1; $i <= $rounds; $i++) {
        if ($pokemon1->getHP() <= 0 || $pokemon2->getHP() <= 0) {
            $battleLog[] = [
                'round' => $i,
                'hp1' => max(0, $pokemon1->getHP()),
                'hp2' => max(0, $pokemon2->getHP()),
            ];
            break; // Stop the battle if a Pokémon is KO'd
        }

        $attacker = ($pokemon1->getHP() > $pokemon2->getHP()) ? $pokemon1 : $pokemon2;
        $defender = ($attacker === $pokemon1) ? $pokemon2 : $pokemon1;

        $attacker->attack($defender);

        $battleLog[] = [
            'round' => $i,
            'hp1' => max(0, $pokemon1->getHP()),
            'hp2' => max(0, $pokemon2->getHP()),
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Battle Field</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            max-width: 800px;
            width: 100%;
        }
        .pokemon-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .pokemon-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 45%;
            text-align: center;
            transition: transform 0.2s;
        }
        .pokemon-card:hover {
            transform: scale(1.05);
        }
        .pokemon-card img {
            max-width: 100px;
            height: auto;
        }
        .pokemon-card h2 {
            margin: 10px 0;
            font-size: 1.5em;
            color: #333;
        }
        .pokemon-card p {
            margin: 5px 0;
            color: #666;
        }
        .battle-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .battle-form label {
            font-size: 1.2em;
            margin-right: 10px;
        }
        .battle-form input[type="number"] {
            padding: 5px;
            font-size: 1em;
            width: 60px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .battle-form button {
            padding: 8px 15px;
            font-size: 1em;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .battle-form button:hover {
            background-color: #45a049;
        }
        .battle-results {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .battle-results h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .winner {
            text-align: center;
            margin-top: 15px;
            font-size: 1.2em;
            font-weight: bold;
        }
        .winner.fire { color: #e74c3c; }
        .winner.water { color: #3498db; }
        .winner.draw { color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Pokémon Cards -->
        <div class="pokemon-cards">
            <div class="pokemon-card">
                <?php $pokemon1->displayImage(); ?>
                <h2><?php echo $pokemon1->getName(); ?></h2>
                <p>- Points: <?php echo $pokemon1->getHP(); ?></p>
                <p>- Min Attack: <?php echo $attack->getAttackMinimal(); ?></p>
                <p>- Max Attack: <?php echo $attack->getAttackMaximal(); ?></p>
                <p>- Special Attack: <?php echo $attack->getSpecialAttack(); ?>x</p>
                <p>- Special Probability: <?php echo $attack->getProbabilitySpecialAttack(); ?>%</p>
            </div>
            <div class="pokemon-card">
                <?php $pokemon2->displayImage(); ?>
                <h2><?php echo $pokemon2->getName(); ?></h2>
                <p>- Points: <?php echo $pokemon2->getHP(); ?></p>
                <p>- Min Attack: <?php echo $attack2->getAttackMinimal(); ?></p>
                <p>- Max Attack: <?php echo $attack2->getAttackMaximal(); ?></p>
                <p>- Special Attack: <?php echo $attack2->getSpecialAttack(); ?>x</p>
                <p>- Special Probability: <?php echo $attack2->getProbabilitySpecialAttack(); ?>%</p>
            </div>
        </div>

        <!-- Battle Form -->
        <div class="battle-form">
            <form method="POST">
                <label for="rounds">Nombre de rounds:</label>
                <input type="number" id="rounds" name="rounds" value="1" min="1" max="10">
                <button type="submit">Lancer le combat</button>
            </form>
        </div>

        <!-- Battle Results -->
        <?php if (!empty($battleLog)): ?>
            <div class="battle-results">
                <h2>Résultats du combat</h2>
                <table>
                    <tr>
                        <th>Round</th>
                        <th><?php echo $pokemon1->getName(); ?> HP</th>
                        <th><?php echo $pokemon2->getName(); ?> HP</th>
                    </tr>
                    <?php foreach ($battleLog as $log): ?>
                        <tr>
                            <td><?php echo $log['round']; ?></td>
                            <td><?php echo $log['hp1']; ?></td>
                            <td><?php echo $log['hp2']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="winner <?php echo $pokemon1->getHP() > $pokemon2->getHP() ? 'fire' : ($pokemon2->getHP() > $pokemon1->getHP() ? 'water' : 'draw'); ?>">
                    <?php
                    if ($pokemon1->getHP() > $pokemon2->getHP()) {
                        echo "{$pokemon1->getName()} a gagné le combat !";
                    } elseif ($pokemon2->getHP() > $pokemon1->getHP()) {
                        echo "{$pokemon2->getName()} a gagné le combat !";
                    } else {
                        echo "Match nul !";
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html><?php
require_once 'Pokemon.php';

// Initialize Pokémon of different types
$attack = new AttackPokemon(100, 200, 2, 20);
$pokemonFeu = new PokemonFeu("Dracaufeu", "https://assets.pokemon.com/assets/cms2/img/pokedex/full/006.png", 200, $attack);
$pokemonEau = new PokemonEau("DracauWater", "https://www.pokebip.com/membres/galeries/1698/1698797421018135400.png", 200, $attack);
$pokemonPlante = new PokemonPlante("Florizarre", "https://assets.pokemon.com/assets/cms2/img/pokedex/full/003.png", 200, $attack);
$pokemonNormal = new Pokemon("Rattata", "https://assets.pokemon.com/assets/cms2/img/pokedex/full/019.png", 200, "Normal", $attack);

$battleLog = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rounds = isset($_POST['rounds']) ? (int)$_POST['rounds'] : 1;
    $battleType = isset($_POST['battleType']) ? $_POST['battleType'] : 'feuVsEau';

    // Reset HP for each battle
    $pokemonFeu->setHP(200);
    $pokemonEau->setHP(200);
    $pokemonPlante->setHP(200);
    $pokemonNormal->setHP(200);

    // Select Pokémon pair based on battle type
    switch ($battleType) {
        case 'feuVsEau':
            $attacker = $pokemonFeu;
            $defender = $pokemonEau;
            break;
        case 'feuVsPlante':
            $attacker = $pokemonFeu;
            $defender = $pokemonPlante;
            break;
        case 'eauVsPlante':
            $attacker = $pokemonEau;
            $defender = $pokemonPlante;
            break;
        case 'normalVsFeu':
            $attacker = $pokemonNormal;
            $defender = $pokemonFeu;
            break;
        default:
            $attacker = $pokemonFeu;
            $defender = $pokemonEau;
    }

    for ($i = 1; $i <= $rounds; $i++) {
        if ($attacker->getHP() <= 0 || $defender->getHP() <= 0) {
            $battleLog[] = [
                'round' => $i,
                'hp1' => max(0, $attacker->getHP()),
                'hp2' => max(0, $defender->getHP()),
            ];
            break;
        }

        $damage = $attacker->attack($defender);

        $battleLog[] = [
            'round' => $i,
            'hp1' => max(0, $attacker->getHP()),
            'hp2' => max(0, $defender->getHP()),
        ];

        // Swap attacker and defender for next round (turn-based)
        [$attacker, $defender] = [$defender, $attacker];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Battle Simulator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            max-width: 800px;
            width: 100%;
        }
        .pokemon-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .pokemon-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 45%;
            text-align: center;
        }
        .pokemon-card img {
            max-width: 100px;
            height: auto;
        }
        .pokemon-card h2 {
            margin: 10px 0;
            font-size: 1.5em;
            color: #333;
        }
        .battle-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .battle-form label {
            font-size: 1.2em;
            margin-right: 10px;
        }
        .battle-form select, .battle-form input[type="number"] {
            padding: 5px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .battle-form button {
            padding: 8px 15px;
            font-size: 1em;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .battle-results {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        .winner {
            text-align: center;
            margin-top: 15px;
            font-size: 1.2em;
            font-weight: bold;
        }
        .winner.fire { color: #e74c3c; }
        .winner.water { color: #3498db; }
        .winner.plante { color: #2ecc71; }
        .winner.normal { color: #7f8c8d; }
        .winner.draw { color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Pokémon Cards (showing only the battling pair) -->
        <div class="pokemon-cards">
            <div class="pokemon-card">
                <?php $pokemonFeu->displayImage(); ?>
                <h2><?php echo $pokemonFeu->getName(); ?> (Feu)</h2>
            </div>
            <div class="pokemon-card">
                <?php echo isset($battleType) && $battleType === 'feuVsEau' ? $pokemonEau->displayImage() : ($battleType === 'feuVsPlante' ? $pokemonPlante->displayImage() : ($battleType === 'eauVsPlante' ? $pokemonPlante->displayImage() : ($battleType === 'normalVsFeu' ? $pokemonFeu->displayImage() : $pokemonEau->displayImage()))); ?>
                <h2><?php echo isset($battleType) && $battleType === 'feuVsEau' ? $pokemonEau->getName() . " (Eau)" : ($battleType === 'feuVsPlante' ? $pokemonPlante->getName() . " (Plante)" : ($battleType === 'eauVsPlante' ? $pokemonPlante->getName() . " (Plante)" : ($battleType === 'normalVsFeu' ? $pokemonFeu->getName() . " (Feu)" : $pokemonEau->getName() . " (Eau)"))); ?></h2>
            </div>
        </div>

        <!-- Battle Form -->
        <div class="battle-form">
            <form method="POST">
                <label for="battleType">Type de combat:</label>
                <select name="battleType" id="battleType">
                    <option value="feuVsEau">Feu vs Eau</option>
                    <option value="feuVsPlante">Feu vs Plante</option>
                    <option value="eauVsPlante">Eau vs Plante</option>
                    <option value="normalVsFeu">Normal vs Feu</option>
                </select>
                <label for="rounds">Rounds:</label>
                <input type="number" id="rounds" name="rounds" value="1" min="1" max="10">
                <button type="submit">Lancer le combat</button>
            </form>
        </div>

        <!-- Battle Results -->
        <?php if (!empty($battleLog)): ?>
            <div class="battle-results">
                <h2>Résultats du combat</h2>
                <table>
                    <tr>
                        <th>Round</th>
                        <th><?php echo $attacker->getName(); ?> HP</th>
                        <th><?php echo $defender->getName(); ?> HP</th>
                    </tr>
                    <?php foreach ($battleLog as $log): ?>
                        <tr>
                            <td><?php echo $log['round']; ?></td>
                            <td><?php echo $log['hp1']; ?></td>
                            <td><?php echo $log['hp2']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="winner <?php echo $attacker->getHP() > $defender->getHP() ? strtolower($attacker->getType()) : ($defender->getHP() > $attacker->getHP() ? strtolower($defender->getType()) : 'draw'); ?>">
                    <?php
                    if ($attacker->getHP() > $defender->getHP()) {
                        echo "{$attacker->getName()} a gagné !";
                    } elseif ($defender->getHP() > $attacker->getHP()) {
                        echo "{$defender->getName()} a gagné !";
                    } else {
                        echo "Match nul !";
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>