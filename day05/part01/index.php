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

    $data = getData();
    $rules = $data['rules'];
    $sequences = $data['sequences'];

    $count = 0;

    foreach ($sequences as $sequence) {
        $rulesForSequence = getRulesForSequence($sequence, $rules);
        $safe = true; 

        $xIndexs = [];
        $yIndexs = [];

        foreach ($rulesForSequence as $key => $rule) {
            if (!in_array($rule[0], $sequence)) {
                continue;
            }

            $xIndexs[$key][] = array_search($rule[0], $sequence);

        }
         
        foreach ($rulesForSequence as $key => $rule) {
            if (!in_array($rule[1], $sequence)) {
                continue;
            }

            $yIndexs[$key][] = array_search($rule[1], $sequence);
        }

        if(count($xIndexs) == count($yIndexs)) {
            $safe = true;
            
            foreach($xIndexs as $key => $index) {
                if($index > $yIndexs[$key]) $safe = false;
            }

            if($safe) {
                $middle = $sequence[(count($sequence) - 1) / 2];
                $count += intval($middle);
            }
        }
    }
    
    echo $count;
?>