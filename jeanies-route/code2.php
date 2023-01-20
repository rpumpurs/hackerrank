<?php

/*
 * Complete the jeanisRoute function below.
 */
function jeanisRoute($k, $roads, $cities) {

    $g = [];
    foreach ($roads as $road)  {
        $g[$road[0]][$road[1]] = $road[2];
        $g[$road[1]][$road[0]] = $road[2];
    }

    var_dump($g);

    $start = $cities[0];
    $visited = [];
    $ans = [0, 0];
    function dfs($node, $dis = 0, $g, $visited, $ans) {
        $visited[] = $node;
        $isBelong = isset($cities[$node]);
        $minus = [0,0];
        var_dump($g[$node]);
        foreach($g[$node] as $i => $val) {
            if (!isset($visited[$i])) {
                $d = dfs($i, $dis+$val, $g , $visited, $ans) - $dis;
                if ($d > 0) {
                    $isBelong = True;
                    $ans[0] += 2 * $g[$node][$i];
                    if ($d > $minus[0]) {
                        $minus[1] = max($minus);
                        $minus[0] = $d;
                    } else {
                        $minus[1] = max($minus[1], $d);
                    }
                }
            }
        }
        $ans[1] = max($ans[1], array_sum($minus));
        return $isBelong ? $dis + max($minus) : 0;
    }
    dfs($start, 0, $g, $visited, $ans);
    return $ans[0] - $ans[1];
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $nk_temp);
$nk = explode(' ', $nk_temp);

$n = intval($nk[0]);

$k = intval($nk[1]);

fscanf($stdin, "%[^\n]", $city_temp);

$cities = array_map('intval', preg_split('/ /', $city_temp, -1, PREG_SPLIT_NO_EMPTY));

$roads = array();

for ($roads_row_itr = 0; $roads_row_itr < $n-1; $roads_row_itr++) {
    fscanf($stdin, "%[^\n]", $roads_temp);
    $roads[] = array_map('intval', preg_split('/ /', $roads_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = jeanisRoute($k, $roads, $cities);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);
