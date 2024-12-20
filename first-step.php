<?php
session_start();


function generateFixedMaze()
{
    return [
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
    ];
}


if (!isset($_SESSION['maze'])) {
    $_SESSION['maze'] = generateFixedMaze(); 
    $_SESSION['cat'] = [1, 1]; 
    $_SESSION['moves'] = 0; 
    $_SESSION['win'] = false; 
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
        <h2>Voici votre labyrinthe :</h2>
        <?php displayMazeWithFog($_SESSION['maze'], $_SESSION['cat']); ?>
    </main>
</body>

</html>
