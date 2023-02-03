<?php


/*
 * Complete the 'roadsInHackerland' function below.
 *
 * The function is expected to return a STRING.
 * The function accepts following parameters:
 *  1. INTEGER n
 *  2. 2D_INTEGER_ARRAY roads
 */

class RoadsInHackerLand
{
    private $edges = [];
    private $parent = [];
    private $tree = [];
    private $n;
    private $ans = [];
    public function run($n, $roads)
    {
        $this->n = $n;
        $this->parent = range(0, $n - 1);
        for ($i = 0; $i < count($roads) * 2; $i++) {
            $this->ans[] = 0;
        }

        $edges = [];
        foreach ($roads as $road) {
            $edges[] = [$road[0] - 1, $road[1] - 1, $road[2]];
        }

        # build MST
        array_multisort(array_column($edges, 2), SORT_ASC, $edges);

        foreach ($edges as $edge) {
            $a = $edge[0];
            $b = $edge[1];
            $c = $edge[2];
            if (!$this->is_connected($a, $b)) {
                $this->union($a, $b);
                $this->tree[$a][] = [$b, $c];
                $this->tree[$b][] = [$a, $c];
            }
        }

        $this->dfs(0);

        $sum = '0';
        $res = 0;
        foreach ($this->ans as $i => $ans) {
//            $binaryArray = '';
//            $binaryArray[$i] = '1';
//            $binaryArray = strrev(str_replace(' ', '0', $binaryArray));

            // $sum = gmp_add($sum, gmp_mul($ans, gmp_pow(2, $i)));

            $sum = bcadd($sum, bcmul($ans, bcpow(2, $i)));

            //$res += $this->ans[$i] * (1 << $i);
        }

        return $this->dec2bin_i($sum);
    }

    private function dec2bin_i($decimal_i)
    {
        bcscale(0);

        $binary_i = '';
        do
        {
            $binary_i = bcmod($decimal_i,'2') . $binary_i;
            $decimal_i = bcdiv($decimal_i,'2');
        } while (bccomp($decimal_i,'0'));

        return($binary_i);
    }

    # Run DFS to count the number of times an edge is used
        # as weights of all edges is different, hence each weight maps to a particular children
    private function dfs($src, $p = -1)
    {
        $total = 1;
        foreach ($this->tree[$src] as $treeNode) {
            $v = $treeNode[0];
            $c = $treeNode[1];
            if ($v != $p) {
                $children = $this->dfs($v, $src);

                # children => nodes right to edge, n - children => nodes left to edge
                if (!isset($this->ans[$c])) $this->ans[$c] = 0;
                $this->ans[$c] += ($this->n - $children) * $children;

                $total += $children;
            }
        }
        return $total;
    }

    private function find($i)
    {
        while ($i != $this->parent[$this->parent[$i]]) {
            $this->parent[$i] = $this->parent[$this->parent[$i]];
            $i = $this->parent[$i];
        }
        return $i;
    }

    private function union($x, $y)
    {
        $p_x = $this->find($x);
        $p_y = $this->find($y);
        $this->parent[$p_y] = $p_x;
    }

    private function is_connected($x, $y): bool
    {
        $p_x = $this->find($x);
        $p_y = $this->find($y);
        return $p_x == $p_y;
    }

    private function addBinaryUtil($a, $b)
    {
        $result = ""; // Initialize result
        $s = 0; // Initialize digit sum

        // Traverse both strings starting from last
        // characters
        $i = strlen($a) - 1;
        $j = strlen($b) - 1;
        while ($i >= 0 || $j >= 0 || $s == 1)
        {

            // Compute sum of last digits and carry
            $s += (($i >= 0) ? ord($a[$i]) - ord('0') : 0);
            $s += (($j >= 0) ? ord($b[$j]) - ord('0') : 0);

            // If current digit sum is 1 or 3,
            // add 1 to result
            $result = chr($s % 2 + ord('0')).$result;

            // Compute carry
            $s =(int)($s/2);

            // Move to next digits
            $i--;
            $j--;
        }
        return $result;
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$n = intval($first_multiple_input[0]);

$m = intval($first_multiple_input[1]);

$roads = array();

for ($i = 0; $i < $m; $i++) {
    $roads_temp = rtrim(fgets(STDIN));

    $roads[] = array_map('intval', preg_split('/ /', $roads_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$r = new RoadsInHackerLand();
$result = $r->run($n, $roads);

fwrite($fptr, $result . "\n");

fclose($fptr);