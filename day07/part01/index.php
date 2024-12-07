<?php
    function getRows() {
        $rows = [];
        $handle = fopen(__DIR__ . '/data.txt', "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $rows[] = explode(" ", $line);
            }
            fclose($handle);
        }
        return $rows;
    }

    function generateOperatorCombinations($operators, $numOperators, $current = []) {
        if (count($current) == $numOperators) {
            return [$current];
        }
        
        $combinations = [];
        foreach ($operators as $operator) {
            $newCombination = array_merge($current, [$operator]);
            $combinations = array_merge($combinations, generateOperatorCombinations($operators, $numOperators, $newCombination));
        }
        return $combinations;
    }

    function generateExpressions($numbers) {
        $operators = ['+', '*']; 
        $numOperators = count($numbers) - 1;
        $results = [];
    
        $operatorCombinations = generateOperatorCombinations($operators, $numOperators);
    
        foreach ($operatorCombinations as $combination) {
            $expression = $numbers[0];
            for ($i = 0; $i < $numOperators; $i++) {
                $expression = "(" . $expression . " " . $combination[$i] . " " . $numbers[$i + 1] . ")";
            }
            $result = eval("return $expression;");
            $results[] = $result;
        }
    
        return $results;
    }
    

    $rows = getRows();
    $total = 0;

    foreach($rows as $rowKey => $row) {
        $answer = str_replace(":", "", $row[0]);
        
        $numbers = [];
        
        foreach($row as $i => $number) {
            if($i != 0) $numbers[] = $number;
        }
        
        $results = generateExpressions($numbers);

        $equationsDone = [];

        foreach($results as $result) {
            if($result == $answer && !in_array($rowKey, $equationsDone)) {
                $equationsDone[] = $rowKey;
                $total += $result;
            }
        }
    }

    echo $total;
?>