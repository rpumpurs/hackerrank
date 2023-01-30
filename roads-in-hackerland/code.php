<?php

/*
 * Complete the 'roadsInHackerland' function below.
 *
 * The function is expected to return a STRING.
 * The function accepts following parameters:
 *  1. INTEGER n
 *  2. 2D_INTEGER_ARRAY roads
 */

function roadsInHackerland($n, $roads): string
{
//    var_dump(decbin(1+4+8));
//    die();

    //set the distance array
    $graph = array();
    foreach ($roads as $road) {
//        $graph[$road[0]][$road[1]] = 2 ** $road[2];
//        $graph[$road[1]][$road[0]] = 2 ** $road[2];
        $graph[$road[0]][$road[1]] = $road[2];
        $graph[$road[1]][$road[0]] = $road[2];
    }

    $sum = '0';
    for ($i = 1; $i < $n; $i++) {
        for ($j = $i + 1; $j <= $n; $j++) {
            list($minD, $path) = dijkstra($graph, $i, $j);
            $binaryArray = '';
            for ($k = 0; $k < count($path) - 1; $k++) {
                $val = $graph[$path[$k]][$path[$k + 1]];
                $binaryArray[$val] = '1';
            }
            $binaryArray = strrev(str_replace(' ', '0', $binaryArray));
            $sum = addBinaryUtil($sum, $binaryArray);
        }
    }

    return $sum;
}

function dijkstra($graph, $a, $b): array
{
    //initialize the array for storing
    $S = array();//the nearest path with its parent and weight
    $Q = array();//the left nodes without the nearest path
    foreach(array_keys($graph) as $val) $Q[$val] = INF;
    $Q[$a] = 0;

    //start calculating
    while(!empty($Q)){
        $min = array_search(min($Q), $Q);//the most min weight
        if($min == $b) break;
        foreach($graph[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
            $Q[$key] = $Q[$min] + $val;
            $S[$key] = array($min, $Q[$key]);
        }
        unset($Q[$min]);
    }

    if (!array_key_exists($b, $S)) {
        echo "No way found from $a to $b" . PHP_EOL;
        return [0, []];
    }

    //list the path
    $path = array();
    $pos = $b;
    while($pos != $a){
        $path[] = $pos;
        $pos = $S[$pos][0];
    }
    $path[] = $a;
    $path = array_reverse($path);

    //print result
//    echo "From $a to $b" . PHP_EOL;
//    echo "The length is " . $S[$b][1] . PHP_EOL;
//    echo "Path is " . implode('->', $path);

    return [$S[$b][1], $path];
}

function addBinaryUtil($a, $b)
{
    $result = ""; // Initialize result
    $s = 0; // Initialize digit sum

    // Traverse both strings starting from last
    // characters
    $i = strlen($a) - 1;
    $j = strlen($b) - 1;
    while ($i >= 0 || $j >= 0 || $s == 1)
    {

        // Compute sum of last digits and carry
        $s += (($i >= 0) ? ord($a[$i]) - ord('0') : 0);
        $s += (($j >= 0) ? ord($b[$j]) - ord('0') : 0);

        // If current digit sum is 1 or 3,
        // add 1 to result
        $result = chr($s % 2 + ord('0')).$result;

        // Compute carry
        $s =(int)($s/2);

        // Move to next digits
        $i--;
        $j--;
    }
    return $result;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$n = intval($first_multiple_input[0]);

$m = intval($first_multiple_input[1]);

$roads = array();

for ($i = 0; $i < $m; $i++) {
    $roads_temp = rtrim(fgets(STDIN));

    $roads[] = array_map('intval', preg_split('/ /', $roads_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = roadsInHackerland($n, $roads);

fwrite($fptr, $result . "\n");

fclose($fptr);
