<?php
    function getData() {
        $rows = [];
        $handle = fopen(__DIR__ . '/data.txt', "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $rows[] = trim($line);
            }
            fclose($handle);
        }

        $rules = [];
        $sequences = [];

        foreach($rows as $row) {
            if(str_contains($row, "|")) {
                $rules[] = explode("|", $row);
            } else if(str_contains($row, ",")) $sequences[] = explode(",", $row);
        }

        return [
            "rules" => $rules,
            "sequences" => $sequences,
        ];
    }

    function getRulesForSequence($sequence, $rules) {
        $_rules = [];

        foreach($rules as $rule) {
            $contains = 0;

            foreach($sequence as $item) {
                if($item == $rule[0] || $item == $rule[1]) $contains++;
            }

            if($contains == 2) {
                $_rules[] = $rule;
            }
        }

        return $_rules;
    }

    function moveElement(&$array, $fromIndex, $toIndex) {
        if (!isset($array[$fromIndex]) || $fromIndex === $toIndex) {
            return;
        }
    
        $element = $array[$fromIndex];
        unset($array[$fromIndex]);
    
        $array = array_values($array);
        array_splice($array, $toIndex, 0, $element);
    }

    function correctOrder($sequence, $rulesForSequence) {
        $newSequence = $sequence;

        [$xIndexs, $yIndexs] = getXAndY($sequence, $rulesForSequence);

        if(count($xIndexs) == count($yIndexs)) {            
            $swapped = true;
            while ($swapped) {
                $swapped = false;
                foreach($xIndexs as $key => $index) {
                    if($index > $yIndexs[$key]) {
                        moveElement($newSequence, $yIndexs[$key][0], $index[0]);
                        [$xIndexs, $yIndexs] = getXAndY($newSequence, $rulesForSequence);
                        $swapped = true;
                    }
                }
            }
        }

        return $newSequence;
    }

    function getXAndY($sequence, $rules) {
        $xIndexs = [];
        $yIndexs = [];

        foreach ($rules as $key => $rule) {
            if (!in_array($rule[0], $sequence)) {
                continue;
            }

            $xIndexs[$key][] = array_search($rule[0], $sequence);
        }
         
        foreach ($rules as $key => $rule) {
            if (!in_array($rule[1], $sequence)) {
                continue;
            }

            $yIndexs[$key][] = array_search($rule[1], $sequence);
        }

        return [
            $xIndexs, 
            $yIndexs
        ];
    }

    function isValid($sequence, $rules) {
        $rulesForSequence = getRulesForSequence($sequence, $rules);
        $safe = true; 


        [$xIndexs, $yIndexs] = getXAndY($sequence, $rulesForSequence);

        if(count($xIndexs) == count($yIndexs)) {
            $safe = true;
            
            foreach($xIndexs as $key => $index) {
                if($index > $yIndexs[$key]) $safe = false;
            }

            return $safe;
        }
    }

    $data = getData();
    $rules = $data['rules'];
    $sequences = $data['sequences'];

    $count = 0;

    foreach ($sequences as $sequence) {
        $rulesForSequence = getRulesForSequence($sequence, $rules);

        if(!isValid($sequence, $rulesForSequence)) {
            $correctSequence = correctOrder($sequence, $rulesForSequence);
            $middle = $correctSequence[(count($correctSequence) - 1) / 2]; 
            $count += intval($middle);        
        }
    }
    
    echo $count;
?>