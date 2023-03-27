<?php

/*
 * Complete the 'pylons' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts following parameters:
 *  1. INTEGER k
 *  2. INTEGER_ARRAY arr
 */

function pylons($k, $arr) {
    $result = 0;
    $currentDistance = 0;
    $lastSuitableCityIndex = -1;
    foreach ($arr as $i => $cityIsSuitable) {
        $currentDistance++;
        if ($cityIsSuitable) {
            $lastSuitableCityIndex = $i;
        }
        if ($k === $currentDistance) {
            if ($lastSuitableCityIndex < 0) {
                return -1;
            }
            $result++;
            $currentDistance = ($i - $lastSuitableCityIndex) - $k + 1;
            $lastSuitableCityIndex = -1;
        }
    }
    if ($currentDistance > 0) {
        $result++;
    }
    return $result;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$n = intval($first_multiple_input[0]);

$k = intval($first_multiple_input[1]);

$arr_temp = rtrim(fgets(STDIN));

$arr = array_map('intval', preg_split('/ /', $arr_temp, -1, PREG_SPLIT_NO_EMPTY));

$result = pylons($k, $arr);

fwrite($fptr, $result . "\n");

fclose($fptr);
