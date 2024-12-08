<?php
function getGrid(): array {
    $grid = [];

    $handle = fopen(__DIR__ . '/data.txt', "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $row = str_split(trim($line));
            $grid[] = $row;
        }
        fclose($handle);
    }

    return $grid;
}

function getCases(array $grid): array {
    $cases = [];

    foreach ($grid as $y => $row) {
        foreach ($row as $x => $place) {
            if ($place != ".") {
                $cases[$place][] = [$x, $y];
            }
        }
    }

    return $cases;
}

$grid = getGrid();
$cases = getCases($grid);

$antinodes = [];

foreach ($cases as $case) {
    foreach ($case as $checkPlace) {
        foreach ($case as $place) {
            if ($checkPlace === $place) {
                continue;
            }

            $dx = $checkPlace[0] - $place[0];
            $dy = $checkPlace[1] - $place[1];

            $nx = $checkPlace[0] + $dx;
            $ny = $checkPlace[1] + $dy;

            if (isset($grid[$ny][$nx])) {
                $antinodes["$nx:$ny"] = true;
            }
        }
    }
}

echo count($antinodes);