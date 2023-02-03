<?php

/*
 * Complete the 'roadsInHackerland' function below.
 *
 * The function is expected to return a STRING.
 * The function accepts following parameters:
 *  1. INTEGER n
 *  2. 2D_INTEGER_ARRAY roads
 */

global $giantMap;
$giantMap = [];

function roadsInHackerland($n, $roads): string
{
    //set the distance array
    $graph = array();
    $graph2 = array();
    foreach ($roads as $road) {
        if (!isset($graph[$road[0]][$road[1]]) || (2 ** $road[2] < $graph2[$road[0]][$road[1]])) {
            $graph[$road[0]][$road[1]] = array($road[2] => true);
            $graph[$road[1]][$road[0]] = array($road[2] => true);

            $graph2[$road[0]][$road[1]] = 2 ** $road[2];
            $graph2[$road[1]][$road[0]] = 2 ** $road[2];
        }
    }

    $sum = '0';
    for ($i = 1; $i < $n; $i++) {
        for ($j = $i + 1; $j <= $n; $j++) {
            list($minD, $path) = dijkstra($graph, $i, $j);
            for ($k = 0; $k < count($path); $k++) {
                //$giantMap[] =
            }
            $binaryArray = '';
            foreach ($minD as $key => $isTrue) {
                if ($key >= 0)
                $binaryArray[$key] = '1';
            }
            $binaryArray = strrev(str_replace(' ', '0', $binaryArray));
            $sum = addBinaryUtil($sum, $binaryArray);
        }
    }

    return $sum;
}

function addBinaryArrays(array $a, array $b): array
{
    return $a + $b;
}

function firstBinaryArraySmaller(array $a, array $b): bool
{
    $aa = [];
    foreach($a as $key => $item) {
        $aa[] = $key;
    }

    $bb = [];
    foreach($b as $key => $item) {
        $bb[] = $key;
    }

    $diff1 = array_diff($aa,$bb);
    if (!$diff1) {
        return false;
    }
    $diff2 = array_diff($bb,$aa);
    if (!$diff2) {
        return false;
    }

    return max($diff1) < max($diff2);
}
function minBinaryArrayArrayKey(array $arrayOfBinaryArrays): int
{
    $min = array_key_first($arrayOfBinaryArrays);
    foreach ($arrayOfBinaryArrays as $i => $binaryArray) {
        if (firstBinaryArraySmaller($binaryArray, $arrayOfBinaryArrays[$min])) {
            $min = $i;
        }
    }
    return $min;
}


function dijkstra($graph, $a, $b): array
{
    if (isset($giantMap[$a][$b])) {
        die('aa');
        return $giantMap;
    }

    //initialize the array for storing
    $S = array();//the nearest path with its parent and weight
    $Q = array();//the left nodes without the nearest path
    foreach(array_keys($graph) as $val) $Q[$val] = array(200001 => true);
    $Q[$a] = array(-1 => true);

    //start calculating
    while(!empty($Q)){
        $min = minBinaryArrayArrayKey($Q);//the most min weight
        if($min == $b) break;
        foreach($graph[$min] as $key=>$val) {
            $binaryArraySum = addBinaryArrays($Q[$min], $val);
            if(!empty($Q[$key]) && firstBinaryArraySmaller($binaryArraySum, $Q[$key])) {
                $Q[$key] = $binaryArraySum;
                $S[$key] = array($min, $Q[$key]);
            }
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

    foreach ($path as $node) {
        $giantMap[$a][$node] = $S[$node][1];
    }

    $path[] = $a;
    $path = array_reverse($path);

    //print result
//    echo "From $a to $b" . PHP_EOL;
//    echo "The length is " . $S[$b][1] . PHP_EOL;
    echo "Path is " . implode('->', $path) . PHP_EOL;

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
