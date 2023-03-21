<?php

/*
 * Complete the 'intervalSelection' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts 2D_INTEGER_ARRAY intervals as parameter.
 */

function intervalSelection($intervals) {
    $intersections = [];
    foreach ($intervals as $interval) {
        $intersections[$interval[0]] = 1;
        $intersections[$interval[1]] = 1;
    }
    $overlapCountAtIntersectionMax = 0;
    foreach ($intersections as $intersection => $_) {
        $overlapCountAtIntersection = 0;
        foreach ($intervals as $interval) {
            if (isOverlap($intersection, $intersection, $interval[0], $interval[1])) {
                $overlapCountAtIntersection++;
            }
        }
        if ($overlapCountAtIntersection > $overlapCountAtIntersectionMax) {
            $overlapCountAtIntersectionMax = $overlapCountAtIntersection;
        }
    }

    if ($overlapCountAtIntersectionMax <= 2) {
        return count($intervals);
    }

    $overlapCounts = [];
    $maxOverlapCount = 0;
    $maxOverlapIndex = -1;
    foreach ($intervals as $i => $intervalOuter) {
        $overlapCounts[$i] = 0;
        foreach ($intervals as $j => $intervalInner) {
            if ($i === $j) continue;
            if (isOverlap($intervalOuter[0], $intervalOuter[1], $intervalInner[0], $intervalInner[1])) {
                $overlapCounts[$i]++;
                if ($overlapCounts[$i] > $maxOverlapCount) {
                    $maxOverlapCount = $overlapCounts[$i];
                    $maxOverlapIndex = $i;
                }
            }
        }
    }

    unset($intervals[$maxOverlapIndex]);
    return intervalSelection($intervals);
}

function isOverlap($start_one,$end_one,$start_two,$end_two) {

    return $start_one <= $end_two && $end_one >= $start_two;
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

//    if ($s_itr === 32) {
//        print_r($intervals);
//    }
    $result = intervalSelection($intervals);

    fwrite($fptr, $result . "\n");
}

fclose($fptr);
