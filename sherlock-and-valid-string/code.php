<?php

/*
 * Complete the 'isValid' function below.
 *
 * The function is expected to return a STRING.
 * The function accepts STRING s as parameter.
 */

function isValid($s) {
    $map = [];
    for ($i = 0; $i < strlen($s); $i++) {
        if (!isset($map[$s[$i]])) $map[$s[$i]] = 0;

        $map[$s[$i]]++;
    }

    $countMap = [];
    foreach ($map as $count) {
        if (!isset($countMap[$count])) $countMap[$count] = 0;

        $countMap[$count]++;

        if (count($countMap) > 2) return 'NO';
    }

    if (count($countMap) === 2) {
        $counts = array_values($countMap);
        $occurrences = array_keys($countMap);

        if (($counts[0] === 1) && (($occurrences[0] === 1) || ($occurrences[0] - $occurrences[1] === 1))) return 'YES';

        if (($counts[1] === 1) && (($occurrences[1] === 1) || ($occurrences[1] - $occurrences[0] === 1))) return 'YES';

        return 'NO';
    }

    return 'YES';
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$s = rtrim(fgets(STDIN), "\r\n");

$result = isValid($s);

fwrite($fptr, $result . "\n");

fclose($fptr);
