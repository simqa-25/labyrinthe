<?php

if (session_status() === PHP_SESSION_ACTIVE) session_destroy();

session_start();

function getMazes()
{
    return [
        [
            [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
            [1, 0, 0, 0, 1, 0, 0, 0, 0, 1],
            [1, 0, 1, 0, 1, 0, 1, 1, 0, 1],
            [1, 0, 1, 0, 0, 0, 0, 1, 0, 1],
            [1, 0, 0, 0, 1, 1, 0, 1, 0, 1],
            [1, 1, 1, 0, 1, 0, 0, 1, 0, 1],
            [1, 0, 0, 0, 0, 0, 1, 1, 0, 1],
            [1, 0, 1, 1, 1, 0, 1, 0, 0, 1],
            [1, 0, 0, 0, 0, 0, 0, 0, 'S', 1],
            [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        ],
        [
            [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
            [1, 0, 0, 0, 0, 0, 1, 0, 0, 1],
            [1, 0, 1, 1, 1, 0, 1, 0, 1, 1],
            [1, 0, 1, 0, 1, 0, 0, 0, 0, 1],
            [1, 0, 1, 0, 1, 1, 1, 1, 0, 1],
            [1, 0, 0, 0, 0, 0, 0, 1, 0, 1],
            [1, 1, 1, 1, 1, 1, 0, 1, 0, 1],
            [1, 0, 0, 0, 0, 1, 0, 0, 0, 1],
            [1, 0, 'S', 1, 0, 0, 0, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
        ],
    ];
}


if (!isset($_SESSION['maze'])) {
    $mazes = getMazes();
    $randomMazeIndex = array_rand($mazes); 
    $_SESSION['maze'] = $mazes[$randomMazeIndex];
    $_SESSION['cat'] = [1, 1]; 
    $_SESSION['moves'] = 0;
    $_SESSION['win'] = false;
}


function moveCat($direction)
{
    if ($_SESSION['win']) return;

    $maze = $_SESSION['maze'];
    [$catRow, $catCol] = $_SESSION['cat'];
    
    $newRow = $catRow;
    $newCol = $catCol;
    if ($direction === 'up') $newRow--;
    if ($direction === 'down') $newRow++;
    if ($direction === 'left') $newCol--;
    if ($direction === 'right') $newCol++;

    if (
        isset($maze[$newRow][$newCol]) && 
        $maze[$newRow][$newCol] !== 1 
    ) {
        
        if ($maze[$newRow][$newCol] === 'S') {
            $_SESSION['win'] = true;
        }

        
        $maze[$catRow][$catCol] = 0; 
        $maze[$newRow][$newCol] = 'C'; 
        $_SESSION['cat'] = [$newRow, $newCol];
        $_SESSION['moves']++; 
    }

    $_SESSION['maze'] = $maze; 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    moveCat($_POST['action']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restart'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


function displayMazeWithFog($maze, $catPosition)
{
    [$catRow, $catCol] = $catPosition;
    echo '<table border="1" style="border-collapse: collapse; text-align: center;">';
    foreach ($maze as $rowIndex => $row) {
        echo '<tr>';
        foreach ($row as $colIndex => $cell) {
            if (abs($rowIndex - $catRow) <= 1 && abs($colIndex - $catCol) <= 1) {
                if ($cell === 1) {
                    echo '<td style="width: 30px; height: 30px; background-color: black;"></td>';
                } elseif ($cell === 0) {
                    echo '<td style="width: 30px; height: 30px; background-color: white;"></td>';
                } elseif ($cell === 'C') {
                    echo '<td style="width: 30px; height: 30px; background-color: orange;">🐱</td>';
                } elseif ($cell === 'S') {
                    echo '<td style="width: 30px; height: 30px; background-color: yellow;">🐭</td>';
                }
            } else {
                echo '<td style="width: 30px; height: 30px; background-color: grey;"></td>';
            }
        }
        echo '</tr>';
    }
    echo '</table>';
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labyrinthe</title>
</head>

<body>
    <header>
        <h1>Labyrinthe : Le chat et la souris</h1>
    </header>
    <main>
        <?php if ($_SESSION['win']): ?>
            <h2>Félicitations ! Vous avez trouvé la souris ! 🐱🐭</h2>
            <p>Nombre de déplacements : <?= $_SESSION['moves']; ?></p>
            <form action="" method="post">
                <button type="submit" name="restart" value="1">Recommencer</button>
            </form>
        <?php else: ?>
            <h2>Voici votre labyrinthe :</h2>
            <?php displayMazeWithFog($_SESSION['maze'], $_SESSION['cat']); ?>

            <h3>Déplacements effectués : <?= $_SESSION['moves']; ?></h3>

            <form action="" method="post" style="margin-top: 20px;">
                <button type="submit" name="action" value="up">⬆️ Haut</button><br>
                <button type="submit" name="action" value="left">⬅️ Gauche</button>
                <button type="submit" name="action" value="down">⬇️ Bas</button>
                <button type="submit" name="action" value="right">➡️ Droite</button>
            </form>
        <?php endif; ?>
    </main>
</body>

</html>
