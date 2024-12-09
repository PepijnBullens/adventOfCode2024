<?php
    function getRows() {
        $rows = [];
        $handle = fopen(__DIR__ . '/data.txt', "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $characters = str_split($line);

                $tempRow = [];
                $toggle = false;
                $id = 0;
                
                foreach($characters as $char) {
                    $splitChars = str_split($char);
                    foreach($splitChars as $splitChar) {
                        if($toggle) {
                            for($i = 0; $i < intval($splitChar); $i++) {
                                $tempRow[] = ".";
                            }
                        }
                        else {
                            for($i = 0; $i < intval($splitChar); $i++) {
                                $tempRow[] = $id;
                            }
                            $id++;
                        }
                        $toggle = !$toggle;
                    }
                }
                
                $rows[] = $tempRow;
            }
            fclose($handle);
        }
        return $rows;
    }

    function reorderRows($rows) {
        $reorderedRows = $rows;

        foreach($reorderedRows as &$row) {
            while (($key = array_search(".", $row)) !== false) {
                $row[$key] = array_pop($row);
            }
        }

        return $reorderedRows;
    }

    $rows = getRows();
    $reorderedRows = reorderRows($rows);

    $sum = 0;

    foreach($reorderedRows as $row) {
        foreach($row as $i => $char) {
           $sum += $i * intval($char); 
        }
    }

    echo $sum;
?>