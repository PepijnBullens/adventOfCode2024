<?php
    function getRows() {
        $rows = [];

        $handle = fopen(__DIR__ . '/data.txt', "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $rows[] = $line;
            }
    
            fclose($handle);
        }

        return $rows;
    }

    function checkDistance($values) {
        sort( $values );
        return $values[1] - $values[0];
    }

    function checkIncrease($levels) {
        $increase = true;

        foreach($levels as $key => $level) {
            $prev = !isset($levels[$key - 1]) ? $levels[$key] : $levels[$key - 1];
            if($level < $prev || ($key != 0 && $prev == $level) || checkDistance([$prev, $level]) > 3) $increase = false;
        }

        return $increase;
    }

    function checkDecrease($levels) {
        $decrease = true;

        foreach($levels as $key => $level) {
            $prev = !isset($levels[$key - 1]) ? $levels[$key] : $levels[$key - 1];
            if($level > $prev || ($key != 0 && $prev == $level) || checkDistance([$prev, $level]) > 3) $decrease = false;
        }

        return $decrease;
    }

    $safeCount = 0;
    $rows = getRows();

    foreach($rows as $row) {
        $levels = explode(" ", $row);
        if(checkIncrease($levels) || checkDecrease($levels)) $safeCount++;
    }

    echo $safeCount;
?>