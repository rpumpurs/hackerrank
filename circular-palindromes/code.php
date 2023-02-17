<?php

/*
 * Complete the 'circularPalindromes' function below.
 *
 * The function is expected to return an INTEGER_ARRAY.
 * The function accepts STRING s as parameter.
 */

function circularPalindromes($s) {
    $result = [];
    $palindrome = new CircularPalindrome($s);
    for ($i = 0; $i < strlen($s); $i++) {
        //$rotatedString = substr($s, $i) . substr($s, 0, $i);
        //$palindrome = new Palindrome($rotatedString);
        //var_dump($i);
        //var_dump($rotatedString);
        $result[] = $palindrome->getLongestPalindrome($i);
    }
    return $result;
}

class CircularPalindrome
{
    protected $string;

    protected $stringLength;

    protected $doubleString;

    protected $doubleStringLength;

    protected $stringHalfLength;

    protected $maxOdds = [];
    protected $maxEvens = [];

    public function __construct($string)
    {
        $this->string = $string;
        $this->stringLength = strlen($string);
        $this->doubleString = $string . $string;
        $this->doubleStringLength = $this->stringLength * 2;
        $this->stringHalfLength = $this->stringLength / 2;
    }

    public function getLongestPalindrome($rotation)
    {
        if (!$this->maxOdds) {
            $this->getMaxOddsPalindromes();
        }
        if (!$this->maxEvens) {
            $this->getMaxEvensPalindromes();
        }
        return max($this->maxEvens[$rotation] * 2, $this->maxOdds[$rotation] * 2 - 1);
    }

    protected function getMaxOddsPalindromes() {
        $l = 0;
        $r = -1;
        $d1 = [];

        $mask = [];
        $maskVal = 0;
        for ($v = 0; $v < $this->stringLength; $v++) {
            if ($v < $this->stringHalfLength) {
                $maskVal++;
            } else if ($v > $this->stringHalfLength) {
                $maskVal--;
            }
            $mask[] = $maskVal;
        }

//        for ($i = 0; $i < $this->stringLength; $i++) {
//            $this->maxOdds[$i] = 0;
//        }

        $rotation = 0;
        //$maxes = [];
        //$heap = new MinMaxHeap();
        $priorityQueue = new SplPriorityQueue();
        for ($i = 0; $i < $this->doubleStringLength; $i++) {
            //echo $i . PHP_EOL;
            $k = $i > $r ? 1 : min($d1[$l + $r - $i], $r - $i);

            while($rotation <= $i - $k && $i + $k < $this->doubleStringLength && $this->doubleString[$i - $k] == $this->doubleString[$i + $k]) {
                $k++;
            }

            $d1[$i] = $k;

            //$maxes[$k][] = $i;
            //$heap->insert($k);;

            if ($i + $k - 1 > $r) {
                $l = $i - $k + 1;
                $r = $i + $k - 1;
            }

            $priorityQueue->insert([$i, $k], $k);

            if ($i >= $this->stringLength) {
                $rotation = $i - $this->stringLength;
                $windowMin = $rotation;
                $windowMax = $i;
                while ($priorityQueue->valid()) {
                    $current = $priorityQueue->current();
                    if ($current[0] > $windowMin && $current[0] <= $windowMax) {
                        //$this->maxOdds[$rotation] = min($current[1], $mask[$i - $current[0]]);
                        $priorityQueue->rewind();
                        break;
                    }
                    $priorityQueue->next();
                }
            }

//            if ($i >= $this->stringLength) {
//                $rotation = $i - $this->stringLength + 1;
//                $this->maxOdds[$rotation] = max($d1);
//                unset($d1[$i - $this->stringLength]);
//            }

//            for ($ii = $rotation; $ii <= $i - $rotation; $ii++) {
//                if ($k < $mask[$i - $ii]) {
//                    $kWithMask = $k;
//                } else {
//                    $kWithMask = $mask[$i - $ii];
//                }
//                if ($kWithMask > $this->maxOdds[$ii]) {
//                    $this->maxOdds[$ii] = $kWithMask;
//                }
//            }
        }
    }

    protected function getMaxEvensPalindromes() {
        $l = 0;
        $r = -1;
        $d2 = [];

        $mask = [];
        $maskVal = -1;
        for ($v = 0; $v < $this->stringLength; $v++) {
            if ($v < $this->stringHalfLength) {
                $maskVal++;
            } else if ($v > $this->stringHalfLength + 1) {
                $maskVal--;
            }

            $mask[] = $maskVal;
        }

        for ($i = 0; $i < $this->stringLength; $i++) {
            $this->maxEvens[$i] = 0;
        }

        $rotation = 0;
        //$maxes = [];
//        for ($i = 0; $i < $this->stringLength; $i++) {
//            $maxes[$i] = new SplPriorityQueue();
//        }
        $priorityQueue = new SplPriorityQueue();
        $heap = new BinaryHeap();
        for ($i = 0; $i < $this->doubleStringLength; $i++) {
            //echo $i . PHP_EOL;
            $k = $i > $r ? 0 : min($d2[$l + $r - $i + 1], $r - $i + 1);

            while($i + $k < $this->doubleStringLength && $i - $k - 1 >= $rotation && $this->doubleString[$i + $k] == $this->doubleString[$i - $k - 1]){
                $k++;
            }

            $d2[$i] = $k;

            if ($i + $k - 1 > $r) {
                $l = $i - $k;
                $r = $i + $k - 1;
            }

            //$maxes[$k][] = $i;

            $priorityQueue->insert([$i, $k], $k);
            $heap->insert([$i, $k]);

            if ($i >= $this->stringLength) {

                $heap->print(0);
                die();

                $rotation = $i - $this->stringLength;
                $windowMin = $rotation;
                $windowMax = $i;
                while (!$heap->isEmpty()) {
                    $current = $heap->peek();
                    //$fromSide = min($current[0]- $windowMin, $windowMax - $current[0]);
                    if ($current[0] > $windowMin && $current[0] <= $windowMax) {
                        //var_dump($i . ' ' . $current[0] . ' ' . $mask[$i - $current[0]]);
                        if (min($current[1], $mask[$i - $current[0]]) > $this->maxEvens[$rotation]) {
                            $this->maxEvens[$rotation] = min($current[1], $mask[$i - $current[0]]);
                        }
                        $fromMiddle = abs($windowMax - (($windowMax - $windowMin) / 2) - $current[0]);

                        $current = $heap->peekNext();;
                        if ($current[0] > $windowMin && $current[0] <= $windowMax) {
                            if (min($current[1], $mask[$i - $current[0]]) > $this->maxEvens[$rotation]) {
                                $this->maxEvens[$rotation] = min($current[1], $mask[$i - $current[0]]);
                            }
                        }

                        break;
                    } else {
                        $heap->extract();
                    }
                    //var_dump($priorityQueue->count());
                    //$priorityQueue->next();
                    //var_dump($priorityQueue->count());
                }
//                while ($priorityQueue->valid()) {
//                    $current = $priorityQueue->extract();
//                    //$fromSide = min($current[0]- $windowMin, $windowMax - $current[0]);
//                    if ($current[0] > $windowMin && $current[0] <= $windowMax) {
//                        //var_dump($i . ' ' . $current[0] . ' ' . $mask[$i - $current[0]]);
//                        if (min($current[1], $mask[$i - $current[0]]) > $this->maxEvens[$rotation]) {
//                            $this->maxEvens[$rotation] = min($current[1], $mask[$i - $current[0]]);
//                        }
//                        $fromMiddle = abs($windowMax - (($windowMax - $windowMin) / 2) - $current[0]);
//
////                        if ($priorityQueue->valid()) {
////                            $priorityQueue->next();
////                            $current = $priorityQueue->current();
////                            if ($current[0] > $windowMin && $current[0] <= $windowMax) {
////                                if (min($current[1], $mask[$i - $current[0]]) > $this->maxEvens[$rotation]) {
////                                    $this->maxEvens[$rotation] = min($current[1], $mask[$i - $current[0]]);
////                                }
////                            }
//                        //}
//
//                        $counter++;
//
//                        if ($counter > 0) {
//                            $priorityQueue->rewind();
//                            break;
//                        }
//                    }
//                    //var_dump($priorityQueue->count());
//                    //$priorityQueue->next();
//                    //var_dump($priorityQueue->count());
//                }
            }

//            for ($ii = $rotation; $ii <= $i - $rotation; $ii++) {
//                if ($k < $mask[$i - $ii]) {
//                    $kWithMask = $k;
//                } else {
//                    $kWithMask = $mask[$i - $ii];
//                }
//                if ($kWithMask > $this->maxEvens[$ii]) {
//                    $this->maxEvens[$ii] = $kWithMask;
//                }
//            }
        }

//        for ($i = 0; $i < $this->stringLength; $i++) {
//            for ($j = $this->stringLength - 1; $j > 0; $j--) {
//                echo $i . PHP_EOL;
//                if ($maxes[$j]) {
//                    $this->maxEvens[$i] = 9;
//                    break;
//                }
//            }
//        }
    }
}

class BinaryHeap
{
    protected $heap;

    public function __construct() {
        $this->heap  = array();
    }

    public function isEmpty() {
        return empty($this->heap);
    }

    public function count() {
        // returns the heapsize
        return count($this->heap) - 1;
    }

    public function peek() {
        if ($this->isEmpty()) {
            throw new RunTimeException('Heap is empty');
        }

        return current($this->heap);
    }

    public function peekNext() {
        if ($this->isEmpty()) {
            throw new RunTimeException('Heap is empty');
        }

        $next = next($this->heap);
        reset($this->heap);
        return $next;
    }

    public function extract() {
//        echo '-----' . PHP_EOL;
//        foreach ($this->heap as $item) {
//            echo $item[1] . PHP_EOL;
//        }

        if ($this->isEmpty()) {
            throw new RunTimeException('Heap is empty');
        }

        // extract the root item
        $root = array_shift($this->heap);

        if (!$this->isEmpty()) {
            // move last item into the root so the heap is
            // no longer disjointed
            $last = array_pop($this->heap);
            array_unshift($this->heap, $last);

            // transform semiheap to heap
            $this->adjust(0);
        }

        return $root;
    }

    public function compare($item1, $item2) {
        if ($item1[1] === $item2[1]) {
            return 0;
        }
        // reverse the comparison to change to a MinHeap!
        return ($item1[1] > $item2[1] ? 1 : -1);
    }

    protected function isLeaf($node) {
        // there will always be 2n + 1 nodes in the
        // sub-heap
        return ((2 * $node) + 1) > $this->count();
    }

    protected function adjust($root) {
        // we've gone as far as we can down the tree if
        // root is a leaf
        if (!$this->isLeaf($root)) {
            $left  = (2 * $root) + 1; // left child
            $right = (2 * $root) + 2; // right child

            // if root is less than either of its children
            $h = $this->heap;
            if (
                (isset($h[$left]) &&
                    $this->compare($h[$root], $h[$left]) < 0)
                || (isset($h[$right]) &&
                    $this->compare($h[$root], $h[$right]) < 0)
            ) {
                // find the larger child
                if (isset($h[$left]) && isset($h[$right])) {
                    $j = ($this->compare($h[$left], $h[$right]) >= 0)
                        ? $left : $right;
                }
                else if (isset($h[$left])) {
                    $j = $left; // left child only
                }
                else {
                    $j = $right; // right child only
                }

                // swap places with root
                list($this->heap[$root], $this->heap[$j]) =
                    array($this->heap[$j], $this->heap[$root]);

                // recursively adjust semiheap rooted at new
                // node j
                $this->adjust($j);
            }
        }
    }

    public function print($root) {
        if (isset($this->heap[$root])) {
            echo $this->heap[$root][1] . PHP_EOL;
        }
        // we've gone as far as we can down the tree if
        // root is a leaf
        if (!$this->isLeaf($root)) {
            $left  = (2 * $root) + 1; // left child
            $right = (2 * $root) + 2; // right child

            $this->print($right);
            $this->print($left);
//            if ($this->compare($this->heap[$left], $this->heap[$right]) >= 0) {
//                $this->print($left);
//                $this->print($right);
//            } else {
//                $this->print($right);
//                $this->print($left);
//            }
        }
    }

    public function insert($item) {
        // insert new items at the bottom of the heap
        $this->heap[] = $item;

        // trickle up to the correct location
        $place = $this->count();
        $parent = floor($place / 2);
        // while not at root and greater than parent
        while (
            $place > 0 && $this->compare(
                $this->heap[$place], $this->heap[$parent]) >= 0
        ) {
            // swap places
            list($this->heap[$place], $this->heap[$parent]) =
                array($this->heap[$parent], $this->heap[$place]);
            $place = $parent;
            $parent = floor($place / 2);
        }
    }
}

class MinMaxHeap {
    protected $list;

    function __construct() {
        $this->list = array();
    }

    public function count() {
        return count($this->list);
    }

    public function peek_min() {
        if ($this->count() == 0) return null;
        $rv = $this->list[0];
        return $rv;
    }
    public function get_min() {
        if ($this->count() == 0) return null;
        $rv = $this->list[0];

        # Remove the last element
        $last = array_pop($this->list);

        if ($this->count() > 0) {
            # Place it in the root
            $this->list[0] = $last;

            # Trickle down
            $this->trickle_down(0);
        }

        # Return the old root
        return $rv;
    }

    public function peek_max() {
        if ($this->count() == 0) return null;
        $max = $this->get_max_id();
        return $this->list[$max];
    }
    public function get_max() {
        if ($this->count() == 0) return null;
        $max = $this->get_max_id();

        # Remove the last element
        $last = array_pop($this->list);

        if ($this->count() > $max) {
            # Place it in the max
            $this->list[$max] = $last;

            # Trickle down
            $this->trickle_down($max);
        }

        # Return the old root
        return $rv;
    }
    private function get_max_id() {
        if ($this->count() == 0) return null;
        $max = 0;
        if ($this->count() >= 2)
            $max = 1; # left root child is definitely bigger
        if ($this->count() >= 3 and $this->compare(2, 1) == 1)
            $max = 2; # maybe right root child is bigger still
        return $max;
    }

    public function insert($val) {
        # Place this value at the end
        $i = array_push($this->list, $val) - 1;

        # Bubble up
        $this->bubble_up($i);
    }

    private function trickle_down($i) {
        $dir = $this->get_level_direction($i);
        $this->trickle_down_r($i, $dir);
    }
    private function trickle_down_r($i, $dir) {
        # If list[i] has children then
        if ($this->count() > $i * 2 + 1) {
            # Find the index of the smallest of children and grandchildren
            $m = $i*2 + 1;
            foreach( array($i*2+2, $i*4+3, $i*4+4, $i*4+5, $i*4+6) as $j ) {
                if (isset($this->list[$j]) and
                    $this->compare($j, $m) == $dir)
                    $m = $j;
            }

            # If m < i
            if ($this->compare($m, $i) == $dir) {
                # If m is a granchild then
                if ($m >= $i*4 + 3) {
                    # Swap list[m] and list[i]
                    $tmp = $this->list[$m];
                    $this->list[$m] = $this->list[$i];
                    $this->list[$i] = $tmp;

                    # If list[m] is now > list[its parent]
                    $mparent = floor(($m-1) / 2);
                    if ($this->compare($m, $mparent) == -$dir) {
                        # Swap list[m] and list[its parent]
                        $tmp = $this->list[$m];
                        $this->list[$m] = $this->list[$mparent];
                        $this->list[$mparent] = $tmp;
                    }

                    # trickle_down_r(m)
                    $this->trickle_down_r($m, $dir);
                    # else, m is a child
                } else {
                    # Swap list[m] and list[i]
                    $tmp = $this->list[$m];
                    $this->list[$m] = $this->list[$i];
                    $this->list[$i] = $tmp;
                }
            }
        }
    }

    private function bubble_up($i) {
        $dir = $this->get_level_direction($i);

        # If list[i] has a parent and list[i] > list[parent]
        $iparent = floor(($i-1) / 2);
        if ($i > 0 and $this->compare($i, $iparent) == -$dir) {
            # swap list[i] and list[parent]
            $tmp = $this->list[$i];
            $this->list[$i] = $this->list[$iparent];
            $this->list[$iparent] = $tmp;

            # bubble_up_r(parent)
            $this->bubble_up_r($iparent, -$dir);
            # else
        } else {
            # bubble_up_r(i)
            $this->bubble_up_r($i, $dir);
        }
    }
    private function bubble_up_r($i, $dir) {
        # If list[i] has grandparent
        if ($i > 2) {
            # If list[i] < list[grandparent]
            $igp = floor((floor(($i-1) / 2)-1) / 2);
            if ($this->compare($i, $igp) == $dir) {
                # swap list[i] and list[grandparent]
                $tmp = $this->list[$i];
                $this->list[$i] = $this->list[$igp];
                $this->list[$igp] = $tmp;

                # bubble_up_r(grandparent)
                $this->bubble_up_r($igp, $dir);
            }
        }
    }

    private function get_level_direction($i) {
        return (floor(log($i+1, 2)) % 2 == 1) ? 1 : -1;
    }

    protected function compare($i, $j) {
        if ($i == $j or $this->list[$i] == $this->list[$j]) return 0;
        return ($this->list[$i] < $this->list[$j]) ? -1 : 1;
    }
}

class Palindrome
{
    /**
     * String
     *
     * @var array
     */
    protected $string;

    /**
     * String length
     *
     * @var integer
     */
    protected $stringLength;

    /**
     * Palindrome constructor.
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->string = $string;
        $this->stringLength = strlen($string);
    }

    /**
     * Get longest palindrome
     *
     * @return integer
     */
    public function getLongestPalindrome()
    {
        return max($this->getMaxEvenPalindrome(), $this->getMaxOddPalindrome());
    }

    /**
     * Get max odd palindrome
     *
     * @return integer
     */
    protected function getMaxOddPalindrome() {
        $l = 0;
        $r = -1;
        $d1 = [];

        for ($i = 0; $i < $this->stringLength; $i++) {
            $k = $i > $r ? 1 : min($d1[$l + $r - $i], $r - $i);

            while(0 <= $i - $k && $i + $k < $this->stringLength && $this->string[$i - $k] == $this->string[$i + $k]) {
                $k++;
            }

            $d1[$i] = $k;

            if ($i + $k - 1 > $r) {
                $l = $i - $k + 1;
                $r = $i + $k - 1;
            }
        }

        $max = max($d1);

        return $max * 2 - 1;
    }

    /**
     * Get max even palindrome
     *
     * @return integer
     */
    protected function getMaxEvenPalindrome() {
        $l = 0;
        $r = -1;
        $d2 = [];

        for ($i = 0; $i < $this->stringLength; $i++) {
            $k = $i > $r ? 0 : min($d2[$l + $r - $i + 1], $r - $i + 1);

            while($i + $k < $this->stringLength && $i - $k - 1 >= 0 && $this->string[$i + $k] == $this->string[$i - $k - 1]){
                $k++;
            }

            $d2[$i] = $k;

            if ($i + $k - 1 > $r) {
                $l = $i - $k;
                $r = $i + $k - 1;
            }
        }

        $max = max($d2);

        return $max * 2;
    }
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$n = intval(trim(fgets(STDIN)));

$s = rtrim(fgets(STDIN), "\r\n");

$result = circularPalindromes($s);

fwrite($fptr, implode("\n", $result) . "\n");

fclose($fptr);
