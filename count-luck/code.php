<?php

/*
 * Complete the 'countLuck' function below.
 *
 * The function is expected to return a STRING.
 * The function accepts following parameters:
 *  1. STRING_ARRAY matrix
 *  2. INTEGER k
 */

function countLuck($matrix, $k) {
    $startX = -1;
    $startY = -1;
    $endX = -1;
    $endY = -1;
    foreach ($matrix as $i => $row) {
        $pos = strpos($row, 'M');
        if ($pos !== false) {
            $startX = $pos;
            $startY = $i;
        }
        $pos = strpos($row, '*');
        if ($pos !== false) {
            $endX = $pos;
            $endY = $i;
        }
    }

    $countLuck = new CountLuck($matrix, $endX, $endY);
    $countLuck->step($startX, $startY);
    return $countLuck->getActualGuessCount() === $k ? 'Impressed' : 'Oops!';
}

class CountLuck
{
    private $matrix;
    private $endX;
    private $endY;
    private $visited = [];
    private $actualGuessCount = 0;

    public function __construct($matrix, $endX, $endY)
    {
        $this->matrix = $matrix;
        $this->endX = $endX;
        $this->endY = $endY;

    }

    public function step($x, $y, $guessCount = 0)
    {
        if ($x === $this->endX && $y === $this->endY) {
            $this->actualGuessCount = $guessCount;
        }

        $this->visited[$x][$y] = true;

        $emptyDirections = 0;
        if ($isUpEmpty = $this->isEmpty($x, $y - 1)) {
            $emptyDirections++;
        }
        if ($isRightEmpty = $this->isEmpty($x + 1, $y)) {
            $emptyDirections++;
        }
        if ($isDownEmpty = $this->isEmpty($x, $y + 1)) {
            $emptyDirections++;
        }
        if ($isLeftEmpty = $this->isEmpty($x - 1, $y)) {
            $emptyDirections++;
        }
        if ($emptyDirections > 1) {
            $guessCount++;
        }
        if ($isUpEmpty) {
            $this->step($x, $y - 1, $guessCount);
        }
        if ($isRightEmpty) {
            $this->step($x + 1, $y, $guessCount);
        }
        if ($isDownEmpty) {
            $this->step($x, $y + 1, $guessCount);
        }
        if ($isLeftEmpty) {
            $this->step($x - 1, $y, $guessCount);
        }
    }

    private function isEmpty($x, $y)
    {
        if (isset($this->visited[$x][$y])) {
            return false;
        }

        if ($x < 0 || $y < 0 || $x > strlen($this->matrix[0]) - 1 || $y > count($this->matrix) - 1) {
            return false;
        }

        return in_array($this->matrix[$y][$x], ['.', '*']);
    }

    public function getActualGuessCount()
    {
        return $this->actualGuessCount;
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$t = intval(trim(fgets(STDIN)));

for ($t_itr = 0; $t_itr < $t; $t_itr++) {
    $first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

    $n = intval($first_multiple_input[0]);

    $m = intval($first_multiple_input[1]);

    $matrix = array();

    for ($i = 0; $i < $n; $i++) {
        $matrix_item = rtrim(fgets(STDIN), "\r\n");
        $matrix[] = $matrix_item;
    }

    $k = intval(trim(fgets(STDIN)));

    $result = countLuck($matrix, $k);

    fwrite($fptr, $result . "\n");
}

fclose($fptr);
