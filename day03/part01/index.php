<?php    
    function getPositions($string, $substring) {
        $positions = [];

        $offset = 0;
        while (($position = strpos($string, $substring, $offset)) !== false) {
            $positions[] = $position;
            $offset = $position + strlen($substring);
        }

        return $positions;
    }

    function calculateStringToMul($string) {
        $regex = '/mul\((\d{1,3}),(\d{1,3})\)/';
        preg_match_all($regex, $string, $mulMatches);
        
        $total = 0;

        for ($i = 0; $i < count($mulMatches[0]); $i++) {
            $firstNumber = $mulMatches[1][$i];
            $secondNumber = $mulMatches[2][$i];

            $result = $firstNumber * $secondNumber;
            $total += $result;
        }

        return $total;
    }
    

    $input = file_get_contents(__DIR__ . '/data.txt');

    $result = calculateStringToMul($input);
    echo $result;
?>