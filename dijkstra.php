<?php

function dijkstra($graph, $a, $b): array
{
    //initialize the array for storing
    $S = array();//the nearest path with its parent and weight
    $Q = array();//the left nodes without the nearest path
    foreach(array_keys($graph) as $val) $Q[$val] = INF;
    $Q[$a] = 0;

    //start calculating
    while(!empty($Q)){
        $min = array_search(min($Q), $Q);//the most min weight
        if($min == $b) break;
        foreach($graph[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
            $Q[$key] = $Q[$min] + $val;
            $S[$key] = array($min, $Q[$key]);
        }
        unset($Q[$min]);
    }

    if (!array_key_exists($b, $S)) {
        echo "No way found from $a to $b" . PHP_EOL;
        return [0, []];
    }

    //list the path
    $path = array();
    $pos = $b;
    while($pos != $a){
        $path[] = $pos;
        $pos = $S[$pos][0];
    }
    $path[] = $a;
    $path = array_reverse($path);

    //print result
    echo "From $a to $b" . PHP_EOL;
    echo "The length is " . $S[$b][1] . PHP_EOL;
    echo "Path is " . implode('->', $path) . PHP_EOL;

    return [$S[$b][1], $path];
}