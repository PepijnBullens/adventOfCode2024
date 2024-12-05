<?php
    // 
    // I'm so tired. 
    // 


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

    function getCharacters($rows) {
        $characters = [];
        foreach ($rows as $row) {
            $characters[] = str_split($row);
        }
        return $characters;
    }

    function countXPattern($characters, $x, $y) {
        $valid = false;

        if(
            isset($characters[$y + 1][$x + 1]) && isset($characters[$y - 1][$x - 1]) &&
            isset($characters[$y - 1][$x + 1]) && isset($characters[$y + 1][$x - 1]) &&
            isset($characters[$y + 1][$x]) && isset($characters[$y - 1][$x]) &&
            isset($characters[$y][$x + 1]) && isset($characters[$y][$x - 1]) &&
            (
            $characters[$y + 1][$x + 1] == "M" && $characters[$y - 1][$x - 1] == "S" &&
            $characters[$y - 1][$x + 1] == "S" && $characters[$y + 1][$x - 1] == "M" ||
            $characters[$y + 1][$x + 1] == "S" && $characters[$y - 1][$x - 1] == "M" &&
            $characters[$y - 1][$x + 1] == "M" && $characters[$y + 1][$x - 1] == "S" ||
            $characters[$y + 1][$x + 1] == "S" && $characters[$y - 1][$x - 1] == "M" &&
            $characters[$y - 1][$x + 1] == "S" && $characters[$y + 1][$x - 1] == "M" ||
            $characters[$y + 1][$x + 1] == "M" && $characters[$y - 1][$x - 1] == "S" &&
            $characters[$y - 1][$x + 1] == "M" && $characters[$y + 1][$x - 1] == "S")
        ) {
            $valid = true;
        }

        return $valid ? 1 : 0;
    }

    $rows = getRows();
    $characters = getCharacters($rows);
    $result = 0;

    for ($y = 0; $y < count($characters); $y++) {
        for ($x = 0; $x < count($characters[$y]); $x++) {
            if ($characters[$y][$x] === "A") {
                $result += countXPattern($characters, $x, $y);
            }
        }
    }

    echo $result;
?>