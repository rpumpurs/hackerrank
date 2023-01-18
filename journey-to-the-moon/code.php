<?php

/*
 * Complete the 'journeyToMoon' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts following parameters:
 *  1. INTEGER n
 *  2. 2D_INTEGER_ARRAY astronaut
 */


// https://cp-algorithms.com/data_structures/disjoint_set_union.html

class DSU{
    private $parent;
    private $size;

    public function __construct(int $n)
    {
        $this->parent = [];
        $this->size = [];

        for($i = 0; $i < $n; $i++){
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

function journeyToMoon($n, $astronaut) {

    $dsu = new DSU($n);

    foreach($astronaut as $pair)
        $dsu->union($pair[0], $pair[1]);

    $set = [];
    $ans = 0;
    $prevComponent = 0;
    for($i = 0; $i < $n; $i++){
        $pi = $dsu->findParent($i);
        if(isset($set[$pi]))
            continue;
        $set[$pi] = $pi;
        $size = $dsu->getSize($pi);
        $ans += $size*(max($n-$size-$prevComponent,0));
        $prevComponent += $size;
    }
    return $ans;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$n = intval($first_multiple_input[0]);

$p = intval($first_multiple_input[1]);

$astronaut = array();

for ($i = 0; $i < $p; $i++) {
    $astronaut_temp = rtrim(fgets(STDIN));

    $astronaut[] = array_map('intval', preg_split('/ /', $astronaut_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = journeyToMoon($n, $astronaut);

fwrite($fptr, $result . "\n");

fclose($fptr);
