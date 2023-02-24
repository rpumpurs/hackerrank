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
    $deviationA = $steadyCount - $countsOriginal['A'];
    if ($deviationA > 0) {
        $deviationPos += $deviationA;
    }
    $deviationC = $steadyCount - $countsOriginal['C'];
    if ($deviationC > 0) {
        $deviationPos += $deviationC;
    }
    $deviationG = $steadyCount - $countsOriginal['G'];
    if ($deviationG > 0) {
        $deviationPos += $deviationG;
    }
    $deviationT = $steadyCount - $countsOriginal['T'];
    if ($deviationT > 0) {
        $deviationPos += $deviationT;
    }

    if ($countsOriginal['A'] === $steadyCount
        && $countsOriginal['C'] === $steadyCount
        && $countsOriginal['G'] === $steadyCount
        && $countsOriginal['T'] === $steadyCount)
    {
        return 0;
    }

    $subLen = $deviationPos;

    $min = $n;
    for ($i = 0; $i < $n; $i++) {
        $countsChanged = [
            'A' => 0,
            'C' => 0,
            'G' => 0,
            'T' => 0,
        ];
        $minADeviation = $n;
        $minCDeviation = $n;
        $minGDeviation = $n;
        $minTDeviation = $n;
        for ($j = $i; $j < $n; $j++) {
            $substringLength = $j - $i + 1;
            $countsChanged[$gene[$j]]++;

            if ($substringLength > $subLen) {
                $countsChanged[$gene[$j - $subLen]]--;
            }

            $missingACount = $steadyCount - ($countsOriginal['A'] - $countsChanged['A']);
            $missingCCount = $steadyCount - ($countsOriginal['C'] - $countsChanged['C']);
            $missingGCount = $steadyCount - ($countsOriginal['G'] - $countsChanged['G']);
            $missingTCount = $steadyCount - ($countsOriginal['T'] - $countsChanged['T']);

            $missingCount = $missingACount + $missingCCount + $missingGCount + $missingTCount;

            if ($missingACount >= 0) {
                $minADeviation = 0;
            }
            if ($missingCCount >= 0) {
                $minCDeviation = 0;
            }
            if ($missingGCount >= 0) {
                $minGDeviation = 0;
            }
            if ($missingTCount >= 0) {
                $minTDeviation = 0;
            }

            if (($v = abs($missingACount)) < $minADeviation) {
                $minADeviation = $v;
            }
            if (($v = abs($missingCCount)) < $minCDeviation) {
                $minCDeviation = $v;
            }
            if (($v = abs($missingGCount)) < $minGDeviation) {
                $minGDeviation = $v;
            }
            if (($v = abs($missingTCount)) < $minTDeviation) {
                $minTDeviation = $v;
            }

            if ($missingACount < 0) {
                continue;
            }
            if ($missingCCount < 0) {
                continue;
            }
            if ($missingGCount < 0) {
                continue;
            }
            if ($missingTCount < 0) {
                continue;
            }

            if ($subLen === $missingCount) {
                return $subLen;
            }
        }

        $subLenAdjustment = max($minADeviation, $minCDeviation, $minGDeviation, $minTDeviation);
        $subLen += $subLenAdjustment === 0 ? 1 : $subLenAdjustment;
    }

    return $min;
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$n = intval(trim(fgets(STDIN)));

$gene = rtrim(fgets(STDIN), "\r\n");

$result = steadyGene($gene);

fwrite($fptr, $result . "\n");

fclose($fptr);
