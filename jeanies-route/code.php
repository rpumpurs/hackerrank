<?php

class Dijkstra
{
  protected $graph;

  public function __construct($graph) {
    $this->graph = $graph;
  }

  public function shortestPath($source, $target) {
    // array of best estimates of shortest path to each
    // vertex
    $d = array();
    // array of predecessors for each vertex
    $pi = array();
    // queue of all unoptimized vertices
    $Q = new SplPriorityQueue();

    foreach ($this->graph as $v => $adj) {
      $d[$v] = INF; // set initial distance to "infinity"
      $pi[$v] = null; // no known predecessors yet
      foreach ($adj as $w => $cost) {
        // use the edge cost as the priority
        $Q->insert($w, $cost);
      }
    }

    // initial distance at source is 0
    $d[$source] = 0;

    while (!$Q->isEmpty()) {
      // extract min cost
      $u = $Q->extract();
      if (!empty($this->graph[$u])) {
        // "relax" each adjacent vertex
        foreach ($this->graph[$u] as $v => $cost) {
          // alternate route length to adjacent neighbor
          $alt = $d[$u] + $cost;
          // if alternate route is shorter
          if ($alt < $d[$v]) {
            $d[$v] = $alt; // update minimum length to vertex
            $pi[$v] = $u;  // add neighbor to predecessors
                           //  for vertex
          }
        }
      }
    }

    // we can now find the shortest path using reverse
    // iteration
    $S = new SplStack(); // shortest path with a stack
    $u = $target;
    $dist = 0;
    // traverse from target to source
    while (isset($pi[$u]) && $pi[$u]) {
      $S->push($u);
      $dist += $this->graph[$u][$pi[$u]]; // add distance to predecessor
      $u = $pi[$u];
    }

    return $dist;

    // stack will be empty if there is no route back
    if ($S->isEmpty()) {
      echo "No route from $source to $target";
    }
    else {
      // add the source node and print the path in reverse
      // (LIFO) order
      $S->push($source);
      echo "$dist:";
      $sep = '';
      foreach ($S as $v) {
        echo $sep, $v;
        $sep = '->';
      }
      echo "n";
    }
  }
}

/*
 * Complete the jeanisRoute function below.
 */
function jeanisRoute($k, $roads, $cities) {
    var_dump($k);
    var_dump($roads);
    var_dump($cities);

    $graph = [];
    foreach ($roads as $road)  {
        $graph[$road[0]][$road[1]] = $road[2];
        $graph[$road[1]][$road[0]] = $road[2];
    }

    $g = new Dijkstra($graph);

    pc_permute($cities, $perms = [], $permutations);

    $minDistance = INF;
    foreach($permutations as $permutation) {
        var_dump($permutation);
        $distance = 0;
        for ($i = 0; $i < count($permutation) - 1; $i++) {
            var_dump($g->shortestPath($permutation[$i], $permutation[$i + 1]));
            $distance += $g->shortestPath($permutation[$i], $permutation[$i + 1]);
        }
        var_dump($distance);
        if ($distance < $minDistance) {
            $minDistance = $distance;
        }
    }

    return $minDistance;
    /*
    for ($i = 0; $i < count($cities); $i++) {
        for ($j = 0; $j < count($cities); $j++) {
            var_dump($g->shortestPath($cities[$i], $cities[$j]));
        }
    }*/
}

function pc_permute($items, $perms = array(), &$result) {
    if (empty($items)) {
        $result[] = $perms;
    } else {
        for ($i = count($items) - 1; $i >= 0; --$i) {
             $newitems = $items;
             $newperms = $perms;
             list($foo) = array_splice($newitems, $i, 1);
             array_unshift($newperms, $foo);
             pc_permute($newitems, $newperms, $result);
         }
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
