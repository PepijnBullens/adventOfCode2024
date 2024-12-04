<?php
function getRows() {
    $rows = [];
    $handle = fopen(__DIR__ . '/data.txt', "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $rows[] = trim($line);
        }
        fclose($handle);
    }
    return $rows;
}

function countWord($rows, $word) {
    $directions = [
        [0, 1],  
        [0, -1], 
        [1, 0],  
        [-1, 0], 
        [1, 1],  
        [-1, -1],
        [1, -1], 
        [-1, 1], 
    ];

    $wordLength = strlen($word);
    $rowCount = count($rows);
    $colCount = strlen($rows[0]);
    $result = 0;

    for ($y = 0; $y < $rowCount; $y++) {
        for ($x = 0; $x < $colCount; $x++) {
            if ($rows[$y][$x] == $word[0]) {
                foreach ($directions as [$dy, $dx]) {
                    $found = true;
                    for ($i = 0; $i < $wordLength; $i++) {
                        $ny = $y + $i * $dy;
                        $nx = $x + $i * $dx;
                        if (
                            $ny < 0 || $ny >= $rowCount ||
                            $nx < 0 || $nx >= $colCount ||
                            $rows[$ny][$nx] !== $word[$i] // char mistmatch
                        ) {
                            $found = false;
                            break;
                        }
                    }
                    if ($found) $result++;
                }
            }
        }
    }

    return $result;
}

$rows = getRows();
$word = "XMAS";

$result = countWord($rows, $word);
echo $result;
?>