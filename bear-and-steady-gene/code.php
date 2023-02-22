<?php

/*
 * Complete the 'steadyGene' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts STRING gene as parameter.
 */

function steadyGene($gene) {
    $n = strlen($gene);
    $steadyCount = $n / 4;

    $countsOriginal = [
        'A' => 0,
        'C' => 0,
        'G' => 0,
        'T' => 0,
    ];
    for ($i = 0; $i < $n; $i++) {
        $countsOriginal[$gene[$i]]++;
    }

    if ($countsOriginal['A'] === $steadyCount
        && $countsOriginal['C'] === $steadyCount
        && $countsOriginal['G'] === $steadyCount
        && $countsOriginal['T'] === $steadyCount)
    {
        return 0;
    }

    $min = $n;
    for ($i = 0; $i < $n; $i++) {
        //echo $i . PHP_EOL;
        $countsChanged = [
            'A' => 0,
            'C' => 0,
            'G' => 0,
            'T' => 0,
        ];
        for ($j = $i; $j < $n; $j++) {
            $countsChanged[$gene[$j]]++;
            $substringLength = $j - $i + 1;

            $missingACount = $steadyCount - ($countsOriginal['A'] - $countsChanged['A']);
            if ($missingACount < 0) {
                continue;
            }
            $missingCCount = $steadyCount - ($countsOriginal['C'] - $countsChanged['C']);
            if ($missingCCount < 0) {
                continue;
            }
            $missingGCount = $steadyCount - ($countsOriginal['G'] - $countsChanged['G']);
            if ($missingGCount < 0) {
                continue;
            }
            $missingTCount = $steadyCount - ($countsOriginal['T'] - $countsChanged['T']);
            if ($missingTCount < 0) {
                continue;
            }

            $missingCount = $missingACount + $missingCCount + $missingGCount + $missingTCount;

            if ($substringLength === $missingCount) {
                if (($substringLength) < $min) {
                    $min = $substringLength;
                }
            }
        }
    }

    return $min;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$n = intval(trim(fgets(STDIN)));

$gene = rtrim(fgets(STDIN), "\r\n");

$result = steadyGene($gene);

fwrite($fptr, $result . "\n");

fclose($fptr);
