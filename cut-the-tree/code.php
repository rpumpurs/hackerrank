<?php

/*
 * Complete the 'cutTheTree' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts following parameters:
 *  1. INTEGER_ARRAY data
 *  2. 2D_INTEGER_ARRAY edges
 */

function cutTheTree($data, $edges) {
    $tree = new Tree();
    foreach ($data as $item) {
        $tree->addData($item);
    }
    foreach ($edges as $edge) {
        $tree->addEdge($edge);
    }

    $tree->calculateNodeWeights(1);
    $tree->resetVisited();
    $tree->findMin(1, 0);

    return $tree->getMin();
}

class Tree
{
    private $array = [[]];

    private $min = PHP_INT_MAX;

    private $visited = [];

    public function addData($item)
    {
        $this->array[] = [$item];
    }

    public function addEdge($edge)
    {
        $this->array[$edge[0]][1][] = $edge[1];
        $this->array[$edge[1]][1][] = $edge[0];
    }

    public function calculateNodeWeights($root)
    {
        $this->visited[$root] = true;
        $sumAfter = 0;
        foreach ($this->array[$root][1] as $nextNode) {
            if (!isset($this->visited[$nextNode])) {
                $after = $this->calculateNodeWeights($nextNode);
                $sumAfter += $after;
            }
        }

        $sumAfter += $this->array[$root][0];

        $this->array[$root][2] = $sumAfter;

        return $sumAfter;
    }

    public function findMin($root, $sumBefore)
    {
        $diff = abs($this->array[$root][2] - $sumBefore);
        if ($diff < $this->min) {
            $this->min = $diff;
        }
        $this->visited[$root] = true;
        foreach ($this->array[$root][1] as $nextNode) {
            if (!isset($this->visited[$nextNode])) {
                $this->findMin($nextNode, $sumBefore + ($this->array[$root][2] - $this->array[$nextNode][2]));
            }
        }
    }

    public function resetVisited()
    {
        $this->visited = [];
    }

    public function getMin()
    {
        return $this->min;
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$n = intval(trim(fgets(STDIN)));

$data_temp = rtrim(fgets(STDIN));

$data = array_map('intval', preg_split('/ /', $data_temp, -1, PREG_SPLIT_NO_EMPTY));

$edges = array();

for ($i = 0; $i < ($n - 1); $i++) {
    $edges_temp = rtrim(fgets(STDIN));

    $edges[] = array_map('intval', preg_split('/ /', $edges_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = cutTheTree($data, $edges);

fwrite($fptr, $result . "\n");

fclose($fptr);
