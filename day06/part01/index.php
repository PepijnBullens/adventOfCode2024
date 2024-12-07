<?php
    function getgrid() {
        $grid = [];
        $handle = fopen(__DIR__ . '/data.txt', "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $grid[] = trim($line);
            }
            fclose($handle);
        }

        $tdgrid = [];
        $startPos = [];

        foreach($grid as $key => $row) {
            $charArray = str_split($row);
            $index = array_search("^", $charArray);
            if ($index !== false) {
                $startPos = [$index, $key];
            }
            
            $tdgrid[$key] = $charArray;
        }

        return [$tdgrid, $startPos];
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

    [$grid, $startPos] = getgrid();
    $guardX = $startPos[0];
    $guardY = $startPos[1];
    $currentDirection = 0;

    $directions = [
        [0, -1],  
        [1, 0], 
        [0, 1],  
        [-1, 0], 
    ];

    $grid[$startPos[1]][$startPos[0]] = "X";

    while($guardX >= 0 && $guardX < count($grid[0]) && $guardY >= 0 && $guardY < count($grid)) {
        [$currentDirection, $grid, $guardX, $guardY] = moveGuard($directions, $currentDirection, $guardX, $guardY, $grid);
    }

    $xCount = 0;

    foreach($grid as $row) {
        foreach($row as $char) {
            if($char == "X") $xCount++;
        }
    }

    echo $xCount;
?>