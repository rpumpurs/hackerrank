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
    if (($i_start - $i_end) % 2 !== 0) {
        echo 'Impossible';
        return;
    }

    $redKnight = new RedKnight($n, $i_start, $j_start, $i_end, $j_end);
    $a = $redKnight->hop($i_start, $j_start);

    if (!$a) {
        echo 'Impossible';
        return;
    }
    echo $a[2] . PHP_EOL;
    echo implode(' ', $a[3]);
}

class RedKnight
{
    private $boardSize;
    private $boardVisited = [];
    private $destinationI;
    private $destinationJ;
    private $queue = [];

    public function __construct($n, $iStart, $jStart, $iEnd, $jEnd)
    {
        $this->boardSize = $n;
        $this->destinationI = $iEnd;
        $this->destinationJ = $jEnd;
        $this->queue[] = [$iStart, $jStart, 0, []];
    }

    public function hop($i, $j, $boardVisited = [], $path = [], $currentMove = '', $jDeviation = 0)
    {
        $dI = [-2, -2, 0, 2, 2, 0];
        $dJ = [-1, 1, 2, 1, -1, -2];
        $dDirection = ['UL', 'UR', 'R', 'LR', 'LL', 'L'];

        while ($this->queue) {
            $t = array_shift($this->queue);

            if ($t[0] == $this->destinationI && $t[1] == $this->destinationJ) {
                return $t;
            }

            for ($ii = 0; $ii < 6; $ii++) {
                $i = $t[0] + $dI[$ii];
                $j = $t[1] + $dJ[$ii];

                if ($this->isInside($i, $j) && (!isset($this->boardVisited[$i][$j]))) {
                    $this->boardVisited[$i][$j] = true;
                    $path = array_reverse(array_reverse($t[3]));
                    $path[] = $dDirection[$ii];
                    $this->queue[] = [$i, $j, $t[2] + 1, $path];
                }
            }
        }
        return null;
    }

    private function isInside($x, $y){
        if (($x >= 0) && ($x < $this->boardSize) && ($y >= 0) && ($y <= $this->boardSize))
            return true;
        return false;
    }
}

$n = intval(trim(fgets(STDIN)));

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$i_start = intval($first_multiple_input[0]);

$j_start = intval($first_multiple_input[1]);

$i_end = intval($first_multiple_input[2]);

$j_end = intval($first_multiple_input[3]);

printShortestPath($n, $i_start, $j_start, $i_end, $j_end);
