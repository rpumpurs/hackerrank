<?php

/*
 * Complete the 'printShortestPath' function below.
 *
 * The function accepts following parameters:
 *  1. INTEGER n
 *  2. INTEGER i_start
 *  3. INTEGER j_start
 *  4. INTEGER i_end
 *  5. INTEGER j_end
 */

function printShortestPath($n, $i_start, $j_start, $i_end, $j_end) {
    $redKnight = new RedKnight($n, $i_end, $j_end);
    $redKnight->hop($i_start, $j_start);
    if (!$redKnight->isPossible()) {
        echo 'Impossible';
        return;
    }
    $minPath = $redKnight->getMinPath();
    echo count($minPath) . PHP_EOL;
    echo implode(' ', $minPath);
}

class RedKnight
{
    private $boardSize;
    private $destinationI;
    private $destinationJ;
    private $paths = [];
    private $minPathLength = PHP_INT_MAX;

    public function __construct($n, $i, $j)
    {
        $this->boardSize = $n;
        $this->destinationI = $i;
        $this->destinationJ = $j;
    }
    
    public function hop($i, $j, $boardVisited = [], $path = [], $currentMove = '')
    {
        if ($i < 0 || $j < 0 || $i >= $this->boardSize || $j >= $this->boardSize) {
            return;
        }

        if (isset($boardVisited[$i][$j])) {
            return;
        }

        if ($currentMove) {
            $path[] = $currentMove;
        }
        $pathLength = count($path);
        if ($pathLength >= $this->minPathLength) {
            return;
        }

        if ($i === $this->destinationI && $j === $this->destinationJ) {
            $this->minPathLength = $pathLength;
            if (!isset($this->paths[$pathLength])) {
                $this->paths[$pathLength] = $path;
            }
        }

        $boardVisited[$i][$j] = true;

        $this->hop($i - 2, $j - 1, $boardVisited, $path, 'UL');
        $this->hop($i - 2, $j + 1, $boardVisited, $path, 'UR');
        $this->hop($i, $j + 2, $boardVisited, $path, 'R');
        $this->hop($i + 2, $j + 1, $boardVisited, $path, 'LR');
        $this->hop($i + 2, $j - 1, $boardVisited, $path, 'LL');
        $this->hop($i, $j - 2, $boardVisited, $path, 'L');
    }

    public function getMinPath()
    {
        return $this->paths[$this->minPathLength];
    }

    public function isPossible()
    {
        return $this->paths ? true : false;
    }
}

$n = intval(trim(fgets(STDIN)));

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$i_start = intval($first_multiple_input[0]);

$j_start = intval($first_multiple_input[1]);

$i_end = intval($first_multiple_input[2]);

$j_end = intval($first_multiple_input[3]);

printShortestPath($n, $i_start, $j_start, $i_end, $j_end);
