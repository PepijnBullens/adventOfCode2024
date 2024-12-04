<?php    
    function nearestSmallestNumber($array, $number) {
        $nearest = null;

        foreach ($array as $value) {
            if ($value < $number) {
                if ($nearest === null || ($number - $value) < ($number - $nearest)) {
                    $nearest = $value;
                }
            }
        }
        
        return $nearest;
    }

    function getMatchPositions($doMatches) {
        $positions = [];

        foreach($doMatches[0] as $match) {
            $positions[] = $match[1];
        }

        return $positions;
    }

    function calculateStringToMul($string) {
        $regexMul = '/mul\((\d{1,3}),(\d{1,3})\)/';
        preg_match_all($regexMul, $string, $mulMatches, PREG_OFFSET_CAPTURE);
        
        $regexDo = '/do\(\)/';
        preg_match_all($regexDo, $string, $doMatches, PREG_OFFSET_CAPTURE);

        $regexDont = '/don\'t\(\)/';
        preg_match_all($regexDont, $string, $dontMatches, PREG_OFFSET_CAPTURE);

        $total = 0;

        $doMatchPositions = getMatchPositions($doMatches);
        $dontMatchPositions = getMatchPositions($dontMatches);


        for ($i = 0; $i < count($mulMatches[0]); $i++) {
            $do = false;

            $nearestDoMatch = nearestSmallestNumber($doMatchPositions, $mulMatches[1][$i][1]);
            $nearestDontMatch = nearestSmallestNumber($dontMatchPositions, $mulMatches[1][$i][1]);

            $rule = false;
            if($nearestDoMatch > $nearestDontMatch || $nearestDoMatch == 0) {
                $rule = true;
            }
            if($rule) $do = true;
            
            if($do) {
                $firstNumber = $mulMatches[1][$i][0];
                $secondNumber = $mulMatches[2][$i][0];

                $result = $firstNumber * $secondNumber;
                $total += $result;
            }
        }

        return $total;
    }
    

    $input = file_get_contents(__DIR__ . '/data.txt');
    $result = calculateStringToMul($input);
    
    echo $result;
?>