<?php

/*
 * Complete the 'roadsAndLibraries' function below.
 *
 * The function is expected to return a LONG_INTEGER.
 * The function accepts following parameters:
 *  1. INTEGER n
 *  2. INTEGER c_lib
 *  3. INTEGER c_road
 *  4. 2D_INTEGER_ARRAY cities
 */

function roadsAndLibraries($n, $c_lib, $c_road, $cities) {
    if ($c_lib <= $c_road) return $n * $c_lib;

    $roadsAndLibraries = new RoadsAndLibraries;

    foreach ($cities as $city)  {
        $roadsAndLibraries->connections[$city[0]][] = $city[1];
        $roadsAndLibraries->connections[$city[1]][] = $city[0];
    }

    $groups = 0;
    for ($i = 1; $i < $n + 1; $i++) {
        if (!isset($roadsAndLibraries->visited[$i])) {
            $groups++;
            $roadsAndLibraries->visit($i);
        }
    }

    return min(($n-$groups)*$c_road + $groups*$c_lib, $c_lib*$n);
}

class RoadsAndLibraries
{
    public $visited = [];
    public $connections = [];

    public function visit($node)
    {
        $this->visited[$node] = $node;
        if (isset($this->connections[$node]))
        foreach($this->connections[$node] as $val) {
            if (!isset($this->visited[$val])) {
                $this->visit($val);
            }
        }
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$q = intval(trim(fgets(STDIN)));

for ($q_itr = 0; $q_itr < $q; $q_itr++) {
    $first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

    $n = intval($first_multiple_input[0]);

    $m = intval($first_multiple_input[1]);

    $c_lib = intval($first_multiple_input[2]);

    $c_road = intval($first_multiple_input[3]);

    $cities = array();

    for ($i = 0; $i < $m; $i++) {
        $cities_temp = rtrim(fgets(STDIN));

        $cities[] = array_map('intval', preg_split('/ /', $cities_temp, -1, PREG_SPLIT_NO_EMPTY));
    }

    $result = roadsAndLibraries($n, $c_lib, $c_road, $cities);

    fwrite($fptr, $result . "\n");
}

fclose($fptr);
