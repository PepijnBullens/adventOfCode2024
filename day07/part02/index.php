<?php

function getRows() {
    $rows = [];
    $handle = fopen(__DIR__ . '/data.txt', "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $rows[] = explode(" ", trim($line));
        }
        fclose($handle);
    }
    return $rows;
}

function generateOperatorCombinations($operators, $numOperators) {
    $combinations = [[]];
    for ($i = 0; $i < $numOperators; $i++) {
        $newCombinations = [];
        foreach ($combinations as $combo) {
            foreach ($operators as $operator) {
                $newCombinations[] = array_merge($combo, [$operator]);
            }
        }
        $combinations = $newCombinations;
    }
    return $combinations;
}

function evaluateExpression($numbers, $combination) {
    $result = $numbers[0];
    for ($i = 0; $i < count($combination); $i++) {
        switch ($combination[$i]) {
            case '+':
                $result += $numbers[$i + 1];
                break;
            case '*':
                $result *= $numbers[$i + 1];
                break;
            case '||':
                $result = intval($result . $numbers[$i + 1]);
                break;
        }
    }
    return $result;
}

function generateExpressions($numbers, $answer) {
    static $cache = [];
    $key = implode(",", $numbers) . "|" . $answer;

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    $operators = ['+', '*', '||'];
    $numOperators = count($numbers) - 1;
    $operatorCombinations = generateOperatorCombinations($operators, $numOperators);

    $results = [];
    foreach ($operatorCombinations as $combination) {
        if (evaluateExpression($numbers, $combination) === $answer) {
            $results[] = $combination;
        }
    }

    $cache[$key] = $results;
    return $results;
}

function processRows($rows) {
    $total = 0;

    foreach ($rows as $rowKey => $row) {
        $answer = (int) str_replace(":", "", $row[0]);
        $numbers = array_map('intval', array_slice($row, 1));

        $validCombinations = generateExpressions($numbers, $answer);

        if (!empty($validCombinations)) {
            $total += $answer;
        }
    }

    return $total;
}

// Main Execution
$rows = getRows();
$total = processRows($rows);

echo $total;

?>