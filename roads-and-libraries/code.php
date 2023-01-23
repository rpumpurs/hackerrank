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

class DSU{
    private $parent;
    private $size;

    public function __construct(int $n)
    {
        $this->parent = [];
        $this->size = [];

        for($i = 1; $i <= $n; $i++){
            $this->parent[$i] = $i;
            $this->size[$i] = 1;
        }
    }

    public function findParent(int $node): int
    {
        if($this->parent[$node] == $node)
            return $node;
        return $this->parent[$node] = $this->findParent($this->parent[$node]);
    }

    public function union(int $u, int $v): void
    {
        $pu = $this->findParent($u);
        $pv = $this->findParent($v);

        if($pu == $pv)
            return;

        if($this->size[$pu] >= $this->size[$pv]){
            $this->parent[$pv] = $pu;
            $this->size[$pu] += $this->size[$pv];
        }
        else{
            $this->parent[$pu] = $pv;
            $this->size[$pv] += $this->size[$pu];
        }
    }

    public function getSize(int $idx): int
    {
        return $this->size[$idx];
    }
}

function roadsAndLibraries($n, $c_lib, $c_road, $cities) {
    //if ($c_lib <= $c_road) return $n * $c_lib;

    // $dsu = new DSU($n);
    //
    // foreach($cities as $city) {
    //     var_dump($city);
    //     $dsu->union($city[0], $city[1]);
    // }
    //
    // print_r($dsu);

    $roadsAndLibraries = new RoadsAndLibraries;
    $roadsAndLibraries->costRoad = $c_road;
    $roadsAndLibraries->costLibrary = $c_lib;

    $connections = [];
    foreach ($cities as $city)  {
        $roadsAndLibraries->connections[$city[0]][] = $city[1];
        $roadsAndLibraries->connections[$city[1]][] = $city[0];
    }

    $roadsAndLibraries->visit(array_key_first($roadsAndLibraries->connections));

    print_r($roadsAndLibraries->connections);
}

class RoadsAndLibraries
{
    public $connections = [];
    public $visited = [];
    public $costRoad;
    public $costLibrary;

    public function visit($node, $dis = 0)
    {
        var_dump($node);
        $this->visited[$node] = $node;
        foreach($this->connections[$node] as $val) {
            if (!isset($this->visited[$val])) {
                $this->visit($val);
            }
        }
        return 0;
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
