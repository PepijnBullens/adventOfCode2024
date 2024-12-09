<?php
    function parseInput($line) {
        $characters = str_split($line);
        $tempRow = [];
        $toggle = false;
        $id = 0;

        foreach ($characters as $char) {
            $splitChars = str_split($char);
            foreach ($splitChars as $splitChar) {
                if ($toggle) {
                    for ($i = 0; $i < intval($splitChar); $i++) {
                        $tempRow[] = ".";
                    }
                } else {
                    for ($i = 0; $i < intval($splitChar); $i++) {
                        $tempRow[] = $id;
                    }
                    $id++;
                }
                $toggle = !$toggle;
            }
        }

        return $tempRow;
    }

    function reorderFilesystem($row) {
        $freeSpaces = [];
        $files = [];

        $currentIndex = 0;

        while ($currentIndex < count($row)) {
            $start = $currentIndex;
            $value = $row[$currentIndex];

            while ($currentIndex < count($row) && $row[$currentIndex] == $value) {
                $currentIndex++;
            }

            $length = $currentIndex - $start;
            if ($value === ".") {
                $freeSpaces[] = ['start' => $start, 'length' => $length];
            } else {
                $files[] = ['id' => $value, 'start' => $start, 'length' => $length];
            }
        }

        usort($files, function($a, $b) {
            return $b['id'] - $a['id'];
        });

        foreach ($files as &$file) {
            foreach ($freeSpaces as &$space) {
                if ($space['length'] >= $file['length'] && $space['start'] < $file['start']) {
                    for ($i = 0; $i < $file['length']; $i++) {
                        $row[$space['start'] + $i] = $file['id'];
                    }

                    $space['start'] += $file['length'];
                    $space['length'] -= $file['length'];

                    for ($i = 0; $i < $file['length']; $i++) {
                        $row[$file['start'] + $i] = ".";
                    }

                    $file['start'] = $space['start'];
                    break;
                }
            }
        }
        
        return $row;
    }

    $input = file_get_contents(__DIR__ . '/data.txt');
    $row = parseInput(trim($input));

    $resultRow = reorderFilesystem($row);

    $sum = 0;

    foreach($resultRow as $i => $char) {
        $sum += $i * intval($char); 
    }

    echo $sum;
?>