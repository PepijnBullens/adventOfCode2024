<?php   

    function parseInput($input) {
        $left = [];
        $right = [];
        $parts = preg_split('/\s+/', $input);

        $rowCount = 1;
        foreach($parts as $part) {
            if($rowCount == 1) $left[] = $part;
            if($rowCount == 2) $right[] = $part;
            $rowCount == 2 ? $rowCount = 1 : $rowCount++;
        }

        return [
            "left" => $left,
            "right" => $right
        ];
    }

    $input = file_get_contents(__DIR__ . '/data.txt');
    $data = parseInput($input);

    $answer = 0;

    function arrayCount($array, $q) {
        $countValues = array_count_values($array);
        $count = isset($countValues[$q]) ? $countValues[$q] : 0;
        return $count;
    }

    foreach($data["left"] as $item) {
        $times = arrayCount($data["right"], $item);
        $result = $item * $times;
        $answer += $result;
    }

    echo $answer;
?>