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

    function checkDistance($values) {
        return abs($values[1] - $values[0]);
    }

    function checkIncrease($levels) {
        for ($key = 1; $key < count($levels); $key++) {
            $prev = $levels[$key - 1];
            if ($levels[$key] < $prev || $prev == $levels[$key] || checkDistance([$prev, $levels[$key]]) > 3) {
                return false;
            }
        }
        return true;
    }

    function checkDecrease($levels) {
        for ($key = 1; $key < count($levels); $key++) {
            $prev = $levels[$key - 1];
            if ($levels[$key] > $prev || $prev == $levels[$key] || checkDistance([$prev, $levels[$key]]) > 3) {
                return false;
            }
        }
        return true;
    }

    function getVariants($rows) {
        $rowVariants = [];
        foreach ($rows as $row) {
            $levels = explode(" ", $row);
            $variants = [];
            foreach ($levels as $key => $level) {
                $variant = array_merge(array_slice($levels, 0, $key), array_slice($levels, $key + 1));
                $variants[] = $variant;
            }
            $variants[] = $levels;
            $rowVariants[] = $variants;
        }
        return $rowVariants;
    }

    $safeCount = 0;
    $rows = getRows();
    $rowVariants = getVariants($rows);

    foreach ($rowVariants as $row) {
        foreach ($row as $levels) {
            if (checkIncrease($levels) || checkDecrease($levels)) {
                $safeCount++;
                break;
            }
        }
    }

    echo $safeCount;
?>