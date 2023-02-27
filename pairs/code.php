<?php

/*
 * Complete the 'pairs' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts following parameters:
 *  1. INTEGER k
 *  2. INTEGER_ARRAY arr
 */

function pairs($k, $arr) {
    rsort($arr);
    $result = 0;
    $n = count($arr);
    for ($i = 0; $i < $n; $i++) {
        if ($i < $n - 1) {
            $top = count($arr) -1;
            $bot = $i + 1;
            $searchValue = $arr[$i] - $k;
            while ($top >= $bot) {
                $p = floor(($top + $bot) / 2);
                if ($arr[$p] > $searchValue) {
                    $bot = $p + 1;
                } elseif ($arr[$p] < $searchValue) {
                    $top = $p - 1;
                } else {
                    $result++;
                    break;
                }
            }
        }
    }
    return $result;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$n = intval($first_multiple_input[0]);

$k = intval($first_multiple_input[1]);

$arr_temp = rtrim(fgets(STDIN));

$arr = array_map('intval', preg_split('/ /', $arr_temp, -1, PREG_SPLIT_NO_EMPTY));

$result = pairs($k, $arr);

fwrite($fptr, $result . "\n");

fclose($fptr);
