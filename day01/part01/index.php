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
    sort( $data["left"] );
    sort( $data["right"] );

    $answer = 0;

    if(count($data["left"]) !== count($data["right"])) echo "Not an equal amount of numbers";
    else {
        for($i = 0; $i < count($data["right"]); $i++) {
            $toBeSorted = [$data["left"][$i], $data["right"][$i]];
            sort($toBeSorted);
            $answer += $toBeSorted[1] - $toBeSorted[0];
        }
    }

    echo $answer;
?>