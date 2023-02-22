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

    $deviationPos = 0;
    $deviationNeg = 0;
    $deviationA = $steadyCount - $countsOriginal['A'];
    if ($deviationA > 0) {
        $deviationPos += $deviationA;
    } else {
        $deviationNeg += $deviationA;
    }
    $deviationC = $steadyCount - $countsOriginal['C'];
    if ($deviationC > 0) {
        $deviationPos += $deviationC;
    } else {
        $deviationNeg += $deviationC;
    }
    $deviationG = $steadyCount - $countsOriginal['G'];
    if ($deviationG > 0) {
        $deviationPos += $deviationG;
    } else {
        $deviationNeg += $deviationG;
    }
    $deviationT = $steadyCount - $countsOriginal['T'];
    if ($deviationT > 0) {
        $deviationPos += $deviationT;
    } else {
        $deviationNeg += $deviationT;
    }
//    echo 'deviationA ' . $deviationA . PHP_EOL;
//    echo 'deviationC ' . $deviationC . PHP_EOL;
//    echo 'deviationG ' . $deviationG . PHP_EOL;
//    echo 'deviationT ' . $deviationT . PHP_EOL;
//    echo 'deviationPos ' . $deviationPos . PHP_EOL;
//    echo 'deviationNeg ' . $deviationNeg . PHP_EOL;
//    die();

    if ($countsOriginal['A'] === $steadyCount
        && $countsOriginal['C'] === $steadyCount
        && $countsOriginal['G'] === $steadyCount
        && $countsOriginal['T'] === $steadyCount)
    {
        return 0;
    }

    $min = $n;
    for ($i = 0; $i < $n; $i++) {
        echo $i . ' ' . $min . PHP_EOL;
        $countsChanged = [
            'A' => 0,
            'C' => 0,
            'G' => 0,
            'T' => 0,
        ];
        for ($j = $i; $j < $n; $j++) {
            $substringLength = $j - $i + 1;
            if ($substringLength >= $min) {
                break;
            }
            $countsChanged[$gene[$j]]++;
            if ($substringLength < $deviationPos) {
                continue;
            }

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
