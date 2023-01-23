<?php




/*
 * Complete the jeanisRoute function below.
 */
function jeanisRoute($k, $roads, $cities) {

    $start = $cities[0];

    $c = [];
    foreach ($cities as $city) {
        $c[$city] = $city;
    }
    $cities = $c;

    $jeanisRoute = new JeanisRoute();

    foreach ($roads as $road)  {
        $jeanisRoute->g[$road[0]][$road[1]] = $road[2];
        $jeanisRoute->g[$road[1]][$road[0]] = $road[2];
    }

    print_r($jeanisRoute->g);

    $jeanisRoute->cities = $cities;

    $jeanisRoute->dfs($start, 0);
    return $jeanisRoute->ans[0] - $jeanisRoute->ans[1];
}

class JeanisRoute
{
    public $g = [];
    public $visited = [];
    public $ans = [0, 0];
    public $cities = [];

    public function dfs($node, $dis = 0)
    {
        var_dump('node: ' . $node);
        $this->visited[$node] = $node;
        #var_dump($this->visited);
        $isBelong = isset($this->cities[$node]);
        $minus = [0,0];
        foreach($this->g[$node] as $i => $val) {
            //var_dump($this->g[$node]);
            // var_dump(!isset($this->visited[$i]));
            if (!isset($this->visited[$i])) {
                $d = $this->dfs($i, $dis+$val) - $dis;
                var_dump('d: ' . $d);
                if ($d > 0) {
                    $isBelong = True;
                    $this->ans[0] += 2 * $this->g[$node][$i];
                    var_dump($this->ans[0]);
                    if ($d > $minus[0]) {
                        $minus[1] = max($minus);
                        $minus[0] = $d;
                    } else {
                        $minus[1] = max($minus[1], $d);
                    }
                }
            }
        }
        $this->ans[1] = max($this->ans[1], array_sum($minus));

        # var_dump($this->ans[1]);
        return $isBelong ? $dis + max($minus) : 0;
    }
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
