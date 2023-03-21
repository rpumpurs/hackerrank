<?php

/*
 * Complete the 'intervalSelection' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts 2D_INTEGER_ARRAY intervals as parameter.
 */

function intervalSelection($intervals) {
    usort($intervals, function($a, $b) {
        return $a[1] > $b[1];
    });
    $count = 0;
    $overlaps = [0,0];
    foreach ($intervals as $interval) {
        if ($interval[0] > $overlaps[1]) {
            $count++;
            $overlaps[1] = $interval[1];
        } elseif ($interval[0] > $overlaps[0]) {
            $count++;
            $overlaps[0] = $overlaps[1];
            $overlaps[1] = $interval[1];
        }
    }
    return $count;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$s = intval(trim(fgets(STDIN)));

for ($s_itr = 0; $s_itr < $s; $s_itr++) {
    $n = intval(trim(fgets(STDIN)));

    $intervals = array();

    for ($i = 0; $i < $n; $i++) {
        $intervals_temp = rtrim(fgets(STDIN));

        $intervals[] = array_map('intval', preg_split('/ /', $intervals_temp, -1, PREG_SPLIT_NO_EMPTY));
    }

    $result = intervalSelection($intervals);

    fwrite($fptr, $result . "\n");
}

fclose($fptr);
