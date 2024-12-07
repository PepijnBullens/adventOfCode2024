<?php
function getGrid(): array {
    $grid = [];
    $startPos = [];

    $handle = fopen(__DIR__ . '/data.txt', "r");
    if ($handle) {
        $y = 0;
        while (($line = fgets($handle)) !== false) {
            $row = str_split(trim($line));
            $grid[] = $row;

            if (($x = array_search('^', $row)) !== false) {
                $startPos = [$x, $y];
            }
            $y++;
        }
        fclose($handle);
    }

    return [$grid, $startPos];
}

function moveGuard($dir, $currentDir, $x, $y, $grid) {
    $newX = $x + $dir[$currentDir][0];
    $newY = $y + $dir[$currentDir][1];

    if($newX < 0 || $newX > count($grid[0]) - 1 || $newY < 0 || $newY > count($grid) - 1) {
        return [$currentDir, $grid, $newX, $newY];
    }

    if($grid[$newY][$newX] == "#") {
        $currentDir < 3 ? $currentDir++ : $currentDir = 0;
        return [$currentDir, $grid, $x, $y];
    } else {
        $grid[$newY][$newX] = "X";
        return [$currentDir, $grid, $newX, $newY];
    }

}

[$grid, $startPos] = getGrid();
$directions = [
    [0, -1],  // Up
    [1, 0],   // Right
    [0, 1],   // Down
    [-1, 0],  // Left
];

$originalGrid = $grid;
$loopCount = 0;

foreach ($grid as $y => $row) {
    foreach ($row as $x => $char) {
        if ($char === ".") {

            $grid = $originalGrid;
            $grid[$y][$x] = "#";

            $currentDir = 0;
            [$guardX, $guardY] = $startPos;
            $grid[$guardY][$guardX] = "X";

            $visitedPositions = [];
            $loopDetected = false;

            while ($guardX >= 0 && $guardX < count($grid[0]) && $guardY >= 0 && $guardY < count($grid)) {
                [$currentDir, $grid, $guardX, $guardY] = moveGuard($directions, $currentDir, $guardX, $guardY, $grid);

                $state = "$guardX,$guardY,$currentDir";
                if (isset($visitedPositions[$state])) {
                    $loopCount++;
                    break;
                }

                $visitedPositions[$state] = true;
            }
        }
    }
}

echo $loopCount;
?>